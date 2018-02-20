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
Route::get('/nav', "NavController@index")->name('nav');
Route::get('/dashboard', "DashboardController@index")->name('dashboard');

Auth::routes();

// Route::get('/dashboard', 'HomeController@index')->name('user.dashboard');
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
            Route::post('logout', 'LoginController@logout')->name('admin.logout');
        });
    }
);