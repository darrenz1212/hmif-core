<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('jadwal_ruangan', function (Blueprint $table) {
            $table->id();
            $table->integer('room_id')->unsigned();
            $table->foreign('room_id')->references('room_id')->on('ruangan');
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jadwal_ruangan');
    }
};
