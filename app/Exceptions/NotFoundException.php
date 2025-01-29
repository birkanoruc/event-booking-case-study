<?php

namespace App\Exceptions;

use Exception;

class NotFoundException extends Exception
{
    public function __construct($message = "Kaynak bulunamadı.", $code = 404)
    {
        parent::__construct($message, $code);
    }

    public function report()
    {
        // NotFoundException ile yakalanan hataları loglamayı devre dışı bırakmak için report metodunu override ediyoruz.
    }
}
