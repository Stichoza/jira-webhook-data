<?php

namespace Stichoza\JiraWebhooksData\Models;

use Stichoza\JiraWebhooksData\Exceptions\JiraWebhookDataException;

/**
 * Class that parses JIRA webhook data and gives access to it.
 *
 * @author  Chewbacca <chewbacca@devadmin.com>
 * @author  Stichoza <me@stichoza.com>
 */
class JiraChangelog extends AbstractModel
{
    /**
     * JIRA changelog id
     */
    public int $id;

    /**
     * Array of changelog items
     *
     * @var array<\Stichoza\JiraWebhooksData\Models\JiraChangelogItem>
     */
    public array $items = [];

    /**
     * @var array<string> Array of required keys in data
     */
    protected array $required = [
        'id',
    ];

    /**
     * @throws JiraWebhookDataException
     */
    public function __construct(array $data = null)
    {
        if ($data !== null) {
            $this->validate($data);

            $this->id = (int) $data['id'];

            foreach ($data['items'] ?? [] as $item) {
                $this->pushItem(new JiraChangelogItem($item));
            }
        }
    }

    protected function pushItem(JiraChangelogItem $item): void
    {
        $this->items[] = $item;
    }
}
