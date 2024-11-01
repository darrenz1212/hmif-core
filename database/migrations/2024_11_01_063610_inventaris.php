<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventarisTable extends Migration
{
    public function up()
    {
        Schema::create('inventaris', function (Blueprint $table) {
            $table->increments('id_inventaris');
            $table->string('nama_barang', 255);
            $table->string('kondisi', 5);
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventaris');
    }
}
