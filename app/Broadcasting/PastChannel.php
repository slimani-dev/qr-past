<?php

namespace App\Broadcasting;

use App\Models\Past;
use Log;

class PastChannel
{
    /**
     * Create a new channel instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     *
     * @param Past $past
     * @return array|bool
     */
    public function join(Past $past): bool|array
    {
        return true;
    }
}
