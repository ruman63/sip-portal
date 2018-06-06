<?php

namespace Tests\Feature\Admin;

use Tests\ValidationTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class UpdateTransactionValidationsTest extends ValidationTestCase
{
    use RefreshDatabase;
    
    public function setUp()
    {
        parent::setUp();
        $this->withExceptionHandling()->signInAdmin();
        $this->scheme = create('App\Scheme');
        $this->transaction = create('App\Transaction');
    }
    
    /** @test */
    public function uid_is_required_to_update()
    {
        $response = $this->patchJson(
            route('admin.transactions.update', 1), 
            $this->dataWithout('uid')
        )->assertStatus(422)->json();

        $this->assertArrayHasKey('uid', $response['errors']);
    } 

    /** @test */
    public function uid_is_required_to_be_unique_to_update()
    {
        create('App\Transaction', ['uid' => '1234']);

        $response = $this->patchJson(
            route('admin.transactions.update', 1), 
            $this->dataWith('uid', '1234')
        )->assertStatus(422)->json();

        $this->assertArrayHasKey('uid', $response['errors']);

        $this->patchJson(
            route('admin.transactions.update', 1), 
            $this->dataWith('uid', $this->transaction->uid)
        )->assertStatus(200);
    } 

    /** @test */
    public function folio_no_is_required_to_update()
    {
        $response = $this->patchJson(
            route('admin.transactions.update', 1), 
            $this->dataWithout('folio_no')
        )->assertStatus(422)->json();
        
        $this->assertArrayHasKey('folio_no', $response['errors']);
    } 

    /** @test */
    public function rate_is_required_to_update()
    {
        $response = $this->patchJson(
            route('admin.transactions.update', 1), 
            $this->dataWithout('rate')
        )->assertStatus(422)->json();
        
        $this->assertArrayHasKey('rate', $response['errors']);
    } 

    /** @test */
    public function date_is_required_to_update()
    {
        $response = $this->patchJson(
            route('admin.transactions.update', 1), 
            $this->dataWithout('date')
        )->assertStatus(422)->json();
        
        $this->assertArrayHasKey('date', $response['errors']);
    }
    
    /** @test */
    public function date_is_required_to_be_in_yyyymmdd_format_to_update()
    {
        $response = $this->patchJson(
            route('admin.transactions.update', 1), 
            $this->dataWith('date', '30-11-2012')
        )->assertStatus(422)->json();

        $this->assertArrayHasKey('date', $response['errors']);
        
        $this->patchJson(
            route('admin.transactions.update', 1), 
            $this->dataWith('date', '2012-11-30')
        )->assertStatus(200);
    }

    /** @test */
    public function date_cannot_be_later_than_today()
    {
        $tomorrow = Carbon::tomorrow()->format('Y-m-d');
        $response = $this->patchJson(
            route('admin.transactions.update', 1), 
            $this->dataWith('date', $tomorrow)
        )->assertStatus(422)->json();

        $this->assertArrayHasKey('date', $response['errors']);
    }

    /** @test */
    public function type_is_required_to_update()
    {
        $response = $this->patchJson(
            route('admin.transactions.update', 1),
            $this->dataWithout('type')
        )->assertStatus(422)->json();
        
        $this->assertArrayHasKey('type', $response['errors']);
    } 

    /** @test */
    public function scheme_code_is_required_to_update()
    {
        $response = $this->patchJson(
            route('admin.transactions.update', 1),
            $this->dataWithout('scheme_code')
        )->assertStatus(422)->json();
        
        $this->assertArrayHasKey('scheme_code', $response['errors']);
    } 

    /** @test */
    public function scheme_code_must_be_valid_code_that_exist_in_database()
    {
        $response = $this->patchJson(
            route('admin.transactions.update', 1), 
            $this->dataWith('scheme_code', 'INVALIDCODE')
        )->assertStatus(422)->json();

        $this->assertArrayHasKey('scheme_code', $response['errors']);
    } 

    /** @test */
    public function amount_is_required_to_update()
    {
        $response = $this->patchJson(
            route('admin.transactions.update', 1), 
            $this->dataWithout('amount')
        )->assertStatus(422)->json();
        
        $this->assertArrayHasKey('amount', $response['errors']);
    } 

    protected function data() {
        return [
            'rate' => 13,
            'type' => 'ADD',
            'amount' => 2000,
            'uid' => 'TR101',
            'date' => '2017-08-01',
            'folio_no' => '1234/12',
            'scheme_code' => $this->scheme->scheme_code,
        ];
    }
}
