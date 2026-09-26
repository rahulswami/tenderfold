@extends('layouts.app')

@section('title', 'All Services | ' . ($site['site_name'] ?? 'Tender Fold') . ' Gurugram')
@section('meta_description', 'Explore Tender Fold services in Gurugram: Laundry Services, Dry Cleaning, Steam Ironing, Shoe Cleaning, Carpet Spa, and Curtain Care.')

@section('content')
    <section class="bg-cream">
      <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8 lg:py-20">
        <div class="reveal max-w-2xl">
          <p class="text-sm font-semibold text-mute"><a href="{{ route('home') }}" class="transition hover:text-navy">Home</a> <span aria-hidden="true">/</span> <span class="text-navy">Services</span></p>
          <p class="mt-5 text-xs font-bold uppercase tracking-[0.18em] text-gold-deep">What we take in</p>
          <h1 class="mt-3 font-display text-4xl font-extrabold text-navy sm:text-5xl">Six services. One doorstep collection.</h1>
          <p class="mt-4 text-lg text-mute">Laundry and ironing are priced per kilogram. Dry cleaning, shoes and curtains are priced per piece. Carpets are quoted on size.</p>
          <button type="button" data-open-book data-service="Mixed pickup" class="mt-8 inline-flex items-center justify-center rounded-full bg-gold px-6 py-3.5 font-display text-sm font-extrabold text-navy transition duration-300 hover:-translate-y-0.5 hover:bg-gold-deep shadow-md">Schedule a Pickup</button>
        </div>

        @php
          $serviceImages = [
            'laundry-services' => 'assets/hands.jpg',
            'dry-cleaning' => 'assets/suit.jpg',
            'ironing' => 'assets/shirts.jpg',
            'shoe-cleaning' => 'assets/shoe.jpg',
            'carpet-cleaning' => 'assets/carpet.jpg',
            'curtain-cleaning' => 'assets/curtain.jpg',
          ];
        @endphp

        <div class="mt-12 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
          @foreach($services as $idx => $srv)
            <a href="{{ route('services.show', $srv->slug) }}" class="reveal group flex flex-col overflow-hidden rounded-3xl bg-white shadow-card transition duration-300 hover:-translate-y-1 hover:shadow-lift" style="animation-delay: {{ $idx * 60 }}ms">
              <div class="overflow-hidden">
                <img src="{{ asset($serviceImages[$srv->slug] ?? 'assets/whiteshirt.jpg') }}" alt="{{ $srv->service_name }}" class="h-48 w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">
              </div>
              <div class="flex flex-1 flex-col p-6">
                <p class="text-xs font-bold uppercase tracking-wider text-gold-deep">{{ $srv->rate_value }}</p>
                <h2 class="mt-1 font-display text-2xl font-extrabold text-navy group-hover:text-gold transition">{{ $srv->service_name }}</h2>
                <p class="mt-2 flex-1 text-sm leading-relaxed text-mute">{{ $srv->summary }}</p>
                <span class="mt-4 font-display text-sm font-extrabold text-navy group-hover:text-gold inline-flex items-center gap-1">Open this service →</span>
              </div>
            </a>
          @endforeach
        </div>
      </div>
    </section>

    <!-- Help Banner -->
    <section class="bg-navy">
      <div class="mx-auto flex max-w-7xl flex-col items-start justify-between gap-6 px-4 py-12 sm:px-6 md:flex-row md:items-center lg:px-8">
        <div class="max-w-xl">
          <p class="text-xs font-bold uppercase tracking-[0.18em] text-gold">Free pickup &amp; delivery</p>
          <h2 class="mt-2 font-display text-3xl font-extrabold text-white">Not sure which service a piece needs?</h2>
          <p class="mt-2 text-sm leading-relaxed text-white/75">Send a photo on WhatsApp or book a mixed pickup. We will inspect the fabric and recommend whether it should be washed or dry cleaned.</p>
        </div>
        <div class="flex w-full flex-col gap-3 sm:w-auto sm:flex-row">
          <button type="button" data-open-book data-service="Mixed pickup" class="inline-flex items-center justify-center rounded-full bg-gold px-6 py-3.5 font-display text-sm font-extrabold text-navy transition duration-300 hover:-translate-y-0.5 hover:bg-gold-deep shadow-md">Schedule a Pickup</button>
          <a href="tel:{{ preg_replace('/[^0-9+]/', '', $site['phone'] ?? '+919582466445') }}" class="inline-flex items-center justify-center rounded-full border-2 border-white/25 px-6 py-3.5 font-display text-sm font-extrabold text-white transition duration-300 hover:-translate-y-0.5 hover:bg-white hover:text-navy">Call {{ $site['phone_display'] ?? $site['phone'] ?? '+91 95824 66445' }}</a>
        </div>
      </div>
    </section>
@endsection
