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
        Schema::create('tweet_metrics', function (Blueprint $table) {
            $table->foreignId('tweet_id')->constrained('tweets')->cascadeOnDelete();
            $table->unsignedInteger('impression_count')->default(0);
            $table->unsignedInteger('like_count')->default(0);
            $table->unsignedInteger('reply_count')->default(0);
            $table->unsignedInteger('retweet_count')->default(0);
            $table->unsignedInteger('quote_count')->default(0);
            $table->unsignedInteger('video_views_count')->default(0);
            $table->unsignedInteger('url_link_clicks')->default(0);
            $table->unsignedInteger('user_profile_clicks')->default(0);

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
        Schema::dropIfExists('tweet_metrics');
    }
};
