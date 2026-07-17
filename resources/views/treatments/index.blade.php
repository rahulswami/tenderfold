@extends('layouts.app')

@section('title', ($treatmentsPage->metatitle ?? 'Orthopaedic & Physiotherapy Treatments | SumitZ Clinic'))
@section('meta_description', ($treatmentsPage->metadescription ?? 'Explore our comprehensive list of clinical treatments including Knee Replacement, Hip Arthritis, Fractures, Sports Injuries & Frozen Shoulder Care.'))
@section('meta_keywords', ($treatmentsPage->metakeywords ?? 'orthopedic treatments pune, knee replacement baner, sports injury rehabilitation'))


@section('content')
<section class="details-hero">
    <div class="container text-center">
        <span class="color-mint font-semibold" style="text-transform: uppercase; letter-spacing: 1.5px; font-size: 0.9rem;">CLINICAL SPECIALITIES</span>
        <h1 style="margin-top: 10px;">Our Orthopaedic & Joint Treatments</h1>
        <p style="color: var(--text-light-muted); max-width: 600px; margin: 0 auto;">Expert treatments utilizing advanced diagnostic procedures and tailored rehabilitation programs.</p>
    </div>
</section>

<section class="section">
    <div class="container animate-on-scroll">
        <div class="blog-grid">
            @forelse($treatments as $treat)
                <div class="blog-card" style="display: flex; flex-direction: column; height: 100%;">
                    <div style="height: 220px; overflow: hidden; position: relative; background-color: var(--bg-light); display: flex; align-items: center; justify-content: center;">
                        @if($treat->image)
                            <img src="{{ site_image($treat->image) }}" alt="{{ $treat->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <i data-lucide="shield-alert" style="width: 60px; height: 60px; color: var(--accent-teal);"></i>
                        @endif
                    </div>
                    <div class="blog-body" style="flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <h3 style="margin-top: 0; color: var(--primary-teal-dark); font-size: 1.25rem;">{{ $treat->name }}</h3>
                            <p style="font-size: 0.88rem; color: var(--text-muted); margin-bottom: 1.5rem;">
                                {{ Str::limit(strip_tags($treat->description), 110) }}
                            </p>
                        </div>
                        <div style="display: flex; gap: 10px; align-items: center;">
                            <a href="{{ route('treatments.show', $treat->slug) }}" class="btn btn-secondary btn-sm" style="flex-grow: 1; text-align: center;">Learn More</a>
                            <button onclick="openDialog('callback-dialog');" class="btn btn-primary btn-sm" style="padding: 10px;"><i data-lucide="calendar" style="width: 16px; height: 16px;"></i></button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center" style="grid-column: 1 / -1; padding: 40px; color: var(--text-light-muted);">
                    <p>No treatments details are currently registered.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
