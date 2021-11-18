<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Schema()
 */
class Neighborhood extends Model
{
    use HasFactory;

    protected $fillable = [
        /**
         *  Neighborhood's Identifier
         *  @var integer
         * 
         *  @OA\Property(
         *      property="id",
         *      format="bigint",
         *      example=3123876,
         *  )
         */
        'id',
        /**
         *  Neighborhood's Name
         *  @var string
         * 
         *  @OA\Property(
         *      property="name",
         *      format="string",
         *      example="Centro",
         *  )
         */
        'name',
        /**
         *  Neighborhood's City Id Foreign
         *  @var integer
         * 
         *  @OA\Property(
         *      property="stateId",
         *      format="bigint",
         *      example=31123697,
         *  )
         */
        'cityId',
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
            'name_search' => ['nullable', 'string'],
            'cityId' => ['nullable', 'integer'],
            'created_at_greater_than' => ['nullable', 'date'],
            'created_at_lesser_than' => ['nullable', 'date'],
            'updated_at_greater_than' => ['nullable', 'date'],
            'updated_at_lesser_than' => ['nullable', 'date'],
        ]);
    }

    public static function getStoreValidator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'unique:neighborhoods'],
            'cityId' => ['required', 'integer', 'exists:cities,id'],
        ]);
    }

    public static function getStoreRequestValidator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string'],
            'cityId' => ['required', 'integer'],
        ]);
    }

    public static function getUpdateValidator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required_without_all:cityId', 'string', 'unique:neighborhoods'],
            'cityId' => ['required_without_all:name', 'integer', 'exists:cities,id'],
        ]);
    }

    public static function getUpdateRequestValidator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string'],
            'cityId' => ['required', 'integer'],
        ]);
    }
}
