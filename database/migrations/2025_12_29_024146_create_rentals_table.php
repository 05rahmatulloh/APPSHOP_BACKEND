<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
      Schema::create('rentals', function (Blueprint $table) {
      $table->id();

      $table->unsignedBigInteger('product_id');

      $table->date('start_date')->nullable();
      $table->date('end_date')->nullable();

      $table->decimal('price_per_day', 12, 2);
      $table->decimal('deposit', 12, 2)->nullable();

      $table->enum('status', ['active', 'finished', 'cancelled'])->default('active');

      $table->timestamps();

      $table->foreign('product_id')
      ->references('id')
      ->on('products')
      ->onDelete('cascade');
      });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};
