<?php

namespace App\Http\Controllers;

use App\Http\Helpers\{
	GenericHelper,
	ScheduleHelper
};
use App\Models\Schedule;
use App\Utils\TranslatedAttributeName;
use Closure;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
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

	public static function destroy(): Closure
	{
		return function (Request $req): array {
			GenericHelper::validate(Schedule::getQueryValidator([
				'id' => $req->id
			]));

			return ["schedule_delete_" . $req->id, function () use ($req): array {
				$queryData = [
					'id' => $req->id,
					'establishmentUuid' => $req->uuid,
				];

				ScheduleHelper::handleDeleteRequest($queryData);

				return [__('messages.deleted', [
					'entity' => TranslatedAttributeName::get(
						'messages.entities.schedule'
					),
				]), 200, 300];
			}];
		};
	}
}
