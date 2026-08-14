@extends('layouts.app')

@section('title', ($testimonialPage->metatitle ?? ('Patient Testimonials & Success Stories | ' . ($site['site_name'] ?? 'SumitZ Clinic'))))
@section('meta_description', ($testimonialPage->metadescription ?? 'Read reviews from satisfied patients who recovered their mobility through our orthopaedic treatments and physiotherapy care at SumitZ Clinic Pune.'))
@section('meta_keywords', ($testimonialPage->metakeywords ?? 'patient reviews sumitz, clinic testimonials pune, dr sumit feedback'))

@section('content')

    <!-- Hero Header -->
    <section class="section-spacing" style="background: linear-gradient(135deg, var(--light-mint) 0%, var(--mist-blue) 100%); padding: 64px 0 48px;">
        <div class="container text-center" style="max-width: 760px;">
            <span class="badge badge-teal" style="margin-bottom: 12px;">Verified Patient Feedback</span>
            <h1 style="font-size: clamp(32px, 4vw, 48px); margin-bottom: 16px;">What Our Patients Say About SumitZ</h1>
            <p style="font-size: 17px; color: var(--body-text); line-height: 1.7;">
                Real recovery stories from individuals who restored active living through our orthopaedic and physiotherapy care.
            </p>
        </div>
    </section>

    <!-- Reviews Grid -->
    <section class="section-spacing" style="background-color: var(--warm-ivory);">
        <div class="container">
            <div class="card-grid">
                @foreach($testimonials as $test)
                    <div class="testimonial-card">
                        <div style="color: #F59E0B; margin-bottom: 16px; display: flex; gap: 4px;">
                            @for($i = 0; $i < ($test->rating ?? 5); $i++)
                                <i data-lucide="star" style="fill: currentColor; width: 18px;"></i>
                            @endfor
                        </div>
                        <p class="testimonial-quote">
                            "{{ trim(strip_tags($test->content ?? $test->quote ?? '')) }}"
                        </p>
                        <div class="testimonial-author">
                            <div class="author-avatar">
                                @if(!empty($test->avatar_path))
                                    <img src="{{ site_image($test->avatar_path) }}" alt="{{ $test->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    {{ substr($test->name ?? 'P', 0, 1) }}
                                @endif
                            </div>
                            <div class="author-info">
                                <h4>{{ $test->name }}</h4>
                                <span>{{ $test->role ?? 'Orthopaedic Patient' }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

@endsection
