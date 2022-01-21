<?php

namespace App\Models;

use App\Contracts\HasConfirmationMail;
use App\Models\Data\{EmailData, EmailViewData, SanctumTokenData};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\HasApiTokens;

/**
 * @OA\Schema()
 */
class Establishment extends Model implements HasConfirmationMail
{
    use HasFactory, HasApiTokens;

    const GENERAL_TOKEN_NAME = "general-establishment-login";
    const GENERAL_ABILITY = "establishment:general";
    const CONFIRMATION_ABILITY = "establishment:confirm-mail";

    protected $fillable = [
        /**
         *  Establishment's Universal Unique Identifier
         *  @var string
         * 
         *  @OA\Property(
         *      property="uuid",
         *      format="uuid",
         *  )
         */
        'uuid',
        /**
         *  Establishment's Name
         *  @var string
         * 
         *  @OA\Property(
         *      property="name",
         *  )
         */
        'name',
        /**
         *  Establishment's Email
         *  @var string
         * 
         *  @OA\Property(
         *      property="email",
         *      format="email",
         *  )
         */
        'email',
        /**
         *  Establishment's Photo Url
         *  @var string
         * 
         *  @OA\Property(
         *      property="photoUrl",
         *      format="url",
         *  )
         */
        'photoUrl',
        /**
         *  Establishment's Street Id Foreign
         *  @var integer
         * 
         *  @OA\Property(
         *      property="streetId",
         *      format="bigint",
         *  )
         */
        'streetId',
        /**
         *  Establishment Address' Number
         *  @var string
         * 
         *  @OA\Property(
         *      property="addresNumber",
         *  )
         */
        'addressNumber',
        'deleted',
        'passwordHash',
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

    protected $hidden = [
        'street',
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

    public static function getMailConfirmationValidator(array $data)
    {
        return Validator::make($data, [
            'token' => ['required', 'string', 'regex:' . SanctumTokenData::tokenRegex],
        ]);
    }

    public function street()
    {
        return $this->belongsTo(Street::class, 'streetId', 'id');
    }

    public function getAddressAttribute()
    {
        $address = [
            "street" => $this->street,
            "neighborhood" => $this->street->neighborhood,
            "city" => $this->street->neighborhood->city,
            "state" => $this->street->neighborhood->city->state,
        ];

        $address["street"]->makeHidden('neighborhood');
        $address["neighborhood"]->makeHidden('city');
        $address["city"]->makeHidden('state');

        return $address;
    }

    public function services()
    {
        return $this->hasManyThrough(
            Service::class,
            EstablishmentService::class,
            'establishmentUuid',
            'id',
            'uuid',
            'serviceId',
        );
    }

    public function schedules()
    {
        return $this->hasMany(
            Schedule::class,
            'establishmentUuid',
            'uuid'
        );
    }

    public function generateConfirmationMailData(): EmailViewData
    {
        $viewName = 'confirm_establishment_mail';

        $token = $this->createToken(
            'confirm-token',
            [self::CONFIRMATION_ABILITY],
        )->plainTextToken;

        $viewData = [
            'url' => env('APP_URL') . "/api/establishments/confirm?token=" . urlencode($token),
        ];

        $contactData = new EmailData($this->name, $this->email);

        return new EmailViewData(
            $viewName,
            $viewData,
            __('messages.mail_confirmation_title'),
            App::getLocale(),
            $contactData
        );
    }
}
