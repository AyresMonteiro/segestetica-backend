<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Schema()
 */
class Street extends Model
{
    use HasFactory;

    protected $fillable = [
        /**
         *  Street's Identifier
         *  @var integer
         * 
         *  @OA\Property(
         *      property="id",
         *      format="bigint",
         *      example=8125379,
         *  )
         */
        'id',
        /**
         *  Street's Name
         *  @var string
         * 
         *  @OA\Property(
         *      property="name",
         *      format="string",
         *      example="Rua Antônio de Godói 122",
         *  )
         */
        'name',
        /**
         *  Street's Post Code
         *  @var string
         * 
         *  @OA\Property(
         *      property="postCode",
         *      pattern="/^\d{2}\.?\d{3}-?\d{3}$/",
         *      example="01.034-903",
         *  )
         */
        'postCode',
        /**
         *  Street's Neighborhood Id Foreign
         *  @var integer
         * 
         *  @OA\Property(
         *      property="neighborhoodId",
         *      format="bigint",
         *      example=8125378,
         *  )
         */
        'neighborhoodId',
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

    public function neighborhood()
    {
        return $this->belongsTo(Neighborhood::class, 'neighborhoodId', 'id');
    }
}
