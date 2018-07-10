<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'PagesController@index')->name('index');
Auth::routes();

Route::group(['middleware' => 'auth:web'], function () {
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
    Route::get('/profile', 'ProfileController@show')->name('profile.show');
    Route::patch('/profile', 'ProfileController@update')->name('profile.update');
    Route::get('/transactions', 'TransactionsController@index')->name('transactions.index');
    Route::get('/allocations', 'AllocationController@index')->name('allocations.index');
    Route::get('/portfolio', 'PortfolioController@index')->name('portfolios.index');
    Route::patch('/change-password', 'Auth\\ClientPasswordController@change')->name('password.update');
    Route::get('/change-password', 'Auth\\ClientPasswordController@edit')->name('password.edit');
    Route::post('/address', 'AddressController@store')->name('address.store');
    Route::patch('/address', 'AddressController@update')->name('address.update');
    Route::post('/bank-account', 'BankAccountController@store')->name('bank-account.store');
    Route::patch('/bank-account/{bankAccount}', 'BankAccountController@update')->name('bank-account.update');
    Route::delete('/bank-account/{bankAccount}', 'BankAccountController@destroy')->name('bank-account.destroy');
    Route::post('/bank-account/{bankAccount}/default', 'DefaultBankAccountController@store')->name('bank-account.default');
});

Route::get('/schemes', 'SchemeController@index')->name('schemes.index')->middleware('auth:web,cpanel');
Route::get('/schemes/types', 'SchemeController@types')->name('schemes.types')->middleware('auth:web,cpanel');
Route::get('/schemes/agents', 'SchemeController@agents')->name('schemes.agents')->middleware('auth:web,cpanel');

Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], base_path('routes/admin.php'));