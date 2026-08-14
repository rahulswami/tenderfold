@extends('layouts.app')

@section('title', ($specialist->metatitle ?? ($specialist->name . ' - ' . $specialist->designation . ' | SumitZ Clinic')))
@section('meta_description', $specialist->metadescription ?? Str::limit(strip_tags($specialist->description), 150))
@section('meta_keywords', ($specialist->metakeywords ?? ''))

@section('schema')
    @if(!empty($specialist->schema_markup))
        {!! $specialist->schema_markup !!}
    @else
        <script type="application/ld+json">
        {
          "{{ '@' }}context": "https://schema.org",
          "{{ '@graph' }}": [
            {
              "{{ '@type' }}": "Physician",
              "name": "{{ $specialist->name }}",
              "jobTitle": "{{ $specialist->designation }}",
              "description": "{{ strip_tags(Str::limit($specialist->description, 200)) }}",
              "medicalSpecialty": "Orthopedics"
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
                <a href="{{ route('specialists') }}">Specialists</a> /
                <span style="color: var(--primary-teal); font-weight: 600;">{{ $specialist->name }}</span>
            </div>

            <div style="display: grid; grid-template-columns: 240px 1fr; gap: 32px; align-items: center;">
                <div style="width: 240px; height: 280px; border-radius: var(--radius-xl); overflow: hidden; box-shadow: var(--shadow-md); background-color: var(--warm-ivory);">
                    @if(!empty($specialist->image))
                        <img src="{{ site_image($specialist->image) }}" alt="{{ $specialist->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <img src="https://images.unsplash.com/photo-1622253692010-333f2da6031d?auto=format&fit=crop&w=600&q=80" alt="{{ $specialist->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @endif
                </div>
                <div>
                    <span class="badge badge-teal" style="margin-bottom: 10px;">Medical Specialist</span>
                    <h1 style="font-size: clamp(30px, 3.5vw, 44px); margin-bottom: 8px;">{{ $specialist->name }}</h1>
                    <span style="font-size: 18px; font-weight: 700; color: var(--primary-teal); display: block; margin-bottom: 16px;">
                        {{ $specialist->designation }}
                    </span>
                    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                        <button onclick="openDialog('callback-dialog');" class="btn btn-primary btn-sm">
                            <i data-lucide="calendar"></i> Book Consultation
                        </button>
                        <a href="tel:+919545559434" class="btn btn-secondary btn-sm">
                            <i data-lucide="phone"></i> Call Clinic
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Profile & Booking Sidebar -->
    <section class="section-spacing" style="background-color: var(--warm-ivory);">
        <div class="container" style="display: grid; grid-template-columns: 1fr 340px; gap: 48px; align-items: start;">
            <div style="font-size: 16.5px; line-height: 1.8; color: var(--body-text);">
                <h2 style="font-size: 26px; margin-bottom: 16px;">Professional Profile & Areas of Expertise</h2>
                {!! $specialist->description !!}
            </div>

            <!-- Sticky Booking Sidebar -->
            <div style="position: sticky; top: 100px;">
                <div class="form-card" style="margin-bottom: 24px;">
                    <h3 style="font-size: 20px; margin-bottom: 8px;">Book Consultation</h3>
                    <p style="font-size: 13.5px; color: var(--muted-text); margin-bottom: 20px;">Schedule an appointment with {{ $specialist->name }}.</p>
                    <form action="{{ route('contact.submit') }}" method="POST">
                        @csrf
                        <input type="hidden" name="source" value="Specialist Profile Request: {{ $specialist->name }}">
                        <div class="form-group">
                            <label for="sp-name" class="form-label">Full Name</label>
                            <input type="text" id="sp-name" name="name" class="form-control" placeholder="Enter full name" required>
                        </div>
                        <div class="form-group">
                            <label for="sp-phone" class="form-label">Phone Number</label>
                            <input type="tel" id="sp-phone" name="phone" class="form-control" placeholder="10-digit number" pattern="[0-9]{10}" required>
                        </div>
                        <div class="form-group">
                            <label for="sp-msg" class="form-label">Brief Message (Optional)</label>
                            <textarea id="sp-msg" name="message" class="form-control" rows="2" placeholder="Briefly state your concern..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-full" style="margin-top: 8px;">
                            <i data-lucide="calendar"></i> Confirm Consultation Request
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

@endsection
