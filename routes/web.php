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

Route::get('/', "PagesController@index")->name('index');
Auth::routes();

Route::group(['middleware' => 'auth:web'], function() {
    Route::get('/dashboard', "DashboardController@index")->name('dashboard');
    Route::get('/transactions', "TransactionsController@index")->name('transactions.index');
    Route::get('/allocations', "AllocationController@index")->name('allocations.index');
    Route::get('/portfolio', "PortfolioController@index")->name('portfolios.index');
    Route::patch('/change-password', "Auth\\ClientPasswordController@change")->name('password.update');
    Route::get('/change-password', "Auth\\ClientPasswordController@edit")->name('password.edit');
});
    
Route::get('/schemes', "SchemeController@index")->name('schemes.index')->middleware('auth:web,cpanel');

Route::group([
        'prefix'=> 'admin', 
        'namespace' => 'Admin',
    ], 
    function() {
        Route::get('login', 'LoginController@showLoginForm')->name('admin.login');
        Route::post('login', 'LoginController@login')->name('admin.login');
        Route::group(['middleware' => 'auth:cpanel'], function() {
            Route::get('/', 'DashboardController@index')->name('admin.dashboard');
            Route::get('clients', 'ClientsController@index')->name('clients.index');
            Route::post('clients/{client}/login', 'ClientsController@loginAs')->name('clients.login-as');
            Route::post('clients/logout', 'ClientsController@logout')->name('clients.logout');
            Route::get('clients/{client}/transactions', 'ClientTransactionController@index')->name('clients.transactions');
            Route::get('/folios', "FoliosController@index")->name('admin.folios.index');
            Route::get('/sip', 'SipController@index')->name('admin.sip.index');     
            Route::get('/transactions', 'TransactionController@index')->name('admin.transactions.index');     
            Route::post('/transactions', 'TransactionController@store')->name('admin.transactions.store');     
            Route::patch('/transactions/{transaction}', 'TransactionController@update')->name('admin.transactions.update');  
            Route::delete('/transactions/{transaction}', 'TransactionController@destroy')->name('admin.transactions.destroy');   
            Route::post('logout', 'LoginController@logout')->name('admin.logout');
        });
    }
);