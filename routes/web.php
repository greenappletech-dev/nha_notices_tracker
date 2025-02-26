<?php

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

Auth::routes();
Route::get('/', function () {
    return view('example');
});

Route::get('/demandnotice', [DemandNoticeController::class, 'index']);
Route::get('/demandnotice/show', [DemandNoticeController::class, 'show']);
Route::post('/demandnotice/store', [DemandNoticeController::class, 'store']);
Route::put('/demandnotice/update/{id}', [DemandNoticeController::class, 'update']);
Route::delete('/demandnotice/destroy/{id}', [DemandNoticeController::class, 'destroy']);
