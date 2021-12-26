<?php

namespace App\Contracts;

use App\Models\Data\EmailViewData;

interface HasConfirmationMail
{
	function generateConfirmationMailData(): EmailViewData;
}
