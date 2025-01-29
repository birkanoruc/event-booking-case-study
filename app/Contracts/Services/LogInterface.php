<?php

namespace App\Contracts\Services;

use Illuminate\Http\Request;
use Throwable;

interface LogInterface
{
    /**
     * Request verilerinin loglama sürecinin yönetileceği metodu tanımlar.
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function logRequest(Request $request): void;

    /**
     * Response verilerinin loglama sürecinin yönetileceği metodu tanımlar.
     * @param mixed $response
     * @return void
     */
    public function logResponse($response): void;

    /**
     * Yakalanan istisna verilerinin loglama sürecinin yönetileceği metodu tanımlar.
     * Yalnızca geliştirme ortamında, geliştirme sürecini daha iyi yönetebilmek için kullanılır.
     * @param \Throwable $exception
     * @return void
     */
    public function logException(Throwable $exception): void;
}
