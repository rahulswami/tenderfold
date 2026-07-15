@extends('layouts.app')

@section('title', $specialist->name . ' - ' . $specialist->designation . ' | SumitZ Clinic')
@section('meta_description', $specialist->metadescription ?? Str::limit(strip_tags($specialist->description), 150))

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
              "{{ '@id' }}": "{{ route('specialists.show', $specialist->slug) }}/#physician",
              "name": "{{ $specialist->name }}",
              "jobTitle": "{{ $specialist->designation }}",
              "description": "{{ $specialist->metadescription ?? strip_tags(Str::limit($specialist->description, 200)) }}",
              "image": "{{ $specialist->image ? site_image($specialist->image) : '' }}",
              "telephone": "{{ $specialist->phone ?? '+919672777749' }}",
              "medicalSpecialty": "Orthopedics",
              "affiliation": {
                "{{ '@type' }}": "Hospital",
                "name": "{{ $site['site_name'] ?? 'SumitZ Clinic' }}"
              }
            }
          ]
        }
        </script>
    @endif
@endsection

@section('content')
<section class="details-hero" style="padding: 100px 0 60px;">
    <div class="container">
        <div style="display: flex; flex-direction: row; gap: 40px; align-items: center; flex-wrap: wrap;">
            <div style="width: 320px; height: 320px; border-radius: var(--radius-lg); overflow: hidden; background-color: var(--bg-light); display: flex; align-items: center; justify-content: center; box-shadow: var(--shadow-lg); border: 4px solid #fff;">
                @if($specialist->image)
                    <img src="{{ site_image($specialist->image) }}" alt="{{ $specialist->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    <i data-lucide="user" style="width: 120px; height: 120px; color: var(--accent-teal);"></i>
                @endif
            </div>
            <div style="flex: 1; min-width: 300px;">
                <span class="color-mint font-semibold" style="text-transform: uppercase; letter-spacing: 1.5px; font-size: 0.9rem;">Specialist Profile</span>
                <h1 style="margin-top: 10px; margin-bottom: 5px; font-size: 2.5rem; color: var(--primary-teal-dark);">{{ $specialist->name }}</h1>
                <p style="font-size: 1.2rem; color: var(--accent-teal); font-weight: 500; margin-bottom: 1.5rem;">{{ $specialist->designation }}</p>
                
                @if($specialist->phone)
                    <p style="margin-bottom: 0.5rem; display: flex; align-items: center; gap: 8px; color: var(--text-light-muted);"><i data-lucide="phone" style="width: 18px; height: 18px; color: var(--accent-teal);"></i> <a href="tel:{{ $specialist->phone }}" style="color: inherit;">{{ $specialist->phone }}</a></p>
                @endif
                
                <div class="social-links" style="margin-top: 1.5rem; display: flex; gap: 10px;">
                    @if($specialist->facebook)
                        <a href="{{ $specialist->facebook }}" target="_blank" class="social-icon" style="background-color: rgba(255,255,255,0.1); color:#fff;"><i data-lucide="facebook"></i></a>
                    @endif
                    @if($specialist->twitter)
                        <a href="{{ $specialist->twitter }}" target="_blank" class="social-icon" style="background-color: rgba(255,255,255,0.1); color:#fff;"><i data-lucide="twitter"></i></a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container animate-on-scroll">
        <div class="compare-container" style="display: flex; gap: 40px; flex-wrap: wrap;">
            <div style="flex: 2; min-width: 300px;">
                <h2 style="color: var(--primary-teal-dark); margin-bottom: 1.5rem; border-bottom: 2px solid var(--accent-teal); padding-bottom: 10px; display: inline-block;">About & Expertise</h2>
                <div class="blog-content" style="line-height: 1.8; font-size: 1.05rem; color: var(--text-dark);">
                    {!! $specialist->description !!}
                </div>
            </div>
            
            <div style="flex: 1; min-width: 300px;">
                <div class="comparison-card" style="position: sticky; top: 100px; padding: 2rem; background: var(--bg-light); border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); border: 1px solid var(--admin-border);">
                    <h3 style="color: var(--primary-teal-dark); margin-bottom: 1rem; font-size: 1.4rem;">Book Consultation</h3>
                    <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 1.5rem;">Schedule an direct face-to-face appointment with {{ $specialist->name }}.</p>
                    
                    <form action="{{ route('contact.submit') }}" method="POST">
                        @csrf
                        <input type="hidden" name="source" value="Specialist Page Slot Request: {{ $specialist->name }}">
                        <div class="form-group" style="margin-bottom: 1rem;">
                            <label style="font-weight: 500; font-size: 0.85rem; margin-bottom: 4px; display: block;">Your Full Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Full Name" required>
                        </div>
                        <div class="form-group" style="margin-bottom: 1rem;">
                            <label style="font-weight: 500; font-size: 0.85rem; margin-bottom: 4px; display: block;">Phone Number</label>
                            <input type="tel" name="phone" class="form-control" placeholder="10-digit mobile" pattern="[0-9]{10}" required>
                        </div>
                        <div class="form-group" style="margin-bottom: 1.5rem;">
                            <label style="font-weight: 500; font-size: 0.85rem; margin-bottom: 4px; display: block;">Brief Message</label>
                            <textarea name="message" class="form-control" rows="3" placeholder="Describe your symptoms/requirements..."></textarea>
                        </div>
                        @if(!empty($site['recaptcha_enabled']) && $site['recaptcha_enabled'] == '1' && !empty($site['recaptcha_site_key']))
                            <div class="form-group" style="margin-bottom: 1rem; display: flex; justify-content: center;">
                                <div class="g-recaptcha" data-sitekey="{{ $site['recaptcha_site_key'] }}"></div>
                            </div>
                        @endif
                        <button type="submit" class="btn btn-primary" style="width: 100%;">Submit Appointment Request</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
