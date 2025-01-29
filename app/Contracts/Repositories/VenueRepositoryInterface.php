<?php

namespace App\Contracts\Repositories;

use App\Models\Venue;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface VenueRepositoryInterface
 *
 * Mekan yönetimi işlemlerinin yapılacağı repo sınıfının uygulaması için gereklidir.
 * Bu interface, mekanlarla ilgili veri erişim işlemlerini tanımlar.
 */
interface VenueRepositoryInterface
{
    /**
     * Belirli bir mekanın koltuk detaylarını listeleyecek metodu tanımlar.
     * @param array $seatFilterData
     * @param int $venueId
     * @return LengthAwarePaginator
     */
    public function getVenueSeats(array $seatFilterData, int $venueId): LengthAwarePaginator;

    /**
     * Belirli bir mekanı getirecek metodu tanımlar.
     * @param int $venueId
     * @return Venue
     */
    public function getVenueById(int $venueId): Venue;
}
