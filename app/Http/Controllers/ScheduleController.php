<?php

namespace App\Http\Controllers;

use App\Http\Helpers\{
	GenericHelper,
	ScheduleHelper
};
use App\Models\Schedule;
use Closure;
use Illuminate\Http\Request;

class ScheduleController extends Controller
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
			$index_params = ScheduleHelper::getIndexRequestData($req);

			GenericHelper::validate(Schedule::getQueryValidator($index_params));

			$cache_key = "schedules_index_" . md5(json_encode($index_params));

			return [$cache_key, function () use ($req): array {
				$data = ScheduleHelper::getIndexRequestData($req);
				$schedules = ScheduleHelper::getSchedules($data);
				return [$schedules, 200, 60];
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
				$data = ScheduleHelper::getStoreRequestData($req);
				$schedule = ScheduleHelper::handleStoreRequest($data);
				return [$schedule, 201, 0];
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
			GenericHelper::validate(Schedule::getQueryValidator([
				'id' => $req->id
			]));

			return ["schedule_" . $req->id, function () use ($req): array {
				$queryData = ['id' => $req->id];
				$schedule = ScheduleHelper::getSchedule($queryData);
				return [$schedule, 200, 60];
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
				$data = ScheduleHelper::getUpdateRequestData($req);
				$schedule = ScheduleHelper::handleUpdateRequest($queryData, $data);
				return [$schedule, 200, 0];
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
			GenericHelper::validate(Schedule::getQueryValidator([
				'id' => $req->id
			]));

			return ["schedule_delete_" . $req->id, function () use ($req): array {
				$queryData = ['id' => $req->id];
				ScheduleHelper::handleDeleteRequest($queryData);
				return [__('messages.deleted', ['entity' => __('messages.entities.schedule')]), 200, 300];
			}];
		};
	}
}
