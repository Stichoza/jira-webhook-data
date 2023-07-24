<?php

namespace Stichoza\JiraWebhooksData\Models;

use Stichoza\JiraWebhooksData\Exceptions\JiraWebhookDataException;

abstract class AbstractModel
{
    /**
     * @throws JiraWebhookDataException;
     */
    protected function validate(array $data = []): void
    {
        foreach ($this->required ?? [] as $key) {
            if ($this->get($data, $key) === null) {
                throw new JiraWebhookDataException('The key "' . $key . '" not found in data passed to ' . get_class($this));
            }
        }
    }

    /**
     * Get array key using dot notation
     *
     * @param array $array Source array
     * @param string|array $key Key in dot notation
     * @param mixed|null $default Default value
     *
     * @return mixed
     */
    protected function get(array $array, string|array $key, mixed $default = null): mixed
    {
        if (!is_array($key)) {
            $key = explode('.', $key);
        }

        $check = array_shift($key);

        if (!count($key)) {
            return $array[$check] ?? $default;
        }

        if (empty($array[$check])) {
            return $default;
        }

        return $this->get($array[$check], $key, $default);
    }
}
