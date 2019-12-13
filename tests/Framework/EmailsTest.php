<?php

namespace Osm\Tests\Framework;

use Osm\Core\App;
use Osm\Framework\Emails\Module;
use Osm\Framework\Testing\Tests\UnitTestCase;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

/**
 * @property Module $module @required
 */
class EmailsTest extends UnitTestCase
{
    public function __get($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'module': return $osm_app->modules['Osm_Framework_Emails'];
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
        $sent = $this->module->send((new Swift_Message('Wonderful Subject'))
            ->setFrom(['example@domain.com' => 'The Sender'])
            ->setTo(['another@domain.com'])
            ->setBody('My <em>amazing</em> body', 'text/html')
            ->addPart('My amazing body in plain text', 'text/plain'));

        $this->assertEquals(1, $sent);
    }

    public function testEmailApiUsingHelperFunction() {
        try {
            global $osm_app; /* @var App $osm_app */

            $osm_app->area = 'test';

            $sent = osm_send_email('welcome_email', [
                '#email' => [
                    'to' => 'recipient@example.com',
                ],
                '#email.body' => [
                    'contents' => 'Hi',
                ],
            ]);

            if ($osm_app->settings->use_email_queue) {
                $this->assertEquals(0, $sent);

            }
            else {
                $this->assertEquals(1, $sent);
            }
        }
        finally {
            // further tests may be corrupted as 'test' area is propagated
            // through the all the objects, so we create new, fresh app instance
            $this->recreateApp();
        }
    }
}