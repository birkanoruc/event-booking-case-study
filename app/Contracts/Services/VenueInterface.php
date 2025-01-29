<?php

namespace App\Contracts\Services;

use Illuminate\Http\JsonResponse;

interface VenueInterface
{
    /**
     * Mekana ait koltuk bilgilerinin listeleneceği süreci yöneten metodu tanımlar.
     * @param array $seatFilterData
     * @param int $venueId
     * @return JsonResponse
     */
    public function getVenueSeats(array $seatFilterData, int $venueId): JsonResponse;
}
