<?php
/**
 * Test for methods in class JiraWebhook\Models\JiraWebhook
 *
 * @credits https://github.com/kommuna
 * @author  Miss Lv lv@devadmin.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Stichoza\JiraWebhooksData\Tests;

use PHPUnit_Framework_TestCase;
use Stichoza\JiraWebhooksData\JiraWebhook;
use Stichoza\JiraWebhooksData\Models\JiraWebhookData;
use Stichoza\JiraWebhooksData\Exceptions\JiraWebhookException;
use Stichoza\JiraWebhooksData\Tests\Factories\JiraWebhookPayloadFactory;

class JiraWebhookTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers JiraWebhook::extractData
     */
    public function testExtractDataThrowsAnExceptionForInvalidJson()
    {
        $data = '{"foo":"bar}';  // Invalid json
        $this->expectException(JiraWebhookException::class);
        $jiraWebhook = new JiraWebhook();
        $jiraWebhook->extractData($data);
    }

    /**
     * @covers JiraWebhook::extractData
     */
    public function testExtractData()
    {
        $data = JiraWebhookPayloadFactory::create();
        $data = json_encode($data);
        $jiraWebhook = new JiraWebhook();
        $webhookData = $jiraWebhook->extractData($data);

        $this->assertInstanceOf(JiraWebhookData::class, $webhookData);
    }
}
