<?php

namespace App\Console\Commands;

use App\Events\PastDeleted;
use App\Models\Past;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

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
    public function handle(): int
    {
        $pasts = Past::where('created_at', '<', now()->subHours(1)->startOfHour())->get();

        foreach ($pasts as $past) {
            $mediaFiles = $past->getMedia('files');
            foreach ($mediaFiles as /* @var $mediaFile Media */ $mediaFile) {
                $past->delete($mediaFile->id);
            }

            event(new PastDeleted($past));
            $past->delete();
        }

        return 0;
    }
}
