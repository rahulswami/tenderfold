<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('social_links')) {
            Schema::create('social_links', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique()->index();
                $table->text('original_url');
                $table->string('utm_source')->default('social');
                $table->string('utm_medium')->default('post');
                $table->string('utm_campaign')->nullable();
                $table->text('full_url');
                $table->string('title')->nullable();
                $table->unsignedBigInteger('clicks')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('social_links');
    }
};
