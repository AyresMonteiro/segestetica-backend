<?php

namespace App\Exceptions;

use Exception;

class BadFormattedMoneyDataException extends Exception
{
    const DecimalPartLesserThanMinusNinetyNine = 0;
    const DecimalPartGreaterThanNinetyNine = 1;
    const IntegerPartAndDecimalPartAreLesserThanZero = 2;
    const IntegerPartIsDifferentThanZeroAndDecimalPartAreLesserThanZero = 3;

    public function __construct(public int $errorCode)
    {
        parent::__construct();
    }
}
