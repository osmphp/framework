<?php

namespace Osm\Framework\Emails\Traits;

use Osm\Core\App;
use Osm\Framework\Emails\Module;
use Swift_Mailer;
use Swift_NullTransport;
use Swift_SmtpTransport;

trait PropertiesTrait
{
    public function Osm_Core_App__mailer(App $app) {
        $settings = $app->settings;

        if (!($transport = (string)$settings->send_emails_via)) {
            return new Swift_Mailer(new Swift_NullTransport());
        }

        /* @var Module $module */
        $module = $app->modules['Osm_Framework_Emails'];

        $transport_ = $module->transports[$transport];

        return new Swift_Mailer($transport_->create());
    }

}