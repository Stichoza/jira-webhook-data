Jira Webhooks Data
==================

[![Latest Stable Version](https://img.shields.io/packagist/v/Stichoza/jira-webhooks-data.svg)](https://packagist.org/packages/stichoza/jira-webhooks-data) [![Total Downloads](https://img.shields.io/packagist/dt/Stichoza/jira-webhooks-data.svg)](https://packagist.org/packages/stichoza/jira-webhooks-data) [![Downloads Month](https://img.shields.io/packagist/dm/Stichoza/jira-webhooks-data.svg)](https://packagist.org/packages/stichoza/jira-webhooks-data) [![Petreon donation](https://img.shields.io/badge/patreon-donate-orange.svg)](https://www.patreon.com/stichoza) [![PayPal donation](https://img.shields.io/badge/paypal-donate-blue.svg)](https://paypal.me/stichoza)

This is PHP library for processing and handling Atlassian [Jira webhook](https://developer.atlassian.com/jiradev/jira-apis/webhooks) data.

If you're looking for the **Laravel** package with events and routes, check out [stichoza/jira-webhooks-laravel](https://github.com/Stichoza/jira-webhooks-laravel) package that includes this package as data structures.

> **Note:** This package was originally forked from [kommuna/jirawebhook](https://github.com/kommuna/jirawebhook) which is meant to be used with the [kommuna/vicky](https://github.com/kommuna/vicky) and has support of League events and message converters. This package, on the other hand, is a separate package because the whole structure is rewritten to the point that it's no longer compatible with the original repo. Kudos to the developers of the original package!

## Installation

Install this package with Composer:

```
composer require stichoza/jira-webhook-data
```

## Usage  

```php
// Example payload received from Jira webhook
$data = [
    "timestamp" => 1629835026055,
    "webhookEvent" => "jira:issue_updated",
    // ... other webhook data ...
    "issue" => [
        // ... issue data ...
    ],
    "changelog" => [
        // ... changelog data ...
    ],
    // ... other data ...
];
```

Create an instance of `JiraWebhookData`:
```php
use Stichoza\JiraWebhooksData\Models\JiraWebhookData;

$webhookData = new JiraWebhookData($data);
```

Now you can access the parsed data from the webhook:

```php
$message = 'New comment by ' . $webhookData->comment->author->displayName
    . ' on issue ' . $webhookData->issue->key;
    . ': ' . $webhookData->comment->body;
```

More properties listed below:

```php
$timestamp = $webhookData->timestamp;
$webhookEvent = $webhookData->webhookEvent;
$issueEvent = $webhookData->issueEvent;
$user = $webhookData->user; // JiraUser instance
$issue = $webhookData->issue; // JiraIssue instance
$changelog = $webhookData->changelog; // JiraChangelog instance
$worklog = $webhookData->worklog; // JiraWorklog instance
```

Access specific properties from the `JiraIssue` instance

```php
$issueId = $issue->id;
$issueKey = $issue->key;
$issueTypeName = $issue->issueTypeName;
$priorityName = $issue->priorityName;
```

Access specific properties from the `JiraUser` instance

```php
if ($user) {
    $userAccountId = $user->accountId;
    $userDisplayName = $user->displayName;
    // ... and other properties ...
}
```

Access specific properties from the `JiraChangelog` instance

```php
$changelogId = $changelog->id;
$changelogItems = $changelog->items; // Array of JiraChangelogItem instances
// ... and other properties ...
```

You can also perform additional checks or operations based on the parsed data

```php
if ($issue && $issue->isStatusResolved()) {
    // Do something for resolved issues
}

if ($user && $user->active) {
    // Do something for active users
}
```

Read more about properties and methods in the `src/Models` folder.

## Error Handling

Object constructors will throw a `Stichoza\JiraWebhooksData\Exceptions\JiraWebhookDataException` exception when incorrect or insufficient data is provided. Make sure you wrap your code in try-catch block.

```php
use Stichoza\JiraWebhooksData\Models\JiraWebhookData;
use Stichoza\JiraWebhooksData\Exceptions\JiraWebhookDataException;

try {
    $webhookData = new JiraWebhookData($data);
} catch (JiraWebhookDataException $e) {
    // Handle the error
}
```

Also keep in mind that certain properties will not be present in response depending on the Jira event. Make sure you check that objects/properties are not null.

```php
$to = $webhookData->issue?->assignee?->email;

if ($to !== null) {
    // Do something
}
```
