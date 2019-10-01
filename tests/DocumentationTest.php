<?php

namespace Osm\Tests;

use Osm\Framework\Testing\Tests\DocumentationTestCase;

class DocumentationTest extends DocumentationTestCase
{
    public function test_web_developer_guide() {
        $this->assertDocumentationIsUpToDate('web-development/css-styles/reference/sass-variables', '096851a', [
            'src/*/*/resources/css/_variables.scss',
        ]);

        $this->assertDocumentationIsUpToDate('web-development/ui-components', '31bcf97', [
            'src/Ui',
        ]);
    }
}