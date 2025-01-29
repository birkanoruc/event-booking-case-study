<?php

namespace App\Contracts\Repositories;

use App\Models\Seat;
use App\Models\SeatBlock;

/**
 * Interface SeatRepositoryInterface
 *
 * Koltuk yönetimi işlemlerinin yapılacağı repo sınıfının uygulaması için gereklidir.
 * Bu interface, koltuklarla ilgili veri erişim işlemlerini tanımlar.
 */
interface SeatRepositoryInterface
{
    /**
     * Koltuğu bloklayan metod tanımlanır.
     * @param array $blockSeatData
     * @return Seat
     */
    public function blockSeat(array $blockSeatData): Seat;

    /**
     * Koltuğu serbest bırakan metod tanımlanır.
     * @param array $releaseSeatData
     * @return bool
     */
    public function releaseSeat(array $releaseSeatData): bool;

    /**
     * Bloklama verisi oluşturan metod tanımlanır.
     * @param array $blockSeatData
     * @return SeatBlock
     */
    public function createSeatBlock(array $blockSeatData): SeatBlock;

    /**
     * Koltuğu bulur, kilitler ve döner.
     * @param int $seatId
     * @return Seat
     */
    public function getAndLockSeatById(int $seatId): Seat;

    /**
     * Bloklanan verilerde arama yapar, veriyi döner.
     * @param array $seatBlockData
     * @return SeatBlock
     */
    public function getSeatBlock(array $seatBlockData): SeatBlock;

    /**
     * bloklanan verilerde arama yapar, veriyi siler.
     * @param array $seatBlockData
     * @return bool
     */
    public function deleteSeatBlock(array $seatBlockData): bool;
}
