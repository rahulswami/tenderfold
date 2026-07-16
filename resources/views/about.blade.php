@extends('layouts.app')

@section('title', ($aboutPage->metatitle ?? 'About SumitZ Clinic | Orthopaedic & Physiotherapy Center Baner'))
@section('meta_description', ($aboutPage->metadescription ?? 'About SumitZ Clinic is Baner\'s leading joint and spinal recovery center led by Dr. Sumit, offering advanced diagnostic OPG, X-ray, lab tests, and physiotherapy.'))

@section('schema')
<script type="application/ld+json">
{
  "{{ '@' }}context": "https://schema.org",
  "{{ '@graph' }}": [
    {
      "{{ '@type' }}": "AboutPage",
      "{{ '@id' }}": "{{ route('about') }}/#webpage",
      "url": "{{ route('about') }}",
      "name": "About SumitZ Clinic | Orthopaedic & Physiotherapy Center Baner",
      "description": "SumitZ Clinic is Baner's leading joint and spinal recovery center led by Dr. Sumit, offering advanced diagnostic OPG, X-ray, lab tests, and physiotherapy.",
      "isPartOf": {
        "{{ '@type' }}": "WebSite",
        "{{ '@id' }}": "{{ route('home') }}/#website"
      },
      "mainEntity": {
        "{{ '@type' }}": "Organization",
        "{{ '@id' }}": "{{ route('home') }}/#organization",
        "name": "{{ $site['site_name'] ?? 'SumitZ Clinic' }}",
        "url": "{{ route('home') }}"
      }
    }
  ]
}
</script>
@endsection

@section('content')

    <!-- Details Hero -->
    <section class="details-hero">
        <div class="container text-center">
            <span class="color-mint font-semibold" style="text-transform: uppercase; letter-spacing: 1.5px; font-size: 0.9rem;">WHO WE ARE</span>
            <h1 style="margin-top: 10px;">About {{ $site['site_name'] ?? 'SumitZ Clinic' }}</h1>
            <p style="color: var(--text-light-muted); max-width: 600px; margin: 0 auto;">Baner's premier joint care, diagnostic facility, and advanced physiotherapy center.</p>
        </div>
    </section>

    <!-- Story Section -->
    <section class="section">
        <div class="container about-grid">
            <div class="about-content animate-on-scroll">
                <span class="pre-title">Our Story</span>
                <h2>Restoring Mobility & Pain-Free Living</h2>
                <p>SumitZ Clinic was founded with a singular mission: to provide high-quality joint recovery, diagnostics, and physiotherapy under one roof. We believe that recovery is not just about relieving temporary pain—it is about restoring joint pathology, correct alignment, muscle strength, and long-term functional mobility.</p>
                <p>Over the last 15 years, we have built a reputation in Pune as a patient-centric clinic that prioritizes non-surgical recovery pathways wherever possible. We combine digital radiography (OPG), pathology, BMD bone scans, and physiotherapy modalities to achieve complete healing.</p>
                <p>Whether you are dealing with chronic back pain, sports-related muscle strains, knee osteoarthritis, or requiring post-surgery fracture rehabilitation, our expert therapists construct customized plans for your recovery.</p>
            </div>
            
            <div class="about-visual animate-on-scroll">
                <div class="about-img-box" style="background: linear-gradient(135deg, var(--accent-teal) 0%, var(--primary-teal-dark) 100%); height: 320px; display: flex; align-items: center; justify-content: center; color: var(--text-light); border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-sm);">
                    <div class="text-center" style="padding: 2rem;">
                        <i data-lucide="award" style="width: 50px; height: 50px; color: var(--mint-green); margin-bottom: 1rem;"></i>
                        <h3>Certified Specialists</h3>
                        <p style="font-size: 0.9rem; color: var(--text-light-muted); margin-top: 10px;">All our therapy programs are managed by qualified experts specializing in musculoskeletal medicine.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Vision & Mission -->
    <section class="section" style="background-color: #fafdfd;">
        <div class="container">
            <div class="benefits-grid">
                <div class="benefit-block animate-on-scroll">
                    <div class="why-icon" style="margin-bottom: 1.5rem; background: rgba(92,203,179,0.1); color: var(--accent-teal);"><i data-lucide="eye"></i></div>
                    <h3>Our Vision</h3>
                    <p>To be Pune's most trusted orthopaedic recovery hub, helping thousands of patients restore their painless mobility and live an active, healthy life.</p>
                </div>
                
                <div class="benefit-block highlight animate-on-scroll" style="background: var(--primary-teal-dark);">
                    <div class="why-icon" style="margin-bottom: 1.5rem; background-color: rgba(255,255,255,0.1); color: var(--mint-green);"><i data-lucide="target"></i></div>
                    <h3>Our Mission</h3>
                    <p>To deliver precise diagnostic reports, target root causes of joint/muscle pains, and outline highly effective, non-invasive physiotherapy exercises.</p>
                </div>
                
                <div class="benefit-block animate-on-scroll">
                    <div class="why-icon" style="margin-bottom: 1.5rem; background: rgba(92,203,179,0.1); color: var(--accent-teal);"><i data-lucide="heart"></i></div>
                    <h3>Our Core Values</h3>
                    <p>Patient health is our priority. We maintain objective clinical assessments, complete pricing transparency, and the highest standards of diagnostic hygiene.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Specialists Highlights Section -->
    <section class="section">
        <div class="container">
            <div class="section-header animate-on-scroll">
                <h2>Our Specialists & Therapists</h2>
                <p>Consult directly with our experienced medical professionals to target joint stiffness, fractures, and spinal disc conditions.</p>
            </div>
            
            <div class="why-grid" style="grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));">
                @foreach($globalSpecialists as $gspec)
                    <div class="service-card text-center animate-on-scroll" style="display: flex; flex-direction: column; align-items: center;">
                        <div style="width:100px; height:100px; border-radius:50%; overflow:hidden; background-color:var(--primary-teal-light); margin:0 auto 1.5rem auto; display:flex; align-items:center; justify-content:center; border: 3px solid var(--accent-teal);">
                            @if($gspec->image)
                                <img src="{{ site_image($gspec->image) }}" alt="{{ $gspec->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <i data-lucide="user" style="width: 40px; height: 40px; color: white;"></i>
                            @endif
                        </div>
                        <h3>{{ $gspec->name }}</h3>
                        <span class="color-accent" style="font-size:0.85rem; font-weight:600; display:block; margin-bottom:1rem; color: var(--accent-teal);">{{ $gspec->designation }}</span>
                        <p style="font-size:0.85rem; line-height: 1.6; color: var(--text-muted);">
                            {{ Str::limit(strip_tags($gspec->description), 100) }}
                        </p>
                        <a href="{{ route('specialists.show', $gspec->slug) }}" class="btn btn-secondary btn-sm" style="margin-top: 1rem; width: 100%;">View Profile</a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Final Call to Action -->
    <section class="final-cta-section" style="background: linear-gradient(135deg, #0d1e1f 0%, #163638 100%);">
        <div class="final-cta-wrapper animate-on-scroll">
            <h2>Ready to Consult a Specialist?</h2>
            <p>Skip the waiting queues. Book a prioritized slots consultation with Dr. Sumit or our physiotherapists today.</p>
            <div class="final-cta-buttons">
                <button onclick="openDialog('callback-dialog');" class="btn btn-white">Book Appointment</button>
                <a href="{{ route('contact') }}" class="btn btn-outline-white"><i data-lucide="phone"></i> Contact Clinic</a>
            </div>
        </div>
    </section>

@endsection
