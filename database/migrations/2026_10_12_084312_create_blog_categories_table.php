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
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        $categories = [
            ['name' => 'Laundry Services', 'slug' => 'laundry-services', 'description' => 'Everyday wash & fold, fabric softness, and daily garment hygiene.'],
            ['name' => 'Dry Cleaning', 'slug' => 'dry-cleaning', 'description' => 'Specialized chemical solvent cleaning for silks, woolens, and suits.'],
            ['name' => 'Steam Pressing', 'slug' => 'steam-pressing', 'description' => 'Vacuum table heated steam pressing for crisp, crease-free garment finishing.'],
            ['name' => 'Shoe Cleaning', 'slug' => 'shoe-cleaning', 'description' => 'Deep cleaning, stain removal, and care for sneakers, leather, and suede shoes.'],
            ['name' => 'Carpet Cleaning', 'slug' => 'carpet-cleaning', 'description' => 'Industrial dust extraction, deep sanitization, and wash for rugs and carpets.'],
            ['name' => 'Curtain Cleaning', 'slug' => 'curtain-cleaning', 'description' => 'Heavy drapery, blackout curtain washing, and dust allergen elimination.'],
        ];

        foreach ($categories as $cat) {
            \Illuminate\Support\Facades\DB::table('blog_categories')->insert([
                'name' => $cat['name'],
                'slug' => $cat['slug'],
                'description' => $cat['description'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_categories');
    }
};
