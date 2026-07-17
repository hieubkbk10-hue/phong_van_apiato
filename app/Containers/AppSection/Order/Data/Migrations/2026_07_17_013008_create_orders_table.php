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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique();
            $table->date('delivery_date');
            $table->string('shipping_carrier');
            $table->string('payment_method'); // COD. CASH, BANK_TRANSFER , DEBT
            $table->integer('debt_days')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account')->nullable();
            $table->decimal('down_payment', 15, 2)->default(0);
            $table->decimal('shipping_fee', 15, 2)->default(0);
            $table->string('status')->default('pending'); // pending, processing, completed, cancelled ,..

            // tránh xoá khách có đơn
            $table->foreignId('customer_id')->constrained('customers')->onDelete('restrict');

            $table->timestamps();
            $table->softDeletes();

            $table->index('delivery_date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
