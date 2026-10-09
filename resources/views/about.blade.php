@extends('layouts.app')

@section('title', 'About Us | ' . ($site['site_name'] ?? 'Tender Fold') . ' Gurgaon')
@section('meta_description', 'Tender Fold is a premium laundry and dry cleaner with counters at Sector 49 and Sector 83, Gurgaon. Free doorstep pickup and delivery.')

@section('content')
    <section class="bg-cream">
      <div class="mx-auto grid max-w-7xl items-center gap-10 px-4 py-14 sm:px-6 lg:grid-cols-2 lg:px-8 lg:py-20">
        <div class="reveal">
          <p class="text-sm font-semibold text-mute"><a href="{{ route('home') }}" class="transition hover:text-navy">Home</a> <span aria-hidden="true">/</span> <span class="text-navy">About</span></p>
          <p class="mt-5 text-xs font-bold uppercase tracking-[0.18em] text-gold-deep">Gurgaon · two counters</p>
          <h1 class="mt-3 font-display text-4xl font-extrabold leading-tight text-navy sm:text-5xl">Clothes looked after, then folded as if they were staying.</h1>
          <p class="mt-4 text-lg leading-relaxed text-mute">Tender Fold is a laundry and dry cleaner with counters at Orchid Petals, Sector 49, and Sapphire 83 Mall, Sector 83. Pickup and delivery cover Gurgaon.</p>
          <div class="mt-8 flex flex-col gap-3 sm:flex-row">
            <button type="button" data-open-book class="inline-flex items-center justify-center rounded-full bg-gold px-6 py-3.5 font-display text-sm font-extrabold text-navy transition duration-300 hover:-translate-y-0.5 hover:bg-gold-deep shadow-md">Schedule a Pickup</button>
            <a href="{{ route('contact') }}" class="inline-flex items-center justify-center rounded-full border-2 border-navy/15 bg-white px-6 py-3.5 font-display text-sm font-extrabold text-navy transition duration-300 hover:-translate-y-0.5 hover:border-navy hover:bg-navy hover:text-white">Find the counters</a>
          </div>
        </div>
        <div class="reveal overflow-hidden rounded-[2rem] shadow-lift" style="animation-delay:80ms">
          <img src="{{ asset('assets/hero.jpg') }}" alt="Freshly folded laundry stacked for delivery" class="h-72 w-full object-cover sm:h-96 lg:h-[28rem]">
        </div>
      </div>
    </section>

    <section class="bg-white py-16 md:py-20">
      <div class="mx-auto grid max-w-7xl gap-6 px-4 sm:px-6 md:grid-cols-3 lg:px-8">
        <article class="reveal rounded-3xl bg-cream p-6">
          <h2 class="font-display text-xl font-extrabold text-navy">One bag, several jobs</h2>
          <p class="mt-3 text-sm leading-relaxed text-mute">Wash &amp; fold, steam ironing, dry cleaning, shoes, curtains and a carpet quote can share a collection. You do not book six separate errands.</p>
        </article>
        <article class="reveal rounded-3xl bg-cream p-6" style="animation-delay:80ms">
          <h2 class="font-display text-xl font-extrabold text-navy">Cloth decides the process</h2>
          <p class="mt-3 text-sm leading-relaxed text-mute">Cotton can be washed. Silk, structure and embellishment usually cannot. If a piece is delicate, we take the gentler path and confirm with you.</p>
        </article>
        <article class="reveal rounded-3xl bg-cream p-6" style="animation-delay:160ms">
          <h2 class="font-display text-xl font-extrabold text-navy">Rates you can read</h2>
          <p class="mt-3 text-sm leading-relaxed text-mute">Laundry is per kilogram. Dry cleaning, shoes and curtains are per piece. Carpets are quoted on size before we collect them.</p>
        </article>
      </div>
    </section>

    <!-- Store counters -->
    <section class="bg-cream py-16">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-10">
          <p class="text-xs font-bold uppercase tracking-[0.18em] text-gold-deep">Our Locations</p>
          <h2 class="mt-2 font-display text-3xl font-extrabold text-navy sm:text-4xl">Visit Our Gurgaon Counters</h2>
        </div>
        <div class="grid gap-6 lg:grid-cols-2">
          <a href="https://share.google/SXSjDFUalRginesFu" target="_blank" rel="noopener noreferrer" class="reveal group rounded-3xl bg-white p-7 shadow-card transition duration-300 hover:-translate-y-1 hover:shadow-lift">
            <p class="text-xs font-bold uppercase tracking-[0.16em] text-gold-deep">Sector 49 Counter</p>
            <h2 class="mt-2 font-display text-2xl font-extrabold text-navy">Orchid Petals Plaza</h2>
            <p class="mt-3 text-sm leading-relaxed text-mute">Shop No. 19, 1st Floor, B Block Shopping Plaza, Orchid Petals, South City II, Sector 49, Gurgaon, Haryana - 122018</p>
            <p class="mt-4 font-display text-sm font-extrabold text-navy inline-flex items-center gap-1 group-hover:text-gold transition">Open in Google Maps →</p>
          </a>
          <a href="https://share.google/UZlhkLsEqN9ICLwTa" target="_blank" rel="noopener noreferrer" class="reveal group rounded-3xl bg-white p-7 shadow-card transition duration-300 hover:-translate-y-1 hover:shadow-lift" style="animation-delay:80ms">
            <p class="text-xs font-bold uppercase tracking-[0.16em] text-gold-deep">Sector 83 Counter</p>
            <h2 class="mt-2 font-display text-2xl font-extrabold text-navy">Sapphire 83 Mall</h2>
            <p class="mt-3 text-sm leading-relaxed text-mute">F-193, First Floor, Sapphire 83 Mall, Sector 83, Gurgaon, Haryana - 122004</p>
            <p class="mt-4 font-display text-sm font-extrabold text-navy inline-flex items-center gap-1 group-hover:text-gold transition">Open in Google Maps →</p>
          </a>
        </div>
      </div>
    </section>

    <!-- Call to Action Banner -->
    <section class="bg-navy">
      <div class="mx-auto flex max-w-7xl flex-col items-start justify-between gap-6 px-4 py-12 sm:px-6 md:flex-row md:items-center lg:px-8">
        <div class="max-w-xl">
          <p class="text-xs font-bold uppercase tracking-[0.18em] text-gold">Free pickup &amp; delivery</p>
          <h2 class="mt-2 font-display text-3xl font-extrabold text-white">Book the next collection from either counter.</h2>
          <p class="mt-2 text-sm leading-relaxed text-white/75">Phone and WhatsApp both reach the same pickup desk. Tell us the sector and what is in the bag.</p>
        </div>
        <div class="flex w-full flex-col gap-3 sm:w-auto sm:flex-row">
          <button type="button" data-open-book data-service="Mixed pickup" class="inline-flex items-center justify-center rounded-full bg-gold px-6 py-3.5 font-display text-sm font-extrabold text-navy transition duration-300 hover:-translate-y-0.5 hover:bg-gold-deep shadow-md">Schedule a Pickup</button>
          <a href="tel:{{ preg_replace('/[^0-9+]/', '', $site['phone'] ?? '+919582466445') }}" class="inline-flex items-center justify-center rounded-full border-2 border-white/25 px-6 py-3.5 font-display text-sm font-extrabold text-white transition duration-300 hover:-translate-y-0.5 hover:bg-white hover:text-navy">Call {{ $site['phone_display'] ?? $site['phone'] ?? '+91 95824 66445' }}</a>
        </div>
      </div>
    </section>
@endsection
