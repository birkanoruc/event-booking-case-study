<?php

namespace App\Contracts\Services;

use Illuminate\Http\JsonResponse;

interface SeatInterface
{
    /**
     * Koltuk bloklama sürecinin yönetileceği metodu tanımlar.
     * @param array $seatData
     * @return JsonResponse
     */
    public function blockSeat(array $seatData): JsonResponse;

    /**
     * Koltuk serbest bırkma sürecinin yönetileceği metodu tanımlar.
     * @param array $seatData
     * @return JsonResponse
     */
    public function releaseSeat(array $seatData): JsonResponse;
}
