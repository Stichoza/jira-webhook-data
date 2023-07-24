<?php

namespace Stichoza\JiraWebhooksData\Models;

use Stichoza\JiraWebhooksData\Exceptions\JiraWebhookDataException;

/**
 * Class that parses JIRA issue data and gives access to it.
 *
 * @author  Chewbacca <chewbacca@devadmin.com>
 * @author  Stichoza <me@stichoza.com>
 */
class JiraIssue
{
    /**
     * JIRA issue id
     */
    public int $id;

    /**
     * JIRA issue self URL
     */
    public string $self;

    /**
     * JIRA issue key
     */
    public string $key;

    /**
     * JIRA issue url
     */
    public ?string $url;

    /**
     * JIRA issue type name
     */
    public string $issueTypeName;

    /**
     * JIRA issue project key
     */
    public ?string $projectKey;

    /**
     * JIRA issue project name
     */
    public ?string $projectName;

    /**
     * JIRA issue priority
     */
    public string $priorityName;

    /**
     * Array of JIRA issue labels
     */
    public array $labels = [];

    /**
     * JiraWebhook\Models\JiraUser
     */
    public ?JiraUser $assignee;

    /**
     * JIRA issue status
     */
    public ?string $statusName;

    /**
     * JIRA issue summary
     */
    public ?string $summary;

    public JiraIssueComments $issueComments;

    /**
     * All Jira issue fields
     */
    public array $fields = [];

    /**
     * @throws JiraWebhookDataException
     */
    public function __construct(array $data = null)
    {
        if ($data !== null) {
            $this->validate($data);

            $issueFields = $data['fields'];

            $this->fields = $issueFields;

            $this->id = (int) $data['id'];
            $this->self = $data['self'];
            $this->key = $data['key'];
            $this->issueTypeName = $issueFields['issuetype']['name'];
            $this->projectKey = $issueFields['project']['key'] ?? null;
            $this->projectName = $issueFields['project']['name'] ?? null;
            $this->priorityName = $issueFields['priority']['name'];
            $this->labels = $issueFields['labels'] ?? [];
            $this->assignee = empty($issueFields['assignee']) ? null : new JiraUser($issueFields['assignee']);
            $this->statusName = $issueFields['status']['name'] ?? null;
            $this->summary = $issueFields['summary'] ?? null;
            $this->issueComments = new JiraIssueComments($data['fields']['comment'] ?? []);

            $this->setUrl($data['key'], $data['self']);
        }
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
        return strtolower($this->priorityName) === 'blocker';
    }

    /**
     * Check JIRA issue type is Operations
     */
    public function isTypeOperations(): bool
    {
        return str_contains(strtolower($this->issueTypeName), 'operations');
    }

    /**
     * Check JIRA issue type is Urgent bug
     */
    public function isTypeUrgentBug(): bool
    {
        return str_contains(strtolower($this->issueTypeName), 'urgent bug');
    }

    /**
     * Check JIRA issue type is Server
     */
    public function isTypeServer(): bool
    {
        return str_contains(strtolower($this->issueTypeName), 'server');
    }

    /**
     * Check JIRA issue status is Resolved
     */
    public function isStatusResolved(): bool
    {
        // This is cause in devadmin JIRA status 'Resolved' has japanese symbols
        return str_contains(strtolower($this->statusName), 'resolved');
    }

    /**
     * Check if JIRA issue status is Closed
     */
    public function isStatusClosed(): bool
    {
        return strtolower($this->statusName) === 'closed';
    }

    /**
     * Sets the web based url of an issue
     */
    public function setUrl(string $key, string $self): void
    {
        $url = parse_url($self);
        $this->url = $url['scheme'] . '://' . $url['host'] . '/browse/' . $key;
    }

    /**
     * Returns the key with a $modifier instead of a hyphen
     */
    public function key(string $modifier = null): string
    {
        return $modifier === null ? $this->key : str_replace('-', $modifier, $this->key);
    }
}
