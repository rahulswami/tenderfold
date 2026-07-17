@extends('layouts.app')

@section('title', $landingPage->title . ' - ' . ($site['site_name'] ?? 'SumitZ Clinic'))
@section('meta_description', $landingPage->meta_description ?? 'Book a consultation at SumitZ Orthopaedic & Physiotherapy Clinic in Baner, Pune.')


@section('schema')
    @if(!empty($landingPage->schema_markup))
        {!! $landingPage->schema_markup !!}
    @endif
@endsection

@section('content')
<style>
    .landing-hero {
        padding: 5rem 0;
        background: linear-gradient(135deg, var(--primary-teal) 0%, var(--primary-teal-dark) 100%);
        color: #ffffff;
        text-align: center;
        position: relative;
    }
    .landing-hero h1 {
        font-size: 2.75rem;
        font-weight: 800;
        margin-bottom: 1.25rem;
        line-height: 1.2;
    }
    .landing-hero p {
        font-size: 1.15rem;
        color: var(--text-light-muted);
        max-width: 750px;
        margin: 0 auto 2rem auto;
    }
    .badge-lp {
        background-color: var(--mint-green);
        color: var(--primary-teal-dark);
        padding: 0.35rem 0.85rem;
        font-weight: 700;
        font-size: 0.75rem;
        border-radius: 50px;
        text-transform: uppercase;
        letter-spacing: 1px;
        display: inline-block;
        margin-bottom: 1rem;
    }
    .landing-grid {
        display: grid;
        grid-template-columns: 1.4fr 1fr;
        gap: 3rem;
        align-items: start;
        margin-top: 3rem;
    }
    @media (max-width: 991px) {
        .landing-grid {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
    }
    .lead-form-card {
        background: #ffffff;
        border: 1px solid var(--border-light);
        border-radius: 12px;
        padding: 2.5rem;
        box-shadow: 0 10px 30px rgba(12, 83, 84, 0.05);
    }
    .lead-form-card h3 {
        color: var(--primary-teal-dark);
        font-size: 1.4rem;
        margin-bottom: 0.5rem;
    }
    .lead-form-card p {
        color: var(--text-muted);
        font-size: 0.9rem;
        margin-bottom: 1.5rem;
    }
    .feature-card-lp {
        background: #ffffff;
        border: 1px solid var(--border-light);
        padding: 1.5rem;
        border-radius: var(--radius-md);
        transition: transform 0.3s, box-shadow 0.3s;
    }
    .feature-card-lp:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 24px rgba(12, 83, 84, 0.06);
    }
    .icon-lp-wrap {
        width: 48px;
        height: 48px;
        border-radius: 8px;
        background: rgba(92, 203, 179, 0.1);
        color: var(--primary-teal);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.25rem;
    }
    .icon-lp-wrap i {
        width: 24px;
        height: 24px;
    }
    .faq-item-lp {
        background: #ffffff;
        border: 1px solid var(--border-light);
        border-radius: var(--radius-md);
        margin-bottom: 1rem;
        overflow: hidden;
    }
    .faq-q-lp {
        padding: 1.25rem 1.5rem;
        font-weight: 600;
        color: var(--primary-teal-dark);
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        user-select: none;
    }
    .faq-a-lp {
        padding: 0 1.5rem 1.25rem 1.5rem;
        color: var(--text-muted);
        font-size: 0.925rem;
        line-height: 1.6;
        display: none;
    }
    .benefit-icon-lp {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: var(--primary-teal-light);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
    }
</style>

<!-- Hero Section -->
<section class="landing-hero">
    <div class="container">
        <span class="badge-lp">PROMOTIONAL OFFER</span>
        <h1>{{ $landingPage->content['hero_title'] ?? $landingPage->title }}</h1>
        <p>{{ $landingPage->content['hero_subtitle'] ?? 'Compare custom loan structures, analyze hidden margins, and secure capital under the guidance of credit consultants.' }}</p>
        <a href="#lead-form-section" class="btn btn-secondary">Get Pre-Approved Options</a>
    </div>
</section>

<!-- Content Grid -->
<div class="container section">
    <div class="landing-grid">
        
        <!-- Left Column: Layout Specific Sections -->
        <div class="left-column">
            
            @if($landingPage->layout_type === 'home')
                <!-- HOME LAYOUT SECTIONS -->
                
                <!-- Features Grid -->
                <div class="features-wrapper">
                    <span class="pre-title">Core Benefits</span>
                    <h2>Exclusive Advantages & Matchmaking</h2>
                    
                    <div class="why-grid" style="grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); margin-top: 1.5rem; margin-bottom: 3rem;">
                        @if(!empty($landingPage->content['features']))
                            @foreach($landingPage->content['features'] as $feat)
                                <div class="feature-card-lp">
                                    <div class="icon-lp-wrap">
                                        <i data-lucide="{{ $feat['icon'] ?? 'shield' }}"></i>
                                    </div>
                                    <h3 style="margin-bottom: 0.5rem; font-size: 1.15rem; color: var(--primary-teal-dark);">{{ $feat['title'] ?? 'Feature Title' }}</h3>
                                    <p style="font-size: 0.875rem; color: var(--text-muted); line-height: 1.5;">{{ $feat['desc'] ?? 'Feature description text' }}</p>
                                </div>
                            @endforeach
                        @else
                            <!-- Fallbacks if empty -->
                            <div class="feature-card-lp">
                                <div class="icon-lp-wrap"><i data-lucide="shield-check"></i></div>
                                <h3 style="margin-bottom: 0.5rem; font-size: 1.15rem; color: var(--primary-teal-dark);">Opaque Charge Filters</h3>
                                <p style="font-size: 0.875rem; color: var(--text-muted); line-height: 1.5;">We scan and eliminate hidden penalties, compound rates, and administration fees.</p>
                            </div>
                            <div class="feature-card-lp">
                                <div class="icon-lp-wrap"><i data-lucide="percent"></i></div>
                                <h3 style="margin-bottom: 0.5rem; font-size: 1.15rem; color: var(--primary-teal-dark);">Optimized Interest Match</h3>
                                <p style="font-size: 0.875rem; color: var(--text-muted); line-height: 1.5;">Comparing 15+ prime lenders to find the cheapest rate fitting your credit score.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- About section -->
                <div style="margin-bottom: 3rem;">
                    <span class="pre-title">How We Help</span>
                    <h2>{{ $landingPage->content['about_title'] ?? 'Strategic Loan Selection & Support' }}</h2>
                    <p style="margin-top: 1rem; line-height: 1.7; color: var(--text-muted);">
                        {!! nl2br(e($landingPage->content['about_text'] ?? (($site['site_name'] ?? 'MLG Finedge') . " is a dedicated credit brokerage partner in Jaipur. We assist with preparing credit files, clearing bank flags, and securing faster loan processing payouts directly with underwriting officers."))) !!}
                    </p>
                </div>

                <!-- FAQs Section -->
                <div style="margin-bottom: 2rem;">
                    <span class="pre-title">Questions & Answers</span>
                    <h2>Frequently Asked Questions</h2>
                    
                    <div style="margin-top: 1.5rem;">
                        @if(!empty($landingPage->content['faqs']))
                            @foreach($landingPage->content['faqs'] as $index => $faq)
                                <div class="faq-item-lp">
                                    <div class="faq-q-lp" onclick="toggleFaqLp({{ $index }})">
                                        <span>{{ $faq['q'] }}</span>
                                        <i data-lucide="plus" id="faq-icon-lp-{{ $index }}"></i>
                                    </div>
                                    <div class="faq-a-lp" id="faq-a-lp-{{ $index }}">
                                        {{ $faq['a'] }}
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="faq-item-lp">
                                <div class="faq-q-lp" onclick="toggleFaqLp(0)">
                                    <span>What documents are required to start?</span>
                                    <i data-lucide="plus" id="faq-icon-lp-0"></i>
                                </div>
                                <div class="faq-a-lp" id="faq-a-lp-0">
                                    Basic KYC, income statements (salary slips or business ITR files), and current bank statements for the past 6 months are sufficient to run an eligibility assessment.
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

            @else
                <!-- ABOUT LAYOUT SECTIONS -->
                
                <!-- Story section -->
                <div style="margin-bottom: 3rem;">
                    <span class="pre-title">Our Story</span>
                    <h2>{{ $landingPage->content['story_title'] ?? 'Strategic Vision & Background' }}</h2>
                    <p style="margin-top: 1rem; line-height: 1.7; color: var(--text-muted);">
                        {!! nl2br(e($landingPage->content['story_text'] ?? 'Founded to solve the complexities of modern credit approvals, we assist small businesses and individuals in navigating rate options across lenders.')) !!}
                    </p>
                </div>

                <!-- Vision/Mission/Values -->
                <div class="benefits-grid" style="grid-template-columns: 1fr; gap: 1.5rem; margin-bottom: 3rem;">
                    <div class="benefit-block" style="padding: 2rem;">
                        <div class="benefit-icon-lp"><i data-lucide="eye" style="width: 24px; height: 24px;"></i></div>
                        <h3>Our Vision</h3>
                        <p style="color: var(--text-muted); font-size: 0.95rem; margin-top: 0.5rem;">
                            {{ $landingPage->content['vision'] ?? 'To empower Indian families and business ventures with secure, structured asset creation options.' }}
                        </p>
                    </div>
                    
                    <div class="benefit-block highlight" style="padding: 2rem;">
                        <div class="benefit-icon-lp" style="background-color: rgba(255,255,255,0.2); color: var(--mint-green);"><i data-lucide="target" style="width: 24px; height: 24px;"></i></div>
                        <h3>Our Mission</h3>
                        <p style="color: var(--text-light-muted); font-size: 0.95rem; margin-top: 0.5rem;">
                            {{ $landingPage->content['mission'] ?? 'To make the credit evaluation cycle transparent and prevent unneeded rejections.' }}
                        </p>
                    </div>

                    <div class="benefit-block" style="padding: 2rem;">
                        <div class="benefit-icon-lp"><i data-lucide="heart" style="width: 24px; height: 24px;"></i></div>
                        <h3>Core Values</h3>
                        <p style="color: var(--text-muted); font-size: 0.95rem; margin-top: 0.5rem;">
                            {{ $landingPage->content['values'] ?? 'Objective evaluations, transparent cost analysis, and complete safety of customer files.' }}
                        </p>
                    </div>
                </div>

                <!-- Team Section -->
                @if(!empty($landingPage->content['team']))
                    <div style="margin-bottom: 2rem;">
                        <span class="pre-title">Guiding Specialists</span>
                        <h2>Advisory Team</h2>
                        <div class="why-grid" style="grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); margin-top: 1.5rem;">
                            @foreach($landingPage->content['team'] as $idx => $member)
                                <div class="service-card text-center" style="padding: 2rem;">
                                    <div style="width:70px; height:70px; border-radius:50%; background-color:var(--primary-teal-light); margin:0 auto 1.5rem auto; display:flex; align-items:center; justify-content:center; color:white; font-size:1.5rem; font-weight:700;">
                                        {{ substr($member['name'] ?? 'C', 0, 2) }}
                                    </div>
                                    <h3 style="font-size: 1.2rem;">{{ $member['name'] ?? 'Advisor' }}</h3>
                                    <span class="color-accent" style="font-size:0.8rem; font-weight:600; display:block; margin-bottom:1rem;">{{ $member['role'] ?? 'Consultant' }}</span>
                                    <p style="font-size:0.85rem; color: var(--text-muted); line-height: 1.6;">{{ $member['bio'] ?? 'Bio details...' }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            @endif

        </div>

        <!-- Right Column: Conversion Lead Form -->
        <div class="right-column" id="lead-form-section">
            <div class="lead-form-card sticky-sidebar" style="position: sticky; top: 100px;">
                <h3>Get Consultation</h3>
                <p>Submit your parameters and secure a call with our advisory specialist.</p>
                
                <form action="{{ route('contact.submit') }}" method="POST">
                    @csrf
                    <input type="hidden" name="source" value="Landing Page: {{ $landingPage->title }}">
                    
                    <div class="form-group" style="margin-bottom: 1.25rem;">
                        <label for="lp-name" style="display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 0.5rem; color: var(--primary-teal-dark);">Your Full Name</label>
                        <input type="text" id="lp-name" name="name" class="form-control" placeholder="e.g. Rahul Kumar" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-light); border-radius: 6px;">
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 1.25rem;">
                        <label for="lp-phone" style="display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 0.5rem; color: var(--primary-teal-dark);">10-Digit Mobile Number</label>
                        <input type="tel" id="lp-phone" name="phone" class="form-control" placeholder="e.g. 9876543210" pattern="[0-9]{10}" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-light); border-radius: 6px;">
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label for="lp-loan" style="display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 0.5rem; color: var(--primary-teal-dark);">Select Required Credit Type</label>
                        <select id="lp-loan" name="loan_type" class="form-control" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-light); border-radius: 6px; background-color: #fff;">
                            <option value="">-- Choose Option --</option>
                            <option value="personal">Personal Loan</option>
                            <option value="home">Home Loan</option>
                            <option value="business">Business Loan</option>
                            <option value="lap">Loan Against Property (LAP)</option>
                            <option value="other">Other Loans</option>
                        </select>
                    </div>

                    @if(!empty($site['recaptcha_enabled']) && $site['recaptcha_enabled'] == '1' && !empty($site['recaptcha_site_key']))
                        <div class="form-group" style="margin-bottom: 1.25rem; display: flex; justify-content: center;">
                            <div class="g-recaptcha" data-sitekey="{{ $site['recaptcha_site_key'] }}"></div>
                        </div>
                    @endif

                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.85rem; font-weight: 700; border-radius: 6px;">Request Callback</button>
                </form>

                <div style="margin-top: 1.5rem; border-top: 1px solid var(--border-light); padding-top: 1rem; display: flex; align-items: center; gap: 10px; color: var(--text-muted); font-size: 0.8rem;">
                    <i data-lucide="shield-check" style="color: var(--mint-green); width: 20px; height: 20px; flex-shrink: 0;"></i>
                    <span>We protect your records. Financial data is never shared with third-party telecallers.</span>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- CTA Footer Block -->
<section class="final-cta-section" style="background-color: var(--primary-teal-light); text-align: center; padding: 4rem 0;">
    <div class="container">
        <h2>{{ $landingPage->content['cta_title'] ?? 'Secure Better Borrowing Matches' }}</h2>
        <p style="max-width: 600px; margin: 0 auto 1.5rem auto; color: var(--text-muted);">
            {{ $landingPage->content['cta_text'] ?? 'Consult our specialists in Jaipur today and receive prime rate quotes within 24 hours.' }}
        </p>
        <a href="#lead-form-section" class="btn btn-primary">Check Your Loan Eligibility Now</a>
    </div>
</section>

<script>
    function toggleFaqLp(index) {
        const answer = document.getElementById('faq-a-lp-' + index);
        const icon = document.getElementById('faq-icon-lp-' + index);
        
        if (answer.style.display === 'block') {
            answer.style.display = 'none';
            icon.setAttribute('data-lucide', 'plus');
        } else {
            answer.style.display = 'block';
            icon.setAttribute('data-lucide', 'minus');
        }
        lucide.createIcons();
    }
</script>
@endsection
