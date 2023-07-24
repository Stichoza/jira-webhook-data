<?php

namespace Stichoza\JiraWebhooksData\Models;

use Stichoza\JiraWebhooksData\Exceptions\JiraWebhookDataException;

/**
 * Class that parses JIRA webhook data and gives access to it.
 *
 * @author  Chewbacca <chewbacca@devadmin.com>
 * @author  Stichoza <me@stichoza.com>
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

    protected ?JiraWorklog $worklog;

    /**
     * @throws JiraWebhookDataException
     */
    public function __construct(array $data = null)
    {
        if ($data !== null) {
            $this->validate($data);

            $this->rawData = $data;
            $this->timestamp = $data['timestamp'];
            $this->webhookEvent = $data['webhookEvent'];
            $this->issueEvent = $data['issue_event_type_name'];

            // For worklogs, best to get the user from the author fields prior to calling this hook.
            $this->user = empty($data['user']) ? null : new JiraUser($data['user']);
            $this->issue = empty($data['issue']) ? null : new JiraIssue($data['issue']);
            $this->changelog = empty($data['changelog']) ? null : new JiraChangelog($data['changelog']);
            $this->worklog = empty($data['worklog']) ? null : new JiraWorklog($data['worklog']);
        }
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
}
