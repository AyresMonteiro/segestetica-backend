<?php

namespace App\Http\Helpers;

use App\Exceptions\GenericAppException;
use App\Http\Helpers\GenericHelper;
use App\Models\Order;
use App\Models\OrderService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderHelper
{
	public static function getStoreRequestData(Request $req)
	{
		$data = array_filter([
			'scheduleId' => $req->scheduleId,
			'establishmentUuid' => $req->establishmentUuid,
			'clientUuid' => $req->authData['tokenable_id'],
			'status' => 'pending',
			'day' => $req->orderDay,
			'services' => $req->orderServices,
		]);

		if (gettype($data['scheduleId']) !== 'integer') {
			$data['scheduleId'] = intval($data['scheduleId']);
		}

		foreach ($data['services'] as &$service) {
			if (gettype($service) === 'string') {
				$service = intval($service);
			}
		}

		return $data;
	}

	public static function getUpdateRequestData(Request $req)
	{
		return array_filter([]);
	}

	public static function getIndexRequestData(Request $req)
	{
		return array_filter([
			'establishmentUuid' => $req->authData['tokenable_id'],
			'created_at_greater_than' => $req->orderCreatedAtGreaterThan,
			'created_at_lesser_than' => $req->orderCreatedAtLesserThan,
			'updated_at_greater_than' => $req->orderUpdatedAtGreaterThan,
			'updated_at_lesser_than' => $req->orderUpdatedAtLesserThan,
		]);
	}

	public static function handleStoreRequest(array $data)
	{
		GenericHelper::validate(Order::getStoreRequestValidator($data));

		$establishment = EstablishmentHelper::getEstablishment([
			'uuid' => $data['establishmentUuid'],
		])->first();

		if (!isset($establishment)) {
			throw new GenericAppException([__('messages.not_found_error')], 404);
		}

		$establishment->schedules = $establishment->schedules()->where([
			'deleted' => false,
		])->get();

		$finded = null;

		foreach ($establishment->schedules as $schedule) {
			if ($schedule->id === $data['scheduleId']) {
				$finded = $schedule;
				break;
			}
		}

		if ($finded === null) {
			throw new GenericAppException([__('messages.not_found_error')], 404);
		}

		$establishment->services = $establishment->services()->where([
			'establishment_services.active' => true,
		])->get();

		$possibleIds = [];
		$validIds = [];

		foreach ($establishment->services as $service) {
			array_push($possibleIds, $service->id);
		}

		foreach ($data['services'] as $serviceId) {
			if (in_array($serviceId, $possibleIds)) {
				array_push($validIds, $serviceId);
			}
		}

		if (sizeof($validIds) !== sizeof($data['services'])) {
			throw new GenericAppException([__('messages.not_found_error')], 404);
		}

		$now = Carbon::now('-3');
		$date = Carbon::parse($data['day'])->toDateString();
		$date = Carbon::parse($date . 'T' . $finded->time, '-3');

		if ($date < $now) {
			throw new GenericAppException([__('messages.past_date_error')], 400);
		}

		$data['day'] = $date->toDateString();

		$orders = Order::where([
			'scheduleId' => $finded->id,
			'day' => $date,
		])->get();

		$maxServices = $finded->maxServices;
		$totalAcceptedServices = 0;

		foreach ($orders as $anOrder) {
			if ($anOrder->status === 'accepted') {
				$totalAcceptedServices++;
			}
		}

		if ($totalAcceptedServices >= $maxServices) {
			throw new GenericAppException([__('messages.max_orders_limit_reached')], 422);
		}

		$data['uuid'] = GenericHelper::generateUUIDString();

		$order = self::saveOrder($data);

		$orderServicesData = [];

		foreach ($validIds as $serviceId) {
			array_push($orderServicesData, ['serviceId' => $serviceId, 'orderUuid' => $order->uuid]);
		}

		OrderService::insert($orderServicesData);

		$order->services = $order->services()->get();
		$order->services->each->setHidden(['laravel_through_key', 'deleted']);

		return $order;
	}

	public static function handleUpdateRequest(array $findData, array $updateData)
	{
		GenericHelper::validate(Order::getUpdateRequestValidator($updateData));

		$order = self::updateOrder($findData, $updateData);

		return $order;
	}

	public static function handleDeleteRequest(array $findData)
	{
		self::deleteOrder($findData);
	}

	public static function getTreatedQuery(array $data)
	{
		GenericHelper::validate(Order::getQueryValidator($data));

		$fixedValues = GenericHelper::getFixedValues($data);
		$searchValues = GenericHelper::getSearchValues($data);
		$compareValues = GenericHelper::getCompareValues($data);

		$builder = Order::where($fixedValues);
		$builder = GenericHelper::handleSearchValues($builder, $searchValues);
		$builder = GenericHelper::handleCompareValues($builder, $compareValues);

		return $builder;
	}

	public static function getOrder(array $data, bool $enableNotFoundError = true)
	{
		$builder = self::getTreatedQuery($data);

		$order = $builder->first();

		if ($enableNotFoundError && !isset($order)) {
			throw new GenericAppException([__('messages.not_found_error')], 404);
		}

		return $order;
	}

	public static function getOrders(array $data, bool $enableNotFoundError = false)
	{
		$builder = self::getTreatedQuery($data);

		$orders = $builder->with(['services'])->get();

		if ($enableNotFoundError && sizeof($orders) === 0) {
			throw new GenericAppException([__('messages.not_found_error')], 404);
		}

		return $orders;
	}

	public static function deleteOrder(array $data)
	{
		$order = self::getOrder($data);

		if (!$order->delete()) {
			throw new GenericAppException([__('messages.delete_error')], 500);
		};
	}

	protected static function saveOrder(array $data)
	{
		GenericHelper::validate(Order::getStoreValidator($data));

		$order = new Order($data);
		$order->save();

		return $order;
	}

	protected static function updateOrder(array $findData, array $updateData)
	{
		$order = self::getOrder($findData);

		GenericHelper::validate(Order::getUpdateValidator($updateData));

		$order->fill($updateData);

		if (sizeof($order->getDirty()) === 0) {
			throw new GenericAppException([__('messages.up_to_date_error')], 400);
		}

		$order->save();

		return $order;
	}
}
