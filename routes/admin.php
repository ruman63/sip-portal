<?php 

Route::get('login', 'LoginController@showLoginForm')->name('admin.login');
Route::post('login', 'LoginController@login')->name('admin.login');
Route::group(['middleware' => 'auth:cpanel'], function () {
    Route::get('/', 'DashboardController@index')->name('admin.dashboard');
    Route::get('clients', 'ClientsController@index')->name('clients.index');
    Route::post('clients/{client}/login', 'ClientsController@loginAs')->name('clients.login-as');
    Route::post('clients/logout', 'ClientsController@logout')->name('clients.logout');
    Route::get('clients/{client}/transactions', 'ClientTransactionController@index')->name('clients.transactions');
    Route::get('/folios', 'FoliosController@index')->name('admin.folios.index');
    Route::get('/schemes', 'SchemeController@index')->name('admin.schemes.index');
    Route::post('/schemes', 'SchemeController@store')->name('admin.schemes.store');
    Route::get('/sip', 'SipController@index')->name('admin.sip.index');
    Route::post('/sip', 'SipController@store')->name('admin.sip.store');
    Route::post('/portfolios/generate', 'GeneratePortfolioController@store')->name('admin.generate-portfolios.store');
    Route::get('/transactions', 'TransactionController@index')->name('admin.transactions.index');
    Route::post('/transactions', 'TransactionController@store')->name('admin.transactions.store');
    Route::patch('/transactions/{transaction}', 'TransactionController@update')->name('admin.transactions.update');
    Route::delete('/transactions/{transaction}', 'TransactionController@destroy')->name('admin.transactions.destroy');
    Route::post('logout', 'LoginController@logout')->name('admin.logout');
});
