<?php

namespace App\Contracts\Services;

use Illuminate\Http\JsonResponse;

interface ReservationInterface
{
    /**
     * Tüm rezervasyonların listeleneme sürecinin yönetileceği metodu tanımlar.
     * @return JsonResponse
     */
    public function getReservations(array $reservationFilterData): JsonResponse;

    /**
     * Belirli bir rezervasyon bilgilerini getirme sürecinin yönetileceği metodu tanımlar.
     * @param int $reservationId
     * @return JsonResponse
     */
    public function getReservationById(int $reservationId): JsonResponse;

    /**
     * Yeni bir rezervasyonun oluşturma sürecinin yönetileceği metodu tanımlar.
     * @return JsonResponse
     */
    public function createReservation(): JsonResponse;

    /**
     * Belirli bir etkinliğin onaylanma sürecinin yönetileceği metodu tanımlar.
     * @param int $reservationId
     * @return JsonResponse
     */
    public function confirmReservation(int $reservationId): JsonResponse;

    /**
     * Belirli bir rezervasyonun silme/iptal sürecinin yönetileceği metodu tanımlar.
     * @param int $reservationId
     * @return JsonResponse
     */
    public function deleteReservation(int $reservationId): JsonResponse;
}
