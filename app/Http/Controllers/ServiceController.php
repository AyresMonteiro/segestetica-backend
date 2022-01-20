<?php

namespace App\Http\Controllers;

use App\Http\Helpers\{
	GenericHelper,
	ServiceHelper
};
use App\Models\{
	EstablishmentService,
	Service
};
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

				if (!isset($data['id'])) {
					$service = ServiceHelper::handleStoreRequest($data);
				} else {
					$service = ServiceHelper::getService(['id' => $data['id']]);
				}

				if (!isset($service)) {
					return [null, 404, 0];
				}

				$establishmentServiceData = [
					'establishmentUuid' => $req->authData['tokenable_id'],
					'serviceId' => $service->id,
				];

				$establishmentService = EstablishmentService::where($establishmentServiceData)->first();

				if (isset($establishmentService)) {
					$body = [
						'errors' => [
							__('messages.entity_already_exists_error'),
						],
					];

					return [$body, 409, 0];
				}

				$establishmentService = new EstablishmentService($establishmentServiceData);

				$establishmentService->active = true;

				$establishmentService->save();

				return [$service, 201, 0];
			}];
		};
	}

	public static function change(): Closure
	{
		return function (Request $req): array {
			return [null, function () use ($req): array {
				$data = ServiceHelper::getChangeRequestData($req);

				$establishmentService = ServiceHelper::handleChangeRequest($data);

				if (!isset($establishmentService)) {
					return [['errors' => [__('messages.not_found_error')]], 404, 0];
				}

				$establishmentService->active = $data['active'];

				$establishmentService->save();

				return [null, 204, 0];
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
