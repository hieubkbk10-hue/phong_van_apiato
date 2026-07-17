<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            // snapshot lại tránh sau này bị mất
            $table->string('product_name');
            $table->decimal('price', 15, 2);
            $table->integer('quantity');

            // Nếu xoá mất sản phẩm thì chỉ set null product id thôi .Không xoá order_item
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('set null');
            // Nếu xoá mất đơn hàng thì order_item xoá theo cho đồng bộ
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
    }
};
