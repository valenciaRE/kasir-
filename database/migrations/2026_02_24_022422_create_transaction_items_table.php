<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('transaction_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->foreignId('product_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->integer('qty');
            $table->decimal('price', 15, 2);

            $table->decimal('subtotal', 15, 2);

            // Diskon per item
            $table->enum('discount_type', ['percent','amount'])->nullable();
            $table->decimal('discount_value', 15, 2)->default(0);
            $table->decimal('discount_total', 15, 2)->default(0);

            $table->decimal('total', 15, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_items');
    }
};