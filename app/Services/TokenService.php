<?php

namespace App\Services;

use App\Contracts\Services\TokenInterface;
use App\Models\User;

class TokenService implements TokenInterface
{
    /**
     * Token doğrulama işlemlerini sağlayan servis.
     * @var TokenInterface
     */
    protected TokenInterface $tokenAuth;

    /**
     * TokenService sınıfının yapıcı fonksiyonu.
     * @param \App\Contracts\Services\TokenInterface $tokenAuth
     */
    public function __construct(TokenInterface $tokenAuth)
    {
        $this->tokenAuth = $tokenAuth;
    }

    /**
     * Kullanıcı için token'ı oluşturur.
     * @param mixed $user
     * @return string
     */
    public function generateToken($user): string
    {
        return $this->tokenAuth->generateToken($user);
    }

    /**
     * Kullanıcı için payload içeren token'ı oluşturur.
     * @param mixed $user
     * @return string
     */
    public function generateTokenWithPayload($user, array $payload): string
    {
        return $this->tokenAuth->generateTokenWithPayload($user, $payload);
    }

    /**
     * Kimlik doğrulama bilgilerini doğrular ve token'ı döner.
     * @param array $credentials
     * @return string|null
     */
    public function validateCredentials(array $credentials): ?string
    {
        return $this->tokenAuth->validateCredentials($credentials);
    }

    /**
     * Mevcut token'ı geçersiz kılar.
     * @return void
     */
    public function invalidateToken(): void
    {
        $this->tokenAuth->invalidateToken();
    }

    /**
     * Mevcut token'ı yeniler.
     * @return string
     */
    public function refreshToken(): string
    {
        return $this->tokenAuth->refreshToken();
    }

    /**
     * Kimlik doğrulanan kullanıcıyı döner.
     * @return mixed
     */
    public function getAuthenticatedUser(): ?User
    {
        return $this->tokenAuth->getAuthenticatedUser();
    }

    /**
     * Geçerli token'ı doğrular.
     * @return bool
     */
    public function validateToken(): bool
    {
        return $this->tokenAuth->validateToken();
    }

    /**
     * Token için geçerlilik süresi alır.
     * @return float
     */
    public function generateTokenExpiresIn(): float
    {
        return $this->tokenAuth->generateTokenExpiresIn();
    }
}
