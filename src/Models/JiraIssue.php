<?php

namespace JiraWebhookData\Models;

use JiraWebhookData\Exceptions\JiraWebhookDataException;

/**
 * Class that parses JIRA issue data and gives access to it.
 *
 * @credits https://github.com/kommuna
 * @author  Chewbacca chewbacca@devadmin.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class JiraIssue
{
    /**
     * JIRA issue id
     */
    protected int $id;

    /**
     * JIRA issue self URL
     */
    protected string $self;

    /**
     * JIRA issue key
     */
    protected string $key;

    /**
     * JIRA issue url
     */
    protected ?string $url;

    /**
     * JIRA issue type name
     */
    protected string $issueTypeName;

    /**
     * JIRA issue project key
     */
    protected ?string $projectKey;

    /**
     * JIRA issue project name
     */
    protected ?string $projectName;

    /**
     * JIRA issue priority
     */
    protected string $priorityName;

    /**
     * Array of JIRA issue labels
     */
    protected array $labels = [];

    /**
     * JiraWebhook\Models\JiraUser
     */
    protected ?JiraUser $assignee;

    /**
     * JIRA issue status
     */
    protected ?string $statusName;

    /**
     * JIRA issue summary
     */
    protected ?string $summary;

    /**
     * JiraWebhook\Models\JiraIssueComments
     */
    protected JiraIssueComments $issueComments;

    /**
     * All Jira issue fields
     */
    protected array $fields = [];

    /**
     * Parsing JIRA issue $data
     *
     * @throws JiraWebhookDataException
     */
    public static function parse(array $data = null): self
    {
        $issueData = new self;

        if (!$data) {
            return $issueData;
        }

        $issueData->validate($data);

        $issueFields = $data['fields'];
        $issueData->setFields($issueFields);

        $issueData->setId((int) $data['id']);
        $issueData->setSelf($data['self']);
        $issueData->setKey($data['key']);
        $issueData->setUrl($data['key'], $data['self']);
        $issueData->setIssueTypeName($issueFields['issuetype']['name']);
        $issueData->setProjectKey($issueFields['project']['key'] ?? null);
        $issueData->setProjectName($issueFields['project']['name'] ?? null);
        $issueData->setPriorityName($issueFields['priority']['name']);
        $issueData->setLabels($issueFields['labels'] ?? []);
        $issueData->setAssignee(empty($issueFields['assignee']) ? null : JiraUser::parse($issueFields['assignee']));
        $issueData->setStatusName($issueFields['status']['name'] ?? null);
        $issueData->setSummary($issueFields['summary'] ?? null);
        $issueData->setIssueComments(JiraIssueComments::parse($data['fields']['comment'] ?? []));

        return $issueData;
    }

    /**
     * @throws JiraWebhookDataException
     */
    public function validate(array $data): void
    {
        if (empty($data['id'])) {
            throw new JiraWebhookDataException('JIRA issue id does not exist!');
        }

        if (empty($data['self'])) {
            throw new JiraWebhookDataException('JIRA issue self URL does not exist!');
        }

        if (empty($data['key'])) {
            throw new JiraWebhookDataException('JIRA issue key does not exist!');
        }

        if (empty($data['fields'])) {
            throw new JiraWebhookDataException('JIRA issue fields does not exist!');
        }

        if (empty($data['fields']['issuetype']['name'])) {
            throw new JiraWebhookDataException('JIRA issue type does not exist!');
        }

        if (empty($data['fields']['priority']['name'])) {
            throw new JiraWebhookDataException('JIRA issue priority does not exist!');
        }
    }

    /**
     * Check JIRA issue priority is Blocker
     */
    public function isPriorityBlocker(): bool
    {
        return strtolower($this->getPriorityName()) === 'blocker';
    }

    /**
     * Check JIRA issue type is Operations
     */
    public function isTypeOperations(): bool
    {
        return str_contains(strtolower($this->getIssueTypeName()), 'operations');
    }

    /**
     * Check JIRA issue type is Urgent bug
     */
    public function isTypeUrgentBug(): bool
    {
        return str_contains(strtolower($this->getIssueTypeName()), 'urgent bug');
    }

    /**
     * Check JIRA issue type is Server
     */
    public function isTypeServer(): bool
    {
        return str_contains(strtolower($this->getIssueTypeName()), 'server');
    }

    /**
     * Check JIRA issue status is Resolved
     */
    public function isStatusResolved(): bool
    {
        // This is cause in devadmin JIRA status 'Resolved' has japanese symbols
        return str_contains(strtolower($this->getStatusName()), 'resolved');
    }

    /**
     * Check if JIRA issue status is Closed
     */
    public function isStatusClosed(): bool
    {
        return strtolower($this->getStatusName()) === 'closed';
    }

    /**************************************************/

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setSelf(string $self): void
    {
        $this->self = $self;
    }

    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    /**
     * Sets the web based url of an issue
     */
    public function setUrl(string $key, string $self): void
    {
        $url = parse_url($self);
        $this->url = $url['scheme'] . '://' . $url['host'] . '/browse/' . $key;
    }

    public function setIssueTypeName(string $issueTypeName): void
    {
        $this->issueTypeName = $issueTypeName;
    }

    public function setProjectKey(?string $projectKey): void
    {
        $this->projectKey = $projectKey;
    }

    public function setProjectName(?string $projectName): void
    {
        $this->projectName = $projectName;
    }

    public function setPriorityName(string $priorityName): void
    {
        $this->priorityName = $priorityName;
    }

    public function setLabels(array $labels): void
    {
        $this->labels = $labels;
    }

    public function setAssignee(?JiraUser $assignee): void
    {
        $this->assignee = $assignee;
    }

    public function setStatusName(?string $statusName): void
    {
        $this->statusName = $statusName;
    }

    public function setSummary(?string $summary): void
    {
        $this->summary = $summary;
    }

    /**
     * Set parsed JIRA issue comments data
     */
    public function setIssueComments(JiraIssueComments $issueComments): void
    {
        $this->issueComments = $issueComments;
    }

    /**
     * Sets all issue fields to access extra info.
     */
    public function setFields(array $fields): void
    {
      $this->fields = $fields;
    }

    /**************************************************/

    public function getId(): int
    {
        return $this->id;
    }

    public function getSelf(): string
    {
        return $this->self;
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    /**
     * Returns the key with a $modifier instead of a hyphen
     */
    public function getModifiedKey(string $modifier = ' '): string
    {
        return str_replace('-', $modifier, $this->key);
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getIssueTypeName(): string
    {
        return $this->issueTypeName;
    }

    public function getProjectKey(): ?string
    {
        return $this->projectKey;
    }

    public function getProjectName(): ?string
    {
        return $this->projectName;
    }

    public function getPriorityName(): string
    {
        return $this->priorityName;
    }

    public function getLabels(): array
    {
        return $this->labels;
    }

    public function getAssignee(): ?JiraUser
    {
        return $this->assignee;
    }

    public function getStatusName(): ?string
    {
        return $this->statusName;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function getIssueComments(): JiraIssueComments
    {
        return $this->issueComments;
    }

    public function getFields(): array
    {
      return $this->fields;
    }
}
