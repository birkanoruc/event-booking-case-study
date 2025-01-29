<?php

namespace App\Contracts\Services;

use Illuminate\Http\JsonResponse;

interface EventInterface
{
    /**
     * Tüm etkinliklerin listeleme sürecinin yönetileceği metodu tanımlar.
     * @return JsonResponse
     */
    public function getEvents(array $eventFilterData): JsonResponse;

    /**
     * Belirli bir etkinliğin bilgilerini getirme sürecinin yönetileceği metodu tanımlar.
     * @param int $eventId
     * @return JsonResponse
     */
    public function getEventById(int $eventId): JsonResponse;

    /**
     * Yeni bir etkinliğin oluşturma sürecinin yönetileceği metodu tanımlar.
     * @param array $eventData
     * @return JsonResponse
     */
    public function createEvent(array $eventData): JsonResponse;

    /**
     * Belirli bir etkinliğin güncelleme sürecinin yönetileceği metodu tanımlar.
     * @param int $eventId
     * @param array $eventData
     * @return JsonResponse
     */
    public function updateEvent(int $eventId, array $eventData): JsonResponse;

    /**
     * Belirli bir etkinliğin silme sürecinin yönetileceği metodu tanımlar.
     * @param int $eventId
     * @return JsonResponse
     */
    public function deleteEvent(int $eventId): JsonResponse;

    /**
     * Belirli bir etkinliğin koltuk bilgilerini listeleme sürecinin yönetileceği metodu tanımlar.
     * @param int $eventId
     * @return JsonResponse
     */
    public function getEventSeats(array $seatFilterData, int $eventId): JsonResponse;
}
