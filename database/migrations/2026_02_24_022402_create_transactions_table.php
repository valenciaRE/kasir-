<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice')->unique();

            $table->decimal('subtotal', 15, 2)->default(0);

            // Diskon transaksi
            $table->enum('discount_type', ['percent','amount'])->nullable();
            $table->decimal('discount_value', 15, 2)->default(0);
            $table->decimal('discount_total', 15, 2)->default(0);

            // Pajak
            $table->decimal('tax_percent', 5, 2)->default(0);
            $table->decimal('tax_total', 15, 2)->default(0);

            $table->decimal('grand_total', 15, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};