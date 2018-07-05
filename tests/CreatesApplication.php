<?php

namespace Tests;

use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\TestResponse;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        Hash::driver('bcrypt')->setRounds(4);

        TestResponse::macro('viewData', function ($key) {
            $this->ensureResponseHasView();
            $this->assertViewHas($key);
            if (is_null($key)) {
                return $this->original->getData();
            }
            return $this->original->getData()[$key];
        });

        return $app;
    }
}
