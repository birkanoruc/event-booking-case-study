<?php

namespace App\Services;

use App\Contracts\Services\EventInterface;
use App\Contracts\Repositories\EventRepositoryInterface;
use App\Http\Resources\EventCollection;
use App\Http\Resources\EventResource;
use App\Http\Resources\EventSeatCollection;
use Illuminate\Http\JsonResponse;

class EventService implements EventInterface
{
    /**
     * Etkinlik veritabanı işlemleri için kullanılan servis tanımlanır.
     * Bu servis, veritabanı üzerinden etkinliklere ilişkin işlemleri gerçekleştiren
     * EventRepository arayüzünü kullanır.
     * @var \App\Contracts\Repositories\EventRepositoryInterface
     */
    protected EventRepositoryInterface $eventRepository;

    /**
     * EventService sınıfının yapıcı fonksiyonu.
     * EventRepositoryInterface türünde bir repository sınıfını enjekte eder.
     * Bu sınıf, EventService içinde etkinliklerle ilgili işlemleri gerçekleştirir.
     * @param \App\Contracts\Repositories\EventRepositoryInterface $eventRepository
     */
    public function __construct(EventRepositoryInterface $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    /**
     * Tüm etkinlikleri döndüren servis metodu.
     *
     * Bu metot, `EventRepository` aracılığıyla tüm etkinlikleri alır ve
     * sonuçları bir `EventCollection` nesnesine dönüştürür.
     * Başarılı sonuç json objesi olarak dönüş sağlar.
     *
     * @param array $eventFilterData
     * @return JsonResponse
     */
    public function getEvents(array $eventFilterData): JsonResponse
    {
        $events = $this->eventRepository->getEvents($eventFilterData);
        $events =  new EventCollection($events);
        return response()->json($events, 200);
    }

    /**
     * Kimlik numarasına göre bir etkinlik döndüren servis metodu.
     *
     * Bu metot, belirtilen etkinlik kimliğine (`eventId`) sahip etkinliği
     * `EventRepository` üzerinden alır ve bir `EventResource` nesnesine dönüştürür.
     * Başarılı sonuç json objesi olarak dönüş sağlar.
     *
     * @param int $eventId
     * @return JsonResponse
     */
    public function getEventById(int $eventId): JsonResponse
    {
        $event = $this->eventRepository->getEventById($eventId);
        $event = new EventResource($event);
        return response()->json($event, 200);
    }

    /**
     * Yeni bir etkinlik oluşturan servis metodu.
     *
     * Bu metot, verilen etkinlik verisiyle yeni bir etkinlik oluşturur.
     * Etkinlik başarılı bir şekilde oluşturulduktan sonra, oluşturulan etkinlik
     * bir `EventResource` nesnesine dönüştürülüp json olarak geri döndürülür.
     *
     * @param array $eventData
     * @return JsonResponse
     */
    public function createEvent(array $eventData): JsonResponse
    {
        $event = $this->eventRepository->createEvent($eventData);
        $event = new EventResource($event);
        return response()->json($event, 201);
    }

    /**
     * Var olan bir etkinliği güncelleyen servis metodu.
     *
     * Bu metot, belirtilen etkinlik kimliğine (`eventId`) sahip etkinliği
     * günceller. Güncelleme işleminden sonra güncellenmiş etkinlik json formatında
     * geri döndürülür.
     *
     * @param int $eventId
     * @param array $eventData
     * @return JsonResponse
     */
    public function updateEvent(int $eventId, array $eventData): JsonResponse
    {
        $event = $this->eventRepository->updateEvent($eventId, $eventData);
        $event = new EventResource($event);
        return response()->json($event, 200);
    }

    /**
     * Etkinliği silen servis metodu.
     *
     * Bu metot, belirtilen etkinlik kimliğine (`eventId`) sahip etkinliği siler.
     * Silme işlemi başarıyla tamamlandığında, 200 durum kodu döndürülür.
     *
     * @param int $eventId
     * @return JsonResponse
     */
    public function deleteEvent(int $eventId): JsonResponse
    {
        $this->eventRepository->deleteEvent($eventId);
        return response()->json(['success' => true, 'message' => 'Etkinlik silme işlemi başarıyla tamamlandı.'], 200);
    }

    /**
     * Kimlik numarasına göre bir etkinliğin koltuklarını döndüren servis metodu.
     *
     * Bu metot, belirtilen etkinlik kimliğine (`eventId`) sahip etkinliğin koltuklarını
     * `EventRepository` üzerinden alır ve bir `SeatCollection` nesnesine dönüştürür.
     * Başarılı sonuç json objesi olarak dönüş sağlar.
     *
     * @return JsonResponse
     */
    public function getEventSeats(array $seatFilterData, int $eventId): JsonResponse
    {
        $eventSeats = $this->eventRepository->getEventSeats($seatFilterData, $eventId);
        $seatCollection = new EventSeatCollection($eventSeats);
        return response()->json($seatCollection, 200);
    }
}
