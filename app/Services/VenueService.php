<?php

namespace App\Services;

use App\Contracts\Repositories\VenueRepositoryInterface;
use App\Contracts\Services\VenueInterface;
use App\Http\Resources\VenueSeatCollection;
use Illuminate\Http\JsonResponse;

class VenueService implements VenueInterface
{
    /**
     * Koltuk veritabanı işlemleri için kullanılan servis tanımlanır.
     * Bu servis, veritabanı üzerinden koltuklara ilişkin işlemleri gerçekleştiren
     * VenueRepository arayüzünü kullanır.
     * @var VenueRepositoryInterface
     */
    protected VenueRepositoryInterface $venueRepository;

    /**
     * VenueService sınıfının yapıcı fonksiyonu.
     * VenueRepositoryInterface türünde bir repository sınıfını enjekte eder.
     * Bu sınıf, VenueService içinde etkinliklerle ilgili işlemleri gerçekleştirir.
     * @param \App\Contracts\Repositories\VenueRepositoryInterface $venueRepository
     */
    public function __construct(VenueRepositoryInterface $venueRepository)
    {
        $this->venueRepository = $venueRepository;
    }

    /**
     * Kimlik numarasına göre bir mekanın koltuklarını döndüren servis metodu.
     *
     * Bu metot, belirtilen mekan kimliğine (`venueId`) sahip mekanın koltuklarını
     * `VenueRepository` üzerinden alır ve bir `VenueSeatCollection` nesnesine dönüştürür.
     * Başarılı sonuç json objesi olarak dönüş sağlar.
     *
     * @param array $seatFilterData
     * @param int $venueId
     * @return JsonResponse
     */
    public function getVenueSeats(array $seatFilterData, int $venueId): JsonResponse
    {
        $venueSeats = $this->venueRepository->getVenueSeats($seatFilterData, $venueId);
        $venueSeats =  new VenueSeatCollection($venueSeats);
        return response()->json($venueSeats, 200);
    }
}
