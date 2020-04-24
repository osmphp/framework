<?php

namespace Osm\Tests\Framework;

use Osm\Framework\Testing\Tests\UnitTestCase;
use Osm\Framework\Views\View;

class ViewsTest extends UnitTestCase
{
    public function testHtmlIdGeneration() {
        $view = View::new([
            'id' => 'root',
            'id_' => null,
            'header' => View::new([
                'menu' => View::new([]),
                'links' => View::new([]),
            ]),
            'content' => View::new([
                'heading' => View::new([]),
                'product' => View::new([
                    'price' => View::new([]),
                    'images' => [
                        View::new([]),
                        View::new([]),
                        View::new([]),
                    ],
                    'items' => [
                        'title' => View::new([]),
                        'description' => View::new([]),
                    ],
                    'content' => View::new([
                        'items' => [
                            'extra' => View::new([]),
                        ],
                    ]),
                ]),
            ]),
            'footer' => View::new([]),
        ]);

        $this->assertNull($view->id_);

        // '#root' children are not prefixed with 'root__' as it is explicitly specified that '#root'
        // has no html id. '#root' children are prefixed with '_' because it has 'content' property.

        $this->assertEquals('_header', $view->header->id_);
        $this->assertEquals('_content', $view->content->id_);
        $this->assertEquals('_footer', $view->footer->id_);

        $this->assertEquals('_header__menu', $view->header->menu->id_);
        $this->assertEquals('_header__links', $view->header->links->id_);

        // '#root.content' children are not prefixed with '_content__'

        $this->assertEquals('heading', $view->content->heading->id_);
        $this->assertEquals('product', $view->content->product->id_);

        // '#root.content.product' children are prefixed with additional '_' because it has 'items' property

        $this->assertEquals('product___price', $view->content->product->price->id_);
        $this->assertEquals('product___images_0', $view->content->product->images[0]->id_);
        $this->assertEquals('product___images_1', $view->content->product->images[1]->id_);
        $this->assertEquals('product___images_2', $view->content->product->images[2]->id_);

        // '#root.content.product.items' items does not have '_items__' in their html id

        $this->assertEquals('product__title', $view->content->product->items['title']->id_);
        $this->assertEquals('product__description', $view->content->product->items['description']->id_);

        // '#root.content.product.content.items[sidebar]' has neither 'content' not 'items' in its html id

        $this->assertEquals('product__extra', $view->content->product->content->items['extra']->id_);

    }
}