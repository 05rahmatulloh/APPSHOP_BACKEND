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
      Schema::create('discounts', function (Blueprint $table) {
      $table->id();
      $table->string('code')->unique();

      $table->enum('type', ['percentage', 'nominal', 'free_shipping']);

      // persen (1–100) atau nominal
      $table->decimal('value', 12, 2)->nullable();

      $table->integer('stock')->default(0);

      $table->boolean('is_active')->default(true);

      $table->dateTime('start_date')->nullable();
      $table->dateTime('end_date')->nullable();

      $table->timestamps();
      });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dicounts');
    }
};
