<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shopping_session_id')->constrained('shopping_sessions')->onDelete('cascade');
            $table->string('name_receiver')->nullable();
            $table->string('detail_address')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->bigInteger('city_id')->nullable();
            $table->bigInteger('province_id')->nullable();
            $table->text('midtrans_token')->nullable();
            $table->text('midtrans_id')->nullable();
            $table->bigInteger('total_payment')->default(0);
            $table->bigInteger('shipping_price')->nullable();
            $table->bigInteger('sub_total')->nullable();
            $table->string('status', 255)->default('pending');
            $table->timestampTz('created_at')->default(DB::raw('now()'));
            $table->timestampTz('modified_at')->default(DB::raw('now()'));
            
            // Add constraints
            $table->primary('id');
            $table->check('status in (\'pending\', \'paid\', \'send\', \'need_confirmation\', \'done\', \'cancel\')');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
