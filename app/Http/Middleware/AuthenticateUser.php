<?php

namespace App\Http\Middleware;

use App\Exceptions\GenericAppException;
use App\Http\Handlers\ErrorResponseHandler;
use App\Http\Helpers\GenericHelper;
use Closure;
use Exception;
use Illuminate\Http\Request;

class AuthenticateUser
{
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

            $authHandler->checkPermission($tokenData, 'user:general');

            $request->previousMiddleware = md5(self::class);
            $request->authData = $authHandler->getSecurityData($tokenData);

            return $next($request);
        } catch (GenericAppException $e) {
            return ErrorResponseHandler::customError($e->getCustomErrors(), $e->getStatusCode());
        } catch (Exception $e) {
            return ErrorResponseHandler::defaultError();
        }
    }
}
