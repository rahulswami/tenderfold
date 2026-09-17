<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Blog;

class ImportBlogsFromOldSql extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-blogs-from-sql {--file=u709314437_smpune.sql}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports all exact blog articles, HTML content, images, and SEO metadata from the old smpune SQL dump.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = base_path($this->option('file'));

        if (!file_exists($filePath)) {
            $this->error("SQL dump file not found at: {$filePath}");
            return 1;
        }

        $this->info("Reading SQL dump from: {$filePath}...");

        $fp = fopen($filePath, 'r');
        $inBlogs = false;
        $blogsSql = '';

        while (($line = fgets($fp)) !== false) {
            if (strpos($line, "CREATE TABLE `blogs`") !== false) {
                $inBlogs = true;
            }
            if ($inBlogs && strpos($line, "CREATE TABLE `carts`") !== false) {
                $inBlogs = false;
                break;
            }
            if ($inBlogs) {
                $blogsSql .= $line;
            }
        }
        fclose($fp);

        if (empty($blogsSql)) {
            $this->error("Could not find blogs table statements in the SQL file.");
            return 1;
        }

        $this->info("Extracted blogs SQL chunk (" . number_format(strlen($blogsSql)) . " bytes).");

        $pdo = DB::connection()->getPdo();
        $pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, true);

        // 1. Create temporary staging table
        $this->info("Setting up staging table `_old_blogs_staging` in MySQL...");
        $pdo->exec("DROP TABLE IF EXISTS `_old_blogs_staging`");

        $stagingSql = preg_replace("/CREATE TABLE `blogs`/", "CREATE TABLE `_old_blogs_staging`", $blogsSql);
        $stagingSql = preg_replace("/INSERT INTO `blogs`/", "INSERT INTO `_old_blogs_staging`", $stagingSql);

        $pdo->exec($stagingSql);

        $totalFound = (int)$pdo->query("SELECT COUNT(*) FROM `_old_blogs_staging`")->fetchColumn();
        $this->info("Loaded {$totalFound} raw blog rows into staging table.");

        if ($totalFound === 0) {
            $this->error("No blog rows were loaded into staging.");
            return 1;
        }

        // 2. Fetch rows and migrate into blogs table
        $this->info("Migrating blogs into target table with exact contents & paths...");

        $stmt = $pdo->query("SELECT * FROM `_old_blogs_staging` ORDER BY `id` ASC");
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Blog::truncate();

        $imported = 0;
        $bar = $this->output->createProgressBar(count($rows));
        $bar->start();

        foreach ($rows as $row) {
            $rawContent = $row['description'] ?? '';

            // Clean URLs and media paths in content:
            // Normalize internal links and upload paths so images and links work seamlessly
            $cleanContent = $rawContent;
            $cleanContent = str_replace(
                ['https://www.drsumitz.com/public/uploads/', 'http://www.drsumitz.com/public/uploads/', 'https://drsumitz.com/public/uploads/', 'http://drsumitz.com/public/uploads/'],
                '/uploads/',
                $cleanContent
            );
            $cleanContent = str_replace(
                ['https://www.drsumitz.com/uploads/', 'http://www.drsumitz.com/uploads/', 'https://drsumitz.com/uploads/', 'http://drsumitz.com/uploads/'],
                '/uploads/',
                $cleanContent
            );
            $cleanContent = str_replace(
                ['https://www.drsumitz.com/', 'http://www.drsumitz.com/', 'https://drsumitz.com/', 'http://drsumitz.com/'],
                '/',
                $cleanContent
            );

            // Handle image path
            $imageName = trim($row['image'] ?? '');
            $imagePath = !empty($imageName) ? 'uploads/product/' . ltrim($imageName, '/') : null;

            // Summary
            $metaDesc = trim($row['metadescription'] ?? '');
            $summary = !empty($metaDesc) ? $metaDesc : Str::limit(strip_tags($cleanContent), 180);

            // Meta tags
            $title = trim($row['name'] ?? '');
            $slug = trim($row['slug'] ?? '');
            $metaTitle = trim($row['metatitle'] ?? '');
            if (empty($metaTitle)) {
                $metaTitle = $title . ' | Dr. SumitZ Clinic';
            }
            $metaKeywords = trim($row['metakeywords'] ?? '');
            $tags = trim($row['tags'] ?? '');
            if (empty($tags) && !empty($metaKeywords)) {
                $tags = $metaKeywords;
            }

            $publishedAt = !empty($row['created_at']) && $row['created_at'] !== '0000-00-00 00:00:00'
                ? $row['created_at']
                : now();
            $updatedAt = !empty($row['updated_at']) && $row['updated_at'] !== '0000-00-00 00:00:00'
                ? $row['updated_at']
                : $publishedAt;

            DB::table('blogs')->insert([
                'id' => (int)$row['id'],
                'title' => $title,
                'slug' => $slug,
                'category' => 'Orthopedics & Physiotherapy',
                'summary' => $summary,
                'content' => $cleanContent,
                'image_path' => $imagePath,
                'published_at' => $publishedAt,
                'created_at' => $publishedAt,
                'updated_at' => $updatedAt,
                'schema_markup' => null,
                'metatitle' => $metaTitle,
                'metadescription' => $metaDesc ?: $summary,
                'metakeywords' => $metaKeywords,
                'tags' => $tags,
            ]);

            $imported++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Clean up staging table
        $pdo->exec("DROP TABLE IF EXISTS `_old_blogs_staging`");
        $pdo->exec("DROP TABLE IF EXISTS `_old_blogs_import`");

        $this->info("Successfully imported {$imported} blogs exactly from old database dump!");
        return 0;
    }
}
