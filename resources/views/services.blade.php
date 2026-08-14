@extends('layouts.app')

@section('title', ($servicesPage->metatitle ?? 'Multidisciplinary Healthcare Services | SumitZ Clinic'))
@section('meta_description', ($servicesPage->metadescription ?? 'Explore clinical services offered by SumitZ Clinic including Physiotherapy, Digital X-Ray, OPG, BMD bone scans, pathology diagnostics, and Paediatric Orthopaedics in Baner & Hinjewadi.'))
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
      "name": "Clinical & Diagnostic Services | {{ $site['site_name'] ?? 'SumitZ Clinic' }}",
      "description": "Multidisciplinary services including Physiotherapy, Digital X-Ray, BMD, and Orthopaedic consultations."
    }
  ]
}
</script>
@endsection

@section('content')

    <!-- Hero Header -->
    <section class="section-spacing" style="background: linear-gradient(135deg, var(--light-mint) 0%, var(--mist-blue) 100%); padding: 64px 0 48px;">
        <div class="container text-center" style="max-width: 760px;">
            <span class="badge badge-teal" style="margin-bottom: 12px;">Complete Patient Care</span>
            <h1 style="font-size: clamp(32px, 4vw, 48px); margin-bottom: 16px;">Our Clinical Services & Diagnostic Facilities</h1>
            <p style="font-size: 17px; color: var(--body-text); line-height: 1.7;">
                We combine state-of-the-art diagnostic machinery with experienced specialist consultations to ensure a seamless recovery journey under one roof in Baner and Hinjewadi.
            </p>
        </div>
    </section>

    <!-- Services Cards Grid -->
    <section class="section-spacing" style="background-color: var(--warm-ivory);">
        <div class="container">
            <div class="card-grid">
                @foreach($services as $service)
                    <div class="medical-card">
                        <div class="card-img-wrapper">
                            @if(!empty($service->image_path))
                                <img src="{{ site_image($service->image_path) }}" alt="{{ $service->service_name }}">
                            @else
                                <img src="https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?auto=format&fit=crop&w=600&q=80" alt="{{ $service->service_name }}">
                            @endif
                        </div>
                        <div class="card-body">
                            <span class="card-category">{{ $service->hero_category ?? 'Clinical Facility' }}</span>
                            <h3 class="card-title">{{ $service->service_name }}</h3>
                            <p class="card-text">{{ Str::limit(strip_tags($service->summary ?? $service->intro_content), 120) }}</p>
                            <div class="card-footer">
                                <a href="{{ route('services.show', $service->slug) }}" class="btn btn-secondary btn-sm">
                                    Learn More
                                </a>
                                <button onclick="openDialog('callback-dialog');" class="btn btn-primary btn-sm">
                                    Enquire Now
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

@endsection
