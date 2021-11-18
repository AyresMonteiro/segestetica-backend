<?php

namespace App\Http\Controllers;

use App\Http\Handlers\DefaultResponseHandler;
use App\Http\Helpers\StreetHelper;
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
    public static function index()
    {
        return function (Request $req) {
            $data = StreetHelper::getIndexRequestData($req);
            $streets = StreetHelper::getStreets($data);

            return DefaultResponseHandler::customResponse($streets);
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
            $data = StreetHelper::getStoreRequestData($req);
            $street = StreetHelper::handleStoreRequest($data);

            return DefaultResponseHandler::customResponse($street, 201);
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

            $street = StreetHelper::getStreet($queryData);

            return DefaultResponseHandler::customResponse($street);
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

            $data = StreetHelper::getUpdateRequestData($req);
            $street = StreetHelper::handleUpdateRequest($queryData, $data);

            return DefaultResponseHandler::customResponse($street);
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

            StreetHelper::handleDeleteRequest($queryData);

            return DefaultResponseHandler::defaultResponse();
        };
    }
}
