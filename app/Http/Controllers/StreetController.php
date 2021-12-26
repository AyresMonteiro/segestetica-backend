<?php

namespace App\Http\Controllers;

use App\Http\Helpers\GenericHelper;
use App\Http\Helpers\StreetHelper;
use App\Models\Street;
use Closure;
use Illuminate\Http\Request;

class StreetController extends Controller
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
            $index_params = StreetHelper::getIndexRequestData($req);

            GenericHelper::validate(Street::getQueryValidator($index_params));

            $cache_key = "street_index_" . md5(json_encode($index_params));

            return [$cache_key, function () use ($req): array {
                $data = StreetHelper::getIndexRequestData($req);
                $streets = StreetHelper::getStreets($data);

                return [$streets, 200, 60];
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
                $data = StreetHelper::getStoreRequestData($req);
                $street = StreetHelper::handleStoreRequest($data);

                return [$street, 201, 0];
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
            GenericHelper::validate(Street::getQueryValidator([
                'id' => $req->id
            ]));

            return ["street_" . $req->id, function () use ($req): array {
                $queryData = ['id' => $req->id];

                $street = StreetHelper::getStreet($queryData);

                return [$street, 200, 60];
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

                $data = StreetHelper::getUpdateRequestData($req);
                $street = StreetHelper::handleUpdateRequest($queryData, $data);

                return [$street, 200, 0];
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
            GenericHelper::validate(Street::getQueryValidator([
                'id' => $req->id
            ]));

            return ["street_delete_" . $req->id, function () use ($req): array {
                $queryData = ['id' => $req->id];

                StreetHelper::handleDeleteRequest($queryData);

                return [__('messages.deleted', ['entity' => __('messages.entities.street')]), 200, 300];
            }];
        };
    }
}
