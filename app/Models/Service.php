<?php

namespace App\Models;

use App\Models\Data\MoneyData;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
