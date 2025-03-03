<?php

namespace App\Exceptions;

class ValidationException extends \Exception
{
    private array $errors;

    public function __construct(array $errors)
    {
        $this->errors = $errors;
        parent::__construct('Validation error', 400);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    
}