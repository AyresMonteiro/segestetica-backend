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

    public function setMoneyInfo(MoneyData $moneyInfo): void
    {
        $this->moneyInfo = $moneyInfo;
    }

    public function getValueAttribute()
    {
        if ($this->moneyInfo === null) {
            $integerValue = $this->attributes['integerValue'];
            $fractionalValue = $this->attributes['fractionalValue'];

            $this->setMoneyInfo(new MoneyData($integerValue, $fractionalValue));
        }

        return $this->moneyInfo->getStringValue();
    }
}
