<?php

namespace Tests\Feature\Admin;

use Tests\ValidationTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use App\Client;

class CreateSipValidationsTest extends ValidationTestCase
{
    use RefreshDatabase;
    
    public function setUp()
    {
        parent::setUp();
        $this->client = create('App\Client');
        $this->withExceptionHandling()->signInAdmin();
        $this->scheme = create('App\Scheme');
    }
    
    /** @test */
    public function client_id_is_required()
    {
        $response = $this->postJson(
            route('admin.sip.store'), 
            $this->dataWithout('client_id')
        )->assertStatus(422)->json();

        $this->assertArrayHasKey('client_id', $response['errors']);
    } 
    
    /** @test */
    public function client_id_should_exist_in_database()
    {
        $client = create(Client::class);
        
        $response = $this->postJson(
            route('admin.sip.store'), 
            $this->dataWith('client_id', 9999)
        )->assertStatus(422)->json();
            
        $this->assertArrayHasKey('client_id', $response['errors']);

        $this->postJson(
            route('admin.sip.store'), 
            $this->dataWith('client_id', $client->id)
        )->assertStatus(201);
    } 
    
    /** @test */
    public function folio_no_is_required()
    {
        $response = $this->postJson(
            route('admin.sip.store'),
            $this->dataWithout('folio_no')
        )->assertStatus(422)->json();
        
        $this->assertArrayHasKey('folio_no', $response['errors']);
    } 
    
    /** @test */
    public function date_is_required()
    {
        $response = $this->postJson(
            route('admin.sip.store'),
            $this->dataWithout('date')
        )->assertStatus(422)->json();
            
        $this->assertArrayHasKey('date', $response['errors']);
    }
    
    /** @test */
    public function date_is_required_to_be_in_correct_format()
    {
        $response = $this->postJson(
            route('admin.sip.store'),
            $this->dataWith('date', '02-02-2018')
        )->assertStatus(422)->json();

        $this->assertArrayHasKey('date', $response['errors']);
    }
    
    /** @test */
    public function date_cannot_be_earlier_than_today()
    {
        $response = $this->postJson(
            route('admin.sip.store'),
            $this->dataWith('date', Carbon::yesterday()->format('Y-m-d')
        ) )->assertStatus(422)->json();

        $this->assertArrayHasKey('date', $response['errors']);
    }
    
    /** @test */
    public function interval_is_required()
    {
        $response = $this->postJson(
            route('admin.sip.store'), 
            $this->dataWithout('interval')
        )->assertStatus(422)->json();
        
        $this->assertArrayHasKey('interval', $response['errors']);
    } 

    /** @test */
    public function interval_could_be_monthly_or_weekly()
    {
        $response = $this->postJson(
            route('admin.sip.store'), 
            $this->dataWith('interval', 'unknown')
        )->assertStatus(422)->json();
        
        $this->assertArrayHasKey('interval', $response['errors']);
    } 

    /** @test */
    public function scheme_code_is_required()
    {
        $response = $this->postJson(
            route('admin.sip.store'), 
            $this->dataWithout('scheme_code')
        )->assertStatus(422)->json();
        
        $this->assertArrayHasKey('scheme_code', $response['errors']);
    } 
    
    /** @test */
    public function scheme_code_must_be_valid_code_that_exist_in_database()
    {
        $response = $this->postJson(
            route('admin.sip.store'), 
            $this->dataWith('scheme_code', 'ABCDWXYZ123')
        )->assertStatus(422)->json();
        
        $this->assertArrayHasKey('scheme_code', $response['errors']);
    } 
    
    /** @test */
    public function amount_is_required()
    {
        $response = $this->postJson(
            route('admin.sip.store'),
            $this->dataWithout('amount')
        )->assertStatus(422)->json();
        
        $this->assertArrayHasKey('amount', $response['errors']);
    }
    
    /** @test */
    public function installments_field_is_required()
    {
        $response = $this->postJson(
            route('admin.sip.store'),
            $this->dataWithout('installments')
        )->assertStatus(422)->json();
        
        $this->assertArrayHasKey('installments', $response['errors']);
    }

    /** @test */
    public function installments_field_cannot_be_fractional()
    {
        $response = $this->postJson(
            route('admin.sip.store'),
            $this->dataWith('installments', 2.54)
        )->assertStatus(422)->json();
        
        $this->assertArrayHasKey('installments', $response['errors']);
    }
    
    protected function data() {
        return [
            'amount' => 2000,
            'folio_no' => '12345',
            'date' => Carbon::tomorrow()->format('Y-m-d'),
            'client_id' => $this->client->id,
            'scheme_code' => $this->scheme->scheme_code,
            'interval' => 'monthly',
            'installments' => '12'
        ];
    }
}
