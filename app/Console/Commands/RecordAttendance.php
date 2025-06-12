<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\RecordDailyAttendanceJob;

class RecordAttendance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'record:attendance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        dispatch(new RecordDailyAttendanceJob());
        $this->info("Daily attendance job dispatched successfully.");
    }
}
