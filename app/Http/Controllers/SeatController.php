<?php

namespace App\Http\Controllers;

use App\Contracts\Services\SeatInterface;
use App\Http\Requests\Seat\SeatReleaseRequest;
use App\Http\Requests\Seat\SeatBlockRequest;
use Illuminate\Http\JsonResponse;

class SeatController extends Controller
{
    /**
     * Koltuklarla ilgili iş mantığını gerçekleştiren servis sınıfı.
     * @var SeatInterface
     */
    private SeatInterface $seatService;

    /**
     * Koltuk servisi dependency injection yoluyla sağlanır.
     * @param \App\Contracts\Services\SeatInterface $seatService
     */
    public function __construct(SeatInterface $seatService)
    {
        $this->seatService = $seatService;
    }

    /**
     * Servis ile koltuğu bloklamak için bağlantı sağlanır.
     * @param \App\Http\Requests\Seat\SeatBlockRequest $request
     * @return JsonResponse
     */
    public function block(SeatBlockRequest $request): JsonResponse
    {
        return $this->seatService->blockSeat($request->only("user_id", "event_id", "seat_id"));
    }

    /**
     * Servis ile koltuğu serbest için bağlantı sağlanır.
     * @param \App\Http\Requests\Seat\SeatReleaseRequest $request
     * @return JsonResponse
     */
    public function release(SeatReleaseRequest $request): JsonResponse
    {
        return $this->seatService->releaseSeat($request->only("user_id", "event_id", "seat_id"));
    }
}
