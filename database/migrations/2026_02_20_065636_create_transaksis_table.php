<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique();
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('diskon_total', 15, 2)->default(0);
            $table->enum('tipe_diskon_total', ['persen', 'rupiah'])->default('rupiah');
            $table->decimal('pajak_persen', 5, 2)->default(0);
            $table->decimal('pajak_nominal', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->decimal('bayar', 15, 2)->default(0);
            $table->decimal('kembalian', 15, 2)->default(0);
            $table->enum('metode_bayar', ['cash', 'qris'])->default('cash');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};