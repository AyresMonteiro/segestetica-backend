<?php

use App\Http\Controllers\{
    StateController,
};
use App\Http\Helpers\GenericHelper;
use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'states'], function () {
    Route::get('/', GenericHelper::genericTryCatchFactory(StateController::index()));
    Route::get('/{id}', GenericHelper::genericTryCatchFactory(StateController::show()));
    Route::post('/', GenericHelper::genericTryCatchFactory(StateController::store()));
    Route::put('/{id}', GenericHelper::genericTryCatchFactory(StateController::update()));
    Route::delete('/{id}', GenericHelper::genericTryCatchFactory(StateController::destroy()));
});

