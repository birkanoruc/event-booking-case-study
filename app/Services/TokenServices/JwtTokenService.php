<?php

namespace App\Services\TokenServices;

use App\Contracts\Services\TokenInterface;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Database\Eloquent\Model;

class JwtTokenService implements TokenInterface
{
    /**
     * Kullanıcıdan JWT token'ı oluşturur.
     * @param mixed $user
     * @return string
     */
    public function generateToken($user): string
    {
        return JWTAuth::claims(['role' => $user->role])->fromUser($user);
    }

    /**
     * Kullanıcıdan istenilen payloadları içeren JWT token oluşturur.
     * @param mixed $user
     * @param array $payload
     * @return mixed
     */
    public function generateTokenWithPayload($user, array $payload): string
    {
        return JWTAuth::claims($payload)->fromUser($user);
    }

    /**
     * Kullanıcı kimlik doğrulama bilgilerini doğrular ve JWT token'ı döner.
     * @param array $credentials
     * @return mixed
     */
    public function validateCredentials(array $credentials): ?string
    {
        return JWTAuth::attempt($credentials) ?: null;
    }

    /**
     * Mevcut JWT token'ını geçersiz kılar.
     * @return void
     */
    public function invalidateToken(): void
    {
        JWTAuth::invalidate(JWTAuth::getToken());
    }

    /**
     * Mevcut JWT token'ını yeniler.
     * @return string
     */
    public function refreshToken(): string
    {
        return JWTAuth::refresh(JWTAuth::getToken());
    }

    /**
     * Mevcut JWT token'ına sahip olan kullanıcıyı döner.
     * @return mixed
     */
    public function getAuthenticatedUser(): ?Model
    {
        return JWTAuth::user();
    }

    /**
     * Geçerli JWT token'ını doğrular.
     * @return bool
     */
    public function validateToken(): bool
    {
        JWTAuth::parseToken()->authenticate();
        return true;
    }

    /**
     * JWT Token için geçerlilik süresi yansıtır.
     * @return float
     */
    public function generateTokenExpiresIn(): float
    {
        return JWTAuth::factory()->getTTL() * 60;
    }
}
