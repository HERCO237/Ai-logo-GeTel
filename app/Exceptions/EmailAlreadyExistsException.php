<?php

namespace App\Exceptions;

use Exception;

class EmailAlreadyExistsException extends Exception
{
    public function __construct(
        string $message = 'Cette adresse e-mail est déjà utilisée.'
    ) {
        parent::__construct($message);
    }
}
