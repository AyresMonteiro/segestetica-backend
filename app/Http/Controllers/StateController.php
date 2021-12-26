<?php

namespace App\Http\Controllers;

use App\Http\Helpers\GenericHelper;
use App\Http\Helpers\StateHelper;
use App\Models\State;
use Closure;
use Illuminate\Http\Request;

class StateController extends Controller
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
            $index_params = StateHelper::getIndexRequestData($req);

            GenericHelper::validate(State::getQueryValidator($index_params));

            $cache_key = "state_index_" . md5(json_encode($index_params));

            return [$cache_key, function () use ($req) {
                $data = StateHelper::getIndexRequestData($req);
                $states = StateHelper::getStates($data);

                return [$states, 200, 600];
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
            return [null, function () use ($req) {
                $data = StateHelper::getStoreRequestData($req);
                $state = StateHelper::handleStoreRequest($data);

                return [$state, 201, 0];
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
            GenericHelper::validate(State::getQueryValidator([
                'id' => $req->id
            ]));

            return ["state_" . $req->id, function () use ($req) {
                $queryData = ['id' => $req->id];

                $state = StateHelper::getState($queryData);

                return [$state, 200, 60];
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
            return [null, function () use ($req) {
                $queryData = ['id' => $req->id];

                $data = StateHelper::getUpdateRequestData($req);
                $state = StateHelper::handleUpdateRequest($queryData, $data);

                return [$state, 200, 0];
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
            GenericHelper::validate(State::getQueryValidator([
                'id' => $req->id
            ]));

            return ["state_delete_" . $req->id, function () use ($req) {
                $queryData = ['id' => $req->id];

                StateHelper::handleDeleteRequest($queryData);

                return [__('messages.deleted', ['entity' => __('messages.entities.state')]), 200, 300];
            }];
        };
    }
}
