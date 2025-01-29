<?php

namespace App\Exceptions;

use Exception;

class AuthenticationException extends Exception
{
    public function __construct($message = "Kimlik doğrulama hatası.", $code = 401)
    {
        parent::__construct($message, $code);
    }

    public function report()
    {
        // Fırlatılan hataları loglamayı devre dışı bırakmak için report metodunu override ediyoruz.
    }
}
