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
        Schema::create('gin_ses_grp_usr_checkpoints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gin_ses_grp_user_id')->nullable()->constrained('gin_ses_grp_users');
            $table->foreignId('gincana_point_id')->nullable()->constrained('gincana_points');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gincana_session_groups_users_checkpoints');
    }
};
