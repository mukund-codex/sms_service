<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SmsRequestLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema:;create('sms_request_log', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->string('uid');
            $table->string('request_id');
            $table->string('to');
            $table->string('message');
            $table->string('sender_id');
            $table->string('provider');
            $table->string('callback');
            $table->string('status');
            $table->string('error');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('sms_request_log');
    }
}
