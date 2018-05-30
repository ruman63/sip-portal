<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadTransactionsTest extends TestCase
{
    /** @test */
    public function a_guest_cannot_see_ransations()
    {
        $this->withExceptionHandling();
        
        $this->get(route('transactions.index'))
            ->assertStatus(401);
    }
}
