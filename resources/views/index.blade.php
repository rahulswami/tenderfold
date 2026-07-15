@extends('layouts.app')

@section('title', 'SumitZ Orthopaedic & Physiotherapy Clinic | Baner Pune')
@section('meta_description', 'SumitZ Clinic is Baner\'s leading orthopaedic and physiotherapy hospital. Under Dr. Sumit, we offer expert joint replacement, fracture care, spine treatment, and sports rehabilitation.')

@section('schema')
@if(!empty($site['home_schema_markup']))
{!! $site['home_schema_markup'] !!}
@else
<script type="application/ld+json">
{
  "{{ '@' }}context": "https://schema.org",
  "{{ '@graph' }}": [
    {
      "{{ '@type' }}": "MedicalBusiness",
      "{{ '@id' }}": "{{ route('home') }}/#organization",
      "name": "{{ $site['site_name'] ?? 'SumitZ Clinic' }}",
      "url": "{{ route('home') }}",
      "logo": {
        "{{ '@type' }}": "ImageObject",
        "{{ '@id' }}": "{{ route('home') }}/#logo",
        "url": "{{ !empty($site['logo_path']) ? site_image($site['logo_path']) : asset('assets/images/logo.png') }}",
        "caption": "{{ $site['site_name'] ?? 'SumitZ Clinic' }}"
      },
      "telephone": "{{ $site['phone'] ?? '+919672777749' }}",
      "address": {
        "{{ '@type' }}": "PostalAddress",
        "streetAddress": "{{ $site['address'] ?? 'Krishna Avenue, Above Dominos, Baner Road, Baner, Pune - 411045' }}",
        "addressLocality": "Pune",
        "addressRegion": "Maharashtra",
        "postalCode": "411045",
        "addressCountry": "IN"
      }
    },
    {
      "{{ '@type' }}": "WebSite",
      "{{ '@id' }}": "{{ route('home') }}/#website",
      "url": "{{ route('home') }}",
      "name": "{{ $site['site_name'] ?? 'SumitZ Clinic' }}",
      "publisher": {
        "{{ '@id' }}": "{{ route('home') }}/#organization"
      }
    }
  ]
}
</script>
@endif
@endsection

@section('content')

    <!-- Hero Slider Section -->
    @if(isset($slides) && $slides->count() > 0)
        <section class="hero hero-slider-container" style="background: linear-gradient(135deg, #f0fcf9 0%, #ffffff 100%); padding: 10rem 0 6rem 0; position: relative; overflow: hidden;">
            <div class="parallax-shape shape-1"></div>
            <div class="parallax-shape shape-2"></div>
            <div class="hero-slider-track" style="display: flex; transition: transform 0.5s ease-in-out; width: {{ $slides->count() * 100 }}%;">
                @foreach($slides as $index => $slide)
                    <div class="hero-slide-item" style="width: calc(100% / {{ $slides->count() }}); flex-shrink: 0;">
                        <div class="container" style="max-width: 900px; margin: 0 auto; padding: 0 1.5rem;">
                            <div class="hero-content" style="text-align: center; width: 100%;">
                                <span class="color-mint font-semibold" style="text-transform: uppercase; letter-spacing: 1.5px; font-size: 0.95rem; display: block; margin-bottom: 1rem;">Welcome to SumitZ Clinic</span>
                                <h1 style="font-size: 3.2rem; line-height: 1.2; margin-bottom: 1.5rem; color: var(--primary-teal-dark); font-weight: 800; letter-spacing: -0.5px;">
                                    {{ $slide->title }}
                                </h1>
                                <p class="subtitle" style="font-size: 1.2rem; color: var(--text-muted); margin-bottom: 2.5rem; line-height: 1.6; max-width: 700px; margin-left: auto; margin-right: auto;">
                                    {{ $slide->subtitle }}
                                </p>
                                <div class="hero-buttons" style="display: flex; gap: 1.25rem; justify-content: center;">
                                    @if($slide->button_text)
                                        <button onclick="document.getElementById('callback-dialog').style.display='flex';" class="btn btn-primary">{{ $slide->button_text }} <i data-lucide="arrow-right"></i></button>
                                    @endif
                                    <a href="tel:{{ $site['phone'] ?? '+919672777749' }}" class="btn btn-secondary"><i data-lucide="phone"></i> Call Doctor</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Slider controls -->
            @if($slides->count() > 1)
                <button class="hero-slider-btn prev" onclick="moveHeroSlide(-1)"><i data-lucide="chevron-left"></i></button>
                <button class="hero-slider-btn next" onclick="moveHeroSlide(1)"><i data-lucide="chevron-right"></i></button>
                <div class="hero-slider-dots">
                    @foreach($slides as $index => $slide)
                        <span class="hero-dot @if($index === 0) active @endif" onclick="setHeroSlide({{ $index }})"></span>
                    @endforeach
                </div>
            @endif

            <!-- Global Static Trust Badges -->
            <div class="container hero-global-trust" style="margin-top: 4rem; position: relative; z-index: 5;">
                <div class="hero-trust">
                    <div class="trust-item">
                        <span class="trust-num">20,000+</span>
                        <span class="trust-label">Happy Patients</span>
                    </div>
                    <div class="trust-item">
                        <span class="trust-num">15+ Years</span>
                        <span class="trust-label">Clinical Experience</span>
                    </div>
                    <div class="trust-item">
                        <span class="trust-num">100%</span>
                        <span class="trust-label">Recovery Focus</span>
                    </div>
                </div>
            </div>
        </section>
    @else
        <!-- Static Hero Section (Fallback) -->
        <section class="hero" style="background: linear-gradient(135deg, #f0fcf9 0%, #ffffff 100%); padding: 10rem 0 6rem 0; text-align: center; position: relative; overflow: hidden;">
            <div class="parallax-shape shape-1"></div>
            <div class="parallax-shape shape-2"></div>
            <div class="container" style="max-width: 800px; margin: 0 auto; padding: 0 1.5rem;">
                <div class="hero-content" style="text-align: center; width: 100%;">
                    <span class="color-mint font-semibold" style="text-transform: uppercase; letter-spacing: 1.5px; font-size: 0.95rem; display: block; margin-bottom: 1rem;">Welcome to SumitZ Clinic</span>
                    <h1 style="font-size: 3rem; line-height: 1.2; margin-bottom: 1.5rem; color: var(--primary-teal-dark); font-weight: 800; letter-spacing: -0.5px;">
                        Pune's Leading Joint & Spine Recovery Center
                    </h1>
                    <p class="subtitle" style="font-size: 1.2rem; color: var(--text-muted); margin-bottom: 2.5rem; line-height: 1.6; max-width: 700px; margin-left: auto; margin-right: auto;">
                        Specialist Orthopaedic treatments and customized Physiotherapy rehabilitation programs designed to restore your pain-free active lifestyle.
                    </p>
                    <div class="hero-buttons" style="display: flex; gap: 1.25rem; justify-content: center;">
                        <button onclick="document.getElementById('callback-dialog').style.display='flex';" class="btn btn-primary">Book Consultation <i data-lucide="arrow-right"></i></button>
                        <a href="tel:{{ $site['phone'] ?? '+919672777749' }}" class="btn btn-secondary"><i data-lucide="phone"></i> Call Doctor</a>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Services Overview Section -->
    <section class="section" id="services" style="background: #ffffff;">
        <div class="container">
            <div class="section-header animate-on-scroll">
                <h2>Our Premium Healthcare Facilities</h2>
                <p>Equipped with advanced diagnostic machines and experienced practitioners, we offer specialized clinical services under one roof.</p>
            </div>
            
            <div class="services-grid">
                @foreach($globalServices as $gs)
                    <div class="service-card animate-on-scroll">
                        <div class="service-icon" style="color: var(--accent-teal);">
                            <i data-lucide="activity"></i>
                        </div>
                        <h3>{{ $gs->service_name }}</h3>
                        <p>{{ $gs->summary }}</p>
                        <a href="{{ route('services.show', $gs->slug) }}" class="service-link">Learn Details <i data-lucide="chevron-right"></i></a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Treatments Showcase Section -->
    <section class="section" id="treatments" style="background: #f8fbfb;">
        <div class="container">
            <div class="section-header animate-on-scroll">
                <h2>Advanced Orthopaedic Treatments</h2>
                <p>We treat a wide range of joint, muscle, spine and fracture conditions using state-of-the-art non-invasive and surgical techniques.</p>
            </div>
            
            <div class="services-grid">
                @foreach($globalTreatments->take(6) as $gt)
                    <div class="service-card animate-on-scroll" style="border-top: 3px solid var(--accent-teal);">
                        @if($gt->image)
                            <div style="height: 120px; overflow: hidden; border-radius: var(--radius-sm); margin-bottom: 1rem;">
                                <img src="{{ site_image($gt->image) }}" alt="{{ $gt->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        @endif
                        <h3>{{ $gt->name }}</h3>
                        <p>{{ Str::limit(strip_tags($gt->description), 120) }}</p>
                        <a href="{{ route('treatments.show', $gt->slug) }}" class="service-link">Learn More <i data-lucide="chevron-right"></i></a>
                    </div>
                @endforeach
            </div>
            <div class="text-center" style="margin-top: 3rem;">
                <a href="{{ route('treatments') }}" class="btn btn-secondary">Explore All Treatment Options</a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="section section-bg-dark" style="background: #0d1e1f; color: #ffffff;">
        <div class="container about-grid">
            <div class="about-visual animate-on-scroll">
                <div class="about-img-box" style="background: linear-gradient(135deg, var(--accent-teal) 0%, var(--primary-teal-dark) 100%); height: 350px; display: flex; align-items: center; justify-content: center; color: var(--text-light); border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-md);">
                    <div class="text-center" style="padding: 2rem;">
                        <i data-lucide="heart-pulse" style="width: 60px; height: 60px; color: var(--mint-green); margin-bottom: 1.5rem;"></i>
                        <h3>Your Movement Is Our Mission</h3>
                        <p style="font-size: 0.9rem; color: var(--text-light-muted); margin-top: 10px;">We help you recover faster through certified experts, personalized exercises, and persistent clinical tracking.</p>
                    </div>
                </div>
                <div class="about-badge">
                    <span class="about-badge-num">15+</span>
                    <span class="about-badge-label"><br>Years of Clinical<br>Excellence</span>
                </div>
            </div>
            
            <div class="about-content animate-on-scroll">
                <span class="pre-title" style="color: var(--accent-teal);">ABOUT SUMITZ CLINIC</span>
                <h2>Dedicated to Orthopaedic Rehabilitation</h2>
                <p>SumitZ Clinic is Baner's premier healthcare provider specializing in advanced Orthopaedics, joint pathology, and comprehensive Physiotherapy care. Directed by our Chief Specialist, we pride ourselves on creating personalized therapy pathways that avoid surgery wherever possible.</p>
                <p>Our multidisciplinary team incorporates advanced diagnostics (Digital OPG, X-ray) with modern physiotherapy machinery (BMD, Ultrasound) to target muscle pain, arthritis, and sports injuries effectively.</p>
                
                <div class="about-bullets">
                    <div class="about-bullet-item">
                        <i data-lucide="check" class="bullet-icon" style="color: var(--accent-teal);"></i>
                        <div>
                            <strong>Qualified Experts:</strong> Our staff is composed of certified specialists dedicated to spine, muscle, joint, and general rehabilitation.
                        </div>
                    </div>
                    <div class="about-bullet-item">
                        <i data-lucide="check" class="bullet-icon" style="color: var(--accent-teal);"></i>
                        <div>
                            <strong>Fully Integrated Diagnostics:</strong> Outfitted with in-house labs and digital radiography for immediate, accurate diagnostic reports.
                        </div>
                    </div>
                </div>
                
                <a href="{{ route('about') }}" class="btn btn-primary" style="margin-top: 1rem;">Meet Our Specialists</a>
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="section" style="background: #ffffff;">
        <div class="container">
            <div class="section-header animate-on-scroll">
                <h2>Why Patients Choose SumitZ</h2>
                <p>We blend diagnostic precision, modern equipment, and compassionate clinical tracking to deliver rapid patient recovery.</p>
            </div>
            
            <div class="why-grid">
                <div class="why-card animate-on-scroll">
                    <div class="why-icon" style="background: rgba(92, 203, 179, 0.1); color: var(--accent-teal);"><i data-lucide="user-check"></i></div>
                    <div class="why-info">
                        <h3>Patient-Centric Care</h3>
                        <p>We craft unique clinical plans focusing on your daily movement and painless recovery goals.</p>
                    </div>
                </div>
                
                <div class="why-card animate-on-scroll">
                    <div class="why-icon" style="background: rgba(92, 203, 179, 0.1); color: var(--accent-teal);"><i data-lucide="activity"></i></div>
                    <div class="why-info">
                        <h3>Modern Physiotherapy</h3>
                        <p>Equipped with computerized therapy units for advanced healing across joints and spine.</p>
                    </div>
                </div>
                
                <div class="why-card animate-on-scroll">
                    <div class="why-icon" style="background: rgba(92, 203, 179, 0.1); color: var(--accent-teal);"><i data-lucide="stethoscope"></i></div>
                    <div class="why-info">
                        <h3>Orthopaedic Specialists</h3>
                        <p>Direct consultations and surgery plans from qualified specialists and expert surgeons.</p>
                    </div>
                </div>
                
                <div class="why-card animate-on-scroll">
                    <div class="why-icon" style="background: rgba(92, 203, 179, 0.1); color: var(--accent-teal);"><i data-lucide="calendar"></i></div>
                    <div class="why-info">
                        <h3>Prioritized Slot Booking</h3>
                        <p>Seamless online booking system ensures you meet our specialists with minimal waiting periods.</p>
                    </div>
                </div>
                
                <div class="why-card animate-on-scroll">
                    <div class="why-icon" style="background: rgba(92, 203, 179, 0.1); color: var(--accent-teal);"><i data-lucide="file-text"></i></div>
                    <div class="why-info">
                        <h3>Accurate Diagnostics</h3>
                        <p>Fully certified in-house laboratories, digital radiography and BMD machines.</p>
                    </div>
                </div>
                
                <div class="why-card animate-on-scroll">
                    <div class="why-icon" style="background: rgba(92, 203, 179, 0.1); color: var(--accent-teal);"><i data-lucide="heart"></i></div>
                    <div class="why-info">
                        <h3>Post-Treatment Tracking</h3>
                        <p>Continuous recovery tracking and home exercise blueprints shared by our therapists.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="section testimonials-section" style="background: #f4f8f8;">
        <div class="container">
            <div class="section-header animate-on-scroll">
                <h2>Real Patient Stories</h2>
                <p>Hear from individuals who recovered their mobility and returned to pain-free living through our custom therapies.</p>
            </div>
            
            <div class="carousel-container animate-on-scroll">
                <div class="carousel-track-wrapper">
                    <div class="carousel-track">
                        @foreach($testimonials as $test)
                            <div class="testimonial-slide">
                                <div class="testimonial-card">
                                    <div class="testimonial-rating" style="color: #ffb100; margin-bottom: 1rem;">
                                        @for($i = 0; $i < $test->rating; $i++)★@endfor
                                    </div>
                                    <p class="testimonial-text">"{{ $test->content }}"</p>
                                    <div class="testimonial-author">
                                        <div class="author-img" style="background-color: var(--accent-teal); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; overflow: hidden; border-radius: 50%; width: 44px; height: 44px;">
                                            @if($test->avatar_path)
                                                <img src="{{ site_image($test->avatar_path) }}" alt="{{ $test->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                            @else
                                                {{ substr($test->name, 0, 2) }}
                                            @endif
                                        </div>
                                        <div class="author-info">
                                            <h4>{{ $test->name }}</h4>
                                            <p>{{ $test->role }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                @if($testimonials->count() > 1)
                    <button class="carousel-btn carousel-btn-prev" aria-label="Previous Slide"><i data-lucide="chevron-left"></i></button>
                    <button class="carousel-btn carousel-btn-next" aria-label="Next Slide"><i data-lucide="chevron-right"></i></button>
                    <div class="carousel-dots"></div>
                @endif
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="section" style="background: #ffffff;">
        <div class="container">
            <div class="section-header animate-on-scroll">
                <h2>Frequently Asked Questions</h2>
                <p>Quick answers to common queries regarding clinic hours, appointments, and diagnostic procedures.</p>
            </div>
            
            <div class="faq-accordion animate-on-scroll">
                <div class="faq-item">
                    <div class="faq-header">
                        <span class="faq-title">What are the clinic timings for consultations?</span>
                        <div class="faq-toggle"></div>
                    </div>
                    <div class="faq-body">
                        <div class="faq-content">
                            <p>SumitZ Clinic is open from Monday to Saturday, 9:00 AM to 1:00 PM and 5:00 PM to 8:30 PM. We recommend calling in advance or booking an appointment online to prevent long waiting times.</p>
                        </div>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-header">
                        <span class="faq-title">Do I need an appointment for X-rays or Laboratory tests?</span>
                        <div class="faq-toggle"></div>
                    </div>
                    <div class="faq-body">
                        <div class="faq-content">
                            <p>No, diagnostic services like Digital OPG, X-rays, and Pathology laboratory collections are open for walk-ins during clinic hours. However, having a specialist prescription speeds up the reporting cycle.</p>
                        </div>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-header">
                        <span class="faq-title">What types of sports injury treatments are available?</span>
                        <div class="faq-toggle"></div>
                    </div>
                    <div class="faq-body">
                        <div class="faq-content">
                            <p>We provide advanced sports injury management using modern physiotherapy modalities, muscle stimulation, dry needling, custom braces, and customized exercises to return athletes safely to their respective sports.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="final-cta-section" style="background: linear-gradient(135deg, #0d1e1f 0%, #163638 100%); position: relative; overflow: hidden;">
        <div class="parallax-bg-layer"></div>
        <div class="final-cta-wrapper animate-on-scroll">
            <h2>Struggling with Joint or Muscle Pain?</h2>
            <p>Speak to our medical coordinator today. Get a customized treatment plan or book an expert consult session.</p>
            <div class="final-cta-buttons">
                <button onclick="document.getElementById('callback-dialog').style.display='flex';" class="btn btn-white">Book Appointment Now</button>
                <a href="{{ route('contact') }}" class="btn btn-outline-white"><i data-lucide="phone"></i> Contact Clinic</a>
            </div>
            <div class="final-cta-trust">
                <i data-lucide="check-circle" style="display:inline-block; vertical-align:middle; margin-right:5px; width:16px; height:16px;"></i> High quality joint care. Multi-speciality medical experts.
            </div>
        </div>
    </section>

@endsection

@section('scripts')
    @if(isset($slides) && $slides->count() > 1)
        <script>
            let currentHeroSlide = 0;
            const heroSlidesCount = {{ $slides->count() }};
            function updateHeroSlider() {
                const track = document.querySelector('.hero-slider-track');
                if (!track) return;
                track.style.transform = `translateX(-${currentHeroSlide * (100 / heroSlidesCount)}%)`;
                
                // Update dots
                const dots = document.querySelectorAll('.hero-dot');
                dots.forEach((dot, idx) => {
                    if (idx === currentHeroSlide) {
                        dot.classList.add('active');
                    } else {
                        dot.classList.remove('active');
                    }
                });
            }
            function moveHeroSlide(direction) {
                currentHeroSlide = (currentHeroSlide + direction + heroSlidesCount) % heroSlidesCount;
                updateHeroSlider();
            }
            function setHeroSlide(index) {
                currentHeroSlide = index;
                updateHeroSlider();
            }
            document.addEventListener('DOMContentLoaded', () => {
                updateHeroSlider();
                // Auto-play every 6 seconds
                setInterval(() => {
                    moveHeroSlide(1);
                }, 6000);
            });
        </script>
    @endif
@endsection
