@extends('layouts.app')

@section('title', ($specialistsPage->metatitle ?? 'Our Medical Specialists | SumitZ Clinic'))
@section('meta_description', ($specialistsPage->metadescription ?? 'Meet our team of orthopaedic surgeons, physiotherapists, and diagnostic specialists led by Dr. Sumit in Baner & Hinjewadi, Pune.'))
@section('meta_keywords', ($specialistsPage->metakeywords ?? 'orthopedic doctor baner, physiotherapist baner, orthopedic surgeons pune'))

@section('content')

    <!-- Hero Header -->
    <section class="section-spacing" style="background: linear-gradient(135deg, var(--light-mint) 0%, var(--mist-blue) 100%); padding: 64px 0 48px;">
        <div class="container text-center" style="max-width: 760px;">
            <span class="badge badge-teal" style="margin-bottom: 12px;">Expert Healthcare Team</span>
            <h1 style="font-size: clamp(32px, 4vw, 48px); margin-bottom: 16px;">Our Orthopaedic & Physiotherapy Specialists</h1>
            <p style="font-size: 17px; color: var(--body-text); line-height: 1.7;">
                Dedicated medical practitioners delivering compassionate, patient-first orthopaedic consultations and guided physical rehabilitation.
            </p>
        </div>
    </section>

    <!-- Specialists Listing Grid -->
    <section class="section-spacing" style="background-color: var(--warm-ivory);">
        <div class="container">
            <div class="card-grid" style="grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));">
                @forelse($specialists as $spec)
                    <div class="doctor-card">
                        <div class="doctor-img-container">
                            @if(!empty($spec->image))
                                <img src="{{ site_image($spec->image) }}" alt="{{ $spec->name }}">
                            @else
                                <img src="https://images.unsplash.com/photo-1622253692010-333f2da6031d?auto=format&fit=crop&w=600&q=80" alt="{{ $spec->name }}">
                            @endif
                        </div>
                        <div class="doctor-card-body">
                            <h3 class="doctor-name">{{ $spec->name }}</h3>
                            <span class="doctor-designation">{{ $spec->designation }}</span>
                            <p style="font-size: 14.5px; color: var(--body-text); margin-bottom: 20px; line-height: 1.6;">
                                {{ Str::limit(strip_tags($spec->description), 110) }}
                            </p>
                            <div style="display: flex; gap: 10px;">
                                <a href="{{ route('specialists.show', $spec->slug) }}" class="btn btn-secondary btn-sm" style="flex-grow: 1;">
                                    View Profile
                                </a>
                                <button onclick="openDialog('callback-dialog');" class="btn btn-primary btn-sm">
                                    <i data-lucide="calendar" style="width: 15px;"></i> Book
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; color: var(--muted-text);">
                        <p>No specialist profiles currently found.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

@endsection
