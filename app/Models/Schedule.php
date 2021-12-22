<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'maxServices',
        'time',
        'establishmentUuid',
        'deleted',
        'created_at',
        'updated_at',
    ];

    public static function getQueryValidator(array $data)
    {
        return Validator::make($data, [
            'id' => ['nullable', 'integer'],
            'maxServices_greater_than' => ['nullable', 'integer'],
            'maxServices_lesser_than' => ['nullable', 'integer'],
            'time_greater_than' => ['nullable', 'date_format:H:i'],
            'time_lesser_than' => ['nullable', 'date_format:H:i'],
            'establishmentUuid' => ['nullable', 'uuid'],
            'deleted' => ['nullable', 'boolean'],
            'created_at_greater_than' => ['nullable', 'date'],
            'created_at_lesser_than' => ['nullable', 'date'],
            'updated_at_greater_than' => ['nullable', 'date'],
            'updated_at_lesser_than' => ['nullable', 'date'],
        ]);
    }

    public static function getStoreValidator(array $data)
    {
        return Validator::make($data, [
            'maxServices' => ['required', 'integer'],
            'time' => ['required', 'date_format:H:i'],
            'establishmentUuid' => ['required', 'uuid', 'exists:establishments,uuid'],
        ]);
    }

    public static function getStoreRequestValidator(array $data)
    {
        return Validator::make($data, [
            'maxServices' => ['required', 'integer'],
            'time' => ['required', 'date_format:H:i'],
            'establishmentUuid' => ['required', 'uuid'],
        ]);
    }

    public static function getUpdateValidator(array $data)
    {
        return Validator::make($data, [
            'maxServices' => ['required_without:time,establishmentUuid,deleted', 'required', 'integer'],
            'time' => ['required_without:maxServices,establishmentUuid,deleted', 'required', 'date_format:H:i'],
            'establishmentUuid' => ['required_without:maxServices,time,deleted', 'uuid', 'exists:establishments,uuid'],
            'deleted' => ['required_without:maxServices,time,establishmentUuid', 'nullable', 'boolean'],
        ]);
    }

    public static function getUpdateRequestValidator(array $data)
    {
        return Validator::make($data, [
            'maxServices' => ['required_without:time,establishmentUuid,deleted', 'required', 'integer'],
            'time' => ['required_without:maxServices,establishmentUuid,deleted', 'required', 'date_format:H:i'],
            'establishmentUuid' => ['required_without:maxServices,time,deleted', 'uuid'],
            'deleted' => ['required_without:maxServices,time,establishmentUuid', 'nullable', 'boolean'],
        ]);
    }
}
