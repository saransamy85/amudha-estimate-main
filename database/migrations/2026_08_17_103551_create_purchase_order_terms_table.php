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
        Schema::create('purchase_order_terms', function (Blueprint $table) {
            $table->id();

            $table->foreignId('purchase_order_id')
                    ->constrained('purchase_orders')
                    ->onDelete('cascade');

            $table->text('term');

            $table->integer('sort_order')->default(0);

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
        Schema::dropIfExists('purchase_order_terms');
    }
};
