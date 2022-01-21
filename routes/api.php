<?php

use App\Http\Adapters\LaravelHTTPRequestAdapter;
use App\Http\Controllers\{
    CityController,
    EstablishmentController,
    NeighborhoodController,
    OrderController,
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
    Route::post('/', LaravelHTTPRequestAdapter::handle(EstablishmentController::store()));
    Route::post('/login', LaravelHTTPRequestAdapter::handle(EstablishmentController::login()));

    Route::group(['middleware' => 'authenticate.user'], function () {
        Route::get('/{uuid}', LaravelHTTPRequestAdapter::handle(EstablishmentController::show()));

        Route::group(['middleware' => 'only.establishments'], function () {
            Route::put('/logout', LaravelHTTPRequestAdapter::handle(EstablishmentController::logout()));
        });

        Route::group(['middleware' => 'checkUuid.establishment'], function () {
            Route::put('/{uuid}', LaravelHTTPRequestAdapter::handle(EstablishmentController::update()));
            Route::delete('/{uuid}', LaravelHTTPRequestAdapter::handle(EstablishmentController::destroy()));
        });
    });
});

Route::group(['prefix' => 'schedules', 'middleware' => ['authenticate.user', 'only.establishments']], function () {
    Route::group(['middleware' => 'checkUuid.establishment'], function () {
        Route::post('/{uuid}', LaravelHTTPRequestAdapter::handle(ScheduleController::store()));
        Route::delete('/{uuid}/{id}', LaravelHTTPRequestAdapter::handle(ScheduleController::destroy()));
    });

    // Route::get('/', LaravelHTTPRequestAdapter::handle(ScheduleController::index()));
    // Route::get('/{id}', LaravelHTTPRequestAdapter::handle(ScheduleController::show()));
    // Route::put('/{id}', LaravelHTTPRequestAdapter::handle(ScheduleController::update()));
});

Route::group(['prefix' => 'services', 'middleware' => 'authenticate.user'], function () {
    Route::group(['middleware' => 'only.establishments'], function () {
        Route::get('/', LaravelHTTPRequestAdapter::handle(ServiceController::index()));
    });

    // Route::get('/{id}', LaravelHTTPRequestAdapter::handle(ServiceController::show()));

    Route::group(['middleware' => 'checkUuid.establishment'], function () {
        Route::post('/{uuid}', LaravelHTTPRequestAdapter::handle(ServiceController::store()));
        Route::put('/{uuid}/change/{id}', LaravelHTTPRequestAdapter::handle(ServiceController::change()));
    });

    // Route::put('/{id}', LaravelHTTPRequestAdapter::handle(ServiceController::update()));
    // Route::delete('/{id}', LaravelHTTPRequestAdapter::handle(ServiceController::destroy()));
});


Route::group(['prefix' => 'users'], function () {
    Route::post('/', LaravelHTTPRequestAdapter::handle(UserController::store()));
    Route::post('/login', LaravelHTTPRequestAdapter::handle(UserController::login()));
    Route::get('/confirm', LaravelHTTPRequestAdapter::handle(UserController::confirmEmail()));

    Route::group(['middleware' => 'authenticate.user'], function () {
        Route::put('/logout', LaravelHTTPRequestAdapter::handle(UserController::logout()));
    });

    Route::group(['middleware' => 'authenticate.user', 'checkUuid.client'], function () {
        Route::get('/{uuid}', LaravelHTTPRequestAdapter::handle(UserController::show()));
        Route::put('/{uuid}',  LaravelHTTPRequestAdapter::handle(UserController::update()));
        Route::delete('/{uuid}', LaravelHTTPRequestAdapter::handle(UserController::destroy()));
    });
});

Route::group(['prefix' => 'orders'], function () {
    Route::group(['middleware' => ['authenticate.user', 'only.clients']], function () {
        Route::post('/{establishmentUuid}/{scheduleId}', LaravelHTTPRequestAdapter::handle(OrderController::store()));
    });

    Route::group(['middleware' => ['authenticate.user', 'only.establishments']], function () {
        Route::get('/', LaravelHTTPRequestAdapter::handle(OrderController::index()));
        Route::put('/{uuid}', LaravelHTTPRequestAdapter::handle(OrderController::change()));
        Route::post('/{scheduleId}', LaravelHTTPRequestAdapter::handle(OrderController::store()));
    });
});
