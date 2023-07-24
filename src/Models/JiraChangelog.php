<?php

namespace Stichoza\JiraWebhooksData\Models;

use Stichoza\JiraWebhooksData\Exceptions\JiraWebhookDataException;

/**
 * Class that parses JIRA webhook data and gives access to it.
 *
 * @author  Chewbacca <chewbacca@devadmin.com>
 * @author  Stichoza <me@stichoza.com>
 */
class JiraChangelog
{
    /**
     * JIRA changelog id
     */
    protected int $id;

    /**
     * Array of changelog items
     *
     * @var array<\Stichoza\JiraWebhooksData\Models\JiraChangelogItem>
     */
    protected array $items = [];

    /**
     * @throws JiraWebhookDataException
     */
    public function __construct(array $data = null)
    {
        if ($data !== null) {
            $this->validate($data);

            $this->setId((int) $data['id']);

            foreach ($data['items'] ?? [] as $item) {
                $this->pushItem(new JiraChangelogItem($item));
            }
        }
    }

    /**
     * @throws JiraWebhookDataException
     */
    public function validate(array $data): void
    {
        if (empty($data['id'])) {
            throw new JiraWebhookDataException('JIRA changelog id does not exist!');
        }
    }

    /**
     * Check if JIRA issue was assigned
     */
    public function isIssueAssigned(): bool
    {
        foreach ($this->items as $item) {
            if ($item->getField() === 'assignee') {
                return true;
            }
        }

        return false;
    }

    /**************************************************/

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @deprecated
     */
    public function setItem(int $key, JiraChangelogItem $item): void
    {
        $this->items[$key] = $item;
    }

    public function pushItem(JiraChangelogItem $item): void
    {
        $this->items[] = $item;
    }

    /**************************************************/

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return array<\Stichoza\JiraWebhooksData\Models\JiraChangelogItem>
     */
    public function getItems(): array
    {
        return $this->items;
    }
}
