<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Street extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'postCode',
        'neighborhoodId',
        'created_at',
        'updated_at',
    ];

    public static function getQueryValidator(array $data)
    {
        return Validator::make($data, [
            'id' => ['nullable', 'integer'],
            'name_search' => ['nullable', 'string'],
            'postCode_search' => ['nullable', 'integer'],
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
            'postCode' => ['required', 'integer'],
            'neighborhoodId' => ['required', 'integer', 'exists:neighborhoods,id'],
        ]);
    }

    public static function getStoreRequestValidator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string'],
            'postCode' => ['required', 'integer'],
            'neighborhoodId' => ['required', 'integer'],
        ]);
    }

    public static function getUpdateValidator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required_without_all:postCode,neighborhoodId', 'string'],
            'postCode' => ['required_without_all:name,neighborhoodId', 'integer'],
            'neighborhoodId' => ['required_without_all:name,postCode', 'integer', 'exists:neighborhoods,id'],
        ]);
    }

    public static function getUpdateRequestValidator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required_without_all:postCode,neighborhoodId', 'string'],
            'postCode' => ['required_without_all:name,neighborhoodId', 'integer'],
            'neighborhoodId' => ['required_without_all:name,postCode', 'integer'],
        ]);
    }
}
