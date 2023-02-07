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
        Schema::create('playlists', function (Blueprint $table) {
            $table->id();
            $table->integer('discogs_id')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('avatar');
            $table->string('discogs_token', 256);
            $table->string('discogs_secret', 256);
            $table->string('spotify_token', 256);
            $table->string('spotify_refresh_token', 256);
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
        Schema::dropIfExists('playlists');
    }
};
