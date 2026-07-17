@extends('layouts.app')

@section('title', ($servicesPage->metatitle ?? 'Our Clinical & Diagnostic Services | SumitZ Clinic'))
@section('meta_description', ($servicesPage->metadescription ?? 'Explore the wide range of services offered by SumitZ Clinic including Physiotherapy, Digital OPG, pathology diagnostics, Pediatric Orthopaedics, and X-Ray.'))
@section('meta_keywords', ($servicesPage->metakeywords ?? 'physiotherapy pune, digital opg baner, pathology lab baner, pediatric orthopedics pune'))


@section('schema')
<script type="application/ld+json">
{
  "{{ '@' }}context": "https://schema.org",
  "{{ '@graph' }}": [
    {
      "{{ '@type' }}": "WebPage",
      "{{ '@id' }}": "{{ route('services') }}/#webpage",
      "url": "{{ route('services') }}",
      "name": "Our Clinical & Diagnostic Services | {{ $site['site_name'] ?? 'SumitZ Clinic' }}",
      "description": "Explore the wide range of services offered by SumitZ Clinic including Physiotherapy, Digital OPG, pathology diagnostics, Pediatric Orthopaedics, and X-Ray.",
      "isPartOf": {
        "{{ '@type' }}": "WebSite",
        "{{ '@id' }}": "{{ route('home') }}/#website"
      },
      "about": [
        @foreach($globalServices as $index => $gs)
        {
          "{{ '@type' }}": "Service",
          "name": "{{ $gs->service_name }}",
          "url": "{{ route('services.show', $gs->slug) }}",
          "provider": {
            "{{ '@type' }}": "Organization",
            "{{ '@id' }}": "{{ route('home') }}/#organization"
          }
        }{{ $index < count($globalServices) - 1 ? ',' : '' }}
        @endforeach
      ]
    }
  ]
}
</script>
@endsection

@section('content')

    <!-- Details Hero -->
    <section class="details-hero">
        <div class="container text-center">
            <span class="color-mint font-semibold" style="text-transform: uppercase; letter-spacing: 1.5px; font-size: 0.9rem;">CLINICAL FACILITIES</span>
            <h1>Our Clinical Services & Diagnostics</h1>
            <p style="color: var(--text-light-muted); max-width: 600px; margin: 0 auto;">We combine state-of-the-art diagnostic machinery with expert clinical consultation to ensure a holistic recovery journey.</p>
        </div>
    </section>

    <!-- Services Cards grid with details -->
    <section class="section">
        <div class="container">
            <div class="solutions-grid animate-on-scroll">
                @foreach($services as $service)
                    <div class="solution-premium-card" style="margin-bottom: 2rem;">
                        <div class="sol-header">
                            <div class="sol-title-wrap">
                                <h3>{{ $service->service_name }}</h3>
                                <p>{{ $service->hero_category }}</p>
                            </div>
                            @if($service->badge)
                                <span class="sol-badge" style="background-color: var(--primary-teal-dark); color: #fff;">{{ $service->badge }}</span>
                            @endif
                        </div>
                        <div class="sol-body">
                            <p class="text-muted" style="margin-bottom:1.5rem;">{{ $service->summary }}</p>
                            <div class="sol-features-title">Highlights & Value</div>
                            <ul class="sol-features-list">
                                @if($service->rate_value)
                                    <li class="sol-feature-item"><i data-lucide="check"></i> {{ $service->rate_value }}</li>
                                @endif
                                @if($service->max_loan)
                                    <li class="sol-feature-item"><i data-lucide="check"></i> {{ $service->max_loan }}</li>
                                @endif
                                @if($service->tenure)
                                    <li class="sol-feature-item"><i data-lucide="check"></i> {{ $service->tenure }}</li>
                                @endif
                            </ul>
                            @if($service->eligibility_criteria)
                            <div class="sol-eligibility-box">
                                <p>Availability: <strong>{{ \Illuminate\Support\Str::limit($service->eligibility_criteria, 80) }}</strong></p>
                            </div>
                            @endif
                        </div>
                        <div class="sol-footer">
                            <a href="{{ route('services.show', $service->slug) }}" class="btn btn-secondary">Learn More</a>
                            <button onclick="openDialog('callback-dialog');" class="btn btn-primary">Enquire Now</button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

@endsection
