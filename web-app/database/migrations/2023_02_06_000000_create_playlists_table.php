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
            $table->string('uuid')->unique();
            $table->foreignId('owner_id')->constrained('users');
            $table->foreignId('playlist_type_id')->constrained('playlist_types');
            $table->json('discogs_query_data');
            $table->string('name', 256);
            $table->string('spotify_identifier', 256)->nullable();
            $table->dateTime('last_sync');
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
