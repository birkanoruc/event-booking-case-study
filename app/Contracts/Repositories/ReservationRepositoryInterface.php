<?php

namespace App\Contracts\Repositories;

use App\Models\Reservation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface ReservationItemRepositoryInterface
 *
 * Rezervasyon yönetimi işlemlerinin yapılacağı repo sınıfının uygulaması için gereklidir.
 * Bu interface, rezervasyonlarla ilgili veri erişim işlemlerini tanımlar.
 */
interface ReservationRepositoryInterface
{
    /**
     * Oturum açan kullanıcınya ait tüm rezervasyonları listeleyecek metodu tanımlar.
     * @return LengthAwarePaginator
     */
    public function getUserReservations(array $reservationFilterData): LengthAwarePaginator;

    /**
     * Oturum açan kullanıcıya ait belirli bir rezervasyonu getirecek metodu tanımlar.
     * @param int $reservationId
     * @return Reservation
     */
    public function getUserReservationById(int $reservationId): Reservation;

    /**
     * Yeni bir rezervasyon oluşturacak metodu tanımlar.
     * @return Reservation
     */
    public function createReservation();

    /**
     * Oturum açan kullanıcıya ait rezervasyonu onaylayacak metodu tanımlar.
     * @param int $reservationId
     * @return Reservation
     */
    public function confirmUserReservation(int $reservationId);

    /**
     * Oturum açan kullanıcıya ait rezervasyonu iptal edecek metodu tanımlar.
     * @param int $reservationId
     * @return bool
     */
    public function cancelUserReservation(int $reservationId): bool;
}
