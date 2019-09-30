<?php

namespace Osm\Tests\Framework;

use Osm\Core\App;
use Osm\Framework\Testing\Tests\UnitTestCase;
use Osm\Framework\Validation\Exceptions\ValidationFailed;
use Osm\Framework\Validation\Validator;

/**
 * @property Validator $validator
 */
class ValidationTest extends UnitTestCase
{
    public function __get($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'validator': return $osm_app[Validator::class];
        }
        return parent::__get($property);
    }

    protected function expectFailure($errors, callable $callback) {
        $failed = false;
        try {
            $callback();
        }
        catch (ValidationFailed $e) {
            $this->assertEquals($errors, $e->errors);
            $failed = true;
        }

        $this->assertTrue($failed, 'Validation failure expected');
    }

    public function test_validation() {
        $this->expectFailure(['' => "Data expected"], function() {
            $this->validator->validate(null, 'string', ['required' => true]);
        });

        $this->expectFailure(['' => "Fill in this field"], function() {
            $this->validator->validate('   ', 'string', ['required' => true]);
        });

        $this->assertEquals(['qq'], $this->validator->validate([' qq '], 'string',
            ['array' => true, 'required' => true]));
    }
}