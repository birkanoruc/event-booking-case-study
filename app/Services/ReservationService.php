<?php

namespace App\Services;

use App\Contracts\Repositories\ReservationRepositoryInterface;
use App\Contracts\Services\ReservationInterface;
use App\Http\Resources\ReservationCollection;
use App\Http\Resources\ReservationResource;
use Illuminate\Http\JsonResponse;

class ReservationService implements ReservationInterface
{
    /**
     * Rezervasyon veritabanı işlemleri için kullanılan servis tanımlanır.
     * Bu servis, veritabanı üzerinden rezervasyonlara ilişkin işlemleri gerçekleştiren
     * ReservationRepository arayüzünü kullanır.
     * @var \App\Contracts\Repositories\ReservationRepositoryInterface
     */
    protected ReservationRepositoryInterface $reservationRepository;

    /**
     * ReservationService sınıfının yapıcı fonksiyonu.
     * ReservationRepositoryInterface türünde bir repository sınıfını enjekte eder.
     * Bu sınıf, ReservationService içinde rezervasyonlarla ilgili işlemleri gerçekleştirir.
     * @param \App\Contracts\Repositories\ReservationRepositoryInterface $reservationRepository
     */
    public function __construct(ReservationRepositoryInterface $reservationRepository)
    {
        $this->reservationRepository = $reservationRepository;
    }

    /**
     * Tüm rezervasyonları döndüren servis metodu.
     *
     * Bu metot, `ReservationRepository` aracılığıyla tüm rezervasyonları alır ve
     * sonuçları bir `ReservationCollection` nesnesine dönüştürür.
     * Başarılı sonuç json objesi olarak dönüş sağlar.
     *
     * @param array $reservationFilterData
     * @return \Illuminate\Http\JsonResponse
     */
    public function getReservations(array $reservationFilterData): JsonResponse
    {
        $reservations = $this->reservationRepository->getUserReservations($reservationFilterData);
        $reservations =  new ReservationCollection($reservations);
        return response()->json($reservations, 200);
    }

    /**
     * Kimlik numarasına göre bir rezervasyon döndüren servis metodu.
     *
     * Bu metot, belirtilen rezervasyon kimliğine (`reservationId`) sahip rezervasyonu
     * `ReservationRepository` üzerinden alır ve bir `ResarvationResource` nesnesine dönüştürür.
     * Başarılı sonuç json objesi olarak dönüş sağlar.
     *
     * @param int $reservationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getReservationById(int $reservationId): JsonResponse
    {
        $reservation = $this->reservationRepository->getUserReservationById($reservationId);
        $reservation = new ReservationResource($reservation);
        return response()->json($reservation, 200);
    }

    /**
     * Yeni bir rezervasyon oluşturan servis metodu.
     *
     * Bu metot, yeni bir rezervasyon oluşturur.
     * Rezervasyon başarılı bir şekilde oluşturulduktan sonra, oluşturulan rezervasyon
     * bir `ReservationResource` nesnesine dönüştürülüp json olarak geri döndürülür.
     *
     * @return JsonResponse
     */
    public function createReservation(): JsonResponse
    {
        $reservation = $this->reservationRepository->createReservation();
        // $reservation = new ReservationResource($reservation);
        return response()->json(["success" => true, "message" => "Rezervasyon oluşturma işlemi başarıyla tamamlandı."], 201);
    }

    /**
     * Rezervasyonu onaylayan eden servis metodu.
     *
     * Bu metot, belirtilen rezervasyon kimliğine (`reservationId`) sahip rezervasyonu onaylar.
     * Onaylanma işlemi başarıyla tamamlandığında, 200 durum kodu döndürülür.
     *
     * @param int $reservationId
     * @return JsonResponse
     */
    public function confirmReservation(int $reservationId): JsonResponse
    {
        $this->reservationRepository->confirmUserReservation($reservationId);
        return response()->json(['success' => true, 'message' => 'Rezervasyon onaylama işlemi başarıyla tamamlandı.'], 200);
    }

    /**
     * Rezervasyonu silen/iptal eden servis metodu.
     *
     * Bu metot, belirtilen rezervasyon kimliğine (`reservationId`) sahip rezervasyonu siler.
     * Silme işlemi başarıyla tamamlandığında, 200 durum kodu döndürülür.
     *
     * @param int $reservationId
     * @return JsonResponse
     */
    public function deleteReservation(int $reservationId): JsonResponse
    {
        $this->reservationRepository->cancelUserReservation($reservationId);
        return response()->json(['success' => true, 'message' => 'Rezervasyon iptal etme işlemi başarıyla tamamlandı.'], 200);
    }
}
