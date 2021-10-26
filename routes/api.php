<?php

use App\Http\Controllers\{
    CityController,
    EstablishmentController,
    NeighborhoodController,
    StateController,
    StreetController,
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

Route::group(['prefix' => 'cities'], function () {
    Route::get('/', GenericHelper::genericTryCatchFactory(CityController::index()));
    Route::get('/{id}', GenericHelper::genericTryCatchFactory(CityController::show()));
    Route::post('/', GenericHelper::genericTryCatchFactory(CityController::store()));
    Route::put('/{id}', GenericHelper::genericTryCatchFactory(CityController::update()));
    Route::delete('/{id}', GenericHelper::genericTryCatchFactory(CityController::destroy()));
});

Route::group(['prefix' => 'neighborhoods'], function () {
    Route::get('/', GenericHelper::genericTryCatchFactory(NeighborhoodController::index()));
    Route::get('/{id}', GenericHelper::genericTryCatchFactory(NeighborhoodController::show()));
    Route::post('/', GenericHelper::genericTryCatchFactory(NeighborhoodController::store()));
    Route::put('/{id}', GenericHelper::genericTryCatchFactory(NeighborhoodController::update()));
    Route::delete('/{id}', GenericHelper::genericTryCatchFactory(NeighborhoodController::destroy()));
});

Route::group(['prefix' => 'streets'], function () {
    Route::get('/', GenericHelper::genericTryCatchFactory(StreetController::index()));
    Route::get('/{id}', GenericHelper::genericTryCatchFactory(StreetController::show()));
    Route::post('/', GenericHelper::genericTryCatchFactory(StreetController::store()));
    Route::put('/{id}', GenericHelper::genericTryCatchFactory(StreetController::update()));
    Route::delete('/{id}', GenericHelper::genericTryCatchFactory(StreetController::destroy()));
});

Route::group(['prefix' => 'establishments'], function () {
    Route::get('/', GenericHelper::genericTryCatchFactory(EstablishmentController::index()));
    Route::get('/confirm', GenericHelper::genericTryCatchFactory(EstablishmentController::confirmEmail()));
    Route::get('/{uuid}', GenericHelper::genericTryCatchFactory(EstablishmentController::show()));
    Route::post('/', GenericHelper::genericTryCatchFactory(EstablishmentController::store()));
    Route::post('/login', GenericHelper::genericTryCatchFactory(EstablishmentController::login()));
    Route::put('/{uuid}', GenericHelper::genericTryCatchFactory(EstablishmentController::update()));
    Route::delete('/{uuid}', GenericHelper::genericTryCatchFactory(EstablishmentController::destroy()));
});
