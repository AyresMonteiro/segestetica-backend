<?php

namespace App\Http\Adapters;

use App\Exceptions\GenericAppException;
use App\Http\Handlers\{
	DefaultResponseHandler,
	ErrorResponseHandler
};
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LaravelHTTPRequestAdapter
{
	public static function handle(Closure $use_case)
	{
		return function (Request $req) use ($use_case) {
			try {
				[$cache_key, $data_proccessing] = $use_case($req);

				if (isset($cache_key) && Cache::has($cache_key)) {
					$data = Cache::get($cache_key);

					if (!isset($data)) {
						return DefaultResponseHandler::defaultResponse();
					}

					return DefaultResponseHandler::customResponse($data, 200);
				}

				[$data, $statusCode, $ttl] = $data_proccessing();

				if (isset($cache_key)) {
					Cache::put($cache_key, $data, $ttl);
				}

				if (!isset($data)) {
					return DefaultResponseHandler::defaultResponse();
				}

				return DefaultResponseHandler::customResponse($data, $statusCode);
			} catch (GenericAppException $e) {
				$e->performActions();

				return ErrorResponseHandler::customError($e->getCustomErrors(), $e->getStatusCode());
			} catch (Exception $e) {
				return ErrorResponseHandler::defaultError();
			}
		};
	}
}
