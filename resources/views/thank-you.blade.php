@extends('layouts.app')

@section('title', 'Pickup Scheduled Successfully | Tenderfold Gurgaon')
@section('meta_description', 'Thank you for choosing Tenderfold. Your pickup request has been received.')

@section('content')
<main id="main">
  <section class="bg-cream py-16 sm:py-24">
    <div class="mx-auto max-w-xl px-4 text-center sm:px-6">
      <div class="reveal mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-gold text-navy shadow-lift">
        <svg class="h-10 w-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
      </div>

      <div class="reveal">
        <p class="text-xs font-bold uppercase tracking-[0.18em] text-gold-deep">Request Received</p>
        <h1 class="mt-2 font-display text-3xl font-extrabold text-navy sm:text-4xl">Pickup Scheduled!</h1>
        <p class="mt-4 text-base leading-relaxed text-mute">
          Thank you for choosing Tenderfold. Our counter desk has received your details and will connect on WhatsApp or phone to confirm the exact collection time window.
        </p>
      </div>

      <div class="reveal mt-8 rounded-3xl bg-white p-6 sm:p-8 text-left shadow-card">
        <h2 class="font-display text-lg font-bold text-navy flex items-center gap-2">
          <svg class="h-5 w-5 text-gold-deep" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
          What happens next?
        </h2>
        <ul class="mt-4 space-y-3 text-sm text-ink">
          <li class="flex gap-3">
            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-cream text-xs font-bold text-navy">1</span>
            <span>Desk checks your sector and assigned pickup slot.</span>
          </li>
          <li class="flex gap-3">
            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-cream text-xs font-bold text-navy">2</span>
            <span>Rider is dispatched with a Tenderfold collection hamper.</span>
          </li>
          <li class="flex gap-3">
            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-cream text-xs font-bold text-navy">3</span>
            <span>Your garments are weighed/itemized and carefully processed.</span>
          </li>
        </ul>
      </div>

      <div class="reveal mt-8 flex flex-col gap-3 sm:flex-row sm:justify-center">
        <a href="{{ route('home') }}" class="inline-flex items-center justify-center rounded-full bg-gold px-6 py-3.5 font-display text-sm font-extrabold text-navy transition duration-300 hover:-translate-y-0.5 hover:bg-gold-deep">Return Home</a>
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $site['whatsapp_number'] ?? '919582466445') }}" target="_blank" rel="noopener" class="inline-flex items-center justify-center rounded-full border border-navy/20 bg-white px-6 py-3.5 font-display text-sm font-extrabold text-navy transition hover:bg-navy hover:text-white">Chat on WhatsApp</a>
      </div>
    </div>
  </section>
</main>
@endsection
