<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFasilitasTable extends Migration
{
    public function up()
    {
        Schema::create('fasilitas', function (Blueprint $table) {
            $table->increments('id_fasilitas');
            $table->string('nama_barang', 255);
            $table->string('kondisi_barang', 10);
            $table->integer('id_ruangan')->unsigned();
            $table->foreign('id_ruangan')->references('id')->on('ruangan');
        });
    }

    public function down()
    {
        Schema::dropIfExists(table: 'fasilitas');
    }
}
