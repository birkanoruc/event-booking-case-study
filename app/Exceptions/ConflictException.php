<?php

namespace App\Exceptions;

use Exception;

class ConflictException extends Exception
{
    public function __construct($message = "Hatalı veya çakışan işlem.", $code = 409)
    {
        parent::__construct($message, $code);
    }

    public function report()
    {
        // NotFoundException ile yakalanan hataları loglamayı devre dışı bırakmak için report metodunu override ediyoruz.
    }
}
