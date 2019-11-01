<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticate;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;

class SMSLogModel extends Model
{
    //
    protected $primarykey = 'id';
    protected $table = 'sms_log';
    protected $fillable = ['uid', 'request_id', 'to', 'message', 'sender_id', 'provider', 'callback', 'is_success', 'output'];
}
