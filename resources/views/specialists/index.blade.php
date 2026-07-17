@extends('layouts.app')

@section('title', ($specialistsPage->metatitle ?? 'Our Medical Specialists | SumitZ Orthopaedic Clinic'))
@section('meta_description', ($specialistsPage->metadescription ?? 'Meet our highly qualified team of orthopaedic surgeons, physiotherapists, and diagnostic specialists led by Dr. Sumit in Baner, Pune.'))
@section('meta_keywords', ($specialistsPage->metakeywords ?? 'orthopedic doctor baner, physiotherapist baner, orthopedic surgeons pune'))


@section('content')
<section class="details-hero">
    <div class="container text-center">
        <span class="color-mint font-semibold" style="text-transform: uppercase; letter-spacing: 1.5px; font-size: 0.9rem;">EXPERT MEDICAL TEAM</span>
        <h1 style="margin-top: 10px;">Our Orthopaedic & Physiotherapy Specialists</h1>
        <p style="color: var(--text-light-muted); max-width: 600px; margin: 0 auto;">Dedicated professionals providing world-class joint, spine, and muscle care under one roof.</p>
    </div>
</section>

<section class="section">
    <div class="container animate-on-scroll">
        <div class="blog-grid">
            @forelse($specialists as $spec)
                <div class="blog-card" style="display: flex; flex-direction: column; height: 100%;">
                    <div style="height: 300px; overflow: hidden; position: relative; background-color: var(--bg-light); display: flex; align-items: center; justify-content: center;">
                        @if($spec->image)
                            <img src="{{ site_image($spec->image) }}" alt="{{ $spec->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <i data-lucide="user" style="width: 80px; height: 80px; color: var(--accent-teal);"></i>
                        @endif
                        <span class="blog-category-badge" style="background-color: var(--primary-teal-dark);">{{ $spec->designation }}</span>
                    </div>
                    <div class="blog-body" style="flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <h3 style="margin-top: 0; color: var(--primary-teal-dark); font-size: 1.3rem;">{{ $spec->name }}</h3>
                            <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 1.5rem;">
                                {{ Str::limit(strip_tags($spec->description), 120) }}
                            </p>
                        </div>
                        <div style="display: flex; gap: 10px; align-items: center;">
                            <a href="{{ route('specialists.show', $spec->slug) }}" class="btn btn-secondary btn-sm" style="flex-grow: 1; text-align: center;">View Profile</a>
                            <button onclick="openDialog('callback-dialog');" class="btn btn-primary btn-sm" style="padding: 10px;"><i data-lucide="calendar" style="width: 16px; height: 16px;"></i></button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center" style="grid-column: 1 / -1; padding: 40px; color: var(--text-light-muted);">
                    <p>No specialists profiles are currently registered.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
