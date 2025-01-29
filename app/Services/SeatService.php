<?php

namespace App\Services;

use App\Contracts\Repositories\SeatRepositoryInterface;
use App\Contracts\Services\SeatInterface;
use App\Http\Resources\SeatResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class SeatService implements SeatInterface
{
    protected SeatRepositoryInterface $seatRepository;
    protected $userId;

    public function __construct(SeatRepositoryInterface $seatRepository)
    {
        $this->seatRepository = $seatRepository;
        $this->userId = Auth::id();
    }

    public function blockSeat(array $seatData): JsonResponse
    {
        $seat = $this->seatRepository->blockSeat($seatData);
        $seat =  new SeatResource($seat);
        return response()->json($seat, 200);
    }

    public function releaseSeat(array $seatData): JsonResponse
    {
        $this->seatRepository->releaseSeat($seatData);
        return response()->json(['success' => true, 'message' => 'Koltuk serbest bırakma işlemi başarıyla tamamlandı.'], 200);
    }
}
