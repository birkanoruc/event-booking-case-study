<?php

namespace App\Repositories;

use App\Contracts\Repositories\VenueRepositoryInterface;
use App\Exceptions\NotFoundException;
use App\Filter\SeatFilter;
use App\Models\Venue;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class VenueRepository implements VenueRepositoryInterface
{
    /**
     * Mekan tablosunda bir mekanı kimliği ile sorgular.
     * Veritabanında hata oluşması durumunda Laravel QueryException istisnası ile 500 hatası fırlatır, program sonlanır.
     * Mekan bulunamama durumunda ModelNotFound 404 hatası fırlatılır, program sonlanır.
     * Getirilen mekan verileri geri döner.
     * @param int $venueId
     * @return Venue
     */
    public function getVenueById(int $venueId): Venue
    {
        $venue = Venue::find($venueId);

        if (!$venue) {
            throw new NotFoundException("Mekan bulunamadı!", 404);
        }

        return $venue;
    }

    /**
     * Mekan tablosunda bir mekanı kimliği ile beraber sorgular.
     * Veritabanında hata oluşması durumunda Laravel QueryException istisnası ile 500 hatası fırlatır, program sonlanır.
     * Mekan bulunamama durumunda ModelNotFound 404 hatası fırlatılır, program sonlanır.
     * Mekana ait koltuklar sayfalama ve filtreleme yöntemi kullanarak kurulan ilişki ile sorgulanır.
     * Mekana ait koltuk bulunamama durumunda istisna ile hata mesajı fırlatılır, program sonlanır.
     * Mekana ait koltuk bulunması durumunda bulunan verileri döner.
     * @param array $seatFilterData
     * @param int $venueId
     * @return LengthAwarePaginator
     */
    public function getVenueSeats(array $seatFilterData, int $venueId): LengthAwarePaginator
    {
        $venue = $this->getVenueById($venueId);

        $venueSeatFilter = new SeatFilter($seatFilterData);

        $venueSeats = $venue->seats()->get()->toQuery();

        $filteredQuery = $venueSeatFilter->applyFilters($venueSeats);

        $filteredVenueSeats = $venueSeatFilter->applyPagination($filteredQuery);

        if ($filteredVenueSeats->isEmpty()) {
            throw new NotFoundException("Mekana ait koltuk bulunamadı!", 404);
        }

        return $filteredVenueSeats;
    }
}
