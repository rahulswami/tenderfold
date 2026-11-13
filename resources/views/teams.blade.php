@extends('layouts.app')

@section('title', !empty($page->metatitle) ? $page->metatitle : ('Our Team | ' . ($site['site_name'] ?? 'Tender Fold') . ' Gurgaon'))
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
      "name": "Our Team - Tenderfold Gurgaon",
      "description": "Meet the team behind Tenderfold, delivering premium laundry and dry cleaning services across Gurgaon.",
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
            <span class="text-navy">Teams</span>
          </p>
          <p class="mt-5 text-xs font-bold uppercase tracking-[0.18em] text-gold-deep">Fabric Care Specialists · Gurgaon</p>
          <h1 class="mt-3 font-display text-4xl font-extrabold leading-tight text-navy sm:text-5xl lg:text-[3.25rem]">
            The craftspeople, technicians &amp; care team behind every fold.
          </h1>
          <p class="mt-5 text-lg leading-relaxed text-mute">
            At Tenderfold, our people are at the heart of everything we do. Combining modern Italian machinery, skin-safe chemistry, and strict single-batch hygiene, our dedicated crew makes professional fabric care effortless for every home in Gurgaon.
          </p>
          
          <div class="mt-8 flex flex-col gap-3 sm:flex-row">
            <button type="button" data-open-book data-service="General Inquiry" class="inline-flex items-center justify-center rounded-full bg-gold px-7 py-3.5 font-display text-sm font-extrabold text-navy transition duration-300 hover:-translate-y-0.5 hover:bg-gold-deep shadow-md">
              Schedule a Pickup
            </button>
            <a href="{{ route('contact') }}" class="inline-flex items-center justify-center rounded-full border-2 border-navy/15 bg-white px-7 py-3.5 font-display text-sm font-extrabold text-navy transition duration-300 hover:-translate-y-0.5 hover:border-navy hover:bg-navy hover:text-white">
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
            <img src="{{ asset('assets/hero.jpg') }}" alt="Tenderfold Care Team at work" class="h-80 w-full object-cover sm:h-96 lg:h-[30rem] opacity-90 transition duration-500 hover:scale-105">
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

    <!-- 2. Story / Meet The Team Section -->
    <section class="bg-white py-16 sm:py-20">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-12 lg:grid-cols-12">
          <div class="reveal lg:col-span-6">
            <div class="relative overflow-hidden rounded-[2.5rem] shadow-lift">
              <img src="{{ asset('assets/hands.jpg') }}" alt="Meticulous fabric inspection by Tenderfold team" class="h-80 w-full object-cover sm:h-96 lg:h-[28rem]">
              <div class="absolute inset-0 bg-gradient-to-t from-navy/60 via-transparent to-transparent"></div>
              <div class="absolute bottom-6 left-6 right-6 rounded-2xl bg-white/95 p-5 shadow-card">
                <p class="text-xs font-bold uppercase tracking-wider text-gold-deep">Quality Assurance Routine</p>
                <p class="mt-1 font-display text-base font-extrabold text-navy">Every seam, button &amp; care label personally examined</p>
              </div>
            </div>
          </div>

          <div class="reveal lg:col-span-6">
            <span class="inline-block rounded-full bg-gold/20 px-3.5 py-1 text-xs font-extrabold uppercase tracking-wider text-navy">
              Who We Are
            </span>
            <h2 class="mt-3 font-display text-3xl font-extrabold leading-tight text-navy sm:text-4xl">
              Meet the Team Behind Tenderfold
            </h2>
            <div class="mt-5 space-y-4 text-base leading-relaxed text-mute">
              <p>
                At Tenderfold, our team is dedicated to delivering exceptional laundry and dry cleaning services in Gurgaon. We believe that every garment deserves expert care, attention to detail, and the right cleaning approach.
              </p>
              <p>
                Our team combines professional expertise, modern Italian machinery, and skin-safe detergents to ensure quality fabric care. From everyday laundry to delicate garment cleaning, we focus on maintaining the freshness, cleanliness, and quality of your clothes.
              </p>
              <p>
                With a commitment to customer satisfaction, timely service, and convenient doorstep pickup and delivery, our team works hard to make laundry care simple, transparent, and hassle-free.
              </p>
            </div>

            <div class="mt-8 grid grid-cols-2 gap-4">
              <div class="rounded-2xl bg-cream p-4 border border-navy/5">
                <div class="flex items-center gap-2">
                  <span class="flex h-6 w-6 items-center justify-center rounded-full bg-gold text-navy font-bold text-xs">✓</span>
                  <span class="font-display font-extrabold text-sm text-navy">Zero Mixing</span>
                </div>
                <p class="mt-1 text-xs text-mute">Dedicated wash load for each household</p>
              </div>
              <div class="rounded-2xl bg-cream p-4 border border-navy/5">
                <div class="flex items-center gap-2">
                  <span class="flex h-6 w-6 items-center justify-center rounded-full bg-gold text-navy font-bold text-xs">✓</span>
                  <span class="font-display font-extrabold text-sm text-navy">Gentle Chemistry</span>
                </div>
                <p class="mt-1 text-xs text-mute">Baby &amp; skin-friendly German eco solvents</p>
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

    <!-- 4. Our Commitment & Quality Benchmarks -->
    <section class="bg-white py-16 sm:py-20">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-3xl text-center">
          <span class="inline-block rounded-full bg-gold/20 px-3.5 py-1 text-xs font-extrabold uppercase tracking-wider text-navy">
            Standards We Live By
          </span>
          <h2 class="mt-3 font-display text-3xl font-extrabold text-navy sm:text-4xl">
            Our Commitment to Every Gurgaon Household
          </h2>
          <p class="mt-3 text-base text-mute">
            When you hand your garments to our team, you receive a standardized level of care backed by five core principles.
          </p>
        </div>

        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
          <div class="reveal rounded-3xl border border-navy/10 bg-cream/60 p-7 transition duration-300 hover:bg-cream hover:shadow-card">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gold text-navy font-black text-xl mb-5 shadow-sm">
              01
            </div>
            <h3 class="font-display text-xl font-extrabold text-navy">Professional Care Standards</h3>
            <p class="mt-2.5 text-sm leading-relaxed text-mute">
              Professional laundry and couture dry cleaning services engineered to preserve fabric weave, color depth, and garment silhouette over time.
            </p>
          </div>

          <div class="reveal rounded-3xl border border-navy/10 bg-cream/60 p-7 transition duration-300 hover:bg-cream hover:shadow-card" style="animation-delay:60ms">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gold text-navy font-black text-xl mb-5 shadow-sm">
              02
            </div>
            <h3 class="font-display text-xl font-extrabold text-navy">Fabric-Specific Expertise</h3>
            <p class="mt-2.5 text-sm leading-relaxed text-mute">
              Cotton, silk, linen, cashmere, wool, and synthetics receive individual attention. We read care labels and verify treatments with you before washing.
            </p>
          </div>

          <div class="reveal rounded-3xl border border-navy/10 bg-cream/60 p-7 transition duration-300 hover:bg-cream hover:shadow-card" style="animation-delay:120ms">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gold text-navy font-black text-xl mb-5 shadow-sm">
              03
            </div>
            <h3 class="font-display text-xl font-extrabold text-navy">Modern Italian Machinery</h3>
            <p class="mt-2.5 text-sm leading-relaxed text-mute">
              Precision Italian finishing equipment and skin-safe, hypoallergenic German detergents ensure crisp results that are safe for sensitive skin and babies.
            </p>
          </div>

          <div class="reveal rounded-3xl border border-navy/10 bg-cream/60 p-7 transition duration-300 hover:bg-cream hover:shadow-card" style="animation-delay:180ms">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gold text-navy font-black text-xl mb-5 shadow-sm">
              04
            </div>
            <h3 class="font-display text-xl font-extrabold text-navy">Strict Single-Batch Hygiene</h3>
            <p class="mt-2.5 text-sm leading-relaxed text-mute">
              Your garments are never mixed or washed together with clothing from other households. Full segregation is maintained from collection to delivery.
            </p>
          </div>

          <div class="reveal rounded-3xl border border-navy/10 bg-cream/60 p-7 transition duration-300 hover:bg-cream hover:shadow-card" style="animation-delay:240ms">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gold text-navy font-black text-xl mb-5 shadow-sm">
              05
            </div>
            <h3 class="font-display text-xl font-extrabold text-navy">Punctual Doorstep Pickup</h3>
            <p class="mt-2.5 text-sm leading-relaxed text-mute">
              Prompt, reliable pickup and delivery schedules across all Gurgaon sectors. Clear communication via phone and WhatsApp keeps you updated.
            </p>
          </div>

          <div class="reveal rounded-3xl border border-gold/30 bg-navy p-7 text-white shadow-lift" style="animation-delay:300ms">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gold text-navy font-black text-xl mb-5 shadow-sm">
              ★
            </div>
            <h3 class="font-display text-xl font-extrabold text-white">Our Promise to You</h3>
            <p class="mt-2.5 text-sm leading-relaxed text-white/80">
              At Tenderfold, our people are at the heart of everything we do. We strive to make professional fabric care reliable, convenient, and effortless for every customer.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- 5. Store Counters / Location Hubs in Gurgaon -->
    <section class="bg-cream py-16">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center mb-10">
          <p class="text-xs font-bold uppercase tracking-[0.18em] text-gold-deep">Our Physical Counters</p>
          <h2 class="mt-2 font-display text-3xl font-extrabold text-navy sm:text-4xl">Meet Our Team at the Counters</h2>
          <p class="mt-2 text-sm text-mute">Drop off your garments or say hello at our convenient retail counters in Gurgaon.</p>
        </div>
        <div class="grid gap-6 lg:grid-cols-2">
          <!-- Store 1 -->
          <a href="https://share.google/SXSjDFUalRginesFu" target="_blank" rel="noopener noreferrer" class="reveal group rounded-3xl bg-white p-7 shadow-card transition duration-300 hover:-translate-y-1 hover:shadow-lift">
            <div class="flex items-center justify-between">
              <span class="text-xs font-bold uppercase tracking-[0.16em] text-gold-deep">Sector 49 Counter</span>
              <span class="rounded-full bg-cream px-3 py-1 text-xs font-semibold text-navy">Open Daily</span>
            </div>
            <h3 class="mt-2 font-display text-2xl font-extrabold text-navy">Orchid Petals Shopping Plaza</h3>
            <p class="mt-3 text-sm leading-relaxed text-mute">Shop No. 19, 1st Floor, B Block Shopping Plaza, Orchid Petals, South City II, Sector 49, Gurgaon, Haryana - 122018</p>
            <div class="mt-4 flex flex-wrap items-center gap-4 text-xs font-bold text-navy">
              <span class="inline-flex items-center gap-1">📞 095824 66445</span>
              <span class="inline-flex items-center gap-1 group-hover:text-gold transition">Open in Maps →</span>
            </div>
          </a>

          <!-- Store 2 -->
          <a href="https://share.google/UZlhkLsEqN9ICLwTa" target="_blank" rel="noopener noreferrer" class="reveal group rounded-3xl bg-white p-7 shadow-card transition duration-300 hover:-translate-y-1 hover:shadow-lift" style="animation-delay:80ms">
            <div class="flex items-center justify-between">
              <span class="text-xs font-bold uppercase tracking-[0.16em] text-gold-deep">Sector 83 Counter</span>
              <span class="rounded-full bg-cream px-3 py-1 text-xs font-semibold text-navy">Open Daily</span>
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

    <!-- 6. Call to Action Banner -->
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
@endsection
