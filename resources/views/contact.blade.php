@extends('layouts.app')

@section('title', ($contactPage->metatitle ?? 'Contact Us & Clinic Locations | SumitZ Clinic'))
@section('meta_description', ($contactPage->metadescription ?? 'Contact SumitZ Clinic Baner & Hinjewadi. Book appointments, get location directions, or call our medical team.'))
@section('meta_keywords', ($contactPage->metakeywords ?? 'contact orthopedic doctor baner, book appointment sumitz, clinic phone number baner'))

@section('content')

    <!-- Hero Header -->
    <section class="section-spacing" style="background: linear-gradient(135deg, var(--light-mint) 0%, var(--mist-blue) 100%); padding: 64px 0 48px;">
        <div class="container text-center" style="max-width: 760px;">
            <span class="badge badge-teal" style="margin-bottom: 12px;">Get In Touch</span>
            <h1 style="font-size: clamp(32px, 4vw, 48px); margin-bottom: 16px;">Contact SumitZ Clinic</h1>
            <p style="font-size: 17px; color: var(--body-text); line-height: 1.7;">
                Reach out for appointment scheduling, diagnostic imaging queries, or consultation guidance at our Baner and Hinjewadi branches.
            </p>
        </div>
    </section>

    <!-- Main Contact Layout -->
    <section class="section-spacing" style="background-color: var(--warm-ivory);">
        <div class="container" style="display: grid; grid-template-columns: 1fr 1.1fr; gap: 48px; align-items: start;">
            <!-- Left Column: Locations & Phone Links -->
            <div style="display: flex; flex-direction: column; gap: 24px;">
                <div class="location-card">
                    <h3 class="location-title">
                        <i data-lucide="map-pin" style="color: var(--primary-teal);"></i> Baner Clinic (Main Branch)
                    </h3>
                    <p style="font-size: 15px; color: var(--body-text); margin-bottom: 16px; line-height: 1.6;">
                        First Floor, Krishna Avenue, Above Dominos Pizza, Main Baner Road, Baner, Pune, Maharashtra - 411045
                    </p>
                    <div style="font-size: 14px; margin-bottom: 16px; color: var(--body-text);">
                        <strong>Helpline:</strong> <a href="tel:+919545559434" style="color: var(--primary-teal); font-weight: 600;">+91 95455 59434</a> / <a href="tel:+918855950433" style="color: var(--primary-teal); font-weight: 600;">+91 88559 50433</a>
                    </div>
                    <a href="https://maps.google.com/?q=SumitZ+Clinic+Baner+Pune" target="_blank" class="btn btn-secondary btn-sm">
                        <i data-lucide="navigation"></i> Baner Directions
                    </a>
                </div>

                <div class="location-card">
                    <h3 class="location-title">
                        <i data-lucide="map-pin" style="color: var(--primary-teal);"></i> Hinjewadi Clinic
                    </h3>
                    <p style="font-size: 15px; color: var(--body-text); margin-bottom: 16px; line-height: 1.6;">
                        Atlanta Society, Near Hinjewadi Phase 1 Circle, Hinjewadi, Pune, Maharashtra - 411057
                    </p>
                    <div style="font-size: 14px; margin-bottom: 16px; color: var(--body-text);">
                        <strong>Helpline:</strong> <a href="tel:+919404963781" style="color: var(--primary-teal); font-weight: 600;">+91 94049 63781</a> / <a href="tel:+919158143325" style="color: var(--primary-teal); font-weight: 600;">+91 91581 43325</a>
                    </div>
                    <a href="https://maps.google.com/?q=SumitZ+Clinic+Hinjewadi+Pune" target="_blank" class="btn btn-secondary btn-sm">
                        <i data-lucide="navigation"></i> Hinjewadi Directions
                    </a>
                </div>

                <div class="location-card" style="padding: 24px; display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <h4 style="font-size: 17px; margin-bottom: 4px;">WhatsApp Direct Contact</h4>
                        <span style="font-size: 13.5px; color: var(--muted-text);">Instant appointment guidance</span>
                    </div>
                    <a href="https://wa.me/{{ $site['whatsapp_number'] ?? '919672777749' }}?text=Hi%20SumitZ%20Clinic,%20I'm%20interested%20in%20booking%20a%20consultation." target="_blank" class="btn btn-primary btn-sm">
                        <i data-lucide="message-circle"></i> Chat Now
                    </a>
                </div>
            </div>

            <!-- Right Column: Appointment Form -->
            <div class="form-card">
                <h2 style="font-size: 24px; margin-bottom: 8px;">Request Appointment</h2>
                <p style="font-size: 14.5px; color: var(--muted-text); margin-bottom: 24px;">Submit your details and our medical coordinator will confirm your slot.</p>

                <form action="{{ route('contact.submit') }}" method="POST">
                    @csrf
                    <input type="hidden" name="source" value="Contact Page Form">
                    <div class="form-group">
                        <label for="cnt-name" class="form-label">Full Name</label>
                        <input type="text" id="cnt-name" name="name" class="form-control" placeholder="Enter your full name" required>
                    </div>

                    <div class="form-group">
                        <label for="cnt-phone" class="form-label">Phone Number</label>
                        <input type="tel" id="cnt-phone" name="phone" class="form-control" placeholder="10-digit mobile number" pattern="[0-9]{10}" required>
                    </div>

                    <div class="form-group">
                        <label for="cnt-email" class="form-label">Email Address (Optional)</label>
                        <input type="email" id="cnt-email" name="email" class="form-control" placeholder="name@example.com">
                    </div>

                    <div class="form-group">
                        <label for="cnt-service" class="form-label">Service / Treatment Required</label>
                        <select id="cnt-service" name="service" class="form-control" required>
                            <option value="">-- Select Service --</option>
                            @foreach($globalServices as $gs)
                                <option value="{{ $gs->slug }}">{{ $gs->service_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="cnt-date" class="form-label">Preferred Date</label>
                        <input type="date" id="cnt-date" name="appointment_date" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="cnt-msg" class="form-label">Symptoms / Notes (Optional)</label>
                        <textarea id="cnt-msg" name="message" class="form-control" rows="3" placeholder="Describe your symptoms..."></textarea>
                    </div>

                    @if(!empty($site['recaptcha_enabled']) && $site['recaptcha_enabled'] == '1' && !empty($site['recaptcha_site_key']))
                        <div class="form-group" style="display: flex; justify-content: center;">
                            <div class="g-recaptcha" data-sitekey="{{ $site['recaptcha_site_key'] }}"></div>
                        </div>
                    @endif

                    <button type="submit" class="btn btn-primary btn-full" style="margin-top: 8px;">
                        <i data-lucide="calendar"></i> Submit Booking Request
                    </button>
                </form>
            </div>
        </div>
    </section>

@endsection
