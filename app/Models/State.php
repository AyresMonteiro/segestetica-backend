<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class State extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'abbreviation',
        'created_at',
        'updated_at',
    ];

    public static function getQueryValidator(array $data)
    {
        return Validator::make($data, [
            'id' => ['nullable', 'integer'],
            'name' => ['nullable', 'string'],
            'name_search' => ['nullable', 'string'],
            'abbreviation' => ['nullable', 'string'],
            'abbreviation_search' => ['nullable', 'string'],
            'created_at_greater_than' => ['nullable', 'date'],
            'created_at_lesser_than' => ['nullable', 'date'],
            'updated_at_greater_than' => ['nullable', 'date'],
            'updated_at_lesser_than' => ['nullable', 'date'],
        ]);
    }

    public static function getStoreValidator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'unique:states'],
            'abbreviation' => ['required', 'string', 'unique:states'],
        ]);
    }

    public static function getStoreRequestValidator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string'],
            'abbreviation' => ['required', 'string'],
        ]);
    }

    public static function getUpdateValidator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required_without_all:abbreviation', 'string', 'unique:states'],
            'abbreviation' => ['required_without_all:name', 'string', 'unique:states'],
        ]);
    }

    public static function getUpdateRequestValidator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required_without_all:abbreviation', 'string'],
            'abbreviation' => ['required_without_all:name', 'string'],
        ]);
    }
}
