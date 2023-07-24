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
    protected int $id;

    /**
     * JIRA worklog self URL
     */
    protected ?string $self;

    /**
     * JIRA worklog issue id
     */
    protected int $issueId;

    /**
     * JIRA issue matching worklog
     */
    protected ?JiraIssue $issue;

    /**
     * JIRA worklog author
     */
    protected JiraUser $author;

    /**
     * JIRA worklog time spent in seconds
     */
    protected int $timeSpentSeconds;

    /**
     * JIRA worklog comment
     */
    protected ?string $comment;

    /**
     * JIRA worklog created date time
     */
    protected ?string $created;

    /**
     * JIRA worklog updated date time
     */
    protected ?string $updated;

    /**
     * JIRA worklog started date time
     */
    protected ?string $started;

    /**
     * @throws JiraWebhookDataException
     */
    public function __construct(array $data = null)
    {
        if ($data !== null) {
            $this->validate($data);

            $this->setId((int) $data['id']);
            $this->setSelf($data['self'] ?? null);
            $this->setIssueId((int) $data['issueId']);
            $this->setAuthor(new JiraUser($data['author']));
            $this->setTimeSpentSeconds((int) $data['timeSpentSeconds']);

            $this->setComment($data['comment'] ?? null);
            $this->setCreatedDate($data['created'] ?? null);
            $this->setUpdatedDate($data['updated'] ?? null);
            $this->setStartedDate($data['started'] ?? null);
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

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setSelf(?string $self): void
    {
        $this->self = $self;
    }

    public function setIssueId(int $issueId): void
    {
        $this->issueId = $issueId;
    }

    public function setAuthor(JiraUser $author): void
    {
        $this->author = $author;
    }

    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }

    public function setCreatedDate(?string $created): void
    {
        $this->created = $created;
    }

    public function setUpdatedDate(?string $updated): void
    {
        $this->updated = $updated;
    }

    public function setStartedDate(?string $started): void
    {
        $this->started = $started;
    }

    public function setTimeSpentSeconds(int $timeSpentSeconds): void
    {
        $this->timeSpentSeconds = $timeSpentSeconds;
    }

  /**
   * Assigns JiraIssue from raw API data.
   *
   * @param array $data Raw Jira issue data retrieved from API.
   *
   * @throws JiraWebhookDataException
   */
    public function setIssueFromData(array $data): void
    {
        $this->issue = new JiraIssue($data);
    }

    /**************************************************/

    public function getId(): int
    {
        return $this->id;
    }

    public function getSelf(): ?string
    {
        return $this->self;
    }

    public function getIssueId(): int
    {
        return $this->issueId;
    }

    public function getAuthor(): JiraUser
    {
        return $this->author;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function getCreatedDate(): ?string
    {
        return $this->created;
    }

    public function getUpdatedDate(): ?string
    {
        return $this->updated;
    }

    public function getStartedDate(): ?string
    {
        return $this->started;
    }

    public function getTimeSpentSeconds(): int
    {
        return $this->timeSpentSeconds;
    }

    public function getIssue(): ?JiraIssue
    {
      return $this->issue;
    }

}
