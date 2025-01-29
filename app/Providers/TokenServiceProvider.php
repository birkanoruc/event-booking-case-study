<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\Services\TokenInterface;
use App\Services\TokenService;
use App\Services\TokenServices\JwtTokenService;

class TokenServiceProvider extends ServiceProvider
{
    /**
     * TokenInterface için singleton tanımı yapılır.
     * Kullanılacak token doğrulama tipi config dosyasındaki 'auth.token_type' anahtarına göre belirlenir.
     * Varsayılan olarak JwtTokenService kullanılır.
     * Yeni bir token servisi eklemek için aşağıdaki yapıya uygun bir eşleme eklenebilir.
     */
    public function register(): void
    {
        $this->app->singleton(TokenInterface::class, function ($app) {
            $tokenType = config('auth.token_type');

            return match ($tokenType) {
                default => new JwtTokenService(),
                // 'token_type' => new TokenTypeService(), // Yeni bir token servisi eklemek için kullanılır
            };
        });

        $this->app->singleton(TokenService::class, function ($app) {
            return new TokenService($app->make(TokenInterface::class));
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
