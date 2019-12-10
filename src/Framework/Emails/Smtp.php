<?php

namespace Osm\Framework\Emails;

use Osm\Core\App;
use Osm\Framework\Settings\Settings;
use Swift_SmtpTransport;

/**
 * Dependencies:
 *
 * @property Settings $settings @required
 * Properties:
 *
 * @property string $host @required @part
 * @property int $port @required @part
 * @property string $encryption @part
 * @property string $user @required @part
 * @property string $password @required @part
 */
class Smtp extends Transport
{
    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'settings': return $osm_app->settings;

            case 'host': return $this->settings->smtp_host;
            case 'port': return (int)(string)$this->settings->smtp_port;
            case 'encryption': return ((string)$this->settings->smtp_encryption) ?: null;
            case 'user': return $this->settings->smtp_user;
            case 'password': return $this->settings->smtp_password;
        }
        return parent::default($property);
    }

    public function create() {
        $result = new Swift_SmtpTransport($this->host, $this->port,
            $this->encryption);

        $result
            ->setUsername($this->user)
            ->setPassword($this->password);

        return $result;
    }
}