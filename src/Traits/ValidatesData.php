<?php

namespace Stichoza\JiraWebhooksData\Traits;

use Stichoza\JiraWebhooksData\Exceptions\JiraWebhookDataException;

trait ValidatesData
{
    /**
     * @throws JiraWebhookDataException;
     */
    protected function validate(array $data = []): void
    {
        foreach ($this->required ?? [] as $key) {
            //if (empty($data[$key])
        }
    }
}
