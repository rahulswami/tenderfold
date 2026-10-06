@extends('layouts.app')

@section('title', 'Pricing & Rate Card | ' . ($site['site_name'] ?? 'Tender Fold') . ' Gurgaon')
@section('meta_description', 'Clear, transparent pricing for laundry by kilo, dry cleaning, steam ironing, shoe laundry, curtains and carpets in Gurgaon.')

@section('content')
    <section class="bg-cream">
      <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8 lg:py-20">
        <div class="reveal max-w-2xl">
          <p class="text-sm font-semibold text-mute"><a href="{{ route('home') }}" class="transition hover:text-navy">Home</a> <span aria-hidden="true">/</span> <span class="text-navy">Pricing</span></p>
          <p class="mt-5 text-xs font-bold uppercase tracking-[0.18em] text-gold-deep">Published rates</p>
          <h1 class="mt-3 font-display text-4xl font-extrabold text-navy sm:text-5xl">Laundry by the kilo. Everything else by the piece.</h1>
          <p class="mt-4 text-lg text-mute">Prices are in INR. A plus sign means the piece may vary with fabric, embellishment or size. First order is 20% off. Pickup and delivery are free. Terms apply.</p>
          <button type="button" data-open-book class="mt-8 inline-flex items-center justify-center rounded-full bg-gold px-6 py-3.5 font-display text-sm font-extrabold text-navy transition duration-300 hover:-translate-y-0.5 hover:bg-gold-deep shadow-md">Schedule a Pickup</button>
        </div>

        <div class="mt-12 grid items-stretch gap-6 lg:grid-cols-3">
          <article class="reveal flex flex-col rounded-3xl border border-navy/10 bg-white p-6 shadow-card sm:p-8">
            <h2 class="font-display text-xl font-extrabold text-navy">Wash &amp; Fold</h2>
            <p class="mt-5 font-display text-4xl font-extrabold text-navy">₹80 <span class="text-base font-bold text-mute">/ kg</span></p>
            <p class="mt-3 flex-1 text-sm text-mute">Colour-sorted, dried and folded for the cupboard.</p>
            <button type="button" data-open-book data-service="Wash &amp; Fold" class="mt-6 inline-flex items-center justify-center rounded-full border-2 border-navy/15 px-5 py-3 font-display text-sm font-extrabold text-navy transition hover:bg-navy hover:text-white">Choose this plan</button>
          </article>
          <article class="reveal relative flex flex-col rounded-3xl bg-navy p-6 text-white shadow-lift sm:p-8 lg:-translate-y-3">
            <p class="absolute -top-3 left-1/2 -translate-x-1/2 rounded-full bg-gold px-4 py-1 font-display text-xs font-extrabold uppercase tracking-wide text-navy">Most chosen</p>
            <h2 class="font-display text-xl font-extrabold">Wash &amp; Iron</h2>
            <p class="mt-5 font-display text-4xl font-extrabold text-gold">₹115 <span class="text-base font-bold text-white/70">/ kg</span></p>
            <p class="mt-3 flex-1 text-sm text-white/75">Washed, steam pressed and finished on hangers.</p>
            <button type="button" data-open-book data-service="Wash &amp; Iron" class="mt-6 inline-flex items-center justify-center rounded-full bg-gold px-5 py-3 font-display text-sm font-extrabold text-navy transition hover:bg-gold-deep shadow-md">Choose this plan</button>
          </article>
          <article class="reveal flex flex-col rounded-3xl border border-navy/10 bg-white p-6 shadow-card sm:p-8">
            <h2 class="font-display text-xl font-extrabold text-navy">Premium Laundry</h2>
            <p class="mt-5 font-display text-4xl font-extrabold text-navy">₹185 <span class="text-base font-bold text-mute">/ kg</span></p>
            <p class="mt-3 flex-1 text-sm text-mute">A gentler cycle and a closer finish for finer cloth.</p>
            <button type="button" data-open-book data-service="Premium Laundry" class="mt-6 inline-flex items-center justify-center rounded-full border-2 border-navy/15 px-5 py-3 font-display text-sm font-extrabold text-navy transition hover:bg-navy hover:text-white">Choose this plan</button>
          </article>
        </div>
      </div>
    </section>

    <section id="rates" class="scroll-mt-36 bg-white py-16">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <h2 class="reveal font-display text-3xl font-extrabold text-navy">Piece rates</h2>
        <p class="reveal mt-3 max-w-2xl text-mute">Carpets are quoted on size before pickup. Household linen is listed with the last group.</p>
        
        <div class="mt-8 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
          <article class="overflow-hidden rounded-2xl bg-white shadow-card border border-navy/5">
            <header class="bg-navy px-5 py-4 text-white"><h3 class="font-display text-lg font-extrabold">Men’s wear</h3></header>
            <ul class="text-sm">
              <li class="flex justify-between gap-3 px-5 py-2.5 border-b border-navy/5"><span>T-shirt / Shirt</span><span class="font-bold text-navy">₹130</span></li>
              <li class="flex justify-between gap-3 px-5 py-2.5 border-b border-navy/5"><span>Kurta</span><span class="font-bold text-navy">₹130</span></li>
              <li class="flex justify-between gap-3 px-5 py-2.5 border-b border-navy/5"><span>Pyjama</span><span class="font-bold text-navy">₹110</span></li>
              <li class="flex justify-between gap-3 px-5 py-2.5 border-b border-navy/5"><span>Pants / Trousers</span><span class="font-bold text-navy">₹130</span></li>
              <li class="flex justify-between gap-3 px-5 py-2.5 border-b border-navy/5"><span>Coat / Blazer</span><span class="font-bold text-navy">₹350</span></li>
              <li class="flex justify-between gap-3 px-5 py-2.5 border-b border-navy/5"><span>Waistcoat</span><span class="font-bold text-navy">₹150</span></li>
              <li class="flex justify-between gap-3 px-5 py-2.5 border-b border-navy/5"><span>2 Piece Suit</span><span class="font-bold text-navy">₹400</span></li>
              <li class="flex justify-between gap-3 px-5 py-2.5"><span>3 Piece Suit</span><span class="font-bold text-navy">₹500</span></li>
            </ul>
          </article>

          <article class="overflow-hidden rounded-2xl bg-white shadow-card border border-navy/5">
            <header class="bg-navy px-5 py-4 text-white"><h3 class="font-display text-lg font-extrabold">Women’s wear</h3></header>
            <ul class="text-sm">
              <li class="flex justify-between gap-3 px-5 py-2.5 border-b border-navy/5"><span>Dress</span><span class="font-bold text-navy">₹275+</span></li>
              <li class="flex justify-between gap-3 px-5 py-2.5 border-b border-navy/5"><span>Kurta / Kurti</span><span class="font-bold text-navy">₹130+</span></li>
              <li class="flex justify-between gap-3 px-5 py-2.5 border-b border-navy/5"><span>Pyjama / Salwar</span><span class="font-bold text-navy">₹100</span></li>
              <li class="flex justify-between gap-3 px-5 py-2.5 border-b border-navy/5"><span>Palazzo</span><span class="font-bold text-navy">₹130+</span></li>
              <li class="flex justify-between gap-3 px-5 py-2.5 border-b border-navy/5"><span>Dupatta (Plain/Heavy)</span><span class="font-bold text-navy">₹80+</span></li>
              <li class="flex justify-between gap-3 px-5 py-2.5"><span>Blouse</span><span class="font-bold text-navy">₹100+</span></li>
            </ul>
          </article>

          <article class="overflow-hidden rounded-2xl bg-white shadow-card border border-navy/5">
            <header class="bg-navy px-5 py-4 text-white"><h3 class="font-display text-lg font-extrabold">Ethnic &amp; Bridal Wear</h3></header>
            <ul class="text-sm">
              <li class="flex justify-between gap-3 px-5 py-2.5 border-b border-navy/5"><span>Saree (Silk / Georgette)</span><span class="font-bold text-navy">₹230+</span></li>
              <li class="flex justify-between gap-3 px-5 py-2.5 border-b border-navy/5"><span>Anarkali Suit</span><span class="font-bold text-navy">₹450+</span></li>
              <li class="flex justify-between gap-3 px-5 py-2.5 border-b border-navy/5"><span>Evening Gown</span><span class="font-bold text-navy">₹450+</span></li>
              <li class="flex justify-between gap-3 px-5 py-2.5"><span>Bridal Lehenga / Sherwani</span><span class="font-bold text-navy">₹550+</span></li>
            </ul>
          </article>

          <article class="overflow-hidden rounded-2xl bg-white shadow-card border border-navy/5">
            <header class="bg-navy px-5 py-4 text-white"><h3 class="font-display text-lg font-extrabold">Winter wear</h3></header>
            <ul class="text-sm">
              <li class="flex justify-between gap-3 px-5 py-2.5 border-b border-navy/5"><span>Jacket</span><span class="font-bold text-navy">₹350</span></li>
              <li class="flex justify-between gap-3 px-5 py-2.5 border-b border-navy/5"><span>Sweater / Cardigan</span><span class="font-bold text-navy">₹280</span></li>
              <li class="flex justify-between gap-3 px-5 py-2.5 border-b border-navy/5"><span>Half Jacket / Sleeveless</span><span class="font-bold text-navy">₹250</span></li>
              <li class="flex justify-between gap-3 px-5 py-2.5 border-b border-navy/5"><span>Sweatshirt / Hoodie</span><span class="font-bold text-navy">₹280 - ₹350</span></li>
              <li class="flex justify-between gap-3 px-5 py-2.5"><span>Overcoat / Trench Coat</span><span class="font-bold text-navy">₹450+</span></li>
            </ul>
          </article>

          <article class="overflow-hidden rounded-2xl bg-white shadow-card border border-navy/5">
            <header class="bg-navy px-5 py-4 text-white"><h3 class="font-display text-lg font-extrabold">Footwear &amp; Bags</h3></header>
            <ul class="text-sm">
              <li class="flex justify-between gap-3 px-5 py-2.5 border-b border-navy/5"><span>Sneakers / Sports Shoes</span><span class="font-bold text-navy">₹350</span></li>
              <li class="flex justify-between gap-3 px-5 py-2.5 border-b border-navy/5"><span>Leather &amp; Suede Shoes</span><span class="font-bold text-navy">₹450 - ₹540</span></li>
              <li class="flex justify-between gap-3 px-5 py-2.5 border-b border-navy/5"><span>Leather Bag (Small/Large)</span><span class="font-bold text-navy">₹500 - ₹800</span></li>
              <li class="flex justify-between gap-3 px-5 py-2.5"><span>Canvas / Fabric Bag</span><span class="font-bold text-navy">₹300 - ₹450</span></li>
            </ul>
          </article>

          <article class="overflow-hidden rounded-2xl bg-white shadow-card border border-navy/5">
            <header class="bg-navy px-5 py-4 text-white"><h3 class="font-display text-lg font-extrabold">Curtains &amp; Drapes</h3></header>
            <ul class="text-sm">
              <li class="flex justify-between gap-3 px-5 py-2.5 border-b border-navy/5"><span>Single Window Curtain</span><span class="font-bold text-navy">₹230</span></li>
              <li class="flex justify-between gap-3 px-5 py-2.5 border-b border-navy/5"><span>Curtain with Blackout Lining</span><span class="font-bold text-navy">₹350</span></li>
              <li class="flex justify-between gap-3 px-5 py-2.5"><span>Sheer / Net Curtain</span><span class="font-bold text-navy">₹180</span></li>
            </ul>
          </article>

          <article class="overflow-hidden rounded-2xl bg-white shadow-card md:col-span-2 xl:col-span-3 border border-navy/5">
            <header class="bg-navy px-5 py-4 text-white"><h3 class="font-display text-lg font-extrabold">Household Linen</h3></header>
            <ul class="grid text-sm sm:grid-cols-2 md:grid-cols-3">
              <li class="flex justify-between gap-3 border-b border-navy/5 px-5 py-3"><span>Blanket / Quilt (Single)</span><span class="font-bold text-navy">₹350</span></li>
              <li class="flex justify-between gap-3 border-b border-navy/5 px-5 py-3"><span>Blanket / Quilt (Double)</span><span class="font-bold text-navy">₹450</span></li>
              <li class="flex justify-between gap-3 border-b border-navy/5 px-5 py-3"><span>Bedsheet (Single)</span><span class="font-bold text-navy">₹175</span></li>
              <li class="flex justify-between gap-3 border-b border-navy/5 px-5 py-3"><span>Bedsheet (Double)</span><span class="font-bold text-navy">₹250</span></li>
              <li class="flex justify-between gap-3 border-b border-navy/5 px-5 py-3"><span>Pillow (Small / Med / Lrg)</span><span class="font-bold text-navy">₹150 - ₹300</span></li>
              <li class="flex justify-between gap-3 border-b border-navy/5 px-5 py-3"><span>Pillow Covers</span><span class="font-bold text-navy">₹100+</span></li>
            </ul>
          </article>
        </div>
      </div>
    </section>

    <!-- Bottom Banner -->
    <section class="bg-navy">
      <div class="mx-auto flex max-w-7xl flex-col items-start justify-between gap-6 px-4 py-12 sm:px-6 md:flex-row md:items-center lg:px-8">
        <div class="max-w-xl">
          <p class="text-xs font-bold uppercase tracking-[0.18em] text-gold">Free pickup &amp; delivery</p>
          <h2 class="mt-2 font-display text-3xl font-extrabold text-white">Send the bag. We will price it from this card.</h2>
          <p class="mt-2 text-sm leading-relaxed text-white/75">If a piece is not listed, or a carpet needs a quote, say so in the note and we will confirm before pickup.</p>
        </div>
        <div class="flex w-full flex-col gap-3 sm:w-auto sm:flex-row">
          <button type="button" data-open-book data-service="Mixed pickup" class="inline-flex items-center justify-center rounded-full bg-gold px-6 py-3.5 font-display text-sm font-extrabold text-navy transition duration-300 hover:-translate-y-0.5 hover:bg-gold-deep shadow-md">Schedule a Pickup</button>
          <a href="tel:{{ preg_replace('/[^0-9+]/', '', $site['phone'] ?? '+919582466445') }}" class="inline-flex items-center justify-center rounded-full border-2 border-white/25 px-6 py-3.5 font-display text-sm font-extrabold text-white transition duration-300 hover:-translate-y-0.5 hover:bg-white hover:text-navy">Call {{ $site['phone_display'] ?? $site['phone'] ?? '+91 95824 66445' }}</a>
        </div>
      </div>
    </section>
@endsection
