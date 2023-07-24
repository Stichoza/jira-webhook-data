<?php

namespace Stichoza\JiraWebhooksData\Models;

use Stichoza\JiraWebhooksData\Exceptions\JiraWebhookDataException;

/**
 * Class that parses JIRA user data and gives access to it.
 *
 * @author  Chewbacca <chewbacca@devadmin.com>
 * @author  Stichoza <me@stichoza.com>
 */
class JiraUser extends AbstractModel
{
    /**
     * JIRA user self URL
     */
    public ?string $self;

    /**
     * JIRA username
     */
    public string $name;

    /**
     * JIRA user key
     */
    public ?string $key;

    /**
     * JIRA user email
     */
    public ?string $email;

    /**
     * Array of JIRA user avatars
     */
    public array $avatarURLs = [];

    /**
     * JIRA user display name
     */
    public ?string $displayName;

    /**
     * JIRA user active
     */
    public ?bool $active;

    /**
     * JIRA user time zone
     */
    public ?string $timeZone;

    /**
     * @throws JiraWebhookDataException
     */
    public function __construct(array $data = null)
    {
        if ($data !== null) {
            $this->validate($data);

            $this->self = $data['self'] ?? null;
            $this->name = $data['name'] ?? $data['displayName']; // Checked in validate()
            $this->key = $data['key'] ?? null;
            $this->email = $data['emailAddress'] ?? null;
            $this->avatarURLs = $data['avatarUrls'] ?? [];
            $this->displayName = $data['displayName'] ?? null;
            $this->active = $data['active'] ?? null;
            $this->timeZone = $data['timeZone'] ?? null;
        }
    }

    /**
     * @throws JiraWebhookDataException
     */
    public function validate(array $data): void
    {
        if (empty($data['name']) && empty($data['displayName'])) {
            throw new JiraWebhookDataException('JIRA issue user name does not exist!');
        }
    }
}
