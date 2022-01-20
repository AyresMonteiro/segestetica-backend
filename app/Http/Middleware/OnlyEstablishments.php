<?php

namespace App\Http\Middleware;

use App\Exceptions\GenericAppException;
use App\Http\Handlers\ErrorResponseHandler;
use App\Models\Establishment;
use Closure;
use Exception;
use Illuminate\Http\Request;

class OnlyEstablishments
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            if ($request->previousMiddleware !== md5(AuthenticateUser::class) || !isset($request->authData)) {
                throw new Exception();
            }

            if ($request->authData['tokenable_type'] !== Establishment::class) {
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
