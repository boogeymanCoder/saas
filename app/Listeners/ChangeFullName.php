<?php

namespace App\Listeners;

use App\Events\NameChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ChangeFullName
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\NameChanged  $event
     * @return void
     */
    public function handle(NameChanged $event)
    {
        $newFullName = $event->hasName->first_name . ' ' . $event->hasName->middle_name . ' ' . $event->hasName->last_name;
        $event->hasName->update(["full_name" => $newFullName]);
    }
}
