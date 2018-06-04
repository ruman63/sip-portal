<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class UpdateTransactionValidationsTest extends TestCase
{
    use RefreshDatabase;
    
    public function setUp()
    {
        parent::setUp();
        $this->withExceptionHandling()->signIn();
        $this->scheme = create('App\Scheme');
        $this->transaction = create('App\Transaction', ['client_id' => auth()->guard('web')->id()]);
    }
    
    /** @test */
    public function uid_is_required_to_update()
    {
        $response = $this->patchJson(route('transactions.update', 1), [
            'folio_no' => '1234/12',
            'rate' => 13,
            'date' => '2017-06-12',
            'type' => 'REDEEM',
            'scheme_code' => $this->scheme->scheme_code,
            'amount' => 2000
        ])->assertStatus(422)->json();

        $this->assertArrayHasKey('uid', $response['errors']);
    } 

    /** @test */
    public function uid_is_required_to_be_unique_to_update()
    {
        create('App\Transaction', ['uid' => '1234']);
        $data = [
            'folio_no' => '1234/12',
            'rate' => 13,
            'date' => '2017-06-12',
            'type' => 'REDEEM',
            'scheme_code' => $this->scheme->scheme_code,
            'amount' => 2000
        ];

        $response = $this->patchJson(route('transactions.update', 1), $data + [ 'uid' => '1234' ])
            ->assertStatus(422)->json();

        $this->assertArrayHasKey('uid', $response['errors']);

        $this->patchJson(route('transactions.update', 1), $data + [ 'uid' => $this->transaction->uid ])
            ->assertStatus(200);
    } 

    /** @test */
    public function folio_no_is_required_to_update()
    {
        $response = $this->patchJson(route('transactions.update', 1), [
            'uid' => 'TR101',
            'rate' => 13,
            'date' => '2017-06-12',
            'type' => 'REDEEM',
            'scheme_code' => $this->scheme->scheme_code,
            'amount' => 2000
        ])->assertStatus(422)->json();
        
        $this->assertArrayHasKey('folio_no', $response['errors']);
    } 

    /** @test */
    public function rate_is_required_to_update()
    {
        $response = $this->patchJson(route('transactions.update', 1), [
            'folio_no' => '12345',
            'uid' => 'TR101',
            'date' => '2017-06-12',
            'type' => 'REDEEM',
            'scheme_code' => $this->scheme->scheme_code,
            'amount' => 2000
        ])->assertStatus(422)->json();
        
        $this->assertArrayHasKey('rate', $response['errors']);
    } 

    /** @test */
    public function date_is_required_to_update()
    {
        $response = $this->patchJson(route('transactions.update', 1), [
            'folio_no' => '12345',
            'uid' => 'TR101',
            'rate' => 13,
            'type' => 'REDEEM',
            'scheme_code' => $this->scheme->scheme_code,
            'amount' => 2000
        ])->assertStatus(422)->json();
        
        $this->assertArrayHasKey('date', $response['errors']);
    }
    
    /** @test */
    public function date_is_required_to_be_in_correct_format_to_update()
    {
        $data = [
            'folio_no' => '12345',
            'uid' => 'TR101',
            'rate' => 13,
            'type' => 'REDEEM',
            'date' => $incorrectDateFormat = '1231231-123-11',
            'scheme_code' => $this->scheme->scheme_code,
            'amount' => 2000
        ];

        $response = $this->patchJson(route('transactions.update', 1), $data)->assertStatus(422)->json();
        $this->assertArrayHasKey('date', $response['errors']);
        
        $data['date'] = '2012-11-30';
        $this->patchJson(route('transactions.update', 1), $data)->assertStatus(200);
    }

    /** @test */
    public function date_cannot_be_later_than_today()
    {
        $tomorrow = Carbon::tomorrow()->format('Y-m-d');
        $response = $this->patchJson(route('transactions.update', 1), [
            'folio_no' => '12345',
            'uid' => 'TR101',
            'rate' => 13,
            'type' => 'REDEEM',
            'date' => $tomorrow,
            'scheme_code' => $this->scheme->scheme_code,
            'amount' => 2000
        ])->assertStatus(422)->json();

        $this->assertArrayHasKey('date', $response['errors']);
    }

    /** @test */
    public function type_is_required_to_update()
    {
        $response = $this->patchJson(route('transactions.update', 1), [
            'folio_no' => '12345',
            'uid' => 'TR101',
            'rate' => 13,
            'date' => '2017-06-12',
            'scheme_code' => $this->scheme->scheme_code,
            'amount' => 2000
        ])->assertStatus(422)->json();
        
        $this->assertArrayHasKey('type', $response['errors']);
    } 

    /** @test */
    public function scheme_code_is_required_to_update()
    {
        $response = $this->patchJson(route('transactions.update', 1), [
            'folio_no' => '12345',
            'uid' => 'TR101',
            'rate' => 13,
            'date' => '2017-06-12',
            'type' => 'REDEEM',
            'amount' => 2000
        ])->assertStatus(422)->json();
        
        $this->assertArrayHasKey('scheme_code', $response['errors']);
    } 

    /** @test */
    public function scheme_code_must_be_valid_code_that_exist_in_database()
    {
        $data = [
            'folio_no' => '12345',
            'uid' => 'TR101',
            'rate' => 13,
            'date' => '2017-06-12',
            'type' => 'REDEEM',
            'amount' => 2000,
            'scheme_code' => 'XYZA1213123BC'
        ];

        $response = $this->patchJson(route('transactions.update', 1), $data)
            ->assertStatus(422)->json();
        $this->assertArrayHasKey('scheme_code', $response['errors']);

        $data['scheme_code'] = $this->scheme->scheme_code;
        $this->patchJson(route('transactions.update', 1), $data)
            ->assertStatus(200);
    } 

    /** @test */
    public function amount_is_required_to_update()
    {
        $response = $this->patchJson(route('transactions.update', 1), [
            'folio_no' => '12345',
            'uid' => 'TR101',
            'amount' => 13,
            'date' => '2017-06-12',
            'type' => 'REDEEM',
            'scheme_code' => $this->scheme->scheme_code,
        ])->assertStatus(422)->json();
        
        $this->assertArrayHasKey('rate', $response['errors']);
    } 
}
