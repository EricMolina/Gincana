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
        Schema::create('gincana_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->foreignId('session_admin')->nullable()->constrained('users');
            $table->foreignId('gincana_id')->nullable()->constrained('gincanas');
            $table->string('session_code')->unique();
            $table->integer('status');
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
        Schema::dropIfExists('gincana_sessions');
    }
};
