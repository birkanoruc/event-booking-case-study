<?php

namespace App\Repositories;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Models\User;

/**
 * UserRepository sınıfı, UserRepositoryInterface interface'ini implement eder.
 * Bu sınıf, kullanıcı verilerini veri tabanında işlemek için gerekli tüm işlemleri gerçekleştirir.
 */
class UserRepository implements UserRepositoryInterface
{
    /**
     * Veritabanında yeni bir kullanıcı kaydı oluşturur.
     * @param array $data Kullanıcı verileri
     * @return User Oluşturulan kullanıcı objesi
     */
    public function createUser(array $data): User
    {
        return User::create($data);
    }
}
