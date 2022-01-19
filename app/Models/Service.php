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

    public static function getStoreRequestValidator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', new UnicodeName],
            'description' => ['required', 'string', new UnicodeText],
            'integerValue' => ['required', 'integer'],
            'fractionalValue' => ['required', 'integer'],
        ]);
    }
}
