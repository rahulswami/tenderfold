<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class SiteSeeder extends Seeder
{
    /**
     * Run the complete, authentic database seeds for Tenderfold.
     */
    public function run(): void
    {
        // 1. Seed Admin User
        DB::table('users')->updateOrInsert(
            ['email' => 'admin@tenderfold.in'],
            [
                'name' => 'Tenderfold Admin',
                'password' => Hash::make('admin123'),
                'role' => 'superadmin',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // 2. Clean up and purge all legacy dummy site parameters
        $legacyKeys = [
            'business_loan_rate', 'car_loan_rate', 'home_loan_rate', 'lap_rate', 'personal_loan_rate',
            'loan_types', 'bank_partners', 'specialists_count', 'treatments_count'
        ];
        DB::table('site_parameters')->whereIn('id', $legacyKeys)->delete();

        // 3. Seed Official Site Parameters
        $parameters = [
            ['id' => 'site_name', 'value' => 'Tenderfold', 'label' => 'Site Name', 'category' => 'general'],
            ['id' => 'site_tagline', 'value' => 'Freshness in Every Single Fold - Premium Laundry & Dry Cleaner in Gurgaon', 'label' => 'Site Tagline', 'category' => 'general'],
            ['id' => 'meta_description', 'value' => 'Tenderfold is a premium laundry and dry cleaner in Gurgaon. Doorstep pickup for wash & fold, steam ironing, dry cleaning and specialized fabric care. Call +91 95824 66445.', 'label' => 'Default Meta Description', 'category' => 'general'],
            ['id' => 'meta_keywords', 'value' => 'laundry gurgaon, dry cleaning gurgaon, wash and fold, steam ironing, shoe cleaning, carpet cleaning, sector 49, sector 83, tenderfold', 'label' => 'Default Meta Keywords', 'category' => 'general'],
            ['id' => 'google_tag_id', 'value' => null, 'label' => 'Google Tag / GA4 Measurement ID', 'category' => 'general'],
            ['id' => 'google_analytics_id', 'value' => null, 'label' => 'Google Analytics Tracking ID', 'category' => 'general'],
            ['id' => 'header_scripts', 'value' => null, 'label' => 'Custom Header Scripts / CSS', 'category' => 'general'],
            ['id' => 'live_site_url', 'value' => 'https://tenderfold.in', 'label' => 'Live Site URL', 'category' => 'general'],
            ['id' => 'sitemap_domain', 'value' => 'https://tenderfold.in', 'label' => 'Sitemap Domain', 'category' => 'general'],
            ['id' => 'google_search_console_site_url', 'value' => 'sc-domain:tenderfold.in', 'label' => 'Google Search Console URL', 'category' => 'general'],
            
            // Branding
            ['id' => 'logo_path', 'value' => 'assets/logo.png', 'label' => 'Custom Logo Image', 'category' => 'branding'],
            ['id' => 'favicon_path', 'value' => 'assets/logo.png', 'label' => 'Custom Favicon Image', 'category' => 'branding'],
            ['id' => 'og_image_path', 'value' => 'assets/logo.png', 'label' => 'Social Open Graph Share Image', 'category' => 'branding'],
            ['id' => 'chat_assistant_name', 'value' => 'Tenderfold Care Assistant', 'label' => 'Chat Assistant Display Name', 'category' => 'general'],
            ['id' => 'chat_assistant_avatar', 'value' => null, 'label' => 'Chat Assistant Avatar Image', 'category' => 'branding'],
            
            // Security & Forms
            ['id' => 'recaptcha_enabled', 'value' => '0', 'label' => 'Enable Google reCAPTCHA', 'category' => 'general'],
            ['id' => 'recaptcha_site_key', 'value' => null, 'label' => 'reCAPTCHA Site Key', 'category' => 'general'],
            ['id' => 'recaptcha_secret_key', 'value' => null, 'label' => 'reCAPTCHA Secret Key', 'category' => 'general'],
            
            // Contact & Store Hubs
            ['id' => 'phone', 'value' => '+91 95824 66445', 'label' => 'Contact Phone Number', 'category' => 'contact'],
            ['id' => 'phone_display', 'value' => '+91 95824 66445', 'label' => 'Display Phone Number', 'category' => 'contact'],
            ['id' => 'email', 'value' => 'info@tenderfold.in', 'label' => 'Contact Email Address', 'category' => 'contact'],
            ['id' => 'address', 'value' => 'Sector 49 & Sector 83, Gurgaon, Haryana - 122018', 'label' => 'Physical Address', 'category' => 'contact'],
            ['id' => 'address_baner', 'value' => 'Shop No. 19, 1st Floor, B Block Shopping Plaza, Orchid Petals, South City II, Sector 49, Gurgaon - 122018', 'label' => 'Sector 49 Hub Address', 'category' => 'contact'],
            ['id' => 'address_hinjewadi', 'value' => 'F-193, First Floor, Sapphire 83 Mall, Sector 83, Gurgaon - 122004', 'label' => 'Sector 83 Hub Address', 'category' => 'contact'],
            ['id' => 'helpline_baner', 'value' => '+91 95824 66445', 'label' => 'Sector 49 Helpline', 'category' => 'contact'],
            ['id' => 'helpline_hinjewadi', 'value' => '+91 98996 22294', 'label' => 'Sector 83 Helpline', 'category' => 'contact'],
            ['id' => 'footer_helplines', 'value' => '+91 95824 66445, +91 98996 22294', 'label' => 'Footer Helpline Numbers', 'category' => 'contact'],
            ['id' => 'clinic_locations_short', 'value' => 'Sector 49 & Sector 83, Gurgaon', 'label' => 'Locations Short Text', 'category' => 'contact'],
            ['id' => 'clinic_timings', 'value' => 'Mon - Sun: 8:00 AM - 9:00 PM', 'label' => 'Operational Hours', 'category' => 'contact'],
            ['id' => 'whatsapp_number', 'value' => '919582466445', 'label' => 'WhatsApp Number (with country code)', 'category' => 'contact'],
            ['id' => 'whatsapp_admin_recipient', 'value' => '919582466445', 'label' => 'WhatsApp Admin Alert Recipient', 'category' => 'contact'],
            
            // Footer & Social
            ['id' => 'copyright_text', 'value' => '© ' . date('Y') . ' Tenderfold. Freshness in Every Single Fold. All Rights Reserved.', 'label' => 'Footer Copyright Line', 'category' => 'general'],
            ['id' => 'footer_description', 'value' => 'Tenderfold is Gurgaon\'s trusted premium laundry, dry cleaning & specialized fabric care service. Doorstep pickup across all major sectors.', 'label' => 'Footer Description', 'category' => 'general'],
            ['id' => 'social_instagram', 'value' => 'https://www.instagram.com/tenderfold/', 'label' => 'Instagram URL', 'category' => 'social'],
            ['id' => 'social_facebook', 'value' => 'https://facebook.com/tenderfold', 'label' => 'Facebook URL', 'category' => 'social'],
            ['id' => 'social_practo', 'value' => 'https://maps.app.goo.gl/tenderfold', 'label' => 'Google Business / Maps URL', 'category' => 'social'],
            
            // Styling & Themes
            ['id' => 'index_hero_bg', 'value' => '#F4F7FB', 'label' => 'Home Hero Section Background', 'category' => 'styling'],
            ['id' => 'index_services_bg', 'value' => '#ffffff', 'label' => 'Home Services Section Background', 'category' => 'styling'],
            ['id' => 'index_about_bg', 'value' => '#ffffff', 'label' => 'Home About Section Background', 'category' => 'styling'],
            ['id' => 'index_testimonials_bg', 'value' => '#F4F7FB', 'label' => 'Home Testimonials Background', 'category' => 'styling'],
            ['id' => 'index_faq_bg', 'value' => '#ffffff', 'label' => 'Home FAQ Background', 'category' => 'styling'],
            ['id' => 'index_cta_bg', 'value' => 'linear-gradient(135deg, #0C2454 0%, #143572 100%)', 'label' => 'Home CTA Section Background', 'category' => 'styling'],
        ];

        foreach ($parameters as $param) {
            DB::table('site_parameters')->updateOrInsert(
                ['id' => $param['id']],
                [
                    'value' => $param['value'],
                    'label' => $param['label'],
                    'category' => $param['category'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // 4. Seed Homepage Slides
        DB::table('home_slides')->truncate();
        DB::table('home_slides')->insert([
            [
                'title' => 'Freshness in Every Single Fold.',
                'subtitle' => "Gurgaon's trusted premium laundry, dry cleaning & specialized fabric spa. Doorstep pickup across all major sectors.",
                'image_path' => 'assets/hero.jpg',
                'button_text' => 'Book Free Pickup',
                'button_link' => '#bookModal',
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Eco-Friendly Dry Cleaning & Couture Care.',
                'subtitle' => 'Zero harsh chemicals. Expert care for designer wear, silk, woolens, curtains, carpets & sneakers.',
                'image_path' => 'assets/shirts.jpg',
                'button_text' => 'Explore Services',
                'button_link' => '/services',
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Crisp Steam Pressing on Vacuum Tables.',
                'subtitle' => 'Sharp creases without fabric stress or shine marks. Delivered fresh on hangers or in boutique packing.',
                'image_path' => 'assets/whiteshirt.jpg',
                'button_text' => 'View Rate Card',
                'button_link' => '/pricing',
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 5. Seed Customer Testimonials
        DB::table('testimonials')->truncate();
        DB::table('testimonials')->insert([
            [
                'name' => 'Pooja Narang',
                'role' => 'South City II, Gurgaon',
                'content' => 'Tenderfold has completely transformed how our household manages laundry. Their wash & fold service is impeccably hygienic, clothes smell subtle and clean, and turnaround is always on point.',
                'rating' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Rohan Mehra',
                'role' => 'Sector 83, Gurgaon',
                'content' => 'I sent my wedding sherwani and my wife\'s designer lehenga for dry cleaning. The embroidery and silk were preserved flawlessly. Exceptional care and attention to detail.',
                'rating' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ananya Sharma',
                'role' => 'DLF Phase 5, Gurgaon',
                'content' => 'The vacuum steam pressing is boutique level. Every business shirt and linen trouser comes back with crisp creases and zero iron shine. Doorstep pickup makes it effortlessly easy.',
                'rating' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Vikram Sethi',
                'role' => 'Nirvana Country, Sector 50',
                'content' => 'Their shoe spa restored my white leather sneakers and suede loafers like new. Extremely impressed with their specialized footwear cleaning techniques.',
                'rating' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Meenakshi Rao',
                'role' => 'Sohna Road, Gurgaon',
                'content' => 'Prompt doorstep pickup, transparent pricing, and gentle fabric care. They handled our heavy wool carpets and blackout curtains with total professionalism.',
                'rating' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 6. Seed Laundry & Fabric Care Services
        DB::table('services')->truncate();
        $services = [
            [
                'slug' => 'laundry-services',
                'service_name' => 'Laundry Services',
                'hero_category' => 'DAILY ESSENTIALS',
                'hero_title' => 'Everyday Wash, Fold & Steam Care',
                'hero_subtitle' => 'Hygienic batch washing, pH-neutral detergents, fabric softening, and neat folding delivered to your doorstep.',
                'rate_value' => '₹80 / kg (Wash & Fold)',
                'max_loan' => 'Batch Washed · 100% Segregated',
                'tenure' => '24 - 48 Hours',
                'intro_title' => 'The Modern Routine for Daily Laundry',
                'intro_content' => '<p>At Tenderfold, we believe daily laundry deserves premium care. Your garments are never mixed with other orders. Each load is sorted by color and fabric type, washed in state-of-the-art Italian washers with skin-safe eco detergents, dried at gentle temperatures, and folded to perfection.</p>',
                'eligibility_criteria' => "Individual Customer Batch Washing\n100% Water Softened & Filtered\nSkin-Safe Allergen-Free Detergents",
                'documents_required' => "Barcoded Garment Tagging\nGentle Low-Heat Tumble Drying\nNeat Boutique Bundle Packaging",
                'tips_title' => 'Fabric Care Instructions',
                'tips_content' => '<p>Separate whites from dark colors and check care labels for delicate wool or silk blend requirements before sending.</p>',
                'faqs' => json_encode([
                    ['question' => 'Is my laundry washed separately from other customers?', 'answer' => 'Yes, absolutely. We practice 100% dedicated batch washing. Your clothes are never mixed with any other customer garments.'],
                    ['question' => 'What is the turnaround time for wash & fold?', 'answer' => 'Standard turnaround is 24 to 48 hours from doorstep collection.']
                ]),
                'badge' => 'Most Popular',
                'summary' => 'Hygienic batch washing, gentle eco-detergents, and crisp folding for everyday clothes, linens, and bedsheets.',
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'dry-cleaning',
                'service_name' => 'Dry Cleaning',
                'hero_category' => 'COUTURE & DELICATES',
                'hero_title' => 'Eco Dry Cleaning & Delicate Restoration',
                'hero_subtitle' => 'Gentle hydrocarbon solvent cleaning for suits, silks, sarees, designer couture, and winter woolens.',
                'rate_value' => 'Suits from ₹350 | Sarees from ₹250',
                'max_loan' => 'Silks, Woolens & Embellished Wear',
                'tenure' => '48 - 72 Hours',
                'intro_title' => 'Couture Fabric Preservation',
                'intro_content' => '<p>Our dry cleaning process uses gentle, odor-free hydrocarbon solvents in closed-loop Italian machines. Embellishments, delicate silk fibers, fine cashmere, and structured suits receive individual attention, targeted stain spotting, and zero color fading.</p>',
                'eligibility_criteria' => "Zero Harsh Perchloroethylene Chemicals\nIndividual Pre-Spotting Stain Treatment\nSafe for Sequins, Zari & Embroidery",
                'documents_required' => "Breathable Garment Bag Delivery\nCustom Hanger Shaping\nAnti-Moth Protective Storage Wrap",
                'tips_title' => 'Storing Dry Cleaned Garments',
                'tips_content' => '<p>Remove plastic covers after delivery and store ethnic garments in breathable muslin bags in a cool, dry wardrobe.</p>',
                'faqs' => json_encode([
                    ['question' => 'Are delicate designer lehengas and wedding sherwanis safe?', 'answer' => 'Yes. Our master fabric technicians test embellishments and use gentle solvent baths designed for delicate zari and embroidery.'],
                    ['question' => 'Do garments have a chemical odor after dry cleaning?', 'answer' => 'No. We use eco-friendly, odorless solvents and thorough aeration cycles so your garments return fresh.']
                ]),
                'badge' => 'Premium Care',
                'summary' => 'Eco-friendly hydrocarbon dry cleaning for ethnic wear, formal suits, cashmere, silks, and heavy woolens.',
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'ironing',
                'service_name' => 'Steam Pressing',
                'hero_category' => 'PRECISION FINISH',
                'hero_title' => 'Boutique Vacuum Steam Finishing',
                'hero_subtitle' => 'Professional temperature-controlled steam pressing on vacuum tables that protect fabric textures with zero shine marks.',
                'rate_value' => '₹115 / kg (Wash & Steam Iron)',
                'max_loan' => 'Formals, Linens & Cottons',
                'tenure' => '24 - 48 Hours',
                'intro_title' => 'The Art of Sharp, Crease-Free Pressing',
                'intro_content' => '<p>Traditional flat irons squash fabric fibers and leave shiny burns. Our vacuum suction tables draw continuous dry steam through the fabric, relaxing threads from within to yield razor-sharp pleats and a smooth, luxurious hand feel.</p>',
                'eligibility_criteria' => "Padded Vacuum Suction Tables\nDry High-Pressure Steam Finish\nZero Fabric Shine or Scorch Marks",
                'documents_required' => "Collar & Cuff Precision Shaping\nDelivered on Sturdy Hangers\nProtective Shoulder Guards",
                'tips_title' => 'Maintaining Creases',
                'tips_content' => '<p>Hang steam-pressed trousers and shirts immediately to preserve crisp pleats and maintain collar form.</p>',
                'faqs' => json_encode([
                    ['question' => 'Will steam pressing damage delicate buttons or zippers?', 'answer' => 'No. We use specialized steam irons with Teflon shoe plates and padded tables that protect delicate trims.']
                ]),
                'badge' => 'Quick Turnaround',
                'summary' => 'Razor-sharp creases on industrial vacuum steam tables with zero burn marks or shine.',
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'shoe-cleaning',
                'service_name' => 'Shoe Cleaning',
                'hero_category' => 'FOOTWEAR SPA',
                'hero_title' => 'Deep Shoe Laundry & Restoration',
                'hero_subtitle' => 'Professional cleaning, sole de-yellowing, suede grooming, and odor removal for sneakers and luxury shoes.',
                'rate_value' => 'From ₹350 / pair',
                'max_loan' => 'Sneakers, Suede & Leather',
                'tenure' => '3 - 5 Days',
                'intro_title' => 'Handcrafted Footwear Spa & Renewal',
                'intro_content' => '<p>From premium white kicks to delicate suede loafers and leather boots, our shoe artisans use specialized pH-neutral cleaners, soft-bristle brushes, and UV ozone sanitization chambers.</p>',
                'eligibility_criteria' => "Material-Specific Brushes\nSole De-Oxidization\nOdor Neutralization",
                'documents_required' => "Lace Cleaning & Ironing\nSuede Re-Nap Grooming\nWater-Repellent Coating Option",
                'tips_title' => 'Shoe Care Recommendations',
                'tips_content' => '<p>Never machine-wash leather or suede shoes at home as water causes structural deformation and adhesive failure.</p>',
                'faqs' => json_encode([
                    ['question' => 'Can you remove stubborn dirt from white boost soles?', 'answer' => 'Yes, we apply specialized oxidation-reversal solutions to restore original white soles.'],
                    ['question' => 'Is suede cleaning safe?', 'answer' => 'Yes, we use dry foam and specialized brass/crepe brushes for safe suede restoration.']
                ]),
                'badge' => 'Specialist Care',
                'summary' => 'Deep cleaning, sole de-yellowing, suede restoration, and deodorization for sneakers and footwear.',
                'sort_order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'carpet-cleaning',
                'service_name' => 'Carpet Cleaning',
                'hero_category' => 'HOME SPA',
                'hero_title' => 'Deep Carpet & Rug Cleaning',
                'hero_subtitle' => 'Multi-stage dust extraction, shampooing, stain removal, and sanitization for home carpets.',
                'rate_value' => 'From ₹25 / sq.ft',
                'max_loan' => 'Rugs, Wool & Persian Carpets',
                'tenure' => '3 - 5 Days',
                'intro_title' => 'Revitalize Your Home Carpets',
                'intro_content' => '<p>Our carpet spa extracts trapped allergens, pet dander, and deep soil using rotary foam scrubbers and hot water extraction without soaking the backing or causing color bleeding.</p>',
                'eligibility_criteria' => "Industrial Dust Beating\nRotary Foam Extraction\nOdor & Mold Elimination",
                'documents_required' => "Stain Guard Coating\nMoisture-Controlled Drying\nSanitized Packaging",
                'tips_title' => 'Carpet Maintenance Tips',
                'tips_content' => '<p>Vacuum carpets weekly and blot liquid spills immediately with a dry cloth instead of scrubbing.</p>',
                'faqs' => json_encode([
                    ['question' => 'Do you clean carpets on-site or at your facility?', 'answer' => 'We provide both doorstep extraction and pickup & delivery to our specialized carpet washing facility.']
                ]),
                'badge' => 'Deep Clean',
                'summary' => 'Multi-stage dust extraction, foam shampooing, and anti-bacterial sanitization for rugs and carpets.',
                'sort_order' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'curtain-cleaning',
                'service_name' => 'Curtain Cleaning',
                'hero_category' => 'HOME CARE',
                'hero_title' => 'Curtain & Drape Refreshing',
                'hero_subtitle' => 'Steam cleaning, dust extraction, and pleat preservation for sheer, blackout, and velvet curtains.',
                'rate_value' => 'From ₹180 / panel',
                'max_loan' => 'All Fabric Types',
                'tenure' => '2 - 4 Days',
                'intro_title' => 'Breathe Life Into Your Window Drapes',
                'intro_content' => '<p>Drapes collect dust, pollen, and airborne pollutants over time. We provide gentle dry cleaning and steam rejuvenation to restore drape volume and clean air quality in your home.</p>',
                'eligibility_criteria' => "Pleat Preservation\nBlackout Lining Care\nShrinkage Prevention",
                'documents_required' => "Free Take-Down & Re-Hanging\nAnti-Dust Repellent Finish\nWrinkle-Free Steam Pressing",
                'tips_title' => 'Drape Longevity',
                'tips_content' => '<p>Regular steam cleaning extends curtain life and keeps indoor air allergen-free.</p>',
                'faqs' => json_encode([
                    ['question' => 'Do you assist in removing and rehanging heavy curtains?', 'answer' => 'Yes, our team handles take-down and rehanging upon request for a completely hassle-free experience.']
                ]),
                'badge' => 'Home Care',
                'summary' => 'On-hook or off-hook steam cleaning, dust removal, and pleat preservation for drapes and curtains.',
                'sort_order' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($services as $srv) {
            DB::table('services')->insert($srv);
        }

        // 7. Seed CMS Pages
        DB::table('cms_pages')->truncate();
        $cmsPages = [
            [
                'slug' => 'home',
                'title' => 'Tenderfold - Premium Laundry & Dry Cleaner in Gurgaon',
                'description' => '<h1>Freshness in Every Single Fold</h1><p>Doorstep laundry, dry cleaning, steam pressing, shoe spa, and carpet care in Gurgaon.</p>',
                'metatitle' => 'Tenderfold | Freshness in Every Single Fold - Premium Laundry & Dry Cleaner in Gurgaon',
                'metadescription' => 'Tenderfold is a premium laundry and dry cleaner in Gurgaon. Doorstep pickup for wash & fold, steam ironing, dry cleaning and specialized fabric care.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'about-us',
                'title' => 'About Tenderfold Gurgaon',
                'description' => '<h1>About Tenderfold</h1><p>We are a specialized fabric care service built around modern Italian machinery, skin-safe detergents, and prompt doorstep delivery across Gurgaon.</p>',
                'metatitle' => 'About Us | Tenderfold Gurgaon Fabric Care',
                'metadescription' => 'Learn about Tenderfold laundry and dry cleaning philosophy, our plant facilities in Gurgaon, and our commitment to garment longevity.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'services',
                'title' => 'All Laundry & Dry Cleaning Services',
                'description' => '<h1>Our Services</h1><p>From daily wash and fold to luxury couture dry cleaning, discover our full suite of fabric spa solutions.</p>',
                'metatitle' => 'All Services | Tenderfold Gurgaon',
                'metadescription' => 'Explore laundry, dry cleaning, steam pressing, shoe cleaning, curtain care, and carpet spa services in Gurgaon.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'pricing',
                'title' => 'Pricing & Rate Card',
                'description' => '<h1>Transparent Laundry & Dry Cleaning Pricing</h1><p>Affordable per kg laundry and per piece dry cleaning rates with no hidden charges.</p>',
                'metatitle' => 'Pricing & Rate Card | Tenderfold Gurgaon',
                'metadescription' => 'View our complete transparent price list for wash & fold, steam pressing, dry cleaning, shoe cleaning, and carpet care.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'testimonials',
                'title' => 'Customer Reviews & Feedback',
                'description' => '<h1>What Our Gurgaon Customers Say</h1><p>Read genuine testimonials from satisfied households, professionals, and businesses across Gurgaon.</p>',
                'metatitle' => 'Customer Reviews & Testimonials | Tenderfold Gurgaon',
                'metadescription' => 'Read reviews and ratings from our customers in Sector 49, Sector 83, DLF, and across Gurgaon.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'faqs',
                'title' => 'Frequently Asked Questions',
                'description' => '<h1>Frequently Asked Questions</h1><p>Everything you need to know about pickup timings, turnaround, fabric safety, and order tracking.</p>',
                'metatitle' => 'Frequently Asked Questions | Tenderfold Laundry Gurgaon',
                'metadescription' => 'Find answers to common questions about Tenderfold doorstep pickup, dry cleaning turnaround, and specialized fabric care.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'contact-us',
                'title' => 'Contact Tenderfold Gurgaon',
                'description' => '<h1>Contact Us</h1><p>Visit our stores in Sector 49 & Sector 83 or call us for free doorstep collection.</p>',
                'metatitle' => 'Contact Tenderfold | Gurgaon Laundry & Dry Cleaning Counters',
                'metadescription' => 'Call +91 95824 66445 or WhatsApp us for instant doorstep laundry collection in Gurgaon.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'privacy-policy',
                'title' => 'Privacy Policy',
                'description' => '<h1>Privacy Policy</h1><p>Tenderfold respects your personal privacy. We only use customer contact and address details for scheduling pickups, delivering orders, and sending service updates.</p>',
                'metatitle' => 'Privacy Policy | Tenderfold Gurgaon',
                'metadescription' => 'Tenderfold privacy policy regarding customer personal data and communication.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'terms-and-conditions',
                'title' => 'Terms & Conditions',
                'description' => '<h1>Terms & Conditions</h1><p>Standard service terms for garment inspection, pickup schedules, care instructions, and delivery turnaround.</p>',
                'metatitle' => 'Terms & Conditions | Tenderfold Gurgaon',
                'metadescription' => 'Tenderfold terms and conditions of fabric care and doorstep delivery services.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($cmsPages as $page) {
            DB::table('cms_pages')->insert($page);
        }

        // 8. Seed Landing Pages (Synced with Frontend & Admin AI Generator)
        if (Schema::hasTable('landing_pages')) {
            DB::table('landing_pages')->truncate();
            $landingPages = [
                [
                    'title' => 'Doorstep Laundry Pickup in Gurgaon | 20% Off First Order',
                    'slug' => 'doorstep-laundry-pickup-gurgaon',
                    'layout_type' => 'home',
                    'meta_description' => 'Get fresh, hygienic wash & fold and steam ironing collected right from your doorstep in Gurgaon. Special 20% discount on first booking.',
                    'content' => json_encode([
                        'about_title' => 'Gurgaon\'s Most Reliable Doorstep Laundry Service',
                        'about_text' => "Tenderfold delivers 5-star garment care directly to your apartment or home in Gurgaon. We sort by fabric type, wash in 100% softened water with skin-safe eco detergents, and return your clothes folded, fresh, and ready for your wardrobe.",
                        'features' => [
                            ['icon' => 'truck', 'title' => 'Free Doorstep Collection', 'desc' => 'Scheduled pickup and delivery across DLF, Golf Course Rd, Sohna Rd, and all Gurgaon sectors.'],
                            ['icon' => 'layers', 'title' => '100% Segregated Batch Washing', 'desc' => 'Your garments are processed in their own dedicated washing and drying machines.'],
                            ['icon' => 'sparkles', 'title' => 'Skin-Safe Eco Detergents', 'desc' => 'Non-toxic, hypoallergenic German detergents that preserve fibers and colors.'],
                            ['icon' => 'award', 'title' => 'Precision Vacuum Steam Ironing', 'desc' => 'Italian vacuum suction tables ensure crisp, wrinkle-free finishing with zero fabric shine.']
                        ],
                        'faqs' => [
                            ['q' => 'How do I schedule a pickup in Gurgaon?', 'a' => 'Simply submit the form or click our WhatsApp link. Our delivery partner will arrive at your selected slot.'],
                            ['q' => 'What is the rate for everyday laundry?', 'a' => 'Our wash & fold service starts at just ₹80/kg, and wash & steam iron starts at ₹115/kg.'],
                            ['q' => 'Is there a minimum order quantity?', 'a' => 'There is a nominal 3 kg minimum order for free doorstep collection.']
                        ],
                        'cta_title' => 'Experience Freshness in Every Single Fold',
                        'cta_text' => 'Book your doorstep laundry collection in Gurgaon today and enjoy 20% off your initial order.'
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'title' => 'Eco Dry Cleaning & Couture Fabric Spa in Gurgaon',
                    'slug' => 'express-dry-cleaning-gurgaon',
                    'layout_type' => 'service',
                    'meta_description' => 'Premium dry cleaning for designer lehengas, silk sarees, bespoke suits, and winter cashmere in Gurgaon. Zero harsh chemicals.',
                    'content' => json_encode([
                        'service_details_title' => 'Specialized Dry Cleaning for Delicate & Ethnic Couture',
                        'service_details_text' => "Our closed-loop hydrocarbon dry cleaning process protects delicate zari work, bead embroidery, pure silks, and tailored woolens. Every item is hand-inspected for stains and finished with Italian dry steam.",
                        'service_bullets' => [
                            ['icon' => 'shield-check', 'title' => 'Safe for Heavy Embellishments', 'desc' => 'Gentle solvent bath preserves sequins, stone work, and fine metallic threads.'],
                            ['icon' => 'sparkles', 'title' => 'Odorless Hydrocarbon Process', 'desc' => 'Zero chemical smell or stiff fabric texture after cleaning.'],
                            ['icon' => 'clock', 'title' => 'Express 48h Turnaround', 'desc' => 'Prompt inspection and delivery in breathable fabric garment bags.']
                        ],
                        'process_title' => 'The 4-Step Couture Cleaning Protocol',
                        'process_steps' => [
                            ['title' => 'Pre-Inspection & Spot Testing', 'desc' => 'We check care labels, colorfastness, and spot-clean food and oil stains by hand.'],
                            ['title' => 'Hydrocarbon Eco Cleaning', 'desc' => 'Delicate closed-loop wash cycle in temperature-controlled Italian machines.'],
                            ['title' => 'Vacuum Steam Pressing', 'desc' => 'Padded vacuum suction tables restore sharp creases and natural garment volume.'],
                            ['title' => 'Boutique Hanger Packaging', 'desc' => 'Delivered in premium breathable garment bags on contoured hangers.']
                        ],
                        'cta_title' => 'Restore Your Wardrobe With Tenderfold',
                        'cta_text' => 'Schedule a free dry cleaning doorstep collection across Gurgaon today.'
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'title' => 'Premium Shoe Laundry & Luxury Sneaker Spa in Gurgaon',
                    'slug' => 'designer-shoe-spa-gurgaon',
                    'layout_type' => 'specialist',
                    'meta_description' => 'Restore white sneakers, leather boots, and suede loafers. Professional cleaning, sole un-yellowing, and deodorization in Gurgaon.',
                    'content' => json_encode([
                        'specialist_bio_title' => 'Artisanal Footwear Care & Sneaker Restoration',
                        'specialist_bio_text' => "Our dedicated shoe spa craftsmen clean, condition, and deodorize every pair by hand using pH-neutral solutions, horsehair brushes, and UV ozone sterilization.",
                        'specialist_qualifications' => [
                            ['title' => 'Suede & Nubuck Re-Napping Mastery', 'desc' => 'Dry foam cleaning and brass brushing to restore soft suede texture.'],
                            ['title' => 'Sole Oxidation Reversal & De-Yellowing', 'desc' => 'Safe chemical treatments to bring aged yellow boost soles back to pure white.'],
                            ['title' => 'UV Ozone Anti-Bacterial Sanitization', 'desc' => 'Kills 99.9% of odor-causing bacteria inside athletic footwear and leather shoes.']
                        ],
                        'cta_title' => 'Step Out With Confidence',
                        'cta_text' => 'Schedule a sneaker & footwear spa pickup in Gurgaon now.'
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ];

            foreach ($landingPages as $lp) {
                DB::table('landing_pages')->insert($lp);
            }
        }

        // 9. Seed Dynamic Page Contents (Synced with Admin Page Editor)
        if (Schema::hasTable('page_contents')) {
            DB::table('page_contents')->truncate();
            $pageContents = [
                // Header Navigation
                ['page' => 'header', 'section' => 'menu', 'key' => 'home', 'value' => 'Home'],
                ['page' => 'header', 'section' => 'menu', 'key' => 'about', 'value' => 'About Us'],
                ['page' => 'header', 'section' => 'menu', 'key' => 'services', 'value' => 'Services'],
                ['page' => 'header', 'section' => 'menu', 'key' => 'pricing', 'value' => 'Pricing'],
                ['page' => 'header', 'section' => 'menu', 'key' => 'blog', 'value' => 'Care Journal'],
                ['page' => 'header', 'section' => 'menu', 'key' => 'testimonials', 'value' => 'Reviews'],
                ['page' => 'header', 'section' => 'menu', 'key' => 'contact', 'value' => 'Contact'],
                ['page' => 'header', 'section' => 'menu', 'key' => 'cta', 'value' => 'Call Now'],
                ['page' => 'header', 'section' => 'cta', 'key' => 'button_text', 'value' => 'Schedule a Pickup'],

                // Footer Blocks
                ['page' => 'footer', 'section' => 'about', 'key' => 'description', 'value' => 'Tenderfold is Gurgaon\'s trusted premium laundry, dry cleaning and specialized fabric spa. Punctual doorstep pickup and delivery across all major sectors.'],
                ['page' => 'footer', 'section' => 'headings', 'key' => 'quick_links', 'value' => 'Quick Links'],
                ['page' => 'footer', 'section' => 'headings', 'key' => 'services', 'value' => 'Our Services'],
                ['page' => 'footer', 'section' => 'headings', 'key' => 'contact', 'value' => 'Contact & Hubs'],
                ['page' => 'footer', 'section' => 'bottom', 'key' => 'copyright', 'value' => 'All Rights Reserved.'],
                ['page' => 'footer', 'section' => 'bottom', 'key' => 'disclaimer', 'value' => 'Tenderfold provides professional textile and garment care under strict manufacturer guidelines.'],

                // Homepage Hero
                ['page' => 'home', 'section' => 'hero', 'key' => 'eyebrow', 'value' => 'Premium Laundry & Dry Cleaner in Gurgaon'],
                ['page' => 'home', 'section' => 'hero', 'key' => 'title', 'value' => 'Freshness in Every Single Fold.'],
                ['page' => 'home', 'section' => 'hero', 'key' => 'subtitle', 'value' => 'Gurgaon\'s trusted premium laundry, dry cleaning & specialized fabric spa. Free doorstep pickup across all major sectors.'],
                ['page' => 'home', 'section' => 'hero', 'key' => 'trust_1_num', 'value' => '50,000+'],
                ['page' => 'home', 'section' => 'hero', 'key' => 'trust_1_label', 'value' => 'Garments Delivered'],
                ['page' => 'home', 'section' => 'hero', 'key' => 'trust_2_num', 'value' => '100%'],
                ['page' => 'home', 'section' => 'hero', 'key' => 'trust_2_label', 'value' => 'Eco-Friendly Solvents'],
                ['page' => 'home', 'section' => 'hero', 'key' => 'trust_3_num', 'value' => 'Sector 49 & 83'],
                ['page' => 'home', 'section' => 'hero', 'key' => 'trust_3_label', 'value' => 'Gurgaon Hubs'],

                // Homepage About
                ['page' => 'home', 'section' => 'about', 'key' => 'pre_title', 'value' => 'About Tenderfold'],
                ['page' => 'home', 'section' => 'about', 'key' => 'title', 'value' => 'Gurgaon\'s Premier Fabric Care & Garment Spa'],
                ['page' => 'home', 'section' => 'about', 'key' => 'body_p1', 'value' => 'Tenderfold is dedicated to elevating everyday laundry and dry cleaning into a meticulous garment care experience. Operating from physical stores in Sector 49 (Orchid Petals) and Sector 83 (Sapphire 83 Mall), we serve households across Gurgaon.'],
                ['page' => 'home', 'section' => 'about', 'key' => 'body_p2', 'value' => 'From everyday wash & fold to luxury silk preservation, sneaker renewal, and carpet shampooing, our team combines German eco-detergents, Italian vacuum steam finishing, and punctual doorstep pickup.'],

                // Homepage Services Section
                ['page' => 'home', 'section' => 'services', 'key' => 'title', 'value' => 'Comprehensive Garment & Home Fabric Care'],
                ['page' => 'home', 'section' => 'services', 'key' => 'subtitle', 'value' => 'Explore our full spectrum of specialized laundry, couture dry cleaning, and home fabric care services.'],

                // Homepage CTA Section
                ['page' => 'home', 'section' => 'cta', 'key' => 'heading', 'value' => '20% Off Your First Order'],
                ['page' => 'home', 'section' => 'cta', 'key' => 'title', 'value' => 'Experience Tenderfold Care Today'],
                ['page' => 'home', 'section' => 'cta', 'key' => 'subtitle', 'value' => 'Schedule a free doorstep pickup in Gurgaon and enjoy pristine, fresh laundry delivered right to your wardrobe.'],

                // Homepage Why Choose Us
                ['page' => 'home', 'section' => 'why_choose_us', 'key' => 'title', 'value' => '4-Step Quality Assurance Routine'],
                ['page' => 'home', 'section' => 'why_choose_us', 'key' => 'subtitle', 'value' => 'Our systematic textile evaluation ensures hygienic washing, zero fiber shrinkage, and sharp steam finishing.'],

                // About Us Page Sections
                ['page' => 'about', 'section' => 'hero', 'key' => 'pre_title', 'value' => 'WHO WE ARE'],
                ['page' => 'about', 'section' => 'hero', 'key' => 'title', 'value' => 'Crafting Freshness, Preserving Fabrics'],
                ['page' => 'about', 'section' => 'hero', 'key' => 'subtitle', 'value' => 'We are passionate about fabric longevity, environmental safety, and convenient doorstep laundry in Gurgaon.'],
                ['page' => 'about', 'section' => 'story', 'key' => 'pre_title', 'value' => 'Our Story'],
                ['page' => 'about', 'section' => 'story', 'key' => 'title', 'value' => 'Redefining Wardrobe Care in Gurgaon'],
                ['page' => 'about', 'section' => 'story', 'key' => 'p1', 'value' => 'Tenderfold began with a simple belief: laundry should be more than a chore — it should be a reliable care routine that protects the investment you make in your wardrobe.'],
                ['page' => 'about', 'section' => 'story', 'key' => 'p2', 'value' => 'By replacing harsh industrial chemicals with gentle eco solvents, and domestic irons with professional vacuum steam tables, we ensure every garment returns with pristine freshness.'],
                ['page' => 'about', 'section' => 'story', 'key' => 'p3', 'value' => 'Today, our counters in Orchid Petals (Sector 49) and Sapphire 83 Mall (Sector 83) serve thousands of families and professionals with free doorstep pickup.'],
                ['page' => 'about', 'section' => 'vision', 'key' => 'title', 'value' => 'Our Vision'],
                ['page' => 'about', 'section' => 'vision', 'key' => 'text', 'value' => 'To become the most trusted fabric care and laundry brand in Northern India by prioritizing hygiene, fabric safety, and customer convenience.'],
                ['page' => 'about', 'section' => 'mission', 'key' => 'title', 'value' => 'Our Mission'],
                ['page' => 'about', 'section' => 'mission', 'key' => 'text', 'value' => 'To provide eco-friendly, hypoallergenic garment cleaning with transparent per-kilo and per-piece pricing, prompt turnaround, and exceptional customer support.'],
                ['page' => 'about', 'section' => 'values', 'key' => 'title', 'value' => 'Our Core Values'],
                ['page' => 'about', 'section' => 'values', 'key' => 'text', 'value' => 'Absolute hygiene, fiber-safe cleaning, environmental responsibility, and punctual service.'],
            ];

            foreach ($pageContents as $pc) {
                DB::table('page_contents')->updateOrInsert(
                    ['page' => $pc['page'], 'section' => $pc['section'], 'key' => $pc['key']],
                    [
                        'value' => $pc['value'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }

        // 10. Seed Blogs / Care Journal
        DB::table('blogs')->truncate();
        DB::table('blogs')->insert([
            [
                'title' => 'The Complete Guide to Caring for Delicate Silks & Wedding Wear',
                'slug' => 'delicate-silk-care-guide',
                'category' => 'Fabric Care',
                'summary' => 'Discover how to store, protect, and safely dry clean pure silk sarees, embellished lehengas, and embroidered sherwanis to prevent fading and zari tarnishing.',
                'content' => '<p>Pure silks, Banarasi weaves, and heavily embellished wedding wear require gentle handling. In this guide, our master dry cleaners share professional tips on preventing moisture damage, using muslin storage wraps, and choosing eco hydrocarbon dry cleaning over harsh industrial washing.</p><h3>1. Never Use Plastic Bags for Long-Term Storage</h3><p>Plastic traps humidity and accelerates zari oxidation. Always wrap silks in pure cotton or unbleached muslin fabric.</p><h3>2. Professional Spot-Cleaning</h3><p>Avoid rubbing water on silk stains at home, as this creates permanent water rings. Bring the garment to certified dry cleaners immediately.</p>',
                'image_path' => 'assets/shirts.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Why Vacuum Steam Pressing is Superior to Traditional Flat Ironing',
                'slug' => 'vacuum-steam-pressing-benefits',
                'category' => 'Garment Finishing',
                'summary' => 'Learn how Italian vacuum steam tables prevent fabric shine, preserve delicate fibers, and deliver razor-sharp creases on trousers and shirts.',
                'content' => '<p>Conventional domestic flat irons rely on direct metallic heat that crushes fabric fibers and creates an unsightly reflective shine on dark wool and cotton suits. Vacuum steam pressing uses continuous high-pressure steam that permeates the weave while suction removes residual moisture immediately, setting sharp creases without thermal stress.</p>',
                'image_path' => 'assets/whiteshirt.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'How to Keep White Sneakers & Suede Shoes Pristine: Tips from our Shoe Spa',
                'slug' => 'sneaker-cleaning-maintenance-guide',
                'category' => 'Footwear Spa',
                'summary' => 'Expert tips on preventing sole yellowing, maintaining suede texture, and keeping designer sneakers fresh throughout the monsoon and summer.',
                'content' => '<p>Sneakers and designer footwear endure the harshest exposure to dust, grease, and rain. Our shoe spa artisans break down the essential steps for cleaning white mesh, re-napping suede, and applying hydrophobic water-repellent coatings.</p>',
                'image_path' => 'assets/hero.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 11. Seed Blog Categories
        if (Schema::hasTable('blog_categories')) {
            $categories = [
                ['name' => 'Laundry Services', 'slug' => 'laundry-services', 'description' => 'Everyday wash & fold, fabric softness, and daily garment hygiene.'],
                ['name' => 'Dry Cleaning', 'slug' => 'dry-cleaning', 'description' => 'Specialized chemical solvent cleaning for silks, woolens, and suits.'],
                ['name' => 'Steam Pressing', 'slug' => 'steam-pressing', 'description' => 'Vacuum table heated steam pressing for crisp, crease-free garment finishing.'],
                ['name' => 'Shoe Cleaning', 'slug' => 'shoe-cleaning', 'description' => 'Deep cleaning, stain removal, and care for sneakers, leather, and suede shoes.'],
                ['name' => 'Carpet Cleaning', 'slug' => 'carpet-cleaning', 'description' => 'Industrial dust extraction, deep sanitization, and wash for rugs and carpets.'],
                ['name' => 'Curtain Cleaning', 'slug' => 'curtain-cleaning', 'description' => 'Heavy drapery, blackout curtain washing, and dust allergen elimination.'],
            ];

            foreach ($categories as $cat) {
                DB::table('blog_categories')->insertOrIgnore([
                    'name' => $cat['name'],
                    'slug' => $cat['slug'],
                    'description' => $cat['description'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
