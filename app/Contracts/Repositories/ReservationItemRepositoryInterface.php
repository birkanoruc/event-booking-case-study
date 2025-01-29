<?php

namespace App\Contracts\Repositories;

use App\Models\ReservationItem;

/**
 * Interface ReservationItemRepositoryInterface
 *
 * Rezervasyon öğeleri yönetimi işlemlerinin yapılacağı repo sınıfının uygulaması için gereklidir.
 * Bu interface, rezervasyon öğeleriyle ilgili veri erişim işlemlerini tanımlar.
 */
interface ReservationItemRepositoryInterface
{
    /**
     * Yeni bir rezervasyon öğesi oluşturacak metodu tanımlar.
     * @param array $reservationItemData
     * @return ReservationItem
     */
    public function createReservationItem(array $reservationItemData): ReservationItem;

    /**
     * Kimliği belli rezervasyon öğesini getirecek metodu tanımlar.
     * @param int $reservationItemId
     * @return void
     */
    public function getReservationItem(int $reservationItemId): ReservationItem;

    /**
     * Kimliği belli rezervasyon öğesini silecek metodu tanımlar.
     * @param int $reservationItemId
     * @return void
     */
    public function deleteReservationItem(int $reservationItemId): bool;
}
