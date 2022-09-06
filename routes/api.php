<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/test',function (){

    $user = User::find(3);
    dd($user->getBalanceInSpecifiedCurrency(2));
});

Route::post('/transfer',[\App\Http\Controllers\API\TransactionController::class,'transferMoney']);
Route::post('/report',[\App\Http\Controllers\API\ReportController::class,'report']);
