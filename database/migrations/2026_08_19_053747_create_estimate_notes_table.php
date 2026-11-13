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
        Schema::create('estimate_notes', function (Blueprint $table) {
           $table->id();
           $table->unsignedBigInteger('estimate_id');
            $table->text('note');
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('estimate_id')
                    ->references('id')
                    ->on('estimates')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('estimate_notes');
    }
};
