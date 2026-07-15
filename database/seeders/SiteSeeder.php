<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Admin User
        DB::table('users')->updateOrInsert(
            ['email' => 'admin@mlgfinedge.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // 2. Seed Site Parameters (including backgrounds)
        $parameters = [
            ['id' => 'site_name', 'value' => 'MLG FINEDGE', 'label' => 'Site Name', 'category' => 'general'],
            ['id' => 'site_tagline', 'value' => 'Your Prosperity, Our Priority', 'label' => 'Site Tagline', 'category' => 'general'],
            ['id' => 'google_tag_id', 'value' => null, 'label' => 'Google Tag ID', 'category' => 'general'],
            ['id' => 'header_scripts', 'value' => null, 'label' => 'Custom Header Scripts', 'category' => 'general'],
            ['id' => 'logo_path', 'value' => null, 'label' => 'Custom Logo Image (WebP)', 'category' => 'branding'],
            ['id' => 'favicon_path', 'value' => null, 'label' => 'Custom Favicon Image (WebP)', 'category' => 'branding'],
            ['id' => 'chat_assistant_name', 'value' => 'Clinic Assistant', 'label' => 'Chat Assistant Display Name', 'category' => 'general'],
            ['id' => 'chat_assistant_avatar', 'value' => null, 'label' => 'Chat Assistant Avatar Image (WebP)', 'category' => 'branding'],
            ['id' => 'recaptcha_enabled', 'value' => '0', 'label' => 'Enable Google reCAPTCHA', 'category' => 'general'],
            ['id' => 'recaptcha_site_key', 'value' => null, 'label' => 'reCAPTCHA Site Key', 'category' => 'general'],
            ['id' => 'recaptcha_secret_key', 'value' => null, 'label' => 'reCAPTCHA Secret Key', 'category' => 'general'],
            ['id' => 'phone', 'value' => '+91 96727 77749', 'label' => 'Contact Phone Number', 'category' => 'contact'],
            ['id' => 'email', 'value' => 'info@mlgfinedge.com', 'label' => 'Contact Email Address', 'category' => 'contact'],
            ['id' => 'address', 'value' => 'B-13 A, 10 B Scheme, Gopalpura Byepass, Behind The Park Classic, Jaipur - 302018', 'label' => 'Physical Address', 'category' => 'contact'],
            ['id' => 'whatsapp_number', 'value' => '919672777749', 'label' => 'WhatsApp Number (with country code)', 'category' => 'contact'],
            
            // Starting Interest Rates
            ['id' => 'home_loan_rate', 'value' => '8.4%', 'label' => 'Home Loan Starting Rate', 'category' => 'rates'],
            ['id' => 'personal_loan_rate', 'value' => '10.5%', 'label' => 'Personal Loan Starting Rate', 'category' => 'rates'],
            ['id' => 'business_loan_rate', 'value' => '12.0%', 'label' => 'Business Loan Starting Rate', 'category' => 'rates'],
            ['id' => 'car_loan_rate', 'value' => '8.7%', 'label' => 'Car Loan Starting Rate', 'category' => 'rates'],
            ['id' => 'lap_rate', 'value' => '9.0%', 'label' => 'Loan Against Property Starting Rate', 'category' => 'rates'],

            // Custom Section Backgrounds
            ['id' => 'index_hero_bg', 'value' => 'linear-gradient(135deg, #eef7f7 0%, #ffffff 100%)', 'label' => 'Home Hero Section Background', 'category' => 'styling'],
            ['id' => 'index_services_bg', 'value' => '#ffffff', 'label' => 'Home Services Section Background', 'category' => 'styling'],
            ['id' => 'index_about_bg', 'value' => '#111b1c', 'label' => 'Home About Section Background', 'category' => 'styling'],
            ['id' => 'index_why_bg', 'value' => '#ffffff', 'label' => 'Home Why Choose Us Background', 'category' => 'styling'],
            ['id' => 'index_testimonials_bg', 'value' => '#f4f8f8', 'label' => 'Home Testimonials Background', 'category' => 'styling'],
            ['id' => 'index_faq_bg', 'value' => '#ffffff', 'label' => 'Home FAQ Background', 'category' => 'styling'],
            ['id' => 'index_cta_bg', 'value' => 'linear-gradient(135deg, #0c5354 0%, #135859 100%)', 'label' => 'Home CTA Section Background', 'category' => 'styling'],
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

        // 3. Seed Page Contents
        $contents = [
            // Homepage Hero
            ['page' => 'home', 'section' => 'hero', 'key' => 'title', 'value' => 'Trusted Loan Provider in Gopalpura Jaipur'],
            ['page' => 'home', 'section' => 'hero', 'key' => 'subtitle', 'value' => 'Your Trusted Partner for Smart Loan Solutions, Quick Assistance, and Reliable Financial Guidance.'],
            
            // Homepage Services
            ['page' => 'home', 'section' => 'services', 'key' => 'title', 'value' => 'Find the Right Loan for Your Needs'],
            ['page' => 'home', 'section' => 'services', 'key' => 'subtitle', 'value' => 'We work as your advisory partner to match you with top-tier lenders. Here are the tailor-made lending solutions we consult on:'],
            
            // Homepage About
            ['page' => 'home', 'section' => 'about', 'key' => 'pre_title', 'value' => 'Your Trusted Loan Advisory Partner'],
            ['page' => 'home', 'section' => 'about', 'key' => 'title', 'value' => 'MLG Finedge: Simplifying Credit for Jaipur'],
            ['page' => 'home', 'section' => 'about', 'key' => 'body_p1', 'value' => "MLG Finedge helps individuals and businesses navigate India's complex credit markets. Rather than acting as a direct lender, we serve as your independent consultant, comparing loan schemes from leading nationalized banks, private financial entities, and NBFCs."],
            ['page' => 'home', 'section' => 'about', 'key' => 'body_p2', 'value' => 'Our goal is to ensure you get the maximum loan amount eligibility at the lowest possible interest rate with the least amount of processing hassle.'],
            
            // Homepage Why Choose Us
            ['page' => 'home', 'section' => 'why_choose_us', 'key' => 'title', 'value' => 'Simplifying Your Loan Journey'],
            ['page' => 'home', 'section' => 'why_choose_us', 'key' => 'subtitle', 'value' => 'We combine deep expertise with strong banking relationships to bring you unmatched credit advisory services.'],
        ];

        DB::table('page_contents')->truncate();
        foreach ($contents as $content) {
            DB::table('page_contents')->insert([
                'page' => $content['page'],
                'section' => $content['section'],
                'key' => $content['key'],
                'value' => $content['value'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 4. Seed Homepage Slides
        DB::table('home_slides')->truncate();
        DB::table('home_slides')->insert([
            [
                'title' => 'Trusted Loan Provider in Gopalpura Jaipur',
                'subtitle' => 'Your Trusted Partner for Smart Loan Solutions, Quick Assistance, and Reliable Financial Guidance.',
                'image_path' => null,
                'button_text' => 'Get Free Consultation',
                'button_link' => '#consultation',
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Government Employees Special Loan Scheme',
                'subtitle' => 'Maturity up to 63 years & higher eligibility on 55% FOIR constraints. Custom options tailored for you.',
                'image_path' => null,
                'button_text' => 'Apply Online Now',
                'button_link' => '/contact',
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 5. Seed Testimonials
        DB::table('testimonials')->truncate();
        DB::table('testimonials')->insert([
            [
                'name' => 'Vikram Sharma',
                'role' => 'Government Teacher, Jaipur',
                'content' => 'As a government teacher in Gopalpura, I had unique eligibility challenges. MLG Finedge got me a home loan with 63-year extended maturity options and high FOIR margins. Excellent work!',
                'rating' => 5,
                'avatar_path' => null,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pooja Khandelwal',
                'role' => 'Boutique Owner, Jaipur',
                'content' => 'We needed urgent working capital for our textile boutique. MLG Finedge structured our files, compared three NBFC offers, and got us ₹45 Lakhs approved without any collateral.',
                'rating' => 5,
                'avatar_path' => null,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Abhishek Khurana',
                'role' => 'IT Professional, Jaipur',
                'content' => 'We were buying our first house in Vaishali Nagar and the builder recommended MLG Finedge. They helped us understand multiple home loan packages and secured a low 8.4% interest rate fast. Very satisfied!',
                'rating' => 5,
                'avatar_path' => null,
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dr. Sunita Gupta',
                'role' => 'Clinic Director, Gopalpura',
                'content' => 'MLG Finedge helped me secure an unsecured Business Loan of ₹30 Lakhs for expanding my healthcare diagnostics clinic. They managed the document collection and bank interactions smoothly. Professional service.',
                'rating' => 5,
                'avatar_path' => null,
                'sort_order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Rajesh Joshi',
                'role' => 'Retailer, Jaipur',
                'content' => 'I highly recommend MLG Finedge for Loan Against Property (LAP). They checked three separate bank valuations for my commercial shop in Malviya Nagar and negotiated a low processing fee. Efficient team!',
                'rating' => 4,
                'avatar_path' => null,
                'sort_order' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 6. Seed Blogs
        DB::table('blogs')->truncate();
        DB::table('blogs')->insert([
            [
                'slug' => 'personal-loans-guide',
                'title' => 'The Ultimate Guide to Securing a Personal Loan in Jaipur',
                'category' => 'Personal Loans',
                'summary' => 'Learn how to improve your net eligibility, check required documents, and get lower rates from banks in Gopalpura, Jaipur.',
                'content' => '<h3>Key Criteria Lenders Check in Jaipur</h3>\n<p>Unsecured personal loans have seen a massive surge in demand in urban centers. When searching for a <strong>personal loan lender near me</strong> or checking out options for <strong>private finance near me</strong> in Jaipur, borrowers often get overwhelmed by options and complex terms.</p>\n<p>A personal loan is unsecured, meaning you do not have to pledge residential property, land titles, or vehicles to raise capital. However, this means banks evaluate your financial dossier strictly. A minor error in salary declarations or a mismatch in credit scores can lead to immediate rejection.</p>\n<p>Before any loan agency submits your file to underwriting, they evaluate three primary parameters:</p>\n<ul>\n    <li><strong>CIBIL Score:</strong> A CIBIL rating of 750+ ensures you get processed at the lowest interest rate margins. Scores below 650 are rarely approved by nationalized banks, requiring NBFC channels instead.</li>\n    <li><strong>Income Stability:</strong> For salaried employees in Gopalpura Jaipur, banks expect a minimum net salary of ₹20k to ₹25k, deposited directly into your bank account. Cash salaries are typically not accepted.</li>\n    <li><strong>Employer Categorization:</strong> Lenders categorize employers into Category A, B, or C. Government teachers and employees of public undertakings get prioritized with fast approvals.</li>\n</ul>\n<h3>Why Consulting a Reputed Loan Agency is Better</h3>\n<p>Many borrowers visit bank branches individually. However, this raises multiple hard inquiries on your credit history, lowering your CIBIL score. Consulting an independent <strong>loan agency</strong> like MLG Finedge helps you compare rates across HDFC, ICICI, SBI, and Axis simultaneously, ensuring you select the finest offer with a single file compilation.</p>',
                'image_path' => null,
                'published_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'home-loan-tips',
                'title' => 'Home Loans: 5 Crucial Tips for First-Time Buyers in Jaipur',
                'category' => 'Home Loans',
                'summary' => 'Avoid critical layout blueprint issues and discover special extended maturity schemes for state government teachers in Rajasthan.',
                'content' => '<p>Jaipur\'s residential real estate market is expanding rapidly, with Gopalpura, Vaishali Nagar, and Jagatpura emerging as hot destinations. If you are browsing for a <strong>home loan near me</strong> or comparing <strong>mortgage lenders near me</strong>, taking the first step requires careful preparation.</p>\n<p>Securing <strong>house loans near me</strong> is a long-term commitment. Interest rate differences of even 0.25% can translate into savings of lakhs of rupees over a 20-year tenure. Here are five crucial guidelines for home buyers:</p>\n<h3>1. Check Your Credit Parameters First</h3>\n<p>Before any of the <strong>home lenders near me</strong> pull your credit file, download your credit report. Ensure your CIBIL rating is above 720. If there are credit card defaults or late payments, resolve them first to prevent banks from raising your interest margins.</p>\n<h3>2. Plan Your Budget and FOIR</h3>\n<p>Fixed Obligation to Income Ratio (FOIR) measures how much of your salary is used to clear existing EMIs. Lenders typically cap this at 50%. However, if you are a state or central government employee in Rajasthan (such as government school teachers), select banks offer relaxed limits (up to 55% or 60% FOIR) and extended maturity tenures up to 63 years.</p>\n<h3>3. Floating vs. Fixed Rates</h3>\n<p>Floating interest rates (pegged to Repo Linked Lending Rates or RLLR) fluctuate based on RBI policy. They are highly popular because floating loans have zero pre-payment or foreclosure charges under RBI guidelines. Fixed rates remain constant but carry penalties if you attempt foreclosure early.</p>\n<h3>4. Verify Local JDA Map Approvals</h3>\n<p>Jaipur banks check if properties have JDA (Jaipur Development Authority) layout approvals. Purchasing properties on agriculture titles without conversion (90A status) can lead to instant rejection.</p>\n<h3>5. Work with a Trusted Advisory Partner</h3>\n<p>Rather than walking into multiple bank branches, work with an independent consultancy like MLG Finedge. We check rates across 150+ partner lenders simultaneously, ensuring you receive the lowest interest rates without multiple hard inquiries.</p>',
                'image_path' => null,
                'published_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 7. Seed Dynamic Services
        DB::table('services')->truncate();
        
        $services = [
            [
                'slug' => 'personal-loan',
                'service_name' => 'Personal Loan',
                'hero_category' => 'UNSECURED CREDIT',
                'hero_title' => 'Personal Loan Advisory in Jaipur',
                'hero_subtitle' => 'Quick, collateral-free financial solutions designed for government teachers, salaried professionals, and business owners in Gopalpura and across Jaipur.',
                'rate_value' => 'Starting @ 10.5% p.a.',
                'max_loan' => 'Up to ₹40 Lakhs',
                'tenure' => '1 - 7 Years',
                'intro_title' => 'Seamless Personal Loan Consultation in Jaipur',
                'intro_content' => '<p>Personal loans provide a highly flexible way to raise cash for wedding expenses, medical emergencies, higher education, or consolidating existing high-interest credit card debt. Because these loans are unsecured, banks do not require you to pledge any land titles, jewelry, or property documents. However, this means underwriting parameters are strictly focused on creditworthiness.</p><p>At <strong>MLG Finedge</strong>, we guide you through the entire process. Rather than applying to multiple banks and triggering hard CIBIL inquiries that lower your score, we analyze your financial profile beforehand. We compile your documents, check eligibility thresholds, and position your application with leading partner lenders (such as HDFC, ICICI, SBI, Axis Bank, and top-tier NBFCs) to secure the lowest possible interest rate margins.</p>',
                'eligibility_criteria' => "Salaried Minimum Income: ₹25,000 net/month\nAge Range: 21 to 60 Years\nCredit Score: CIBIL 720+ preferred\nWork Experience: 1 Year (min 6 months with current employer)",
                'documents_required' => "Identity Proof: PAN Card & Aadhaar Card\nIncome Proof: Last 3 Months Salary Slips\nBank History: Last 6 Months Salary Account Statements\nTax Proof: Form 16 / ITR filings for past 2 years",
                'tips_title' => 'How to Optimize Your Personal Loan Application',
                'tips_content' => '<p>To ensure your file gets approved quickly and at the lowest possible interest rate, consider the following parameters:</p><ul style="padding-left: 20px; line-height: 1.8; margin-bottom: 2rem;"><li><strong>Employer Categorization:</strong> Lenders classify employers into categories (e.g., Cat A, Cat B, Cat C). Employees of government bodies, public sector undertakings, and major corporate brands receive immediate prioritization and lower rate offers.</li><li><strong>FOIR (Fixed Obligation to Income Ratio):</strong> This measures how much of your monthly income goes toward existing debts. Keeping this ratio below 40% ensures banks feel confident in your repayment capability.</li><li><strong>Advisory Support:</strong> Navigating bank structures alone can be difficult. Working with MLG Finedge gives you an expert advocate who knows exactly how to position your file to matching underwriters.</li></ul>',
                'faqs' => json_encode([
                    ['question' => 'How fast can I get a personal loan disbursed?', 'answer' => 'Once the document packet is complete and verified, bank approvals typically take 24 to 48 hours. Payouts are made directly to your savings bank account shortly after signing the loan agreement.'],
                    ['question' => 'Can I close my personal loan early?', 'answer' => 'Yes, most banks allow prepayment or foreclosure after 12 successful EMIs. Some banks charge foreclosure penalties (2% to 4% of outstanding balance), while others offer zero-charge terms. We will ensure you select a bank with flexible prepayment terms.'],
                    ['question' => 'Does applying for a personal loan affect my CIBIL score?', 'answer' => 'When you apply directly to multiple lenders, each bank performs a hard inquiry on your report, which can lower your score. By consulting with MLG Finedge, we compare rates and perform only a single targeted submission, preserving your credit standing.']
                ]),
                'badge' => 'Popular',
                'summary' => 'Quick cash options up to ₹40 Lakhs with minimum paperwork. Perfect for medical bills, education, vacations, and marriages.',
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'home-loan',
                'service_name' => 'Home Loan',
                'hero_category' => 'MORTGAGE FINANCE',
                'hero_title' => 'Home Loan Solutions in Jaipur',
                'hero_subtitle' => 'Purchase or construct your dream house in Gopalpura, Vaishali Nagar, and Jagatpura with lowest interest rates, high eligibility margins, and simple bank approvals.',
                'rate_value' => 'Starting @ 8.4% p.a.',
                'max_loan' => 'Up to ₹10 Crore',
                'tenure' => 'Up to 30 Years',
                'intro_title' => 'Secure Your Home Loan with Independent Consultation',
                'intro_content' => '<p>Buying a home is one of the most significant lifetime financial commitments you will make. While minor interest differences like 0.25% may seem negligible at first, they can translate into savings of lakhs of rupees over a 20-year or 30-year repayment tenure. However, navigating the housing finance market individually often leads to frustration, high processing fees, and multiple credit report checks that lower your CIBIL standing.</p><p>At <strong>MLG Finedge</strong>, we serve as your independent consultant. We analyze interest rates (pegged to Repo Linked Lending Rates or RLLR), processing parameters, and hidden charges across 150+ leading nationalized banks, private sector institutions, and housing finance companies (HFCs). We also specialize in structuring profiles with unique criteria, such as extended maturity tenures up to 63 years for Rajasthan state government employees and school teachers.</p>',
                'eligibility_criteria' => "Salaried Minimum Income: ₹20,000 net/month\nSelf-Employed: Minimum 3 Years business stability\nCredit standing: CIBIL score 700+ preferred\nFOIR constraints: Flexible limits up to 55-60%",
                'documents_required' => "PAN Card, Aadhaar Card, & Photos\nLast 6 Months salary bank statements\nSalary slips & Form 16 / ITR documents\nProperty chain documents & layout map",
                'tips_title' => 'Crucial Tips for Home Buyers in Jaipur',
                'tips_content' => '<p>When purchasing a property, ensuring your funding is approved requires verifying specific legal and bank credit parameters:</p><ul style="padding-left: 20px; line-height: 1.8; margin-bottom: 2rem;"><li><strong>JDA Layout Approvals:</strong> Lenders in Jaipur strictly check if the property has JDA (Jaipur Development Authority) layout approvals. Buying agricultural titles without proper conversion (90A status) can lead to immediate file rejections by top nationalized banks.</li><li><strong>Floating vs. Fixed Interest Rates:</strong> Floating rate loans are highly popular because they are pegged directly to the RBI Repo Rate (RLLR) and carry zero pre-payment or foreclosure charges under RBI guidelines.</li><li><strong>Co-Applicants:</strong> Adding a female co-applicant (like a spouse or mother) can help you qualify for lower interest rate margins and register properties with lower stamp duty fees in Rajasthan.</li></ul>',
                'faqs' => json_encode([
                    ['question' => 'What is the maximum Loan-to-Value (LTV) ratio allowed?', 'answer' => 'Under RBI guidelines, banks can finance up to 90% of the property value for loans up to ₹30 Lakhs. For loans between ₹30 Lakhs and ₹75 Lakhs, the LTV is capped at 80%. For high-value loans above ₹75 Lakhs, the maximum LTV is 75%.'],
                    ['question' => 'What is the difference between RLLR and MCLR?', 'answer' => 'RLLR (Repo Linked Lending Rate) is linked directly to the RBI\'s repo rate, meaning interest adjustments happen immediately when the central bank changes rates. MCLR (Marginal Cost of Funds based Lending Rate) is determined internally by banks and adjusts only at specific intervals (usually once a year).'],
                    ['question' => 'Can I get a home loan for a property on a Gram Panchayat patta?', 'answer' => 'Yes, but nationalized banks typically avoid Gram Panchayat properties. In these cases, we route your file through specific NBFCs and private housing finance institutions that accept Gram Panchayat titles with a valid structural map approval.']
                ]),
                'badge' => 'Top Service',
                'summary' => 'Finance up to 90% of property cost. First-time buyer schemes and dedicated support for government employee profiles.',
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'car-loan',
                'service_name' => 'Car Loan',
                'hero_category' => 'VEHICLE FINANCE',
                'hero_title' => 'Car Loan Consultation in Jaipur',
                'hero_subtitle' => 'Finance your next vehicle with competitive interest margins, quick document processing, and flexible repayment tenures for new and used cars.',
                'rate_value' => 'Starting @ 8.7% p.a.',
                'max_loan' => 'Up to 100% On-Road',
                'tenure' => '1 - 7 Years',
                'intro_title' => 'Streamlined Car Loan Advisory Services in Jaipur',
                'intro_content' => '<p>Buying a car represents an exciting milestone, but choosing the right auto financing package can be confusing. Lenders offer various rate margins, processing fees, and foreclosure conditions that affect the total cost of ownership. Navigating dealer-end financing can also result in hidden dealer commissions being bundled into your loan package.</p><p>At <strong>MLG Finedge</strong>, we help you bypass the retail markup. We analyze financing options across leading nationalized banks, private banks, and NBFC channels to bring you low-interest auto loans. We consult on both **new car financing** (with up to 100% on-road funding options) and **used car financing** (with higher LTV structures and flexible tenures), ensuring you drive home with complete peace of mind.</p>',
                'eligibility_criteria' => "Salaried Minimum Income: ₹20,000 net/month\nSelf-Employed: Minimum 2 Years business proof\nAge Limit: 21 to 65 Years\nCredit standing: CIBIL score 700+ preferred",
                'documents_required' => "PAN Card, Aadhaar Card, & Photos\nLast 6 Months bank statements\nLast 3 Months salary slips / 2 Years ITR filings\nCar proforma invoice (for new cars) or valuation report (for used cars)",
                'tips_title' => 'Key Advantages of Bank-Backed Car Loans',
                'tips_content' => '<p>Choosing bank-backed car loans offers several financial advantages:</p><ul style="padding-left: 20px; line-height: 1.8; margin-bottom: 2rem;"><li><strong>Up to 100% Funding:</strong> Some private and public sector banks offer 100% on-road price funding for select car models and premium profiles, minimizing your out-of-pocket costs.</li><li><strong>Used Car Rates:</strong> Used car loans typically carry higher rates than new cars. We assist in negotiating valuation multiples to keep rates as close to new car brackets as possible.</li><li><strong>No Hidden Insurance Costs:</strong> Dealerships often bundle expensive car insurance plans. We help you choose external insurance or negotiate dealer quotes, saving you thousands on your upfront payment.</li></ul>',
                'faqs' => json_encode([
                    ['question' => 'Can I get a used car loan for a vehicle that is 8 years old?', 'answer' => 'Most banks limit used car financing to vehicles that are less than 5 to 7 years old at the time of loan application, and specify that the vehicle\'s age plus the loan tenure should not exceed 10 years.'],
                    ['question' => 'Is a guarantor required for a car loan?', 'answer' => 'Generally, a guarantor is not required if the applicant satisfies the bank\'s income and credit score criteria. A co-applicant or guarantor may be requested if your score is low or income thresholds are borderline.'],
                    ['question' => 'How is used car valuation calculated?', 'answer' => 'Banks send an independent surveyor to inspect the vehicle\'s condition, manufacture year, mileage, and registry history. The loan amount is capped at 80% to 90% of the value determined by the surveyor.']
                ]),
                'badge' => null,
                'summary' => 'Finance options up to 100% on-road price for select vehicles. Attractive rate margins, flexi EMI, and instant support.',
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'business-loan',
                'service_name' => 'Business Loan',
                'hero_category' => 'COMMERCIAL CREDIT',
                'hero_title' => 'Business Loan Advisory in Jaipur',
                'hero_subtitle' => 'Fuel your enterprise growth, buy equipment, and manage stock flow with collateral-free business financing options up to ₹75 Lakhs.',
                'rate_value' => 'Starting @ 12.0% p.a.',
                'max_loan' => 'Up to ₹75 Lakhs',
                'tenure' => '1 - 5 Years',
                'intro_title' => 'Accelerate Business Growth with Collateral-Free Loans',
                'intro_content' => '<p>Maintaining positive cash flow and seizing immediate expansion opportunities is crucial for SMEs in Jaipur\'s competitive markets. Whether you need to invest in new manufacturing equipment, secure seasonal raw inventory, or scale up marketing setups, waiting months for secured property financing can halt your business momentum.</p><p>At <strong>MLG Finedge</strong>, we specialize in structuring unsecured **SME Business Loans**. We work directly with credit managers at leading private banks, nationalized banks, and corporate NBFCs to evaluate your financials. We help present your business turnover, tax filings, and account dynamics in the best possible light, ensuring rapid approvals with minimum processing friction and competitive interest rates.</p>',
                'eligibility_criteria' => "Business Standing: Minimum 3 Years active operation\nAnnual Turnover: Minimum ₹20 Lakhs audited sales\nTax Record: ITR filings for past 2 Years min\nCredit Standing: Personal & Business score 700+",
                'documents_required' => "KYC: PAN Card, Aadhaar Card, & GST Registration\nBank Statements: Main Current Account statements (last 12 mos)\nAudited Financials: Balance sheet & P&L (if loan > ₹15L)\nRent Agreement / Business Location Ownership Proof",
                'tips_title' => 'Tips for SME Business Loan Approvals',
                'tips_content' => '<p>Lenders evaluate corporate credit profiles differently than retail applications. Optimize your approval chances by monitoring these critical points:</p><ul style="padding-left: 20px; line-height: 1.8; margin-bottom: 2rem;"><li><strong>Avoid Banking Rejections:</strong> Frequent bounced cheques, outward ECS returns, or running close to overdraft limits can lead to instant rejections by automated banking credit algorithms. We review your account trends beforehand.</li><li><strong>Highlight Stable GST Filings:</strong> Consistent, sequential GST filing history indicates regular business operational activity, giving underwriters strong confidence in your monthly cash flow stability.</li><li><strong>Choose Custom Tenures:</strong> Select a repayment tenure (1 to 5 years) that balances your projected monthly operations, ensuring EMI payments do not restrict your day-to-day capital reserves.</li></ul>',
                'faqs' => json_encode([
                    ['question' => 'What is the maximum loan amount available without collateral?', 'answer' => 'Unsecured business loans are generally capped at ₹50 Lakhs to ₹75 Lakhs by private sector banks. If your enterprise requires higher funding limits, we can explore secured Loan Against Property or Project Funding structures.'],
                    ['question' => 'Can I get a business loan for a new startup?', 'answer' => 'Standard unsecured business loans require at least 2 to 3 years of operations with active tax filings. For brand new startups, we can assist in exploring government-supported programs like CGTMSE or Mudra Loans through nationalized banking partners.'],
                    ['question' => 'Are interest rates for business loans fixed or floating?', 'answer' => 'Most unsecured business loans are processed on a fixed interest rate schedule. This allows you to plan your monthly budget accurately with predictable EMI outlays over the entire tenure.']
                ]),
                'badge' => 'SMEs',
                'summary' => 'Unsecured commercial funding for machinery purchase, business expansion, stock creation, or hiring personnel.',
                'sort_order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'working-capital',
                'service_name' => 'Working Capital',
                'hero_category' => 'OPERATIONAL CAPITAL',
                'hero_title' => 'Working Capital & CC Limits in Jaipur',
                'hero_subtitle' => 'Optimize enterprise liquidity, manage seasonal inventory demands, and balance payment cycles with flexible Cash Credit (CC) and Overdraft (OD) limits.',
                'rate_value' => 'Highly Competitive Rates',
                'max_loan' => 'CC / OD Overdrafts',
                'tenure' => 'Yearly Renewable limits',
                'intro_title' => 'Optimize Cash Flow with Structured Working Capital Advisory',
                'intro_content' => '<p>Even highly profitable businesses can face operational roadblocks due to locked-up cash in unpaid customer bills, slow-moving seasonal stock, or upfront supplier requirements. A standard term loan provides a fixed lump sum that may not fit your shifting monthly capital needs, resulting in unnecessary interest costs during low-utilization periods.</p><p>At <strong>MLG Finedge</strong>, we help structure flexible **Working Capital facilities**, including **Cash Credit (CC) limits** and **secured/unsecured Overdraft (OD) lines**. These facilities allow you to draw funds as needed and pay interest only on the exact amount used and the number of days utilized. We analyze your debtor-creditor cycles, stock turnovers, and bank profiles to secure maximum limits at the lowest possible margins from top banking institutions.</p>',
                'eligibility_criteria' => "Business Standing: Minimum 3 Years active operation\nAnnual Turnover: Minimum ₹50 Lakhs audited sales\nCollateral Option: Residential/Commercial property / Bank FD\nFinancial standing: Positive net worth, stable current ratio",
                'documents_required' => "GST registration & GST filings (last 12 months)\nAudited Financial Reports (P&L, Balance Sheet, Audit report for 3 Years)\nBank statements of all business accounts (last 12 months)\nProperty chain papers (for secured CC/OD facilities)",
                'tips_title' => 'Key Advantages of Cash Credit & Overdraft Limits',
                'tips_content' => '<p>Utilizing cash credit lines offers significant financial flexibility:</p><ul style="padding-left: 20px; line-height: 1.8; margin-bottom: 2rem;"><li><strong>Interest on Utilization:</strong> Unlike term loans, interest is charged only on the daily closing balance of the amount you actually spend, saving substantial interest costs.</li><li><strong>Yearly Renewals:</strong> Working capital limits are renewed annually based on a review of your business turnover, current account transactions, and financial health.</li><li><strong>Easy Payouts:</strong> Once approved, the CC limit functions like a current account with cheque book and internet banking features, allowing you to pay suppliers instantly.</li></ul>',
                'faqs' => json_encode([
                    ['question' => 'What is the difference between CC (Cash Credit) and OD (Overdraft)?', 'answer' => 'Cash Credit is generally granted against the hypothecation of business stock and debtors (receivables). Overdraft is a limit granted against a secured asset, such as a property mortgage or fixed deposits.'],
                    ['question' => 'How is the drawing power (DP) calculated for CC?', 'answer' => 'Drawing Power is calculated monthly based on your paid stock and debtors list, minus your creditors. Banks require you to submit monthly stock statements to determine how much of your approved CC limit you can draw.'],
                    ['question' => 'Can we get an unsecured overdraft limit?', 'answer' => 'Yes, select private banks offer unsecured overdraft limits (up to ₹30 Lakhs to ₹50 Lakhs) for premium business profiles with strong, audited turnover history.']
                ]),
                'badge' => null,
                'summary' => 'Maintain consistent operational cash flow, pay invoices, clear tax obligations, and balance seasonal cycles.',
                'sort_order' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'loan-against-property',
                'service_name' => 'Loan Against Property',
                'hero_category' => 'SECURED LIQUIDITY',
                'hero_title' => 'Loan Against Property (LAP) in Jaipur',
                'hero_subtitle' => 'Unlock the equity locked in your commercial shops, residential houses, or industrial plots to get high-value funding at low interest rates.',
                'rate_value' => 'Starting @ 9.0% p.a.',
                'max_loan' => 'Up to 75% Market Value',
                'tenure' => 'Up to 15 Years',
                'intro_title' => 'Unlock Financial Value with Loan Against Property Advisory',
                'intro_content' => '<p>When your enterprise or personal needs require substantial, long-term capital, unsecured options may carry high interest rates or short repayment tenures that place a heavy monthly burden on your finances. A Loan Against Property (LAP) allows you to mortgage your residential property, commercial showroom, or industrial land to raise funds, keeping interest outlays light.</p><p>At <strong>MLG Finedge</strong>, we guide you through the property mortgage process. Because banks evaluate property chains, local registry approvals, and valuation metrics strictly, compiling a clean application packet is crucial. We compare valuation standards and credit parameters across 150+ partner lenders to ensure you secure the maximum loan eligibility at the lowest possible rates.</p>',
                'eligibility_criteria' => "Property Ownership: Valid, clear property chain titles\nIncome Stability: Salaried or self-employed with tax records\nAge Range: 21 to 65 Years\nCredit standing: CIBIL score 700+ preferred",
                'documents_required' => "Identity/Address Proof (PAN, Aadhaar)\nOriginal property title chain records & registry copy\nJDA approved layout map / construction blueprint\nLast 6 Months bank statements & 2 Years ITR / Form 16",
                'tips_title' => 'Crucial Guidelines for LAP Approvals',
                'tips_content' => '<p>Securing a property mortgage requires verifying specific legal and bank credit parameters:</p><ul style="padding-left: 20px; line-height: 1.8; margin-bottom: 2rem;"><li><strong>Clear Property Chain:</strong> Banks verify the historical registry documents (minimum 13 to 30 years chain). Any missing linkage in previous sale deeds can delay or block approvals.</li><li><strong>Approved Layout:</strong> Properties built without approved local layout maps (like JDA approvals) may be rejected by nationalized banks, requiring specific private lenders instead.</li><li><strong>End-Use Declaration:</strong> While LAP is flexible, banks expect you to declare the end-use (e.g. business expansion, education) to ensure funds are not used for speculative trading.</li></ul>',
                'faqs' => json_encode([
                    ['question' => 'Can I get a loan against agricultural land in Jaipur?', 'answer' => 'Standard Loans Against Property are granted only against commercial, residential, or industrial converted properties. For agricultural land, we assist in routing files through dedicated agricultural credit schemes.'],
                    ['question' => 'What is the maximum LTV allowed for LAP?', 'answer' => 'Lenders can finance up to 60% to 70% of the market value for residential properties, and up to 50% to 60% for commercial properties, subject to technical valuation reports.'],
                    ['question' => 'Can I continue using my property while mortgaged?', 'answer' => 'Yes, you retain full operational ownership and usage of the property. The bank only holds the original registry papers as security, which are returned upon complete loan closure.']
                ]),
                'badge' => 'Secured',
                'summary' => 'Mortgage your property to raise high-value capital. Lower interest rates compared to unsecured personal options.',
                'sort_order' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'project-funding',
                'service_name' => 'Project Funding',
                'hero_category' => 'CORPORATE SYNDICATION',
                'hero_title' => 'Project Funding Advisory in Jaipur',
                'hero_subtitle' => 'Secure multi-crore capital structures for large-scale real estate, manufacturing units, and commercial setups with structured banking syndication.',
                'rate_value' => 'Structured Term Loans & Equity',
                'max_loan' => 'Industrial & Commercial Projects',
                'tenure' => 'Multi-Crore limits',
                'intro_title' => 'Structured Corporate Debt & Project Funding Advisory',
                'intro_content' => '<p>Large-scale commercial projects—such as constructing JDA-approved residential townships, setting up manufacturing factories, or building commercial shopping malls—require custom financial structures. A standard retail commercial loan cannot meet these complex capital requirements, making dedicated financial syndication and structured term loans necessary.</p><p>At <strong>MLG Finedge</strong>, we guide corporate builders, industrial companies, and entrepreneurs through project finance. We assist in preparing detailed project reports (DPRs), calculating debt-service coverage ratios (DSCR), and structuring consortia or multiple-banking matchings. We present your project\'s viability to senior credit committees at nationalized banks, private banks, and corporate investment funds to secure competitive multi-crore limits.</p>',
                'eligibility_criteria' => "Developer Track Record: Proven commercial construction history\nClear approvals: Land title conversions, fire, environmental NOCs\nFinancial Standing: Minimum 3 Years audited accounts\nEquity contribution: Minimum 30% developer margin",
                'documents_required' => "Detailed Project Report (DPR) & CMA Data projection\nLand deeds, registry, conversions & JDA blueprints\nKYC: Company incorporation, GST, & audits (3 Years)\nCopy of local builder registrations (e.g. RERA in Rajasthan)",
                'tips_title' => 'Key Steps in Project Funding Syndication',
                'tips_content' => '<p>Securing multi-crore project financing requires a structured approach. We assist you with each step:</p><ul style="padding-left: 20px; line-height: 1.8; margin-bottom: 2rem;"><li><strong>Viability Assessment:</strong> We analyze cash flow projections, debt coverage, and cost structures to verify the project\'s financial feasibility.</li><li><strong>CMA Data & DPR Preparation:</strong> We prepare Credit Monitoring Arrangement (CMA) data and comprehensive project reports aligned with current underwriting standards.</li><li><strong>Bank Matching & Syndication:</strong> We route your project file to matching institutions, coordinating meetings with credit heads to secure timely approvals.</li></ul>',
                'faqs' => json_encode([
                    ['question' => 'What is RERA registration, and is it mandatory?', 'answer' => 'RERA (Real Estate Regulatory Authority) registration is mandatory under Indian law for all residential and commercial real estate projects where the total land area exceeds 500 square meters or the number of apartments exceeds eight. Banks require a valid RERA registration before releasing construction finance limits.'],
                    ['question' => 'What is the debt-to-equity ratio expected by banks?', 'answer' => 'Lenders generally expect a debt-to-equity ratio of 2:1 or 1.5:1. This means the promoter or developer should contribute at least 30% to 40% of the total project cost as equity margin before banking debt is released.'],
                    ['question' => 'Do you assist with Letter of Credit (LC) and Bank Guarantees (BG)?', 'answer' => 'Yes, we structure both fund-based limits (term loans, working capital) and non-fund-based limits (Letter of Credit, Bank Guarantees) to support equipment purchasing, tender bidding, and supplier payments.']
                ]),
                'badge' => null,
                'summary' => 'Custom funding advisory for large-scale real estate, infrastructure, and manufacturing projects.',
                'sort_order' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'gold-loan',
                'service_name' => 'Gold Loan',
                'hero_category' => 'INSTANT LIQUIDITY',
                'hero_title' => 'Gold Loan Consultation in Jaipur',
                'hero_subtitle' => 'Get quick, same-day payouts against your physical gold ornaments with minimum documentation, low interest rates, and zero prepayment charges.',
                'rate_value' => 'Starting @ 9.2% p.a.',
                'max_loan' => 'Approval: Within 60 Minutes',
                'tenure' => 'Secure Bank Locker Storage',
                'intro_title' => 'Access Instant Cash with Low-Interest Gold Loans',
                'intro_content' => '<p>When you encounter a sudden business emergency, immediate raw stock requirement, or personal cash flow gap, waiting for credit history checks and home valuation approvals can delay your plans. A gold loan allows you to unlock cash using your gold jewelry, bypassing traditional credit checks.</p><p>At <strong>MLG Finedge</strong>, we guide you to secure, low-interest gold loans. Rather than visiting local pawn brokers who charge high interest rates, we connect you with leading nationalized banks and private lenders. This ensures your assets are evaluated accurately, stored in secure bank lockers, and financed at competitive rates under RBI guidelines.</p>',
                'eligibility_criteria' => "Asset Quality: 18 to 22 Karat gold jewelry\nAge Range: 18 to 70 Years\nIncome Proof: Not mandatory for most loans\nCredit Score: Low CIBIL profiles accepted",
                'documents_required' => "PAN Card & Aadhaar Card\nValid Address Proof\nPassport size photos\nOriginal gold ornaments (for bank evaluation)",
                'tips_title' => 'Key Advantages of Bank-Backed Gold Loans',
                'tips_content' => '<p>Choosing bank-backed gold loans offers several financial advantages:</p><ul style="padding-left: 20px; line-height: 1.8; margin-bottom: 2rem;"><li><strong>Flexible Repayment:</strong> Banks offer various repayment structures, including monthly EMI, interest-only payments (bullet repayment), or pay-on-maturity options.</li><li><strong>LTV up to 75%:</strong> Under RBI regulations, banks can finance up to 75% of the appraised gold market value, ensuring you get maximum value for your assets.</li><li><strong>Asset Safety:</strong> Your jewelry is stored in secure, insured bank lockers and returned to you in its original condition upon full loan closure.</li></ul>',
                'faqs' => json_encode([
                    ['question' => 'What happens during the gold appraisal process?', 'answer' => 'An authorized bank valuer weighs and tests the purity of your gold ornaments (excluding any stone weight) in your presence. The loan limit is determined based on the purity and current market price of the gold.'],
                    ['question' => 'Can I pay off my gold loan early without charges?', 'answer' => 'Yes, bank-backed gold loans generally allow early repayment or foreclosure without any prepayment charges, allowing you to redeem your jewelry as soon as funds are available.'],
                    ['question' => 'What happens if I delay my payments?', 'answer' => 'Banks provide grace periods and send notifications if payments are delayed. If a borrower defaults continuously over long periods, the bank may auction the gold to recover the outstanding balance under RBI guidelines.']
                ]),
                'badge' => null,
                'summary' => 'Secure instant funding against your physical gold ornaments with minimum documentation and fast payouts.',
                'sort_order' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        foreach ($services as $service) {
            DB::table('services')->updateOrInsert(
                ['slug' => $service['slug']],
                $service
            );
        }

        // 8. Seed Comparison Lenders (Banks)
        DB::table('comparison_banks')->truncate();
        
        $banks = [
            ['name' => 'State Bank of India (SBI)', 'home_rate' => 7.25, 'personal_rate' => 10.00, 'business_rate' => 11.20, 'fee_percent' => 0.35, 'sector' => 'Public', 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'HDFC Bank', 'home_rate' => 7.75, 'personal_rate' => 9.99, 'business_rate' => 11.90, 'fee_percent' => 0.50, 'sector' => 'Private', 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'ICICI Bank', 'home_rate' => 7.50, 'personal_rate' => 9.99, 'business_rate' => 11.75, 'fee_percent' => 0.50, 'sector' => 'Private', 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Axis Bank', 'home_rate' => 8.00, 'personal_rate' => 9.99, 'business_rate' => 12.00, 'fee_percent' => 0.50, 'sector' => 'Private', 'sort_order' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bank of Baroda (BoB)', 'home_rate' => 7.20, 'personal_rate' => 10.15, 'business_rate' => 11.50, 'fee_percent' => 0.35, 'sector' => 'Public', 'sort_order' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Punjab National Bank (PNB)', 'home_rate' => 7.20, 'personal_rate' => 10.25, 'business_rate' => 11.40, 'fee_percent' => 0.35, 'sector' => 'Public', 'sort_order' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kotak Mahindra Bank', 'home_rate' => 7.40, 'personal_rate' => 10.25, 'business_rate' => 11.80, 'fee_percent' => 0.40, 'sector' => 'Private', 'sort_order' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Union Bank of India', 'home_rate' => 7.25, 'personal_rate' => 10.40, 'business_rate' => 11.35, 'fee_percent' => 0.30, 'sector' => 'Public', 'sort_order' => 8, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'IDFC First Bank', 'home_rate' => 7.75, 'personal_rate' => 10.49, 'business_rate' => 12.25, 'fee_percent' => 0.50, 'sector' => 'Private', 'sort_order' => 9, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'IndusInd Bank', 'home_rate' => 7.95, 'personal_rate' => 10.25, 'business_rate' => 12.00, 'fee_percent' => 0.50, 'sector' => 'Private', 'sort_order' => 10, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('comparison_banks')->insert($banks);
    }
}
