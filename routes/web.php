<?php

use App\Http\Controllers\DeliveryController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DemandNoticeController;


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

Auth::routes(['verify' => true, 'register' => false]); //, 'register' => false

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', function () {
        return view('example');
    });
    Route::get('/home', function () {
        return view('example');
    });
    Route::prefix('deliveries')->group(function () {
        Route::get('/', [DeliveryController:: class, 'index']);
        Route::get('gather_project/{id}',[DeliveryController::class, 'gather_project']);
        Route::get('gather_beneficiaries/{id}',[DeliveryController::class, 'gather_beneficiaries']);
        Route::post('store', [DeliveryController::class, 'store']);
    });
    
}); 

Route::get('/demandnotice', [DemandNoticeController::class, 'index']);
Route::get('/demandnotice/show', [DemandNoticeController::class, 'show']);
Route::post('/demandnotice/store', [DemandNoticeController::class, 'store']);
Route::put('/demandnotice/update/{id}', [DemandNoticeController::class, 'update']);
Route::delete('/demandnotice/destroy/{id}', [DemandNoticeController::class, 'destroy']);
