<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::rename('orders', 'order');


        Schema::create('order_item', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('order_id')->constrained('order')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('product')->onDelete('cascade');
            $table->bigInteger('quantity');
            $table->text('product_name')->nullable();
            $table->bigInteger('price')->nullable();
            $table->text('image_url')->nullable();
            $table->text('shipping_price')->nullable();
            $table->timestampTz('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestampTz('modified_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_items');
    }
};
