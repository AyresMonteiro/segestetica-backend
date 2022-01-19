<?php

namespace App\Http\Helpers;

use App\Exceptions\GenericAppException;
use App\Http\Helpers\GenericHelper;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceHelper
{
	public static function getStoreRequestData(Request $req)
	{
		return array_filter([
			'id' => $req->serviceId,
			'name' => $req->serviceName,
			'description' => $req->serviceDescription,
			'integerValue' => $req->serviceIntegerValue,
			'fractionalValue' => $req->serviceFractionalValue,
		]);
	}

	public static function getUpdateRequestData(Request $req)
	{
		return array_filter([]);
	}

	public static function getIndexRequestData(Request $req)
	{
		return array_filter([
			'created_at_greater_than' => $req->serviceCreatedAtGreaterThan,
			'created_at_lesser_than' => $req->serviceCreatedAtLesserThan,
			'updated_at_greater_than' => $req->serviceUpdatedAtGreaterThan,
			'updated_at_lesser_than' => $req->serviceUpdatedAtLesserThan,
		]);
	}

	public static function handleStoreRequest(array $data)
	{
		GenericHelper::validate(Service::getStoreRequestValidator($data));

		$service = self::saveService($data);

		return $service;
	}

	public static function handleUpdateRequest(array $findData, array $updateData)
	{
		GenericHelper::validate(Service::getUpdateRequestValidator($updateData));

		$service = self::updateService($findData, $updateData);

		return $service;
	}

	public static function handleDeleteRequest(array $findData)
	{
		self::deleteService($findData);
	}

	public static function getTreatedQuery(array $data)
	{
		GenericHelper::validate(Service::getQueryValidator($data));

		$fixedValues = GenericHelper::getFixedValues($data);
		$searchValues = GenericHelper::getSearchValues($data);
		$compareValues = GenericHelper::getCompareValues($data);

		$builder = Service::where($fixedValues);
		$builder = GenericHelper::handleSearchValues($builder, $searchValues);
		$builder = GenericHelper::handleCompareValues($builder, $compareValues);

		return $builder;
	}

	public static function getService(array $data, bool $enableNotFoundError = true)
	{
		$builder = self::getTreatedQuery($data);

		$service = $builder->first();

		if ($enableNotFoundError && !isset($service)) {
			throw new GenericAppException([__('messages.not_found_error')], 404);
		}

		return $service;
	}

	public static function getServices(array $data, bool $enableNotFoundError = false)
	{
		$builder = self::getTreatedQuery($data);

		$services = $builder->get();

		if ($enableNotFoundError && sizeof($services) === 0) {
			throw new GenericAppException([__('messages.not_found_error')], 404);
		}

		return $services;
	}

	public static function deleteService(array $data)
	{
		$service = self::getService($data);

		if (!$service->delete()) {
			throw new GenericAppException([__('messages.delete_error')], 500);
		};
	}

	protected static function saveService(array $data)
	{
		GenericHelper::validate(Service::getStoreValidator($data));

		$service = new Service($data);
		$service->save();

		return $service;
	}

	protected static function updateService(array $findData, array $updateData)
	{
		$service = self::getService($findData);

		GenericHelper::validate(Service::getUpdateValidator($updateData));

		$service->fill($updateData);

		if (sizeof($service->getDirty()) === 0) {
			throw new GenericAppException([__('messages.up_to_date_error')], 400);
		}

		$service->save();

		return $service;
	}
}
