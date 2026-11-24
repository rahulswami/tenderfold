<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $content = '<p class="text-sm font-semibold text-mute" style="margin-bottom: 1.5rem;"><strong>Last Updated:</strong> November 24, 2026</p><h2>1. Introduction</h2><p>Welcome to TenderFold. We value your privacy and are committed to protecting your personal information. This Privacy Policy explains how we collect, use, store and protect your information when you visit our website, use our laundry services, schedule a pickup, or contact us.</p><p>By using our website and services, you agree to the practices described in this Privacy Policy.</p><h2>2. Information We Collect</h2><p>We may collect the following information when you interact with our website or use our services:</p><ul><li><strong>Personal Information:</strong> Your name, phone number, email address and other contact details.</li><li><strong>Address Information:</strong> Pickup and delivery addresses provided when booking our laundry services.</li><li><strong>Service Information:</strong> Details about your laundry requirements, service preferences, pickup and delivery schedules, and order-related information.</li><li><strong>Payment Information:</strong> Payment details or transaction information necessary to process payments. Payment information may be processed securely by third-party payment providers.</li><li><strong>Technical Information:</strong> IP address, browser type, device information, website usage, cookies and other technical data collected when you visit our website.</li></ul><h2>3. How We Use Your Information</h2><p>We use your information for the following purposes:</p><ul><li>To provide laundry, cleaning, pickup and delivery services.</li><li>To schedule and manage pickup and delivery appointments.</li><li>To communicate with you regarding orders, bookings and service updates.</li><li>To respond to your enquiries, feedback and customer support requests.</li><li>To process payments and maintain transaction records.</li><li>To improve our website, services and customer experience.</li><li>To maintain website security and prevent fraudulent or unauthorized activities.</li><li>To comply with applicable laws and legal obligations.</li></ul><h2>4. Sharing of Information</h2><p>We do not sell or rent your personal information to third parties.</p><p>We may share your information with trusted service providers when necessary to operate our business and provide our services, including:</p><ul><li>Pickup and delivery personnel or logistics service providers.</li><li>Payment processing providers.</li><li>Website hosting, maintenance and technical service providers.</li><li>Communication service providers for customer notifications and support.</li><li>Government authorities or legal agencies when required by applicable law.</li></ul><p>These third parties may access your information only to the extent necessary to perform their respective services or comply with legal obligations.</p><h2>5. Cookies and Website Tracking</h2><p>Our website may use cookies and similar technologies to improve website functionality, understand visitor behaviour and enhance your browsing experience.</p><p>Cookies may help us:</p><ul><li>Understand how visitors use our website.</li><li>Improve website performance and functionality.</li><li>Remember certain user preferences.</li><li>Analyse website traffic and usage patterns.</li></ul><p>You can manage or disable cookies through your browser settings. Disabling cookies may affect certain website functionalities.</p><h2>6. Data Security</h2><p>We take reasonable technical and organizational measures to protect your personal information against unauthorized access, misuse, loss, alteration or disclosure.</p><p>However, no method of transmitting information over the internet or storing electronic data is completely secure. While we strive to protect your information, we cannot guarantee absolute security.</p><h2>7. Data Retention</h2><p>We retain personal information only for as long as necessary to fulfil the purposes for which it was collected, provide our services, maintain business records, resolve disputes and comply with applicable legal obligations.</p><p>When information is no longer required, we may delete it or securely dispose of it, subject to applicable laws.</p><h2>8. Your Privacy Rights</h2><p>You may contact us regarding your personal information to:</p><ul><li>Request information about the personal data we hold about you.</li><li>Request correction or updating of inaccurate information.</li><li>Request deletion of your personal information, where applicable.</li><li>Withdraw consent for processing where processing is based on your consent.</li><li>Raise concerns or complaints regarding the use of your personal information.</li></ul><p>We will review and respond to such requests in accordance with applicable laws.</p><h2>9. Third-Party Websites</h2><p>Our website may contain links to third-party websites or services. We are not responsible for the privacy practices, security or content of third-party websites.</p><p>We encourage you to review the privacy policies of any third-party website you visit.</p><h2>10. Children\'s Privacy</h2><p>Our website and services are not specifically intended to collect personal information directly from children without appropriate consent or authorization as required under applicable law.</p><p>If we become aware that personal information has been collected inappropriately, we will take reasonable steps to address the matter in accordance with applicable laws.</p><h2>11. Changes to This Privacy Policy</h2><p>We may update this Privacy Policy from time to time to reflect changes in our business, services, website functionality or applicable legal requirements.</p><p>Any updates will be published on this page along with the revised date.</p><h2>12. Contact Us</h2><p>If you have any questions, concerns or requests regarding this Privacy Policy or the handling of your personal information, you may contact us:</p><p><strong>TenderFold</strong></p><p><strong>Website:</strong> <a href="https://tenderfold.in/" target="_blank" rel="noopener">https://tenderfold.in/</a></p><div class="address-grid"><div class="address-card"><div class="address-title">📍 Sector 49 (Orchid Petals)</div><div class="address-text">Shop No. 19, 1st Floor, B Block Shopping Plaza, Orchid Petals, South City II, Sector 49, Gurgaon - 122018</div></div><div class="address-card"><div class="address-title">📍 Sector 83 (Sapphire 83 Mall)</div><div class="address-text">F-193, First Floor, Sapphire 83 Mall, Sector 83, Gurgaon - 122004</div></div></div><p style="margin-top: 1rem;">We will make reasonable efforts to address your privacy-related queries and concerns in a timely manner.</p>';

        if (DB::getSchemaBuilder()->hasTable('cms_pages')) {
            $hasContentCol = DB::getSchemaBuilder()->hasColumn('cms_pages', 'content');
            $exists = DB::table('cms_pages')->where('slug', 'privacy-policy')->first();
            $data = [
                'title' => 'Privacy Policy',
                'metatitle' => 'Privacy Policy | Tenderfold Gurgaon',
                'metadescription' => 'Read TenderFold\'s Privacy Policy. Learn how we collect, use, store and protect your personal information when using our laundry and dry cleaning services in Gurgaon.',
                'description' => $content,
                'status' => true,
                'updated_at' => now(),
            ];
            if ($hasContentCol) {
                $data['content'] = $content;
            }

            if ($exists) {
                DB::table('cms_pages')->where('slug', 'privacy-policy')->update($data);
            } else {
                $data['slug'] = 'privacy-policy';
                $data['created_at'] = now();
                DB::table('cms_pages')->insert($data);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Keep page intact or no-op
    }
};
