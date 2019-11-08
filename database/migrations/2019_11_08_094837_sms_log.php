<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SmsLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('sms_log', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->string('uid');
            $table->string('request_id');
            $table->string('to');
            $table->string('message');
            $table->string('sender_id');
            $table->string('provider');
            $table->string('callback');
            $table->string('is_success');
            $table->string('output');
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
        Schema::dropIfExists('sms_log');
    }
}
