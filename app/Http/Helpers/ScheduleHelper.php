<?php

namespace App\Http\Helpers;

use App\Exceptions\GenericAppException;
use App\Http\Helpers\GenericHelper;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleHelper
{
	public static function getStoreRequestData(Request $req)
	{
		return array_filter([
			'maxServices' => $req->scheduleMaxServices,
			'time' => $req->scheduleTime,
			'duration' => $req->scheduleDuration,
			'establishmentUuid' => $req->uuid,
		]);
	}

	public static function getUpdateRequestData(Request $req)
	{
		return array_filter([
			'maxServices' => $req->scheduleMaxServices,
			'time' => $req->scheduleTime,
			'duration' => $req->scheduleDuration,
			'establishmentUuid' => $req->uuid,
			'deleted' => $req->scheduleDeleted,
		]);
	}

	public static function getIndexRequestData(Request $req)
	{
		$data = array_filter([
			'maxServices_greater_than' => $req->scheduleMaxServicesGreaterThan,
			'maxServices_lesser_than' => $req->scheduleMaxServicesLesserThan,
			'time_greater_than' => $req->scheduleTimeGreaterThan,
			'time_lesser_than' => $req->scheduleTimeLesserThan,
			'establishmentUuid' => $req->scheduleEstablishmentUuid,
			'created_at_greater_than' => $req->scheduleCreatedAtGreaterThan,
			'created_at_lesser_than' => $req->scheduleCreatedAtLesserThan,
			'updated_at_greater_than' => $req->scheduleUpdatedAtGreaterThan,
			'updated_at_lesser_than' => $req->scheduleUpdatedAtLesserThan,
		]);

		$data['deleted'] = false;

		return $data;
	}

	public static function handleStoreRequest(array $data)
	{
		GenericHelper::validate(Schedule::getStoreRequestValidator($data));

		$schedule = self::saveSchedule($data);

		return $schedule;
	}

	public static function handleUpdateRequest(array $findData, array $updateData)
	{
		GenericHelper::validate(Schedule::getUpdateRequestValidator($updateData));

		$schedule = self::updateSchedule($findData, $updateData);

		return $schedule;
	}

	public static function handleDeleteRequest(array $data)
	{
		GenericHelper::validate(Schedule::getDeleteRequestValidator($data));

		self::deleteSchedule($data);
	}

	public static function getTreatedQuery(array $data)
	{
		GenericHelper::validate(Schedule::getQueryValidator($data));

		$fixedValues = GenericHelper::getFixedValues($data);
		$searchValues = GenericHelper::getSearchValues($data);
		$compareValues = GenericHelper::getCompareValues($data);

		$builder = Schedule::where($fixedValues);
		$builder = GenericHelper::handleSearchValues($builder, $searchValues);
		$builder = GenericHelper::handleCompareValues($builder, $compareValues);

		return $builder;
	}

	public static function getSchedule(array $data, bool $enableNotFoundError = true)
	{
		$builder = self::getTreatedQuery($data);

		$schedule = $builder->first();

		if ($enableNotFoundError && !isset($schedule)) {
			throw new GenericAppException([__('messages.not_found_error')], 404);
		}

		return $schedule;
	}

	public static function getSchedules(array $data, bool $enableNotFoundError = false)
	{
		$builder = self::getTreatedQuery($data);

		$schedules = $builder->get();

		if ($enableNotFoundError && sizeof($schedules) === 0) {
			throw new GenericAppException([__('messages.not_found_error')], 404);
		}

		return $schedules;
	}

	public static function deleteSchedule(array $data)
	{
		$schedule = self::getSchedule($data);

		$schedule->deleted = true;

		if (!$schedule->save()) {
			throw new GenericAppException([__('messages.delete_error')], 500);
		};
	}

	protected static function saveSchedule(array $data)
	{
		GenericHelper::validate(Schedule::getStoreValidator($data));

		$schedule = new Schedule($data);
		$schedule->save();

		return $schedule;
	}

	protected static function updateSchedule(array $findData, array $updateData)
	{
		$schedule = self::getSchedule($findData);

		GenericHelper::validate(Schedule::getUpdateValidator($updateData));

		$schedule->fill($updateData);

		if (sizeof($schedule->getDirty()) === 0) {
			throw new GenericAppException([__('messages.up_to_date_error')], 400);
		}

		$schedule->save();

		return $schedule;
	}
}
