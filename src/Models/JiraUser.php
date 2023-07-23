<?php

namespace JiraWebhookData\Models;

use JiraWebhookData\Exceptions\JiraWebhookDataException;

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
     * Parsing JIRA user data
     *
     * @throws JiraWebhookDataException
     */
    public static function parse(array $data = null): self
    {
        $userData = new self;

        if (!$data) {
            return $userData;
        }

        $userData->validate($data);

        $userData->setSelf($data['self'] ?? null);
        $userData->setName($data['name'] ?? $data['displayName']); // Checked in validate()
        $userData->setKey($data['key'] ?? null);
        $userData->setEmail($data['emailAddress'] ?? null);
        $userData->setAvatarURLs($data['avatarUrls'] ?? []);
        $userData->setDisplayName($data['displayName'] ?? null);
        $userData->setActive($data['active'] ?? null);
        $userData->setTimeZone($data['timeZone'] ?? null);

        return $userData;
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
