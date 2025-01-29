<?php

namespace App\Exceptions;

use Exception;

class TokenException extends Exception
{
    public function __construct($message = "Token doğrulama hatası.", $code = 401)
    {
        parent::__construct($message, $code);
    }

    // Fırlatılan hataları loglamayı devre dışı bırakmak için report metodunu override ediyoruz.
    public function report()
    {
        // Burada hiçbir şey yapmıyoruz, böylece loglanmaz.
    }
}
