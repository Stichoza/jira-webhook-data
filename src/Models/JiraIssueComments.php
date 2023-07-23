<?php

namespace JiraWebhookData\Models;

use JiraWebhookData\Exceptions\JiraWebhookDataException;

/**
 * Class that parses JIRA issue comments data and gives access to it.
 *
 * @credits https://github.com/kommuna
 * @author  Chewbacca chewbacca@devadmin.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class JiraIssueComments
{
    /**
     * Contains array of comments
     *
     * @var array<\JiraWebhook\Models\JiraIssueComment>
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
     * Parsing JIRA issue comments $data
     *
     * @throws JiraWebhookDataException
     */
    public static function parse(array $data = null): self
    {
        $issueCommentsData = new self;

        if (!$data) {
            return $issueCommentsData;
        }

        foreach ($data['comments'] ?? [] as $comment) {
            $issueCommentsData->pushComment(JiraIssueComment::parse($comment));
        }

        $issueCommentsData->setMaxResults($data['maxResults'] ?? null);
        $issueCommentsData->setTotal($data['total'] ?? null);
        $issueCommentsData->setStartAt($data['startAt'] ?? null);

        return $issueCommentsData;
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
     * @return array<\JiraWebhook\Models\JiraIssueComment>
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
