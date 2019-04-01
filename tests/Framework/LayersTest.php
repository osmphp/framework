<?php

namespace Manadev\Tests\Framework;

use Manadev\Core\App;
use Manadev\Framework\Layers\Layout;
use Manadev\Framework\Testing\Tests\UnitTestCase;
use Manadev\Samples\Layers\Views\TestView;

class LayersTest extends UnitTestCase
{
    public function testIncludeInstruction() {
        global $m_app; /* @var App $m_app */

        $layout = Layout::new(['area' => $m_app->areas['test']]);

        $layout->load([
            '@include' => 'base',
        ]);

        $this->assertEquals('test_root', $layout->root->id);
    }

    public function testIdSelector() {
        global $m_app; /* @var App $m_app */

        $layout = Layout::new(['area' => $m_app->areas['test']]);

        $layout->load('base', [
            '#test_root' => [
                'modifier' => '-test-root',
            ],
        ]);

        $this->assertEquals('-test-root', $layout->root->modifier);
    }

    public function testPropertySelector() {
        global $m_app; /* @var App $m_app */

        $layout = Layout::new(['area' => $m_app->areas['test']]);

        $layout->load('base', [
            '#test_root' => [
                'child' => TestView::new([
                    'child' => TestView::new([
                    ]),
                ]),
            ],
            '#test_root.child.child' => [
                'modifier' => '-test-child',
            ],
        ]);

        $view = $layout->root; /* @var TestView $view */

        $this->assertEquals('-test-child', $view->child->child->modifier);
    }

    public function testSelectorApi() {
        global $m_app; /* @var App $m_app */

        $layout = Layout::new(['area' => $m_app->areas['test']]);

        $layout->load('base', [
            '#test_root' => [
                'modifier' => '-test-root',
                'child' => TestView::new([
                    'child' => TestView::new([
                        'modifier' => '-test-child',
                    ]),
                ]),
            ],
        ]);

        $this->assertEquals('-test-root', $layout->select('#test_root')->modifier);
        $this->assertEquals('-test-child', $layout->select('#test_root.child.child')->modifier);
    }
}