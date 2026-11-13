@extends('layouts.app')

@section('title', ($page->metatitle ?? ($page->title . ' | ' . ($site['site_name'] ?? 'Tenderfold'))))
@section('meta_description', $page->metadescription ?? Str::limit(strip_tags($page->content ?? $page->description), 150))
@section('meta_keywords', $page->metakeywords ?? '')

@section('schema')
  @if(!empty($page->schema_markup))
    {!! $page->schema_markup !!}
  @else
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@type": "WebPage",
      "name": "{{ $page->title }}"
    }
    </script>
  @endif
@endsection

@section('content')
<main id="main">
  <!-- Header -->
  <section class="bg-cream">
    <div class="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
      <div class="reveal">
        <p class="text-sm font-semibold text-mute">
          <a href="{{ route('home') }}" class="transition hover:text-navy">Home</a> 
          <span aria-hidden="true">/</span> 
          <span class="text-navy">{{ $page->title }}</span>
        </p>
        <p class="mt-4 text-xs font-bold uppercase tracking-[0.18em] text-gold-deep">Tenderfold Gurgaon</p>
        <h1 class="mt-2 font-display text-3xl font-extrabold text-navy sm:text-4xl lg:text-5xl">{{ $page->title }}</h1>
        @if($page->updated_at)
          <p class="mt-3 text-xs font-semibold text-mute">Last updated: {{ $page->updated_at->format('F d, Y') }}</p>
        @endif
      </div>
    </div>
  </section>

  <!-- Page Content -->
  <section class="bg-white py-12 sm:py-16">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
      <div class="reveal rounded-3xl bg-cream/30 p-6 sm:p-10 border border-navy/5 shadow-card">
        <div class="cms-prose text-ink leading-relaxed">
          {!! $page->content ?: $page->description !!}
        </div>
      </div>
    </div>
  </section>

  <!-- Bottom CTA Banner -->
  <section class="bg-navy">
    <div class="mx-auto flex max-w-7xl flex-col items-start justify-between gap-6 px-4 py-12 sm:px-6 md:flex-row md:items-center lg:px-8">
      <div class="max-w-xl">
        <p class="text-xs font-bold uppercase tracking-[0.18em] text-gold">Free pickup &amp; delivery across Gurgaon</p>
        <h2 class="mt-2 font-display text-3xl font-extrabold text-white">Experience premium fabric care with Tenderfold.</h2>
        <p class="mt-2 text-sm leading-relaxed text-white/75">Doorstep laundry, Italian steam pressing, and specialized dry cleaning across all major sectors.</p>
      </div>
      <div class="flex w-full flex-col gap-3 sm:w-auto sm:flex-row">
        <button type="button" data-open-book class="inline-flex items-center justify-center rounded-full bg-gold px-6 py-3.5 font-display text-sm font-extrabold text-navy transition duration-300 hover:-translate-y-0.5 hover:bg-gold-deep shadow-md">Schedule a Pickup</button>
        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $site['phone'] ?? '+919582466445') }}" class="inline-flex items-center justify-center rounded-full border-2 border-white/25 px-6 py-3.5 font-display text-sm font-extrabold text-white transition duration-300 hover:-translate-y-0.5 hover:bg-white hover:text-navy">Call {{ $site['phone_display'] ?? $site['phone'] ?? '+91 95824 66445' }}</a>
      </div>
    </div>
  </section>
</main>

<style>
  .cms-prose h1 {
    font-family: 'Nunito', ui-sans-serif, system-ui, sans-serif;
    font-size: 1.875rem;
    line-height: 2.25rem;
    font-weight: 800;
    color: #143572;
    margin-top: 1rem;
    margin-bottom: 1rem;
  }
  @media (min-width: 640px) {
    .cms-prose h1 { font-size: 2.25rem; line-height: 2.5rem; }
  }
  .cms-prose h2 {
    font-family: 'Nunito', ui-sans-serif, system-ui, sans-serif;
    font-size: 1.5rem;
    line-height: 2rem;
    font-weight: 800;
    color: #143572;
    margin-top: 2rem;
    margin-bottom: 0.75rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid rgba(20, 53, 114, 0.1);
  }
  .cms-prose h3 {
    font-family: 'Nunito', ui-sans-serif, system-ui, sans-serif;
    font-size: 1.25rem;
    line-height: 1.75rem;
    font-weight: 700;
    color: #143572;
    margin-top: 1.5rem;
    margin-bottom: 0.5rem;
  }
  .cms-prose p {
    font-size: 1rem;
    line-height: 1.75;
    color: #5C6A82;
    margin-bottom: 1.25rem;
  }
  .cms-prose ul {
    margin-top: 1rem;
    margin-bottom: 1.5rem;
    padding-left: 0;
    list-style: none;
    display: grid;
    gap: 0.75rem;
  }
  .cms-prose li {
    position: relative;
    padding-left: 2rem;
    font-size: 0.95rem;
    color: #1A2744;
    line-height: 1.6;
  }
  .cms-prose li::before {
    content: "✓";
    position: absolute;
    left: 0.25rem;
    top: 0.1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 1.25rem;
    height: 1.25rem;
    border-radius: 9999px;
    background-color: #F5B400;
    color: #143572;
    font-weight: 800;
    font-size: 0.75rem;
  }
  .cms-prose ol {
    margin-top: 1rem;
    margin-bottom: 1.5rem;
    padding-left: 1.5rem;
    list-style-type: decimal;
    color: #5C6A82;
  }
  .cms-prose ol li {
    margin-bottom: 0.5rem;
    padding-left: 0.5rem;
    color: #1A2744;
  }
</style>
@endsection
