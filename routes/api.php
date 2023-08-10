<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// Route::post('/login', 'Api\LoginController@loginUserExample');
Route::get('/getuser', 'Api\LoginController@getUserDetails');
// Route::get('/logout', 'Api\LoginController@logOut');


// Route::get('login',[app\Http\Controller\Api\LoginController::class,'loginUserExample']);

Route::post('/login','Api\LoginController@loginUserExample');
// Route::get('/login', [App\Http\Controller\Api\LoginController::class, 'index'])->name('home');


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::post('login', 'AuthController@login');

// Route::group([

//     'middleware' => 'api',
//     'prefix' => 'auth'

// ], function ($router) {

    
//     Route::post('logout', 'AuthController@logout');
//     Route::post('refresh', 'AuthController@refresh');
//     Route::post('me', 'AuthController@me');

// });
