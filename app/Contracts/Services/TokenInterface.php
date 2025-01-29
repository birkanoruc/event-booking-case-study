<?php

namespace App\Contracts\Services;

use Illuminate\Database\Eloquent\Model;

interface TokenInterface
{
    /**
     * Token oluşturma sürecinin yönetileceği metodu tanımlar.
     * @param mixed $user
     * @return string
     */
    public function generateToken($user): string;

    /**
     * Payload barındıran token oluşturma sürecinin yönetileceği metodu tanımlar.
     * @param mixed $user
     * @param array $payload
     * @return string
     */
    public function generateTokenWithPayload($user, array $payload): string;

    /**
     * Kimlik doğrulama sürecinin yönetileceği metodu tanımlar.
     * @param array $credentials
     * @return ?string
     */
    public function validateCredentials(array $credentials): ?string;

    /**
     * Token'ı geçersiz kılma sürecinin yönetileceği metodu tanımlar.
     * @return void
     */
    public function invalidateToken(): void;

    /**
     * Token'ı yenileme sürecinin yönetileceği metodu tanımlar.
     * @return string
     */
    public function refreshToken(): string;

    /**
     * Oturumu açık olan kullanıcının bilgilerini getirme sürecini yöneten metodu tanımlar.
     * @return ?Model
     */
    public function getAuthenticatedUser(): ?Model;

    /**
     * Token doğrulama sürecinin yönetileceği metodu tanımlar.
     * @return bool
     */
    public function validateToken(): bool;

    /**
     * Token geçerlilik süresinin üretileceği metodu tanımlar.
     * @return float
     */
    public function generateTokenExpiresIn(): float;
}
