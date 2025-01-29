<?php

use Illuminate\Support\Facades\Schedule;

/**
 * Seat block verilerini saatlik temizleyecek cron job.
 */
Schedule::command("seatblocks:clean-expired")->hourly();
