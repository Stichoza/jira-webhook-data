<?php

namespace JiraWebhookData\Models;

use JiraWebhookData\Exceptions\JiraWebhookDataException;

/**
 * Class that parses JIRA changelog data and gives access to it.
 *
 * @credits https://github.com/kommuna
 * @author  Chewbacca chewbacca@devadmin.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
     * @var array<\JiraWebhook\Models\JiraChangelogItem>
     */
    protected array $items = [];

    /**
     * Parsing JIRA changelog data
     *
     * @throws \JiraWebhook\Exceptions\JiraWebhookDataException
     */
    public static function parse(array $data = null): self
    {
        $changelogData = new self;

        if (!$data) {
            return $changelogData;
        }

        $changelogData->validate($data);

        $changelogData->setId((int) $data['id']);

        foreach ($data['items'] ?? [] as $item) {
            $changelogData->pushItem(JiraChangelogItem::parse($item));
        }

        return $changelogData;
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
     * @return array<\JiraWebhook\Models\JiraChangelogItem>
     */
    public function getItems(): array
    {
        return $this->items;
    }
}
