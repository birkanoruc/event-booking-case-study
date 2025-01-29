<?php

namespace App\Http\Controllers;

use App\Contracts\Services\EventInterface;
use App\Http\Requests\Event\EventDeleteRequest;
use App\Http\Requests\Event\EventFilterRequest;
use App\Http\Requests\Event\EventStoreOrUpdateRequest;
use App\Http\Requests\Seat\SeatFilterRequest;
use Illuminate\Http\JsonResponse;

class EventController extends Controller
{
    /**
     * Etkinliklerle ilgili iş mantığını gerçekleştiren servis sınıfı.
     * @var EventInterface
     */
    private EventInterface $eventService;

    /**
     * Etkinlik servisi dependency injection yoluyla sağlanır.
     * @param \App\Contracts\Services\EventInterface $eventService
     */
    public function __construct(EventInterface $eventService)
    {
        $this->eventService = $eventService;
    }

    /**
     * Servis ile etkinlikleri listelemek için bağlantı sağlanır.
     * @param \App\Http\Requests\Event\EventFilterRequest $request
     * @return JsonResponse
     */
    public function index(EventFilterRequest $request): JsonResponse
    {
        return $this->eventService->getEvents($request->validated());
    }

    /**
     * Servis ile belirli bir etkinliği getirmek içik bağlantı sağlanır.
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        return $this->eventService->getEventById($id);
    }

    /**
     * Servis ile yeni bir etkinlik oluşturmak için bağlantı sağlanır.
     * @param \App\Http\Requests\Event\EventStoreOrUpdateRequest $request
     * @return JsonResponse
     */
    public function store(EventStoreOrUpdateRequest $request): JsonResponse
    {
        return $this->eventService->createEvent($request->validated());
    }

    /**
     * Servis ile bir etkinliği güncellemek için bağlantı sağlanır.
     * @param \App\Http\Requests\Event\EventStoreOrUpdateRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(EventStoreOrUpdateRequest $request, int $id): JsonResponse
    {
        return $this->eventService->updateEvent($id, $request->validated());
    }

    /**
     * Servis ile bir etkinliği silmek/iptal etmek için bağlantı sağlanır.
     * @param \App\Http\Requests\Event\EventDeleteRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(EventDeleteRequest $request, int $id): JsonResponse
    {
        return $this->eventService->deleteEvent($id);
    }

    /**
     * Servis ile etkinliğe ait koltukları listelemek için bağlantı sağlanır.
     * @param \App\Http\Requests\Seat\SeatFilterRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function seats(SeatFilterRequest $request, int $id): JsonResponse
    {
        return $this->eventService->getEventSeats($request->validated(), $id);
    }
}
