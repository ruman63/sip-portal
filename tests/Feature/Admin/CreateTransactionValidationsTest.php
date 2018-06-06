<?php

namespace Tests\Feature\Admin;

use Tests\ValidationTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class CreateTransactionValidationsTest extends ValidationTestCase
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
    public function uid_is_required()
    {
        $response = $this->postJson(
            route('admin.transactions.store'), 
            $this->dataWithout('uid')
        )->assertStatus(422)->json();

        $this->assertArrayHasKey('uid', $response['errors']);
    } 
    
    /** @test */
    public function uid_is_required_to_be_unique()
    {
        create('App\Transaction', ['uid' => '1234']);

        $response = $this->postJson(
            route('admin.transactions.store'), 
            $this->dataWith('uid', '1234')
        )->assertStatus(422)->json();
            
        $this->assertArrayHasKey('uid', $response['errors']);
    } 
    
    /** @test */
    public function folio_no_is_required()
    {
        $response = $this->postJson(
            route('admin.transactions.store'),
            $this->dataWithout('folio_no')
        )->assertStatus(422)->json();
        
        $this->assertArrayHasKey('folio_no', $response['errors']);
    } 
    
    /** @test */
    public function rate_is_required()
    {
        $response = $this->postJson(
            route('admin.transactions.store'), 
            $this->dataWithout('rate')
        )->assertStatus(422)->json();
        
        $this->assertArrayHasKey('rate', $response['errors']);
    } 
    
    /** @test */
    public function date_is_required()
    {
        $response = $this->postJson(
            route('admin.transactions.store'),
            $this->dataWithout('date')
        )->assertStatus(422)->json();
            
        $this->assertArrayHasKey('date', $response['errors']);
    }
    
    /** @test */
    public function date_is_required_to_be_in_correct_format()
    {
        $response = $this->postJson(
            route('admin.transactions.store'),
            $this->dataWith('date', '02-02-2018')
        )->assertStatus(422)->json();

        $this->assertArrayHasKey('date', $response['errors']);
    }
    
    /** @test */
    public function date_cannot_be_later_than_today()
    {
        $tomorrow = Carbon::tomorrow()->format('Y-m-d');
        $response = $this->postJson(
            route('admin.transactions.store'),
            $this->dataWith('date', Carbon::tomorrow()->addDay()->format('Y-m-d')
        ) )->assertStatus(422)->json();

        $this->assertArrayHasKey('date', $response['errors']);
    }
    
    /** @test */
    public function type_is_required()
    {
        $response = $this->postJson(
            route('admin.transactions.store'), 
            $this->dataWithout('type')
        )->assertStatus(422)->json();
        
        $this->assertArrayHasKey('type', $response['errors']);
    } 

    /** @test */
    public function scheme_code_is_required()
    {
        $response = $this->postJson(
            route('admin.transactions.store'), 
            $this->dataWithout('scheme_code')
        )->assertStatus(422)->json();
        
        $this->assertArrayHasKey('scheme_code', $response['errors']);
    } 
    
    /** @test */
    public function scheme_code_must_be_valid_code_that_exist_in_database()
    {
        $response = $this->postJson(
            route('admin.transactions.store'), 
            $this->dataWith('scheme_code', 'ABCDWXYZ123')
        )->assertStatus(422)->json();
        
        $this->assertArrayHasKey('scheme_code', $response['errors']);
    } 
    
    /** @test */
    public function amount_is_required()
    {
        $response = $this->postJson(
            route('admin.transactions.store'),
            $this->dataWithout('amount')
        )->assertStatus(422)->json();
        
        $this->assertArrayHasKey('amount', $response['errors']);
    } 
    
    /** @test */
    public function client_id_for_existing_client_is_required()
    {
        $response = $this->postJson(
            route('admin.transactions.store'),
            $this->dataWithout('client_id')
        )->assertStatus(422)->json();
        
        $this->assertArrayHasKey('client_id', $response['errors']);
        
        $response = $this->postJson(
            route('admin.transactions.store'),
            $this->dataWith('client_id', 999)
        )->assertStatus(422)->json();

        $this->assertArrayHasKey('client_id', $response['errors']);
    }


    protected function data() {
        return [
            'rate' => 13,
            'amount' => 2000,
            'uid' => 'TR101',
            'type' => 'REDEEM',
            'folio_no' => '12345',
            'date' => '2017-06-12',
            'client_id' => $this->client->id,
            'scheme_code' => $this->scheme->scheme_code,
        ];
    }
}
