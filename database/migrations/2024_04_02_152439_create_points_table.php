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
        Schema::create('points', function (Blueprint $table) {
            $table->id();
            $table->double('coord_x')->nullable();
            $table->double('coord_y')->nullable();
            $table->string('name')->nullable();
            $table->string('address')->nullable();
            $table->longText('img')->nullable();
            $table->foreignId('main_label_id')->nullable()->constrained('labels');
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
        Schema::dropIfExists('points');
    }
};
