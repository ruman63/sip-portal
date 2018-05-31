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

Route::group(['middleware' => 'auth'], function() {
    Route::get('/dashboard', "DashboardController@index")->name('dashboard');
    
    Route::get('/folios', "FoliosController@index")->name('folios.index');

    Route::get('/transactions', "TransactionsController@index")->name('transactions.index');
    Route::post('/transactions', "TransactionsController@store")->name('transactions.store');
    Route::get('/transactions/create', "TransactionsController@create")->name('transactions.create');
    
    Route::get('/allocations', "AllocationController@index")->name('allocations.index');
    Route::get('/portfolio', "PortfolioController@index")->name('portfolios.index');
    
    Route::get('/schemes', "SchemeController@index")->name('schemes.index');
    
});

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
            Route::post('logout', 'LoginController@logout')->name('admin.logout');
        });
    }
);