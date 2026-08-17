@extends('layouts.app')

@section('title', ($treatmentsPage->metatitle ?? 'Orthopaedic & Joint Treatments | SumitZ Clinic'))
@section('meta_description', ($treatmentsPage->metadescription ?? 'Explore our clinical treatments including Knee Replacement, Joint Pain Care, Spine Surgery, Fracture Treatment & Sports Injury Rehabilitation in Baner & Hinjewadi.'))
@section('meta_keywords', ($treatmentsPage->metakeywords ?? 'orthopedic treatments pune, knee replacement baner, sports injury rehabilitation'))

@section('content')

    <!-- Hero Header -->
    <section class="section-spacing" style="background: linear-gradient(135deg, var(--light-mint) 0%, var(--mist-blue) 100%); padding: 64px 0 48px;">
        <div class="container text-center" style="max-width: 760px;">
            <span class="badge badge-teal" style="margin-bottom: 12px;">Specialized Orthopaedics</span>
            <h1 style="font-size: clamp(32px, 4vw, 48px); margin-bottom: 16px;">Our Orthopaedic & Joint Treatments</h1>
            <p style="font-size: 17px; color: var(--body-text); line-height: 1.7;">
                Precision diagnostic evaluation and customized rehabilitation protocols designed to resolve joint, spine, bone, and sports injury conditions.
            </p>
        </div>
    </section>

    <!-- Treatments Listing Grid -->
    <section class="section-spacing" style="background-color: var(--warm-ivory);">
        <div class="container">
            <div class="card-grid">
                @forelse($treatments as $treat)
                    <div class="medical-card">
                        <div class="card-img-wrapper">
                            @if(!empty($treat->image))
                                <img src="{{ site_image($treat->image) }}" alt="{{ $treat->name }}" loading="lazy" decoding="async">
                            @else
                                <img src="https://images.unsplash.com/photo-1584515979956-d9f6e5d09982?auto=format&fit=crop&w=600&q=80" alt="{{ $treat->name }}" loading="lazy" decoding="async">
                            @endif
                        </div>
                        <div class="card-body">
                            <span class="card-category">Clinical Speciality</span>
                            <h3 class="card-title">{{ $treat->name }}</h3>
                            <p class="card-text">{{ Str::limit(strip_tags($treat->description), 110) }}</p>
                            <div class="card-footer">
                                <a href="{{ route('treatments.show', $treat->slug) }}" class="btn btn-secondary btn-sm">
                                    Read Guide
                                </a>
                                <button onclick="openDialog('callback-dialog');" class="btn btn-primary btn-sm">
                                    <i data-lucide="calendar" style="width: 15px;"></i> Book
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; color: var(--muted-text);">
                        <p>No treatment records found.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

@endsection
