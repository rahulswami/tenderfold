@extends('layouts.app')

@section('title', ($homePage->metatitle ?? 'Dr. Sumit — Best Orthopedic Doctor in Baner, Balewadi & Pune | SumitZ Clinic'))
@section('meta_description', ($homePage->metadescription ?? 'SumitZ Clinic is Pune\'s top-rated Orthopedic & Physiotherapy clinic in Baner and Balewadi, led by Dr. Sumit. Expert in joint replacement, knee surgery, spine care, fractures, and sports injury rehabilitation.'))
@section('meta_keywords', ($homePage->metakeywords ?? 'best orthopedic doctor in baner pune, best orthopedic clinic baner balewadi pune, dr sumit orthopedic surgeon baner, knee replacement surgeon pune, joint replacement clinic balewadi, physiotherapy clinic baner pune'))

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
      "name": "{{ $site['site_name'] ?? 'SumitZ Orthopaedic & Physiotherapy Clinic' }}",
      "url": "{{ route('home') }}",
      "logo": {
        "{{ '@type' }}": "ImageObject",
        "url": "{{ !empty($site['logo_path']) ? site_image($site['logo_path']) : asset('assets/images/logo.png') }}"
      },
      "telephone": "{{ $site['phone'] ?? '+919545559434' }}",
      "description": "Best Orthopedic Doctor and Clinic in Baner, Balewadi, Pune. Dr. Sumit offers joint replacement, knee surgery, spine care, and physiotherapy.",
      "medicalSpecialty": ["Orthopedic Surgery", "Physiotherapy", "Sports Medicine"],
      "address": {
        "{{ '@type' }}": "PostalAddress",
        "streetAddress": "First Floor, Krishna Avenue, Baner Road",
        "addressLocality": "Baner, Pune",
        "addressRegion": "Maharashtra",
        "postalCode": "411045",
        "addressCountry": "IN"
      },
      "geo": {
        "{{ '@type' }}": "GeoCoordinates",
        "latitude": "18.5590",
        "longitude": "73.7868"
      },
      "openingHoursSpecification": [
        {
          "{{ '@type' }}": "OpeningHoursSpecification",
          "dayOfWeek": ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],
          "opens": "09:00",
          "closes": "21:00"
        }
      ]
    },
    {
      "{{ '@type' }}": "Physician",
      "name": "Dr. Sumit",
      "medicalSpecialty": "Orthopedic Surgery",
      "worksFor": {
        "{{ '@type' }}": "MedicalClinic",
        "name": "{{ $site['site_name'] ?? 'SumitZ Clinic' }}"
      },
      "url": "{{ route('home') }}"
    }
  ]
}
</script>
@endif
@endsection

@section('styles')
<style>
    /* ─── HERO SLIDER ─────────────────────────────────────────────────── */
    .hero-slider-section {
        position: relative;
        background: var(--primary-navy);
        overflow: hidden;
        min-height: 500px;
        display: flex;
        align-items: stretch;
    }
    .slider-track {
        position: relative;
        width: 100%;
        min-height: 500px;
    }
    /* Every slide sits absolute/full-bleed inside the track */
    .slide-item {
        position: absolute;
        inset: 0;
        opacity: 0;
        transition: opacity 0.8s ease-in-out;
        pointer-events: none;
        display: flex;
        align-items: center;
    }
    /* Active slide on top — keep position: absolute so width = track width */
    .slide-item.active {
        opacity: 1;
        pointer-events: auto;
        z-index: 2;
    }
    .hero-bg-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(120deg, rgba(7,26,46,0.92) 0%, rgba(0,133,200,0.18) 100%);
        z-index: 1;
    }
    .slide-bg-pic {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        z-index: 0;
        display: block;
    }
    .slide-bg-img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: 0;
        filter: brightness(0.55);
    }
    /* Content wrapper sits above overlay */
    .slide-content-wrap {
        position: relative;
        z-index: 3;
        width: 100%;
    }
    .hero-inner {
        display: grid;
        grid-template-columns: 1.15fr 0.85fr;
        gap: 40px;
        align-items: center;
        padding: 46px 0 42px;
    }
    @media (max-width: 900px) {
        .hero-inner { grid-template-columns: 1fr; gap: 16px; padding: 28px 0 24px; }
        .hero-visual-panel { display: none; }
        .hero-slider-section { min-height: unset; }
        .slider-track { min-height: unset; height: auto; }
        .slide-item { position: relative; inset: unset; opacity: 1; pointer-events: auto; }
        .slide-item:not(.active) { display: none; }
        .slide-bg-pic { position: absolute; inset: 0; height: 100%; }
        .slide-bg-img { position: absolute; inset: 0; height: 100%; }
        .hero-h1 { font-size: clamp(22px, 6vw, 30px); margin-bottom: 10px; }
        .hero-desc { font-size: 14px; margin-bottom: 18px; line-height: 1.5; }
        .hero-btn-row { gap: 10px; margin-bottom: 20px; }
        .hero-btn-primary, .hero-btn-secondary { padding: 11px 18px; font-size: 13.5px; width: 100%; justify-content: center; }
        .hero-trust-strip { gap: 16px; }
        .hero-trust-num { font-size: 20px; }
        .hero-trust-lbl { font-size: 11px; }
    }
    .hero-eyebrow-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(0,133,200,0.18);
        border: 1px solid rgba(0,133,200,0.4);
        color: #7DD3FC;
        padding: 5px 14px;
        border-radius: 999px;
        font-size: 12.5px;
        font-weight: 600;
        margin-bottom: 12px;
        letter-spacing: 0.3px;
    }
    .hero-h1 {
        font-size: clamp(26px, 3.4vw, 44px);
        font-weight: 800;
        color: #fff;
        line-height: 1.18;
        margin-bottom: 12px;
        letter-spacing: -0.5px;
    }
    .hero-h1 .highlight-blue { color: #38BDF8; }
    .hero-h1 .highlight-red { color: #FB7185; }
    .hero-desc {
        font-size: 15px;
        color: rgba(255,255,255,0.85);
        line-height: 1.6;
        margin-bottom: 22px;
        max-width: 520px;
    }
    .hero-btn-row {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 26px;
    }
    .hero-btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: var(--brand-blue);
        color: #fff;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 14.5px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: background 0.2s, transform 0.2s;
    }
    .hero-btn-primary:hover { background: #006ca5; transform: translateY(-2px); }
    .hero-btn-secondary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255,255,255,0.1);
        color: #fff;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14.5px;
        text-decoration: none;
        border: 1px solid rgba(255,255,255,0.35);
        cursor: pointer;
        transition: background 0.2s;
    }
    .hero-btn-secondary:hover { background: rgba(255,255,255,0.2); }
    .hero-trust-strip {
        display: flex;
        gap: 26px;
        flex-wrap: wrap;
    }
    .hero-trust-item { color: rgba(255,255,255,0.9); }
    .hero-trust-num {
        font-size: 22px;
        font-weight: 800;
        color: #38BDF8;
        display: block;
        line-height: 1;
        margin-bottom: 2px;
    }
    .hero-trust-lbl { font-size: 11.5px; color: rgba(255,255,255,0.65); font-weight: 500; }
    /* Hero image panel */
    .hero-visual-panel { position: relative; }
    .hero-img-frame {
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 24px 64px rgba(0,0,0,0.4);
        aspect-ratio: 4/5;
        max-height: 520px;
    }
    .hero-img-frame img { width: 100%; height: 100%; object-fit: cover; }
    .hero-float-badge {
        position: absolute;
        bottom: 28px;
        left: -24px;
        background: #fff;
        border-radius: 12px;
        padding: 14px 18px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.18);
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 220px;
    }
    .hero-float-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: var(--brand-blue-light);
        color: var(--brand-blue);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .hero-rating-badge {
        position: absolute;
        top: 28px;
        right: -16px;
        background: #0A2540;
        color: #fff;
        border-radius: 12px;
        padding: 10px 16px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.3);
        text-align: center;
    }
    /* Slider Controls */
    .slider-nav-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 10;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.3);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.2s;
        backdrop-filter: blur(4px);
    }
    .slider-nav-btn:hover { background: rgba(0,133,200,0.7); }
    .slider-prev { left: 16px; }
    .slider-next { right: 16px; }
    .slider-dots {
        position: absolute;
        bottom: 24px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 8px;
        z-index: 10;
    }
    .slider-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: rgba(255,255,255,0.4);
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        padding: 0;
    }
    .slider-dot.active {
        background: var(--brand-blue);
        width: 24px;
        border-radius: 4px;
    }
    /* ─── SYMPTOM FINDER ─────────────────────────────────────────────── */
    .symptom-finder-section { background: var(--surface-white); padding: 0; }
    .symptom-card-wrapper {
        background: #fff;
        border-radius: 0 0 20px 20px;
        box-shadow: 0 8px 32px rgba(10,37,64,0.08);
        padding: 24px 32px;
    }
    .symptom-card-top {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 18px;
        flex-wrap: wrap;
    }
    .symptom-header { flex-shrink: 0; }
    .symptom-header h2 { font-size: 15px; margin: 0; color: var(--heading-navy); }
    .symptom-grid {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    .symptom-item {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: var(--brand-blue-light);
        color: var(--brand-blue);
        padding: 9px 16px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.2s;
        white-space: nowrap;
    }
    .symptom-item:hover { background: var(--brand-blue); color: #fff; transform: translateY(-2px); }
    .symptom-icon { width: 15px; height: 15px; flex-shrink: 0; }
    /* ─── SPECIALISTS GRID (SECTION A) ─────────────────────────────────── */
    .specialist-card-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 24px;
    }
    .specialist-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid var(--border-color);
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(10, 25, 47, 0.05);
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.3s ease;
        display: flex;
        flex-direction: column;
    }
    .specialist-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 40px rgba(0, 133, 200, 0.14);
        border-color: var(--brand-blue-light);
    }
    .specialist-img-box {
        position: relative;
        width: 100%;
        aspect-ratio: 4/4;
        background: #e2e8f0;
        overflow: hidden;
    }
    .specialist-img-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: top center;
        transition: transform 0.5s ease;
    }
    .specialist-card:hover .specialist-img-box img {
        transform: scale(1.05);
    }
    .specialist-card-body {
        padding: 18px 16px;
        text-align: center;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .specialist-name {
        font-size: 17.5px;
        font-weight: 700;
        color: var(--heading-navy);
        margin-bottom: 4px;
        line-height: 1.3;
    }
    .specialist-designation {
        font-size: 13px;
        color: var(--brand-blue);
        font-weight: 600;
        margin-bottom: 14px;
        min-height: 20px;
    }
    .specialist-action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        width: 100%;
        padding: 9px 14px;
        background: var(--brand-blue-light);
        color: var(--brand-blue);
        border-radius: 8px;
        font-weight: 600;
        font-size: 13px;
        text-decoration: none;
        transition: background 0.2s, color 0.2s;
    }
    .specialist-card:hover .specialist-action-btn {
        background: var(--brand-blue);
        color: #ffffff;
    }

    /* ─── WHY CHOOSE US (SECTION B) ───────────────────────────────────── */
    .why-choose-grid {
        display: grid;
        grid-template-columns: 0.9fr 1.1fr;
        gap: 48px;
        align-items: center;
    }
    @media (max-width: 900px) {
        .why-choose-grid { grid-template-columns: 1fr; gap: 36px; }
    }
    .why-item-row {
        display: flex;
        gap: 20px;
        align-items: flex-start;
        padding: 20px 24px;
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid var(--border-color);
        box-shadow: 0 4px 16px rgba(0,0,0,0.03);
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }
    .why-item-row:hover {
        transform: translateX(6px);
        border-color: var(--brand-blue);
        box-shadow: 0 10px 30px rgba(0, 133, 200, 0.12);
    }
    .why-number-circle {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--brand-blue-light) 0%, #e0f2fe 100%);
        color: var(--brand-blue);
        font-size: 20px;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border: 1px solid rgba(0, 133, 200, 0.2);
    }
    .why-item-row:hover .why-number-circle {
        background: var(--brand-blue);
        color: #ffffff;
    }
    .why-content-title {
        font-size: 19px;
        font-weight: 700;
        color: var(--heading-navy);
        margin-bottom: 6px;
    }
    .why-content-desc {
        font-size: 14.5px;
        color: var(--body-text);
        line-height: 1.6;
        margin: 0;
    }

    /* ─── CLINIC OVERVIEW & TEAM COLLAGE (SECTION C) ─────────────────── */
    .clinic-overview-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 48px;
        align-items: center;
    }
    @media (max-width: 900px) {
        .clinic-overview-grid { grid-template-columns: 1fr; gap: 36px; }
    }
    .team-collage-box {
        background: linear-gradient(135deg, #f8fafc 0%, #edf2f7 100%);
        border-radius: 20px;
        padding: 28px 24px;
        border: 1px solid var(--border-color);
        box-shadow: 0 12px 36px rgba(7, 26, 46, 0.08);
        text-align: center;
    }
    .team-collage-title {
        font-size: 14px;
        font-weight: 800;
        color: var(--heading-navy);
        letter-spacing: 1.5px;
        text-transform: uppercase;
        margin-bottom: 24px;
        display: inline-block;
        padding: 6px 18px;
        background: rgba(0, 133, 200, 0.1);
        border-radius: 20px;
    }
    .team-collage-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }
    @media (max-width: 500px) {
        .team-collage-grid { grid-template-columns: repeat(2, 1fr); }
    }
    .team-collage-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        border: 1px solid var(--border-color);
        text-align: center;
        transition: transform 0.25s ease;
    }
    .team-collage-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0, 133, 200, 0.15);
    }
    .team-collage-img {
        width: 100%;
        aspect-ratio: 1/1;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 6px;
        background: #e2e8f0;
    }
    .team-collage-name {
        font-size: 12.5px;
        font-weight: 700;
        color: var(--heading-navy);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: block;
    }
    /* ─── HOME APPOINTMENT SECTION ─────────────────────────────────── */
    @media (max-width: 860px) {
        .home-appt-grid {
            grid-template-columns: 1fr !important;
            gap: 32px !important;
        }
    }
</style>
@endsection

@section('content')

    {{-- ===== 1. HERO SLIDER SECTION ===== --}}
    <section class="hero-slider-section" id="hero-slider" aria-label="Hero Slider">
        <div class="slider-track" id="slider-track">

            @if(isset($slides) && $slides->count() > 0)
                {{-- DB-managed slides --}}
                @foreach($slides as $i => $slide)
                    <div class="slide-item {{ $i === 0 ? 'active' : '' }}" data-index="{{ $i }}">
                        <picture class="slide-bg-pic">
                            @if(!empty($slide->mobile_image_path))
                                <source media="(max-width: 768px)" srcset="{{ site_image($slide->mobile_image_path) }}">
                            @endif
                            @if(!empty($slide->image_path))
                                <img class="slide-bg-img"
                                     src="{{ site_image($slide->image_path) }}"
                                     alt="{{ $slide->title ?? 'SumitZ Clinic' }}"
                                     {{ $i === 0 ? 'fetchpriority="high"' : 'loading="lazy"' }}
                                     decoding="async"
                                     width="1400" height="700">
                            @elseif(!empty($slide->mobile_image_path))
                                <img class="slide-bg-img"
                                     src="{{ site_image($slide->mobile_image_path) }}"
                                     alt="{{ $slide->title ?? 'SumitZ Clinic' }}"
                                     {{ $i === 0 ? 'fetchpriority="high"' : 'loading="lazy"' }}
                                     decoding="async"
                                     width="1400" height="700">
                            @else
                                <img class="slide-bg-img"
                                     src="https://images.unsplash.com/photo-1622253692010-333f2da6031d?auto=format&fit=crop&w=1400&q=80"
                                     alt="SumitZ Orthopaedic Clinic"
                                     {{ $i === 0 ? 'fetchpriority="high"' : 'loading="lazy"' }}
                                     decoding="async"
                                     width="1400" height="700">
                            @endif
                        </picture>
                        <div class="hero-bg-overlay"></div>
                        <div class="slide-content-wrap">
                            <div class="container">
                                <div class="hero-inner">
                                    <div>
                                        <div class="hero-eyebrow-badge">
                                            <i data-lucide="shield-check" style="width:14px;height:14px;"></i>
                                            {{ $pageContents['home']['hero']['eyebrow'] ?? 'Best Orthopedic Doctor in Baner, Balewadi & Pune' }}
                                        </div>
                                        <h1 class="hero-h1">
                                            {!! $slide->title ?? ($pageContents['home']['hero']['title'] ?? 'Pune\'s Most Trusted <span class="highlight-blue">Orthopedic</span> &amp; Physiotherapy Expert') !!}
                                        </h1>
                                        <p class="hero-desc">
                                            {{ $slide->subtitle ?? ($pageContents['home']['hero']['subtitle'] ?? 'Dr. Sumit offers world-class joint replacement, knee surgery, spine care, fracture treatment, and advanced physiotherapy at his clinics in Baner, Balewadi, and Hinjewadi, Pune.') }}
                                        </p>
                                        <div class="hero-btn-row">
                                            @if(!empty($slide->button_text) && !empty($slide->button_link))
                                                <a href="{{ $slide->button_link }}" class="hero-btn-primary">
                                                    <i data-lucide="calendar" style="width:18px;height:18px;"></i>
                                                    {{ $slide->button_text }}
                                                </a>
                                            @else
                                                <button onclick="openDialog('callback-dialog');" class="hero-btn-primary">
                                                    <i data-lucide="calendar" style="width:18px;height:18px;"></i>
                                                    Book Appointment
                                                </button>
                                            @endif
                                            <a href="tel:{{ $site['phone'] ?? '+919545559434' }}" class="hero-btn-secondary">
                                                <i data-lucide="phone" style="width:18px;height:18px;"></i>
                                                Call Clinic
                                            </a>
                                        </div>
                                        <div class="hero-trust-strip">
                                            <div class="hero-trust-item">
                                                <span class="hero-trust-num">{{ $pageContents['home']['hero']['trust_1_num'] ?? '20,000+' }}</span>
                                                <span class="hero-trust-lbl">{{ $pageContents['home']['hero']['trust_1_label'] ?? 'Patients Recovered' }}</span>
                                            </div>
                                            <div class="hero-trust-item">
                                                <span class="hero-trust-num">{{ $pageContents['home']['hero']['trust_2_num'] ?? '15+ Yrs' }}</span>
                                                <span class="hero-trust-lbl">{{ $pageContents['home']['hero']['trust_2_label'] ?? 'Clinical Experience' }}</span>
                                            </div>
                                            <div class="hero-trust-item">
                                                <span class="hero-trust-num">{{ $pageContents['home']['hero']['trust_3_num'] ?? '3 Locations' }}</span>
                                                <span class="hero-trust-lbl">{{ $pageContents['home']['hero']['trust_3_label'] ?? 'Baner · Balewadi · Hinjewadi' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="hero-visual-panel">
                                        {{-- Panel intentionally minimal when slides are used; bg image is full-bleed --}}
                                        <div style="text-align:right;">
                                            <div style="display:inline-block;background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.2);border-radius:16px;padding:24px;backdrop-filter:blur(8px);max-width:280px;">
                                                <div style="font-size:13px;color:rgba(255,255,255,0.7);margin-bottom:8px;font-weight:600;text-transform:uppercase;letter-spacing:1px;">Why Choose Us</div>
                                                @php $whyItems = [
                                                    ['icon'=>'check-circle-2','text'=>'Board-Certified Orthopaedic Surgeon'],
                                                    ['icon'=>'check-circle-2','text'=>'In-House Digital X-Ray & BMD'],
                                                    ['icon'=>'check-circle-2','text'=>'Robotic-Assisted Joint Replacement'],
                                                    ['icon'=>'check-circle-2','text'=>'Same-Day Appointment Available'],
                                                    ['icon'=>'check-circle-2','text'=>'Insurance & Cashless Facility'],
                                                ]; @endphp
                                                @foreach($whyItems as $wi)
                                                    <div style="display:flex;align-items:center;gap:10px;padding:8px 0;border-bottom:1px solid rgba(255,255,255,0.1);">
                                                        <i data-lucide="{{ $wi['icon'] }}" style="width:16px;height:16px;color:#38BDF8;flex-shrink:0;"></i>
                                                        <span style="font-size:13.5px;color:rgba(255,255,255,0.9);">{{ $wi['text'] }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            @else
                {{-- Fallback: no slides in DB yet — static hero --}}
                <div class="slide-item active">
                    <picture class="slide-bg-pic">
                        <img class="slide-bg-img"
                             src="https://images.unsplash.com/photo-1631815588090-d4bfec5b1ccb?auto=format&fit=crop&w=1400&q=80"
                             alt="Dr. Sumit — Best Orthopedic Doctor Baner Pune"
                             fetchpriority="high" decoding="async" width="1400" height="700">
                    </picture>
                    <div class="hero-bg-overlay"></div>
                    <div class="slide-content-wrap">
                        <div class="container">
                            <div class="hero-inner">
                                <div>
                                    <div class="hero-eyebrow-badge">
                                        <i data-lucide="shield-check" style="width:14px;height:14px;"></i>
                                        {{ $pageContents['home']['hero']['eyebrow'] ?? 'Best Orthopedic Doctor in Baner, Balewadi & Pune' }}
                                    </div>
                                    <h1 class="hero-h1">
                                        {!! $pageContents['home']['hero']['title'] ?? 'Pune\'s Most Trusted <span class="highlight-blue">Orthopedic</span> Surgeon &amp; Physiotherapy Expert' !!}
                                    </h1>
                                    <p class="hero-desc">
                                        {{ $pageContents['home']['hero']['subtitle'] ?? 'Dr. Sumit provides world-class joint replacement, knee surgery, spine care, fracture treatment, and advanced physiotherapy at his clinics in Baner, Balewadi, and Hinjewadi, Pune.' }}
                                    </p>
                                    <div class="hero-btn-row">
                                        <button onclick="openDialog('callback-dialog');" class="hero-btn-primary">
                                            <i data-lucide="calendar" style="width:18px;height:18px;"></i>
                                            Book Appointment
                                        </button>
                                        <a href="tel:{{ $site['phone'] ?? '+919545559434' }}" class="hero-btn-secondary">
                                            <i data-lucide="phone" style="width:18px;height:18px;"></i>
                                            Call Clinic Now
                                        </a>
                                    </div>
                                    <div class="hero-trust-strip">
                                        <div class="hero-trust-item">
                                            <span class="hero-trust-num">{{ $pageContents['home']['hero']['trust_1_num'] ?? '20,000+' }}</span>
                                            <span class="hero-trust-lbl">{{ $pageContents['home']['hero']['trust_1_label'] ?? 'Patients Recovered' }}</span>
                                        </div>
                                        <div class="hero-trust-item">
                                            <span class="hero-trust-num">{{ $pageContents['home']['hero']['trust_2_num'] ?? '15+ Yrs' }}</span>
                                            <span class="hero-trust-lbl">{{ $pageContents['home']['hero']['trust_2_label'] ?? 'Clinical Experience' }}</span>
                                        </div>
                                        <div class="hero-trust-item">
                                            <span class="hero-trust-num">{{ $pageContents['home']['hero']['trust_3_num'] ?? '3 Locations' }}</span>
                                            <span class="hero-trust-lbl">{{ $pageContents['home']['hero']['trust_3_label'] ?? 'Baner · Balewadi · Hinjewadi' }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="hero-visual-panel">
                                    <div class="hero-img-frame">
                                        <img src="https://images.unsplash.com/photo-1622253692010-333f2da6031d?auto=format&fit=crop&w=800&q=80"
                                             alt="Dr. Sumit — Orthopedic Surgeon Baner Pune"
                                             fetchpriority="high" decoding="async" width="800" height="1000">
                                    </div>
                                    <div class="hero-float-badge">
                                        <div class="hero-float-icon">
                                            <i data-lucide="heart-pulse" style="width:20px;height:20px;"></i>
                                        </div>
                                        <div>
                                            <strong style="font-size:14px;color:var(--heading-navy);display:block;">Multi-Specialty Care</strong>
                                            <span style="font-size:12px;color:var(--muted-text);">Orthopedics · Spine · Sports</span>
                                        </div>
                                    </div>
                                    <div class="hero-rating-badge">
                                        <div style="color:#FBBF24;font-size:14px;margin-bottom:2px;">★★★★★</div>
                                        <div style="font-size:11px;color:rgba(255,255,255,0.8);">500+ Google Reviews</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Slider navigation (only shown if multiple slides) --}}
        @if(isset($slides) && $slides->count() > 1)
            <button class="slider-nav-btn slider-prev" onclick="sliderPrev()" aria-label="Previous Slide">
                <i data-lucide="chevron-left" style="width:22px;height:22px;"></i>
            </button>
            <button class="slider-nav-btn slider-next" onclick="sliderNext()" aria-label="Next Slide">
                <i data-lucide="chevron-right" style="width:22px;height:22px;"></i>
            </button>
            <div class="slider-dots" id="slider-dots">
                @foreach($slides as $i => $slide)
                    <button class="slider-dot {{ $i === 0 ? 'active' : '' }}" onclick="goToSlide({{ $i }})" aria-label="Slide {{ $i+1 }}"></button>
                @endforeach
            </div>
        @endif
    </section>

    {{-- ===== 2. APPOINTMENT BOOKING CARD ===== --}}
    <section class="section-spacing" id="appointment-section" style="background-color: var(--bg-surface);">
        <div class="container">
        <div class="home-appt-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 48px; align-items: center;">
                <div>
                    <span class="badge badge-blue" style="margin-bottom: 12px;">Seamless Booking</span>
                    <h2 style="margin-bottom: 16px;">{{ $pageContents['home']['appointment']['heading'] ?? 'Book Your Priority Consultation with Dr. Sumit' }}</h2>
                    <p style="font-size: 16px; margin-bottom: 24px; color: var(--body-text);">
                        {{ $pageContents['home']['appointment']['subtitle'] ?? 'Our clinical coordinators will help schedule your visit at our Baner, Balewadi, or Hinjewadi clinic. Fill in your details for a prompt callback.' }}
                    </p>
                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 36px; height: 36px; border-radius: 50%; background-color: var(--brand-blue-light); color: var(--brand-blue); display: flex; align-items: center; justify-content: center; font-weight: 700;">1</div>
                            <span style="font-size: 15px; font-weight: 600; color: var(--heading-navy);">Submit your preferred service and contact details</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 36px; height: 36px; border-radius: 50%; background-color: var(--brand-blue-light); color: var(--brand-blue); display: flex; align-items: center; justify-content: center; font-weight: 700;">2</div>
                            <span style="font-size: 15px; font-weight: 600; color: var(--heading-navy);">Receive a confirmation call from our coordinator</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 36px; height: 36px; border-radius: 50%; background-color: var(--brand-blue-light); color: var(--brand-blue); display: flex; align-items: center; justify-content: center; font-weight: 700;">3</div>
                            <span style="font-size: 15px; font-weight: 600; color: var(--heading-navy);">Visit Baner, Balewadi, or Hinjewadi clinic at your scheduled time</span>
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
                            <label for="hp-service" class="form-label">Treatment / Service Needed</label>
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

    {{-- ===== 4. SERVICES GRID ===== --}}
    <section class="section-spacing" style="background-color: var(--brand-blue-light);">
        <div class="container">
            <div style="text-align: center; max-width: 680px; margin: 0 auto 48px;">
                <span class="badge badge-blue" style="margin-bottom: 12px;">Multidisciplinary Facilities</span>
                <h2>{{ $pageContents['home']['services']['title'] ?? 'Comprehensive Orthopedic & Physiotherapy Services' }}</h2>
                <p style="color: var(--body-text); font-size: 16.5px;">{{ $pageContents['home']['services']['subtitle'] ?? 'From specialist orthopaedic evaluations and robotic joint replacement to full-spectrum physiotherapy and diagnostic imaging in Baner, Balewadi & Hinjewadi.' }}</p>
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
    {{-- ===== CLINIC OVERVIEW & TEAM COLLAGE SECTION (Screenshot 3) ===== --}}
    <section class="section-spacing" style="background-color: var(--surface-white);">
        <div class="container">
            <div class="clinic-overview-grid">
                <div>
                    <span class="badge badge-blue" style="margin-bottom: 12px;">Established Excellence</span>
                    <h2 style="font-size: 34px; margin-bottom: 6px; color: var(--heading-navy); line-height: 1.2;">
                        Orthopedic &amp; Physiotherapy Clinic In Baner <span style="color: var(--brand-blue);">SUMITZ</span>
                    </h2>
                    <span style="font-size: 18px; font-weight: 700; color: var(--brand-blue); display: block; margin-bottom: 20px;">
                        Your Health is in Good Hands
                    </span>
                    <p style="font-size: 16px; line-height: 1.7; color: var(--body-text); margin-bottom: 16px;">
                        SumitZ is one of the leading Orthopaedic &amp; Physiotherapy clinics in Baner and has been serving patients since 2006.
                    </p>
                    <p style="font-size: 16px; line-height: 1.7; color: var(--body-text); margin-bottom: 16px;">
                        We offer proactive, quality healthcare in a safe and comfortable environment tailored to your specific musculoskeletal needs.
                    </p>
                    <p style="font-size: 16px; line-height: 1.7; color: var(--body-text); margin-bottom: 28px;">
                        Your health is our number one priority, and we make sure to meet all your needs in a timely manner.
                    </p>
                    <div>
                        <a href="{{ route('services') }}" class="btn btn-primary btn-lg" style="display: inline-flex; align-items: center; gap: 8px;">
                            Our Services <i data-lucide="arrow-right" style="width: 18px;"></i>
                        </a>
                    </div>
                </div>

                <div class="team-collage-box">
                    <div class="team-collage-title">SUMITZ ORTHOPAEDIC TEAM</div>
                    <div class="team-collage-grid">
                        @php
                            $collageSlugs = ['dr-randhir-patil', 'dr-varun-agrawal', 'dr-sumit-gupta', 'dr-sachin-khalane', 'dr-ashish-ranade', 'dr-aniruddha-deshmukh', 'dr-pradnya-jagtap'];
                            $collageDocs = $globalSpecialists->filter(function($d) use ($collageSlugs) {
                                return in_array($d->slug, $collageSlugs);
                            })->values();
                            if ($collageDocs->isEmpty()) {
                                $collageDocs = $globalSpecialists->take(7);
                            }
                        @endphp
                        @foreach($collageDocs as $doc)
                            <div class="team-collage-card">
                                @if(!empty($doc->image))
                                    <img src="{{ site_image($doc->image) }}" alt="{{ $doc->name }}" class="team-collage-img" loading="lazy" decoding="async">
                                @else
                                    <img src="https://images.unsplash.com/photo-1622253692010-333f2da6031d?auto=format&fit=crop&w=300&q=80" alt="{{ $doc->name }}" class="team-collage-img" loading="lazy" decoding="async">
                                @endif
                                <span class="team-collage-name">{{ $doc->name }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== WHY CHOOSE US SECTION (Screenshot 2) ===== --}}
    <section class="section-spacing" style="background-color: var(--brand-blue-light);">
        <div class="container">
            <div class="why-choose-grid">
                <div style="position: relative;">
                    <div style="border-radius: 20px; overflow: hidden; box-shadow: 0 20px 50px rgba(7, 26, 46, 0.15); aspect-ratio: 4/3;">
                        <img src="https://images.unsplash.com/photo-1622253692010-333f2da6031d?auto=format&fit=crop&w=800&q=80" alt="Why Choose SumitZ Orthopedic Specialists Baner" style="width: 100%; height: 100%; object-fit: cover;" loading="lazy" decoding="async">
                    </div>
                    <div style="position: absolute; bottom: -20px; right: -20px; background: #ffffff; border-radius: 16px; padding: 18px 24px; box-shadow: 0 12px 32px rgba(0,0,0,0.12); border: 1px solid var(--border-color); display: flex; align-items: center; gap: 14px;">
                        <div style="width: 48px; height: 48px; border-radius: 12px; background: var(--brand-blue-light); color: var(--brand-blue); display: flex; align-items: center; justify-content: center;">
                            <i data-lucide="award" style="width: 24px; height: 24px;"></i>
                        </div>
                        <div>
                            <strong style="font-size: 18px; color: var(--heading-navy); display: block;">15+ Yrs</strong>
                            <span style="font-size: 13px; color: var(--muted-text);">Clinical Expertise in Baner</span>
                        </div>
                    </div>
                </div>

                <div>
                    <span class="badge badge-blue" style="margin-bottom: 12px;">Patient Advantage</span>
                    <h2 style="font-size: 34px; margin-bottom: 24px; color: var(--heading-navy); line-height: 1.25;">
                        Why Choose Us As Orthopedic &amp; Physiotherapy Specialist In Baner?
                    </h2>

                    <div class="why-item-row">
                        <div class="why-number-circle">01.</div>
                        <div>
                            <h3 class="why-content-title">Patient-Centred</h3>
                            <p class="why-content-desc">
                                While our team brings important experience and knowledge, we know that each patient is the expert in their own life.
                            </p>
                        </div>
                    </div>

                    <div class="why-item-row">
                        <div class="why-number-circle">02.</div>
                        <div>
                            <h3 class="why-content-title">Comprehensive</h3>
                            <p class="why-content-desc">
                                We offer comprehensive health care focusing on the whole person, at all ages and stages of life.
                            </p>
                        </div>
                    </div>

                    <div class="why-item-row" style="margin-bottom: 0;">
                        <div class="why-number-circle">03.</div>
                        <div>
                            <h3 class="why-content-title">Flexible Working Hours</h3>
                            <p class="why-content-desc">
                                We have flexible hours and are open on certain evenings and during the weekend, to accommodate your schedule.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== MEET THE TEAM: SPECIALISTS GRID SECTION (Screenshot 1) ===== --}}
    @if($globalSpecialists->count() > 0)
        <section class="section-spacing" style="background-color: var(--surface-white);">
            <div class="container">
                <div style="text-align: center; max-width: 720px; margin: 0 auto 48px;">
                    <span class="badge badge-blue" style="margin-bottom: 12px;">Meet the Team</span>
                    <h2 style="font-size: 36px; margin-bottom: 12px; color: var(--heading-navy);">
                        Our Orthopedic &amp; Physiotherapy Specialists
                    </h2>
                    <p style="color: var(--body-text); font-size: 16.5px; line-height: 1.6;">
                        We offer highly specialised medical care, on one site, from some of the foremost national and international specialists in their fields of medicine.
                    </p>
                </div>

                <div class="specialist-card-grid">
                    @foreach($globalSpecialists->take(8) as $doc)
                        <div class="specialist-card">
                            <div class="specialist-img-box">
                                @if(!empty($doc->image))
                                    <img src="{{ site_image($doc->image) }}" alt="{{ $doc->name }}" loading="lazy" decoding="async">
                                @else
                                    <img src="https://images.unsplash.com/photo-1622253692010-333f2da6031d?auto=format&fit=crop&w=500&q=80" alt="{{ $doc->name }}" loading="lazy" decoding="async">
                                @endif
                            </div>
                            <div class="specialist-card-body">
                                <div>
                                    <h3 class="specialist-name">{{ $doc->name }}</h3>
                                    <div class="specialist-designation">{{ $doc->designation ?: 'Medical Specialist' }}</div>
                                </div>
                                <a href="{{ route('specialists.show', $doc->slug) }}" class="specialist-action-btn">
                                    View Doctor Profile <i data-lucide="arrow-right" style="width: 15px;"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($globalSpecialists->count() > 8)
                    <div style="text-align: center; margin-top: 40px;">
                        <a href="{{ route('specialists') }}" class="btn btn-secondary btn-lg">
                            View All Specialists ({{ $globalSpecialists->count() }})
                        </a>
                    </div>
                @endif
            </div>
        </section>
    @endif
    @if($globalSpecialists->count() > 0)
        @php $leadDoc = $globalSpecialists->first(); @endphp
        <section class="section-spacing" style="background-color: var(--surface-white);">
            <div class="container">
                <div style="display: grid; grid-template-columns: 0.9fr 1.1fr; gap: 48px; align-items: center;">
                    <div class="doctor-img-container" style="border-radius: var(--radius-xl); overflow: hidden; box-shadow: var(--shadow-lg);">
                        @if(!empty($leadDoc->image))
                            <img src="{{ site_image($leadDoc->image) }}" alt="{{ $leadDoc->name }} — Best Orthopedic Doctor Baner Pune" style="width: 100%; height: 100%; object-fit: cover;" loading="lazy" decoding="async">
                        @else
                            <img src="https://images.unsplash.com/photo-1622253692010-333f2da6031d?auto=format&fit=crop&w=800&q=80" alt="Dr. Sumit — Orthopedic Surgeon Baner Balewadi Pune" loading="lazy" decoding="async">
                        @endif
                    </div>
                    <div>
                        <span class="badge badge-blue" style="margin-bottom: 12px;">Lead Consultant</span>
                        <h2 style="font-size: 36px; margin-bottom: 8px;">{{ $leadDoc->name }}</h2>
                        <span style="font-size: 16px; font-weight: 700; color: var(--brand-blue); display: block; margin-bottom: 8px;">
                            {{ $leadDoc->designation }}
                        </span>
                        <span style="font-size: 14px; color: var(--muted-text); display: block; margin-bottom: 20px;">
                            Best Orthopedic Doctor in Baner, Balewadi &amp; Pune
                        </span>
                        <p style="font-size: 16.5px; line-height: 1.7; color: var(--body-text); margin-bottom: 24px;">
                            {{ Str::limit(strip_tags($leadDoc->description), 280) }}
                        </p>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 32px;">
                            @foreach([
                                'Joint Replacement Surgery', 'Robotic-Assisted Surgery',
                                'Spine Care & Treatment', 'Trauma & Fracture Management',
                                'Sports Injury Rehabilitation', 'Knee & Hip Arthroplasty'
                            ] as $skill)
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <i data-lucide="check-circle-2" style="color: var(--brand-blue); width: 20px; flex-shrink:0;"></i>
                                <span style="font-size: 14px; font-weight: 600; color: var(--heading-navy);">{{ $skill }}</span>
                            </div>
                            @endforeach
                        </div>
                        <div style="display: flex; gap: 16px; flex-wrap: wrap;">
                            <a href="{{ route('specialists.show', $leadDoc->slug) }}" class="btn btn-primary">
                                Meet {{ $leadDoc->name }}'s Profile
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

    {{-- ===== 6. 4-STEP RECOVERY PATH ===== --}}
    <section class="section-spacing" style="background-color: var(--bg-surface);">
        <div class="container">
            <div style="text-align: center; max-width: 640px; margin: 0 auto 48px;">
                <span class="badge badge-blue" style="margin-bottom: 12px;">Clear Patient Pathway</span>
                <h2>{{ $pageContents['home']['process']['heading'] ?? 'Your Journey to Pain-Free Living' }}</h2>
                <p style="color: var(--body-text); font-size: 16px;">{{ $pageContents['home']['process']['subtitle'] ?? 'We follow a systematic, compassionate four-step protocol for every patient at our Baner and Balewadi clinics.' }}</p>
            </div>

            <div class="process-grid">
                <div class="process-step">
                    <div class="step-number">01</div>
                    <h3 style="font-size: 19px; margin-bottom: 8px;">Detailed Consultation</h3>
                    <p style="font-size: 14.5px; color: var(--body-text);">Comprehensive physical examination and review of your pain history by Dr. Sumit.</p>
                </div>
                <div class="process-step">
                    <div class="step-number">02</div>
                    <h3 style="font-size: 19px; margin-bottom: 8px;">Precision Diagnosis</h3>
                    <p style="font-size: 14.5px; color: var(--body-text);">In-house Digital X-Ray, OPG, and BMD bone density testing for accurate insights.</p>
                </div>
                <div class="process-step">
                    <div class="step-number">03</div>
                    <h3 style="font-size: 19px; margin-bottom: 8px;">Targeted Treatment</h3>
                    <p style="font-size: 14.5px; color: var(--body-text);">Custom physiotherapy, non-invasive therapies, or precision surgical care tailored for you.</p>
                </div>
                <div class="process-step">
                    <div class="step-number">04</div>
                    <h3 style="font-size: 19px; margin-bottom: 8px;">Guided Rehabilitation</h3>
                    <p style="font-size: 14.5px; color: var(--body-text);">Progressive exercise tracking to ensure long-term mobility and strength recovery.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== 7. ADVANCED FACILITIES ===== --}}
    <section class="section-spacing" style="background-color: var(--mist-blue);">
        <div class="container">
            <div style="text-align: center; max-width: 640px; margin: 0 auto 48px;">
                <span class="badge badge-blue" style="margin-bottom: 12px;">In-House Diagnostics</span>
                <h2>{{ $pageContents['home']['facilities']['heading'] ?? 'State-of-the-Art Clinical Facilities in Baner' }}</h2>
                <p style="color: var(--body-text); font-size: 16px;">{{ $pageContents['home']['facilities']['subtitle'] ?? 'Modern diagnostic and therapeutic equipment ensuring accurate answers and faster recovery — all under one roof.' }}</p>
            </div>

            <div class="card-grid">
                <div class="medical-card">
                    <div class="card-img-wrapper">
                        <img src="https://images.unsplash.com/photo-1516549655169-df83a0774514?auto=format&fit=crop&w=600&q=80" alt="Digital X-Ray Unit Baner Clinic" loading="lazy" decoding="async">
                    </div>
                    <div class="card-body">
                        <h3 class="card-title">High-Frequency Digital X-Ray</h3>
                        <p class="card-text">Ultra-low radiation digital radiography providing instant high-resolution bone images for rapid orthopedic diagnosis.</p>
                    </div>
                </div>

                <div class="medical-card">
                    <div class="card-img-wrapper">
                        <img src="https://images.unsplash.com/photo-1579684385127-1ef15d508118?auto=format&fit=crop&w=600&q=80" alt="Physiotherapy Equipment Baner" loading="lazy" decoding="async">
                    </div>
                    <div class="card-body">
                        <h3 class="card-title">Advanced Physiotherapy Gym</h3>
                        <p class="card-text">Electrotherapy, IFT, ultrasound, and dedicated muscle rehabilitation equipment for comprehensive recovery programs.</p>
                    </div>
                </div>

                <div class="medical-card">
                    <div class="card-img-wrapper">
                        <img src="https://images.unsplash.com/photo-1581595220892-b0739db3ba8c?auto=format&fit=crop&w=600&q=80" alt="BMD Bone Density Scan" loading="lazy" decoding="async">
                    </div>
                    <div class="card-body">
                        <h3 class="card-title">BMD Bone Density Scan</h3>
                        <p class="card-text">Accurate osteoporosis screening and bone mineral density assessment for early prevention and treatment planning.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== 8. TREATMENTS GRID ===== --}}
    <section class="section-spacing" style="background-color: var(--surface-white);">
        <div class="container">
            <div style="text-align: center; max-width: 640px; margin: 0 auto 48px;">
                <span class="badge badge-blue" style="margin-bottom: 12px;">Conditions We Treat</span>
                <h2>{{ $pageContents['home']['treatments']['heading'] ?? 'Specialized Orthopedic Treatments in Pune' }}</h2>
                <p style="color: var(--body-text); font-size: 16px;">{{ $pageContents['home']['treatments']['subtitle'] ?? 'Targeted interventions for joint, bone, muscle, and ligament disorders by Pune\'s leading orthopedic specialist.' }}</p>
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

    {{-- ===== 9. TESTIMONIALS ===== --}}
    @if(isset($testimonials) && $testimonials->count() > 0)
        <section class="section-spacing" style="background-color: var(--bg-surface);">
            <div class="container">
                <div style="text-align: center; max-width: 640px; margin: 0 auto 48px;">
                    <span class="badge badge-blue" style="margin-bottom: 12px;">Verified Patient Feedback</span>
                    <h2>{{ $pageContents['home']['testimonials']['heading'] ?? 'Stories of Recovery & Renewed Hope' }}</h2>
                    <p style="color: var(--body-text); font-size: 16px;">{{ $pageContents['home']['testimonials']['subtitle'] ?? 'Read how Dr. Sumit\'s patient-first approach helped individuals in Baner, Balewadi & Pune regain comfortable daily living.' }}</p>
                </div>

                <div class="card-grid">
                    @foreach($testimonials->take(3) as $testimonial)
                        <div class="testimonial-card">
                            <div style="color: #F59E0B; margin-bottom: 16px; display: flex; gap: 4px;">
                                @for($s = 0; $s < 5; $s++)
                                    <i data-lucide="star" style="fill: currentColor; width: 18px;"></i>
                                @endfor
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

    {{-- ===== 10. CLINIC LOCATIONS ===== --}}
    <section class="section-spacing" style="background-color: var(--surface-white);">
        <div class="container">
            <div style="text-align: center; max-width: 640px; margin: 0 auto 48px;">
                <span class="badge badge-blue" style="margin-bottom: 12px;">Convenient Access</span>
                <h2>{{ $pageContents['home']['locations']['heading'] ?? 'Visit SumitZ Clinic — Baner, Balewadi & Hinjewadi' }}</h2>
                <p style="color: var(--body-text); font-size: 16px;">{{ $pageContents['home']['locations']['subtitle'] ?? 'Three conveniently located clinics serving patients across Pune with expert orthopedic care.' }}</p>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 28px;">
                <div class="location-card">
                    <h3 class="location-title">
                        <i data-lucide="map-pin" style="color: var(--brand-blue);"></i> Baner Clinic
                    </h3>
                    <p style="font-size: 15px; color: var(--body-text); margin-bottom: 20px; line-height: 1.6;">
                        {{ $site['address_baner'] ?? 'First Floor, Krishna Avenue, Above Dominos Pizza, Main Baner Road, Baner, Pune - 411045' }}
                    </p>
                    <div style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 24px; font-size: 14px;">
                        <div><strong>Timings:</strong> Mon – Sat: 9:00 AM – 9:00 PM</div>
                        <div><strong>Helpline:</strong> {{ $site['helpline_baner'] ?? '+91 95455 59434 / +91 88559 50433' }}</div>
                    </div>
                    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                        <a href="https://maps.google.com/?q=SumitZ+Clinic+Baner+Pune" target="_blank" class="btn btn-secondary btn-sm">
                            <i data-lucide="navigation"></i> Directions
                        </a>
                        <a href="tel:+919545559434" class="btn btn-primary btn-sm">
                            <i data-lucide="phone"></i> Call Now
                        </a>
                    </div>
                </div>

                <div class="location-card">
                    <h3 class="location-title">
                        <i data-lucide="map-pin" style="color: var(--brand-blue);"></i> Balewadi Clinic
                    </h3>
                    <p style="font-size: 15px; color: var(--body-text); margin-bottom: 20px; line-height: 1.6;">
                        {{ $site['address_balewadi'] ?? 'Balewadi, Pune, Maharashtra — Near Balewadi Stadium, Pune - 411045' }}
                    </p>
                    <div style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 24px; font-size: 14px;">
                        <div><strong>Timings:</strong> Mon – Sat: 9:00 AM – 8:00 PM</div>
                        <div><strong>Helpline:</strong> {{ $site['phone'] ?? '+91 95455 59434' }}</div>
                    </div>
                    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                        <a href="https://maps.google.com/?q=SumitZ+Clinic+Balewadi+Pune" target="_blank" class="btn btn-secondary btn-sm">
                            <i data-lucide="navigation"></i> Directions
                        </a>
                        <a href="tel:{{ $site['phone'] ?? '+919545559434' }}" class="btn btn-primary btn-sm">
                            <i data-lucide="phone"></i> Call Now
                        </a>
                    </div>
                </div>

                <div class="location-card">
                    <h3 class="location-title">
                        <i data-lucide="map-pin" style="color: var(--brand-blue);"></i> Hinjewadi Clinic
                    </h3>
                    <p style="font-size: 15px; color: var(--body-text); margin-bottom: 20px; line-height: 1.6;">
                        {{ $site['address_hinjewadi'] ?? 'Atlanta Society, Near Hinjewadi Phase 1 Circle, Hinjewadi, Pune - 411057' }}
                    </p>
                    <div style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 24px; font-size: 14px;">
                        <div><strong>Timings:</strong> Mon – Sat: 10:00 AM – 8:30 PM</div>
                        <div><strong>Helpline:</strong> {{ $site['helpline_hinjewadi'] ?? '+91 94049 63781 / +91 91581 43325' }}</div>
                    </div>
                    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                        <a href="https://maps.google.com/?q=SumitZ+Clinic+Hinjewadi+Pune" target="_blank" class="btn btn-secondary btn-sm">
                            <i data-lucide="navigation"></i> Directions
                        </a>
                        <a href="tel:+919404963781" class="btn btn-primary btn-sm">
                            <i data-lucide="phone"></i> Call Now
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== 11. FAQ ACCORDION ===== --}}
    <section class="section-spacing" style="background-color: var(--brand-blue-light);">
        <div class="container" style="max-width: 800px;">
            <div style="text-align: center; margin-bottom: 40px;">
                <span class="badge badge-blue" style="margin-bottom: 12px;">Frequently Asked Questions</span>
                <h2>{{ $pageContents['home']['faq']['heading'] ?? 'Common Questions About Orthopedic Treatment in Pune' }}</h2>
            </div>

            <div class="accordion">
                <div class="accordion-item active">
                    <button class="accordion-header" onclick="toggleAccordion(this)">
                        Who is the best orthopedic doctor in Baner, Pune?
                        <i data-lucide="chevron-down" style="width: 20px;"></i>
                    </button>
                    <div class="accordion-body">
                        Dr. Sumit at SumitZ Clinic in Baner is recognized as one of Pune's leading board-certified orthopaedic surgeons, with over 15 years of experience in joint replacement, knee surgery, spine care, and sports injury rehabilitation. The clinic also serves patients in Balewadi and Hinjewadi.
                    </div>
                </div>

                <div class="accordion-item">
                    <button class="accordion-header" onclick="toggleAccordion(this)">
                        Do I need a doctor's referral to book a physiotherapy session?
                        <i data-lucide="chevron-down" style="width: 20px;"></i>
                    </button>
                    <div class="accordion-body">
                        No. You can directly schedule a consultation with our experienced physiotherapists or orthopaedic specialists at our Baner, Balewadi, or Hinjewadi clinics without any prior referral.
                    </div>
                </div>

                <div class="accordion-item">
                    <button class="accordion-header" onclick="toggleAccordion(this)">
                        Are digital X-ray and diagnostic scans available at the clinic?
                        <i data-lucide="chevron-down" style="width: 20px;"></i>
                    </button>
                    <div class="accordion-body">
                        Yes! SumitZ Clinic features in-house high-frequency Digital X-Ray, Digital OPG, and BMD bone density scanning facilities for same-visit rapid diagnosis.
                    </div>
                </div>

                <div class="accordion-item">
                    <button class="accordion-header" onclick="toggleAccordion(this)">
                        What non-surgical options are available for knee and joint pain?
                        <i data-lucide="chevron-down" style="width: 20px;"></i>
                    </button>
                    <div class="accordion-body">
                        We offer customized conservative care including therapeutic electrotherapy, targeted joint exercises, posture correction, intra-articular injections, and lifestyle management — before considering surgical options. Many patients achieve excellent results without surgery.
                    </div>
                </div>

                <div class="accordion-item">
                    <button class="accordion-header" onclick="toggleAccordion(this)">
                        Does SumitZ Clinic offer cashless insurance and EMI options?
                        <i data-lucide="chevron-down" style="width: 20px;"></i>
                    </button>
                    <div class="accordion-body">
                        Yes. SumitZ Clinic is empanelled with most major health insurance providers and offers cashless treatment facilities. EMI options for surgical procedures can also be arranged. Please contact our Baner clinic coordinator for details.
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== 12. FINAL CTA ===== --}}
    <section class="section-spacing" style="background: linear-gradient(135deg, var(--primary-navy) 0%, #071A2E 100%); color: white; text-align: center;">
        <div class="container" style="max-width: 760px;">
            <span class="badge badge-blue" style="background: rgba(255,255,255,0.15); color: #fff; border: none; margin-bottom: 16px;">Take The Next Step</span>
            <h2 style="color: white; font-size: 38px; margin-bottom: 16px;">
                {{ $pageContents['home']['cta']['heading'] ?? 'Ready to Live Pain-Free? Consult Pune\'s Best Orthopedic Doctor Today' }}
            </h2>
            <p style="color: rgba(255,255,255,0.85); font-size: 18px; margin-bottom: 32px; line-height: 1.6;">
                {{ $pageContents['home']['cta']['subtitle'] ?? 'Book an appointment at SumitZ Clinic Baner, Balewadi, or Hinjewadi for expert orthopedic consultation and personalized recovery planning.' }}
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
    // ─── Accordion ───────────────────────────────────────────
    function toggleAccordion(button) {
        const item = button.parentElement;
        const isActive = item.classList.contains('active');
        document.querySelectorAll('.accordion-item').forEach(el => el.classList.remove('active'));
        if (!isActive) item.classList.add('active');
    }

    // ─── Hero Slider ─────────────────────────────────────────
    (function() {
        const track  = document.getElementById('slider-track');
        const slides = document.querySelectorAll('#slider-track .slide-item');
        const dots   = document.querySelectorAll('#slider-dots .slider-dot');
        if (!slides.length) return;

        // Single slide — make it fully visible, nothing else needed
        if (slides.length < 2) {
            slides[0].classList.add('active');
            // Ensure track has height based on slide content
            if (track) track.style.minHeight = slides[0].scrollHeight + 'px';
            return;
        }

        let current = 0;
        let timer   = null;

        function activate(n) {
            slides[current].classList.remove('active');
            if (dots[current]) dots[current].classList.remove('active');
            current = (n + slides.length) % slides.length;
            slides[current].classList.add('active');
            if (dots[current]) dots[current].classList.add('active');
        }

        function startAuto() {
            stopAuto();
            timer = setInterval(() => activate(current + 1), 5000);
        }
        function stopAuto() {
            if (timer) { clearInterval(timer); timer = null; }
        }

        window.sliderPrev = () => { stopAuto(); activate(current - 1); startAuto(); };
        window.sliderNext = () => { stopAuto(); activate(current + 1); startAuto(); };
        window.goToSlide  = (n) => { stopAuto(); activate(n); startAuto(); };

        startAuto();
    })();
    </script>

@endsection
