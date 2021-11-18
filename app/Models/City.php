<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Schema()
 */
class City extends Model
{
    use HasFactory;

    const cityNamePattern = "/^(\p{L}| )+$/u";

    protected $fillable = [
        /**
         *  City's Identifier
         *  @var integer
         * 
         *  @OA\Property(
         *      property="id",
         *      format="bigint",
         *      example=812581236,
         *  )
         */
        'id',
        /**
         *  City's Name
         *  @var string
         * 
         *  @OA\Property(
         *      property="name",
         *      format="string",
         *      example="São Paulo",
         *      pattern="/^(\p{L}| )+$/u",
         *  )
         */
        'name',
        /**
         *  City's State Id Foreign
         *  @var integer
         * 
         *  @OA\Property(
         *      property="stateId",
         *      format="tinyint",
         *      example=255,
         *  )
         */
        'stateId',
        /**
         *  Timestamp of creation in database
         *  @var string
         * 
         *  @OA\Property(
         *      property="created_at",
         *      format="date-time",
         *  )
         */
        'created_at',
        /**
         *  Timestamp of last update in database
         *  @var string
         * 
         *  @OA\Property(
         *      property="updated_at",
         *      format="date-time",
         *  )
         */
        'updated_at',
    ];

    public static function getQueryValidator(array $data)
    {
        return Validator::make($data, [
            'id' => ['nullable', 'integer'],
            'name_search' => ['nullable', 'string', 'regex:' . self::cityNamePattern],
            'stateId' => ['nullable', 'integer', 'max:255'],
            'created_at_greater_than' => ['nullable', 'date'],
            'created_at_lesser_than' => ['nullable', 'date'],
            'updated_at_greater_than' => ['nullable', 'date'],
            'updated_at_lesser_than' => ['nullable', 'date'],
        ]);
    }

    public static function getStoreValidator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'regex:' . self::cityNamePattern, 'unique:cities'],
            'stateId' => ['required', 'integer', 'max:255', 'exists:states,id'],
        ]);
    }

    public static function getStoreRequestValidator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'regex:' . self::cityNamePattern],
            'stateId' => ['required', 'integer', 'max:255'],
        ]);
    }

    public static function getUpdateValidator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required_without_all:stateId', 'string', 'regex:' . self::cityNamePattern, 'unique:cities'],
            'stateId' => ['required_without_all:name', 'integer', 'max:255', 'exists:states,id'],
        ]);
    }

    public static function getUpdateRequestValidator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required_without_all:stateId', 'string', 'regex:' . self::cityNamePattern],
            'stateId' => ['required_without_all:name', 'integer', 'max:255'],
        ]);
    }
}
