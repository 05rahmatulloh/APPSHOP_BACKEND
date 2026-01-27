<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // 🔑 Order identifier (WAJIB untuk Midtrans)
            $table->string('order_code')->unique();

            // 🔢 Pricing snapshot
            $table->decimal('subtotal', 12, 2);
            $table->decimal('discount_total', 12, 2)->default(0);
            $table->decimal('shipping_cost', 12, 2)->default(0);
            $table->decimal('total', 12, 2);

            // 📦 Status bisnis order
            $table->enum('status', [
                'pending',      // baru checkout
                'processing',   // dibayar / COD confirmed
                'shipped',
                'completed',
                'cancelled'
            ])->default('pending');

            // 💳 Status pembayaran (PENTING)
            $table->enum('payment_status', [
                'pending',
                'paid',
                'failed',
                'expired'
            ])->default('pending');

            // 💳 Metode pembayaran
            $table->enum('payment_method', [
                'midtrans',
                'cod'
            ])->nullable();

            // 🧾 MIDTRANS METADATA (opsional tapi profesional)
            $table->string('midtrans_transaction_id')->nullable();
            $table->string('midtrans_payment_type')->nullable();
            $table->json('midtrans_response')->nullable();

            // 🚚 Shipping
            $table->text('shipping_address');

            // ⏱ Lifecycle timestamps
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
