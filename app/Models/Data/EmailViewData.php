<?php

namespace App\Models\Data;

class EmailViewData
{
	public function __construct(
		public string $viewName,
		public mixed $viewData,
		public string $mailTitle,
		public string $locale,
		public EmailData $contactData
	) {
	}
}
