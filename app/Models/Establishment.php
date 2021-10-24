<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\HasApiTokens;

class Establishment extends Model
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'uuid',
        'name',
        'email',
        'photoUrl',
        'streetId',
        'addressNumber',
        'deleted',
        'passwordHash',
        'created_at',
        'updated_at',
    ];

    protected $hidden = [
        'passwordHash',
        'deleted',
    ];

    protected $primaryKey = 'uuid';
    public $incrementing = false;

    public const pathRegex = "/^images[\/].*$/";

    public static function getQueryValidator(array $data)
    {
        return Validator::make($data, [
            'uuid' => ['nullable', 'uuid'],
            'name_search' => ['nullable', 'string'],
            'streetId' => ['nullable', 'integer'],
            'created_at_greater_than' => ['nullable', 'date'],
            'created_at_lesser_than' => ['nullable', 'date'],
            'updated_at_greater_than' => ['nullable', 'date'],
            'updated_at_lesser_than' => ['nullable', 'date'],
        ]);
    }

    public static function getStoreValidator(array $data)
    {
        return Validator::make($data, [
            'uuid' => ['required', 'uuid', 'unique:establishments'],
            'name' => ['required', 'string'],
            'email' => ['required', 'string', 'email', 'unique:establishments'],
            'photoUrl' => ['nullable', 'string', 'regex:' . self::pathRegex, 'unique:establishments'],
            'streetId' => ['required', 'integer', 'exists:streets,id'],
            'addressNumber' => ['required', 'string', 'alpha_num'],
            'passwordHash' => ['required', 'string'],
        ]);
    }

    public static function getStoreRequestValidator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string'],
            'email' => ['required', 'string', 'email'],
            'photo' => ['nullable', 'image', 'dimensions:max_width=1080,max_height=1080'],
            'streetId' => ['required', 'integer'],
            'addressNumber' => ['required', 'string', 'alpha_num'],
            'password' => ['required', 'string'],
        ]);
    }

    public static function getUpdateValidator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required_without_all:email,photoUrl,streetId,addressNumber,passwordHash', 'string'],
            'email' => ['required_without_all:name,photoUrl,streetId,addressNumber,passwordHash', 'string', 'email'],
            'photoUrl' => ['required_without_all:name,email,streetId,addressNumber,passwordHash', 'string', 'regex:' . self::pathRegex],
            'streetId' => ['required_without_all:name,email,photoUrl,addressNumber,passwordHash', 'integer'],
            'addressNumber' => ['required_without_all:name,email,photoUrl,streetId,passwordHash', 'string', 'alpha_num'],
            'passwordHash' => ['required_without_all:name,email,photoUrl,streetId,addressNumber', 'string'],
        ]);
    }

    public static function getUpdateRequestValidator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required_without_all:email,photo,streetId,addressNumber,password', 'string'],
            'email' => ['required_without_all:name,photo,streetId,addressNumber,password', 'string', 'email'],
            'photo' => ['required_without_all:name,email,streetId,addressNumber,password', 'image', 'dimensions:max_width=1080,max_height=1080'],
            'streetId' => ['required_without_all:name,email,photo,addressNumber,password', 'integer'],
            'addressNumber' => ['required_without_all:name,email,photo,streetId,password', 'string', 'alpha_num'],
            'password' => ['required_without_all:name,email,photo,streetId,addressNumber', 'string'],
        ]);
    }

    public static function getLoginRequestValidator(array $data)
    {
        return Validator::make($data, [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);
    }
}
