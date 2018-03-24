<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp()
    {
        parent::setUp();

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
}
