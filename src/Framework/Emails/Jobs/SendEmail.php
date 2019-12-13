<?php

namespace Osm\Framework\Emails\Jobs;

use Osm\Core\App;
use Osm\Framework\Emails\Module;
use Osm\Framework\Queues\Job;
use Swift_Mailer;
use Swift_Message;

/**
 * @property string $email @required @part
 * @property Swift_Message $message @required
 * @property Module $module @required
 */
class SendEmail extends Job
{
    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'module': return $osm_app->modules['Osm_Framework_Emails'];
            case 'message': return unserialize($this->email);
        }

        return parent::default($property);
    }

    public function handle() {
        $mailer = $this->module->createMailer();
        $mailer->send($this->message);
    }

}