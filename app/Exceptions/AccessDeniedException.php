<?php

namespace App\Exceptions;

use Exception;

class AccessDeniedException extends Exception
{
    public function __construct($message = "Bu işlemi gerçekleştirme yetkiniz yok.", $code = 403)
    {
        parent::__construct($message, $code);
    }

    public function report()
    {
        // Fırlatılan hataları loglamayı devre dışı bırakmak için report metodunu override ediyoruz.
    }
}
