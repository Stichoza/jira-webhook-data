<?php
/**
 * This file is part of JiraWebhook.
 *
 * @credits https://github.com/kommuna
 * @author  chewbacca@devadmin.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JiraWebhook\Models;

use JiraWebhook\Exceptions\JiraWebhookDataException;

class JiraIssueComments
{
    /**
     * Contains array of JiraWebhook\Models\JiraIssueComment
     * 
     * @var array
     */
    protected $comments = [];

    /**
     * JIRA comments max results
     *
     * @var
     */
    protected $maxResults;

    /**
     * Total number of comments
     *
     * @var
     */
    protected $total;

    /**
     * JIRA comments start at
     *
     * @var
     */
    protected $startAt;

    /**
     * Parsing JIRA issue comments $data
     *
     * @param null $data
     *
     * @return JiraIssueComments
     *
     * @throws JiraWebhookDataException
     */
    public static function parse($data = null)
    {
        $issueCommentsData = new self;

        if (!$data || empty($data['comments'])) {
            return $issueCommentsData;
        }

        foreach ($data['comments'] as $key => $comment) {
            $issueCommentsData->setComment($key, $comment);
        }

        $issueCommentsData->setMaxResults($data['maxResults']);
        $issueCommentsData->setTotal($data['total']);
        $issueCommentsData->setStartAt($data['startAt']);

        return $issueCommentsData;
    }

    /**
     * Set parsed single comment
     *
     * @param $key              array key
     * @param $comment callable comment data
     *
     * @throws JiraWebhookDataException
     */
    public function setComment($key, $comment)
    {
        $this->comments[$key] = JiraIssueComment::parse($comment);
    }

    /**
     * @param $maxResults
     */
    public function setMaxResults($maxResults)
    {
        $this->maxResults = $maxResults;
    }

    /**
     * @param $total
     */
    public function setTotal($total)
    {
        $this->total = $total;
    }

    /**
     * @param $startAt
     */
    public function setStartAt($startAt)
    {
        $this->startAt = $startAt;
    }

    /**************************************************/

    /**
     * @return array
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @return mixed
     */
    public function getMaxResults()
    {
        return $this->maxResults;
    }

    /**
     * @return mixed
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @return mixed
     */
    public function getStartAt()
    {
        return $this->startAt;
    }

    /**
     * Get object of last comment
     * 
     * @return mixed
     */
    public function getLastComment()
    {
        return end($this->comments);
    }

    /**
     * Get author name of last comment
     * 
     * @return mixed
     */
    public function getLastCommenterName()
    {
        return $this->getLastComment()->getAuthor()->getName();
    }

    /**
     * Get body (text) of last comment
     * 
     * @return mixed
     */
    public function getLastCommentBody()
    {
        return $this->getLastComment()->getBody();
    }
}