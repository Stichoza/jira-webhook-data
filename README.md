# What is this?  

This is PHP library for processing and handling Atlassian [Jira webhook](https://developer.atlassian.com/jiradev/jira-apis/webhooks) data.

The package was forked from [kommuna/jirawebhook](https://github.com/kommuna/jirawebhook) which is meant to be used with the [kommuna/vicky](https://github.com/kommuna/vicky) and has support of events and message converters. I forked and modified this to better suit my needs as I needed only data structures with a slight modifications that. Check out the [original repo](https://github.com/kommuna/jirawebhook) to choose which one is better for you. 

# Installation
  
Install this package with Composer:

```
composer require stichoza/jira-webhook-data
```

# Usage  

```php

use Stichoza\JiraWebhooksData\Models\JiraWebhookData;

$data = [
    // JIRA webhook data
];

$webhookData = new JiraWebhookData($data);

// Access parsed data
$webhookData->getRawData();
$webhookData->getTimestamp();
$webhookData->getWebhookEvent();
$webhookData->getIssueEvent();
$webhookData->getUser();
$webhookData->getIssue();
$webhookData->getChangelog();
$webhookData->getWorklog();

// Check if issue is commented
$webhookData->isIssueCommented();

// Get author of latest comment
$webhookData->getIssue()->getIssueComments()->getLastComment()->getAuthor()->getName();
```

More info soon...
