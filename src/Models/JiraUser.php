<?php

namespace Stichoza\JiraWebhooksData\Models;

use Stichoza\JiraWebhooksData\Exceptions\JiraWebhookDataException;

/**
 * Class that parses JIRA user data and gives access to it.
 *
 * @author  Chewbacca <chewbacca@devadmin.com>
 * @author  Stichoza <me@stichoza.com>
 */
class JiraUser
{
    /**
     * JIRA user self URL
     */
    protected ?string $self;

    /**
     * JIRA username
     */
    protected string $name;

    /**
     * JIRA user key
     */
    protected ?string $key;

    /**
     * JIRA user email
     */
    protected ?string $email;

    /**
     * Array of JIRA user avatars
     */
    protected array $avatarURLs = [];

    /**
     * JIRA user display name
     */
    protected ?string $displayName;

    /**
     * JIRA user active
     */
    protected ?bool $active;

    /**
     * JIRA user time zone
     */
    protected ?string $timeZone;

    /**
     * @throws JiraWebhookDataException
     */
    public function __construct(array $data = null)
    {
        if ($data !== null) {
            $this->validate($data);

            $this->setSelf($data['self'] ?? null);
            $this->setName($data['name'] ?? $data['displayName']); // Checked in validate()
            $this->setKey($data['key'] ?? null);
            $this->setEmail($data['emailAddress'] ?? null);
            $this->setAvatarURLs($data['avatarUrls'] ?? []);
            $this->setDisplayName($data['displayName'] ?? null);
            $this->setActive($data['active'] ?? null);
            $this->setTimeZone($data['timeZone'] ?? null);
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

    public function setSelf(?string $self): void
    {
        $this->self = $self;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setKey(?string $key): void
    {
        $this->key = $key;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function setAvatarURLs(array $avatarURLs): void
    {
        $this->avatarURLs = $avatarURLs;
    }

    public function setDisplayName(?string $displayName): void
    {
        $this->displayName = $displayName;
    }

    public function setActive(?bool $active): void
    {
        $this->active = $active;
    }

    public function setTimeZone(?string $timeZone): void
    {
        $this->timeZone = $timeZone;
    }

    /**************************************************/

    public function getSelf(): ?string
    {
        return $this->self;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public  function getAvatarURLs(): array
    {
        return $this->avatarURLs;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function getTimeZone(): ?string
    {
        return $this->timeZone;
    }
}
