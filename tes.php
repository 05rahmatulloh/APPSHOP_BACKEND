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
      Schema::create('categories', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('slug')->unique();
      $table->enum('type', ['sale', 'rent'])->default('sale');
      $table->timestamps();
      });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};





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

//COD CEK
$table->boolean('is_cod_available')->default(true);
//MIDTRANS CEK
$table->boolean('is_midtrans_available')->default(true);


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
       Schema::create('discounts_product', function (Blueprint $table) {
       $table->id();

       $table->foreignId('discount_id')
       ->constrained()
       ->cascadeOnDelete();

       $table->foreignId('product_id')
       ->constrained()
       ->cascadeOnDelete();
       });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dicount_product');
    }
};


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
        Schema::create('carts', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};


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
    Schema::create('cart_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('cart_id')->constrained()->cascadeOnDelete();
    $table->foreignId('product_id')->constrained()->cascadeOnDelete();

    $table->integer('quantity');
    $table->decimal('price', 12, 2);

    $table->timestamps();

    $table->unique(['cart_id', 'product_id']);
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};


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
        Schema::create('order_items', function (Blueprint $table) {
        $table->id();

        $table->foreignId('order_id')
        ->constrained()
        ->cascadeOnDelete();

        $table->foreignId('product_id')
        ->constrained()
        ->cascadeOnDelete();

        $table->decimal('price', 12, 2); // snapshot harga
        $table->integer('quantity');
        $table->decimal('subtotal', 12, 2);

        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
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
        Schema::create('ongkirs', function (Blueprint $table) {
            $table->id();
            $table->string('kampus');
            $table->integer('biaya');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ongkirs');
    }
};
