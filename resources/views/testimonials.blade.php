@extends('layouts.app')

@section('title', ($testimonialPage->metatitle ?? 'Customer Reviews & Testimonials | Tenderfold Gurgaon'))
@section('meta_description', ($testimonialPage->metadescription ?? 'Read what residents in Sector 49, Sector 83, and across Gurgaon say about Tenderfold laundry and dry cleaning services.'))
@section('meta_keywords', ($testimonialPage->metakeywords ?? 'tenderfold reviews, laundry reviews gurgaon, dry cleaning ratings gurgaon'))

@section('content')
<main id="main">
  <!-- Testimonials Hero -->
  <section class="bg-cream">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8 lg:py-20">
      <div class="reveal max-w-2xl">
        <p class="text-sm font-semibold text-mute">
          <a href="{{ route('home') }}" class="transition hover:text-navy">Home</a> 
          <span aria-hidden="true">/</span> 
          <span class="text-navy">Reviews</span>
        </p>
        <p class="mt-5 text-xs font-bold uppercase tracking-[0.18em] text-gold-deep">Client Feedback</p>
        <h1 class="mt-3 font-display text-4xl font-extrabold text-navy sm:text-5xl">What Gurgaon says about Tenderfold.</h1>
        <p class="mt-4 text-lg text-mute">Genuine experiences from families, professionals, and residents in Sector 49, Sector 83, Golf Course Ext., and New Gurgaon.</p>
        <div class="mt-8 flex flex-wrap gap-4">
          <button type="button" data-open-book class="inline-flex items-center justify-center rounded-full bg-gold px-6 py-3.5 font-display text-sm font-extrabold text-navy transition duration-300 hover:-translate-y-0.5 hover:bg-gold-deep">Schedule a Pickup</button>
          <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $site['whatsapp_number'] ?? '919582466445') }}" target="_blank" rel="noopener" class="inline-flex items-center justify-center rounded-full border border-navy/20 bg-white px-6 py-3.5 font-display text-sm font-extrabold text-navy transition hover:bg-navy hover:text-white">Share Your Review</a>
        </div>
      </div>

      <!-- Testimonials Grid -->
      <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-2">
        @forelse($testimonials as $test)
          <article class="reveal rounded-3xl bg-white p-7 shadow-card flex flex-col justify-between">
            <div>
              <div class="flex items-center gap-1 text-gold mb-4" aria-label="{{ $test->rating ?? 5 }} out of 5 stars">
                @for($i = 0; $i < ($test->rating ?? 5); $i++)
                  <svg class="h-5 w-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                @endfor
              </div>
              <p class="text-base leading-relaxed text-ink italic">“{{ trim(strip_tags($test->content ?? $test->quote ?? '')) }}”</p>
            </div>
            <div class="mt-6 flex items-center gap-3 pt-4 border-t border-navy/10">
              <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-cream font-display font-extrabold text-navy text-base overflow-hidden border border-gold/30">
                @if(!empty($test->avatar_path))
                  <img src="{{ site_image($test->avatar_path) }}" alt="{{ $test->name }}" class="h-full w-full object-cover">
                @else
                  {{ substr($test->name ?? 'G', 0, 1) }}
                @endif
              </div>
              <div>
                <h3 class="font-display font-bold text-navy text-sm">{{ $test->name }}</h3>
                <p class="text-xs text-mute">{{ $test->role ?? ($test->designation ?? 'Gurgaon Resident') }}</p>
              </div>
            </div>
          </article>
        @empty
          <div class="col-span-full rounded-3xl bg-white p-12 text-center shadow-card">
            <p class="text-mute font-semibold">No reviews published yet.</p>
          </div>
        @endforelse
      </div>
    </div>
  </section>

  <!-- CTA Banner -->
  <section class="bg-navy">
    <div class="mx-auto flex max-w-7xl flex-col items-start justify-between gap-6 px-4 py-12 sm:px-6 md:flex-row md:items-center lg:px-8">
      <div class="max-w-xl">
        <p class="text-xs font-bold uppercase tracking-[0.18em] text-gold">Free pickup &amp; delivery</p>
        <h2 class="mt-2 font-display text-3xl font-extrabold text-white">Experience the difference in every fold.</h2>
        <p class="mt-2 text-sm leading-relaxed text-white/75">Sector 49 (Orchid Petals) & Sector 83 (Sapphire 83 Mall). Doorstep collection across Gurgaon.</p>
      </div>
      <div class="flex w-full flex-col gap-3 sm:w-auto sm:flex-row">
        <button type="button" data-open-book class="inline-flex items-center justify-center rounded-full bg-gold px-6 py-3.5 font-display text-sm font-extrabold text-navy transition duration-300 hover:-translate-y-0.5 hover:bg-gold-deep">Schedule a Pickup</button>
        <a href="tel:{{ preg_replace('/[^0-9\+]/', '', $site['phone'] ?? '+919582466445') }}" class="inline-flex items-center justify-center rounded-full border-2 border-white/25 px-6 py-3.5 font-display text-sm font-extrabold text-white transition duration-300 hover:-translate-y-0.5 hover:bg-white hover:text-navy">Call the store</a>
      </div>
    </div>
  </section>
</main>
@endsection
