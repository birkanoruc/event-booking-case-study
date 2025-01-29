<?php

namespace App\Http\Controllers;

use App\Contracts\Services\ReservationInterface;
use App\Http\Requests\Reservation\ReservationFilterRequest;
use Illuminate\Http\JsonResponse;

class ReservationController extends Controller
{
    /**
     * Rezervasyonlarla ilgili iş mantığını gerçekleştiren servis sınıfı.
     * @var ReservationInterface
     */
    private ReservationInterface $reservationService;

    /**
     * Rezervasyon servisi dependency injection yoluyla sağlanır.
     * @param \App\Contracts\Services\ReservationInterface $reservationService
     */
    public function __construct(ReservationInterface $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    /**
     * Servis ile rezervasyonları listelemek için bağlantı sağlanır.
     * @param \App\Http\Requests\Reservation\ReservationFilterRequest $request
     * @return JsonResponse
     */
    public function index(ReservationFilterRequest $request): JsonResponse
    {
        return $this->reservationService->getReservations($request->validated());
    }

    /**
     * Servis ile belirli bir rezervasyonu getirmek içik bağlantı sağlanır.
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        return $this->reservationService->getReservationById($id);
    }

    /**
     * Servis ile yeni bir rezervasyon oluşturmak için bağlantı sağlanır.
     * @return JsonResponse
     */
    public function store(): JsonResponse
    {
        return $this->reservationService->createReservation();
    }

    /**
     * Servis ile bir rezervasyonu onaylamak için bağlantı sağlanır.
     * @param int $id
     * @return JsonResponse
     */
    public function confirm(int $id): JsonResponse
    {
        return $this->reservationService->confirmReservation($id);
    }

    /**
     * Servis ile bir rezervasyonu silmek/iptal etmek için bağlantı sağlanır.
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        return $this->reservationService->deleteReservation($id);
    }
}
