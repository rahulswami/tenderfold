@extends('layouts.app')

@section('title', $landingPage->title . ' - ' . ($site['site_name'] ?? 'Tenderfold'))
@section('meta_description', $landingPage->meta_description ?? 'Book premium laundry and dry cleaning pickup across Gurgaon with Tenderfold.')


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
        color: #ffffff !important;
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
        <p style="margin-bottom: 0;">{{ $landingPage->content['hero_subtitle'] ?? 'Compare custom loan structures, analyze hidden margins, and secure capital under the guidance of credit consultants.' }}</p>
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
                                <div class="icon-lp-wrap"><i data-lucide="truck"></i></div>
                                <h3 style="margin-bottom: 0.5rem; font-size: 1.15rem; color: var(--primary-teal-dark);">Free Doorstep Pickup</h3>
                                <p style="font-size: 0.875rem; color: var(--text-muted); line-height: 1.5;">Scheduled collection and delivery from your doorstep across all sectors of Gurgaon.</p>
                            </div>
                            <div class="feature-card-lp">
                                <div class="icon-lp-wrap"><i data-lucide="sparkles"></i></div>
                                <h3 style="margin-bottom: 0.5rem; font-size: 1.15rem; color: var(--primary-teal-dark);">Skin-Safe & Eco Solvents</h3>
                                <p style="font-size: 0.875rem; color: var(--text-muted); line-height: 1.5;">Non-toxic, allergen-free German detergents that preserve fibers and protect delicate skin.</p>
                            </div>
                            <div class="feature-card-lp">
                                <div class="icon-lp-wrap"><i data-lucide="layers"></i></div>
                                <h3 style="margin-bottom: 0.5rem; font-size: 1.15rem; color: var(--primary-teal-dark);">Individual Batch Washing</h3>
                                <p style="font-size: 0.875rem; color: var(--text-muted); line-height: 1.5;">Your garments are never mixed with other customers' clothes. 100% segregated and hygienic.</p>
                            </div>
                            <div class="feature-card-lp">
                                <div class="icon-lp-wrap"><i data-lucide="award"></i></div>
                                <h3 style="margin-bottom: 0.5rem; font-size: 1.15rem; color: var(--primary-teal-dark);">Vacuum Steam Finishing</h3>
                                <p style="font-size: 0.875rem; color: var(--text-muted); line-height: 1.5;">Crisp, wrinkle-free finish using temperature-controlled Italian vacuum tables.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- About section -->
                <div style="margin-bottom: 3rem;">
                    <span class="pre-title">How We Help</span>
                    <h2>{{ $landingPage->content['about_title'] ?? 'Professional Fabric Care & Doorstep Delivery' }}</h2>
                    <p style="margin-top: 1rem; line-height: 1.7; color: var(--text-muted);">
                        {!! nl2br(e($landingPage->content['about_text'] ?? (($site['site_name'] ?? 'Tenderfold') . " is your dedicated laundry and dry cleaning partner in Gurgaon. We provide hygienic batch washing, expert stain removal, eco-friendly dry cleaning, and crisp vacuum steam pressing with free doorstep pickup across Gurgaon."))) !!}
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
                                    <span>How does doorstep pickup work?</span>
                                    <i data-lucide="plus" id="faq-icon-lp-0"></i>
                                </div>
                                <div class="faq-a-lp" id="faq-a-lp-0">
                                    Schedule a pickup online or via WhatsApp. Our executive collects your garments, tags them with barcodes, processes them according to fabric type, and delivers them back fresh and ready to wear.
                                </div>
                            </div>
                            <div class="faq-item-lp">
                                <div class="faq-q-lp" onclick="toggleFaqLp(1)">
                                    <span>Do you wash my clothes with other people's clothes?</span>
                                    <i data-lucide="plus" id="faq-icon-lp-1"></i>
                                </div>
                                <div class="faq-a-lp" id="faq-a-lp-1">
                                    Never. We practice strict batch washing where every order is processed in its own dedicated washing and drying cycle for total hygiene and care.
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

            @elseif($landingPage->layout_type === 'service')
                <!-- SERVICE LAYOUT SECTIONS -->
                <!-- Service Details -->
                <div style="margin-bottom: 3rem;">
                    <span class="pre-title">Service Details</span>
                    <h2>{{ $landingPage->content['service_details_title'] ?? 'Comprehensive Garment & Fabric Care' }}</h2>
                    <p style="margin-top: 1rem; line-height: 1.7; color: var(--text-muted);">
                        {!! nl2br(e($landingPage->content['service_details_text'] ?? 'Our advanced fabric spa combines gentler Italian cleaning processes, specialized stain spotting agents, and customized temperature controls to maintain the longevity, color brilliance, and texture of every garment.')) !!}
                    </p>
                </div>

                <!-- Bullets -->
                <div class="features-wrapper">
                    <span class="pre-title">Core Benefits</span>
                    <h2>Key Advantages of the Service</h2>
                    
                    <div class="why-grid" style="grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); margin-top: 1.5rem; margin-bottom: 3rem;">
                        @if(!empty($landingPage->content['service_bullets']))
                            @foreach($landingPage->content['service_bullets'] as $bullet)
                                <div class="feature-card-lp">
                                    <div class="icon-lp-wrap">
                                        <i data-lucide="{{ $bullet['icon'] ?? 'check' }}"></i>
                                    </div>
                                    <h3 style="margin-bottom: 0.5rem; font-size: 1.15rem; color: var(--primary-teal-dark);">{{ $bullet['title'] ?? 'Benefit Title' }}</h3>
                                    <p style="font-size: 0.875rem; color: var(--text-muted); line-height: 1.5;">{{ $bullet['desc'] ?? 'Benefit description details.' }}</p>
                                </div>
                            @endforeach
                        @else
                            <div class="feature-card-lp">
                                <div class="icon-lp-wrap"><i data-lucide="shield-check"></i></div>
                                <h3 style="margin-bottom: 0.5rem; font-size: 1.15rem; color: var(--primary-teal-dark);">Color & Texture Protection</h3>
                                <p style="font-size: 0.875rem; color: var(--text-muted); line-height: 1.5;">Targeted stain removal and gentle detergents prevent fading and fiber damage.</p>
                            </div>
                            <div class="feature-card-lp">
                                <div class="icon-lp-wrap"><i data-lucide="clock"></i></div>
                                <h3 style="margin-bottom: 0.5rem; font-size: 1.15rem; color: var(--primary-teal-dark);">Fast 24-48h Turnaround</h3>
                                <p style="font-size: 0.875rem; color: var(--text-muted); line-height: 1.5;">Convenient pickup schedules with timely doorstep delivery right to your closet.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Process Steps -->
                <div style="margin-bottom: 3rem;">
                    <span class="pre-title">How It Works</span>
                    <h2>{{ $landingPage->content['process_title'] ?? 'Your 4-Step Doorstep Care Process' }}</h2>
                    <div style="margin-top: 1.5rem; display: flex; flex-direction: column; gap: 1.5rem;">
                        @if(!empty($landingPage->content['process_steps']))
                            @foreach($landingPage->content['process_steps'] as $idx => $step)
                                <div style="display: flex; gap: 1.5rem; align-items: start; background: rgba(92, 203, 179, 0.05); padding: 1.5rem; border-radius: 8px; border-left: 4px solid var(--primary-teal);">
                                    <div style="font-size: 1.5rem; font-weight: 800; color: var(--primary-teal); line-height: 1;">0{{ $idx + 1 }}</div>
                                    <div>
                                        <h3 style="font-size: 1.1rem; color: var(--primary-teal-dark); margin-bottom: 0.25rem;">{{ $step['title'] ?? 'Care Step' }}</h3>
                                        <p style="font-size: 0.9rem; color: var(--text-muted); line-height: 1.5;">{{ $step['desc'] ?? 'Details of step.' }}</p>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div style="display: flex; gap: 1.5rem; align-items: start; background: rgba(92, 203, 179, 0.05); padding: 1.5rem; border-radius: 8px; border-left: 4px solid var(--primary-teal);">
                                <div style="font-size: 1.5rem; font-weight: 800; color: var(--primary-teal); line-height: 1;">01</div>
                                <div>
                                    <h3 style="font-size: 1.1rem; color: var(--primary-teal-dark); margin-bottom: 0.25rem;">Doorstep Collection & Barcoding</h3>
                                    <p style="font-size: 0.9rem; color: var(--text-muted); line-height: 1.5;">We pick up garments from your home, barcode tag each piece, and check care labels.</p>
                                </div>
                            </div>
                            <div style="display: flex; gap: 1.5rem; align-items: start; background: rgba(92, 203, 179, 0.05); padding: 1.5rem; border-radius: 8px; border-left: 4px solid var(--primary-teal);">
                                <div style="font-size: 1.5rem; font-weight: 800; color: var(--primary-teal); line-height: 1;">02</div>
                                <div>
                                    <h3 style="font-size: 1.1rem; color: var(--primary-teal-dark); margin-bottom: 0.25rem;">Custom Washing & Dry Cleaning</h3>
                                    <p style="font-size: 0.9rem; color: var(--text-muted); line-height: 1.5;">Individual batch washing with pH-neutral eco detergents or gentle dry cleaning.</p>
                                </div>
                            </div>
                            <div style="display: flex; gap: 1.5rem; align-items: start; background: rgba(92, 203, 179, 0.05); padding: 1.5rem; border-radius: 8px; border-left: 4px solid var(--primary-teal);">
                                <div style="font-size: 1.5rem; font-weight: 800; color: var(--primary-teal); line-height: 1;">03</div>
                                <div>
                                    <h3 style="font-size: 1.1rem; color: var(--primary-teal-dark); margin-bottom: 0.25rem;">Vacuum Steam Pressing</h3>
                                    <p style="font-size: 0.9rem; color: var(--text-muted); line-height: 1.5;">Wrinkle-free pressing on padded vacuum suction tables with zero fabric shine.</p>
                                </div>
                            </div>
                            <div style="display: flex; gap: 1.5rem; align-items: start; background: rgba(92, 203, 179, 0.05); padding: 1.5rem; border-radius: 8px; border-left: 4px solid var(--primary-teal);">
                                <div style="font-size: 1.5rem; font-weight: 800; color: var(--primary-teal); line-height: 1;">04</div>
                                <div>
                                    <h3 style="font-size: 1.1rem; color: var(--primary-teal-dark); margin-bottom: 0.25rem;">Boutique Packaging & Delivery</h3>
                                    <p style="font-size: 0.9rem; color: var(--text-muted); line-height: 1.5;">Garments are hung or folded neatly in breathable garment bags and delivered on time.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

            @elseif($landingPage->layout_type === 'specialist')
                <!-- SPECIALIST LAYOUT SECTIONS -->
                <!-- Master Artisan Bio -->
                <div style="margin-bottom: 3rem;">
                    <span class="pre-title">Fabric Care Craftsmanship</span>
                    <h2>{{ $landingPage->content['specialist_bio_title'] ?? 'Trained Technicians & Master Dry Cleaners' }}</h2>
                    <p style="margin-top: 1rem; line-height: 1.7; color: var(--text-muted);">
                        {!! nl2br(e($landingPage->content['specialist_bio_text'] ?? 'Every fabric at Tenderfold is handled by certified dry cleaning specialists and garment technicians. From couture silk sarees to bespoke suits and luxury designer sneakers, our craftsmen inspect, spot-clean, and finish every item to perfection.')) !!}
                    </p>
                </div>

                <!-- Credentials / Qualifications -->
                <div style="margin-bottom: 3rem;">
                    <span class="pre-title">Care Standards</span>
                    <h2>Quality Certifications & Infrastructure</h2>
                    <div style="margin-top: 1.5rem; display: grid; grid-template-columns: 1fr; gap: 1rem;">
                        @if(!empty($landingPage->content['specialist_qualifications']))
                            @foreach($landingPage->content['specialist_qualifications'] as $qual)
                                <div style="background: #ffffff; border: 1px solid var(--border-color); padding: 1.25rem; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); display: flex; align-items: center; gap: 1rem;">
                                    <div style="background: rgba(92, 203, 179, 0.1); color: var(--primary-teal); width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        <i data-lucide="award" style="width: 20px; height: 20px;"></i>
                                    </div>
                                    <div>
                                        <h4 style="font-size: 1rem; color: var(--primary-teal-dark); margin: 0;">{{ $qual['title'] ?? 'Certification Title' }}</h4>
                                        <p style="font-size: 0.85rem; color: var(--text-muted); margin: 0.25rem 0 0 0;">{{ $qual['desc'] ?? 'Details of certification.' }}</p>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div style="background: #ffffff; border: 1px solid var(--border-color); padding: 1.25rem; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); display: flex; align-items: center; gap: 1rem;">
                                <div style="background: rgba(92, 203, 179, 0.1); color: var(--primary-teal); width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <i data-lucide="award" style="width: 20px; height: 20px;"></i>
                                </div>
                                <div>
                                    <h4 style="font-size: 1rem; color: var(--primary-teal-dark); margin: 0;">Certified Hydrocarbon & Eco Dry Cleaning Plant</h4>
                                    <p style="font-size: 0.85rem; color: var(--text-muted); margin: 0.25rem 0 0 0;">State-of-the-art closed-loop Italian dry cleaning machinery for fabric safety.</p>
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
                    <h2>{{ $landingPage->content['story_title'] ?? 'The Story Behind Tenderfold' }}</h2>
                    <p style="margin-top: 1rem; line-height: 1.7; color: var(--text-muted);">
                        {!! nl2br(e($landingPage->content['story_text'] ?? 'Founded in Gurgaon, Tenderfold was created with a single vision: to elevate everyday laundry and dry cleaning into a reliable, premium fabric care service that Gurgaon households can trust.')) !!}
                    </p>
                </div>

                <!-- Vision/Mission/Values -->
                <div class="benefits-grid" style="grid-template-columns: 1fr; gap: 1.5rem; margin-bottom: 3rem;">
                    <div class="benefit-block" style="padding: 2rem;">
                        <div class="benefit-icon-lp"><i data-lucide="eye" style="width: 24px; height: 24px;"></i></div>
                        <h3>Our Vision</h3>
                        <p style="color: var(--text-muted); font-size: 0.95rem; margin-top: 0.5rem;">
                            {{ $landingPage->content['vision'] ?? 'To deliver pristine freshness and fabric longevity to every home across Gurgaon.' }}
                        </p>
                    </div>
                    
                    <div class="benefit-block highlight" style="padding: 2rem;">
                        <div class="benefit-icon-lp" style="background-color: rgba(255,255,255,0.2); color: var(--mint-green);"><i data-lucide="target" style="width: 24px; height: 24px;"></i></div>
                        <h3>Our Mission</h3>
                        <p style="color: var(--text-light-muted); font-size: 0.95rem; margin-top: 0.5rem;">
                            {{ $landingPage->content['mission'] ?? 'To make garment care seamless and transparent through modern technology and doorstep pickup.' }}
                        </p>
                    </div>

                    <div class="benefit-block" style="padding: 2rem;">
                        <div class="benefit-icon-lp"><i data-lucide="heart" style="width: 24px; height: 24px;"></i></div>
                        <h3>Core Values</h3>
                        <p style="color: var(--text-muted); font-size: 0.95rem; margin-top: 0.5rem;">
                            {{ $landingPage->content['values'] ?? 'Uncompromising hygiene, eco-friendly solvents, and punctual doorstep service.' }}
                        </p>
                    </div>
                </div>

            @endif

        </div>

        <!-- Right Column: Conversion Lead Form -->
        <div class="right-column" id="lead-form-section">
            <div class="lead-form-card sticky-sidebar" style="position: sticky; top: 100px;">
                <h3>Schedule a Pickup</h3>
                <p>Fill out your details to arrange a free doorstep laundry pickup in Gurgaon.</p>
                
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
                        <label for="lp-service" style="display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 0.5rem; color: var(--primary-teal-dark);">Select Service Needed</label>
                        <select id="lp-service" name="service" class="form-control" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-light); border-radius: 6px; background-color: #fff;">
                            <option value="">-- Choose Service --</option>
                            <option value="laundry-services">Everyday Laundry (Wash & Fold)</option>
                            <option value="dry-cleaning">Eco-Friendly Dry Cleaning</option>
                            <option value="ironing">Steam Pressing</option>
                            <option value="shoe-cleaning">Shoe Laundry & Spa</option>
                            <option value="carpet-cleaning">Carpet & Rug Cleaning</option>
                            <option value="curtain-cleaning">Curtain & Drape Cleaning</option>
                        </select>
                    </div>

                    @if(!empty($site['recaptcha_enabled']) && $site['recaptcha_enabled'] == '1' && !empty($site['recaptcha_site_key']))
                        <div class="form-group" style="margin-bottom: 1.25rem; display: flex; justify-content: center;">
                            <div class="g-recaptcha" data-sitekey="{{ $site['recaptcha_site_key'] }}"></div>
                        </div>
                    @endif

                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.85rem; font-weight: 700; border-radius: 6px;">Schedule Doorstep Pickup</button>
                </form>

                <div style="margin-top: 1.5rem; border-top: 1px solid var(--border-light); padding-top: 1rem; display: flex; align-items: center; gap: 10px; color: var(--text-muted); font-size: 0.8rem;">
                    <i data-lucide="shield-check" style="color: var(--mint-green); width: 20px; height: 20px; flex-shrink: 0;"></i>
                    <span>We protect your privacy. Customer details are only used for pickup & delivery coordination.</span>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- CTA Footer Block -->
<section class="final-cta-section" style="background-color: var(--primary-teal-light); text-align: center; padding: 4rem 0;">
    <div class="container">
        <h2>{{ $landingPage->content['cta_title'] ?? 'Experience Freshness in Every Single Fold' }}</h2>
        <p style="max-width: 600px; margin: 0 auto 1.5rem auto; color: var(--text-muted);">
            {{ $landingPage->content['cta_text'] ?? 'Schedule your free doorstep collection in Gurgaon today and enjoy 20% off your first order.' }}
        </p>
        <a href="#lead-form-section" class="btn btn-primary">Book Doorstep Pickup Now</a>
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
