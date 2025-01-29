<?php

use App\Exceptions\CustomExceptionHandler;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\LogRequestResponse;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    /**
     * append => global olarak kullanılmak istenilen ara katmanlar tanımlanır
     * alias => route içinde kullanılmak istenilen ara katmanlar tanımlanır
     * logRequestResponse ile genel olarak istekleri ve yanıtları logluyoruz.
     */
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(LogRequestResponse::class);
    })
    /**
     *  withExceptions metodunun içinde, uygulama genelindeki tüm hatalar ele alınacaktır.
     *  renderable metodu, belirtilen hata (Throwable $exception) oluştuğunda nasıl bir yanıt döndürüleceğini tanımlar.
     *  CustomExceptionHandler::handle($exception) çağrılır.
     *  response()->json() metodu, hata bilgilerini JSON formatında döndürür.
     */
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (Throwable $exception, $request) {
            $handler = app(CustomExceptionHandler::class); // Laravel servis konteynerinden çözümleme
            return $handler->handle($exception);
        });
    })->create();
