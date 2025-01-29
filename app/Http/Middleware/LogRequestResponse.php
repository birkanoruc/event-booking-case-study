<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Contracts\Services\LogInterface;

class LogRequestResponse
{
    /**
     * LogService özelliği, LogInterface'i uygular ve loglama işlemleri için kullanılır.
     * @var LogInterface
     */
    protected LogInterface $logService;

    /**
     * LogService'in bağımlılık enjeksiyonu ile atanmasını sağlayan yapılandırıcı.
     * @param \App\Contracts\Services\LogInterface $logService
     */
    public function __construct(LogInterface $logService)
    {
        $this->logService = $logService;
    }

    /**
     * Request/Response loglama işlemlerini gerçekleştiren ara katman (middleware).
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $this->logService->logRequest($request);
        $response = $next($request);
        $this->logService->logResponse($response);
        return $response;
    }
}
