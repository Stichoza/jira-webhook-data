<?php

namespace JiraWebhookData\Models;

use JiraWebhookData\Exceptions\JiraWebhookDataException;

/**
 * Class that parses JIRA webhook data and gives access to it.
 *
 * @credits https://github.com/kommuna
 * @author  Chewbacca chewbacca@devadmin.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class JiraWebhookData
{
    /**
     * Decoded raw data
     */
    protected array $rawData = [];

    protected ?int $timestamp;

    protected string $webhookEvent;

    protected ?string $issueEvent;

    protected ?JiraUser $user;

    protected ?JiraIssue $issue;

    protected ?JiraChangelog $changelog;

    protected ?JiraWorklog $workLog;

    /**
     * Parsing JIRA webhook $data
     *
     * @throws JiraWebhookDataException
     */
    public static function parse(array $data = null): self
    {
        $webhookData = new self;

        if (!$data) {
            return $webhookData;
        }

        $webhookData->setRawData($data);

        $webhookData->validate($data);

        $webhookData->setTimestamp($data['timestamp']);
        $webhookData->setWebhookEvent($data['webhookEvent']);
        $webhookData->setIssueEvent($data['issue_event_type_name']);

        // For worklogs, best to get the user from the author fields prior to calling this hook.
        $webhookData->setUser(JiraUser::parse($data['user']));
        $webhookData->setIssue(JiraIssue::parse($data['issue']));
        $webhookData->setChangelog(JiraChangelog::parse($data['changelog']));
        $webhookData->setWorklog(JiraWorklog::parse($data['worklog']));

        return $webhookData;
    }

    /**
     * @throws JiraWebhookDataException
     */
    public function validate(array $data): void
    {
        if (empty($data['webhookEvent'])) {
            throw new JiraWebhookDataException('JIRA webhook event not set!');
        }

        if (empty($data['issue_event_type_name']) && empty($data['worklog'])) {
            throw new JiraWebhookDataException('JIRA issue event type or worklog not set!');
        }

        if (empty($data['issue']) && empty($data['worklog'])) {
            throw new JiraWebhookDataException('JIRA issue or worklog not set!');
        }
    }

    /**
     * Check if JIRA issue event is issue commented
     */
    public function isIssueCommented(): bool
    {
        return array_key_exists('comment', $this->rawData);
    }

    /**
     * Get array of channel labels that referenced in comment
     */
    public static function getReferencedLabels(string $string): array
    {
        preg_match_all("/#([A-Za-z0-9]*)/", $string, $matches);

        return $matches[1];
    }

    /**************************************************/

    /**
     * Set raw array, decoded from JIRA webhook
     */
    public function setRawData(array $rawData): void
    {
        $this->rawData = $rawData;
    }

    public function setTimestamp(int $timestamp): void
    {
        $this->timestamp = $timestamp;
    }

    public function setWebhookEvent(string $webhookEvent): void
    {
        $this->webhookEvent = $webhookEvent;
    }

    public function setIssueEvent(string $issueEvent): void
    {
        $this->issueEvent = $issueEvent;
    }

    public function setUser(JiraUser $user): void
    {
        $this->user = $user;
    }

    public function setIssue(JiraIssue $issue): void
    {
        $this->issue = $issue;
    }

    public function setChangelog(JiraChangelog $changelog): void
    {
        $this->changelog = $changelog;
    }

    public function setWorklog(JiraWorklog $worklog): void
    {
        $this->workLog = $worklog;
    }

    /**************************************************/

    public function getRawData(): array
    {
        return $this->rawData;
    }

    public function getTimestamp(): ?int
    {
        return $this->timestamp;
    }

    public function getWebhookEvent(): string
    {
        return $this->webhookEvent;
    }

    public function getIssueEvent(): ?string
    {
        return $this->issueEvent;
    }

    public function getUser(): ?JiraUser
    {
        return $this->user;
    }

    public function getIssue(): ?JiraIssue
    {
        return $this->issue;
    }

    public function getChangelog(): ?JiraChangelog
    {
        return $this->changelog;
    }

    public function getWorklog(): ?JiraWorklog
    {
        return $this->workLog;
    }
}
