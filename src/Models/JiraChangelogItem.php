<?php

namespace Stichoza\JiraWebhooksData\Models;

use Stichoza\JiraWebhooksData\Exceptions\JiraWebhookDataException;

/**
 * Class that parses JIRA changelog item data and gives access to it.
 *
 * @author  Chewbacca <chewbacca@devadmin.com>
 * @author  Stichoza <me@stichoza.com>
 */
class JiraChangelogItem
{
    /**
     * Issue field that has changed
     */
    protected string $field;

    /**
     * Type of changed field
     */
    protected string $fieldType;

    /**
     * Old value of the field
     */
    protected ?int $from;

    /**
     * Name of the field old value
     */
    protected ?string $fromString;

    /**
     * New value of the field
     */
    protected ?int $to;

    /**
     * Name of field new value
     */
    protected ?string $toString;

    /**
     * @throws JiraWebhookDataException
     */
    public function __construct(array $data = null)
    {
        if ($data !== null) {
            $this->validate($data);

            $this->setField($data['field']);
            $this->setFieldType($data['fieldtype']);
            $this->setFrom($data['from'] ?? null);
            $this->setFromString($data['fromString'] ?? null);
            $this->setTo($data['to'] ?? null);
            $this->setToString($data['toString'] ?? null);
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

    public function setField(string $field): void
    {
        $this->field = $field;
    }

    public function setFieldType(string $fieldType): void
    {
        $this->fieldType = $fieldType;
    }

    public function setFrom(?string $from): void
    {
        $this->from = $from;
    }

    public function setFromString(?string $fromString): void
    {
        $this->fromString = $fromString;
    }

    public function setTo(?string $to): void
    {
        $this->to = $to;
    }

    public function setToString(?string $toString): void
    {
        $this->toString = $toString;
    }

    /**************************************************/

    public function getField(): string
    {
        return $this->field;
    }

    public function getFieldType(): string
    {
        return $this->fieldType;
    }

    public function getFrom(): ?int
    {
        return $this->from;
    }

    public function getFromString(): ?string
    {
        return $this->fromString;
    }

    public function getTo(): ?int
    {
        return $this->to;
    }

    public function getToString(): ?string
    {
        return $this->toString;
    }
}
