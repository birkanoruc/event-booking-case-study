<?php

namespace App\Http\Controllers;

use App\Contracts\Services\VenueInterface;
use App\Http\Requests\Seat\SeatFilterRequest;
use Illuminate\Http\JsonResponse;

class VenueController extends Controller
{
    /**
     * Mekanlarla ilgili iş mantığını gerçekleştiren servis sınıfı.
     * @var VenueInterface
     */
    private VenueInterface $venueService;

    /**
     * Mekan servisi dependency injection yoluyla sağlanır.
     * @param \App\Contracts\Services\VenueInterface $venueService
     */
    public function __construct(VenueInterface $venueService)
    {
        $this->venueService = $venueService;
    }

    /**
     * Servis ile mekana ait koltukları listelemek için bağlantı sağlanır.
     * @param \App\Http\Requests\Seat\SeatFilterRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function seats(SeatFilterRequest $request, int $id): JsonResponse
    {
        return $this->venueService->getVenueSeats($request->validated(), $id);
    }
}
