<?php

use App\Http\Controllers\MarketController;
use App\Http\Controllers\PlayerReportController;
use App\Http\Controllers\playerWeeklyController;
use App\Http\Controllers\retailerController;
use App\Http\Controllers\FrontController;
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

Auth::routes();

// Route::get('/User', 'demoController@index');

// Route::view('auth.login', '/');

Route::get('/', function () {
    return view('auth.login');
});

Route::post('/login_custom', 'Auth\LoginController@Login_custom');

Route::group(['middleware' => 'CheckAuth'], function () {

    Route::get('/logout', 'Auth\LoginController@logout');
    Route::get('/dashboard', 'DashboardController@index');

    Route::get('/admin/Franchise', 'AdminController@franchise');

    Route::get('/gamedraw/{id}', 'GameController@index');
    Route::get('/gameProfit', 'GameController@gameProfit');

    Route::post('/checkUserName', 'AdminController@CheckUserName');
    Route::resources([
        'admin' => 'AdminController',
        'subAdmin' => 'subAdminController',
        'stocks' => 'StocksController',
        'rates' => 'RatesController'
    ]);


    Route::get('stocks/delete/{id}', 'StocksController@destroy');
    Route::get('rates/delete/{id}', 'RatesController@destroy');

    Route::get('/point_requests_create', 'AdminController@point_requests_create');
    Route::post('/point_request_store', 'AdminController@point_request_store');

    Route::get('/Franchise/add_premium', 'AdminController@add_premium');
    Route::get('/Franchise/add_executive', 'AdminController@add_executive');
    Route::get('/Franchise/add_distributer', 'AdminController@add_executive');
    Route::get('/Franchise/add_classic', 'AdminController@add_classic');
    Route::get('/Franchise/add_agent', 'AdminController@add_classic');
    Route::get('/Franchise/add_player', 'AdminController@add_player');

    Route::get('/agents/add_agent', 'AdminController@add_premium');
    Route::get('/agents/add_premium', 'AdminController@add_premium');
    Route::get('/agents/add_executive', 'AdminController@add_executive');
    Route::get('/agents/add_classic', 'AdminController@add_classic');
    Route::get('/agents/add_player', 'AdminController@add_player');

    // Route::get('/point_request/{id}/{point}', 'AdminController@point_request_confirm');

    Route::get('/detail/{id}', 'AdminController@detail');
    Route::get('/admin/delete/{id}', 'AdminController@destroy');
    Route::get('/complaintAll/{id}', 'AdminController@complaintAll');
    Route::get('/users', 'AdminController@index');
    Route::get('/users/Franchise', 'AdminController@user_Franchise');
    Route::get('/users/distlist', 'AdminController@user_Franchise');
    Route::get('/users/plyrlist', 'AdminController@user_Franchise');
    Route::get('/users/agentlist', 'AdminController@user_Franchise');

    Route::get('/notification', 'AdminController@alert');

    Route::get('/do_generate_points', 'AdminController@do_generate_points');
    Route::post('/do_generate_points_create', 'AdminController@do_generate_points_create');
    Route::get('/generatePointList', 'AdminController@generatePointList');

    Route::get('super/edit/{id}', 'AdminController@edit');
    Route::get('distributer/edit/{id}', 'AdminController@edit');
    Route::get('super/detail/{id}', 'AdminController@detail');
    Route::get('/superDistributer', 'SuperDistributerController@index');
    Route::get('/retailer', 'ReatilerController@index');
    Route::get('/detail/{id}', 'AdminController@detail');

    Route::get('admin/delete/{id}', 'AdminController@destroy');
    Route::get('/winhistory', 'CommanController@winhistory');

    Route::get('/winningPercent', 'AdminController@winningPercent');
    Route::get('/adminPercent', 'AdminController@adminPercent');
    Route::post('/percent', 'AdminController@percent');

    Route::get('/Winbyadmin', 'AdminController@Winbyadmin');
    Route::post('/winnerIdAdmin', 'AdminController@winnerIdAdmin');
    Route::get('/Winbyadmin/delete/{id}', 'AdminController@WinByAdminDelete');

    Route::get('/announcement', 'CommanController@announcement');
    Route::post('/announcements', 'CommanController@announcements');

    Route::get('/gstnumber', 'CommanController@gstNumber');
    Route::post('/gstnumbers', 'CommanController@gstNumbers');

    Route::get('/version', 'CommanController@version');
    Route::post('/versions', 'CommanController@versions');

    Route::get('/complaint', 'AdminController@complaint');
    Route::get('/complaint/{status}/{id}', 'AdminController@complaintStatus');
    Route::get('complaint/delete/{id}', 'AdminController@complaintDelete');

    Route::get('/Advancedraw', 'CommanController@Advancedraw');
    Route::get('/allData/{id}', 'CommanController@allData');
    Route::get('/deletePlayerHistory/{id}', 'CommanController@deletePlayerHistory');

    Route::post('/get_data', 'AdminController@get_data');
    Route::post('/get_distributer', 'AdminController@get_distributer');

    Route::get('/PointFile', 'DashboardController@point_file');
    Route::get('/verify_pointFile', 'DashboardController@verify_pointFile');
    Route::get('/points_in', 'DashboardController@points_in');
    Route::get('/points_out', 'DashboardController@points_out');

    Route::get('/detail/{id}', 'AdminController@detail');

    Route::get('distributer/edit/{id}', 'AdminController@edit');
    Route::get('retailer/edit/{id}', 'AdminController@edit');

    Route::put('distributer/{id}', 'AdminController@update');
    Route::put('superDistributer/{id}', 'AdminController@update');
    Route::put('retailer/{id}', 'AdminController@update');

    Route::get('/distributer', 'DistributerController@index');
    Route::get('/retailer', 'ReatilerController@index');

    Route::get('player/detail/{id}', 'CommanController@playerHistory');

    Route::get('/OnPlayers', 'CommanController@OnPlayers');
    Route::get('/blockedPlayers', 'CommanController@blockedPlayers');

    Route::get('/history', 'CommanController@history');
    Route::get('/historyDetail/{id}', 'CommanController@historyDetail');

    Route::get('/Tnover', 'TnOverController@index');
    Route::get('/Tnover/detail/{id}', 'TnOverController@detail');
    Route::get('/transactions', 'CommanController@transactions');
    Route::post('/transaction', 'CommanController@transaction');

    Route::get('/cmbreport', 'CommanController@cmbreport');

    Route::get('/chpass', 'AdminController@chpass');
    Route::post('/chpassword', 'AdminController@chpassword');

    Route::get('/changepin', 'AdminController@changepin');
    Route::post('/chpin', 'AdminController@chpin');

    Route::get('transfercredit/{id}', 'AdminController@transfercredit');
    Route::get('point_request', 'AdminController@point_request');
    Route::get('point_request/{id}/{points}', 'AdminController@point_request_id');
    Route::post('transfercredits/{id}', 'AdminController@transfercredits');
    Route::get('adjustcredit/{id}', 'AdminController@adjustcredit');
    Route::post('adjustcredit/{id}', 'AdminController@adjustcrEdits');
    Route::get('banuser/{id}/{isActive}', 'AdminController@banuser');
    Route::get('blockUser/{id}/{isActive}', 'AdminController@blockUser');
    Route::get('/transfer', 'AdminController@transfer');
    Route::post('/search', 'AdminController@search');
    Route::post('/search_transfer', 'AdminController@search_transfer');
    Route::get('/success/{id}', 'AdminController@success');
    Route::get('/reject/{id}', 'AdminController@reject');
    Route::post('/change/stock/status', 'StocksController@changeStockStatus');
    Route::get('playerReport', [PlayerReportController::class,'index'])->name('playerReport');
    Route::get('playerwReport', [playerWeeklyController::class,'index'])->name('playerwReport');
    Route::get('genrep/{id}', [PlayerReportController::class,'show'])->name('genrep.show');
    Route::get('betsReport', [PlayerReportController::class,'Bets'])->name('betsReport');
    Route::get('/commission', 'commissionController@index');



    Route::get('/markets', [MarketController::class, 'index'])->name('markets.index');
    Route::get('/markets/create', [MarketController::class, 'create'])->name('markets.create');
    Route::post('/markets', [MarketController::class, 'store'])->name('markets.store');
    Route::get('/markets/{id}/edit', [MarketController::class, 'edit'])->name('markets.edit');
    Route::put('/markets/{id}', [MarketController::class, 'update'])->name('markets.update');
    Route::delete('/markets/{id}', [MarketController::class, 'destroy'])->name('markets.destroy');

    Route::get('/front-data', [FrontController::class, 'index'])->name('front-data');

});
