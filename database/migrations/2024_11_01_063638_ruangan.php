<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRuanganTable extends Migration
{
    public function up()
    {
        Schema::create('ruangan', function (Blueprint $table) {
            $table->increments('room_id');
            $table->string('nama_ruangan', 255);
            $table->integer('kapasitas');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ruangan');
    }
}

