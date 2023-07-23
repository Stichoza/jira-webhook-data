<?php

namespace JiraWebhookData\Models;

use JiraWebhookData\Exceptions\JiraWebhookDataException;

/**
 * Class that parses JIRA changelog item data and gives access to it.
 *
 * @credits https://github.com/kommuna
 * @author  Chewbacca chewbacca@devadmin.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
     * Parsing JIRA changelog item data
     *
     * @throws \JiraWebhook\Exceptions\JiraWebhookDataException
     */
    public static function parse(array $data = null): self
    {
        $changelogItemData = new self;

        if (!$data) {
            return $changelogItemData;
        }

        $changelogItemData->validate($data);

        $changelogItemData->setField($data['field']);
        $changelogItemData->setFieldType($data['fieldtype']);
        $changelogItemData->setFrom($data['from'] ?? null);
        $changelogItemData->setFromString($data['fromString'] ?? null);
        $changelogItemData->setTo($data['to'] ?? null);
        $changelogItemData->setToString($data['toString'] ?? null);

        return $changelogItemData;
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
