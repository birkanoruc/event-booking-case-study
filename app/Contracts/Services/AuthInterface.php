<?php

namespace App\Contracts\Services;

use Illuminate\Http\JsonResponse;

interface AuthInterface
{
    /**
     * Kullanıcı kayıt sürecinin yönetileceği metodu tanımlar.
     * @param array $userData
     * @return JsonResponse
     */
    public function register(array $userData): JsonResponse;

    /**
     * Kullanıcı giriş sürecinin yönetileceği metodu tanımlar.
     * @param array $userCredentials
     * @return JsonResponse
     */
    public function login(array $userCredentials): JsonResponse;

    /**
     * Kullanıcı çıkış sürecinin yönetileceği metodu tanımlar.
     * @return JsonResponse
     */
    public function logout(): JsonResponse;

    /**
     * Token yenileme sürecinin yönetileceği metodu tanımlar.
     * @return JsonResponse
     */
    public function refresh(): JsonResponse;

    /**
     * Oturumu devam eden kullanıcı bilgilerini getirme sürecinin yönetileceği metodu tanımlar.
     * @return JsonResponse
     */
    public function getAuthenticatedUser(): JsonResponse;
}
