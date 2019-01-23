<?php

namespace Manadev\Framework\Installer;

use Manadev\Core\App;
use Manadev\Core\Modules\BaseModule;

/**
 * @property Questions|Question[] $questions @required
 * @property Steps|Step[] $steps @required
 * @property Requirements|Requirement[] $requirements @required
 */
class Module extends BaseModule
{
    protected function default($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'questions': return $m_app->cache->remember("installation_questions", function($data) {
                return Questions::new($data);
            });
            case 'steps': return $m_app->cache->remember("installation_steps", function($data) {
                return Steps::new($data);
            });
            case 'requirements': return $m_app->cache->remember("installation_requirements", function($data) {
                return Requirements::new($data);
            });
        }
        return parent::default($property);
    }

}