@extends('layouts.app')

@section('title', ($faqPage->metatitle ?? ('Frequently Asked Questions | ' . ($site['site_name'] ?? 'SumitZ Clinic'))))
@section('meta_description', ($faqPage->metadescription ?? 'Everything you need to know about processing timelines, rates, documents, and qualifications.'))

@section('schema')
<script type="application/ld+json">
{
  "{{ '@' }}context": "https://schema.org",
  "{{ '@graph' }}": [
    {
      "{{ '@type' }}": "FAQPage",
      "{{ '@id' }}": "{{ route('faq') }}/#faq",
      "mainEntity": {!! json_encode(array_values(collect($services)->flatMap(function($s) {
          if (empty($s->faqs) || !is_array($s->faqs)) return [];
          return collect($s->faqs)->map(function($faq) {
              return [
                  "\x40type" => 'Question',
                  'name' => $faq['question'] ?? '',
                  'acceptedAnswer' => [
                      "\x40type" => 'Answer',
                      'text' => $faq['answer'] ?? ''
                  ]
              ];
          })->toArray();
      })->toArray())) !!}
    }
  ]
}
</script>
@endsection

@section('content')

    <!-- Details Hero -->
    <section class="details-hero">
        <div class="container text-center">
            <span class="color-mint font-semibold" style="text-transform: uppercase; letter-spacing: 1.5px; font-size: 0.9rem;">KNOWLEDGE HUB</span>
            <h1 style="margin-top: 10px;">Frequently Asked Questions</h1>
            <p style="color: var(--text-light-muted); max-width: 600px; margin: 0 auto;">Everything you need to know about processing timelines, rates, documents, and qualifications.</p>
        </div>
    </section>

    <!-- FAQs Section Accordions -->
    <section class="section">
        <div class="container">
            <!-- Tab filters -->
            <div style="display:flex; justify-content:center; gap:1rem; flex-wrap:wrap; margin-bottom:3rem;">
                @foreach($services as $service)
                    @if(!empty($service->faqs) && is_array($service->faqs) && count($service->faqs) > 0)
                        <a href="#{{ $service->slug }}-faq" class="btn btn-secondary btn-sm">{{ $service->service_name }} FAQ</a>
                    @endif
                @endforeach
            </div>

            @foreach($services as $service)
                @if(!empty($service->faqs) && is_array($service->faqs) && count($service->faqs) > 0)
                    <!-- {{ $service->service_name }} Accordion -->
                    <div id="{{ $service->slug }}-faq" style="scroll-margin-top: 120px; margin-bottom: 4rem;">
                        <h2 style="font-size: 1.6rem; color: var(--primary-teal); margin-bottom: 1.5rem; text-align: center; border-bottom: 2px solid var(--border-color); padding-bottom: 0.5rem;">{{ $service->service_name }} Questions</h2>
                        <div class="faq-accordion">
                            @foreach($service->faqs as $faq)
                                <div class="faq-item">
                                    <div class="faq-header">
                                        <span class="faq-title">{{ $faq['question'] ?? '' }}</span>
                                        <div class="faq-toggle"></div>
                                    </div>
                                    <div class="faq-body">
                                        <div class="faq-content">
                                            <p>{{ $faq['answer'] ?? '' }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </section>

@endsection
