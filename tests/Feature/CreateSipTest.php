<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateSipTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function a_guest_or_a_client_cannot_create_sip()
    {
        $this->withExceptionHandling();
        $this->postJson(route('admin.sip.store'))->assertStatus(401);

        $this->signIn();
        $this->postJson(route('admin.sip.store'))->assertStatus(401);
    }

    /** @test */
    public function an_admin_can_create_sip()
    {
        $this->signInAdmin();
        $data = [
            'client_id' => 1,
            'folio_no' => '12345',
            'scheme_code' => 'ABCXYZ',
            'amount' => 2000,
            'installments' => 12,
            'interval' => 'monthly',
            'date' => '2012-11-12',
        ];
        $this->postJson(route('admin.sip.store'), $data)->assertStatus(201);

        $this->assertDatabaseHas('sip', $data);
    }
}
