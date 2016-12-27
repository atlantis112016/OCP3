<?php
/**
 * Created by PhpStorm.
 * User: MisterX
 * Date: 09/12/2016
 * Time: 19:04
 */

namespace MyApp\BilletterieBundle\Tests\Controller;


use MyApp\BilletterieBundle\Controller\MailControllerTestController;


class MailControllerTestControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testMailIsSentAndContentIsOk()
    {
        $client = static::createClient();

        // Enable the profiler for the next request (it does nothing if the profiler is not available)
        $client->enableProfiler();

        $crawler = $client->request('POST', '/path/to/above/action');

        $mailCollector = $client->getProfile()->getCollector('swiftmailer');

        // Check that an email was sent
        $this->assertEquals(1, $mailCollector->getMessageCount());

        $collectedMessages = $mailCollector->getMessages();
        $message = $collectedMessages[0];

        // Asserting email data
        $this->assertInstanceOf('Swift_Message', $message);
        $this->assertEquals('Hello Email', $message->getSubject());
        $this->assertEquals('admin@museeLouvre.com', key($message->getFrom()));
        $this->assertEquals('atlantis11@libertysurf.fr', key($message->getTo()));
        $this->assertEquals(
            'You should see me from the profiler!',
            $message->getBody()
        );
    }
}
