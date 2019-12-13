<?php

namespace Osm\Framework\Emails;

use Osm\Core\App;
use Osm\Core\Modules\BaseModule;
use Osm\Core\Properties;
use Osm\Framework\Settings\Settings;

/**
 * @property Settings $settings @required
 * @property Transports|Transport[] $transports @required
 */
class Module extends BaseModule
{
    public $hard_dependencies = [
        'Osm_Framework_Queues',
    ];

    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'settings': return $osm_app->settings;
            case 'transports': return $osm_app->cache->remember("email_transports", function($data) {
                return Transports::new($data);
            });
        }
        return parent::default($property);
    }

    public function createMailer() {
        if (!($transport = (string)$this->settings->send_emails_via)) {
            return new \Swift_Mailer(new \Swift_NullTransport());
        }

        return new \Swift_Mailer($this->transports[$transport]->create());
    }
}