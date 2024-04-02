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
        Schema::create('gincana_points', function (Blueprint $table) {
            $table->id();
            $table->longText('hint')->nullable();
            $table->integer('order_id')->nullable();
            $table->foreignId('gincana_id')->nullable()->constrained('gincanas');
            $table->foreignId('point_id')->nullable()->constrained('points');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gincana_points');
    }
};
