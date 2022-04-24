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
        Schema::create('fixtures', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('home_team_id')->unsigned();
            $table->bigInteger('away_team_id')->unsigned();
            $table->integer('home_goals')->default(0);
            $table->integer('away_goals')->default(0);
            $table->integer('tour');
            $table->boolean('is_played')->nullable(false)->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fixtures');
    }
};
