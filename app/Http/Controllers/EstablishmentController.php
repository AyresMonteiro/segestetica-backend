<?php

namespace App\Http\Controllers;

use App\Exceptions\GenericAppException;
use App\Http\Handlers\DefaultResponseHandler;
use App\Http\Helpers\EstablishmentHelper;
use App\Http\Helpers\GenericHelper;
use App\Models\Establishment;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

class EstablishmentController extends Controller
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
            $data = EstablishmentHelper::getIndexRequestData($req);
            $establishments = EstablishmentHelper::getEstablishments($data);

            return DefaultResponseHandler::customResponse($establishments);
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
            $data = EstablishmentHelper::getStoreRequestData($req);
            $establishment = EstablishmentHelper::handleStoreRequest($data);

            EstablishmentHelper::sendConfirmationMail($establishment);

            return DefaultResponseHandler::customResponse(__("messages.confirm_email", [
                'email_address' => $establishment->email
            ]));
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
            $queryData = ['uuid' => $req->uuid];

            $establishment = EstablishmentHelper::getEstablishment($queryData);

            return DefaultResponseHandler::customResponse($establishment);
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
            $queryData = ['uuid' => $req->uuid];

            $data = EstablishmentHelper::getUpdateRequestData($req);
            $establishment = EstablishmentHelper::handleUpdateRequest($queryData, $data);

            return DefaultResponseHandler::customResponse($establishment);
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
            $queryData = ['uuid' => $req->uuid];

            EstablishmentHelper::handleDeleteRequest($queryData);

            return DefaultResponseHandler::defaultResponse();
        };
    }

    /**
     * Returns a closure that:
     * Execute login and return token.
     * 
     * @return Closure
     */
    public static function login()
    {
        return function (Request $req) {
            $data = EstablishmentHelper::getLoginRequestData($req);

            $token = EstablishmentHelper::handleLoginRequest($data);

            return DefaultResponseHandler::customResponse(['token' => $token]);
        };
    }

    /**
     * Returns a closure that:
     * Confirm email of establishment.
     * 
     * @return Closure
     */
    public static function confirmEmail()
    {
        return function (Request $req) {
            $data = EstablishmentHelper::getMailConfirmationRequestData($req);

            EstablishmentHelper::handleMailConfirmation($data);

            return DefaultResponseHandler::customResponse(__('messages.email_confirmed'));
        };
    }
}
