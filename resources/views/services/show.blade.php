@extends('layouts.app')

@section('title', $service->service_name . ' | ' . ($site['site_name'] ?? 'Tender Fold') . ' Gurgaon')
@section('meta_description', $service->summary . ' Doorstep collection across Gurgaon. Call ' . ($site['phone'] ?? '+91 95824 66445') . '.')

@section('content')
    @php
        $serviceImages = [
            'laundry-services' => 'assets/hands.jpg',
            'dry-cleaning' => 'assets/suit.jpg',
            'ironing' => 'assets/shirts.jpg',
            'shoe-cleaning' => 'assets/shoe.jpg',
            'carpet-cleaning' => 'assets/carpet.jpg',
            'curtain-cleaning' => 'assets/curtain.jpg',
        ];
        $img = $serviceImages[$service->slug] ?? 'assets/whiteshirt.jpg';

        $serviceRates = [
            'laundry-services' => [
                ['name' => 'Wash & Fold (Daily Wear)', 'price' => '₹80 / kg'],
                ['name' => 'Wash & Steam Iron (Office Wear)', 'price' => '₹115 / kg'],
                ['name' => 'Premium Laundry (Delicates & Linens)', 'price' => '₹185 / kg'],
            ],
            'dry-cleaning' => [
                ['name' => 'Shirt / T-Shirt / Top', 'price' => 'From ₹130'],
                ['name' => 'Trousers / Jeans / Chinos', 'price' => 'From ₹150'],
                ['name' => 'Saree (Silk / Georgette)', 'price' => 'From ₹230'],
                ['name' => '2-Piece Men’s Suit / Blazer', 'price' => 'From ₹400'],
                ['name' => 'Heavy Bridal Lehenga / Sherwani', 'price' => 'From ₹550'],
            ],
            'ironing' => [
                ['name' => 'Shirt / T-Shirt (Steam Pressed)', 'price' => 'From ₹19 / pc'],
                ['name' => 'Trouser / Kurta', 'price' => 'From ₹25 / pc'],
                ['name' => 'Saree / Heavy Garment', 'price' => 'From ₹65 / pc'],
                ['name' => 'Wash & Iron Bulk Plan', 'price' => '₹115 / kg'],
            ],
            'shoe-cleaning' => [
                ['name' => 'Sneakers / Sports Shoes', 'price' => '₹350 / pair'],
                ['name' => 'Leather Shoes & Formal Loafers', 'price' => '₹450 / pair'],
                ['name' => 'Suede / Luxury Boots', 'price' => '₹550 / pair'],
            ],
            'carpet-cleaning' => [
                ['name' => 'Standard Floor Rugs', 'price' => '₹25 / sq.ft'],
                ['name' => 'Wool & Persian Carpets', 'price' => '₹35 / sq.ft'],
                ['name' => 'Large Living Area Carpets', 'price' => 'Quoted on size'],
            ],
            'curtain-cleaning' => [
                ['name' => 'Single Window Drape (Unlined)', 'price' => 'From ₹230 / panel'],
                ['name' => 'Floor Length Blackout / Velvet Curtain', 'price' => 'From ₹350 / panel'],
                ['name' => 'Sheer & Net Curtains', 'price' => 'From ₹180 / panel'],
            ]
        ];

        $ratesList = $serviceRates[$service->slug] ?? [
            ['name' => 'Standard Care', 'price' => $service->rate_value],
            ['name' => 'Express Same-Day Option', 'price' => 'Available on request']
        ];
    @endphp

    <!-- Hero Header -->
    <section class="bg-cream">
      <div class="mx-auto grid max-w-7xl items-center gap-10 px-4 py-14 sm:px-6 lg:grid-cols-2 lg:px-8 lg:py-20">
        <div class="reveal">
          <p class="text-sm font-semibold text-mute">
            <a href="{{ route('home') }}" class="transition hover:text-navy">Home</a> <span aria-hidden="true">/</span> 
            <a href="{{ route('services') }}" class="transition hover:text-navy">Services</a> <span aria-hidden="true">/</span> 
            <span class="text-navy">{{ $service->service_name }}</span>
          </p>
          <p class="mt-5 text-xs font-bold uppercase tracking-[0.18em] text-gold-deep">{{ $service->hero_category ?? 'Tender Fold Care' }}</p>
          <h1 class="mt-3 font-display text-4xl font-extrabold leading-tight text-navy sm:text-5xl">{{ $service->hero_title ?: $service->service_name }}</h1>
          <p class="mt-4 text-lg leading-relaxed text-mute">{{ $service->hero_subtitle ?: $service->summary }}</p>
          <p class="mt-4 font-display text-2xl font-extrabold text-navy">{{ $service->rate_value }}</p>
          <div class="mt-8 flex flex-col gap-3 sm:flex-row">
            <button type="button" data-open-book data-service="{{ $service->service_name }}" class="inline-flex items-center justify-center rounded-full bg-gold px-6 py-3.5 font-display text-sm font-extrabold text-navy transition duration-300 hover:-translate-y-0.5 hover:bg-gold-deep shadow-md">Schedule a Pickup</button>
            <a href="{{ route('pricing') }}" class="inline-flex items-center justify-center rounded-full border-2 border-navy/15 bg-white px-6 py-3.5 font-display text-sm font-extrabold text-navy transition duration-300 hover:-translate-y-0.5 hover:border-navy hover:bg-navy hover:text-white">See rate card</a>
          </div>
        </div>
        <div class="reveal overflow-hidden rounded-[2rem] shadow-lift" style="animation-delay:80ms">
          <img src="{{ asset($img) }}" alt="{{ $service->service_name }}" class="h-72 w-full object-cover sm:h-96 lg:h-[28rem]">
        </div>
      </div>
    </section>

    <!-- Handling & Process -->
    <section class="bg-white py-16 md:py-20">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="reveal max-w-2xl">
          <h2 class="font-display text-3xl font-extrabold text-navy">How {{ $service->service_name }} is handled</h2>
          <div class="mt-4 text-mute leading-relaxed text-base">
            @if($service->intro_content)
              {!! $service->intro_content !!}
            @else
              <p>Every piece is inspected for fabric type, color stability, and stains before selecting the optimal gentle cleaning path.</p>
            @endif
          </div>
        </div>

        <div class="mt-10 grid gap-5 md:grid-cols-3">
          <article class="reveal rounded-3xl bg-cream p-6 shadow-card" style="animation-delay:0ms">
            <p class="font-display text-sm font-extrabold text-gold-deep">01</p>
            <h3 class="mt-2 font-display text-xl font-extrabold text-navy">Fabric &amp; Color Sorted</h3>
            <p class="mt-2 text-sm leading-relaxed text-mute">Lights, darks, and delicate embroidery stay in dedicated cycles so colors remain vibrant and unblemished.</p>
          </article>
          <article class="reveal rounded-3xl bg-cream p-6 shadow-card" style="animation-delay:80ms">
            <p class="font-display text-sm font-extrabold text-gold-deep">02</p>
            <h3 class="mt-2 font-display text-xl font-extrabold text-navy">Boutique Finish</h3>
            <p class="mt-2 text-sm leading-relaxed text-mute">Delivered folded crisp or hung on custom hangers in breathable protective covers ready for your wardrobe.</p>
          </article>
          <article class="reveal rounded-3xl bg-cream p-6 shadow-card" style="animation-delay:160ms">
            <p class="font-display text-sm font-extrabold text-gold-deep">03</p>
            <h3 class="mt-2 font-display text-xl font-extrabold text-navy">Single Doorstep Pickup</h3>
            <p class="mt-2 text-sm leading-relaxed text-mute">All items—from everyday laundry to shoes, rugs, and formal dry cleaning—can be collected together.</p>
          </article>
        </div>
      </div>
    </section>

    <!-- Published Rates Table & Offer -->
    <section class="bg-cream py-16 md:py-20">
      <div class="mx-auto grid max-w-7xl items-start gap-8 px-4 sm:px-6 lg:grid-cols-[1.1fr_0.9fr] lg:px-8">
        <div class="reveal overflow-hidden rounded-3xl bg-white shadow-card">
          <header class="bg-navy px-6 py-5 text-white">
            <h2 class="font-display text-xl font-extrabold">Published rates</h2>
            <p class="text-sm text-white/70">{{ $service->service_name }}</p>
          </header>
          <ul class="text-sm divide-y divide-navy/5">
            @foreach($ratesList as $rItem)
              <li class="flex items-center justify-between gap-3 px-6 py-3.5">
                <span class="font-medium text-ink">{{ $rItem['name'] }}</span>
                <span class="font-display font-extrabold text-navy">{{ $rItem['price'] }}</span>
              </li>
            @endforeach
          </ul>
        </div>
        <div class="reveal rounded-3xl bg-navy p-8 text-white shadow-lift" style="animation-delay:80ms">
          <p class="text-xs font-bold uppercase tracking-[0.18em] text-gold">Before we collect</p>
          <h3 class="mt-2 font-display text-2xl font-extrabold">20% off your first pickup</h3>
          <p class="mt-3 text-sm leading-relaxed text-white/80">Experience Tender Fold's premium care with 20% off your initial order and 100% free doorstep pickup and delivery across Gurgaon.</p>
          <button type="button" data-open-book data-service="{{ $service->service_name }}" class="mt-6 inline-flex items-center justify-center rounded-full bg-gold px-6 py-3.5 font-display text-sm font-extrabold text-navy transition duration-300 hover:-translate-y-0.5 hover:bg-gold-deep shadow-md">Book {{ $service->service_name }}</button>
        </div>
      </div>
    </section>

    <!-- Other Services in the same pickup -->
    <section class="bg-white py-16">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <h2 class="reveal font-display text-2xl font-extrabold text-navy">Other services in the same pickup</h2>
        <div class="mt-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
          @foreach($allServices as $otherSrv)
            @if($otherSrv->id !== $service->id)
              <a href="{{ route('services.show', $otherSrv->slug) }}" class="group flex items-center justify-between gap-3 rounded-2xl bg-white px-5 py-4 shadow-card transition duration-300 hover:-translate-y-0.5 hover:shadow-lift border border-navy/5">
                <span>
                  <span class="block font-display text-base font-extrabold text-navy group-hover:text-gold transition">{{ $otherSrv->service_name }}</span>
                  <span class="text-sm text-mute">{{ $otherSrv->rate_value }}</span>
                </span>
                <span class="text-navy transition group-hover:translate-x-1 group-hover:text-gold" aria-hidden="true">→</span>
              </a>
            @endif
          @endforeach
        </div>
      </div>
    </section>

    <!-- Bottom Banner -->
    <section class="bg-navy">
      <div class="mx-auto flex max-w-7xl flex-col items-start justify-between gap-6 px-4 py-12 sm:px-6 md:flex-row md:items-center lg:px-8">
        <div class="max-w-xl">
          <p class="text-xs font-bold uppercase tracking-[0.18em] text-gold">Free pickup &amp; delivery</p>
          <h2 class="mt-2 font-display text-3xl font-extrabold text-white">Send {{ strtolower($service->service_name) }} with the next collection.</h2>
          <p class="mt-2 text-sm leading-relaxed text-white/75">Call {{ $site['phone_display'] ?? $site['phone'] ?? '+91 95824 66445' }} or leave a pickup request. We confirm the window on WhatsApp from Sector 49 or Sector 83.</p>
        </div>
        <div class="flex w-full flex-col gap-3 sm:w-auto sm:flex-row">
          <button type="button" data-open-book data-service="{{ $service->service_name }}" class="inline-flex items-center justify-center rounded-full bg-gold px-6 py-3.5 font-display text-sm font-extrabold text-navy transition duration-300 hover:-translate-y-0.5 hover:bg-gold-deep shadow-md">Schedule a Pickup</button>
          <a href="tel:{{ preg_replace('/[^0-9+]/', '', $site['phone'] ?? '+919582466445') }}" class="inline-flex items-center justify-center rounded-full border-2 border-white/25 px-6 py-3.5 font-display text-sm font-extrabold text-white transition duration-300 hover:-translate-y-0.5 hover:bg-white hover:text-navy">Call {{ $site['phone_display'] ?? $site['phone'] ?? '+91 95824 66445' }}</a>
        </div>
      </div>
    </section>
@endsection
