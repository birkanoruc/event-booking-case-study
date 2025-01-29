<?php

namespace App\Contracts\Repositories;

use App\Models\User;

/**
 * Interface UserRepositoryInterface
 *
 * Kullanıcı yönetimi işlemlerinin yapılacağı repo sınıfının uygulaması için gereklidir.
 * Bu interface, kullanıcılarla ilgili veri erişim işlemlerini tanımlar.
 */
interface UserRepositoryInterface
{
    /**
     * Yeni bir kullanıcı oluşturacak metodu tanımlar.
     * @param array $data Kullanıcıya ait veriler
     * @return User Oluşturulan kullanıcı objesi
     */
    public function createUser(array $data): User;
}
