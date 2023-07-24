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
    public array $comments = [];

    /**
     * JIRA comments max results
     */
    public ?int $maxResults;

    /**
     * Total number of comments
     */
    public ?int $total;

    /**
     * JIRA comments start at
     */
    public ?int $startAt;

    /**
     * @throws JiraWebhookDataException
     */
    public function __construct(array $data = null)
    {
        if ($data !== null) {
            foreach ($data['comments'] ?? [] as $comment) {
                $this->pushComment(new JiraIssueComment($comment));
            }

            $this->maxResults = $data['maxResults'] ?? null;
            $this->total = $data['total'] ?? null;
            $this->startAt = $data['startAt'] ?? null;
        }
    }

    /**
     * Get object of last comment
     */
    public function lastComment(): ?JiraIssueComment
    {
        return end($this->comments) ?: null;
    }

    /**
     * Push parsed single comment
     */
    protected function pushComment(JiraIssueComment $comment): void
    {
        $this->comments[] = $comment;
    }
}
