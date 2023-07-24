<?php

namespace Stichoza\JiraWebhooksData\Models;

use Stichoza\JiraWebhooksData\Exceptions\JiraWebhookDataException;

/**
 * Class that parses JIRA changelog item data and gives access to it.
 *
 * @author  Chewbacca <chewbacca@devadmin.com>
 * @author  Stichoza <me@stichoza.com>
 */
class JiraChangelogItem extends AbstractModel
{
    /**
     * Issue field that has changed
     */
    public string $field;

    /**
     * Type of changed field
     */
    public string $fieldType;

    /**
     * Old value of the field
     */
    public ?int $from;

    /**
     * Name of the field old value
     */
    public ?string $fromString;

    /**
     * New value of the field
     */
    public ?int $to;

    /**
     * Name of field new value
     */
    public ?string $toString;

    /**
     * @throws JiraWebhookDataException
     */
    public function __construct(array $data = null)
    {
        if ($data !== null) {
            $this->validate($data);

            $this->field = $data['field'];
            $this->fieldType = $data['fieldtype'];
            $this->from = $data['from'] ?? null;
            $this->fromString = $data['fromString'] ?? null;
            $this->to = $data['to'] ?? null;
            $this->toString = $data['toString'] ?? null;
        }
    }

    /**
     * @throws JiraWebhookDataException
     */
    public function validate(array $data): void
    {
        if (empty($data['field'])) {
            throw new JiraWebhookDataException('JIRA changelog item fields does not exist!');
        }

        if (empty($data['fieldtype'])) {
            throw new JiraWebhookDataException('JIRA changelog item fieldtype does not exist!');
        }
    }
}
