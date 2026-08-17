<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Str;

$jsonFile = __DIR__ . '/storage/app/old_drsumitz_seo.json';
if (!file_exists($jsonFile)) {
    die("JSON dump file not found at " . $jsonFile);
}

$data = json_decode(file_get_contents($jsonFile), true);

echo "Starting SEO & URL Sync from u709314437_smpune.sql...\n\n";

// Ensure metatitle, metadescription, metakeywords columns exist in services and blogs
if (!Schema::hasColumn('services', 'metatitle')) {
    Schema::table('services', function (Blueprint $table) {
        $table->string('metatitle')->nullable();
        $table->text('metadescription')->nullable();
        $table->text('metakeywords')->nullable();
    });
}
if (!Schema::hasColumn('blogs', 'metatitle')) {
    Schema::table('blogs', function (Blueprint $table) {
        $table->string('metatitle')->nullable();
        $table->text('metadescription')->nullable();
        $table->text('metakeywords')->nullable();
        $table->text('tags')->nullable();
    });
}

// ── 1. CMS Pages Sync ─────────────────────────────────────────────────────────
$cmsCount = 0;
foreach ($data['cms'] as $c) {
    $slug = trim($c['slug'] ?? '');
    if (empty($slug)) continue;

    $title = trim($c['title'] ?? '');
    $metaTitle = trim($c['metatitle'] ?? '');
    $metaDesc = trim($c['metadescription'] ?? '');
    $metaKeywords = trim($c['metakeywords'] ?? '');
    $description = $c['description'] ?? '';

    if (empty($metaTitle)) $metaTitle = $title;
    if (empty($metaDesc)) $metaDesc = Str::limit(strip_tags($description), 160);

    DB::table('cms_pages')->updateOrInsert(
        ['slug' => $slug],
        [
            'title' => $title,
            'metatitle' => $metaTitle,
            'metadescription' => $metaDesc,
            'metakeywords' => $metaKeywords,
            'description' => $description,
            'updated_at' => now(),
            'created_at' => now(),
        ]
    );
    $cmsCount++;
}
echo "✅ Synced {$cmsCount} CMS Pages.\n";

// ── 2. Services Sync ──────────────────────────────────────────────────────────
$serviceCount = 0;
foreach ($data['services'] as $s) {
    $slug = trim($s['slug'] ?? '');
    if (empty($slug)) continue;

    $name = trim($s['name'] ?? '');
    $metaTitle = trim($s['metatitle'] ?? '');
    $metaDesc = trim($s['metadescription'] ?? '');
    $metaKeywords = trim($s['metakeywords'] ?? '');
    $description = $s['description'] ?? '';
    $image = $s['image'] ?? null;

    if (empty($metaTitle)) $metaTitle = $name;
    if (empty($metaDesc)) $metaDesc = Str::limit(strip_tags($description), 160);

    $existing = DB::table('services')->where('slug', $slug)->first();
    if ($existing) {
        DB::table('services')->where('id', $existing->id)->update([
            'service_name' => $name,
            'metatitle' => $metaTitle,
            'metadescription' => $metaDesc,
            'metakeywords' => $metaKeywords,
            'updated_at' => now(),
        ]);
    } else {
        DB::table('services')->insert([
            'service_name' => $name,
            'slug' => $slug,
            'image_path' => $image,
            'summary' => Str::limit(strip_tags($description), 200),
            'intro_content' => $description,
            'metatitle' => $metaTitle,
            'metadescription' => $metaDesc,
            'metakeywords' => $metaKeywords,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
    $serviceCount++;
}
echo "✅ Synced {$serviceCount} Services.\n";

// ── 3. Treatments Sync ────────────────────────────────────────────────────────
$treatmentCount = 0;
foreach ($data['treatments'] as $t) {
    $slug = trim($t['slug'] ?? '');
    if (empty($slug)) continue;

    $name = trim($t['name'] ?? '');
    $metaTitle = trim($t['metatitle'] ?? '');
    $metaDesc = trim($t['metadescription'] ?? '');
    $metaKeywords = trim($t['metakeywords'] ?? '');
    $description = $t['description'] ?? '';
    $image = $t['image'] ?? null;

    if (empty($metaTitle)) $metaTitle = $name;
    if (empty($metaDesc)) $metaDesc = Str::limit(strip_tags($description), 160);

    DB::table('treatments')->updateOrInsert(
        ['slug' => $slug],
        [
            'name' => $name,
            'image' => $image,
            'description' => $description,
            'metatitle' => $metaTitle,
            'metadescription' => $metaDesc,
            'metakeywords' => $metaKeywords,
            'tags' => $metaKeywords,
            'updated_at' => now(),
            'created_at' => now(),
        ]
    );
    $treatmentCount++;
}
echo "✅ Synced {$treatmentCount} Treatments.\n";

// ── 4. Blogs Sync ─────────────────────────────────────────────────────────────
$blogCount = 0;
foreach ($data['blogs'] as $b) {
    $slug = trim($b['slug'] ?? '');
    if (empty($slug)) continue;

    $title = trim($b['title'] ?? '');
    $metaTitle = trim($b['metatitle'] ?? '');
    $metaDesc = trim($b['metadescription'] ?? '');
    $metaKeywords = trim($b['metakeywords'] ?? '');
    $content = $b['content'] ?? '';
    $image = $b['image'] ?? null;

    if (empty($metaTitle)) $metaTitle = $title;
    if (empty($metaDesc)) $metaDesc = Str::limit(strip_tags($content), 160);

    DB::table('blogs')->updateOrInsert(
        ['slug' => $slug],
        [
            'title' => $title,
            'category' => 'Orthopedics & Physiotherapy',
            'image_path' => $image,
            'content' => $content,
            'summary' => Str::limit(strip_tags($content), 200),
            'metatitle' => $metaTitle,
            'metadescription' => $metaDesc,
            'metakeywords' => $metaKeywords,
            'tags' => $metaKeywords,
            'published_at' => now(),
            'updated_at' => now(),
            'created_at' => now(),
        ]
    );
    $blogCount++;
}
echo "✅ Synced {$blogCount} Blogs.\n";

// ── 5. Team Members / Specialists Sync ───────────────────────────────────────
$teamCount = 0;
foreach ($data['teams'] as $tm) {
    $name = trim($tm['name'] ?? '');
    if (empty($name)) continue;

    $slug = trim($tm['slug'] ?? '');
    if (empty($slug)) $slug = Str::slug($name);

    $designation = trim($tm['designation'] ?? '');
    $metaTitle = trim($tm['metatitle'] ?? '');
    $metaDesc = trim($tm['metadescription'] ?? '');
    $metaKeywords = trim($tm['metakeywords'] ?? '');
    $description = $tm['description'] ?? '';
    $image = $tm['image'] ?? null;

    if (empty($metaTitle)) $metaTitle = "{$name} | {$designation} in Baner & Pune";
    if (empty($metaDesc)) $metaDesc = Str::limit(strip_tags($description), 160);

    DB::table('team_members')->updateOrInsert(
        ['name' => $name],
        [
            'slug' => $slug,
            'designation' => $designation,
            'image' => $image,
            'description' => $description,
            'metatitle' => $metaTitle,
            'metadescription' => $metaDesc,
            'metakeywords' => $metaKeywords,
            'tags' => $metaKeywords,
            'updated_at' => now(),
            'created_at' => now(),
        ]
    );
    $teamCount++;
}
echo "✅ Synced {$teamCount} Specialists.\n";

echo "\n🎉 All SEO URLs, Meta Titles, and Meta Descriptions successfully synchronized!\n";
