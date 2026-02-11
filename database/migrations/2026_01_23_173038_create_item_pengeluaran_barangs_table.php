<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('item_pengeluaran_barangs', function (Blueprint $table) {
    $table->id();
    $table->string('nomor_pengeluaran');
    $table->string('nama_produk'); // NOT NULL
    $table->integer('qty');
    $table->integer('harga');
    $table->integer('sub_total');
    $table->timestamps();
});

    }

    public function down(): void
    {
        Schema::dropIfExists('item_pengeluaran_barangs');
    }
};