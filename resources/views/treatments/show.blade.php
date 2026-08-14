@extends('layouts.app')

@section('title', ($treatment->metatitle ?? ($treatment->name . ' Treatment | ' . ($site['site_name'] ?? 'SumitZ Clinic'))))
@section('meta_description', ($treatment->metadescription ?? Str::limit(strip_tags($treatment->description), 150)))
@section('meta_keywords', ($treatment->metakeywords ?? ''))

@section('schema')
    @if(!empty($treatment->schema_markup))
        {!! $treatment->schema_markup !!}
    @else
        <script type="application/ld+json">
        {
          "{{ '@' }}context": "https://schema.org",
          "{{ '@graph' }}": [
            {
              "{{ '@type' }}": "MedicalProcedure",
              "name": "{{ $treatment->name }}",
              "description": "{{ strip_tags(Str::limit($treatment->description, 200)) }}"
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
                <a href="{{ route('treatments') }}">Treatments</a> /
                <span style="color: var(--primary-teal); font-weight: 600;">{{ $treatment->name }}</span>
            </div>
            <span class="badge badge-teal" style="margin-bottom: 12px;">Specialized Orthopaedics</span>
            <h1 style="font-size: clamp(32px, 4vw, 48px); margin-bottom: 16px;">{{ $treatment->name }}</h1>
            <p style="font-size: 17.5px; color: var(--body-text); line-height: 1.7; max-width: 760px;">
                Comprehensive medical evaluation and tailored therapy options engineered specifically for your recovery.
            </p>
        </div>
    </section>

    <!-- Main Content & Sticky Sidebar -->
    <section class="section-spacing" style="background-color: var(--warm-ivory);">
        <div class="container" style="display: grid; grid-template-columns: 1fr 340px; gap: 48px; align-items: start;">
            <div>
                <!-- Featured Image -->
                <div style="border-radius: var(--radius-xl); overflow: hidden; margin-bottom: 36px; box-shadow: var(--shadow-md); aspect-ratio: 16/9; background-color: var(--mist-blue);">
                    @if(!empty($treatment->image))
                        <img src="{{ site_image($treatment->image) }}" alt="{{ $treatment->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <img src="https://images.unsplash.com/photo-1584515979956-d9f6e5d09982?auto=format&fit=crop&w=1000&q=80" alt="{{ $treatment->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @endif
                </div>

                <div style="font-size: 16.5px; line-height: 1.8; color: var(--body-text);">
                    <h2 style="font-size: 26px; margin-bottom: 16px;">Treatment Overview & Care Pathway</h2>
                    {!! $treatment->description !!}
                </div>
            </div>

            <!-- Sticky Booking Sidebar -->
            <div style="position: sticky; top: 100px;">
                <div class="form-card" style="margin-bottom: 24px;">
                    <h3 style="font-size: 20px; margin-bottom: 8px;">Book Treatment Slot</h3>
                    <p style="font-size: 13.5px; color: var(--muted-text); margin-bottom: 20px;">Consult our specialists about {{ $treatment->name }}.</p>
                    <form action="{{ route('contact.submit') }}" method="POST">
                        @csrf
                        <input type="hidden" name="source" value="Treatment Page Form: {{ $treatment->name }}">
                        <input type="hidden" name="service" value="{{ $treatment->slug }}">
                        <div class="form-group">
                            <label for="t-name" class="form-label">Full Name</label>
                            <input type="text" id="t-name" name="name" class="form-control" placeholder="Enter full name" required>
                        </div>
                        <div class="form-group">
                            <label for="t-phone" class="form-label">Phone Number</label>
                            <input type="tel" id="t-phone" name="phone" class="form-control" placeholder="10-digit number" pattern="[0-9]{10}" required>
                        </div>
                        <div class="form-group">
                            <label for="t-msg" class="form-label">Brief Symptoms (Optional)</label>
                            <textarea id="t-msg" name="message" class="form-control" rows="2" placeholder="State your symptoms..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-full" style="margin-top: 8px;">
                            <i data-lucide="calendar"></i> Book Consultation
                        </button>
                    </form>
                </div>

                <div class="location-card" style="padding: 24px;">
                    <h4 style="font-size: 16px; margin-bottom: 8px;">Need Quick Guidance?</h4>
                    <p style="font-size: 13.5px; color: var(--body-text); margin-bottom: 16px;">Speak directly with our clinic team.</p>
                    <a href="tel:+919545559434" class="btn btn-secondary btn-full btn-sm">
                        <i data-lucide="phone"></i> Call +91 95455 59434
                    </a>
                </div>
            </div>
        </div>
    </section>

@endsection
