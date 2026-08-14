@extends('layouts.app')

@section('title', 'Request Received | ' . ($site['site_name'] ?? 'SumitZ Clinic'))
@section('meta_description', 'Thank you for submitting your appointment request. Our medical coordinator will contact you shortly.')

@section('content')
    <section class="section-spacing" style="background-color: var(--warm-ivory); min-height: 70vh; display: flex; align-items: center;">
        <div class="container text-center" style="max-width: 600px;">
            <div style="width: 80px; height: 80px; border-radius: 50%; background-color: var(--light-mint); color: var(--primary-teal); display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; box-shadow: var(--shadow-sm);">
                <i data-lucide="check-circle-2" style="width: 48px; height: 48px;"></i>
            </div>
            
            <h1 style="font-size: 36px; margin-bottom: 12px; color: var(--heading-navy);">Request Submitted Successfully!</h1>
            
            <p style="font-size: 16.5px; line-height: 1.7; color: var(--body-text); margin-bottom: 32px;">
                Thank you for reaching out to SumitZ Clinic. Our clinical coordinator will review your request and call you back shortly to confirm your appointment time.
            </p>

            <div class="location-card" style="text-align: left; padding: 28px; margin-bottom: 32px; background-color: var(--surface-white);">
                <h3 style="font-size: 17px; margin-bottom: 12px; color: var(--heading-navy); display: flex; align-items: center; gap: 8px;">
                    <i data-lucide="info" style="color: var(--primary-teal); width: 18px;"></i> What Happens Next?
                </h3>
                <ol style="margin: 0; padding-left: 20px; font-size: 14.5px; color: var(--body-text); line-height: 1.7;">
                    <li style="margin-bottom: 8px;">Our team checks slot availability for your selected treatment.</li>
                    <li style="margin-bottom: 8px;">A coordinator contacts you via phone to finalize the exact appointment time.</li>
                    <li>You receive clinic directions and visit guidelines directly on your mobile device.</li>
                </ol>
            </div>

            <div style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('home') }}" class="btn btn-primary">
                    <i data-lucide="home"></i> Return to Homepage
                </a>
                <a href="{{ route('blog') }}" class="btn btn-secondary">
                    <i data-lucide="book-open"></i> Read Health Guides
                </a>
            </div>
        </div>
    </section>
@endsection
