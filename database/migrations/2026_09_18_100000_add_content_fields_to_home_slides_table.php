<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('home_slides', function (Blueprint $table) {
            $table->string('eyebrow')->nullable()->after('mobile_image_path');
            $table->string('button_2_text')->nullable()->after('button_link');
            $table->string('button_2_link')->nullable()->after('button_2_text');
            $table->string('trust_1_num')->nullable()->after('button_2_link');
            $table->string('trust_1_label')->nullable()->after('trust_1_num');
            $table->string('trust_2_num')->nullable()->after('trust_1_label');
            $table->string('trust_2_label')->nullable()->after('trust_2_num');
            $table->string('trust_3_num')->nullable()->after('trust_2_label');
            $table->string('trust_3_label')->nullable()->after('trust_3_num');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('home_slides', function (Blueprint $table) {
            $table->dropColumn([
                'eyebrow',
                'button_2_text',
                'button_2_link',
                'trust_1_num',
                'trust_1_label',
                'trust_2_num',
                'trust_2_label',
                'trust_3_num',
                'trust_3_label',
            ]);
        });
    }
};
