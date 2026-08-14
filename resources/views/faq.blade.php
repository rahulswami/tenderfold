@extends('layouts.app')

@section('title', ($faqPage->metatitle ?? ('Frequently Asked Questions | ' . ($site['site_name'] ?? 'SumitZ Clinic'))))
@section('meta_description', ($faqPage->metadescription ?? 'Find answers to frequently asked questions about orthopaedic treatments, physiotherapy sessions, digital X-Ray availability, and appointment slot booking at SumitZ Clinic.'))
@section('meta_keywords', ($faqPage->metakeywords ?? 'orthopedic faq, joint care queries, physiotherapy faq pune'))

@section('content')

    <!-- Hero Header -->
    <section class="section-spacing" style="background: linear-gradient(135deg, var(--light-mint) 0%, var(--mist-blue) 100%); padding: 64px 0 48px;">
        <div class="container text-center" style="max-width: 760px;">
            <span class="badge badge-teal" style="margin-bottom: 12px;">Knowledge Base</span>
            <h1 style="font-size: clamp(32px, 4vw, 48px); margin-bottom: 16px;">Frequently Asked Questions</h1>
            <p style="font-size: 17px; color: var(--body-text); line-height: 1.7;">
                Common queries regarding treatment options, diagnostic preparation, clinic timings, and appointment scheduling.
            </p>
        </div>
    </section>

    <!-- FAQ Accordions -->
    <section class="section-spacing" style="background-color: var(--warm-ivory);">
        <div class="container" style="max-width: 840px;">
            @foreach($services as $service)
                @if(!empty($service->faqs) && is_array($service->faqs) && count($service->faqs) > 0)
                    <div style="margin-bottom: 48px;">
                        <h2 style="font-size: 22px; color: var(--heading-navy); margin-bottom: 20px; border-bottom: 2px solid var(--border-color); padding-bottom: 10px;">
                            {{ $service->service_name }} FAQs
                        </h2>
                        <div class="accordion">
                            @foreach($service->faqs as $faq)
                                <div class="accordion-item">
                                    <button class="accordion-header" onclick="toggleAccordion(this)">
                                        {{ $faq['question'] ?? '' }}
                                        <i data-lucide="chevron-down" style="width: 20px;"></i>
                                    </button>
                                    <div class="accordion-body">
                                        {{ $faq['answer'] ?? '' }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </section>

    <script>
        function toggleAccordion(button) {
            const item = button.parentElement;
            const isActive = item.classList.contains('active');
            document.querySelectorAll('.accordion-item').forEach(el => el.classList.remove('active'));
            if (!isActive) {
                item.classList.add('active');
            }
        }
    </script>

@endsection
