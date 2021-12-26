<?php

namespace App\Models;

use App\Contracts\HasConfirmationMail;
use App\Models\Data\EmailData;
use App\Models\Data\EmailViewData;
use App\Models\Data\SanctumTokenData;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\HasApiTokens;

class User extends Model implements HasConfirmationMail
{
    use HasFactory, HasApiTokens;

    const userNamePattern = "/^(\p{L}| )+$/u";
    const userPhoneNumberParsePattern = "/^(\+\d{2})? ?\(?(\d{2})\)? ?(\d)? ?(\d{4})-?(\d{4})$/";
    const userPhoneNumberSavePattern = "/^\+?(\d){10,13}$/";
    const userPhoneNumberSearchPattern = "/^\+?(\d)+$/";

    protected $fillable = [
        'uuid',
        'name',
        'lastName',
        'email',
        'passwordHash',
        'phoneNumber',
        'neighborhoodId',
        'emailConfirmation',
        'deleted',
        'created_at',
        'updated_at',
    ];

    protected $hidden = [
        'passwordHash',
        'deleted',
    ];

    protected $primaryKey = 'uuid';
    public $incrementing = false;

    public static function getQueryValidator(array $data)
    {
        return Validator::make($data, [
            'uuid' => ['nullable', 'uuid'],
            'name_search' => ['nullable', 'string', 'regex:' . self::userNamePattern],
            'lastName_search' => ['nullable', 'string', 'regex:' . self::userNamePattern],
            'email_search' => ['nullable', 'string', 'regex:' . self::userNamePattern],
            'phoneNumber_search' => ['nullable', 'string', 'regex:' . self::userPhoneNumberSearchPattern],
            'neighborhoodId' => ['nullable', 'integer'],
            'deleted' => ['nullable', 'boolean'],
            'created_at_greater_than' => ['nullable', 'date'],
            'created_at_lesser_than' => ['nullable', 'date'],
            'updated_at_greater_than' => ['nullable', 'date'],
            'updated_at_lesser_than' => ['nullable', 'date'],
        ]);
    }

    public static function getStoreValidator(array $data)
    {
        return Validator::make($data, [
            'uuid' => ['required', 'string', 'uuid'],
            'name' => ['required', 'string', 'regex:' . self::userNamePattern],
            'lastName' => ['required', 'string', 'regex:' . self::userNamePattern],
            'email' => ['required', 'string', 'email'],
            'passwordHash' => ['required', 'string'],
            'phoneNumber' => ['required', 'string', 'regex:' . self::userPhoneNumberSavePattern],
            'neighborhoodId' => ['required', 'integer', 'exists:neighborhoods,id'],
        ]);
    }

    public static function getStoreRequestValidator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'regex:' . self::userNamePattern],
            'lastName' => ['required', 'string', 'regex:' . self::userNamePattern],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'phoneNumber' => ['required', 'string', 'regex:' . self::userPhoneNumberParsePattern],
            'neighborhoodId' => ['required', 'integer'],
        ]);
    }

    public static function getUpdateValidator(array $data)
    {
        return Validator::make($data, [
            'uuid' => ['required_without:name,lastName,email,passwordHash,phoneNumber,neighborhoodId,emailConfirmation,deleted', 'string', 'uuid'],
            'name' => ['required_without:uuid,lastName,email,passwordHash,phoneNumber,neighborhoodId,emailConfirmation,deleted', 'string', 'regex:' . self::userNamePattern],
            'lastName' => ['required_without:uuid,name,email,passwordHash,phoneNumber,neighborhoodId,emailConfirmation,deleted', 'string', 'regex:' . self::userNamePattern],
            'email' => ['required_without:uuid,name,lastName,passwordHash,phoneNumber,neighborhoodId,emailConfirmation,deleted', 'string', 'email'],
            'passwordHash' => ['required_without:uuid,name,lastName,email,phoneNumber,neighborhoodId,emailConfirmation,deleted', 'string'],
            'phoneNumber' => ['required_without:uuid,name,lastName,email,passwordHash,neighborhoodId,emailConfirmation,deleted', 'string', self::userPhoneNumberSavePattern],
            'neighborhoodId' => ['required_without:uuid,name,lastName,email,passwordHash,phoneNumber,emailConfirmation,deleted', 'integer', 'exists:neighborhoods,id'],
        ]);
    }

    public static function getMailConfirmationValidator(array $data)
    {
        return Validator::make($data, [
            'token' => ['required', 'string', 'regex:' . SanctumTokenData::tokenRegex],
        ]);
    }

    public static function getLoginRequestValidator(array $data)
    {
        return Validator::make($data, [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);
    }

    public static function getUpdateRequestValidator(array $data)
    {
        return Validator::make($data, [
            'uuid' => ['required_without:name,lastName,email,password,phoneNumber,neighborhoodId,emailConfirmation,deleted', 'string', 'uuid'],
            'name' => ['required_without:uuid,lastName,email,password,phoneNumber,neighborhoodId,emailConfirmation,deleted', 'string', 'regex:' . self::userNamePattern],
            'lastName' => ['required_without:uuid,name,email,password,phoneNumber,neighborhoodId,emailConfirmation,deleted', 'string', 'regex:' . self::userNamePattern],
            'email' => ['required_without:uuid,name,lastName,password,phoneNumber,neighborhoodId,emailConfirmation,deleted', 'string', 'email'],
            'password' => ['required_without:uuid,name,lastName,email,phoneNumber,neighborhoodId,emailConfirmation,deleted', 'string'],
            'phoneNumber' => ['required_without:uuid,name,lastName,email,password,neighborhoodId,emailConfirmation,deleted', 'string', self::userPhoneNumberSavePattern],
            'neighborhoodId' => ['required_without:uuid,name,lastName,email,password,phoneNumber,emailConfirmation,deleted', 'integer'],
        ]);
    }

    public function generateConfirmationMailData(): EmailViewData
    {
        $viewName = 'confirm_establishment_mail';

        $token = $this->createToken(
            'confirm-token',
            ['user:confirm-mail'],
        )->plainTextToken;

        $viewData = [
            'url' => env('APP_URL') . "/api/users/confirm?token=" . urlencode($token),
        ];

        $contactData = new EmailData($this->name . ' ' . $this->lastName, $this->email);

        return new EmailViewData(
            $viewName,
            $viewData,
            __('messages.mail_confirmation_title'),
            App::getLocale(),
            $contactData
        );
    }
}
