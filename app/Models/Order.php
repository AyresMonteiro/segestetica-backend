<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Order extends Model
{
    use HasFactory;

    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'uuid',
        'scheduleId',
        'establishmentUuid',
        'clientUuid',
        'status',
        'day',
        'created_at',
        'updated_at',
    ];

    public static function getQueryValidator(array $data)
    {
        return Validator::make($data, [
            'uuid' => ['nullable', 'uuid'],
            'establishmentUuid' => ['nullable', 'uuid'],
            'created_at_greater_than' => ['nullable', 'date'],
            'created_at_lesser_than' => ['nullable', 'date'],
            'updated_at_greater_than' => ['nullable', 'date'],
            'updated_at_lesser_than' => ['nullable', 'date'],
        ]);
    }

    public static function getStoreValidator(array $data)
    {
        return Validator::make($data, []);
    }

    public static function getStoreRequestValidator(array $data)
    {
        return Validator::make($data, [
            'scheduleId' => ['required', 'integer'],
            'establishmentUuid' => ['required', 'uuid'],
            'status' => ['required', 'regex:/^pending$/'],
            'day' => ['required', 'date'],
            'services' => ['required', 'array', 'min:1'],
            'services.*' => ['required', 'integer'],
        ]);
    }

    public static function getIndexRequestValidator(array $data)
    {
        return Validator::make($data, [
            'establishmentUuid' => ['required', 'uuid'],
        ]);
    }

    public static function getChangeRequestValidator(array $data)
    {
        return Validator::make($data, [
            'uuid' => ['required', 'uuid'],
            'establishmentUuid' => ['required', 'uuid'],
            'status' => ['required', 'regex:/^pending|accepted|refused|completed$/'],
        ]);
    }

    public static function getUpdateValidator(array $data)
    {
        return Validator::make($data, []);
    }

    public static function getUpdateRequestValidator(array $data)
    {
        return Validator::make($data, []);
    }

    public function services()
    {
        return $this->hasManyThrough(
            Service::class,
            OrderService::class,
            'orderUuid',
            'id',
            'uuid',
            'serviceId',
        );
    }

    public function schedule()
    {
        return $this->belongsTo(
            Schedule::class,
            'scheduleId',
            'id',
        );
    }
}
