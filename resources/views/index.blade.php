@extends('layouts.app')

@section('title', ($homePage->metatitle ?? 'SumitZ Orthopaedic & Physiotherapy Clinic | Baner & Hinjewadi, Pune'))
@section('meta_description', ($homePage->metadescription ?? 'SumitZ Clinic is Pune\'s premier orthopaedic and physiotherapy hospital in Baner & Hinjewadi. Led by Dr. Sumit, we offer expert joint replacement, fracture care, spine treatment, and sports rehabilitation.'))
@section('meta_keywords', ($homePage->metakeywords ?? 'orthopedic clinic in baner, physiotherapy clinic in baner, orthopedic doctor in baner, dr sumit diwan'))

@section('schema')
@if(!empty($site['home_schema_markup']))
{!! $site['home_schema_markup'] !!}
@else
<script type="application/ld+json">
{
  "{{ '@' }}context": "https://schema.org",
  "{{ '@graph' }}": [
    {
      "{{ '@type' }}": "MedicalClinic",
      "{{ '@id' }}": "{{ route('home') }}/#organization",
      "name": "{{ $site['site_name'] ?? 'SumitZ Clinic' }}",
      "url": "{{ route('home') }}",
      "logo": {
        "{{ '@type' }}": "ImageObject",
        "url": "{{ !empty($site['logo_path']) ? site_image($site['logo_path']) : asset('assets/images/logo.png') }}"
      },
      "telephone": "{{ $site['phone'] ?? '+919545559434' }}",
      "address": {
        "{{ '@type' }}": "PostalAddress",
        "streetAddress": "First Floor, Krishna Avenue, Baner Road",
        "addressLocality": "Baner, Pune",
        "addressRegion": "Maharashtra",
        "postalCode": "411045",
        "addressCountry": "IN"
      }
    }
  ]
}
</script>
@endif
@endsection

@section('content')

    <!-- 1. Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-grid">
                <div>
                    <div class="hero-eyebrow">
                        <span class="badge badge-blue">
                            <i data-lucide="shield-check" style="width: 14px; height: 14px;"></i>
                            Trusted Orthopaedic & Physiotherapy Center
                        </span>
                    </div>
                    <h1 class="hero-title">
                        {!! $pageContents['home']['hero']['title'] ?? 'Move Better. Recover Stronger. Live Without Limiting <span class="highlight-red">Pain</span>.' !!}
                    </h1>
                    <p class="hero-subtitle">
                        {{ $pageContents['home']['hero']['subtitle'] ?? 'Specialist consultations, advanced physiotherapy, digital diagnostics, and personalized rehabilitation programs designed to restore your natural mobility in Baner and Hinjewadi.' }}
                    </p>
                    <div class="hero-cta-group">
                        <button onclick="openDialog('callback-dialog');" class="btn btn-primary btn-lg">
                            <i data-lucide="calendar"></i> Book an Appointment
                        </button>
                        <a href="tel:{{ $site['phone'] ?? '+919545559434' }}" class="btn btn-secondary btn-lg">
                            <i data-lucide="phone"></i> Call Clinic Coordinator
                        </a>
                    </div>
                    <div class="hero-trust-row">
                        <div class="trust-item">
                            <span class="trust-number">20,000+</span>
                            <span class="trust-label">Patients Recovered</span>
                        </div>
                        <div class="trust-item">
                            <span class="trust-number">15+ Years</span>
                            <span class="trust-label">Clinical Practice</span>
                        </div>
                        <div class="trust-item">
                            <span class="trust-number">Baner & Hinjewadi</span>
                            <span class="trust-label">State-of-the-Art Clinics</span>
                        </div>
                    </div>
                </div>

                <div class="hero-visual">
                    <div class="hero-img-container">
                        @if(isset($slides) && $slides->count() > 0 && !empty($slides->first()->image_path) && strpos($slides->first()->image_path, 'http') !== false)
                            <img src="{{ site_image($slides->first()->image_path) }}" alt="Dr. Sumit Consultation" fetchpriority="high" decoding="async" width="1000" height="667">
                        @else
                            <img src="https://images.unsplash.com/photo-1629909613654-28e377c37b09?auto=format&fit=crop&w=1000&q=80" alt="SumitZ Physiotherapy & Orthopaedic Clinic" fetchpriority="high" decoding="async" width="1000" height="667">
                        @endif
                    </div>
                    <div class="hero-floating-card">
                        <div class="floating-card-icon">
                            <i data-lucide="heart-pulse" style="width: 24px; height: 24px;"></i>
                        </div>
                        <div>
                            <strong style="display: block; font-size: 15px; color: var(--heading-navy);">Multi-Speciality Team</strong>
                            <span style="font-size: 13px; color: var(--muted-text);">Orthopaedics • Spine • Sports Care</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. Quick Care Finder (Symptom Navigator) -->
    <section class="symptom-finder-section">
        <div class="container">
            <div class="symptom-card-wrapper">
                <div class="symptom-header">
                    <span class="badge badge-blue" style="margin-bottom: 8px;">Quick Care Finder</span>
                    <h2 style="font-size: 22px; margin-top: 4px;">What area of mobility or pain can we help you with?</h2>
                </div>
                <div class="symptom-grid">
                    <a href="{{ route('treatments') }}#knee" class="symptom-item">
                        <i data-lucide="activity" class="symptom-icon"></i>
                        <span>Knee & Joint Pain</span>
                    </a>
                    <a href="{{ route('treatments') }}#spine" class="symptom-item">
                        <i data-lucide="shield" class="symptom-icon"></i>
                        <span>Back & Neck Care</span>
                    </a>
                    <a href="{{ route('services') }}#physiotherapy" class="symptom-item">
                        <i data-lucide="zap" class="symptom-icon"></i>
                        <span>Physiotherapy</span>
                    </a>
                    <a href="{{ route('treatments') }}#sports" class="symptom-item">
                        <i data-lucide="award" class="symptom-icon"></i>
                        <span>Sports Injury</span>
                    </a>
                    <a href="{{ route('treatments') }}#fracture" class="symptom-item">
                        <i data-lucide="plus-square" class="symptom-icon"></i>
                        <span>Fracture Care</span>
                    </a>
                    <a href="{{ route('services') }}#diagnostics" class="symptom-item">
                        <i data-lucide="check-circle-2" class="symptom-icon"></i>
                        <span>X-Ray & BMD</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. Direct Appointment Booking Card -->
    <section class="section-spacing" id="appointment-section" style="background-color: var(--bg-surface);">
        <div class="container">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 48px; align-items: center;">
                <div>
                    <span class="badge badge-blue" style="margin-bottom: 12px;">Seamless Booking</span>
                    <h2 style="margin-bottom: 16px;">Schedule Your Priority Consultation</h2>
                    <p style="font-size: 16px; margin-bottom: 24px; color: var(--body-text);">
                        Our clinical coordinators are available to help align your visit with Dr. Sumit or our specialized physiotherapy team. Fill in your details for a prompt callback.
                    </p>
                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 36px; height: 36px; border-radius: 50%; background-color: var(--brand-blue-light); color: var(--brand-blue); display: flex; align-items: center; justify-content: center; font-weight: 700;">1</div>
                            <span style="font-size: 15px; font-weight: 600; color: var(--heading-navy);">Submit your preferred service and contact details</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 36px; height: 36px; border-radius: 50%; background-color: var(--brand-blue-light); color: var(--brand-blue); display: flex; align-items: center; justify-content: center; font-weight: 700;">2</div>
                            <span style="font-size: 15px; font-weight: 600; color: var(--heading-navy);">Receive a quick confirmation call from our coordinator</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 36px; height: 36px; border-radius: 50%; background-color: var(--brand-blue-light); color: var(--brand-blue); display: flex; align-items: center; justify-content: center; font-weight: 700;">3</div>
                            <span style="font-size: 15px; font-weight: 600; color: var(--heading-navy);">Visit Baner or Hinjewadi clinic at your scheduled time</span>
                        </div>
                    </div>
                </div>

                <div class="form-card">
                    <form action="{{ route('contact.submit') }}" method="POST">
                        @csrf
                        <input type="hidden" name="source" value="Homepage Booking Card">
                        <div class="form-group">
                            <label for="hp-name" class="form-label">Full Name</label>
                            <input type="text" id="hp-name" name="name" class="form-control" placeholder="Enter your full name" required>
                        </div>
                        <div class="form-group">
                            <label for="hp-phone" class="form-label">Phone Number</label>
                            <input type="tel" id="hp-phone" name="phone" class="form-control" placeholder="10-digit mobile number" pattern="[0-9]{10}" required>
                        </div>
                        <div class="form-group">
                            <label for="hp-service" class="form-label">Service Needed</label>
                            <select id="hp-service" name="service" class="form-control" required>
                                <option value="">-- Select Service --</option>
                                @foreach($globalServices as $gs)
                                    <option value="{{ $gs->slug }}">{{ $gs->service_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="hp-date" class="form-label">Preferred Appointment Date</label>
                            <input type="date" id="hp-date" name="appointment_date" class="form-control">
                        </div>
                        @if(!empty($site['recaptcha_enabled']) && $site['recaptcha_enabled'] == '1' && !empty($site['recaptcha_site_key']))
                            <div class="form-group" style="display: flex; justify-content: center;">
                                <div class="g-recaptcha" data-sitekey="{{ $site['recaptcha_site_key'] }}"></div>
                            </div>
                        @endif
                        <button type="submit" class="btn btn-primary btn-full" style="margin-top: 8px;">
                            <i data-lucide="calendar"></i> Request Consultation Slot
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. Most Requested Care (Services Grid) -->
    <section class="section-spacing" style="background-color: var(--brand-blue-light);">
        <div class="container">
            <div style="text-align: center; max-width: 680px; margin: 0 auto 48px;">
                <span class="badge badge-blue" style="margin-bottom: 12px;">Multidisciplinary Facilities</span>
                <h2>Comprehensive Healthcare Services</h2>
                <p style="color: var(--body-text); font-size: 16.5px;">From specialist orthopaedic evaluations to full-spectrum diagnostic imaging and sports rehabilitation.</p>
            </div>

            <div class="card-grid">
                @foreach($globalServices->take(6) as $index => $service)
                    <div class="medical-card">
                        <div class="card-img-wrapper">
                            @if(!empty($service->image_path))
                                <img src="{{ site_image($service->image_path) }}" alt="{{ $service->service_name }}" loading="lazy" decoding="async">
                            @else
                                <img src="https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?auto=format&fit=crop&w=600&q=80" alt="{{ $service->service_name }}" loading="lazy" decoding="async">
                            @endif
                        </div>
                        <div class="card-body">
                            <span class="card-category">{{ $service->hero_category ?? 'Clinical Facility' }}</span>
                            <h3 class="card-title">{{ $service->service_name }}</h3>
                            <p class="card-text">{{ Str::limit(strip_tags($service->summary ?? $service->intro_content), 110) }}</p>
                            <div class="card-footer">
                                <a href="{{ route('services.show', $service->slug) }}" class="card-link">
                                    Explore Service <i data-lucide="arrow-right" style="width: 16px;"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div style="text-align: center; margin-top: 40px;">
                <a href="{{ route('services') }}" class="btn btn-secondary btn-lg">View All Services</a>
            </div>
        </div>
    </section>

    <!-- 5. Meet the Lead Specialist -->
    @if($globalSpecialists->count() > 0)
        @php $leadDoc = $globalSpecialists->first(); @endphp
        <section class="section-spacing" style="background-color: var(--surface-white);">
            <div class="container">
                <div style="display: grid; grid-template-columns: 0.9fr 1.1fr; gap: 48px; align-items: center;">
                    <div class="doctor-img-container" style="border-radius: var(--radius-xl); overflow: hidden; box-shadow: var(--shadow-lg);">
                        @if(!empty($leadDoc->image))
                            <img src="{{ site_image($leadDoc->image) }}" alt="{{ $leadDoc->name }}" style="width: 100%; height: 100%; object-fit: cover;" loading="lazy" decoding="async">
                        @else
                            <img src="https://images.unsplash.com/photo-1622253692010-333f2da6031d?auto=format&fit=crop&w=800&q=80" alt="{{ $leadDoc->name }}" loading="lazy" decoding="async">
                        @endif
                    </div>
                    <div>
                        <span class="badge badge-blue" style="margin-bottom: 12px;">Lead Consultant</span>
                        <h2 style="font-size: 36px; margin-bottom: 8px;">{{ $leadDoc->name }}</h2>
                        <span style="font-size: 16px; font-weight: 700; color: var(--brand-blue); display: block; margin-bottom: 20px;">
                            {{ $leadDoc->designation }}
                        </span>
                        <p style="font-size: 16.5px; line-height: 1.7; color: var(--body-text); margin-bottom: 24px;">
                            {{ Str::limit(strip_tags($leadDoc->description), 280) }}
                        </p>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 32px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <i data-lucide="check-circle-2" style="color: var(--brand-blue); width: 20px;"></i>
                                <span style="font-size: 14.5px; font-weight: 600; color: var(--heading-navy);">Joint Replacement</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <i data-lucide="check-circle-2" style="color: var(--brand-blue); width: 20px;"></i>
                                <span style="font-size: 14.5px; font-weight: 600; color: var(--heading-navy);">Robotic Surgeries</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <i data-lucide="check-circle-2" style="color: var(--brand-blue); width: 20px;"></i>
                                <span style="font-size: 14.5px; font-weight: 600; color: var(--heading-navy);">Spine Care</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <i data-lucide="check-circle-2" style="color: var(--brand-blue); width: 20px;"></i>
                                <span style="font-size: 14.5px; font-weight: 600; color: var(--heading-navy);">Trauma & Fracture</span>
                            </div>
                        </div>
                        <div style="display: flex; gap: 16px; flex-wrap: wrap;">
                            <a href="{{ route('specialists.show', $leadDoc->slug) }}" class="btn btn-primary">
                                Meet Dr. Sumit Profile
                            </a>
                            <button onclick="openDialog('callback-dialog');" class="btn btn-secondary">
                                <i data-lucide="calendar"></i> Book Consultation
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- 6. 4-Step Recovery Path -->
    <section class="section-spacing" style="background-color: var(--bg-surface);">
        <div class="container">
            <div style="text-align: center; max-width: 640px; margin: 0 auto 48px;">
                <span class="badge badge-blue" style="margin-bottom: 12px;">Clear Patient Pathway</span>
                <h2>Your Journey to Pain-Free Living</h2>
                <p style="color: var(--body-text); font-size: 16px;">We follow a systematic, compassionate four-step protocol for every patient.</p>
            </div>

            <div class="process-grid">
                <div class="process-step">
                    <div class="step-number">01</div>
                    <h3 style="font-size: 19px; margin-bottom: 8px;">Detailed Consultation</h3>
                    <p style="font-size: 14.5px; color: var(--body-text);">Comprehensive physical examination and review of your pain history.</p>
                </div>
                <div class="process-step">
                    <div class="step-number">02</div>
                    <h3 style="font-size: 19px; margin-bottom: 8px;">Precision Diagnosis</h3>
                    <p style="font-size: 14.5px; color: var(--body-text);">In-house Digital X-Ray, OPG, and BMD testing for accurate insights.</p>
                </div>
                <div class="process-step">
                    <div class="step-number">03</div>
                    <h3 style="font-size: 19px; margin-bottom: 8px;">Targeted Treatment</h3>
                    <p style="font-size: 14.5px; color: var(--body-text);">Custom physiotherapy, non-invasive therapies, or precision surgical care.</p>
                </div>
                <div class="process-step">
                    <div class="step-number">04</div>
                    <h3 style="font-size: 19px; margin-bottom: 8px;">Guided Rehabilitation</h3>
                    <p style="font-size: 14.5px; color: var(--body-text);">Progressive exercise tracking to ensure long-term mobility and strength.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 7. Advanced Facilities Showcase -->
    <section class="section-spacing" style="background-color: var(--mist-blue);">
        <div class="container">
            <div style="text-align: center; max-width: 640px; margin: 0 auto 48px;">
                <span class="badge badge-blue" style="margin-bottom: 12px;">In-House Diagnostics</span>
                <h2>State-of-the-Art Clinical Facilities</h2>
                <p style="color: var(--body-text); font-size: 16px;">Modern diagnostic equipment ensuring accurate answers without visiting multiple centers.</p>
            </div>

            <div class="card-grid">
                <div class="medical-card">
                    <div class="card-img-wrapper">
                        <img src="https://images.unsplash.com/photo-1516549655169-df83a0774514?auto=format&fit=crop&w=600&q=80" alt="Digital X-Ray Unit" loading="lazy" decoding="async">
                    </div>
                    <div class="card-body">
                        <h3 class="card-title">High-Frequency Digital X-Ray</h3>
                        <p class="card-text">Ultra-low radiation digital radiography providing instant high-resolution bone images.</p>
                    </div>
                </div>

                <div class="medical-card">
                    <div class="card-img-wrapper">
                        <img src="https://images.unsplash.com/photo-1579684385127-1ef15d508118?auto=format&fit=crop&w=600&q=80" alt="Physiotherapy Equipment" loading="lazy" decoding="async">
                    </div>
                    <div class="card-body">
                        <h3 class="card-title">Advanced Physiotherapy Gym</h3>
                        <p class="card-text">Electrotherapy, IFT, ultrasound, and dedicated muscle rehabilitation equipment.</p>
                    </div>
                </div>

                <div class="medical-card">
                    <div class="card-img-wrapper">
                        <img src="https://images.unsplash.com/photo-1581595220892-b0739db3ba8c?auto=format&fit=crop&w=600&q=80" alt="BMD Density Scan" loading="lazy" decoding="async">
                    </div>
                    <div class="card-body">
                        <h3 class="card-title">BMD Bone Density Scan</h3>
                        <p class="card-text">Accurate osteoporosis screening and bone mineral density assessment.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 8. Treatment Explorer Grid -->
    <section class="section-spacing" style="background-color: var(--surface-white);">
        <div class="container">
            <div style="text-align: center; max-width: 640px; margin: 0 auto 48px;">
                <span class="badge badge-blue" style="margin-bottom: 12px;">Conditions We Treat</span>
                <h2>Specialized Orthopaedic Treatments</h2>
                <p style="color: var(--body-text); font-size: 16px;">Targeted interventions for joint, bone, muscle, and ligament disorders.</p>
            </div>

            <div class="card-grid">
                @foreach($globalTreatments->take(6) as $treatment)
                    <div class="medical-card">
                        <div class="card-img-wrapper">
                            @if(!empty($treatment->image))
                                <img src="{{ site_image($treatment->image) }}" alt="{{ $treatment->name }}" loading="lazy" decoding="async">
                            @else
                                <img src="https://images.unsplash.com/photo-1584515979956-d9f6e5d09982?auto=format&fit=crop&w=600&q=80" alt="{{ $treatment->name }}" loading="lazy" decoding="async">
                            @endif
                        </div>
                        <div class="card-body">
                            <span class="card-category">Orthopaedic Speciality</span>
                            <h3 class="card-title">{{ $treatment->name }}</h3>
                            <p class="card-text">{{ Str::limit(strip_tags($treatment->description), 110) }}</p>
                            <div class="card-footer">
                                <a href="{{ route('treatments.show', $treatment->slug) }}" class="card-link">
                                    Read Treatment Guide <i data-lucide="arrow-right" style="width: 16px;"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div style="text-align: center; margin-top: 40px;">
                <a href="{{ route('treatments') }}" class="btn btn-secondary btn-lg">Explore All Treatments</a>
            </div>
        </div>
    </section>

    <!-- 9. Patient Stories (Testimonials) -->
    @if(isset($testimonials) && $testimonials->count() > 0)
        <section class="section-spacing" style="background-color: var(--bg-surface);">
            <div class="container">
                <div style="text-align: center; max-width: 640px; margin: 0 auto 48px;">
                    <span class="badge badge-blue" style="margin-bottom: 12px;">Verified Patient Feedback</span>
                    <h2>Stories of Recovery & Renewed Hope</h2>
                    <p style="color: var(--body-text); font-size: 16px;">Read how our patient-first approach helped individuals regain comfortable daily living.</p>
                </div>

                <div class="card-grid">
                    @foreach($testimonials->take(3) as $testimonial)
                        <div class="testimonial-card">
                            <div style="color: #F59E0B; margin-bottom: 16px; display: flex; gap: 4px;">
                                <i data-lucide="star" style="fill: currentColor; width: 18px;"></i>
                                <i data-lucide="star" style="fill: currentColor; width: 18px;"></i>
                                <i data-lucide="star" style="fill: currentColor; width: 18px;"></i>
                                <i data-lucide="star" style="fill: currentColor; width: 18px;"></i>
                                <i data-lucide="star" style="fill: currentColor; width: 18px;"></i>
                            </div>
                            <p class="testimonial-quote">
                                "{{ Str::limit(strip_tags($testimonial->content ?? $testimonial->quote), 180) }}"
                            </p>
                            <div class="testimonial-author">
                                <div class="author-avatar">
                                    {{ substr($testimonial->patient_name ?? $testimonial->name ?? 'P', 0, 1) }}
                                </div>
                                <div class="author-info">
                                    <h4>{{ $testimonial->patient_name ?? $testimonial->name }}</h4>
                                    <span>{{ $testimonial->service_taken ?? 'Orthopaedic Patient' }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div style="text-align: center; margin-top: 40px;">
                    <a href="{{ route('testimonials') }}" class="btn btn-secondary">Read More Patient Stories</a>
                </div>
            </div>
        </section>
    @endif

    <!-- 10. Clinic Locations (Baner & Hinjewadi) -->
    <section class="section-spacing" style="background-color: var(--surface-white);">
        <div class="container">
            <div style="text-align: center; max-width: 640px; margin: 0 auto 48px;">
                <span class="badge badge-blue" style="margin-bottom: 12px;">Convenient Access</span>
                <h2>Visit SumitZ Clinic Locations</h2>
                <p style="color: var(--body-text); font-size: 16px;">Two modern clinics conveniently located to serve patients across Pune.</p>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px;">
                <div class="location-card">
                    <h3 class="location-title">
                        <i data-lucide="map-pin" style="color: var(--brand-blue);"></i> Baner Clinic
                    </h3>
                    <p style="font-size: 15px; color: var(--body-text); margin-bottom: 20px; line-height: 1.6;">
                        {{ $site['address_baner'] ?? 'First Floor, Krishna Avenue, Above Dominos Pizza, Main Baner Road, Baner, Pune, Maharashtra - 411045' }}
                    </p>
                    <div style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 24px; font-size: 14px;">
                        <div><strong>Timings:</strong> Mon - Sat: 9:00 AM - 9:00 PM</div>
                        <div><strong>Helpline:</strong> +91 95455 59434 / +91 88559 50433</div>
                    </div>
                    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                        <a href="https://maps.google.com/?q=SumitZ+Clinic+Baner+Pune" target="_blank" class="btn btn-secondary btn-sm">
                            <i data-lucide="navigation"></i> Get Directions
                        </a>
                        <a href="tel:+919545559434" class="btn btn-primary btn-sm">
                            <i data-lucide="phone"></i> Call Baner Clinic
                        </a>
                    </div>
                </div>

                <div class="location-card">
                    <h3 class="location-title">
                        <i data-lucide="map-pin" style="color: var(--brand-blue);"></i> Hinjewadi Clinic
                    </h3>
                    <p style="font-size: 15px; color: var(--body-text); margin-bottom: 20px; line-height: 1.6;">
                        {{ $site['address_hinjewadi'] ?? 'Atlanta Society, Near Hinjewadi Phase 1 Circle, Hinjewadi, Pune, Maharashtra - 411057' }}
                    </p>
                    <div style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 24px; font-size: 14px;">
                        <div><strong>Timings:</strong> Mon - Sat: 10:00 AM - 8:30 PM</div>
                        <div><strong>Helpline:</strong> +91 94049 63781 / +91 91581 43325</div>
                    </div>
                    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                        <a href="https://maps.google.com/?q=SumitZ+Clinic+Hinjewadi+Pune" target="_blank" class="btn btn-secondary btn-sm">
                            <i data-lucide="navigation"></i> Get Directions
                        </a>
                        <a href="tel:+919404963781" class="btn btn-primary btn-sm">
                            <i data-lucide="phone"></i> Call Hinjewadi Clinic
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 11. FAQ Accordion -->
    <section class="section-spacing" style="background-color: var(--brand-blue-light);">
        <div class="container" style="max-width: 800px;">
            <div style="text-align: center; margin-bottom: 40px;">
                <span class="badge badge-blue" style="margin-bottom: 12px;">Frequently Asked Questions</span>
                <h2>Common Questions About Treatment</h2>
            </div>

            <div class="accordion">
                <div class="accordion-item active">
                    <button class="accordion-header" onclick="toggleAccordion(this)">
                        Do I need a doctor's referral to book a physiotherapy session?
                        <i data-lucide="chevron-down" style="width: 20px;"></i>
                    </button>
                    <div class="accordion-body">
                        No, you can directly schedule a consultation with our experienced physiotherapists or orthopaedic specialists at our Baner or Hinjewadi clinics.
                    </div>
                </div>

                <div class="accordion-item">
                    <button class="accordion-header" onclick="toggleAccordion(this)">
                        Are digital X-ray and diagnostic scans performed at the clinic?
                        <i data-lucide="chevron-down" style="width: 20px;"></i>
                    </button>
                    <div class="accordion-body">
                        Yes! SumitZ Clinic features in-house high-frequency Digital X-Ray, Digital OPG, and BMD bone density scanning facilities for rapid diagnosis during your visit.
                    </div>
                </div>

                <div class="accordion-item">
                    <button class="accordion-header" onclick="toggleAccordion(this)">
                        What non-surgical treatment options exist for knee and joint pain?
                        <i data-lucide="chevron-down" style="width: 20px;"></i>
                    </button>
                    <div class="accordion-body">
                        We offer customized conservative care including therapeutic electrotherapy, targeted joint exercises, posture correction, intra-articular injections, and lifestyle management before considering surgical options.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 12. Final CTA Banner -->
    <section class="section-spacing" style="background: linear-gradient(135deg, var(--primary-navy) 0%, #071A2E 100%); color: white; text-align: center;">
        <div class="container" style="max-width: 760px;">
            <span class="badge badge-blue" style="background: rgba(255,255,255,0.15); color: #fff; border: none; margin-bottom: 16px;">Take The Next Step</span>
            <h2 style="color: white; font-size: 38px; margin-bottom: 16px;">Ready to Live Without Limiting Joint or Spine Pain?</h2>
            <p style="color: rgba(255,255,255,0.85); font-size: 18px; margin-bottom: 32px; line-height: 1.6;">
                Book an appointment at SumitZ Clinic Baner or Hinjewadi for expert consultation and personalized recovery planning.
            </p>
            <div style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
                <button onclick="openDialog('callback-dialog');" class="btn btn-primary btn-lg" style="background: white; color: var(--primary-navy);">
                    <i data-lucide="calendar"></i> Book Appointment Now
                </button>
                <a href="tel:{{ $site['phone'] ?? '+919545559434' }}" class="btn btn-secondary btn-lg" style="color: white; border-color: rgba(255,255,255,0.4);">
                    <i data-lucide="phone"></i> Call {{ $site['phone_display'] ?? ($site['phone'] ?? '+91 95455 59434') }}
                </a>
            </div>
        </div>
    </section>

    <script>
        function toggleAccordion(button) {
            const item = button.parentElement;
            const isActive = item.classList.contains('active');
            
            document.querySelectorAll('.accordion-item').forEach(el => el.classList.remove('active'));
            
            if (!isActive) {
                item.classList.add('active');
            }
        }
    </script>
@endsection
