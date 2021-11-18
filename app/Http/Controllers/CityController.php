<?php

namespace App\Http\Controllers;

use App\Http\Handlers\DefaultResponseHandler;
use App\Http\Helpers\CityHelper;
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
    public static function index()
    {
        return function (Request $req) {
            $data = CityHelper::getIndexRequestData($req);
            $cities = CityHelper::getCities($data);

            return DefaultResponseHandler::customResponse($cities);
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
            $data = CityHelper::getStoreRequestData($req);
            $city = CityHelper::handleStoreRequest($data);

            return DefaultResponseHandler::customResponse($city, 201);
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

            $city = CityHelper::getCity($queryData);

            return DefaultResponseHandler::customResponse($city);
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

            $data = CityHelper::getUpdateRequestData($req);
            $city = CityHelper::handleUpdateRequest($queryData, $data);

            return DefaultResponseHandler::customResponse($city);
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

            CityHelper::handleDeleteRequest($queryData);

            return DefaultResponseHandler::defaultResponse();
        };
    }
}
