<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'stateId',
        'created_at',
        'updated_at',
    ];

    public static function getQueryValidator(array $data)
    {
        return Validator::make($data, [
            'id' => ['nullable', 'integer'],
            'name_search' => ['nullable', 'string'],
            'stateId' => ['nullable', 'integer'],
            'created_at_greater_than' => ['nullable', 'date'],
            'created_at_lesser_than' => ['nullable', 'date'],
            'updated_at_greater_than' => ['nullable', 'date'],
            'updated_at_lesser_than' => ['nullable', 'date'],
        ]);
    }

    public static function getStoreValidator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string'],
            'stateId' => ['required', 'integer', 'exists:states,id'],
        ]);
    }

    public static function getStoreRequestValidator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string'],
            'stateId' => ['required', 'integer'],
        ]);
    }

    public static function getUpdateValidator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required_without_all:stateId', 'string'],
            'stateId' => ['required_without_all:name', 'integer', 'exists:states,id'],
        ]);
    }

    public static function getUpdateRequestValidator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required_without_all:stateId', 'string'],
            'stateId' => ['required_without_all:name', 'integer'],
        ]);
    }
}
