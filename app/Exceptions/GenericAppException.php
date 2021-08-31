<?php

namespace App\Exceptions;

use Exception;

class GenericAppException extends Exception
{
    protected $customErrors;
    protected $statusCode;
    protected $actionsToPerform;

    public function __construct(
        array $customErrors = [],
        int $statusCode = 400,
        array $actionsToPerform = []
    ) {
        parent::__construct(__('messages.error_detect'));

        if (sizeof($customErrors) === 0) {
            array_push($customErrors, __('messages.system_error'));
        }

        $this->customErrors = $customErrors;
        $this->statusCode = $statusCode;
        $this->actionsToPerform = $actionsToPerform;
    }

    public function appendErrors(array|string $errors)
    {
        $appendHandlers = [
            'array' => fn ($arr1, $arr2) => array_merge($arr1, $arr2),
            'string' => fn ($arr, $arg) => [...$arr, $arg],
        ];

        if (!isset($appendHandlers[gettype($errors)])) {
            throw new self(__('messages.implementation_error'), 500);
        }

        $this->customErrors = $appendHandlers[gettype($errors)]($this->customErrors, $errors);
    }

    public function getCustomErrors()
    {
        return $this->customErrors;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function performActions()
    {
        foreach ($this->actionsToPerform as $action) {
            if (!isset($action)) continue;

            $action();
        }
    }

    public function setCustomErrors(array $errors)
    {
        $this->customErrors = $errors;
    }

    public function setStatusCode(int $statusCode)
    {
        $this->statusCode = $statusCode;
    }

    public function setActionsToPerform($actions)
    {
        $this->actionsToPerform = $actions;
    }
}
