<?php

namespace App\Http\Controllers;

use App\Http\Handlers\DefaultResponseHandler;
use App\Http\Helpers\NeighborhoodHelper;
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
    public static function index()
    {
        return function (Request $req) {
            $data = NeighborhoodHelper::getIndexRequestData($req);
            $neighborhoods = NeighborhoodHelper::getNeighborhoods($data);

            return DefaultResponseHandler::customResponse($neighborhoods);
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
            $data = NeighborhoodHelper::getStoreRequestData($req);
            $neighborhood = NeighborhoodHelper::handleStoreRequest($data);

            return DefaultResponseHandler::customResponse($neighborhood, 201);
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

            $neighborhood = NeighborhoodHelper::getNeighborhood($queryData);

            return DefaultResponseHandler::customResponse($neighborhood);
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

            $data = NeighborhoodHelper::getUpdateRequestData($req);
            $neighborhood = NeighborhoodHelper::handleUpdateRequest($queryData, $data);

            return DefaultResponseHandler::customResponse($neighborhood);
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

            NeighborhoodHelper::handleDeleteRequest($queryData);

            return DefaultResponseHandler::defaultResponse();
        };
    }
}
