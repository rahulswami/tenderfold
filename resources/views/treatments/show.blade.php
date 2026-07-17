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
              "{{ '@id' }}": "{{ route('treatments.show', $treatment->slug) }}/#procedure",
              "name": "{{ $treatment->name }}",
              "description": "{{ $treatment->metadescription ?? strip_tags(Str::limit($treatment->description, 200)) }}"
            }
          ]
        }
        </script>
    @endif
@endsection

@section('content')
<section class="details-hero" style="padding: 100px 0 60px;">
    <div class="container text-center">
        <span class="color-mint font-semibold" style="text-transform: uppercase; letter-spacing: 1.5px; font-size: 0.9rem;">Specialized Treatment</span>
        <h1 style="margin-top: 10px; font-size: 2.5rem; color: var(--primary-teal-dark);">{{ $treatment->name }}</h1>
        <p style="color: var(--text-light-muted); max-width: 700px; margin: 10px auto 0 auto;">Comprehensive care and therapy options tailored specifically for you.</p>
    </div>
</section>

<section class="section">
    <div class="container animate-on-scroll">
        <div class="compare-container" style="display: flex; gap: 40px; flex-wrap: wrap;">
            <div style="flex: 2; min-width: 300px;">
                @if($treatment->image)
                    <div style="margin-bottom: 30px; border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-sm); max-height: 400px;">
                        <img src="{{ site_image($treatment->image) }}" alt="{{ $treatment->name }}" style="width: 100%; height: auto; object-fit: cover;">
                    </div>
                @endif

                <h2 style="color: var(--primary-teal-dark); margin-bottom: 1.5rem; border-bottom: 2px solid var(--accent-teal); padding-bottom: 10px; display: inline-block;">Treatment Overview</h2>
                
                <div class="blog-content" style="line-height: 1.8; font-size: 1.05rem; color: var(--text-dark);">
                    {!! $treatment->description !!}
                </div>
            </div>
            
            <div style="flex: 1; min-width: 300px;">
                <div class="comparison-card" style="position: sticky; top: 100px; padding: 2rem; background: var(--bg-light); border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); border: 1px solid var(--admin-border);">
                    <h3 style="color: var(--primary-teal-dark); margin-bottom: 1rem; font-size: 1.4rem;">Book Treatment slot</h3>
                    <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 1.5rem;">Schedule a diagnostic session or consult Dr. Sumit about {{ $treatment->name }}.</p>
                    
                    <form action="{{ route('contact.submit') }}" method="POST">
                        @csrf
                        <input type="hidden" name="source" value="Treatment Page Slot Request: {{ $treatment->name }}">
                        <input type="hidden" name="service" value="{{ $treatment->slug }}">
                        
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
                            <textarea name="message" class="form-control" rows="3" placeholder="Describe symptoms or pain history..."></textarea>
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
