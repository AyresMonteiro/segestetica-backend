<?php

namespace App\Http\Controllers;

use App\Http\Helpers\{
	GenericHelper,
	ServiceHelper
};
use App\Models\Service;
use Closure;
use Illuminate\Http\Request;

class ServiceController extends Controller
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
			return ["cache_key", function () use ($req): array {
				$data = ServiceHelper::getIndexRequestData($req);
				$services = ServiceHelper::getServices($data);
				return [$services, 200, 60];
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
				$data = ServiceHelper::getStoreRequestData($req);
				$service = ServiceHelper::handleStoreRequest($data);
				return [$service, 201, 0];
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
			GenericHelper::validate(Service::getQueryValidator([
				'id' => $req->id
			]));

			return ["service_" . $req->id, function () use ($req): array {
				$queryData = ['id' => $req->id];
				$service = ServiceHelper::getService($queryData);
				return [$service, 200, 60];
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
				$queryData = ['id' => $req->id];
				$data = ServiceHelper::getUpdateRequestData($req);
				$service = ServiceHelper::handleUpdateRequest($queryData, $data);
				return [$service, 200, 0];
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
			GenericHelper::validate(Service::getQueryValidator([
				'id' => $req->id
			]));

			return ["service_delete_" . $req->id, function () use ($req): array {
				$queryData = ['id' => $req->id];
				ServiceHelper::handleDeleteRequest($queryData);
				return [__('messages.deleted', ['entity' => __('messages.entities.service')]), 200, 300];
			}];
		};
	}
}
