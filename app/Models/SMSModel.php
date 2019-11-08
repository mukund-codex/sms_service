<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticate;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;

class SMSModel extends Model
{
    //
    protected $primaryKey = 'id';
    protected $table = 'sms_request_log';
    protected $fillable = ['uid', 'to', 'message', 'sender_id', 'provider', 'callback', 'status'];
}
