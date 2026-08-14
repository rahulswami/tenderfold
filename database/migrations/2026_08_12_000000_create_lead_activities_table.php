<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('lead_activities')) {
            Schema::create('lead_activities', function (Blueprint $table) {
                $table->id();
                $table->foreignId('lead_id')->constrained('leads')->onDelete('cascade');
                $table->string('user_name')->default('Admin');
                $table->string('action_type')->default('status_update'); // e.g. status_update, note_added, call_log
                $table->string('status_from')->nullable();
                $table->string('status_to')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('lead_activities');
    }
};
