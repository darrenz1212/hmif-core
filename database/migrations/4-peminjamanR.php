<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeminjamanRuanganTable extends Migration
{
    public function up()
    {
        Schema::create('peminjaman_ruangan', function (Blueprint $table) {
            $table->increments('id_peminjaman_ruangan');
            $table->integer('id_ruangan')->unsigned();
            $table->integer('id_peminjam')->unsigned();
            $table->string('surat_peminjaman', 255);
            $table->string('keterangan_peminjaman', 255);
            $table->date('tanggal_peminjaman');
            $table->time('waktu_peminjaman');
            $table->string('status', 30);
            $table->foreign('id_ruangan')->references('room_id')->on('ruangan');
        });
    }

    public function down()
    {
        Schema::dropIfExists('peminjaman_ruangan');
    }
}

