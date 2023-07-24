<?php

namespace Stichoza\JiraWebhooksData\Models;

use Stichoza\JiraWebhooksData\Exceptions\JiraWebhookDataException;

/**
 * Class that parses JIRA webhook data and gives access to it.
 *
 * @author  Chewbacca <chewbacca@devadmin.com>
 * @author  Stichoza <me@stichoza.com>
 */
class JiraWebhookData extends AbstractModel
{

    public int $timestamp;

    public string $webhookEvent;

    public ?string $issueEvent;

    public ?JiraUser $user;

    public ?JiraIssue $issue;

    public ?JiraChangelog $changelog;

    public ?JiraWorklog $worklog;

    /**
     * @var array Decoded raw data
     */
    protected array $rawData = [];

    /**
     * @var array<string> Array of required keys in data
     */
    protected array $required = [
        'timestamp',
        'webhookEvent',
    ];

    /**
     * @throws JiraWebhookDataException
     */
    public function __construct(array $data = null)
    {
        if ($data !== null) {
            $this->validate($data);

            $this->rawData = $data;
            $this->timestamp = (int) $data['timestamp'];
            $this->webhookEvent = $data['webhookEvent'];
            $this->issueEvent = $data['issue_event_type_name'] ?? null;

            // For worklogs, best to get the user from the author fields prior to calling this hook.
            $this->user = empty($data['user']) ? null : new JiraUser($data['user']);
            $this->issue = empty($data['issue']) ? null : new JiraIssue($data['issue']);
            $this->changelog = empty($data['changelog']) ? null : new JiraChangelog($data['changelog']);
            $this->worklog = empty($data['worklog']) ? null : new JiraWorklog($data['worklog']);
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
