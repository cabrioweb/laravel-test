<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


/*
 * Frontend
 */
Route::get('/', function () {
    return view('welcome');
});


/*
 * Pages
 */
Route::group(['middleware' => 'stat'], function() {

    Route::get('/page/{alias}', 'PageController@index')
        ->where('alias', '[a-zA-Z0-9_-]+');

});


/*
 * Admin
 */
Route::group(['middleware' => 'auth'], function(){

    //Auth only paths


    //Admin paths
    Route::group(['namespace' => 'Admin'], function() {

        // Admin stat section
        Route::get('/admin/dashboard', array('as' => 'dashboard', 'uses' => 'DashboardController@index'));
        Route::get('/admin/dashboard/page/{alias}', 'DashboardController@pageStat')
            ->where('page', '[a-zA-Z0-9_-]+');

        // Admin login section
        Route::get('/admin', function(){
            return redirect()->route('dashboard');
        });

    });

});

/*
 * Auth
 */
Route::get('/login', 'Auth\AuthController@getLogin');
Route::post('/login', 'Auth\AuthController@postLogin');
Route::get('/logout', 'Auth\AuthController@getLogout');


