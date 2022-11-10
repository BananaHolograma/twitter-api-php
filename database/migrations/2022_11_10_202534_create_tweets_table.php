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
        Schema::create('tweets', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('in_reply_to_author_id')->nullable()->constrained('users');
            $table->string('text', 140)->index();
            $table->json('edit_history_tweet_ids');
            $table->json('edit_controls');
            $table->json('public_metrics');
            $table->json('non_public_metrics');
            $table->json('organic_metrics');
            $table->json('promoted_metrics');
            $table->json('reply_settings');
            $table->char('lang', 8)->index();
            $table->boolean('possibly_sensitive')->default(false);
            $table->string('source')->default('Twitter Web App');
            $table->json('withheld')->nullable();
            $table->timestamps();
        });

        Schema::table('tweets', function (Blueprint $table) {
            $table->foreignId('conversation_id')->after('in_reply_to_author_id')->constrained('tweets');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tweets');
    }
};
