@extends('layouts.app')

@section('title', 'Thank You | Appointment Requested | ' . ($site['site_name'] ?? 'SumitZ Clinic'))
@section('meta_description', 'Thank you for submitting your details. One of our clinical coordinators will reach out to you shortly.')

@section('content')
    <section class="section" style="min-height: calc(100vh - 400px); display: flex; align-items: center; justify-content: center; padding: 4rem 1rem;">
        <div class="container text-center" style="max-width: 600px;">
            <div style="margin-bottom: 2rem; display: inline-flex; align-items: center; justify-content: center; width: 90px; height: 90px; border-radius: 50%; background: rgba(0, 168, 150, 0.1); color: var(--mint-green); animation: scaleUp 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) both;">
                <i data-lucide="check-circle-2" style="width: 52px; height: 52px;"></i>
            </div>
            
            <h1 style="font-size: 2.5rem; margin-bottom: 1rem; color: var(--primary-teal-dark); font-weight: 800;">Thank You!</h1>
            
            <p style="font-size: 1.15rem; line-height: 1.6; color: var(--text-muted); margin-bottom: 2rem;">
                Your request has been successfully submitted and saved. One of our clinical coordinators will review your details and call you back shortly (typically within 2 hours).
            </p>
 
            <div style="background: rgba(12, 83, 84, 0.03); border: 1px solid var(--border-light); border-radius: var(--radius-lg); padding: 1.5rem; margin-bottom: 2.5rem; text-align: left;">
                <h4 style="margin: 0 0 10px 0; color: var(--primary-teal-dark); font-size: 0.95rem; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                    <i data-lucide="info" style="width: 16px; height: 16px; color: var(--mint-green);"></i> What happens next?
                </h4>
                <ol style="margin: 0; padding-left: 1.25rem; font-size: 0.875rem; color: var(--text-muted); line-height: 1.6;">
                    <li style="margin-bottom: 6px;">We evaluate your symptoms and preferred date against current doctor schedules.</li>
                    <li style="margin-bottom: 6px;">A clinic coordinator contacts you to confirm the time slot and provide preparation details.</li>
                    <li>You receive confirmation details and clinic directions directly on your phone.</li>
                </ol>
            </div>
 
            <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('home') }}" class="btn btn-primary" style="text-decoration: none; padding: 0.75rem 1.75rem; display: inline-flex; align-items: center; gap: 8px; justify-content: center; width: auto; font-weight: 600;">
                    <i data-lucide="home" style="width: 18px; height: 18px;"></i> Go to Home Page
                </a>
                <a href="{{ route('blog') }}" class="btn-logout" style="text-decoration: none; padding: 0.75rem 1.75rem; background-color: transparent; border: 1px solid var(--border-light); color: var(--primary-teal-dark); border-radius: 4px; display: inline-flex; align-items: center; gap: 8px; justify-content: center; transition: all 0.3s ease; font-weight: 600;">
                    <i data-lucide="book-open" style="width: 18px; height: 18px;"></i> Read Latest Tips
                </a>
            </div>
        </div>
    </section>

    <style>
        @keyframes scaleUp {
            0% { transform: scale(0.5); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
        .btn-logout:hover {
            background-color: rgba(255,255,255,0.05) !important;
            border-color: var(--mint-green) !important;
            color: var(--mint-green) !important;
        }
    </style>
@endsection
