<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadSchemesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function index_view_is_returned_from_HTTP_request()
    {
        $this->signInAdmin();
        
        $this->get(route('admin.schemes.index'))
            ->assertSuccessful()
            ->assertViewIs('admin.schemes.index')
            ->assertSee('Schemes');
    }
}
