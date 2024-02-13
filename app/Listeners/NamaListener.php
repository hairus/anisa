<?php

namespace App\Listeners;

use App\Events\NamaEvent;
use App\Models\pesan;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NamaListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NamaEvent $event): void
    {
        //
        $data = $event->data;
        // dd($data);
        pesan::create([
            "user_id" => 1,
            "pesan" => $data,
        ]);
    }
}
