<?php

namespace App\Repositories;

use App\Contracts\Repositories\SeatRepositoryInterface;
use App\Enums\SeatStatus;
use App\Exceptions\ConflictException;
use App\Exceptions\NotFoundException;
use App\Models\Seat;
use App\Models\SeatBlock;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class SeatRepository implements SeatRepositoryInterface
{
    /**
     * Koltuğu bloklama.
     */
    public function blockSeat(array $blockSeatData): Seat
    {
        // DB transaction başlat, bir problem olma durumu için...
        return DB::transaction(function () use ($blockSeatData) {

            $seat = $this->getAndLockSeatById($blockSeatData["seat_id"]);

            $blockSeatData['expires_at'] = Carbon::now()->addMinutes(15);

            $this->createSeatBlock($blockSeatData);

            return $seat;
        });
    }

    /**
     * Koltuğu serbest bırakma işlemini gerçekleştirir.
     * @param array $releaseSeatData
     * @return bool
     */
    public function releaseSeat(array $releaseSeatData): bool
    {
        return $this->deleteSeatBlock($releaseSeatData);
    }

    /**
     * Koltuk bulma ve kilitleme işlemi
     * @param int $seatId
     * @return void
     */
    public function getAndLockSeatById(int $seatId): Seat
    {
        $seat = Seat::lockForUpdate()->find($seatId);

        // Burada dönen hatanın sebebi seatId'nin daha önce varlığının kontrol edilmesi.
        if (!$seat) {
            throw new ConflictException("Koltuk bir başka kullanıcı tarafından bloklandı.", 409);
        }

        return $seat;
    }

    /**
     * SeatBlock verisini bulur ve döner, bulamazsa hata fırlatır.
     * @param array $seatBlockData
     * @throws \App\Exceptions\NotFoundException
     * @return SeatBlock
     */
    public function getSeatBlock(array $seatBlockData): SeatBlock
    {
        $seatBlock = SeatBlock::where('seat_id', $seatBlockData['seat_id'])
            ->where('event_id', $seatBlockData['event_id'])
            ->where('user_id', $seatBlockData['user_id'])
            ->first();

        if (!$seatBlock) {
            throw new NotFoundException("Size ait serbest bırakılmasını istediğiniz koltuk bulunamadı.", 404);
        }

        return $seatBlock;
    }

    /**
     * SeatBlock verisi oluşturur.
     * @param array $seatBlockData
     * @return SeatBlock
     */
    public function createSeatBlock(array $seatBlockData): SeatBlock
    {
        return SeatBlock::create($seatBlockData);
    }

    /**
     * SeatBlock verisi siler.
     * @param array $seatBlockData
     * @return bool|null
     */
    public function deleteSeatBlock(array $seatBlockData): bool
    {
        $seatBlock = $this->getSeatBlock($seatBlockData);
        return $seatBlock->delete();
    }
}
