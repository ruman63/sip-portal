<?php

namespace Tests;

use PHPUnit\Framework\Assert;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\Response;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp()
    {
        parent::setUp();

        EloquentCollection::macro('assertEquals', function ($expectedCollection) {
            Assert::assertCount($expectedCollection->count(), $this);
            $this->zip($expectedCollection)->each(function ($pair) {
                Assert::assertEquals($pair[0], $pair[1]);
            });
        });

        EloquentCollection::macro('assertHasKey', function ($key) {
            if (!$this->has($key)) {
                Assert::fail("expected key '$key' but it does not exist on Collection");
            }
        });

        EloquentCollection::macro('assertHasKeyAndNotNull', function ($key) {
            $this->assertHasKey($key);
            Assert::assertNotNull($this[$key], "was expecting a NOT NULL key '$key' but it is NULL");
        });

        Response::macro('getData', function ($key) {
            $data = $this->original->getData();
            if (!array_key_exists($key, $data)) {
                return;
            }
            return $data[$key];
        });

        \DB::statement('PRAGMA foreign_keys=on;');

        $this->withoutExceptionHandling();
    }

    protected function signIn($user = null)
    {
        $this->actingAs($user ?? factory('App\Client')->create(), 'web');
        return $this;
    }

    protected function signInAdmin($admin = null)
    {
        $this->actingAs($admin ?? factory('App\Admin')->create(), 'cpanel');
        return $this;
    }

    protected function assertApproximatelyEquals($expected, $actual, $decimals = 2)
    {
        return $this->assertEquals(
            round($expected, $decimals),
            round($actual, $decimals),
            'Failed asserting that ' . ($actual ?? "'null'") . " is approximately equal to expected ${expected}."
        );
    }

    protected function assertArrayHasSameElements(array $expected, array $actual)
    {
        sort($expected);
        sort($actual);
        return $this->assertEquals($expected, $actual);
    }
}
