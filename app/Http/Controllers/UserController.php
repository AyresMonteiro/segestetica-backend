<?php

namespace App\Http\Controllers;

use App\Http\Helpers\{
    GenericHelper,
    UserHelper
};
use App\Jobs\SendConfirmationMail;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class UserController extends Controller
{
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
                $data = UserHelper::getStoreRequestData($req);
                $user = UserHelper::handleStoreRequest($data);

                SendConfirmationMail::dispatch($user->generateConfirmationMailData());

                return [__("messages.confirm_email", [
                    'email_address' => $user->email
                ]), 201, 0];
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
            return [null, function () use ($req): array {
                $queryData = ['uuid' => $req->uuid];
                $user = UserHelper::getUser($queryData);
                return [$user, 200, 60];
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
                $data = UserHelper::getUpdateRequestData($req);
                $user = UserHelper::handleUpdateRequest($queryData, $data);
                return [$user, 200, 0];
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
            return [null, function () use ($req): array {
                $queryData = ['id' => $req->uuid];
                UserHelper::handleDeleteRequest($queryData);
                return [__('messages.deleted', ['entity' => __('messages.entities.user')]), 200, 300];
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
                $data = UserHelper::getLoginRequestData($req);

                $token = UserHelper::handleLoginRequest($data);

                return [['token' => $token], 200, 0];
            }];
        };
    }

    /**
     * Returns a closure that:
     * Confirm email of user.
     * 
     * @return Closure
     */
    public static function confirmEmail(): Closure
    {
        return function (Request $req): array {
            GenericHelper::validate(User::getMailConfirmationValidator([
                'token' => $req->token,
            ]));

            return ["user_confirm_" . $req->token, function () use ($req): array {
                $data = UserHelper::getMailConfirmationRequestData($req);

                UserHelper::handleMailConfirmation($data);

                return [__('messages.email_confirmed'), 200, 3600];
            }];
        };
    }
}
