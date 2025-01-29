<?php

namespace App\Traits;

use App\Enums\UserRole;
use App\Exceptions\AccessDeniedException;
use Tymon\JWTAuth\Facades\JWTAuth;

trait AuthorizationTrait
{
    /**
     * Kullanıcının belirli bir role sahip olup olmadığını kontrol eder.
     *
     * @param  UserRole $role İzin verilen rol
     * @return bool
     * @throws AccessDeniedException
     */
    public function authorizeRole(UserRole $role): bool
    {
        $payload = JWTAuth::parseToken()->getPayload(); // Token'dan payload'ı al
        $userRole = $payload->get('role');              // Payload'dan rol bilgisini al

        if ($userRole === $role->value) {
            return true;
        }

        throw new AccessDeniedException("Yetkisiz erişim. Yalnızca {$role->value} yetkisine sahip kullanıcılar erişebilir.");
    }
}
