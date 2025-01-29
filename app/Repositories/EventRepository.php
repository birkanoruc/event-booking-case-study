<?php

namespace App\Repositories;

use App\Contracts\Repositories\EventRepositoryInterface;
use App\Exceptions\NotFoundException;
use App\Filter\EventFilter;
use App\Filter\SeatFilter;
use App\Models\Event;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EventRepository implements EventRepositoryInterface
{
    /**
     * Etkinlik tablosunda tüm etkinlikleri ilişkileri ile beraber sayfalama ve filtreleme yöntemi kullanarak sorgulanır.
     * Veritabanında hata oluşması durumunda Laravel QueryException istisnası ile 500 hatası fırlatır program sonlanır.
     * Etkinlik bulunamama durumunda istisna ile hata mesajı fırlatılır program sonlanır.
     * Etkinlik verileri döner.
     * @param array $eventFilterData
     * @return LengthAwarePaginator
     */
    public function getEvents(array $eventFilterData): LengthAwarePaginator
    {
        $eventFilter = new EventFilter($eventFilterData);

        $events = Event::withTrashed()->with("venue");

        $filteredQuery = $eventFilter->applyFilters($events);

        $filteredEvents = $eventFilter->applyPagination($filteredQuery);

        if ($filteredEvents->isEmpty()) {
            throw new NotFoundException("Etkinlik bulunamadı!", 404);
        }

        return $filteredEvents;
    }

    /**
     * Etkinlik tablosunda bir etkinliği kimliği ve ilişkileri ile beraber sorgular.
     * Veritabanında hata oluşması durumunda Laravel QueryException istisnası ile 500 hatası fırlatır.
     * Etkinlik bulunamama durumunda Laravel ModelNotFound 404 hatası fırlatır.
     * Getirilen etkinlik verileri geri döner.
     * @param int $eventId
     * @return Event
     */
    public function getEventById(int $eventId): Event
    {
        $event = Event::withTrashed()->with("venue")->find($eventId);

        if (!$event) {
            throw new NotFoundException("Etkinlik bulunamadı!", 404);
        }

        return $event;
    }

    /**
     * Etkinlik verileri bu aşamaya gelmeden doğrulanmıştır.
     * Etkinlik kayıt işlemi başlar.
     * Veritabanında hata oluşması durumunda Laravel QueryException 500 hatası fırlatır, program sonlanır.
     * Eklenen etkinlik verileri geri döner.
     * @param array $eventData
     * @return Event
     */
    public function createEvent(array $eventData): Event
    {
        return Event::create($eventData);
    }

    /**
     * Etkinlik verileri bu aşamaya gelmeden doğrulanmıştır.
     * Etkinlik verileri getEventById ile getirilir.
     * Etkinlik güncelleme işlemi başlar.
     * Etkinliğin varlığı updateOrFaile ile tekrar kontrol edilir. Bulunamama durumunda Laravel ModelNotFound 404 hatası fırlatılır.
     * Veritabanında hata oluşması durumunda Laravel QueryException istisnası ile 500 hatası fırlatır.
     * Güncellenmiş etkinlik verileri geri döner.
     * @param int $eventId
     * @param array $eventData
     * @return Event
     */
    public function updateEvent(int $eventId, array $eventData): Event
    {
        $event = $this->getEventById($eventId);
        $event->updateOrFail($eventData);
        return $event;
    }

    /**
     * Etkinlik verileri getEventById ile soruglanır.
     * Etkinliğin varlığı deleteOrFail ile tekrar kontrol edilir. Bulunamama durumunda Laravel ModelNotFound 404 hatası fırlatılır.
     * Etkinlik silme işlemi başlar.
     * Veritabanında hata oluşması durumunda Laravel QueryException istisnası ile 500 hatası fırlatır.
     * Silme işlemi başarılıysa true başarısız ise false değeri döner.
     * @param int $eventId
     * @return bool
     */
    public function deleteEvent(int $eventId): bool
    {
        $event = $this->getEventById($eventId);

        if ($event->delete()) {
            return true;
        }

        return false;
    }

    /**
     * Etkinlik tablosunda bir etkinliği kimliği ile beraber sorgular.
     * Veritabanında hata oluşması durumunda Laravel QueryException istisnası ile 500 hatası fırlatır, program sonlanır.
     * Etkinlik bulunamama durumunda ModelNotFound 404 hatası fırlatılır program sonlanır.
     * Etkinliğe ait koltuklar sayfalama ve filtreleme yöntemi kullanarak kurulan ilişki ile sorgulanır.
     * Etkinliğe ait koltuk bulunamama durumunda istisna ile hata mesajı fırlatılır, program sonlanır.
     * Etkinliğe ait koltuk verileri döner.
     * @param int $eventId
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getEventSeats(array $seatFilterData, int $eventId): LengthAwarePaginator
    {
        $event = $this->getEventById($eventId);

        $eventSeatFilter = new SeatFilter($seatFilterData);

        $eventSeats = $event->seats()->get()->toQuery();

        $filteredQuery = $eventSeatFilter->applyFilters($eventSeats);

        $filteredEventSeats = $eventSeatFilter->applyPagination($filteredQuery);

        if ($filteredEventSeats->isEmpty()) {
            throw new NotFoundException("Etkinliğe ait koltuk bulunamadı!", 404);
        }

        return $filteredEventSeats;
    }
}
