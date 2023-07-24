<?php

namespace Stichoza\JiraWebhooksData\Models;

use Stichoza\JiraWebhooksData\Exceptions\JiraWebhookDataException;

/**
 * Class that parses JIRA issue single comment data and gives access to it.
 *
 * @author  Chewbacca <chewbacca@devadmin.com>
 * @author  Stichoza <me@stichoza.com>
 */
class JiraIssueComment
{
    /**
     * JIRA comment self url
     */
    protected ?string $self;

    /**
     * JIRA comment ID
     */
    protected int $id;

    /**
     * JIRA comment author
     */
    protected JiraUser $author;

    /**
     * JIRA comment text
     */
    protected string $body;

    /**
     * JIRA comment update author
     */
    protected JiraUser $updateAuthor;

    /**
     * JIRA comment create data time
     */
    protected ?string $created;

    /**
     * JIRA comment update data time
     */
    protected ?string $updated;

    /**
     * @throws JiraWebhookDataException
     */
    public function __construct(array $data = null)
    {
        if ($data !== null) {
            $this->validate($data);

            $this->setSelf($data['self'] ?? null);
            $this->setId((int) $data['id']);
            $this->setAuthor(new JiraUser($data['author']));
            $this->setBody($data['body'] ?? '');
            $this->setUpdateAuthor(new JiraUser($data['updateAuthor']));
            $this->setCreated($data['created'] ?? null);
            $this->setUpdated($data['updated'] ?? null);
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
            throw new JiraWebhookDataException('JIRA issue comment id does not exist!');
        }

        if (empty($data['author'])) {
            throw new JiraWebhookDataException('JIRA issue comment author does not exist!');
        }

        if (empty($data['updateAuthor'])) {
            throw new JiraWebhookDataException('JIRA issue comment update author does not exist!');
        }

        if (empty($data['body'])) {
            throw new JiraWebhookDataException('JIRA issue comment body does not exist!');
        }
    }

    /**
     * Get array of user nicknames that referenced in comment
     */
    public function getMentionedUsersNicknames(): array
    {
        preg_match_all("/\[~(.*?)\]/", $this->body, $matches);

        return $matches[1];
    }

    /**
     * Remove from comment body code and quote blocks
     */
    public function bodyParsing(): string
    {
        return preg_replace("/\{code(.*?)\}(.*?)\{code\}|\{quote\}(.*?)\{quote\}/", '', $this->body);
    }

    public function setSelf(?string $self): void
    {
        $this->self = $self;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setAuthor(JiraUser $author): void
    {
        $this->author = $author;
    }

    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    public function setUpdateAuthor(JiraUser $updateAuthor): void
    {
        $this->updateAuthor = $updateAuthor;
    }

    public function setCreated(?string $created): void
    {
        $this->created = $created;
    }

    public function setUpdated(?string $updated): void
    {
        $this->updated = $updated;
    }

    /**************************************************/

    public function getSelf(): ?string
    {
        return $this->self;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAuthor(): JiraUser
    {
        return $this->author;
    }

    public function getBody(int $start = 0, int $length = null): string
    {
        return mb_substr($this->body, $start, $length);
    }

    public function getUpdateAuthor(): JiraUser
    {
        return $this->updateAuthor;
    }

    public function getCreated(): ?string
    {
        return $this->created;
    }

    public function getUpdated(): ?string
    {
        return $this->updated;
    }
}
