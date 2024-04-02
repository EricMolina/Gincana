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
        Schema::create('points_labels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('point_id')->nullable()->constrained('points');
            $table->foreignId('label_id')->nullable()->constrained('labels');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('points_labels');
    }
};
