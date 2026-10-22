@extends('layouts.app')

@section('title', ($site['site_name'] ?? 'Tenderfold') . ' | ' . ($site['site_tagline'] ?? 'Freshness in Every Single Fold - Premium Laundry & Dry Cleaner in Gurgaon'))

@section('content')
    @php
        $site = \App\Models\SiteParameter::all()->pluck('value', 'id')->toArray();
        $recentBlogs = \App\Models\Blog::orderBy('published_at', 'desc')->take(3)->get();
    @endphp

    <!-- Hero Section with Swiper Slider -->
    <section id="top" class="relative overflow-hidden bg-cream">
      <div class="swiper hero-swiper relative">
        <div class="swiper-wrapper">
          @forelse($slides as $slide)
            <div class="swiper-slide">
              <div class="mx-auto grid max-w-7xl items-center gap-10 px-4 py-12 sm:px-6 lg:grid-cols-2 lg:gap-16 lg:px-8 lg:py-16">
                <div>
                  <p class="inline-flex items-center gap-2 rounded-full bg-gold/15 px-4 py-1.5 text-xs font-bold uppercase tracking-[0.16em] text-navy">
                    <span class="h-1.5 w-1.5 rounded-full bg-gold" aria-hidden="true"></span>
                    Premium laundry &amp; dry cleaner
                  </p>
                  @if($loop->first)
                    <h1 class="mt-5 font-display text-4xl font-extrabold leading-[1.08] tracking-tight text-navy sm:text-5xl lg:text-6xl">
                      {{ $slide->title }}
                    </h1>
                  @else
                    <h2 class="mt-5 font-display text-4xl font-extrabold leading-[1.08] tracking-tight text-navy sm:text-5xl lg:text-6xl">
                      {{ $slide->title }}
                    </h2>
                  @endif
                  <p class="mt-5 max-w-xl text-base leading-relaxed text-mute sm:text-lg">
                    {{ $slide->subtitle }}
                  </p>
                  <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    @if(str_starts_with($slide->button_link ?? '#bookModal', '#'))
                      <button type="button" data-open-book class="inline-flex items-center justify-center gap-2 rounded-full bg-gold px-6 py-3.5 font-display text-sm font-extrabold text-navy shadow-md transition duration-300 hover:-translate-y-0.5 hover:bg-gold-deep hover:shadow-lg">
                        {{ $slide->button_text ?? 'Schedule a Pickup' }}
                      </button>
                    @else
                      <a href="{{ $slide->button_link }}" class="inline-flex items-center justify-center gap-2 rounded-full bg-gold px-6 py-3.5 font-display text-sm font-extrabold text-navy shadow-md transition duration-300 hover:-translate-y-0.5 hover:bg-gold-deep hover:shadow-lg">
                        {{ $slide->button_text ?? 'Schedule a Pickup' }}
                      </a>
                    @endif
                    <a href="{{ route('pricing') }}" class="inline-flex items-center justify-center rounded-full border-2 border-navy/15 bg-white px-6 py-3.5 font-display text-sm font-extrabold text-navy transition duration-300 hover:-translate-y-0.5 hover:border-navy hover:bg-navy hover:text-white">See our prices</a>
                  </div>
                </div>
                <div class="relative">
                  <div class="group relative overflow-hidden rounded-[2rem] shadow-lift">
                    <img src="{{ asset($slide->image_path ?: 'assets/hero.jpg') }}" alt="{{ $slide->title }}" class="aspect-[4/5] max-h-[520px] w-full object-cover transition duration-700 group-hover:scale-105 sm:aspect-auto sm:h-[500px]" width="1400" height="1600" fetchpriority="high">
                    <div class="absolute right-4 top-4 rounded-full bg-gold px-4 py-2 font-display text-sm font-extrabold text-navy shadow-md">20% off first order</div>
                    <div class="absolute bottom-4 left-4 right-4 rounded-2xl bg-white/95 p-4 shadow-lift backdrop-blur sm:right-auto sm:max-w-xs">
                      <p class="font-display text-lg font-extrabold text-navy">Free pickup &amp; delivery</p>
                      <p class="mt-1 text-sm text-mute">Across Gurgaon. Terms apply on the welcome offer.</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          @empty
            <div class="swiper-slide">
              <div class="mx-auto grid max-w-7xl items-center gap-10 px-4 py-12 sm:px-6 lg:grid-cols-2 lg:gap-16 lg:px-8 lg:py-16">
                <div>
                  <p class="inline-flex items-center gap-2 rounded-full bg-gold/15 px-4 py-1.5 text-xs font-bold uppercase tracking-[0.16em] text-navy">
                    <span class="h-1.5 w-1.5 rounded-full bg-gold" aria-hidden="true"></span>
                    Premium laundry &amp; dry cleaner
                  </p>
                  <h1 class="mt-5 font-display text-4xl font-extrabold leading-[1.08] tracking-tight text-navy sm:text-5xl lg:text-6xl">
                    You leave it,<br><span class="text-gold">we clean it.</span>
                  </h1>
                  <p class="mt-5 max-w-xl text-base leading-relaxed text-mute sm:text-lg">
                    Doorstep laundry and dry cleaning for homes and offices in Gurgaon. We collect the pile, care for every fabric, and bring it back folded, pressed and ready to wear.
                  </p>
                  <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <button type="button" data-open-book class="inline-flex items-center justify-center gap-2 rounded-full bg-gold px-6 py-3.5 font-display text-sm font-extrabold text-navy shadow-md transition duration-300 hover:-translate-y-0.5 hover:bg-gold-deep hover:shadow-lg">Schedule a Pickup</button>
                    <a href="{{ route('pricing') }}" class="inline-flex items-center justify-center rounded-full border-2 border-navy/15 bg-white px-6 py-3.5 font-display text-sm font-extrabold text-navy transition duration-300 hover:-translate-y-0.5 hover:border-navy hover:bg-navy hover:text-white">See our prices</a>
                  </div>
                </div>
                <div class="relative">
                  <div class="group relative overflow-hidden rounded-[2rem] shadow-lift">
                    <img src="{{ asset('assets/hero.jpg') }}" alt="Freshly folded clothes" class="aspect-[4/5] max-h-[520px] w-full object-cover transition duration-700 group-hover:scale-105 sm:aspect-auto sm:h-[500px]" width="1400" height="1600" fetchpriority="high">
                    <div class="absolute right-4 top-4 rounded-full bg-gold px-4 py-2 font-display text-sm font-extrabold text-navy shadow-md">20% off first order</div>
                    <div class="absolute bottom-4 left-4 right-4 rounded-2xl bg-white/95 p-4 shadow-lift backdrop-blur sm:right-auto sm:max-w-xs">
                      <p class="font-display text-lg font-extrabold text-navy">Free pickup &amp; delivery</p>
                      <p class="mt-1 text-sm text-mute">Across Gurgaon. Terms apply on the welcome offer.</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          @endforelse
        </div>

        <button type="button" class="hero-prev absolute left-3 top-1/2 z-10 hidden h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-white text-navy shadow-lift transition hover:bg-gold sm:flex" aria-label="Previous slide">
          <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M15 6l-6 6 6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
        <button type="button" class="hero-next absolute right-3 top-1/2 z-10 hidden h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-white text-navy shadow-lift transition hover:bg-gold sm:flex" aria-label="Next slide">
          <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M9 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
        <div class="pointer-events-auto relative z-10 mx-auto mb-8 flex max-w-7xl px-4 sm:px-6 lg:absolute lg:bottom-6 lg:left-0 lg:right-0 lg:mb-0 lg:px-8">
          <div class="hero-pagination"></div>
        </div>
      </div>
    </section>

    <!-- Professional care & Pillars -->
    <section id="about" class="scroll-mt-36 bg-white py-16 md:py-24">
      <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 sm:px-6 lg:grid-cols-2 lg:gap-20 lg:px-8">
        <div class="reveal relative mx-auto w-fit">
          <div class="absolute -inset-4 rounded-full bg-gold/20" aria-hidden="true"></div>
          <img src="{{ asset('assets/blog1.jpg') }}" alt="Someone sorting a pile of folded shirts, jeans and everyday wear" class="relative h-72 w-72 rounded-full object-cover shadow-lift sm:h-80 sm:w-80" width="800" height="800">
          <div class="absolute -bottom-2 right-0 rounded-2xl bg-white px-4 py-3 shadow-lift sm:-right-4">
            <p class="font-display text-2xl font-extrabold leading-none text-gold">Free</p>
            <p class="mt-1 text-xs font-bold uppercase tracking-wide text-navy">Doorstep pickup</p>
          </div>
        </div>

        <div class="reveal" style="animation-delay: 100ms">
          <p class="text-xs font-bold uppercase tracking-[0.18em] text-gold-deep">Home &amp; office</p>
          <h2 class="mt-3 font-display text-3xl font-extrabold leading-tight text-navy sm:text-4xl">Care for every fabric, from Monday shirts to wedding silks.</h2>
          <p class="mt-4 text-base leading-relaxed text-mute sm:text-lg">
            Tender Fold is built around one habit: the cloth decides the process. Daily cottons are washed and finished. Formals, silks and embroidered pieces are dry cleaned. You leave the pile with us. We return it ready for the cupboard.
          </p>
          <ul class="mt-6 space-y-3">
            <li class="flex gap-3">
              <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-gold text-navy" aria-hidden="true">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 12.5l4.2 4.2L19 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
              </span>
              <span class="text-ink"><strong class="font-semibold">Professional cleaning</strong> for daily wear and clothes that only come out on occasion.</span>
            </li>
            <li class="flex gap-3">
              <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-gold text-navy" aria-hidden="true">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 12.5l4.2 4.2L19 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
              </span>
              <span class="text-ink"><strong class="font-semibold">Fabric care</strong> matched to cotton, silk, wool, linen and embellished wear.</span>
            </li>
            <li class="flex gap-3">
              <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-gold text-navy" aria-hidden="true">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 12.5l4.2 4.2L19 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
              </span>
              <span class="text-ink"><strong class="font-semibold">Stain removal</strong> chosen for the mark and the material, not a single harsh wash.</span>
            </li>
            <li class="flex gap-3">
              <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-gold text-navy" aria-hidden="true">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 12.5l4.2 4.2L19 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
              </span>
              <span class="text-ink"><strong class="font-semibold">Doorstep pickup &amp; delivery</strong> across Gurgaon, for the house and the workplace.</span>
            </li>
          </ul>
          <div class="mt-8 flex flex-col gap-3 sm:flex-row">
            <a href="{{ route('about') }}" class="inline-flex items-center justify-center rounded-full bg-gold px-6 py-3.5 font-display text-sm font-extrabold text-navy transition duration-300 hover:-translate-y-0.5 hover:bg-gold-deep">About Tender Fold</a>
            <a href="{{ route('services') }}" class="inline-flex items-center justify-center rounded-full bg-navy px-6 py-3.5 font-display text-sm font-extrabold text-white transition duration-300 hover:-translate-y-0.5 hover:bg-navy-dark hover:shadow-lg">Explore services</a>
          </div>
        </div>
      </div>
    </section>

    <!-- Services Dynamic Slider -->
    <section id="services" class="scroll-mt-36 bg-white py-16 md:py-24 border-t border-navy/5">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="reveal mx-auto max-w-2xl text-center">
          <p class="text-xs font-bold uppercase tracking-[0.18em] text-gold-deep">What we take in</p>
          <h2 class="mt-3 font-display text-3xl font-extrabold text-navy sm:text-4xl">Laundry, pressing and dry cleaning under one pickup.</h2>
          <p class="mt-4 text-mute">From everyday wash &amp; fold to bridal dry cleaning, shoe care, drapes, and rugs in Gurgaon.</p>
        </div>

        <div class="relative mt-12">
          <div class="swiper services-swiper">
            <div class="swiper-wrapper">
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

              @foreach(($services ?? $navServices ?? []) as $srv)
                <div class="swiper-slide">
                  <article class="group flex h-full flex-col rounded-3xl bg-white shadow-card transition duration-300 hover:-translate-y-1 hover:shadow-lift">
                    <div class="overflow-hidden rounded-t-3xl">
                      <img src="{{ asset($serviceImages[$srv->slug] ?? 'assets/whiteshirt.jpg') }}" alt="{{ $srv->service_name }}" class="h-52 w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">
                    </div>
                    <div class="relative flex flex-1 flex-col px-6 pb-6 pt-8">
                      <div class="absolute -top-6 left-6 flex h-12 w-12 items-center justify-center rounded-2xl bg-gold text-navy shadow-md" aria-hidden="true">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 8h16v11a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V8Z"/><path d="M8 8V6a4 4 0 0 1 8 0v2" stroke-linecap="round"/></svg>
                      </div>
                      <p class="text-xs font-bold uppercase tracking-wider text-gold-deep">{{ $srv->rate_value }}</p>
                      <h3 class="mt-1 font-display text-2xl font-extrabold text-navy"><a href="{{ route('services.show', $srv->slug) }}" class="transition hover:text-navy-mid">{{ $srv->service_name }}</a></h3>
                      <p class="mt-2 flex-1 text-sm leading-relaxed text-mute">{{ $srv->summary }}</p>
                      <a href="{{ route('services.show', $srv->slug) }}" class="mt-4 inline-flex items-center gap-1 font-display text-sm font-extrabold text-navy transition hover:gap-2">Open this service <span aria-hidden="true">→</span></a>
                    </div>
                  </article>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Welcome Offer Banner -->
    <section class="bg-cream py-6 md:py-10" aria-label="Welcome offer">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="reveal grid items-center gap-8 overflow-hidden rounded-[2rem] bg-navy shadow-lift lg:grid-cols-[0.9fr_1.1fr]">
          <div class="group relative h-64 overflow-hidden sm:h-80 lg:h-full lg:min-h-[340px]">
            <img src="{{ asset('assets/shirts.jpg') }}" alt="A rail of freshly pressed colourful shirts" class="h-full w-full object-cover transition duration-700 group-hover:scale-105" loading="lazy">
          </div>
          <div class="px-6 pb-8 sm:px-10 lg:py-12 lg:pr-12">
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-gold">Welcome offer</p>
            <h2 class="mt-3 font-display text-3xl font-extrabold leading-tight text-white sm:text-4xl">20% off your first order.</h2>
            <p class="mt-4 max-w-lg text-white/75">Start with a lighter bill and a free doorstep pickup. The welcome saving applies to your first Tender Fold order. Terms apply.</p>
            <div class="mt-6 flex flex-col gap-3 sm:flex-row">
              <button type="button" data-open-book data-note="Asking about the 20% first-order offer" class="inline-flex items-center justify-center rounded-full bg-gold px-6 py-3.5 font-display text-sm font-extrabold text-navy shadow-md transition duration-300 hover:-translate-y-0.5 hover:bg-gold-deep hover:shadow-lg">Claim the offer</button>
              <a href="tel:{{ preg_replace('/[^0-9+]/', '', $site['phone'] ?? '+919582466445') }}" class="inline-flex items-center justify-center rounded-full border-2 border-white/25 px-6 py-3.5 font-display text-sm font-extrabold text-white transition duration-300 hover:-translate-y-0.5 hover:bg-white hover:text-navy">Call {{ $site['phone_display'] ?? $site['phone'] ?? '+91 95824 66445' }}</a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- How it works -->
    <section id="how" class="relative scroll-mt-36 overflow-hidden bg-cream py-16 md:py-24">
      <div class="relative mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="reveal mx-auto max-w-2xl text-center">
          <svg class="mx-auto h-9 w-9 text-gold" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
            <path d="M12 4v3" stroke-linecap="round"/>
            <path d="M8 7h8" stroke-linecap="round"/>
            <path d="M9 7c0 6-2 9-2 12h10c0-3-2-6-2-12" stroke-linejoin="round"/>
          </svg>
          <p class="mt-3 text-xs font-bold uppercase tracking-[0.22em] text-gold-deep">Four simple steps</p>
          <h2 class="mt-2 font-display text-4xl font-extrabold tracking-tight text-navy sm:text-5xl">How it works</h2>
        </div>

        <div class="relative mt-14">
          <ol class="grid gap-10 sm:grid-cols-2 lg:grid-cols-4 lg:gap-6">
            <li class="reveal text-center">
              <div class="relative z-10 mx-auto h-36 w-36">
                <div class="flex h-full w-full items-center justify-center rounded-full border-[2.5px] border-dashed border-gold bg-white shadow-sm">
                  <svg class="h-14 w-14" viewBox="0 0 64 64" fill="none" aria-hidden="true">
                    <path d="M8 30h28v16H8z" fill="#143572"/>
                    <path d="M36 36h10l8 8v2H36" fill="#1E4A96"/>
                    <circle cx="18" cy="48" r="4" fill="#0C2454"/>
                    <circle cx="44" cy="48" r="4" fill="#0C2454"/>
                    <circle cx="46" cy="18" r="10" fill="#F5B400"/>
                    <path d="M46 13v6l3 2" stroke="#143572" stroke-width="1.8" stroke-linecap="round"/>
                  </svg>
                </div>
                <span class="absolute right-1 top-1 flex h-8 w-8 items-center justify-center rounded-full bg-gold font-display text-sm font-extrabold text-navy ring-4 ring-cream">1</span>
              </div>
              <h3 class="mt-5 font-display text-xl font-extrabold text-navy">Book a pickup</h3>
              <p class="mx-auto mt-2 max-w-[15rem] text-sm leading-relaxed text-mute">Tell us the service, your society and a time window. Call or WhatsApp.</p>
            </li>
            <li class="reveal text-center" style="animation-delay: 80ms">
              <div class="relative z-10 mx-auto h-36 w-36">
                <div class="flex h-full w-full items-center justify-center rounded-full border-[2.5px] border-dashed border-gold bg-white shadow-sm">
                  <svg class="h-14 w-14" viewBox="0 0 64 64" fill="none" aria-hidden="true">
                    <path d="M18 14h4l2 6h16l2-6h4v28H18V14Z" fill="#143572"/>
                    <path d="M24 20h16v16H24z" fill="#F5B400"/>
                    <path d="M28 24v8M32 24v8M36 24v8" stroke="#143572" stroke-width="1.6" stroke-linecap="round"/>
                    <path d="M22 14c1-4 4-6 10-6s9 2 10 6" stroke="#1E4A96" stroke-width="2" stroke-linecap="round"/>
                  </svg>
                </div>
                <span class="absolute right-1 top-1 flex h-8 w-8 items-center justify-center rounded-full bg-gold font-display text-sm font-extrabold text-navy ring-4 ring-cream">2</span>
              </div>
              <h3 class="mt-5 font-display text-xl font-extrabold text-navy">We collect</h3>
              <p class="mx-auto mt-2 max-w-[15rem] text-sm leading-relaxed text-mute">A rider picks the bag up from your door. No counter, no second trip downstairs.</p>
            </li>
            <li class="reveal text-center" style="animation-delay: 160ms">
              <div class="relative z-10 mx-auto h-36 w-36">
                <div class="flex h-full w-full items-center justify-center rounded-full border-[2.5px] border-dashed border-gold bg-white shadow-sm">
                  <svg class="h-14 w-14" viewBox="0 0 64 64" fill="none" aria-hidden="true">
                    <rect x="16" y="16" width="32" height="8" rx="2" fill="#1E4A96"/>
                    <rect x="14" y="26" width="36" height="8" rx="2" fill="#143572"/>
                    <rect x="16" y="36" width="32" height="8" rx="2" fill="#F5B400"/>
                    <path d="M18 20h28M16 30h32M18 40h28" stroke="#fff" stroke-width="1.2" stroke-linecap="round" opacity=".45"/>
                  </svg>
                </div>
                <span class="absolute right-1 top-1 flex h-8 w-8 items-center justify-center rounded-full bg-gold font-display text-sm font-extrabold text-navy ring-4 ring-cream">3</span>
              </div>
              <h3 class="mt-5 font-display text-xl font-extrabold text-navy">Expert care</h3>
              <p class="mx-auto mt-2 max-w-[15rem] text-sm leading-relaxed text-mute">Wash, steam iron or dry clean. The finish follows the fabric, the stain and the label.</p>
            </li>
            <li class="reveal text-center" style="animation-delay: 240ms">
              <div class="relative z-10 mx-auto h-36 w-36">
                <div class="flex h-full w-full items-center justify-center rounded-full border-[2.5px] border-dashed border-gold bg-white shadow-sm">
                  <svg class="h-14 w-14" viewBox="0 0 64 64" fill="none" aria-hidden="true">
                    <rect x="22" y="10" width="20" height="36" rx="3" fill="#143572"/>
                    <rect x="25" y="16" width="14" height="20" rx="1" fill="#E8EEF8"/>
                    <circle cx="32" cy="41" r="2" fill="#F5B400"/>
                    <circle cx="46" cy="22" r="9" fill="#F5B400"/>
                    <path d="M42 22l3 3 5-6" stroke="#143572" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                </div>
                <span class="absolute right-1 top-1 flex h-8 w-8 items-center justify-center rounded-full bg-gold font-display text-sm font-extrabold text-navy ring-4 ring-cream">4</span>
              </div>
              <h3 class="mt-5 font-display text-xl font-extrabold text-navy">We deliver</h3>
              <p class="mx-auto mt-2 max-w-[15rem] text-sm leading-relaxed text-mute">Clothes come back folded or on hangers, ready for the cupboard, the office or the day.</p>
            </li>
          </ol>
        </div>

        <div class="reveal mt-12 text-center">
          <button type="button" data-open-book class="inline-flex items-center justify-center rounded-full bg-gold px-8 py-3.5 font-display text-sm font-extrabold uppercase tracking-wide text-navy shadow-md transition duration-300 hover:-translate-y-0.5 hover:bg-gold-deep hover:shadow-lg">Schedule a Pickup</button>
        </div>
      </div>
    </section>

    <!-- Pricing Summary Section -->
    <section id="pricing" class="scroll-mt-36 bg-white py-16 md:py-24">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="reveal mx-auto max-w-2xl text-center">
          <p class="text-xs font-bold uppercase tracking-[0.18em] text-gold-deep">Laundry plans</p>
          <h2 class="mt-3 font-display text-3xl font-extrabold text-navy sm:text-4xl">Simple plans, published rates.</h2>
          <p class="mt-4 text-mute">Laundry is priced per kilogram. Dry cleaning, linen, footwear and bags are priced per piece on the rate card.</p>
        </div>

        <div class="mt-12 grid items-stretch gap-6 lg:grid-cols-3 lg:pt-4">
          <article class="reveal flex flex-col rounded-3xl border border-navy/10 bg-white p-6 shadow-card transition duration-300 hover:-translate-y-2 hover:shadow-lift sm:p-8">
            <h3 class="font-display text-xl font-extrabold text-navy">Wash &amp; Fold</h3>
            <p class="mt-1 text-sm text-mute">Everyday care, made easy.</p>
            <p class="mt-5 font-display text-4xl font-extrabold text-navy">₹80 <span class="text-base font-bold text-mute">/ kg</span></p>
            <ul class="mt-6 flex-1 space-y-3 text-sm text-ink">
              <li class="flex gap-2"><span class="text-gold" aria-hidden="true">●</span> Colour-sorted wash</li>
              <li class="flex gap-2"><span class="text-gold" aria-hidden="true">●</span> Dried and neatly folded</li>
              <li class="flex gap-2"><span class="text-gold" aria-hidden="true">●</span> Daily cottons and home wear</li>
              <li class="flex gap-2"><span class="text-gold" aria-hidden="true">●</span> Packed for the cupboard</li>
            </ul>
            <button type="button" data-open-book data-service="Wash &amp; Fold" class="mt-8 inline-flex items-center justify-center rounded-full border-2 border-navy/15 px-5 py-3 font-display text-sm font-extrabold text-navy transition duration-300 hover:-translate-y-0.5 hover:border-navy hover:bg-navy hover:text-white">Choose this plan</button>
          </article>

          <article class="reveal relative flex flex-col rounded-3xl bg-navy p-6 text-white shadow-lift transition duration-300 hover:-translate-y-2 sm:p-8 lg:-translate-y-3 lg:hover:-translate-y-5" style="animation-delay: 80ms">
            <p class="absolute -top-3 left-1/2 -translate-x-1/2 rounded-full bg-gold px-4 py-1 font-display text-xs font-extrabold uppercase tracking-wide text-navy">Most chosen</p>
            <h3 class="font-display text-xl font-extrabold">Wash &amp; Iron</h3>
            <p class="mt-1 text-sm text-white/70">Pressed, hung and office-ready.</p>
            <p class="mt-5 font-display text-4xl font-extrabold text-gold">₹115 <span class="text-base font-bold text-white/70">/ kg</span></p>
            <ul class="mt-6 flex-1 space-y-3 text-sm">
              <li class="flex gap-2"><span class="text-gold" aria-hidden="true">●</span> Washed and steam pressed</li>
              <li class="flex gap-2"><span class="text-gold" aria-hidden="true">●</span> Shirts finished on hangers</li>
              <li class="flex gap-2"><span class="text-gold" aria-hidden="true">●</span> Kurtas, trousers and office wear</li>
              <li class="flex gap-2"><span class="text-gold" aria-hidden="true">●</span> Creases only where you want them</li>
            </ul>
            <button type="button" data-open-book data-service="Wash &amp; Iron" class="mt-8 inline-flex items-center justify-center rounded-full bg-gold px-5 py-3 font-display text-sm font-extrabold text-navy shadow-md transition duration-300 hover:-translate-y-0.5 hover:bg-gold-deep hover:shadow-lg">Choose this plan</button>
          </article>

          <article class="reveal flex flex-col rounded-3xl border border-navy/10 bg-white p-6 shadow-card transition duration-300 hover:-translate-y-2 hover:shadow-lift sm:p-8" style="animation-delay: 160ms">
            <h3 class="font-display text-xl font-extrabold text-navy">Premium Laundry</h3>
            <p class="mt-1 text-sm text-mute">A finer finish for finer cloth.</p>
            <p class="mt-5 font-display text-4xl font-extrabold text-navy">₹185 <span class="text-base font-bold text-mute">/ kg</span></p>
            <ul class="mt-6 flex-1 space-y-3 text-sm text-ink">
              <li class="flex gap-2"><span class="text-gold" aria-hidden="true">●</span> Gentler cycle for finer fabrics</li>
              <li class="flex gap-2"><span class="text-gold" aria-hidden="true">●</span> Extra inspection and finishing</li>
              <li class="flex gap-2"><span class="text-gold" aria-hidden="true">●</span> Delicates welcome</li>
              <li class="flex gap-2"><span class="text-gold" aria-hidden="true">●</span> Wardrobe-ready packing</li>
            </ul>
            <button type="button" data-open-book data-service="Premium Laundry" class="mt-8 inline-flex items-center justify-center rounded-full border-2 border-navy/15 px-5 py-3 font-display text-sm font-extrabold text-navy transition duration-300 hover:-translate-y-0.5 hover:border-navy hover:bg-navy hover:text-white">Choose this plan</button>
          </article>
        </div>

        <div class="reveal mt-8 flex flex-wrap justify-center gap-2">
          <span class="rounded-full bg-cream px-4 py-2 text-sm font-semibold text-navy">Shirt ₹130</span>
          <span class="rounded-full bg-cream px-4 py-2 text-sm font-semibold text-navy">Saree ₹230+</span>
          <span class="rounded-full bg-cream px-4 py-2 text-sm font-semibold text-navy">2-pc suit ₹400</span>
          <span class="rounded-full bg-cream px-4 py-2 text-sm font-semibold text-navy">Lehenga ₹550+</span>
          <span class="rounded-full bg-cream px-4 py-2 text-sm font-semibold text-navy">Shoes from ₹350</span>
        </div>
      </div>
    </section>

    <!-- Testimonials / Quotes -->
    <section id="reviews" class="scroll-mt-36 bg-cream py-16 md:py-24">
      <div class="mx-auto max-w-4xl px-4 text-center sm:px-6 lg:px-8">
        <p class="text-xs font-bold uppercase tracking-[0.18em] text-gold-deep">Neighbourhood reviews</p>
        <h2 class="mt-3 font-display text-3xl font-extrabold text-navy sm:text-4xl">From cupboards across Gurgaon.</h2>

        @if($testimonials->count() > 0)
          @php $firstT = $testimonials->first(); @endphp
          <figure class="reveal mt-10 rounded-3xl bg-white p-8 shadow-card sm:p-12">
            <blockquote id="quoteText" class="font-display text-xl font-extrabold leading-relaxed text-navy sm:text-2xl">
              “{{ $firstT->content }}”
            </blockquote>
            <figcaption class="mt-8">
              <div class="flex justify-center gap-3">
                @foreach($testimonials as $idx => $tItem)
                  <button type="button" class="quote-tab rounded-full transition duration-300 ring-2 {{ $idx === 0 ? 'h-14 w-14 ring-gold ring-offset-2 ring-offset-cream' : 'h-12 w-12 opacity-70 ring-transparent' }}"
                    data-quote="{{ $tItem->content }}"
                    data-name="{{ $tItem->name }}"
                    data-place="{{ $tItem->role }}"
                    aria-label="Read review from {{ $tItem->name }}"
                    aria-selected="{{ $idx === 0 ? 'true' : 'false' }}">
                    <img src="{{ asset($tItem->avatar_path ?: ('assets/face' . (($idx % 4) + 1) . '.jpg')) }}" alt="{{ $tItem->name }}" class="h-full w-full rounded-full object-cover">
                  </button>
                @endforeach
              </div>
              <p id="quoteName" class="mt-4 font-display text-lg font-extrabold text-navy">{{ $firstT->name }}</p>
              <p id="quotePlace" class="text-sm text-mute">{{ $firstT->role }}</p>
            </figcaption>
          </figure>
        @endif
      </div>
    </section>

    <!-- Care Journal / Blog Previews -->
    @if($recentBlogs->count() > 0)
      <section class="bg-white py-16 md:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
              <p class="text-xs font-bold uppercase tracking-[0.18em] text-gold-deep">Fabric care notes</p>
              <h2 class="mt-2 font-display text-3xl font-extrabold text-navy sm:text-4xl">The Care Journal</h2>
            </div>
            <a href="{{ route('blog') }}" class="font-display text-sm font-extrabold text-navy transition hover:text-gold">View all articles →</a>
          </div>

          <div class="mt-10 grid gap-8 md:grid-cols-3">
            @foreach($recentBlogs as $rb)
              <article class="group flex flex-col rounded-3xl border border-navy/10 bg-white p-4 shadow-card transition duration-300 hover:-translate-y-1 hover:shadow-lift">
                <div class="overflow-hidden rounded-2xl">
                  <img src="{{ asset($rb->image_path ?: 'assets/blog1.jpg') }}" alt="{{ $rb->title }}" class="h-48 w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">
                </div>
                <div class="flex flex-1 flex-col p-3">
                  <span class="text-xs font-bold uppercase tracking-wider text-gold-deep">{{ $rb->category ?? 'Fabric Care' }}</span>
                  <h3 class="mt-1.5 font-display text-lg font-extrabold text-navy">
                    <a href="{{ route('blog.show', $rb->slug) }}" class="transition hover:text-navy-mid">{{ $rb->title }}</a>
                  </h3>
                  <p class="mt-2 flex-1 text-sm text-mute line-clamp-2">{{ $rb->summary }}</p>
                  <a href="{{ route('blog.show', $rb->slug) }}" class="mt-4 font-display text-xs font-extrabold text-navy transition hover:text-gold">Read guide →</a>
                </div>
              </article>
            @endforeach
          </div>
        </div>
      </section>
    @endif

    <!-- FAQ Accordions -->
    <section id="faq" class="scroll-mt-36 bg-cream py-16 md:py-24">
      <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
        <div class="reveal text-center">
          <p class="text-xs font-bold uppercase tracking-[0.18em] text-gold-deep">Clear answers</p>
          <h2 class="mt-3 font-display text-3xl font-extrabold text-navy sm:text-4xl">Frequently asked questions</h2>
        </div>

        <div class="mt-10 space-y-4">
          <details class="group rounded-2xl bg-white p-6 shadow-card transition duration-200 open:shadow-lift" open>
            <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-display text-lg font-extrabold text-navy">
              <span>Do you provide doorstep pickup and delivery across Gurgaon?</span>
              <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-cream text-navy transition duration-200 group-open:rotate-180 group-open:bg-gold">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
              </span>
            </summary>
            <p class="mt-4 text-base leading-relaxed text-mute">
              Yes! We provide free doorstep pickup and delivery across all major sectors, DLF phases, Golf Course Ext Rd, and New Gurgaon societies.
            </p>
          </details>

          <details class="group rounded-2xl bg-white p-6 shadow-card transition duration-200 open:shadow-lift">
            <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-display text-lg font-extrabold text-navy">
              <span>Are my clothes washed in an individual drum?</span>
              <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-cream text-navy transition duration-200 group-open:rotate-180 group-open:bg-gold">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
              </span>
            </summary>
            <p class="mt-4 text-base leading-relaxed text-mute">
              Always. We maintain strict hygiene protocols. Your clothes are never mixed with anyone else’s clothes. Each batch is color-sorted and washed in dedicated commercial drums.
            </p>
          </details>

          <details class="group rounded-2xl bg-white p-6 shadow-card transition duration-200 open:shadow-lift">
            <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-display text-lg font-extrabold text-navy">
              <span>What is the difference between steam pressing and dry cleaning?</span>
              <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-cream text-navy transition duration-200 group-open:rotate-180 group-open:bg-gold">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
              </span>
            </summary>
            <p class="mt-4 text-base leading-relaxed text-mute">
              Steam pressing removes wrinkles on vacuum steam tables with zero scorching or shine marks. Dry cleaning is a specialized solvent bath for delicate silks, woolens, suits, and ethnic wear that cannot withstand water washing.
            </p>
          </details>

          <details class="group rounded-2xl bg-white p-6 shadow-card transition duration-200 open:shadow-lift">
            <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-display text-lg font-extrabold text-navy">
              <span>How fast is the turnaround time?</span>
              <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-cream text-navy transition duration-200 group-open:rotate-180 group-open:bg-gold">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
              </span>
            </summary>
            <p class="mt-4 text-base leading-relaxed text-mute">
              Standard wash &amp; fold or steam pressing is returned within 24 to 48 hours. Dry cleaning and specialized carpet/shoe restoration takes 48 to 72 hours. Same-day express options are also available.
            </p>
          </details>
        </div>
      </div>
    </section>

    <!-- Fabrics in our care -->
    <section class="border-y border-navy/5 bg-white py-10" aria-label="Fabrics we care for">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <p class="text-center text-xs font-bold uppercase tracking-[0.2em] text-mute">In our care</p>
        <ul class="mt-5 flex flex-wrap items-center justify-center gap-x-8 gap-y-4 sm:gap-x-12">
          <li class="font-display text-lg font-extrabold tracking-wide text-navy/35 transition duration-300 hover:text-navy sm:text-xl">COTTON</li>
          <li class="font-display text-lg font-extrabold tracking-wide text-navy/35 transition duration-300 hover:text-navy sm:text-xl">SILK</li>
          <li class="font-display text-lg font-extrabold tracking-wide text-navy/35 transition duration-300 hover:text-navy sm:text-xl">WOOL</li>
          <li class="font-display text-lg font-extrabold tracking-wide text-navy/35 transition duration-300 hover:text-navy sm:text-xl">LINEN</li>
          <li class="font-display text-lg font-extrabold tracking-wide text-navy/35 transition duration-300 hover:text-navy sm:text-xl">DENIM</li>
          <li class="font-display text-lg font-extrabold tracking-wide text-navy/35 transition duration-300 hover:text-navy sm:text-xl">LEATHER</li>
          <li class="font-display text-lg font-extrabold tracking-wide text-navy/35 transition duration-300 hover:text-navy sm:text-xl">ETHNIC WEAR</li>
        </ul>
      </div>
    </section>
@endsection
