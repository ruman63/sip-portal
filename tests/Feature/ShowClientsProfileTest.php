<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\BankAccount;
use App\Address;

class ShowClientsProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function only_authenticated_users_can_see_profile_page()
    {
        $this->withExceptionHandling()
            ->get(route('profile.show'))
            ->assertRedirect(route('index'));
    }

    /** @test */
    public function shows_clients_own_profile_page()
    {
        $this->signIn($client = create(Client::class));
        $response = $this->get(route('profile.show'));

        $response->assertSuccessful()
            ->assertViewIs('profile.show')
            ->assertSee($client->name);
    }

    /** @test */
    public function profile_page_has_correct_client_data()
    {
        $this->signIn($client = create(Client::class));
        $defaultBankAccount = create(BankAccount::class, ['owner_id' => auth()->id()]);
        $defaultBankAccount->setAsDefault();
        $bankAccounts = create(BankAccount::class, ['owner_id' => auth()->id()], 2);
        $address = create(Address::class, ['client_id' => auth()->id()]);

        $viewClient = $this->get(route('profile.show'))
            ->assertSuccessful()
            ->assertViewIs('profile.show')
            ->viewData('client');

        $this->assertInstanceOf(Client::class, $viewClient);
        $this->assertCount(3, $viewClient->bankAccounts);
        $this->assertTrue($viewClient->defaultBankAccount->is($defaultBankAccount));
        $this->assertInstanceOf(Address::class, $viewClient->address);
        $this->assertTrue($viewClient->address->is($address));
    }
}
