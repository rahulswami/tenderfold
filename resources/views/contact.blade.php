@extends('layouts.app')

@section('title', 'Contact Us | Book Appointment | SumitZ Clinic')
@section('meta_description', 'Contact SumitZ Clinic Baner. Submit our slot booking form, drop a WhatsApp message, or find directions to our orthopaedic and physiotherapy hospital.')

@section('schema')
<script type="application/ld+json">
{
  "{{ '@' }}context": "https://schema.org",
  "{{ '@graph' }}": [
    {
      "{{ '@type' }}": "ContactPage",
      "{{ '@id' }}": "{{ route('contact') }}/#webpage",
      "url": "{{ route('contact') }}",
      "name": "Contact Us | Book Appointment | {{ $site['site_name'] ?? 'SumitZ Clinic' }}",
      "description": "Contact SumitZ Clinic Baner. Submit our slot booking form, drop a WhatsApp message, or find directions to our orthopaedic and physiotherapy hospital.",
      "isPartOf": {
        "{{ '@type' }}": "WebSite",
        "{{ '@id' }}": "{{ route('home') }}/#website"
      },
      "mainEntity": {
        "{{ '@type' }}": "Organization",
        "{{ '@id' }}": "{{ route('home') }}/#organization",
        "name": "{{ $site['site_name'] ?? 'SumitZ Clinic' }}",
        "url": "{{ route('home') }}"
      }
    }
  ]
}
</script>
@endsection

@section('content')

    <!-- Details Hero -->
    <section class="details-hero">
        <div class="container text-center">
            <span class="color-mint font-semibold" style="text-transform: uppercase; letter-spacing: 1.5px; font-size: 0.9rem;">CONNECT WITH US</span>
            <h1 style="margin-top: 10px;">Contact Our Clinic</h1>
            <p style="color: var(--text-light-muted); max-width: 600px; margin: 0 auto;">Reach out for diagnostic test slot queries, appointment bookings, or recovery guidance.</p>
        </div>
    </section>

    <!-- Contact Main Grid -->
    <section class="section">
        <div class="container contact-grid animate-on-scroll">
            <div class="contact-info-list">
                <h2>Clinic Location & Details</h2>
                <p class="text-muted" style="margin-bottom: 2rem;">We are based in Baner, Pune. Visit our facility for joint consultations, digital radiography, pathology, and advanced physiotherapy sessions.</p>
                
                <div class="contact-info-item">
                    <div class="contact-info-icon" style="color: var(--accent-teal);"><i data-lucide="map-pin"></i></div>
                    <div class="contact-info-content">
                        <h3>Our Clinic</h3>
                        <p>{{ $site['address'] ?? 'Krishna Avenue, Above Dominos, Baner Road, Baner, Pune - 411045' }}</p>
                    </div>
                </div>
                
                <div class="contact-info-item">
                    <div class="contact-info-icon" style="color: var(--accent-teal);"><i data-lucide="phone"></i></div>
                    <div class="contact-info-content">
                        <h3>Phone Support</h3>
                        <p><a href="tel:{{ $site['phone'] ?? '+919672777749' }}">{{ $site['phone'] ?? '+91 96727 77749' }}</a> (Direct Support)</p>
                    </div>
                </div>
                
                <div class="contact-info-item">
                    <div class="contact-info-icon" style="color: var(--accent-teal);"><i data-lucide="mail"></i></div>
                    <div class="contact-info-content">
                        <h3>Email Inquiries</h3>
                        <p><a href="mailto:{{ $site['email'] ?? 'info@drsumitz.com' }}">{{ $site['email'] ?? 'info@drsumitz.com' }}</a></p>
                    </div>
                </div>

                <div class="contact-info-item">
                    <div class="contact-info-icon" style="background-color:#25d366; color:white;"><i data-lucide="message-circle"></i></div>
                    <div class="contact-info-content">
                        <h3>WhatsApp Booking</h3>
                        <p><a href="https://wa.me/{{ $site['whatsapp_number'] ?? '919672777749' }}?text=Hi%20SumitZ%20Clinic,%20I'd%20like%20to%20book%20an%20appointment." target="_blank">Chat with Coordinator Now</a> (Instant response)</p>
                    </div>
                </div>
            </div>
            
            <div class="form-card">
                <h2 style="margin-bottom: 1.5rem;">Request Appointment</h2>
                <form action="{{ route('contact.submit') }}" method="POST">
                    @csrf
                    <input type="hidden" name="source" value="Contact Page Form">
                    <div class="form-group">
                        <label for="c-name">Full Name</label>
                        <input type="text" id="c-name" name="name" class="form-control" placeholder="Enter your name" required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="c-phone">Phone Number</label>
                            <input type="tel" id="c-phone" name="phone" class="form-control" placeholder="10-digit number" pattern="[0-9]{10}" required>
                        </div>
                        <div class="form-group">
                            <label for="c-email">Email Address</label>
                            <input type="email" id="c-email" name="email" class="form-control" placeholder="Enter email" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="c-dept">Preferred Treatment / Service</label>
                            <select id="c-dept" name="service" class="form-control" required>
                                <option value="">-- Select --</option>
                                <option value="physiotherapy">Physiotherapy Care</option>
                                <option value="joint-replacement">Joint Replacement Surgery</option>
                                <option value="spine-care">Spine & Back Care</option>
                                <option value="fracture-treatment">Fracture Treatment</option>
                                <option value="sports-injury">Sports Injury Rehabilitation</option>
                                <option value="other">Other Consultation</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="c-date">Preferred Date</label>
                            <input type="date" id="c-date" name="appointment_date" class="form-control">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="c-message">Symptoms / Medical History</label>
                        <textarea id="c-message" name="message" class="form-control" rows="4" placeholder="Briefly specify your pain details or symptoms..."></textarea>
                    </div>
                    
                    @if(!empty($site['recaptcha_enabled']) && $site['recaptcha_enabled'] == '1' && !empty($site['recaptcha_site_key']))
                        <div class="form-group" style="margin-bottom: 1.5rem; display: flex; justify-content: center;">
                            <div class="g-recaptcha" data-sitekey="{{ $site['recaptcha_site_key'] }}"></div>
                        </div>
                    @endif
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Book Appointment Slot</button>
                </form>
            </div>
        </div>
    </section>

    <!-- Map Integration Section -->
    <section class="section" style="padding-top:0;">
        <div class="container animate-on-scroll">
            <div class="contact-map-box">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3782.3820253457193!2d73.7885409!3d18.5568165!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bc2bec707d0f1ab%3A0xe543db544833c82e!2sBaner%20Road%2C%20Baner%2C%20Pune%2C%20Maharashtra!5e0!3m2!1sen!2sin!4v1700000000000!5m2!1sen!2sin" 
                    width="100%" height="450" style="border:0; border-radius: var(--radius-lg);" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </section>

@endsection
