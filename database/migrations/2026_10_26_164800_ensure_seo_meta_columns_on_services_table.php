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
        if (Schema::hasTable('services')) {
            Schema::table('services', function (Blueprint $table) {
                if (!Schema::hasColumn('services', 'metatitle')) {
                    $table->string('metatitle')->nullable()->after('schema_markup');
                }
                if (!Schema::hasColumn('services', 'metadescription')) {
                    $table->text('metadescription')->nullable()->after('metatitle');
                }
                if (!Schema::hasColumn('services', 'metakeywords')) {
                    $table->text('metakeywords')->nullable()->after('metadescription');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('services')) {
            Schema::table('services', function (Blueprint $table) {
                if (Schema::hasColumn('services', 'metakeywords')) {
                    $table->dropColumn('metakeywords');
                }
                if (Schema::hasColumn('services', 'metadescription')) {
                    $table->dropColumn('metadescription');
                }
                if (Schema::hasColumn('services', 'metatitle')) {
                    $table->dropColumn('metatitle');
                }
            });
        }
    }
};
