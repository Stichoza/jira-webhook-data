<?php

namespace Stichoza\JiraWebhooksData\Models;

use Stichoza\JiraWebhooksData\Exceptions\JiraWebhookDataException;

/**
 * Class that parses JIRA issue comments data and gives access to it.
 *
 * @author  Chewbacca <chewbacca@devadmin.com>
 * @author  Stichoza <me@stichoza.com>
 */
class JiraIssueComments
{
    /**
     * Contains array of comments
     *
     * @var array<\Stichoza\JiraWebhooksData\Models\JiraIssueComment>
     */
    protected array $comments = [];

    /**
     * JIRA comments max results
     */
    protected ?int $maxResults;

    /**
     * Total number of comments
     */
    protected ?int $total;

    /**
     * JIRA comments start at
     */
    protected ?int $startAt;

    /**
     * @throws JiraWebhookDataException
     */
    public function __construct(array $data = null)
    {
        if ($data !== null) {
            foreach ($data['comments'] ?? [] as $comment) {
                $this->pushComment(new JiraIssueComment($comment));
            }

            $this->setMaxResults($data['maxResults'] ?? null);
            $this->setTotal($data['total'] ?? null);
            $this->setStartAt($data['startAt'] ?? null);
        }
    }

    /**
     * @deprecated
     */
    public function setComment(int $key, JiraIssueComment $comment): void
    {
        $this->comments[$key] = $comment;
    }

    /**
     * Push parsed single comment
     */
    public function pushComment(JiraIssueComment $comment): void
    {
        $this->comments[] = $comment;
    }

    public function setMaxResults(?int $maxResults): void
    {
        $this->maxResults = $maxResults;
    }

    public function setTotal(?int $total): void
    {
        $this->total = $total;
    }

    public function setStartAt(?int $startAt): void
    {
        $this->startAt = $startAt;
    }

    /**************************************************/

    /**
     * @return array<\Stichoza\JiraWebhooksData\Models\JiraIssueComment>
     */
    public function getComments(): array
    {
        return $this->comments;
    }

    public function getMaxResults(): ?int
    {
        return $this->maxResults;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function getStartAt(): ?int
    {
        return $this->startAt;
    }

    /**
     * Get object of last comment
     */
    public function getLastComment(): ?JiraIssueComment
    {
        return end($this->comments) ?: null;
    }

    /**
     * Get author name of last comment
     */
    public function getLastCommenterName(): ?string
    {
        return $this->getLastComment()?->getAuthor()->getName();
    }

    /**
     * Get body (text) of last comment
     */
    public function getLastCommentBody(): ?string
    {
        return $this->getLastComment()?->getBody();
    }
}
