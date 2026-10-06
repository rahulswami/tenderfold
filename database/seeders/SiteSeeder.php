<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SiteSeeder extends Seeder
{
    /**
     * Run the database seeds for Tenderfold.
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

        // 2. Seed Site Parameters
        $parameters = [
            ['id' => 'site_name', 'value' => 'Tenderfold', 'label' => 'Site Name', 'category' => 'general'],
            ['id' => 'site_tagline', 'value' => 'Freshness in Every Single Fold - Premium Laundry & Dry Cleaner in Gurugram', 'label' => 'Site Tagline', 'category' => 'general'],
            ['id' => 'meta_description', 'value' => 'Tenderfold is a premium laundry and dry cleaner in Gurugram. Doorstep pickup for wash & fold, steam ironing, dry cleaning and specialized fabric care. Call +91 95824 66445.', 'label' => 'Default Meta Description', 'category' => 'general'],
            ['id' => 'meta_keywords', 'value' => 'laundry gurugram, dry cleaning gurugram, wash and fold, steam ironing, shoe cleaning, carpet cleaning, sector 49, sector 83, tenderfold', 'label' => 'Default Meta Keywords', 'category' => 'general'],
            ['id' => 'google_tag_id', 'value' => null, 'label' => 'Google Tag / GA4 Measurement ID', 'category' => 'general'],
            ['id' => 'google_analytics_id', 'value' => null, 'label' => 'Google Analytics Tracking ID', 'category' => 'general'],
            ['id' => 'header_scripts', 'value' => null, 'label' => 'Custom Header Scripts / CSS', 'category' => 'general'],
            ['id' => 'live_site_url', 'value' => 'https://tenderfold.in', 'label' => 'Live Site URL', 'category' => 'general'],
            
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
            
            // Contact Details
            ['id' => 'phone', 'value' => '+91 95824 66445', 'label' => 'Contact Phone Number', 'category' => 'contact'],
            ['id' => 'phone_display', 'value' => '+91 95824 66445', 'label' => 'Display Phone Number', 'category' => 'contact'],
            ['id' => 'email', 'value' => 'info@tenderfold.in', 'label' => 'Contact Email Address', 'category' => 'contact'],
            ['id' => 'address', 'value' => 'Sector 49 & Sector 83, Gurugram, Haryana - 122018', 'label' => 'Physical Address', 'category' => 'contact'],
            ['id' => 'whatsapp_number', 'value' => '919582466445', 'label' => 'WhatsApp Number (with country code)', 'category' => 'contact'],
            ['id' => 'whatsapp_admin_recipient', 'value' => '919582466445', 'label' => 'WhatsApp Admin Alert Recipient', 'category' => 'contact'],
            
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

        // 3. Seed Homepage Slides
        DB::table('home_slides')->truncate();
        DB::table('home_slides')->insert([
            [
                'title' => 'Freshness in Every Single Fold.',
                'subtitle' => "Gurugram's trusted premium laundry, dry cleaning & specialized fabric spa. Doorstep pickup across all major sectors.",
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
                'subtitle' => 'Sharp creases without fabric stress or shine marks. Delivered fresh on custom hangers.',
                'image_path' => 'assets/whiteshirt.jpg',
                'button_text' => 'View Price List',
                'button_link' => '/pricing',
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 4. Seed Testimonials
        DB::table('testimonials')->truncate();
        DB::table('testimonials')->insert([
            [
                'name' => 'Pooja Narang',
                'role' => 'South City II, Gurugram',
                'content' => 'Tenderfold has completely transformed how our household manages laundry. Their wash & fold is impeccably sorted and packed, and doorstep pickup is always on time!',
                'rating' => 5,
                'avatar_path' => null,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Rohan Mehra',
                'role' => 'Sector 83, Gurugram',
                'content' => "I sent my wedding sherwani and my wife's designer lehenga for dry cleaning. Zero chemical smell, perfect stone detailing preservation, and delivered in custom garment bags.",
                'rating' => 5,
                'avatar_path' => null,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ananya Sharma',
                'role' => 'DLF Phase 5, Gurugram',
                'content' => 'The vacuum steam pressing is boutique level. Every business shirt is returned with crisp collar supports and sharp creases. Highly recommend their weekly pickup routine.',
                'rating' => 5,
                'avatar_path' => null,
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Vikram Sethi',
                'role' => 'Nirvana Country, Sector 50',
                'content' => 'Their shoe spa restored my white leather sneakers and suede boots to like-new condition. The sole de-yellowing and odor elimination worked wonders.',
                'rating' => 5,
                'avatar_path' => null,
                'sort_order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Meenakshi Rao',
                'role' => 'Sohna Road, Gurugram',
                'content' => 'Prompt doorstep pickup, transparent pricing, and gentle fabric care. Tenderfold is definitely the best professional dry cleaner in Gurugram!',
                'rating' => 5,
                'avatar_path' => null,
                'sort_order' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 5. Seed Blogs
        DB::table('blogs')->truncate();
        DB::table('blogs')->insert([
            [
                'slug' => 'delicate-silk-care-guide',
                'title' => 'The Complete Guide to Caring for Delicate Silks & Wedding Wear in Gurugram',
                'category' => 'Fabric Care',
                'summary' => 'Discover how to store, protect, and safely dry clean pure silks, embroidered lehengas, and woolens without fiber breakdown.',
                'content' => '<p>Pure silks, Banarasi weaves, and heavily embellished wedding ensembles represent prized additions to any wardrobe. However, the delicate natural proteins in silk fibers and intricate zari embroidery make them vulnerable to moisture, perfume chemicals, and improper cleaning methods.</p><h3>1. Never Attempt Home Spot Cleaning with Water</h3><p>Water can cause localized ring stains and fiber distortion on dry clean-only silks. If a food or beverage spill occurs during an event, gently blot the excess liquid with a dry, clean tissue without rubbing.</p><h3>2. Why Hydrocarbon Dry Cleaning is Essential</h3><p>Traditional perchlorethylene dry cleaning can sometimes degrade delicate adhesives and metallic sequins. At Tenderfold, we utilize gentle, biodegradable hydrocarbon solvents that remove oil-based grime while keeping fine threads, pearls, and zari lustrous and intact.</p><h3>3. Long-Term Storage Recommendations</h3><p>Always store silks in breathable muslin cloth bags rather than sealed plastic covers. Plastic traps humidity and can cause yellowing and fabric brittleness over time.</p>',
                'image_path' => 'assets/blog1.jpg',
                'published_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'vacuum-steam-pressing-benefits',
                'title' => 'Why Vacuum Steam Pressing is Superior to Traditional Flat Ironing',
                'category' => 'Garment Finishing',
                'summary' => 'Learn how Italian vacuum steam tables prevent fabric shine, eliminate 99.9% of allergens, and create razor-sharp creases.',
                'content' => '<p>Conventional domestic flat irons rely on direct metallic heat to flatten creases. On delicate cottons, linens, and blended suits, this direct heat often crushes fabric fibers, creates unsightly shine marks on dark trousers, and can even burn subtle synthetic blends.</p><h3>The Science of Vacuum Steam Finishing</h3><p>At Tenderfold, we use commercial Italian vacuum steam tables. High-volume, dry steam penetrates deep through garment fibers to relax tension naturally. Simultaneously, powerful vacuum tables draw excess moisture out of the cloth instantly, setting a crisp, durable crease without direct scorching heat.</p><h3>Key Benefits for Your Wardrobe:</h3><ul><li><strong>Zero Shine or Glaze:</strong> Perfect for navy, black, and charcoal formal trousers and blazers.</li><li><strong>Sanitization:</strong> High-temperature steam neutralizes odor-causing bacteria and dust mites.</li><li><strong>Collar & Cuff Precision:</strong> Delicate shirts receive custom form-fitting finishing with cardboard supports.</li></ul>',
                'image_path' => 'assets/blog2.jpg',
                'published_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'sneaker-cleaning-maintenance-guide',
                'title' => 'How to Keep White Sneakers & Suede Shoes Pristine: Tips from Footwear Spa Specialists',
                'category' => 'Footwear Spa',
                'summary' => 'Expert tips on preventing sole yellowing, maintaining suede texture, and professional UV ozone sanitization.',
                'content' => '<p>Sneakers and designer footwear endure the harshest exposure to Gurugram dust, rain, and pavement grime. Regular maintenance not only keeps your kicks looking fresh but also prevents premature sole cracking and upper separation.</p><h3>1. Avoid Domestic Washing Machines</h3><p>Submerging sneakers in domestic washing machines dissolves sole adhesives, degrades internal cushioning foam, and warps leather shapes. Footwear requires specialized dry foam and pH-neutral hand brushing.</p><h3>2. Sole De-Yellowing & Oxidation Reversal</h3><p>White rubber and boost soles naturally turn yellow over time due to UV exposure and oxidation. Our specialized footwear spa applies advanced de-oxidizing treatments under controlled UV chambers to restore original factory brightness.</p><h3>3. Suede & Nubuck Grooming</h3><p>Suede must never come into contact with water. We utilize specialized brass and crepe brushes alongside water-repellent conditioning sprays to protect delicate nap fibers against future rain and dust.</p>',
                'image_path' => 'assets/blog3.jpg',
                'published_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 6. Seed Dynamic Services
        DB::table('services')->truncate();
        
        $services = [
            [
                'slug' => 'laundry-services',
                'service_name' => 'Laundry Services',
                'hero_category' => 'DAILY ESSENTIALS',
                'hero_title' => 'Everyday Laundry, Elevated Care',
                'hero_subtitle' => 'Wash, gentle dry & precise fold for your daily wardrobes, linens, and activewear in Gurugram.',
                'rate_value' => 'From ₹80 / kg',
                'max_loan' => 'Weight or Garment',
                'tenure' => '24 - 48 Hours',
                'intro_title' => 'Hygienic Washing with Fabric-Safe Care',
                'intro_content' => '<p>Our everyday laundry service treats every load with individual wash drums, skin-safe eco-detergents, and precise temperature control. Clothes are gently tumble dried and crisply folded or hung according to your preference.</p>',
                'eligibility_criteria' => "Individual Batch Washing\nZero Cross-Contamination\nSkin-Safe Hypoallergenic Detergents",
                'documents_required' => "Free Doorstep Pickup across Gurugram\nCustom Starch & Softener options\nNeat Barcoded Packaging",
                'tips_title' => 'Tips for Daily Laundry',
                'tips_content' => '<p>Separate delicates from heavy towels, and let our pickup executives know of any stubborn food or ink stains beforehand.</p>',
                'faqs' => json_encode([
                    ['question' => 'Do you wash my clothes with other customers?', 'answer' => 'Never. Every order is processed in its own dedicated wash drum to maintain absolute hygiene.'],
                    ['question' => 'What is the standard turnaround time?', 'answer' => 'Standard wash & fold is delivered within 24 to 48 hours. Express same-day service is also available upon request.']
                ]),
                'badge' => 'Popular',
                'summary' => 'Wash, gentle dry & crisp fold for your daily wear, bedsheets, towels, and activewear.',
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'dry-cleaning',
                'service_name' => 'Dry Cleaning',
                'hero_category' => 'COUTURE & DELICATES',
                'hero_title' => 'Eco-Friendly Dry Cleaning in Gurugram',
                'hero_subtitle' => 'Specialized solvent care for suits, silks, lehengas, woolens, and designer couture.',
                'rate_value' => 'From ₹130 / pc',
                'max_loan' => 'Garment Inspection Included',
                'tenure' => '48 - 72 Hours',
                'intro_title' => 'Zero-Odor Fabric-Safe Dry Cleaning',
                'intro_content' => '<p>Our state-of-the-art dry cleaning plant uses gentle, biodegradable hydrocarbon solvents that preserve delicate fibers, embroidery, and colors while effectively removing oil-based stains and grime.</p>',
                'eligibility_criteria' => "Color Fastness Testing\nManual Spot Treatment\nAnti-Moth Treatment Included",
                'documents_required' => "Suit Covers & Premium Hangers\nSilk & Embroidery Protection\nButtons & Seam Inspection",
                'tips_title' => 'Dry Cleaning Best Practices',
                'tips_content' => '<p>Do not attempt home water spot cleaning on dry clean-only silk or wool fabrics as it may set the stain.</p>',
                'faqs' => json_encode([
                    ['question' => 'Is your dry cleaning process safe for heavy bridal wear?', 'answer' => 'Yes, our master spotters inspect every stone, sequin, and thread before customized delicate treatment.'],
                    ['question' => 'Do clothes smell of chemical solvents?', 'answer' => 'No. Our modern closed-loop machines ensure 100% odor-free, fresh garments.']
                ]),
                'badge' => 'Premium',
                'summary' => 'Gentle solvent care for suits, silks, sherwanis, woolens, and delicate couture fabrics.',
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'ironing',
                'service_name' => 'Steam Pressing',
                'hero_category' => 'PRECISION FINISH',
                'hero_title' => 'Commercial Vacuum Steam Ironing',
                'hero_subtitle' => 'Razor-sharp creases and smooth finishes without fabric shine, burn marks, or heat damage.',
                'rate_value' => 'From ₹19 / pc',
                'max_loan' => 'Steam Tables & Form Finishers',
                'tenure' => 'Same Day / 24 Hours',
                'intro_title' => 'Boutique-Grade Steam Finishing',
                'intro_content' => '<p>We utilize industrial Italian vacuum steam tables and dummy form finishers to relax fibers and produce crisp, long-lasting creases without the scorching risk of traditional hot flat irons.</p>',
                'eligibility_criteria' => "Zero Direct Scorching Heat\nVacuum Moisture Extraction\nAnti-Static Conditioning",
                'documents_required' => "Cardboard Collar Supports\nDelivered on Wire/Wood Hangers\nBreathable Garment Wraps",
                'tips_title' => 'Steam Pressing Advantages',
                'tips_content' => '<p>Steam penetrates deep into garment fibers, removing wrinkles while eliminating 99.9% of bacteria and allergens.</p>',
                'faqs' => json_encode([
                    ['question' => 'Do you provide hanger delivery for pressed shirts?', 'answer' => 'Yes, shirts and trousers are delivered on hangers or neatly folded in protective covers per your preference.']
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
                'title' => 'Tenderfold - Premium Laundry & Dry Cleaner in Gurugram',
                'content' => '<h1>Freshness in Every Single Fold</h1><p>Doorstep laundry, dry cleaning, steam pressing, shoe spa, and carpet care in Gurugram.</p>',
                'metatitle' => 'Tenderfold | Freshness in Every Single Fold - Premium Laundry & Dry Cleaner in Gurugram',
                'metadescription' => 'Tenderfold is a premium laundry and dry cleaner in Gurugram. Doorstep pickup for wash & fold, steam ironing, dry cleaning and specialized fabric care.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'about-us',
                'title' => 'About Tenderfold Gurugram',
                'content' => '<h1>About Tenderfold</h1><p>We are a specialized fabric care service built around modern Italian machinery, skin-safe detergents, and prompt doorstep delivery across Gurugram.</p>',
                'metatitle' => 'About Us | Tenderfold Gurugram Fabric Care',
                'metadescription' => 'Learn about Tenderfold laundry and dry cleaning philosophy, our plant facilities in Gurugram, and our commitment to garment longevity.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'services',
                'title' => 'All Laundry & Dry Cleaning Services',
                'content' => '<h1>Our Services</h1><p>From daily wash and fold to luxury couture dry cleaning, discover our full suite of fabric spa solutions.</p>',
                'metatitle' => 'All Services | Tenderfold Gurugram',
                'metadescription' => 'Explore laundry, dry cleaning, steam pressing, shoe cleaning, curtain care, and carpet spa services in Gurugram.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'pricing',
                'title' => 'Pricing & Rate Card',
                'content' => '<h1>Transparent Laundry & Dry Cleaning Pricing</h1><p>Affordable per kg laundry and per piece dry cleaning rates with no hidden charges.</p>',
                'metatitle' => 'Pricing & Rate Card | Tenderfold Gurugram',
                'metadescription' => 'View our complete transparent price list for wash & fold, steam pressing, dry cleaning, shoe cleaning, and carpet care.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'testimonials',
                'title' => 'Customer Reviews & Feedback',
                'content' => '<h1>What Our Gurugram Customers Say</h1><p>Read genuine testimonials from satisfied households, professionals, and businesses across Gurugram.</p>',
                'metatitle' => 'Customer Reviews & Testimonials | Tenderfold Gurugram',
                'metadescription' => 'Read reviews and ratings from our customers in Sector 49, Sector 83, DLF, and across Gurugram.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'faqs',
                'title' => 'Frequently Asked Questions',
                'content' => '<h1>Frequently Asked Questions</h1><p>Everything you need to know about pickup timings, turnaround, fabric safety, and order tracking.</p>',
                'metatitle' => 'Frequently Asked Questions | Tenderfold Laundry Gurugram',
                'metadescription' => 'Find answers to common questions about Tenderfold doorstep pickup, dry cleaning turnaround, and specialized fabric care.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'contact-us',
                'title' => 'Contact Tenderfold Gurugram',
                'content' => '<h1>Contact Us</h1><p>Visit our stores in Sector 49 & Sector 83 or call us for free doorstep collection.</p>',
                'metatitle' => 'Contact Tenderfold | Gurugram Laundry & Dry Cleaning Counters',
                'metadescription' => 'Call +91 95824 66445 or WhatsApp us for instant doorstep laundry collection in Gurugram.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'privacy-policy',
                'title' => 'Privacy Policy',
                'content' => '<h1>Privacy Policy</h1><p>Tenderfold respects your personal privacy. We only use customer contact and address details for scheduling pickups, delivering orders, and sending service updates.</p>',
                'metatitle' => 'Privacy Policy | Tenderfold Gurugram',
                'metadescription' => 'Tenderfold privacy policy regarding customer personal data and communication.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'terms-and-conditions',
                'title' => 'Terms & Conditions',
                'content' => '<h1>Terms & Conditions</h1><p>Standard service terms for garment inspection, pickup schedules, care instructions, and delivery turnaround.</p>',
                'metatitle' => 'Terms & Conditions | Tenderfold Gurugram',
                'metadescription' => 'Tenderfold terms and conditions of fabric care and doorstep delivery services.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($cmsPages as $page) {
            DB::table('cms_pages')->insert($page);
        }
    }
}
