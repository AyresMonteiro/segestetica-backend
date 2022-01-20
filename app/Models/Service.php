<?php

namespace App\Models;

use App\Models\Data\MoneyData;
use App\Rules\UnicodeName;
use App\Rules\UnicodeText;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Service extends Model
{
    use HasFactory;

    protected ?MoneyData $moneyInfo = null;

    protected $fillable = [
        'id',
        'name',
        'description',
        'deleted',
        'integerValue',
        'fractionalValue',
        'created_at',
        'updated_at',
    ];

    protected $hidden = [
        'integerValue',
        'fractionalValue',
    ];

    protected $appends = [
        'value',
    ];

    public function &getMoneyInfo(): ?MoneyData
    {
        if ($this->moneyInfo === null) {
            $this->useModelValuesInMoneyInfo();
        }

        return $this->moneyInfo;
    }

    public function setMoneyInfo(MoneyData $moneyInfo): void
    {
        $this->moneyInfo = $moneyInfo;
    }

    public function useModelValuesInMoneyInfo(): void
    {
        $hasIntegerValue = isset($this->attributes['integerValue']);
        $hasFractionalValue = isset($this->attributes['fractionalValue']);

        if ($hasIntegerValue && $hasFractionalValue) {
            $integerValue = $this->attributes['integerValue'];
            $fractionalValue = $this->attributes['fractionalValue'];

            $this->setMoneyInfo(new MoneyData($integerValue, $fractionalValue));
        }
    }

    public function getValueAttribute()
    {
        if ($this->moneyInfo === null) {
            $this->useModelValuesInMoneyInfo();
        }

        return $this->moneyInfo->getStringValue();
    }

    public static function getQueryValidator(array $data)
    {
        return Validator::make($data, [
            'id' => ['nullable', 'integer'],
            'name' => ['nullable', 'string', new UnicodeName],
            'description' => ['nullable', 'string', new UnicodeText],
            'deleted',
            'integerValue',
            'fractionalValue',
            'created_at_greater_than' => ['nullable', 'date'],
            'created_at_lesser_than' => ['nullable', 'date'],
            'updated_at_greater_than' => ['nullable', 'date'],
            'updated_at_lesser_than' => ['nullable', 'date'],
        ]);
    }

    public static function getStoreValidator(array $data)
    {
        return Validator::make($data, []);
    }

    public static function getStoreRequestValidator(array $data)
    {
        return Validator::make($data, [
            'id' => ['required_without:name,description,integerValue,fractionalValue', 'integer'],
            'name' => ['required_without:id', 'string', new UnicodeName],
            'description' => ['required_without:id', 'string', new UnicodeText],
            'integerValue' => ['required_without:id', 'integer'],
            'fractionalValue' => ['required_without:id', 'integer'],
        ]);
    }

    public static function getChangeRequestValidator(array $data)
    {
        return Validator::make($data, [
            'serviceId' => ['required', 'integer'],
            'establishmentUuid' => ['required', 'uuid'],
            'active' => ['required', 'boolean'],
        ]);
    }

    public static function getUpdateValidator(array $data)
    {
        return Validator::make($data, []);
    }

    public static function getUpdateRequestValidator(array $data)
    {
        return Validator::make($data, []);
    }
}
