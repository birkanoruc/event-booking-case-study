<?php

namespace App\Contracts\Repositories;

use App\Models\Event;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface EventRepositoryInterface
 *
 * Etkinlik yönetimi işlemlerinin yapılacağı repo sınıfının uygulaması için gereklidir.
 * Bu interface, etkinliklerle ilgili veri erişim işlemlerini tanımlar.
 */
interface EventRepositoryInterface
{
    /**
     * Tüm etkinlikleri listeleyecek metodu tanımlar.
     * @param array $eventFilterData
     * @return LengthAwarePaginator
     */
    public function getEvents(array $eventFilterData): LengthAwarePaginator;

    /**
     * Belirli bir etkinliği getirecek metodu tanımlar.
     * @param int $eventId
     * @return Event
     */
    public function getEventById(int $eventId): Event;

    /**
     * Yeni bir etkinlik oluşturacak metodu tanımlar.
     * @param array $eventData
     * @return Event
     */
    public function createEvent(array $eventData): Event;

    /**
     * Belirli bir etkinliği güncelleyecek metodu tanımlar.
     * @param int $eventId
     * @param array $eventData
     * @return Event
     */
    public function updateEvent(int $eventId, array $eventData): Event;

    /**
     * Belirli bir etkinliği silecek metodu tanımlar.
     * @param int $eventId
     * @return bool
     */
    public function deleteEvent(int $eventId): bool;

    /**
     * Belirli bir etkinliğin koltuk detaylarını listeleyecek metodu tanımlar.
     * @param array $seatFilterData
     * @param int $eventId
     * @return LengthAwarePaginator
     */
    public function getEventSeats(array $seatFilterData, int $eventId): LengthAwarePaginator;
}
