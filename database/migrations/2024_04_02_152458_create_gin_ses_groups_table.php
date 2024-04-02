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
        Schema::create('gin_ses_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->integer('status')->nullable();
            $table->foreignId('gincana_session_id')->nullable()->constrained('gincana_sessions');
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
        Schema::dropIfExists('gincana_session_groups');
    }
};
