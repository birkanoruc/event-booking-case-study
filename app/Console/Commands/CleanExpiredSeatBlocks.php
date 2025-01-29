<?php

namespace App\Console\Commands;

use App\Models\SeatBlock;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CleanExpiredSeatBlocks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seatblocks:clean-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Düzenli olarak seat_blocks tablosu süresi geçmiş bloklama verilerinden arındırılır.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $deletedCount = SeatBlock::where('expires_at', '<', Carbon::now())->delete();

        $this->info("{$deletedCount} süresi geçmiş bloklama verileri temizlendi.");
    }
}
