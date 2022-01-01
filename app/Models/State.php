<?php

namespace App\Models;

use App\Rules\UnicodeName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Schema()
 */
class State extends Model
{
    use HasFactory;

    const stateAbbreviationPattern = "/^[A-Z]{2,3}$/";
    const stateAbbreviationQueryPattern = "/^[A-Z]{1,3}$/";

    protected $fillable = [
        /**
         *  State's Identifier
         *  @var integer
         * 
         *  @OA\Property(
         *      property="id",
         *      format="tinyint",
         *      example=255,
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
         *      example="São Paulo",
         *      pattern="/^(\p{L}| |'|\.)+$/u",
         *  )
         */
        'name',
        /**
         *  State's Abbreviation
         *  @var string
         * 
         *  @OA\Property(
         *      property="abbreviation",
         *      example="SP",
         *      pattern="/^[A-Z]{2}$/",
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
            'id' => ['nullable', 'integer', 'max:255'],
            'name' => ['nullable', 'string', new UnicodeName],
            'name_search' => ['nullable', 'string', new UnicodeName],
            'abbreviation' => ['nullable', 'string', 'regex:' . self::stateAbbreviationPattern],
            'abbreviation_search' => ['nullable', 'string', 'regex:' . self::stateAbbreviationQueryPattern],
            'created_at_greater_than' => ['nullable', 'date'],
            'created_at_lesser_than' => ['nullable', 'date'],
            'updated_at_greater_than' => ['nullable', 'date'],
            'updated_at_lesser_than' => ['nullable', 'date'],
        ]);
    }

    public static function getStoreValidator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', new UnicodeName, 'unique:states'],
            'abbreviation' => ['required', 'string', 'regex:' . self::stateAbbreviationPattern, 'unique:states'],
        ]);
    }

    public static function getStoreRequestValidator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', new UnicodeName],
            'abbreviation' => ['required', 'string', 'regex:' . self::stateAbbreviationPattern],
        ]);
    }

    public static function getUpdateValidator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required_without_all:abbreviation', 'string', new UnicodeName, 'unique:states'],
            'abbreviation' => ['required_without_all:name', 'string', 'regex:' . self::stateAbbreviationPattern, 'unique:states'],
        ]);
    }

    public static function getUpdateRequestValidator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required_without_all:abbreviation', 'string', new UnicodeName],
            'abbreviation' => ['required_without_all:name', 'string', 'regex:' . self::stateAbbreviationPattern],
        ]);
    }
}
