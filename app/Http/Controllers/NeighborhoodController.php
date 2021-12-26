<?php

namespace App\Http\Controllers;

use App\Http\Helpers\GenericHelper;
use App\Http\Helpers\NeighborhoodHelper;
use App\Models\Neighborhood;
use Closure;
use Illuminate\Http\Request;

class NeighborhoodController extends Controller
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
            $index_params = NeighborhoodHelper::getIndexRequestData($req);

            GenericHelper::validate(Neighborhood::getQueryValidator($index_params));

            $cache_key = "neighborhood_index_" . md5(json_encode($index_params));

            return [$cache_key, function () use ($req): array {
                $data = NeighborhoodHelper::getIndexRequestData($req);
                $neighborhoods = NeighborhoodHelper::getNeighborhoods($data);

                return [$neighborhoods, 200, 60];
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
                $data = NeighborhoodHelper::getStoreRequestData($req);
                $neighborhood = NeighborhoodHelper::handleStoreRequest($data);

                return [$neighborhood, 201, 0];
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
            GenericHelper::validate(Neighborhood::getQueryValidator([
                'id' => $req->id,
            ]));

            return ["neighborhood_" . $req->id, function () use ($req): array {
                $queryData = ['id' => $req->id];

                $neighborhood = NeighborhoodHelper::getNeighborhood($queryData);

                return [$neighborhood, 200, 60];
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

                $data = NeighborhoodHelper::getUpdateRequestData($req);
                $neighborhood = NeighborhoodHelper::handleUpdateRequest($queryData, $data);

                return [$neighborhood, 200, 0];
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
            GenericHelper::validate(Neighborhood::getQueryValidator([
                'id' => $req->id,
            ]));

            return ["neighborhood_delete_" . $req->id, function () use ($req): array {
                $queryData = ['id' => $req->id];

                NeighborhoodHelper::handleDeleteRequest($queryData);

                return [__('messages.deleted', ['entity' => __('messages.entities.neighborhood')]), 200, 300];
            }];
        };
    }
}
