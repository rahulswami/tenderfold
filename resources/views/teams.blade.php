@extends('layouts.app')

@section('title', !empty($page->metatitle) ? $page->metatitle : (!empty($page->title) && strtolower($page->title) !== 'teams' ? ($page->title . ' | ' . ($site['site_name'] ?? 'Tender Fold') . ' Gurgaon') : ('Our Team | ' . ($site['site_name'] ?? 'Tender Fold') . ' Gurgaon')))
@section('meta_description', !empty($page->metadescription) ? $page->metadescription : 'Meet the team behind Tenderfold, your trusted laundry and dry cleaning service in Gurgaon. Experience professional fabric care, quality service and convenient doorstep pickup and delivery.')
@section('meta_keywords', !empty($page->metakeywords) ? $page->metakeywords : 'Tenderfold Team, Laundry Experts in Gurgaon, Professional Laundry Team, Fabric Care Experts, Doorstep Laundry Service Gurgaon')

@section('schema')
  @if(!empty($page->schema_markup))
    {!! $page->schema_markup !!}
  @else
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@type": "AboutPage",
      "name": "{{ !empty($page->title) ? $page->title : 'Our Team' }} - Tenderfold Gurgaon",
      "description": "{{ !empty($page->metadescription) ? $page->metadescription : 'Meet the team behind Tenderfold, delivering premium laundry and dry cleaning services across Gurgaon.' }}",
      "publisher": {
        "@type": "Organization",
        "name": "{{ $site['site_name'] ?? 'Tender Fold' }}",
        "url": "{{ url('/') }}"
      }
    }
    </script>
  @endif
@endsection

@section('content')
    <!-- 1. Hero Section -->
    <section class="bg-cream">
      <div class="mx-auto grid max-w-7xl items-center gap-10 px-4 py-14 sm:px-6 lg:grid-cols-12 lg:px-8 lg:py-20">
        <div class="reveal lg:col-span-7">
          <p class="text-sm font-semibold text-mute">
            <a href="{{ route('home') }}" class="transition hover:text-navy">Home</a> 
            <span aria-hidden="true">/</span> 
            <span class="text-navy">{{ $page->title ?? 'Teams' }}</span>
          </p>
          <p class="mt-5 text-xs font-bold uppercase tracking-[0.18em] text-gold-deep">Fabric Care Specialists · Gurgaon</p>
          <h1 class="mt-3 font-display text-4xl font-extrabold leading-tight text-navy sm:text-5xl lg:text-[3.25rem]">
            {{ !empty($page->title) && strtolower($page->title) !== 'teams' ? $page->title : 'The craftspeople, technicians & care team behind every fold.' }}
          </h1>
          <p class="mt-5 text-lg leading-relaxed text-mute">
            {{ !empty($page->metadescription) ? $page->metadescription : 'At Tenderfold, our people are at the heart of everything we do. Combining modern Italian machinery, skin-safe chemistry, and strict single-batch hygiene, our dedicated crew makes professional fabric care effortless for every home in Gurgaon.' }}
          </p>
          
          <div class="mt-8 flex flex-col gap-3 sm:flex-row">
            <button type="button" data-open-book data-service="General Inquiry" class="inline-flex items-center justify-center rounded-full bg-gold px-7 py-3.5 font-display text-sm font-extrabold text-navy transition duration-300 hover:-translate-y-0.5 hover:bg-gold-deep shadow-md">
              Schedule a Pickup
            </button>
            <a href="#counters" class="inline-flex items-center justify-center rounded-full border-2 border-navy/15 bg-white px-7 py-3.5 font-display text-sm font-extrabold text-navy transition duration-300 hover:-translate-y-0.5 hover:border-navy hover:bg-navy hover:text-white">
              Visit Store Counters
            </a>
          </div>

          <!-- Trust Badges -->
          <div class="mt-10 grid grid-cols-3 gap-4 border-t border-navy/10 pt-6">
            <div>
              <p class="font-display text-2xl font-extrabold text-navy sm:text-3xl">100%</p>
              <p class="mt-1 text-xs font-semibold text-mute">Individual Batch Wash</p>
            </div>
            <div>
              <p class="font-display text-2xl font-extrabold text-navy sm:text-3xl">Italian</p>
              <p class="mt-1 text-xs font-semibold text-mute">Steam Press Tables</p>
            </div>
            <div>
              <p class="font-display text-2xl font-extrabold text-navy sm:text-3xl">Sec 49 &amp; 83</p>
              <p class="mt-1 text-xs font-semibold text-mute">Gurgaon Store Hubs</p>
            </div>
          </div>
        </div>

        <div class="reveal lg:col-span-5" style="animation-delay:80ms">
          <div class="relative overflow-hidden rounded-[2.5rem] bg-navy shadow-lift border border-navy/10">
            @php
              $heroImageSrc = !empty($page->image) ? site_image($page->image, 'cms') : asset('assets/hero.jpg');
            @endphp
            <img src="{{ $heroImageSrc }}" alt="{{ $page->title ?? 'Tenderfold Care Team at work' }}" class="h-80 w-full object-cover sm:h-96 lg:h-[30rem] opacity-90 transition duration-500 hover:scale-105">
            <div class="absolute inset-0 bg-gradient-to-t from-navy-dark/90 via-navy-dark/30 to-transparent"></div>
            <div class="absolute bottom-6 left-6 right-6 rounded-2xl bg-white/95 p-5 shadow-card backdrop-blur-md">
              <div class="flex items-center gap-3.5">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-gold text-navy font-extrabold">
                  <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <div>
                  <h2 class="font-display text-base font-extrabold text-navy">Tenderfold Plant &amp; Store Crew</h2>
                  <p class="text-xs font-semibold text-mute">Serving households across Gurgaon daily</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- 2. Dynamic Admin CMS Content Section -->
    <section class="bg-white py-16 sm:py-20 border-b border-navy/5">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-start gap-12 lg:grid-cols-12">
          
          <!-- Left Column: Visual & Trust Pillars (5 cols) -->
          <div class="reveal lg:col-span-5 space-y-6 lg:sticky lg:top-24">
            <div class="relative overflow-hidden rounded-[2.5rem] shadow-lift">
              <img src="{{ asset('assets/hands.jpg') }}" alt="Meticulous fabric inspection by Tenderfold team" class="h-80 w-full object-cover sm:h-96 lg:h-[26rem] transition duration-500 hover:scale-105">
              <div class="absolute inset-0 bg-gradient-to-t from-navy/70 via-transparent to-transparent"></div>
              <div class="absolute bottom-5 left-5 right-5 rounded-2xl bg-white/95 p-4 shadow-card backdrop-blur-sm">
                <p class="text-xs font-bold uppercase tracking-wider text-gold-deep">Quality Assurance Routine</p>
                <p class="mt-1 font-display text-sm font-extrabold text-navy">Every seam, button &amp; care label personally examined</p>
              </div>
            </div>

            <!-- Trust Highlights Card -->
            <div class="rounded-3xl bg-cream/70 p-6 border border-navy/5 shadow-sm">
              <p class="text-xs font-bold uppercase tracking-[0.16em] text-gold-deep mb-3.5">Our Care Guarantee</p>
              <div class="space-y-3.5">
                <div class="flex items-start gap-3">
                  <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-gold text-navy font-bold text-xs mt-0.5 shadow-sm">✓</span>
                  <div>
                    <p class="font-display font-extrabold text-sm text-navy">Zero Mixing Policy</p>
                    <p class="text-xs text-mute">Your clothes are always washed segregated in dedicated drums.</p>
                  </div>
                </div>
                <div class="flex items-start gap-3">
                  <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-gold text-navy font-bold text-xs mt-0.5 shadow-sm">✓</span>
                  <div>
                    <p class="font-display font-extrabold text-sm text-navy">Skin-Friendly Chemistry</p>
                    <p class="text-xs text-mute">Gentle German eco detergents safe for infant and delicate wear.</p>
                  </div>
                </div>
                <div class="flex items-start gap-3">
                  <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-gold text-navy font-bold text-xs mt-0.5 shadow-sm">✓</span>
                  <div>
                    <p class="font-display font-extrabold text-sm text-navy">Free Doorstep Pickup</p>
                    <p class="text-xs text-mute">Scheduled collection slots across all major Gurgaon sectors.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Right Column: Live Managed CMS Content (7 cols) -->
          <div class="reveal lg:col-span-7">
            <div class="rounded-3xl bg-cream/30 p-6 sm:p-10 border border-navy/5 shadow-card">
              <div class="flex flex-wrap items-center justify-between gap-2 mb-6 pb-4 border-b border-navy/10">
                <span class="inline-block rounded-full bg-gold/20 px-3.5 py-1 text-xs font-extrabold uppercase tracking-wider text-navy">
                  Who We Are · Admin Managed
                </span>
                @if(!empty($page->updated_at))
                  <span class="text-xs font-semibold text-mute">Last updated: {{ $page->updated_at->format('M d, Y') }}</span>
                @endif
              </div>

              <!-- DYNAMIC CONTENT: Driven directly from Admin CMS Control Panel -->
              <div class="cms-teams-prose">
                {!! !empty($page->content) ? $page->content : (!empty($page->description) ? $page->description : '<p class="text-mute">Welcome to Tenderfold. Meet the craftspeople and fabric care specialists dedicated to your wardrobe.</p>') !!}
              </div>
            </div>
          </div>

        </div>
      </div>
    </section>

    <!-- 3. Specialized Departments / Pillars Grid -->
    <section class="bg-cream py-16 sm:py-24">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center">
          <p class="text-xs font-bold uppercase tracking-[0.18em] text-gold-deep">Our Specialized Departments</p>
          <h2 class="mt-2 font-display text-3xl font-extrabold text-navy sm:text-4xl">
            The Specialists Caring for Your Wardrobe
          </h2>
          <p class="mt-3 text-base text-mute">
            From stain removal to steam shaping and doorstep logistics, each stage of your garment's journey is supervised by trained technicians.
          </p>
        </div>

        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
          <!-- Card 1 -->
          <article class="reveal group flex flex-col overflow-hidden rounded-3xl bg-white shadow-card transition duration-300 hover:-translate-y-1.5 hover:shadow-lift">
            <div class="h-48 overflow-hidden bg-navy/5">
              <img src="{{ asset('assets/suit.jpg') }}" alt="Master Dry Cleaners" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">
            </div>
            <div class="flex flex-1 flex-col p-6">
              <span class="text-xs font-bold uppercase tracking-wider text-gold-deep">Couture &amp; Delicate Fabric Care</span>
              <h3 class="mt-1.5 font-display text-xl font-extrabold text-navy group-hover:text-gold-deep transition">Master Dry Cleaners &amp; Spotters</h3>
              <p class="mt-2.5 flex-1 text-sm leading-relaxed text-mute">
                Trained in German hydrocarbon solvent systems and ultrasonic spotting tables. They carefully evaluate silk sarees, designer suits, and fine woolens to safely remove stubborn marks without fiber abrasion.
              </p>
              <div class="mt-4 border-t border-navy/5 pt-3 text-xs font-semibold text-navy/80">
                <span>✦ Spot testing · Zero harsh perc · Color-safe</span>
              </div>
            </div>
          </article>

          <!-- Card 2 -->
          <article class="reveal group flex flex-col overflow-hidden rounded-3xl bg-white shadow-card transition duration-300 hover:-translate-y-1.5 hover:shadow-lift" style="animation-delay:60ms">
            <div class="h-48 overflow-hidden bg-navy/5">
              <img src="{{ asset('assets/shirts.jpg') }}" alt="Steam Pressing Technicians" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">
            </div>
            <div class="flex flex-1 flex-col p-6">
              <span class="text-xs font-bold uppercase tracking-wider text-gold-deep">Steam Finishing &amp; Shaping</span>
              <h3 class="mt-1.5 font-display text-xl font-extrabold text-navy group-hover:text-gold-deep transition">Italian Vacuum Press Masters</h3>
              <p class="mt-2.5 flex-1 text-sm leading-relaxed text-mute">
                Operating ergonomic Italian vacuum pressing tables that steam from beneath. Garments are cleanly relaxed and contoured without shiny iron glaze, crushed buttons, or flattened textures.
              </p>
              <div class="mt-4 border-t border-navy/5 pt-3 text-xs font-semibold text-navy/80">
                <span>✦ Vacuum cooling · Anti-shine pads · Hanger finish</span>
              </div>
            </div>
          </article>

          <!-- Card 3 -->
          <article class="reveal group flex flex-col overflow-hidden rounded-3xl bg-white shadow-card transition duration-300 hover:-translate-y-1.5 hover:shadow-lift" style="animation-delay:120ms">
            <div class="h-48 overflow-hidden bg-navy/5">
              <img src="{{ asset('assets/shoe.jpg') }}" alt="Shoe & Sneaker Restoration" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">
            </div>
            <div class="flex flex-1 flex-col p-6">
              <span class="text-xs font-bold uppercase tracking-wider text-gold-deep">Artisanal Sneaker &amp; Shoe Spa</span>
              <h3 class="mt-1.5 font-display text-xl font-extrabold text-navy group-hover:text-gold-deep transition">Footwear Spa Craftsmen</h3>
              <p class="mt-2.5 flex-1 text-sm leading-relaxed text-mute">
                Specialists in suede re-napping, mesh stain extraction, leather hydration, sole de-yellowing, and internal UV ozone sterilization to eliminate 99.9% of bacteria and odors.
              </p>
              <div class="mt-4 border-t border-navy/5 pt-3 text-xs font-semibold text-navy/80">
                <span>✦ Hand brushing · Sole un-yellowing · UV ozone</span>
              </div>
            </div>
          </article>

          <!-- Card 4 -->
          <article class="reveal group flex flex-col overflow-hidden rounded-3xl bg-white shadow-card transition duration-300 hover:-translate-y-1.5 hover:shadow-lift" style="animation-delay:180ms">
            <div class="h-48 overflow-hidden bg-navy/5">
              <img src="{{ asset('assets/hands.jpg') }}" alt="Batch Laundry Specialists" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">
            </div>
            <div class="flex flex-1 flex-col p-6">
              <span class="text-xs font-bold uppercase tracking-wider text-gold-deep">Hygiene &amp; Washing Care</span>
              <h3 class="mt-1.5 font-display text-xl font-extrabold text-navy group-hover:text-gold-deep transition">Batch Washing Technicians</h3>
              <p class="mt-2.5 flex-1 text-sm leading-relaxed text-mute">
                Strict adherents to our single-household washing protocol. They calibrate water temperatures and detergent dosing to preserve cotton weaves, activewear elasticity, and vibrant colors.
              </p>
              <div class="mt-4 border-t border-navy/5 pt-3 text-xs font-semibold text-navy/80">
                <span>✦ 100% segregated drums · Lint-free drying</span>
              </div>
            </div>
          </article>

          <!-- Card 5 -->
          <article class="reveal group flex flex-col overflow-hidden rounded-3xl bg-white shadow-card transition duration-300 hover:-translate-y-1.5 hover:shadow-lift" style="animation-delay:240ms">
            <div class="h-48 overflow-hidden bg-navy/5">
              <img src="{{ asset('assets/carpet.jpg') }}" alt="Home Furnishing Specialists" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">
            </div>
            <div class="flex flex-1 flex-col p-6">
              <span class="text-xs font-bold uppercase tracking-wider text-gold-deep">Curtain &amp; Carpet Renewal</span>
              <h3 class="mt-1.5 font-display text-xl font-extrabold text-navy group-hover:text-gold-deep transition">Home Textile Care Crew</h3>
              <p class="mt-2.5 flex-1 text-sm leading-relaxed text-mute">
                Specialized in deep-fiber rug shampooing, heavy drape dry cleaning, dust-mite extraction, and antimicrobial thermal drying, returning bulky home textiles clean and fresh.
              </p>
              <div class="mt-4 border-t border-navy/5 pt-3 text-xs font-semibold text-navy/80">
                <span>✦ Dust extraction · Allergen sanitization · On-site quotes</span>
              </div>
            </div>
          </article>

          <!-- Card 6 -->
          <article class="reveal group flex flex-col overflow-hidden rounded-3xl bg-white shadow-card transition duration-300 hover:-translate-y-1.5 hover:shadow-lift" style="animation-delay:300ms">
            <div class="h-48 overflow-hidden bg-navy/5">
              <img src="{{ asset('assets/office.jpg') }}" alt="Doorstep Logistics and Customer Desk" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">
            </div>
            <div class="flex flex-1 flex-col p-6">
              <span class="text-xs font-bold uppercase tracking-wider text-gold-deep">Logistics &amp; Customer Support</span>
              <h3 class="mt-1.5 font-display text-xl font-extrabold text-navy group-hover:text-gold-deep transition">Pickup Drivers &amp; QA Desk</h3>
              <p class="mt-2.5 flex-1 text-sm leading-relaxed text-mute">
                Courteous pickup drivers cover all major Gurgaon sectors on scheduled routes. Every order passes final inspection before packaging in breathable garment bags on contoured hangers.
              </p>
              <div class="mt-4 border-t border-navy/5 pt-3 text-xs font-semibold text-navy/80">
                <span>✦ Free doorstep service · WhatsApp updates · Punctual</span>
              </div>
            </div>
          </article>
        </div>
      </div>
    </section>

    <!-- 4. Store Counters / Location Hubs in Gurgaon -->
    <section id="counters" class="bg-white py-16 border-t border-navy/5">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center mb-10">
          <p class="text-xs font-bold uppercase tracking-[0.18em] text-gold-deep">Our Physical Counters</p>
          <h2 class="mt-2 font-display text-3xl font-extrabold text-navy sm:text-4xl">Meet Our Team at the Counters</h2>
          <p class="mt-2 text-sm text-mute">Drop off your garments or say hello at our convenient retail counters in Gurgaon.</p>
        </div>
        <div class="grid gap-6 lg:grid-cols-2">
          <!-- Store 1 -->
          <a href="https://share.google/SXSjDFUalRginesFu" target="_blank" rel="noopener noreferrer" class="reveal group rounded-3xl bg-cream/50 p-7 border border-navy/5 shadow-card transition duration-300 hover:-translate-y-1 hover:shadow-lift">
            <div class="flex items-center justify-between">
              <span class="text-xs font-bold uppercase tracking-[0.16em] text-gold-deep">Sector 49 Counter</span>
              <span class="rounded-full bg-white px-3 py-1 text-xs font-bold text-navy shadow-sm">Open Daily</span>
            </div>
            <h3 class="mt-2 font-display text-2xl font-extrabold text-navy">Orchid Petals Shopping Plaza</h3>
            <p class="mt-3 text-sm leading-relaxed text-mute">Shop No. 19, 1st Floor, B Block Shopping Plaza, Orchid Petals, South City II, Sector 49, Gurgaon, Haryana - 122018</p>
            <div class="mt-4 flex flex-wrap items-center gap-4 text-xs font-bold text-navy">
              <span class="inline-flex items-center gap-1">📞 095824 66445</span>
              <span class="inline-flex items-center gap-1 group-hover:text-gold transition">Open in Maps →</span>
            </div>
          </a>

          <!-- Store 2 -->
          <a href="https://share.google/UZlhkLsEqN9ICLwTa" target="_blank" rel="noopener noreferrer" class="reveal group rounded-3xl bg-cream/50 p-7 border border-navy/5 shadow-card transition duration-300 hover:-translate-y-1 hover:shadow-lift" style="animation-delay:80ms">
            <div class="flex items-center justify-between">
              <span class="text-xs font-bold uppercase tracking-[0.16em] text-gold-deep">Sector 83 Counter</span>
              <span class="rounded-full bg-white px-3 py-1 text-xs font-bold text-navy shadow-sm">Open Daily</span>
            </div>
            <h3 class="mt-2 font-display text-2xl font-extrabold text-navy">Sapphire 83 Mall</h3>
            <p class="mt-3 text-sm leading-relaxed text-mute">F-193, First Floor, Sapphire 83 Mall, Sector 83, Gurgaon, Haryana - 122004</p>
            <div class="mt-4 flex flex-wrap items-center gap-4 text-xs font-bold text-navy">
              <span class="inline-flex items-center gap-1">📞 098996 22294</span>
              <span class="inline-flex items-center gap-1 group-hover:text-gold transition">Open in Maps →</span>
            </div>
          </a>
        </div>
      </div>
    </section>

    <!-- 5. Call to Action Banner -->
    <section class="bg-navy">
      <div class="mx-auto flex max-w-7xl flex-col items-start justify-between gap-6 px-4 py-12 sm:px-6 md:flex-row md:items-center lg:px-8">
        <div class="max-w-xl">
          <p class="text-xs font-bold uppercase tracking-[0.18em] text-gold">Free pickup &amp; delivery across Gurgaon</p>
          <h2 class="mt-2 font-display text-3xl font-extrabold text-white">Experience the care of Gurgaon's finest laundry team.</h2>
          <p class="mt-2 text-sm leading-relaxed text-white/75">From daily wash &amp; fold to bridal lehengas and shoe spa, our team is ready to collect from your doorstep.</p>
        </div>
        <div class="flex w-full flex-col gap-3 sm:w-auto sm:flex-row">
          <button type="button" data-open-book data-service="Mixed pickup" class="inline-flex items-center justify-center rounded-full bg-gold px-6 py-3.5 font-display text-sm font-extrabold text-navy transition duration-300 hover:-translate-y-0.5 hover:bg-gold-deep shadow-md">Schedule a Pickup</button>
          <a href="tel:{{ preg_replace('/[^0-9+]/', '', $site['phone'] ?? '+919582466445') }}" class="inline-flex items-center justify-center rounded-full border-2 border-white/25 px-6 py-3.5 font-display text-sm font-extrabold text-white transition duration-300 hover:-translate-y-0.5 hover:bg-white hover:text-navy">Call {{ $site['phone_display'] ?? $site['phone'] ?? '+91 95824 66445' }}</a>
        </div>
      </div>
    </section>

    <!-- Custom Styling for Admin TinyMCE Rendered HTML Content -->
    <style>
      .cms-teams-prose {
        font-family: inherit;
        color: #4B5563;
        line-height: 1.8;
      }
      .cms-teams-prose h1 {
        font-family: 'Nunito', ui-sans-serif, system-ui, sans-serif;
        font-size: 1.875rem;
        line-height: 2.25rem;
        font-weight: 800;
        color: #143572;
        margin-top: 0.5rem;
        margin-bottom: 1.25rem;
      }
      @media (min-width: 640px) {
        .cms-teams-prose h1 {
          font-size: 2.25rem;
          line-height: 2.5rem;
        }
      }
      .cms-teams-prose h2 {
        font-family: 'Nunito', ui-sans-serif, system-ui, sans-serif;
        font-size: 1.5rem;
        line-height: 2rem;
        font-weight: 800;
        color: #143572;
        margin-top: 2rem;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid rgba(245, 180, 0, 0.4);
        display: inline-block;
      }
      @media (min-width: 640px) {
        .cms-teams-prose h2 {
          font-size: 1.75rem;
        }
      }
      .cms-teams-prose h3 {
        font-family: 'Nunito', ui-sans-serif, system-ui, sans-serif;
        font-size: 1.25rem;
        line-height: 1.75rem;
        font-weight: 800;
        color: #143572;
        margin-top: 1.5rem;
        margin-bottom: 0.75rem;
      }
      .cms-teams-prose p {
        font-size: 1.025rem;
        line-height: 1.8;
        color: #5C6F84;
        margin-bottom: 1.25rem;
      }
      .cms-teams-prose ul {
        list-style: none !important;
        padding-left: 0 !important;
        margin-top: 1.25rem !important;
        margin-bottom: 1.5rem !important;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
      }
      .cms-teams-prose ul li {
        position: relative;
        padding: 0.875rem 1.25rem 0.875rem 2.85rem !important;
        font-size: 0.975rem;
        line-height: 1.55;
        color: #143572;
        font-weight: 600;
        background: #ffffff;
        border-radius: 1rem;
        border: 1px solid rgba(20, 53, 114, 0.08);
        box-shadow: 0 1px 3px rgba(20, 53, 114, 0.04);
        transition: all 0.2s ease;
      }
      .cms-teams-prose ul li:hover {
        background: #F4F7FB;
        border-color: rgba(245, 180, 0, 0.5);
        transform: translateX(3px);
      }
      .cms-teams-prose ul li::before {
        content: "✓";
        position: absolute;
        left: 0.85rem;
        top: 50%;
        transform: translateY(-50%);
        width: 1.35rem;
        height: 1.35rem;
        border-radius: 9999px;
        background-color: #F5B400;
        color: #143572;
        font-weight: 800;
        font-size: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        line-height: 1.35rem;
        text-align: center;
      }
      .cms-teams-prose ol {
        counter-reset: cms-counter;
        list-style: none !important;
        padding-left: 0 !important;
        margin-top: 1.25rem !important;
        margin-bottom: 1.5rem !important;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
      }
      .cms-teams-prose ol li {
        counter-increment: cms-counter;
        position: relative;
        padding: 0.875rem 1.25rem 0.875rem 2.85rem !important;
        font-size: 0.975rem;
        line-height: 1.55;
        color: #143572;
        font-weight: 600;
        background: #ffffff;
        border-radius: 1rem;
        border: 1px solid rgba(20, 53, 114, 0.08);
      }
      .cms-teams-prose ol li::before {
        content: counter(cms-counter);
        position: absolute;
        left: 0.85rem;
        top: 50%;
        transform: translateY(-50%);
        width: 1.35rem;
        height: 1.35rem;
        border-radius: 9999px;
        background-color: #F5B400;
        color: #143572;
        font-weight: 800;
        font-size: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        line-height: 1.35rem;
        text-align: center;
      }
      .cms-teams-prose strong,
      .cms-teams-prose b {
        color: #143572;
        font-weight: 800;
      }
      .cms-teams-prose a {
        color: #143572;
        font-weight: 700;
        text-decoration: underline;
        text-decoration-color: #F5B400;
        text-underline-offset: 3px;
        transition: color 0.2s;
      }
      .cms-teams-prose a:hover {
        color: #b48500;
      }
      .cms-teams-prose blockquote {
        border-left: 4px solid #F5B400;
        background: #ffffff;
        padding: 1rem 1.5rem;
        border-radius: 0 1rem 1rem 0;
        font-style: italic;
        color: #143572;
        margin: 1.5rem 0;
        border: 1px solid rgba(20, 53, 114, 0.08);
        border-left: 4px solid #F5B400;
      }
      .cms-teams-prose img {
        border-radius: 1.5rem;
        max-width: 100%;
        height: auto;
        box-shadow: 0 10px 25px -5px rgba(20, 53, 114, 0.1);
        margin: 1.5rem 0;
      }
    </style>
@endsection
