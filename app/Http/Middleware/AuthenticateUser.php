<?php

namespace App\Http\Middleware;

use App\Exceptions\GenericAppException;
use App\Http\Handlers\AuthHandler;
use App\Http\Handlers\ErrorResponseHandler;
use App\Http\Helpers\GenericHelper;
use App\Models\{
    Establishment,
    User,
};
use Closure;
use Exception;
use Illuminate\Http\Request;

class AuthenticateUser
{
    public function handleEstablishmentUser()
    {
        return function (AuthHandler $authHandler, array $tokenData) {
            $authHandler->checkPermission($tokenData, Establishment::GENERAL_ABILITY);
        };
    }

    public function handleClientUser()
    {
        return function (AuthHandler $authHandler, array $tokenData) {
            $authHandler->checkPermission($tokenData, User::GENERAL_ABILITY);
        };
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $authHandler = GenericHelper::getDefaultAuthHandler();

            $bearerData = $authHandler->getBearerData($request);

            $authHandler->eraseCache();

            $tokenData = ['token' => $bearerData->token];

            $request->authData = $authHandler->getSecurityData($tokenData);

            $handleFunctions = [
                Establishment::class => $this->handleEstablishmentUser(),
                User::class => $this->handleClientUser(),
            ];

            $handleFunctions[$request->authData['tokenable_type']]($authHandler, $tokenData);

            $request->previousMiddleware = md5(self::class);

            return $next($request);
        } catch (GenericAppException $e) {
            return ErrorResponseHandler::customError($e->getCustomErrors(), $e->getStatusCode());
        } catch (Exception $e) {
            return ErrorResponseHandler::defaultError();
        }
    }
}
