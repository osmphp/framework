<?php

namespace Osm\Tests\Framework;

use Osm\Core\App;
use Osm\Framework\Testing\Tests\UnitTestCase;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

/**
 * @property Swift_Mailer $mailer
 */
class EmailsTest extends UnitTestCase
{
    public function __get($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'mailer': return $osm_app->mailer;
        }

        return parent::__get($property);
    }

    public function testThatSwiftMailerWorks() {
        if (!env('SMTP_USERNAME')) {
            $this->assertTrue(true);
            return;
        }

        // Create the Transport
        $transport = new Swift_SmtpTransport(env('SMTP_HOST'),
            env('SMTP_PORT'), env('SMTP_ENCRYPTION'));
        $transport
            ->setUsername(env('SMTP_USERNAME'))
            ->setPassword(env('SMTP_PASSWORD'));

        // Create the Mailer using your created Transport
        $mailer = new Swift_Mailer($transport);

        // Create a message
        $message = (new Swift_Message('Wonderful Subject'))
            ->setFrom(['example@domain.com' => 'The Sender'])
            ->setTo(['another@domain.com'])
            ->setBody('My <em>amazing</em> body', 'text/html')
            ->addPart('My amazing body in plain text', 'text/plain')
          ;

        // Send the message and assert it is successful
        $this->assertEquals(1, $mailer->send($message));
    }

    public function testEmailApi() {
        $sent = $this->mailer->send((new Swift_Message('Wonderful Subject'))
            ->setFrom(['example@domain.com' => 'The Sender'])
            ->setTo(['another@domain.com'])
            ->setBody('My <em>amazing</em> body', 'text/html')
            ->addPart('My amazing body in plain text', 'text/plain'));

        $this->assertEquals(1, $sent);
    }
}