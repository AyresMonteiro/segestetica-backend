<?php

namespace App\Http\Controllers;

use App\Http\Handlers\DefaultResponseHandler;
use App\Http\Helpers\StateHelper;
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
    public static function index()
    {
        return function (Request $req) {
            $data = StateHelper::getIndexRequestData($req);
            $states = StateHelper::getStates($data);

            return DefaultResponseHandler::customResponse($states);
        };
    }

    /**
     * Returns a closure that:
     * Stores a newly created resource in storage.
     * 
     * @return Closure
     */
    public static function store()
    {
        return function (Request $req) {
            $data = StateHelper::getStoreRequestData($req);
            $state = StateHelper::handleStoreRequest($data);

            return DefaultResponseHandler::customResponse($state);
        };
    }

    /**
     * Returns a closure that:
     * Displays the specified resource.
     *
     * @return Closure
     */
    public static function show()
    {
        return function (Request $req) {
            $queryData = ['id' => $req->id];

            $state = StateHelper::getState($queryData);

            return DefaultResponseHandler::customResponse($state);
        };
    }

    /**
     * Returns a closure that:
     * Updates the specified resource in storage.
     *
     * @return Closure
     */
    public static function update()
    {
        return function (Request $req) {
            $queryData = ['id' => $req->id];

            $data = StateHelper::getUpdateRequestData($req);
            $state = StateHelper::handleUpdateRequest($queryData, $data);

            return DefaultResponseHandler::customResponse($state);
        };
    }

    /**
     * Returns a closure that:
     * Removes the specified resource from storage.
     *
     * @return Closure
     */
    public static function destroy()
    {
        return function (Request $req) {
            $queryData = ['id' => $req->id];

            StateHelper::handleDeleteRequest($queryData);

            return DefaultResponseHandler::defaultResponse();
        };
    }
}
