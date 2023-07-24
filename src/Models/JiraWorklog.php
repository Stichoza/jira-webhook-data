<?php

namespace Stichoza\JiraWebhooksData\Models;

use Stichoza\JiraWebhooksData\Exceptions\JiraWebhookDataException;

/**
 * Class that parses JIRA webhook data and gives access to it.
 *
 * @author  Chewbacca <chewbacca@devadmin.com>
 * @author  Stichoza <me@stichoza.com>
 */
class JiraWorklog
{
    /**
     * JIRA worklog id
     */
    public int $id;

    /**
     * JIRA worklog self URL
     */
    public ?string $self;

    /**
     * JIRA worklog issue id
     */
    public int $issueId;

    /**
     * JIRA issue matching worklog
     */
    public ?JiraIssue $issue;

    /**
     * JIRA worklog author
     */
    public JiraUser $author;

    /**
     * JIRA worklog time spent in seconds
     */
    public int $timeSpentSeconds;

    /**
     * JIRA worklog comment
     */
    public ?string $comment;

    /**
     * JIRA worklog created date time
     */
    public ?string $created;

    /**
     * JIRA worklog updated date time
     */
    public ?string $updated;

    /**
     * JIRA worklog started date time
     */
    public ?string $started;

    /**
     * @throws JiraWebhookDataException
     */
    public function __construct(array $data = null)
    {
        if ($data !== null) {
            $this->validate($data);

            $this->id = (int) $data['id'];
            $this->self = $data['self'] ?? null;
            $this->issueId = (int) $data['issueId'];
            $this->author = new JiraUser($data['author']);
            $this->timeSpentSeconds = (int) $data['timeSpentSeconds'];

            $this->comment = $data['comment'] ?? null;
            $this->created = $data['created'] ?? null;
            $this->updated = $data['updated'] ?? null;
            $this->started = $data['started'] ?? null;
        }
    }

    /**
     * Validates if the necessary parameters have been provided
     *
     * @throws JiraWebhookDataException
     */
    public function validate($data): void
    {
        if (empty($data['id'])) {
            throw new JiraWebhookDataException('JIRA worklog issue id does not exist!');
        }
        if (empty($data['issueId'])) {
            throw new JiraWebhookDataException('JIRA worklog id does not exist!');
        }
        if (empty($data['author'])) {
            throw new JiraWebhookDataException('JIRA worklog author does not exist!');
        }
        if (empty($data['timeSpentSeconds'])) {
            throw new JiraWebhookDataException('JIRA worklog time spent in sec does not exist!');
        }
    }
}
