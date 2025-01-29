<?php

namespace App\Repositories;

use App\Contracts\Repositories\ReservationRepositoryInterface;
use App\Enums\EventStatus;
use App\Enums\ReservationStatus;
use App\Exceptions\ConflictException;
use App\Exceptions\NotFoundException;
use App\Filter\ReservationFilter;
use App\Models\Reservation;
use App\Models\ReservationItem;
use App\Models\Seat;
use App\Models\SeatBlock;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReservationRepository implements ReservationRepositoryInterface
{

    /**
     * Rezervasyon işlemleri kullanıcılara özeldir.
     * Servis genelinde kullanılmak üzere kullanıcı kimliği tanımlanır.
     * @var int $this->userId
     */
    protected int $userId;

    /**
     * ReservationRepository sınfının yapıcı fonksiyonu.
     */
    public function __construct()
    {
        $this->userId = Auth::user()->id;
    }

    /**
     * Rezervasyon tablosunda kullanıcının tüm rezervasyonları ilişkileri ile beraber sayfalama ve filtreleme yöntemi kullanarak sorgulanır.
     * Veritabanında hata oluşması durumunda Laravel QueryException istisnası ile 500 hatası fırlatır program sonlanır.
     * Rezervasyon bulunamama durumunda istisna ile hata mesajı fırlatılır program sonlanır.
     * Rezervasyon verileri döner.
     *
     * @param array $reservationFilterData
     * @return LengthAwarePaginator
     */
    public function getUserReservations(array $reservationFilterData): LengthAwarePaginator
    {

        $reservationFilter = new ReservationFilter($reservationFilterData);

        $reservations = Reservation::withTrashed()->where("user_id", $this->userId)->with(["event", "reservationItems", "reservationItems.seat"]);

        $filteredQuery = $reservationFilter->applyFilters($reservations);

        $filteredReservations = $reservationFilter->applyPagination($filteredQuery);

        if ($filteredReservations->isEmpty()) {
            throw new NotFoundException("Rezervasyon bulunamadı!", 404);
        }

        return $filteredReservations;
    }

    /**
     * Rezervasyon tablosunda bir rezervasyonu kimliği ve ilişkileri ile beraber sorgular.
     * Veritabanında hata oluşması durumunda Laravel QueryException istisnası ile 500 hatası fırlatır.
     * Rezervasyon bulunamama durumunda Laravel ModelNotFound 404 hatası fırlatır.
     * Getirilen rezervasyon verileri geri döner.
     *
     * @param int $reservationId
     * @return Reservation
     */
    public function getUserReservationById(int $reservationId): Reservation
    {
        $reservation = Reservation::withTrashed()->where("user_id", $this->userId)->with(["reservationItems"])->find($reservationId);

        if (!$reservation) {
            throw new NotFoundException("Rezervasyon bulunamadı!", 404);
        }

        return $reservation;
    }

    public function getLockForUpdateUserReservationById(int $reservationId): Reservation
    {
        $reservation = Reservation::lockForUpdate()->withTrashed()->where("user_id", $this->userId)->with(["reservationItems"])->find($reservationId);

        if (!$reservation) {
            throw new NotFoundException("Rezervasyon bulunamadı!", 404);
        }

        return $reservation;
    }

    /**
     * Kullanıcının blokladığı koltuk verilierini alır.
     * @throws NotFoundException
     * @return Collection
     */
    public function getSeatBlocks(): Collection
    {
        // SeatBlock, user_id ve expires_at kontrolü ile seat blocks verilerini alıyoruz
        $seatBlocks = SeatBlock::where('user_id', $this->userId)
            ->where('expires_at', '>', Carbon::now())
            ->get();

        // Eğer seat blocks bulunmazsa hata fırlatılır
        if ($seatBlocks->isEmpty()) {
            throw new NotFoundException('Rezervasyon oluşturmadan önce koltuk seçimi yapmalısınız.', 400);
        }

        // SeatBlocks verileri event bazlı gruplandırıp geri döner
        return $seatBlocks->groupBy('event_id');
    }


    /**
     * Yeni rezervasyon oluşturur.
     *
     * @return Reservation
     */
    public function createReservation()
    {
        $groupedSeatBlocks = $this->getSeatBlocks();

        return DB::transaction(function () use ($groupedSeatBlocks) {
            return $this->createReservationsForEvents($groupedSeatBlocks);
        });
    }

    /**
     * Etkinlik bazında rezervasyonları oluşturur.
     *
     * @param \Illuminate\Support\Collection $groupedSeatBlocks
     * @return array
     */
    private function createReservationsForEvents($groupedSeatBlocks): array
    {
        $reservations = [];

        foreach ($groupedSeatBlocks as $eventId => $seatBlocks) {
            $reservation = $this->createReservationForEvent($eventId, $seatBlocks);
            $this->deleteSeatBlocks($seatBlocks);
            $reservations[] = $reservation;
        }

        return $reservations;
    }

    /**
     * Etkinlik için bir rezervasyon oluşturur.
     *
     * @param int $eventId
     * @param \Illuminate\Support\Collection $seatBlocks
     * @return Reservation
     */
    private function createReservationForEvent(int $eventId, $seatBlocks): Reservation
    {
        $totalAmount = 0;
        $reservationItems = [];

        foreach ($seatBlocks as $seatBlock) {
            $seat = Seat::select("price")->find($seatBlock->seat_id);

            if (!$seat) {
                throw new NotFoundException("Koltuk bulunamadı.", 404);
            }

            $totalAmount += $seat->price;

            $reservationItems[] = [
                'seat_id' => $seatBlock->seat_id,
                'price' => $seat->price,
            ];
        }

        $reservation = Reservation::create([
            'expires_at' => Carbon::now()->addMinutes(15),
            'user_id' => $this->userId,
            'event_id' => $eventId,
            'total_amount' => $totalAmount,
        ]);

        foreach ($reservationItems as &$item) {
            $item['reservation_id'] = $reservation->id;
        }

        ReservationItem::insert($reservationItems);

        return $reservation;
    }

    /**
     * Koltuk bloklarını siler.
     *
     */
    private function deleteSeatBlocks($seatBlocks): void
    {
        foreach ($seatBlocks as $seatBlock) {
            $seatBlock->delete();
        }
    }


    /**
     * Rezervasyon verileri getUserReservationById ile soruglanır.
     * Rezervasyon onaylama işlemi başlar.
     * Veritabanında hata oluşması durumunda Laravel QueryException istisnası ile 500 hatası fırlatır.
     * Onaylama işlemi başarılıysa rezervasyon verileri döner.
     *
     * @param array $reservationData
     * @return Reservation
     */
    public function confirmUserReservation(int $reservationId)
    {
        // Rezervasyonu al
        $reservation = $this->getUserReservationById($reservationId);

        // Eğer rezervasyon PENDING (bekliyor) durumunda değilse, hata fırlat
        if ($reservation->status !== ReservationStatus::PENDING) {
            throw new ConflictException("Rezervasyon geçerlilik durumunda değil!", 409);
        }

        // Ticket oluşturma işlemi
        $tickets = $this->createTicketFromReservation($reservation);

        // Ticket oluşturulamazsa hata fırlat
        if (!$tickets || count($tickets) === 0) {
            throw new \Exception("Bilet oluşturulurken bir hata oluştu.", 500);
        }


        // Ticket'ları döndür
        return $tickets;
    }

    public function createTicketFromReservation($reservation)
    {
        return DB::transaction(function () use ($reservation) {

            $tickets = [];
            foreach ($reservation->reservationItems as $reservationItem) {

                $ticket = Ticket::create([
                    "ticket_code" => strtoupper('TICKET-' . Str::uuid()),
                    'reservation_id' => $reservation->id,
                    'seat_id' => $reservationItem->seat_id,
                ]);

                // 7. Biletleri diziye ekle
                $tickets[] = $ticket;
            }

            // 8. Biletleri geri döndür
            return $tickets;
        });
    }

    /**
     * Rezervasyon verileri getUserReservationById ile soruglanır.
     * Rezervasyon silme işlemi başlar.
     * Veritabanında hata oluşması durumunda Laravel QueryException istisnası ile 500 hatası fırlatır.
     * Silme/iptal işlemi başarılıysa true başarısız ise false değeri döner.
     *
     * @param array $reservationData
     * @return Reservation
     */
    public function cancelUserReservation(int $reservationId): bool
    {
        $reservation = $this->getUserReservationById($reservationId)->with("event");


        if ($reservation->status !== ReservationStatus::PENDING) {
            throw new ConflictException("Rezervasyon geçerlilik durumunda değil!", 409);
        }

        if ($reservation->delete()) {
            return true;
        }
        return false;
    }
}
