<?php

namespace App\Exceptions;

use Throwable;
use App\Services\LogService;
use Illuminate\Http\JsonResponse;

class CustomExceptionHandler
{
    private LogService $logService;

    public function __construct(LogService $logService)
    {
        $this->logService = $logService;
    }

    /**
     * Hata mesajlarını özelleştirmek ve loglamak için kullanılır.
     * @param Throwable $exception
     * @return array
     */
    public function handle(Throwable $exception): JsonResponse
    {
        if (app()->environment('local')) {
            $this->logService->logException($exception);
        }

        $status = self::getStatusCode($exception);
        $message = self::getErrorMessage($exception);
        $errors = self::getErrorDetails($exception);

        $response = [
            'success' => false,
            'message' => $message
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $status);
    }

    private static function getStatusCode(Throwable $exception): int
    {
        $httpStatusCode = match (true) {
            $exception instanceof \TypeError,
            $exception instanceof \Symfony\Component\HttpKernel\Exception\BadRequestHttpException => 400,
            $exception instanceof \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException => 401,
            $exception instanceof \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException => 403,
            $exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException => 404,
            $exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException => 404,
            $exception instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException => 405,
            $exception instanceof \Illuminate\Validation\ValidationException => 422,
            $exception instanceof \Illuminate\Database\QueryException => 500,
            $exception instanceof \Symfony\Component\ErrorHandler\Error\FatalError => 500,
            $exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException => $exception->getStatusCode(),
            default => $exception->getCode(),
        };
        return (is_numeric($httpStatusCode) && $httpStatusCode >= 100 && $httpStatusCode <= 599) ? $httpStatusCode : 500;
    }

    private static function getErrorMessage(Throwable $exception): string
    {
        $exception = $exception->getPrevious() ?: $exception;

        $message = match (true) {
            $exception instanceof \TypeError,
            $exception instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException => "Geçersiz token hatası. Token süresi dolmuş olabilir.",
            $exception instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException => "Geçersiz token hatası. Token kullanım dışı bırakılmış olabilir.",
            $exception instanceof \Tymon\JWTAuth\Exceptions\JWTException => "Geçersiz token hatası.",
            $exception instanceof \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException => "Geçersiz token hatası. Lütfen giriş yapın.",
            $exception instanceof \Symfony\Component\HttpKernel\Exception\BadRequestHttpException => "Parametre türü uyumsuz. Lütfen doğru veri tipi gönderin.",
            $exception instanceof \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException => 'Bu işlemi gerçekleştirme yetkiniz yok.',
            $exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException => 'Belirtilen kaynak bulunamadı. İlgili kayıt silinmiş veya mevcut olmayabilir.',
            $exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException => 'İstenen yol bulunamadı. Lütfen URL\'yi kontrol edin.',
            $exception instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException => 'Bu metod belirtilen rota için desteklenmiyor.',
            $exception instanceof \Illuminate\Validation\ValidationException => 'Veri doğrulama hatası.',
            $exception instanceof \Symfony\Component\ErrorHandler\Error\FatalError => 'Beklenmeyen bir hata oluştu. Lütfen site sahibi ile iletişime geçiniz.',
            $exception instanceof \Illuminate\Database\QueryException => 'Veritabanı hatası sebebiyle işlem gerçekleştirilemedi. Lütfen tekrar deneyin.',
            $exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException => 'Bir HTTP hatası oluştu: ' . ($exception->getMessage() ?: 'Bilinmeyen hata.'),
            default => $exception->getMessage() ?: 'Bilinmeyen bir hata oluştu.',
        };

        return $message;
    }

    private static function getErrorDetails(Throwable $exception): array
    {
        if ($exception instanceof \Illuminate\Validation\ValidationException) {
            return $exception->errors();
        }

        return [];
    }
}
