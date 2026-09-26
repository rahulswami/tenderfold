@extends('layouts.app')

@section('title', ($faqPage->metatitle ?? 'Frequently Asked Questions | Tenderfold Laundry Gurugram'))
@section('meta_description', ($faqPage->metadescription ?? 'Common questions about laundry per kg rates, dry cleaning turnaround times, free pickup & delivery, and garment care at Tenderfold Gurugram.'))
@section('meta_keywords', ($faqPage->metakeywords ?? 'laundry faq gurugram, dry cleaning pricing queries, tenderfold pickup questions'))

@section('content')
<main id="main">
  <!-- FAQ Hero -->
  <section class="bg-cream">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8 lg:py-20">
      <div class="reveal max-w-2xl">
        <p class="text-sm font-semibold text-mute">
          <a href="{{ route('home') }}" class="transition hover:text-navy">Home</a> 
          <span aria-hidden="true">/</span> 
          <span class="text-navy">FAQ</span>
        </p>
        <p class="mt-5 text-xs font-bold uppercase tracking-[0.18em] text-gold-deep">Clear answers</p>
        <h1 class="mt-3 font-display text-4xl font-extrabold text-navy sm:text-5xl">Frequently Asked Questions</h1>
        <p class="mt-4 text-lg text-mute">Everything you need to know about pricing, pickup timings, fabric handling, and store operations.</p>
      </div>

      <!-- FAQ List -->
      <div class="mt-12 max-w-4xl space-y-4">
        <!-- Default Core Questions -->
        <details class="reveal group rounded-3xl bg-white p-6 shadow-card open:ring-2 open:ring-gold/30">
          <summary class="flex cursor-pointer list-none items-center justify-between font-display text-lg font-bold text-navy">
            <span>How does free doorstep pickup & delivery work?</span>
            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-cream text-navy transition group-open:rotate-180">
              <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </span>
          </summary>
          <div class="mt-4 text-sm leading-relaxed text-mute border-t border-navy/10 pt-4">
            Simply schedule a pickup on our website or send a WhatsApp message to +91 95824 66445. We confirm your preferred pickup slot, our rider collects the garments from your doorstep, and returns them fresh and crisply folded or on hangers.
          </div>
        </details>

        <details class="reveal group rounded-3xl bg-white p-6 shadow-card open:ring-2 open:ring-gold/30">
          <summary class="flex cursor-pointer list-none items-center justify-between font-display text-lg font-bold text-navy">
            <span>What is the difference between wash & fold and wash & iron?</span>
            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-cream text-navy transition group-open:rotate-180">
              <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </span>
          </summary>
          <div class="mt-4 text-sm leading-relaxed text-mute border-t border-navy/10 pt-4">
            Wash & Fold (₹80/kg) is ideal for everyday daily wear, t-shirts, linens, and bedsheets that are washed in soft water, tumble dried, and folded neatly. Wash & Steam Iron (₹115/kg) includes professional steam pressing and hanger/fold packaging suitable for formal shirts, trousers, and office wear.
          </div>
        </details>

        <details class="reveal group rounded-3xl bg-white p-6 shadow-card open:ring-2 open:ring-gold/30">
          <summary class="flex cursor-pointer list-none items-center justify-between font-display text-lg font-bold text-navy">
            <span>Which items should be dry cleaned instead of laundered?</span>
            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-cream text-navy transition group-open:rotate-180">
              <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </span>
          </summary>
          <div class="mt-4 text-sm leading-relaxed text-mute border-t border-navy/10 pt-4">
            Silk sarees, designer lehengas, structured suits, blazers, woolens, jackets, and delicate embroidered garments should always be dry cleaned with eco-safe solvents to maintain fabric integrity, color vibrancy, and structural drape.
          </div>
        </details>

        <details class="reveal group rounded-3xl bg-white p-6 shadow-card open:ring-2 open:ring-gold/30">
          <summary class="flex cursor-pointer list-none items-center justify-between font-display text-lg font-bold text-navy">
            <span>Where are your physical store counters located?</span>
            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-cream text-navy transition group-open:rotate-180">
              <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </span>
          </summary>
          <div class="mt-4 text-sm leading-relaxed text-mute border-t border-navy/10 pt-4">
            We have two physical counters in Gurugram: 
            <br>1. <strong>Sector 49:</strong> Shop No. 19, 1st Floor, B Block Shopping Plaza, Orchid Petals, South City II.
            <br>2. <strong>Sector 83:</strong> F-193, First Floor, Sapphire 83 Mall.
          </div>
        </details>

        <!-- Service-Specific Dynamic FAQs from CMS (if any) -->
        @if(isset($services))
          @foreach($services as $service)
            @if(!empty($service->faqs) && is_array($service->faqs))
              @foreach($service->faqs as $faq)
                <details class="reveal group rounded-3xl bg-white p-6 shadow-card open:ring-2 open:ring-gold/30">
                  <summary class="flex cursor-pointer list-none items-center justify-between font-display text-lg font-bold text-navy">
                    <span>{{ $faq['question'] ?? '' }}</span>
                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-cream text-navy transition group-open:rotate-180">
                      <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </span>
                  </summary>
                  <div class="mt-4 text-sm leading-relaxed text-mute border-t border-navy/10 pt-4">
                    {{ $faq['answer'] ?? '' }}
                  </div>
                </details>
              @endforeach
            @endif
          @endforeach
        @endif
      </div>
    </div>
  </section>

  <!-- CTA Banner -->
  <section class="bg-navy">
    <div class="mx-auto flex max-w-7xl flex-col items-start justify-between gap-6 px-4 py-12 sm:px-6 md:flex-row md:items-center lg:px-8">
      <div class="max-w-xl">
        <p class="text-xs font-bold uppercase tracking-[0.18em] text-gold">Still have a question?</p>
        <h2 class="mt-2 font-display text-3xl font-extrabold text-white">Ask our counter desk directly on WhatsApp.</h2>
        <p class="mt-2 text-sm leading-relaxed text-white/75">We are available from 9 AM to 9 PM every day to assist you with queries and pickups.</p>
      </div>
      <div class="flex w-full flex-col gap-3 sm:w-auto sm:flex-row">
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $site['whatsapp_number'] ?? '919582466445') }}" target="_blank" rel="noopener" class="inline-flex items-center justify-center rounded-full bg-gold px-6 py-3.5 font-display text-sm font-extrabold text-navy transition duration-300 hover:-translate-y-0.5 hover:bg-gold-deep">Chat on WhatsApp</a>
        <a href="tel:{{ preg_replace('/[^0-9\+]/', '', $site['phone'] ?? '+919582466445') }}" class="inline-flex items-center justify-center rounded-full border-2 border-white/25 px-6 py-3.5 font-display text-sm font-extrabold text-white transition duration-300 hover:-translate-y-0.5 hover:bg-white hover:text-navy">Call the store</a>
      </div>
    </div>
  </section>
</main>
@endsection
