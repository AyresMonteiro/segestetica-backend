<?php

use App\Http\Adapters\LaravelHTTPRequestAdapter;
use App\Http\Controllers\{
    CityController,
    EstablishmentController,
    NeighborhoodController,
    ScheduleController,
    ServiceController,
    StateController,
    StreetController,
    UserController,
};
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
    Route::get('/', LaravelHTTPRequestAdapter::handle(StateController::index()));
    Route::get('/{id}', LaravelHTTPRequestAdapter::handle(StateController::show()));
    // Route::post('/', LaravelHTTPRequestAdapter::handle(StateController::store()));
    // Route::put('/{id}', LaravelHTTPRequestAdapter::handle(StateController::update()));
    // Route::delete('/{id}', LaravelHTTPRequestAdapter::handle(StateController::destroy()));
});

Route::group(['prefix' => 'cities'], function () {
    Route::get('/', LaravelHTTPRequestAdapter::handle(CityController::index()));
    Route::get('/{id}', LaravelHTTPRequestAdapter::handle(CityController::show()));
    // Route::post('/', LaravelHTTPRequestAdapter::handle(CityController::store()));
    // Route::put('/{id}', LaravelHTTPRequestAdapter::handle(CityController::update()));
    // Route::delete('/{id}', LaravelHTTPRequestAdapter::handle(CityController::destroy()));
});

Route::group(['prefix' => 'neighborhoods'], function () {
    Route::get('/', LaravelHTTPRequestAdapter::handle(NeighborhoodController::index()));
    Route::get('/{id}', LaravelHTTPRequestAdapter::handle(NeighborhoodController::show()));
    Route::post('/', LaravelHTTPRequestAdapter::handle(NeighborhoodController::store()));
    Route::put('/{id}', LaravelHTTPRequestAdapter::handle(NeighborhoodController::update()));
    Route::delete('/{id}', LaravelHTTPRequestAdapter::handle(NeighborhoodController::destroy()));
});

Route::group(['prefix' => 'streets'], function () {
    Route::get('/', LaravelHTTPRequestAdapter::handle(StreetController::index()));
    Route::get('/{id}', LaravelHTTPRequestAdapter::handle(StreetController::show()));
    Route::post('/', LaravelHTTPRequestAdapter::handle(StreetController::store()));
    Route::put('/{id}', LaravelHTTPRequestAdapter::handle(StreetController::update()));
    Route::delete('/{id}', LaravelHTTPRequestAdapter::handle(StreetController::destroy()));
});

Route::group(['prefix' => 'establishments'], function () {
    Route::get('/', LaravelHTTPRequestAdapter::handle(EstablishmentController::index()));
    Route::get('/confirm', LaravelHTTPRequestAdapter::handle(EstablishmentController::confirmEmail()));
    Route::get('/{uuid}', LaravelHTTPRequestAdapter::handle(EstablishmentController::show()));
    Route::post('/', LaravelHTTPRequestAdapter::handle(EstablishmentController::store()));
    Route::post('/login', LaravelHTTPRequestAdapter::handle(EstablishmentController::login()));
    Route::put('/logout', LaravelHTTPRequestAdapter::handle(EstablishmentController::logout()))->middleware(['authenticate.establishment']);
    Route::put('/{uuid}', LaravelHTTPRequestAdapter::handle(EstablishmentController::update()));
    Route::delete('/{uuid}', LaravelHTTPRequestAdapter::handle(EstablishmentController::destroy()));
});

Route::group(['prefix' => 'schedules'], function () {
    Route::get('/', LaravelHTTPRequestAdapter::handle(ScheduleController::index()));
    Route::get('/{id}', LaravelHTTPRequestAdapter::handle(ScheduleController::show()));
    Route::post('/', LaravelHTTPRequestAdapter::handle(ScheduleController::store()));
    Route::put('/{id}', LaravelHTTPRequestAdapter::handle(ScheduleController::update()));
    Route::delete('/{id}', LaravelHTTPRequestAdapter::handle(ScheduleController::destroy()));
});

Route::group(['prefix' => 'services', 'middleware' => 'authenticate.establishment'], function () {
    Route::get('/', LaravelHTTPRequestAdapter::handle(ServiceController::index()));
    // Route::get('/{id}', LaravelHTTPRequestAdapter::handle(ServiceController::show()));
    Route::post('/', LaravelHTTPRequestAdapter::handle(ServiceController::store()));
    Route::put('/change/{id}', LaravelHTTPRequestAdapter::handle(ServiceController::change()));
    // Route::put('/{id}', LaravelHTTPRequestAdapter::handle(ServiceController::update()));
    // Route::delete('/{id}', LaravelHTTPRequestAdapter::handle(ServiceController::destroy()));
});


Route::group(['prefix' => 'users'], function () {
    Route::get('/confirm', LaravelHTTPRequestAdapter::handle(UserController::confirmEmail()));
    Route::get('/{uuid}', LaravelHTTPRequestAdapter::handle(UserController::show()))->middleware(['authenticate.user', 'checkUuid.user']);
    Route::post('/', LaravelHTTPRequestAdapter::handle(UserController::store()));
    Route::post('/login', LaravelHTTPRequestAdapter::handle(UserController::login()));
    Route::put('/logout', LaravelHTTPRequestAdapter::handle(UserController::logout()))->middleware(['authenticate.user']);
    Route::put('/{id}', LaravelHTTPRequestAdapter::handle(UserController::update()))->middleware(['authenticate.user', 'checkUuid.user']);
    Route::delete('/{id}', LaravelHTTPRequestAdapter::handle(UserController::destroy()))->middleware(['authenticate.user', 'checkUuid.user']);
});
