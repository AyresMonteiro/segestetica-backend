<?php

namespace App\Http\Controllers;

use App\Exceptions\GenericAppException;
use App\Http\Helpers\EstablishmentHelper;
use App\Http\Helpers\GenericHelper;
use App\Jobs\SendConfirmationMail;
use App\Models\Establishment;
use App\Models\EstablishmentService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EstablishmentController extends Controller
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
            $index_params = EstablishmentHelper::getIndexRequestData($req);

            GenericHelper::validate(Establishment::getQueryValidator($index_params));

            $cache_key = "establishment_index_" . md5(json_encode($index_params));

            return [$cache_key, function () use ($req): array {
                $data = EstablishmentHelper::getIndexRequestData($req);
                $establishments = EstablishmentHelper::getEstablishments($data);
                $establishments->each->append('address');

                return [$establishments, 200, 60];
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
                $data = EstablishmentHelper::getStoreRequestData($req);
                $establishment = EstablishmentHelper::handleStoreRequest($data);

                SendConfirmationMail::dispatch($establishment->generateConfirmationMailData());

                return [__("messages.confirm_email", [
                    'email_address' => $establishment->email
                ]), 200, 120];
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
            GenericHelper::validate(Establishment::getQueryValidator([
                'uuid' => $req->uuid
            ]));

            return ['establishment_' . $req->uuid, function () use ($req): array {
                $queryData = ['uuid' => $req->uuid];

                $establishment = EstablishmentHelper::getEstablishment($queryData, true, false);

                $establishment->append('address');

                $establishment->services = $establishment->services()->where([
                    'establishment_services.active' => true,
                ])->get();

                $establishment->services->each->setHidden(['laravel_through_key']);

                return [$establishment, 200, 60];
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
                $queryData = ['uuid' => $req->uuid];

                $data = EstablishmentHelper::getUpdateRequestData($req);
                $establishment = EstablishmentHelper::handleUpdateRequest($queryData, $data);

                return [$establishment, 200, 0];
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
            if (!preg_match(GenericHelper::UUIDRegex, $req->uuid)) {
                throw new GenericAppException([__('validation.uuid', ['attribute' => 'uuid'])], 400);
            }

            return ["establishment_delete_" . $req->uuid, function () use ($req): array {
                $queryData = ['uuid' => $req->uuid];

                EstablishmentHelper::handleDeleteRequest($queryData);

                return [__('messages.deleted', ['entity' => __('messages.entities.establishment')]), 200, 300];
            }];
        };
    }

    /**
     * Returns a closure that:
     * Execute login and return token.
     * 
     * @return Closure
     */
    public static function login(): Closure
    {
        return function (Request $req): array {
            return [null, function () use ($req): array {
                $data = EstablishmentHelper::getLoginRequestData($req);

                [$token, $uuid] = EstablishmentHelper::handleLoginRequest($data);

                return [[
                    'uuid' => $uuid,
                    'token' => $token,
                ], 200, 0];
            }];
        };
    }

    /**
     * Returns a closure that:
     * Execute logout.
     * 
     * @return Closure
     */
    public static function logout(): Closure
    {
        return function (Request $req): array {
            return [null, function () use ($req): array {
                EstablishmentHelper::handleLogoutRequest($req->authData['tokenable_id']);

                return [null, 200, 0];
            }];
        };
    }

    /**
     * Returns a closure that:
     * Confirm email of establishment.
     * 
     * @return Closure
     */
    public static function confirmEmail(): Closure
    {
        return function (Request $req): array {
            GenericHelper::validate(Establishment::getMailConfirmationValidator([
                'token' => $req->token,
            ]));

            return ["establishment_confirm_" . $req->token, function () use ($req): array {
                $data = EstablishmentHelper::getMailConfirmationRequestData($req);

                EstablishmentHelper::handleMailConfirmation($data);

                return [__('messages.email_confirmed'), 200, 3600];
            }];
        };
    }
}
