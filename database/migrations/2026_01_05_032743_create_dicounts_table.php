<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();

            $table->string('code')->unique();

            // 🔑 scope diskon
            $table->enum('scope', ['product', 'order']);

            // tipe diskon
            $table->enum('type', [
                'percentage',
                'nominal',
                'free_shipping'
            ]);

            // nilai diskon (persen / nominal)
            $table->decimal('value', 12, 2)->nullable();

            $table->integer('stock')->default(0);

            $table->boolean('is_active')->default(true);

            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discounts'); // ✅ BENAR
    }
};
