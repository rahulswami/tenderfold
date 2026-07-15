@extends('layouts.app')

@section('title', 'Patient Testimonials & Success Stories | ' . ($site['site_name'] ?? 'SumitZ Clinic'))
@section('meta_description', 'Read reviews from satisfied patients who recovered their mobility through our orthopaedic treatments and physiotherapy care at SumitZ Clinic Pune.')

@section('schema')
<script type="application/ld+json">
{
  "{{ '@' }}context": "https://schema.org",
  "{{ '@graph' }}": [
    {
      "{{ '@type' }}": "WebPage",
      "{{ '@id' }}": "{{ route('testimonials') }}/#webpage",
      "url": "{{ route('testimonials') }}",
      "name": "Patient Testimonials & Success Stories | {{ $site['site_name'] ?? 'SumitZ Clinic' }}",
      "description": "Read reviews from satisfied patients who recovered their mobility through our orthopaedic treatments and physiotherapy care at SumitZ Clinic Pune.",
      "isPartOf": {
        "{{ '@type' }}": "WebSite",
        "{{ '@id' }}": "{{ route('home') }}/#website"
      },
      "reviewedBy": {
        "{{ '@type' }}": "Organization",
        "{{ '@id' }}": "{{ route('home') }}/#organization"
      }
    }
    @if(isset($testimonials) && $testimonials->isNotEmpty())
    ,
    {
      "{{ '@type' }}": "ItemList",
      "{{ '@id' }}": "{{ route('testimonials') }}/#reviewlist",
      "name": "Patient Reviews",
      "itemListElement": [
        @foreach($testimonials as $index => $t)
        {
          "{{ '@type' }}": "ListItem",
          "position": {{ $index + 1 }},
          "item": {
            "{{ '@type' }}": "Review",
            "author": {
              "{{ '@type' }}": "Person",
              "name": "{{ $t->name }}"
            },
            "reviewBody": "{{ str_replace('"', '\"', $t->content) }}",
            "reviewRating": {
              "{{ '@type' }}": "Rating",
              "ratingValue": "{{ $t->rating }}",
              "bestRating": "5"
            },
            "itemReviewed": {
              "{{ '@type' }}": "Organization",
              "{{ '@id' }}": "{{ route('home') }}/#organization",
              "name": "{{ $site['site_name'] ?? 'SumitZ Clinic' }}"
            }
          }
        }{{ $index < count($testimonials) - 1 ? ',' : '' }}
        @endforeach
      ]
    }
    @endif
  ]
}
</script>
@endsection

@section('content')

    <!-- Details Hero -->
    <section class="details-hero">
        <div class="container text-center">
            <span class="color-mint font-semibold" style="text-transform: uppercase; letter-spacing: 1.5px; font-size: 0.9rem;">SUCCESS STORIES</span>
            <h1 style="margin-top: 10px;">What Our Patients Say</h1>
            <p style="color: var(--text-light-muted); max-width: 600px; margin: 0 auto;">Read real feedback from people who restored their mobility and active lifestyle at SumitZ Clinic.</p>
        </div>
    </section>

    <!-- Reviews Grid -->
    <section class="section">
        <div class="container">
            <div class="why-grid">
                @foreach($testimonials as $test)
                    <!-- Review Card -->
                    <div class="service-card animate-on-scroll">
                        <div class="testimonial-rating" style="color: #ffb100; margin-bottom: 1rem;">
                            @for($i = 0; $i < $test->rating; $i++)★@endfor
                        </div>
                        <p class="text-muted" style="font-style: italic; margin-bottom: 1.5rem;">"{{ $test->content }}"</p>
                        <div class="testimonial-author">
                            <div style="width:40px; height:40px; border-radius:50%; background-color:var(--accent-teal); display:flex; align-items:center; justify-content:center; color:white; font-weight:bold; overflow:hidden;">
                                @if($test->avatar_path)
                                    <img src="{{ site_image($test->avatar_path) }}" alt="{{ $test->name }}" style="width:100%; height:100%; object-fit:cover;">
                                @else
                                    {{ substr($test->name, 0, 2) }}
                                @endif
                            </div>
                            <div class="author-info">
                                <h4 style="font-size:0.9rem; color: var(--primary-teal-dark);">{{ $test->name }}</h4>
                                <p style="font-size:0.75rem;">{{ $test->role }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section class="final-cta-section" style="background: linear-gradient(135deg, #0d1e1f 0%, #163638 100%);">
        <div class="final-cta-wrapper animate-on-scroll">
            <h2>Start Your Own Recovery Journey</h2>
            <p>Speak to our specialists today to obtain prioritized slots and customized therapy guidelines.</p>
            <div class="final-cta-buttons">
                <button onclick="document.getElementById('callback-dialog').style.display='flex';" class="btn btn-white">Book Appointment</button>
                <a href="{{ route('contact') }}" class="btn btn-outline-white"><i data-lucide="phone"></i> Contact Clinic</a>
            </div>
        </div>
    </section>

@endsection
