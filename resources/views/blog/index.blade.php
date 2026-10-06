@extends('layouts.app')

@section('title', ($blogsPage->metatitle ?? 'Journal & Care Notes | Tenderfold Gurgaon'))
@section('meta_description', ($blogsPage->metadescription ?? 'Care notes, fabric tips, and wardrobe advice from the Tenderfold laundry counter. Learn what to wash, dry clean, or steam press in Gurgaon.'))
@section('meta_keywords', ($blogsPage->metakeywords ?? 'laundry tips gurgaon, dry cleaning advice, silk care guide, woolens dry clean gurgaon'))

@section('content')
<main id="main">
  <!-- Journal Hero -->
  <section class="bg-cream">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8 lg:py-20">
      <div class="reveal max-w-2xl">
        <p class="text-sm font-semibold text-mute">
          <a href="{{ route('home') }}" class="transition hover:text-navy">Home</a> 
          <span aria-hidden="true">/</span> 
          <span class="text-navy">Journal</span>
        </p>
        <p class="mt-5 text-xs font-bold uppercase tracking-[0.18em] text-gold-deep">Care notes</p>
        <h1 class="mt-3 font-display text-4xl font-extrabold text-navy sm:text-5xl">What to send, and what to keep out of the machine.</h1>
        <p class="mt-4 text-lg text-mute">Practical fabric notes and wardrobe care advice from our counters in Sector 49 and Sector 83, Gurgaon.</p>
        <button type="button" data-open-book class="mt-8 inline-flex items-center justify-center rounded-full bg-gold px-6 py-3.5 font-display text-sm font-extrabold text-navy transition duration-300 hover:-translate-y-0.5 hover:bg-gold-deep">Ask us about a piece</button>
      </div>

      <!-- Journal Articles Listing -->
      <div class="mt-12">
        @if($blogs->count() > 0)
          <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($blogs as $blog)
              @include('blog.partials.card', ['blog' => $blog])
            @endforeach
          </div>

          <!-- Pagination -->
          @if($blogs->hasPages())
            <div class="mt-12 flex justify-center">
              {{ $blogs->links() }}
            </div>
          @endif
        @else
          <div class="rounded-3xl bg-white p-12 text-center shadow-card">
            <p class="text-mute font-semibold">No care notes published yet. Check back soon!</p>
          </div>
        @endif
      </div>
    </div>
  </section>

  <!-- CTA Banner -->
  <section class="bg-navy">
    <div class="mx-auto flex max-w-7xl flex-col items-start justify-between gap-6 px-4 py-12 sm:px-6 md:flex-row md:items-center lg:px-8">
      <div class="max-w-xl">
        <p class="text-xs font-bold uppercase tracking-[0.18em] text-gold">Free pickup &amp; delivery</p>
        <h2 class="mt-2 font-display text-3xl font-extrabold text-white">A piece that does not fit these notes?</h2>
        <p class="mt-2 text-sm leading-relaxed text-white/75">Describe it in the pickup note, or send a photo on WhatsApp. We will say whether it is laundry, dry cleaning, or a quote.</p>
      </div>
      <div class="flex w-full flex-col gap-3 sm:w-auto sm:flex-row">
        <button type="button" data-open-book data-service="Mixed pickup" class="inline-flex items-center justify-center rounded-full bg-gold px-6 py-3.5 font-display text-sm font-extrabold text-navy transition duration-300 hover:-translate-y-0.5 hover:bg-gold-deep">Schedule a Pickup</button>
        <a href="tel:{{ preg_replace('/[^0-9\+]/', '', $site['phone'] ?? '+919582466445') }}" class="inline-flex items-center justify-center rounded-full border-2 border-white/25 px-6 py-3.5 font-display text-sm font-extrabold text-white transition duration-300 hover:-translate-y-0.5 hover:bg-white hover:text-navy">Call the store</a>
      </div>
    </div>
  </section>
</main>
@endsection
