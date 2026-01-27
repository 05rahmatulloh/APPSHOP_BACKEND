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
Schema::create('products', function (Blueprint $table) {
$table->id();
$table->unsignedBigInteger('category_id');
$table->string('name');
$table->text('description')->nullable();
$table->decimal('price', 12, 2)->nullable();
$table->integer('stock')->nullable();
$table->string('image')->nullable();

// KHUSUS SEWA
// $table->dateTime('rent_start')->nullable();
// $table->dateTime('rent_end')->nullable();
// $table->boolean('is_rented')->default(false);

//COD CEK
$table->boolean('is_cod_available')->default(true);



// STATUS AKTIF / NONAKTIF
$table->boolean('is_active')->default(true);

$table->timestamps();

$table->foreign('category_id')
->references('id')
->on('categories')
->onDelete('cascade');
});
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
