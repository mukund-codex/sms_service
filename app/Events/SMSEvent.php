<?php

namespace App\Events;

use App\Models\SMSModel;

class SMSEvent extends Event
{   

    public $sms_queue_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($sms_queue_id)
    {
        //
        $this->sms_queue_id = $sms_queue_id;
    }
}
