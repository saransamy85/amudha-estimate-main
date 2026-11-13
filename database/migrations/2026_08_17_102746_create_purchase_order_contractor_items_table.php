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
        Schema::create('purchase_order_contractor_items', function (Blueprint $table) {
               $table->id();

                $table->foreignId('purchase_order_id')
                        ->constrained('purchase_orders')
                        ->onDelete('cascade');

                $table->string('scope_of_work')->nullable();

                $table->decimal('qty', 15, 2)->default(0);

                $table->string('unit')->nullable();

                $table->decimal('rate', 15, 2)->default(0);

                $table->decimal('amount', 15, 2)->default(0);

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
        Schema::dropIfExists('purchase_order_contractor_items');
    }
};
