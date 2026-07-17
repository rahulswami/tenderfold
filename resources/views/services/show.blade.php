@extends('layouts.app')

@section('title', ($service->metatitle ?? ($service->service_name . ' | ' . ($site['site_name'] ?? 'SumitZ Clinic'))))
@section('meta_description', ($service->metadescription ?? $service->summary))
@section('meta_keywords', ($service->metakeywords ?? ''))


@section('schema')
    @if(!empty($service->schema_markup))
        {!! $service->schema_markup !!}
    @else
        <script type="application/ld+json">
        {
          "{{ '@' }}context": "https://schema.org",
          "{{ '@graph' }}": [
            {
              "{{ '@type' }}": "Service",
              "{{ '@id' }}": "{{ route('services.show', $service->slug) }}/#service",
              "name": "{{ $service->service_name }}",
              "description": "{{ $service->summary }}",
              "provider": {
                "{{ '@type' }}": "Organization",
                "{{ '@id' }}": "{{ route('home') }}/#organization",
                "name": "{{ $site['site_name'] ?? 'SumitZ Clinic' }}",
                "url": "{{ route('home') }}"
              },
              "areaServed": {
                "{{ '@type' }}": "State",
                "name": "Maharashtra"
              },
              "serviceType": "MedicalService",
              "offers": {
                "{{ '@type' }}": "Offer",
                "priceCurrency": "INR",
                "description": "Consultation details: {{ $service->rate_value ?? 'Standard slots' }}"
              }
            }
            @if(!empty($service->faqs) && is_array($service->faqs))
            ,
            {
              "{{ '@type' }}": "FAQPage",
              "{{ '@id' }}": "{{ route('services.show', $service->slug) }}/#faq",
              "mainEntity": [
                @foreach($service->faqs as $index => $faq)
                {
                  "{{ '@type' }}": "Question",
                  "name": "{{ $faq['question'] ?? '' }}",
                  "acceptedAnswer": {
                    "{{ '@type' }}": "Answer",
                    "text": "{{ $faq['answer'] ?? '' }}"
                  }
                }{{ $index < count($service->faqs) - 1 ? ',' : '' }}
                @endforeach
              ]
            }
            @endif
          ]
        }
        </script>
    @endif
@endsection

@section('content')

    <!-- Section 1: Hero Section -->
    <section class="details-hero">
        <div class="container">
            <span class="color-mint font-semibold" style="text-transform: uppercase; letter-spacing: 1.5px; font-size: 0.9rem;">{{ $service->hero_category }}</span>
            <h1>{{ $service->hero_title }}</h1>
            <p style="color: var(--text-light-muted); max-width: 600px; margin-top: 10px;">{{ $service->hero_subtitle }}</p>
            <div class="details-hero-meta" style="margin-top: 20px;">
                @if($service->rate_value)
                    <div class="meta-item"><i data-lucide="check"></i> {{ $service->rate_value }}</div>
                @endif
                @if($service->max_loan)
                    <div class="meta-item"><i data-lucide="info"></i> {{ $service->max_loan }}</div>
                @endif
                @if($service->tenure)
                    <div class="meta-item"><i data-lucide="clock"></i> {{ $service->tenure }}</div>
                @endif
            </div>
        </div>
    </section>

    <!-- Section 2: Detailed Overview & Introduction -->
    <section class="section">
        <div class="container details-layout animate-on-scroll">
            <div class="details-content">
                <h2>{{ $service->intro_title }}</h2>
                {!! $service->intro_content !!}
                
                <!-- Section 3: Feature Blocks (Eligibility & Docs) -->
                @if($service->eligibility_criteria || $service->documents_required)
                <div class="details-grid-cards" style="margin-top: 2.5rem; margin-bottom: 2.5rem;">
                    <!-- Eligibility Card -->
                    @if($service->eligibility_criteria)
                    <div class="details-list-card">
                        <h3><i data-lucide="check-circle" style="color: var(--accent-teal);"></i> Requirements & Preparation</h3>
                        <ul class="sol-features-list" style="list-style: none; padding: 0;">
                            @foreach(explode("\n", str_replace("\r", "", $service->eligibility_criteria)) as $item)
                                @if(trim($item))
                                    <li style="margin-bottom: 0.75rem;"><i data-lucide="check" style="color: var(--accent-teal); margin-right: 8px; vertical-align: middle;"></i> {{ trim($item) }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <!-- Documents Card -->
                    @if($service->documents_required)
                    <div class="details-list-card">
                        <h3><i data-lucide="file-text" style="color: var(--accent-teal);"></i> Diagnostics / Prep Work</h3>
                        <ul class="sol-features-list" style="list-style: none; padding: 0;">
                            @foreach(explode("\n", str_replace("\r", "", $service->documents_required)) as $item)
                                @if(trim($item))
                                    <li style="margin-bottom: 0.75rem;"><i data-lucide="check" style="color: var(--accent-teal); margin-right: 8px; vertical-align: middle;"></i> {{ trim($item) }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Section 4: Optimization Tips -->
                @if($service->tips_title)
                    <h2>{{ $service->tips_title }}</h2>
                    {!! $service->tips_content !!}
                @endif

                <!-- Section 5: FAQs -->
                @if(!empty($service->faqs) && is_array($service->faqs))
                    <h2 style="margin-top: 3rem;">Frequently Asked Questions</h2>
                    <div class="faq-accordion" style="margin-top: 1.5rem;">
                        @foreach($service->faqs as $faq)
                            <div class="faq-item">
                                <div class="faq-header">
                                    <span class="faq-title">{{ $faq['question'] ?? '' }}</span>
                                    <div class="faq-toggle"></div>
                                </div>
                                <div class="faq-body">
                                    <div class="faq-content">
                                        <p>{{ $faq['answer'] ?? '' }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            
            <!-- Sticky Sidebar Form -->
            <div class="sidebar-sticky">
                <div class="sidebar-box form-card">
                    <h3>Request Consultation</h3>
                    <form action="{{ route('contact.submit') }}" method="POST">
                        @csrf
                        <input type="hidden" name="source" value="Service Page Form: {{ $service->service_name }}">
                        <input type="hidden" name="service" value="{{ $service->slug }}">
                        <div class="form-group">
                            <label for="sb-name">Full Name</label>
                            <input type="text" id="sb-name" name="name" class="form-control" placeholder="Enter your name" required>
                        </div>
                        <div class="form-group">
                            <label for="sb-phone">Phone Number</label>
                            <input type="tel" id="sb-phone" name="phone" class="form-control" placeholder="10-digit number" pattern="[0-9]{10}" required>
                        </div>
                        @if(!empty($site['recaptcha_enabled']) && $site['recaptcha_enabled'] == '1' && !empty($site['recaptcha_site_key']))
                            <div class="form-group" style="margin-bottom: 1rem; display: flex; justify-content: center;">
                                <div class="g-recaptcha" data-sitekey="{{ $site['recaptcha_site_key'] }}"></div>
                            </div>
                        @endif
                        <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Request Slot</button>
                    </form>
                </div>
                
                <div class="sidebar-box sidebar-contact-card">
                    <h3 style="border:none; padding:0; margin-bottom:0.75rem;">Need Quick Help?</h3>
                    <p style="font-size:0.875rem; color:var(--text-muted); margin-bottom:1.5rem;">Speak directly to our clinical coordinator.</p>
                    <a href="tel:{{ $site['phone'] ?? '+919672777749' }}" class="btn btn-secondary" style="width: 100%;"><i data-lucide="phone"></i> {{ $site['phone'] ?? '+91 96727 77749' }}</a>
                </div>
            </div>
        </div>
    </section>

@endsection
