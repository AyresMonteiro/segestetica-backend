<?php

namespace App\Http\Middleware;

use App\Exceptions\GenericAppException;
use App\Http\Handlers\ErrorResponseHandler;
use App\Models\User;
use Closure;
use Exception;
use Illuminate\Http\Request;

class CheckIfUserIdCanProceed
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
            if ($request->previousMiddleware !== md5(AuthenticateUser::class) || !isset($request->authData)) {
                throw new Exception();
            }

            if ($request->authData['tokenable_type'] !== User::class || $request->authData['tokenable_id'] !== $request->uuid) {
                throw new GenericAppException([__('messages.auth.not_authorized')], 403);
            }

            return $next($request);
        } catch (GenericAppException $e) {
            return ErrorResponseHandler::customError($e->getCustomErrors(), $e->getStatusCode());
        } catch (Exception $e) {
            return ErrorResponseHandler::defaultError();
        }
    }
}
