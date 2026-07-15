<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\HomeSlide;
use App\Models\Testimonial;
use App\Models\Blog;
use App\Models\Service;
use App\Models\Treatment;
use App\Models\TeamMember;
use App\Models\CmsPage;
use App\Models\SiteParameter;
use Illuminate\Support\Str;

#[Signature('app:migrate-dr-sumitz-data')]
#[Description('Migrates content database records from old drsumitz smpune DB to mlg_finedge DB.')]
class MigrateDrSumitzData extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Connecting to smpune database...');
        try {
            $sourcePdo = new \PDO("mysql:host=127.0.0.1;dbname=smpune;charset=utf8", "root", "");
            $sourcePdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\Exception $e) {
            $this->error('Failed to connect to source database smpune: ' . $e->getMessage());
            return 1;
        }

        $this->info('Truncating target tables...');
        HomeSlide::truncate();
        Testimonial::truncate();
        Blog::truncate();
        Service::truncate();
        Treatment::truncate();
        TeamMember::truncate();
        CmsPage::truncate();

        // 1. Migrate Home Slides (from Galleries table where type = 1 and status = 1)
        $this->info('Migrating slides...');
        $stmt = $sourcePdo->query("SELECT * FROM galleries WHERE type = 1 AND status = 1");
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            HomeSlide::create([
                'title' => $row['title'] ?: 'SumitZ Orthopaedic & Physiotherapy',
                'subtitle' => 'Proactive, quality healthcare in a safe and comfortable environment.',
                'image_path' => 'uploads/gallery/' . $row['gallery_image'],
                'button_text' => 'Book Appointment',
                'button_link' => '#contactSection',
                'sort_order' => 0,
            ]);
        }

        // 2. Migrate Testimonials
        $this->info('Migrating testimonials...');
        $stmt = $sourcePdo->query("SELECT * FROM testimonials WHERE status = 1");
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            Testimonial::create([
                'name' => $row['title'] ?: 'Anonymous',
                'role' => 'Verified Patient',
                'content' => $row['description'],
                'rating' => 5,
                'avatar_path' => null,
                'sort_order' => 0,
            ]);
        }

        // 3. Migrate Blogs
        $this->info('Migrating blogs...');
        $stmt = $sourcePdo->query("SELECT * FROM blogs WHERE status = 1");
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $content = $row['description'];
            $summary = Str::limit(strip_tags($content), 150);
            
            Blog::create([
                'title' => $row['name'],
                'slug' => $row['slug'],
                'category' => 'Orthopedics & Physiotherapy',
                'summary' => $summary,
                'content' => $content,
                'image_path' => 'uploads/product/' . $row['image'],
                'published_at' => $row['created_at'] ?: now(),
                'schema_markup' => null,
            ]);
        }

        // 4. Migrate Services
        $this->info('Migrating services...');
        $stmt = $sourcePdo->query("SELECT * FROM services WHERE status = 1");
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            Service::create([
                'service_name' => $row['name'],
                'slug' => $row['slug'],
                'image_path' => 'uploads/category/' . $row['image'],
                'icon_path' => 'uploads/category/' . $row['icon'],
                'hero_category' => 'Clinic Services',
                'hero_title' => $row['name'],
                'hero_subtitle' => $row['metadescription'] ?: 'SUMITZ Medical Center specializes in different medical services for the convenience of the community.',
                'intro_title' => 'Service Overview',
                'intro_content' => $row['description'],
                'summary' => $row['metadescription'],
                'is_active' => true,
                'sort_order' => 0,
                'schema_markup' => null,
            ]);
        }

        // 5. Migrate Treatments
        $this->info('Migrating treatments...');
        $stmt = $sourcePdo->query("SELECT * FROM treatments WHERE status = 1");
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            Treatment::create([
                'name' => $row['name'],
                'slug' => $row['slug'],
                'image' => 'uploads/category/' . $row['image'],
                'description' => $row['description'],
                'metatitle' => $row['metatitle'],
                'metadescription' => $row['metadescription'],
                'metakeywords' => $row['metakeywords'],
                'tags' => $row['tags'],
                'status' => true,
                'schema_markup' => null,
            ]);
        }

        // 6. Migrate Specialists (Teams)
        $this->info('Migrating specialists...');
        $stmt = $sourcePdo->query("SELECT * FROM teams WHERE status = 1");
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            TeamMember::create([
                'name' => $row['name'],
                'designation' => $row['designation'],
                'slug' => $row['slug'],
                'image' => 'uploads/collection/' . $row['image'],
                'description' => $row['description'],
                'facebook' => $row['facebook'],
                'twitter' => $row['twitter'],
                'phone' => $row['phone'],
                'status' => true,
                'metatitle' => $row['metatitle'],
                'metadescription' => $row['metadescription'],
                'metakeywords' => $row['metakeywords'],
                'tags' => $row['tags'],
                'schema_markup' => null,
            ]);
        }

        // 7. Migrate CMS Pages
        $this->info('Migrating CMS pages...');
        $stmt = $sourcePdo->query("SELECT * FROM cms WHERE status = 1");
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            CmsPage::create([
                'title' => $row['title'],
                'slug' => $row['slug'],
                'metatitle' => $row['metatitle'],
                'metadescription' => $row['metadescription'],
                'metakeywords' => $row['metakeywords'],
                'image' => $row['image'] ? 'uploads/page/' . $row['image'] : null,
                'description' => $row['description'],
                'status' => true,
                'schema_markup' => null,
            ]);
        }

        // 8. Migrate Leads
        $this->info('Migrating leads...');
        DB::table('leads')->truncate();
        
        // Migrate from contactus
        $stmt = $sourcePdo->query("SELECT * FROM contactus");
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            DB::table('leads')->insert([
                'name' => $row['name'],
                'phone' => $row['phone'] ?: 'N/A',
                'email' => $row['email'],
                'message' => $row['message'],
                'source' => Str::limit('Contact Us: ' . ($row['subject'] ?: 'General Inquiry'), 250),
                'status' => 'New',
                'notes' => null,
                'created_at' => $row['created_at'] ?: now(),
                'updated_at' => $row['updated_at'] ?: now(),
            ]);
        }

        // Migrate from appointments
        $stmt = $sourcePdo->query("SELECT * FROM appointments");
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $notes = "Age: " . $row['age'] . "\n";
            $notes .= "Requested Service ID: " . $row['requestedservice'] . "\n";
            $notes .= "Preferred Date: " . $row['requestdate'] . ", Time: " . $row['requesttime'];

            DB::table('leads')->insert([
                'name' => $row['name'],
                'phone' => $row['phone'] ?: 'N/A',
                'email' => $row['email'],
                'message' => $row['message'],
                'source' => 'Appointment Request Form',
                'status' => 'New',
                'notes' => $notes,
                'created_at' => $row['created_at'] ?: now(),
                'updated_at' => $row['updated_at'] ?: now(),
            ]);
        }

        // 9. Seed/Update Site Settings
        $this->info('Seeding clinic contact settings...');
        SiteParameter::updateOrCreate(['id' => 'site_name'], ['value' => 'SumitZ Clinic', 'label' => 'Site Name', 'category' => 'general']);
        SiteParameter::updateOrCreate(['id' => 'site_tagline'], ['value' => 'Orthopedic & Physiotherapy Specialists', 'label' => 'Site Tagline', 'category' => 'general']);
        SiteParameter::updateOrCreate(['id' => 'phone'], ['value' => '+91 9545559434', 'label' => 'Contact Phone Number', 'category' => 'contact']);
        SiteParameter::updateOrCreate(['id' => 'whatsapp_number'], ['value' => '919545559434', 'label' => 'WhatsApp Number (with country code)', 'category' => 'contact']);
        SiteParameter::updateOrCreate(['id' => 'email'], ['value' => 'drgsumit@gmail.com', 'label' => 'Contact Email Address', 'category' => 'contact']);
        SiteParameter::updateOrCreate(['id' => 'address'], ['value' => 'First Floor, Krishna Avenue, Above Dominos, Opposite DMART, Baner, Pune - 411045', 'label' => 'Physical Address', 'category' => 'contact']);
        SiteParameter::updateOrCreate(['id' => 'logo_path'], ['value' => 'images/logo.png', 'label' => 'Custom Logo Image (WebP)', 'category' => 'branding']);
        SiteParameter::updateOrCreate(['id' => 'favicon_path'], ['value' => 'favicon.ico', 'label' => 'Custom Favicon Image (WebP)', 'category' => 'branding']);

        $this->info('Migration completed successfully!');
        return 0;
    }
}
