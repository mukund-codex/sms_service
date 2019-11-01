<?php

namespace App\Listeners;

use App\Events\SMSEvent;
use App\Jobs\SMSSender; 
use Illuminate\Support\Facades\Log;

class SMSListener
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
     * @param  SMSEvent  $event
     * @return void
     */
    public function handle(SMSEvent $event)
    {
        //
        
        $sms_queue_id = $event->sms_queue_id;
        if(!empty($sms_queue_id)){
            \dispatch(new SMSSender($sms_queue_id));
            Log::info('Listener - sms_queue_id: '.$sms_queue_id);
        }
    }
}
