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
        Schema::create('gincanas', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->longText('desc')->nullable();
            $table->integer('difficulty')->nullable();
            $table->double('coord_x')->nullable();
            $table->double('coord_y')->nullable();
            $table->integer('user_id')->nullable()->constrained('users');;
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
        Schema::dropIfExists('gincanas');
    }
};
