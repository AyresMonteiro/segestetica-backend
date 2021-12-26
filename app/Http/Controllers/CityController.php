<?php

namespace App\Http\Controllers;

use App\Http\Helpers\CityHelper;
use App\Http\Helpers\GenericHelper;
use App\Models\City;
use Closure;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * Returns a closure that:
     * Displays a listing of the resource.
     *
     * @return Closure
     */
    public static function index(): Closure
    {
        return function (Request $req): array {
            $index_params = CityHelper::getIndexRequestData($req);

            GenericHelper::validate(City::getQueryValidator($index_params));

            $cache_key = "city_index_" . md5(json_encode($index_params));

            return [$cache_key, function () use ($req): array {
                $data = CityHelper::getIndexRequestData($req);
                $cities = CityHelper::getCities($data);

                return [$cities, 200, 600];
            }];
        };
    }

    /**
     * Returns a closure that:
     * Stores a newly created resource in storage.
     * 
     * @return Closure
     */
    public static function store(): Closure
    {
        return function (Request $req): array {
            return [null, function () use ($req): array {
                $data = CityHelper::getStoreRequestData($req);
                $city = CityHelper::handleStoreRequest($data);

                return [$city, 201, 0];
            }];
        };
    }

    /**
     * Returns a closure that:
     * Displays the specified resource.
     *
     * @return Closure
     */
    public static function show(): Closure
    {
        return function (Request $req): array {
            GenericHelper::validate(City::getQueryValidator([
                'id' => $req->id
            ]));

            return ["city_" . $req->id, function () use ($req): array {
                $queryData = ['id' => $req->id];

                $city = CityHelper::getCity($queryData);

                return [$city, 200, 60];
            }];
        };
    }

    /**
     * Returns a closure that:
     * Updates the specified resource in storage.
     *
     * @return Closure
     */
    public static function update(): Closure
    {
        return function (Request $req): array {
            return [null, function () use ($req): array {
                $queryData = ['id' => $req->id];

                $data = CityHelper::getUpdateRequestData($req);
                $city = CityHelper::handleUpdateRequest($queryData, $data);

                return [$city, 200, 0];
            }];
        };
    }

    /**
     * Returns a closure that:
     * Removes the specified resource from storage.
     *
     * @return Closure
     */
    public static function destroy(): Closure
    {
        return function (Request $req): array {
            GenericHelper::validate(City::getQueryValidator([
                'id' => $req->id
            ]));

            return ["city_delete_" . $req->id, function () use ($req): array {
                $queryData = ['id' => $req->id];

                CityHelper::handleDeleteRequest($queryData);

                return [__('messages.deleted', ['entity' => __('messages.entities.city')]), 200, 300];
            }];
        };
    }
}
