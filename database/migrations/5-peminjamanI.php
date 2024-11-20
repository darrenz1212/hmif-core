<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeminjamanInventarisTable extends Migration
{
    public function up()
    {
        Schema::create('peminjaman_inventaris', function (Blueprint $table) {
            $table->increments('id_peminjaman_inventaris');
            $table->integer('id_inventaris')->unsigned();
            $table->integer('id_peminjam')->unsigned();
            $table->string('surat_peminjaman', 255);
            $table->string('keterangan_peminjaman', 255);
            $table->string('status', 10);
            $table->foreign('id_inventaris')->references('id_inventaris')->on('inventaris');
        });
    }

    public function down()
    {
        Schema::dropIfExists('peminjaman_inventaris');
    }
}

