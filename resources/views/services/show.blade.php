@extends('layouts.app')

@section('title', ($service->metatitle ?? ($service->service_name . ' | ' . ($site['site_name'] ?? 'SumitZ Clinic'))))
@section('meta_description', ($service->metadescription ?? $service->summary))
@section('meta_keywords', ($service->metakeywords ?? ''))

@section('schema')
    @if(!empty($service->schema_markup))
        {!! $service->schema_markup !!}
    @else
        <script type="application/ld+json">
        {
          "{{ '@' }}context": "https://schema.org",
          "{{ '@graph' }}": [
            {
              "{{ '@type' }}": "MedicalProcedure",
              "name": "{{ $service->service_name }}",
              "description": "{{ $service->summary }}"
            }
          ]
        }
        </script>
    @endif
@endsection

@section('content')

    <!-- Hero Header -->
    <section class="section-spacing" style="background: linear-gradient(135deg, var(--light-mint) 0%, var(--mist-blue) 100%); padding: 56px 0 40px;">
        <div class="container" style="max-width: 1000px;">
            <div style="font-size: 14px; color: var(--muted-text); margin-bottom: 12px; display: flex; gap: 8px; align-items: center;">
                <a href="{{ route('home') }}">Home</a> /
                <a href="{{ route('services') }}">Services</a> /
                <span style="color: var(--primary-teal); font-weight: 600;">{{ $service->service_name }}</span>
            </div>
            <span class="badge badge-teal" style="margin-bottom: 12px;">{{ $service->hero_category ?? 'Clinical Facility' }}</span>
            <h1 style="font-size: clamp(32px, 4vw, 48px); margin-bottom: 16px;">{{ $service->hero_title ?? $service->service_name }}</h1>
            <p style="font-size: 17.5px; color: var(--body-text); line-height: 1.7; max-width: 760px;">
                {{ $service->hero_subtitle ?? $service->summary }}
            </p>
        </div>
    </section>

    <!-- Main Content & Sidebar Layout -->
    <section class="section-spacing" style="background-color: var(--warm-ivory);">
        <div class="container" style="display: grid; grid-template-columns: 1fr 340px; gap: 48px; align-items: start;">
            <div>
                <!-- Featured Image -->
                <div style="border-radius: var(--radius-xl); overflow: hidden; margin-bottom: 36px; box-shadow: var(--shadow-md); aspect-ratio: 16/9; background-color: var(--mist-blue);">
                    @if(!empty($service->image_path))
                        <img src="{{ site_image($service->image_path) }}" alt="{{ $service->service_name }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <img src="https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?auto=format&fit=crop&w=1000&q=80" alt="{{ $service->service_name }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @endif
                </div>

                <div style="font-size: 16.5px; line-height: 1.8; color: var(--body-text);">
                    <h2 style="font-size: 26px; margin-bottom: 16px;">{{ $service->intro_title ?? 'Overview & Facilities' }}</h2>
                    {!! $service->intro_content !!}

                    @if($service->eligibility_criteria)
                        <div style="background-color: var(--surface-white); border-radius: var(--radius-lg); padding: 28px; border: 1px solid var(--border-color); margin: 32px 0;">
                            <h3 style="font-size: 20px; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                                <i data-lucide="check-circle" style="color: var(--primary-teal);"></i> Who Should Consider This Service
                            </h3>
                            <ul style="list-style: none; padding: 0; display: flex; flex-direction: column; gap: 10px;">
                                @foreach(explode("\n", str_replace("\r", "", $service->eligibility_criteria)) as $item)
                                    @if(trim($item))
                                        <li style="display: flex; align-items: flex-start; gap: 8px;">
                                            <i data-lucide="check" style="color: var(--primary-teal); width: 18px; margin-top: 3px;"></i>
                                            <span>{{ trim($item) }}</span>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if($service->tips_title)
                        <h2 style="font-size: 24px; margin: 36px 0 16px;">{{ $service->tips_title }}</h2>
                        {!! $service->tips_content !!}
                    @endif

                    @php
                        $serviceFaqs = parse_faqs($service->faqs, $service->service_name);
                    @endphp
                    @if(!empty($serviceFaqs))
                        <div style="margin-top: 40px; padding-top: 32px; border-top: 1px solid var(--border-color);">
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
                                <span class="badge badge-teal" style="font-size: 12px;">Patient Queries</span>
                                <h2 style="font-size: 24px; margin: 0;">Frequently Asked Questions</h2>
                            </div>
                            <div class="accordion">
                                @foreach($serviceFaqs as $index => $faq)
                                    <div class="accordion-item {{ $index === 0 ? 'active' : '' }}" style="background: var(--surface-white); border: 1px solid var(--border-color); border-radius: var(--radius-lg); margin-bottom: 12px; overflow: hidden;">
                                        <button class="accordion-header" onclick="toggleAccordion(this)" style="width: 100%; text-align: left; padding: 18px 24px; background: none; border: none; font-size: 16.5px; font-weight: 600; color: var(--navy-dark); cursor: pointer; display: flex; justify-content: space-between; align-items: center; gap: 16px;">
                                            <span>{{ $faq['question'] }}</span>
                                            <i data-lucide="chevron-down" style="width: 20px; flex-shrink: 0; transition: transform 0.2s ease;"></i>
                                        </button>
                                        <div class="accordion-body" style="padding: 0 24px 20px; font-size: 15px; color: var(--body-text); line-height: 1.7; display: {{ $index === 0 ? 'block' : 'none' }};">
                                            {{ $faq['answer'] }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Google FAQPage Schema Markup --}}
                        <script type="application/ld+json">
                        {
                            "{{ '@' }}context": "https://schema.org",
                            "{{ '@' }}type": "FAQPage",
                            "mainEntity": [
                                @foreach($serviceFaqs as $index => $faq)
                                {
                                    "{{ '@' }}type": "Question",
                                    "name": "{{ addslashes($faq['question']) }}",
                                    "acceptedAnswer": {
                                        "{{ '@' }}type": "Answer",
                                        "text": "{{ addslashes($faq['answer']) }}"
                                    }
                                }{{ $index < count($serviceFaqs) - 1 ? ',' : '' }}
                                @endforeach
                            ]
                        }
                        </script>
                    @endif
                </div>
            </div>

            <!-- Sticky Booking Sidebar -->
            <div style="position: sticky; top: 100px;">
                <div class="form-card" style="margin-bottom: 24px;">
                    <h3 style="font-size: 20px; margin-bottom: 8px;">Request Consultation</h3>
                    <p style="font-size: 13.5px; color: var(--muted-text); margin-bottom: 20px;">Schedule your visit for {{ $service->service_name }}.</p>
                    <form action="{{ route('contact.submit') }}" method="POST">
                        @csrf
                        <input type="hidden" name="source" value="Service Page Form: {{ $service->service_name }}">
                        <input type="hidden" name="service" value="{{ $service->slug }}">
                        <div class="form-group">
                            <label for="sbs-name" class="form-label">Full Name</label>
                            <input type="text" id="sbs-name" name="name" class="form-control" placeholder="Enter full name" required>
                        </div>
                        <div class="form-group">
                            <label for="sbs-phone" class="form-label">Phone Number</label>
                            <input type="tel" id="sbs-phone" name="phone" class="form-control" placeholder="10-digit number" pattern="[0-9]{10}" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-full" style="margin-top: 8px;">
                            <i data-lucide="calendar"></i> Book Consultation
                        </button>
                    </form>
                </div>

                <div class="location-card" style="padding: 24px;">
                    <h4 style="font-size: 16px; margin-bottom: 8px;">Direct Clinic Helpline</h4>
                    <p style="font-size: 13.5px; color: var(--body-text); margin-bottom: 16px;">Speak directly with our clinical coordinator.</p>
                    <a href="tel:+919545559434" class="btn btn-secondary btn-full btn-sm">
                        <i data-lucide="phone"></i> Call +91 95455 59434
                    </a>
                </div>
            </div>
        </div>
    </section>

    <script>
        function toggleAccordion(button) {
            const item = button.parentElement;
            const body = item.querySelector('.accordion-body');
            const isActive = item.classList.contains('active');
            
            document.querySelectorAll('.accordion-item').forEach(el => {
                el.classList.remove('active');
                const b = el.querySelector('.accordion-body');
                if (b) b.style.display = 'none';
            });
            
            if (!isActive) {
                item.classList.add('active');
                if (body) body.style.display = 'block';
            }
        }
    </script>

@endsection
