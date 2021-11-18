<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Schema()
 */
class State extends Model
{
    use HasFactory;

    protected $fillable = [
        /**
         *  State's Identifier
         *  @var integer
         * 
         *  @OA\Property(
         *      property="id",
         *      format="bigint",
         *      example=3876181,
         *  )
         */
        'id',
        /**
         *  State's Name
         *  @var string
         * 
         *  @OA\Property(
         *      property="name",
         *      format="string",
         *      example="São Paulo"
         *  )
         */
        'name',
        /**
         *  State's Abbreviation
         *  @var string
         * 
         *  @OA\Property(
         *      property="abbreviation",
         *      pattern="/^[A-Z]{2}$/",
         *      example="SP",
         *  )
         */
        'abbreviation',
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
