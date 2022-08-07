<?php

namespace App\Console\Commands;

use App\Models\Past;
use Carbon\Carbon;
use Illuminate\Console\Command;

class clearPasts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pasts:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'remove pasts that have been kept more then they should be';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Past::where('created_at', '<', now()->subHours(1)->startOfHour())->delete();
        return 0;
    }
}
