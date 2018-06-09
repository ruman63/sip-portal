<?php

namespace Tests;

use PHPUnit\Framework\Assert;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp()
    {
        parent::setUp();

        EloquentCollection::macro('assertEquals', function($expectedCollection) {
            Assert::assertCount($expectedCollection->count(), $this);
            $this->zip($expectedCollection)->each(function($pair) {
                Assert::assertEquals($pair[0], $pair[1]);
            });
        });

        \DB::statement('PRAGMA foreign_keys=on;');

        $this->withoutExceptionHandling();
    }

    protected function signIn($user = null) {
        $this->actingAs($user ?? factory('App\Client')->create(), 'web');
        return $this;
    }

    protected function signInAdmin($admin = null) {
        $this->actingAs($admin ?? factory('App\Admin')->create() , 'cpanel');
        return $this;
    }

    protected function assertApproximatelyEquals($expected, $actual, $decimals = 2) {
        return $this->assertEquals(
            round($expected, $decimals), 
            round($actual, $decimals),
            "Failed asserting that " . ($actual ?? "'null'") . " is approximately equal to expected ${expected}."
        );
    }
}
