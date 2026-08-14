@extends('layouts.app')

@section('title', ($aboutPage->metatitle ?? 'About SumitZ Clinic | Orthopaedic & Physiotherapy Center Baner'))
@section('meta_description', ($aboutPage->metadescription ?? 'SumitZ Clinic is Pune\'s premier joint and spinal recovery center led by Dr. Sumit in Baner & Hinjewadi.'))
@section('meta_keywords', ($aboutPage->metakeywords ?? 'about dr sumit, orthopedic surgeon baner, best physiotherapist baner'))

@section('content')

    <!-- Hero Header -->
    <section class="section-spacing" style="background: linear-gradient(135deg, var(--light-mint) 0%, var(--mist-blue) 100%); padding: 64px 0 48px;">
        <div class="container text-center" style="max-width: 760px;">
            <span class="badge badge-teal" style="margin-bottom: 12px;">Who We Are</span>
            <h1 style="font-size: clamp(32px, 4vw, 48px); margin-bottom: 16px;">About SumitZ Clinic</h1>
            <p style="font-size: 17px; color: var(--body-text); line-height: 1.7;">
                Pune's premier joint care, diagnostic facility, and advanced physiotherapy recovery center located in Baner and Hinjewadi.
            </p>
        </div>
    </section>

    <!-- Clinic Story Section -->
    <section class="section-spacing" style="background-color: var(--warm-ivory);">
        <div class="container">
            <div style="display: grid; grid-template-columns: 1.1fr 0.9fr; gap: 48px; align-items: center;">
                <div style="font-size: 16.5px; line-height: 1.8; color: var(--body-text);">
                    <span class="badge badge-teal" style="margin-bottom: 12px;">Patient-First Philosophy</span>
                    <h2 style="font-size: 32px; margin-bottom: 16px;">Restoring Pain-Free Living Through Non-Surgical Care</h2>
                    <p style="margin-bottom: 16px;">
                        SumitZ Clinic was founded with a clear vision: to provide comprehensive orthopaedic evaluation, diagnostics, and guided physical therapy under one roof. We focus on non-invasive recovery pathways wherever medically possible.
                    </p>
                    <p style="margin-bottom: 16px;">
                        Over 15+ years of clinical practice in Pune, we have helped over 20,000 patients recover from knee osteoarthritis, back and disc stiffness, sports ligament injuries, and post-fracture rehabilitation.
                    </p>
                </div>

                <div class="location-card" style="padding: 36px; background-color: var(--surface-white);">
                    <div style="display: flex; flex-direction: column; gap: 24px;">
                        <div style="display: flex; gap: 16px; align-items: flex-start;">
                            <div style="width: 44px; height: 44px; border-radius: 50%; background-color: var(--light-mint); color: var(--primary-teal); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i data-lucide="eye" style="width: 24px;"></i>
                            </div>
                            <div>
                                <h3 style="font-size: 18px; margin-bottom: 4px;">Our Vision</h3>
                                <p style="font-size: 14.5px; color: var(--body-text); margin: 0;">To be Pune's most trusted orthopaedic recovery hub, helping individuals live active, painless lives.</p>
                            </div>
                        </div>

                        <div style="display: flex; gap: 16px; align-items: flex-start;">
                            <div style="width: 44px; height: 44px; border-radius: 50%; background-color: var(--light-mint); color: var(--primary-teal); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i data-lucide="target" style="width: 24px;"></i>
                            </div>
                            <div>
                                <h3 style="font-size: 18px; margin-bottom: 4px;">Our Mission</h3>
                                <p style="font-size: 14.5px; color: var(--body-text); margin: 0;">Delivering precise diagnostic imaging, root-cause treatment, and personalized exercise protocols.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Medical Specialists Showcase -->
    <section class="section-spacing" style="background-color: var(--light-mint);">
        <div class="container">
            <div style="text-align: center; max-width: 640px; margin: 0 auto 48px;">
                <span class="badge badge-teal" style="margin-bottom: 12px;">Expert Medical Team</span>
                <h2>Meet Our Clinical Specialists</h2>
                <p style="color: var(--body-text); font-size: 16px;">Experienced doctors and physiotherapists managing your recovery.</p>
            </div>

            <div class="card-grid" style="grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));">
                @foreach($globalSpecialists as $gspec)
                    <div class="doctor-card">
                        <div class="doctor-img-container">
                            @if(!empty($gspec->image))
                                <img src="{{ site_image($gspec->image) }}" alt="{{ $gspec->name }}">
                            @else
                                <img src="https://images.unsplash.com/photo-1622253692010-333f2da6031d?auto=format&fit=crop&w=600&q=80" alt="{{ $gspec->name }}">
                            @endif
                        </div>
                        <div class="doctor-card-body">
                            <h3 class="doctor-name">{{ $gspec->name }}</h3>
                            <span class="doctor-designation">{{ $gspec->designation }}</span>
                            <a href="{{ route('specialists.show', $gspec->slug) }}" class="btn btn-secondary btn-sm btn-full" style="margin-top: 12px;">
                                View Specialist Profile
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

@endsection
