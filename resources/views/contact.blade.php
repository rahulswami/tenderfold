@extends('layouts.app')

@section('title', ($contactPage->metatitle ?? 'Contact Tenderfold | Gurgaon Laundry & Dry Cleaning Counters'))
@section('meta_description', ($contactPage->metadescription ?? 'Call, WhatsApp, or visit Tenderfold in Sector 49 (Orchid Petals) and Sector 83 (Sapphire 83 Mall), Gurgaon. Free doorstep laundry pickup & delivery across Gurgaon.'))
@section('meta_keywords', ($contactPage->metakeywords ?? 'tenderfold contact, dry cleaners gurgaon contact, sector 49 laundry pickup, sector 83 dry cleaning'))

@section('content')
  <!-- Contact Hero & Stores -->
  <section class="bg-cream">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8 lg:py-20">
      <div class="reveal max-w-2xl">
        <p class="text-sm font-semibold text-mute">
          <a href="{{ route('home') }}" class="transition hover:text-navy">Home</a> 
          <span aria-hidden="true">/</span> 
          <span class="text-navy">Contact</span>
        </p>
        <p class="mt-5 text-xs font-bold uppercase tracking-[0.18em] text-gold-deep">Two counters · one number</p>
        <h1 class="mt-3 font-display text-4xl font-extrabold text-navy sm:text-5xl">Call, WhatsApp, or ask for a pickup.</h1>
        <p class="mt-4 text-lg text-mute">Both stores use {{ $site['phone'] ?? '+91 95824 66445' }}. A pickup request opens WhatsApp so the team can confirm a window.</p>
        <div class="mt-8 flex flex-col gap-3 sm:flex-row">
          <button type="button" data-open-book class="inline-flex items-center justify-center rounded-full bg-gold px-6 py-3.5 font-display text-sm font-extrabold text-navy transition duration-300 hover:-translate-y-0.5 hover:bg-gold-deep">Schedule a Pickup</button>
          <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $site['whatsapp_number'] ?? '919582466445') }}?text={{ urlencode('Hi Tenderfold, I have a query about laundry pickup & services in Gurgaon.') }}" target="_blank" rel="noopener" class="inline-flex items-center justify-center rounded-full border-2 border-navy/15 bg-white px-6 py-3.5 font-display text-sm font-extrabold text-navy transition duration-300 hover:-translate-y-0.5 hover:border-navy hover:bg-navy hover:text-white">WhatsApp the desk</a>
        </div>
      </div>

      <div class="mt-12 grid gap-6 lg:grid-cols-2">
        <!-- Sector 49 Card -->
        <article class="reveal rounded-3xl bg-white p-7 shadow-card">
          <p class="text-xs font-bold uppercase tracking-[0.16em] text-gold-deep">Sector 49</p>
          <h2 class="mt-2 font-display text-2xl font-extrabold text-navy">Orchid Petals</h2>
          <p class="mt-3 text-sm leading-relaxed text-mute">Shop No. 19, 1st Floor, B Block Shopping Plaza, Orchid Petals, South City II, Sector 49, Gurgaon, Haryana 122018</p>
          <a href="https://www.google.com/maps/search/?api=1&amp;query=Shop%20No.%2019%2C%201st%20Floor%2C%20B%20Block%20Shopping%20Plaza%2C%20Orchid%20Petals%2C%20South%20City%20II%2C%20Sector%2049%2C%20Gurgaon%2C%20Haryana%20122018" target="_blank" rel="noopener" class="mt-5 inline-flex font-display text-sm font-extrabold text-navy">Get directions →</a>
        </article>

        <!-- Sector 83 Card -->
        <article class="reveal rounded-3xl bg-white p-7 shadow-card" style="animation-delay:80ms">
          <p class="text-xs font-bold uppercase tracking-[0.16em] text-gold-deep">Sector 83</p>
          <h2 class="mt-2 font-display text-2xl font-extrabold text-navy">Sapphire 83 Mall</h2>
          <p class="mt-3 text-sm leading-relaxed text-mute">F-193, First Floor, Sapphire 83 Mall, Sector 83, Gurgaon 122004</p>
          <a href="https://www.google.com/maps/search/?api=1&amp;query=F-193%20First%20Floor%20Sapphire%2083%20Mall%20Sector%2083%20Gurgaon%20122004" target="_blank" rel="noopener" class="mt-5 inline-flex font-display text-sm font-extrabold text-navy">Get directions →</a>
        </article>
      </div>

      <div class="reveal mt-6 grid gap-4 rounded-3xl bg-navy p-6 text-white sm:grid-cols-3 sm:p-8">
        <a href="tel:{{ preg_replace('/[^0-9\+]/', '', $site['phone'] ?? '+919582466445') }}" class="rounded-2xl bg-white/10 px-5 py-4 transition hover:bg-white/15">
          <p class="text-xs font-bold uppercase tracking-wider text-gold">Phone</p>
          <p class="mt-1 font-display text-xl font-extrabold">{{ $site['phone'] ?? '+91 95824 66445' }}</p>
        </a>
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $site['whatsapp_number'] ?? '919582466445') }}" target="_blank" rel="noopener" class="rounded-2xl bg-white/10 px-5 py-4 transition hover:bg-white/15">
          <p class="text-xs font-bold uppercase tracking-wider text-gold">WhatsApp</p>
          <p class="mt-1 font-display text-xl font-extrabold">Message the desk</p>
        </a>
        <div class="rounded-2xl bg-white/10 px-5 py-4">
          <span class="block text-xs font-bold uppercase tracking-wider text-gold">Pickup</span>
          <span class="mt-1 block font-display text-xl font-extrabold">Free across Gurgaon</span>
        </div>
      </div>
    </div>
  </section>

  <!-- Contact Form Section -->
  <section class="bg-white py-16 md:py-20" aria-labelledby="contactFormTitle">
    <div class="mx-auto grid max-w-7xl items-start gap-10 px-4 sm:px-6 lg:grid-cols-2 lg:px-8">
      <div class="reveal">
        <p class="text-xs font-bold uppercase tracking-[0.18em] text-gold-deep">Message the desk</p>
        <h2 id="contactFormTitle" class="mt-3 font-display text-3xl font-extrabold text-navy sm:text-4xl">Send a note. We reply on WhatsApp.</h2>
        <p class="mt-4 text-mute">There is no separate inbox. Your inquiry is recorded in our system and automatically formatted for direct WhatsApp coordination with the counter desk.</p>
        <ul class="mt-6 space-y-3 text-sm text-ink">
          <li class="flex gap-3"><span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-gold text-navy font-bold" aria-hidden="true">1</span> Tell us which counter is closer, or leave it as either.</li>
          <li class="flex gap-3"><span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-gold text-navy font-bold" aria-hidden="true">2</span> Add the sector and what you want collected.</li>
          <li class="flex gap-3"><span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-gold text-navy font-bold" aria-hidden="true">3</span> We confirm a window before the rider leaves.</li>
        </ul>
      </div>

      <form id="contactPageForm" class="reveal rounded-3xl bg-cream p-6 shadow-card sm:p-8" style="animation-delay:80ms">
        @csrf
        <div class="grid gap-4 sm:grid-cols-2">
          <div>
            <label for="contactName" class="mb-1.5 block text-sm font-semibold text-navy">Name</label>
            <input id="contactName" name="name" required autocomplete="name" class="w-full rounded-2xl border border-navy/15 bg-white px-4 py-3 text-sm outline-none transition focus:border-navy focus:ring-2 focus:ring-gold/50" placeholder="Your full name">
          </div>
          <div>
            <label for="contactPhone" class="mb-1.5 block text-sm font-semibold text-navy">Phone</label>
            <input id="contactPhone" name="phone" type="tel" required autocomplete="tel" inputmode="tel" placeholder="10-digit mobile" class="w-full rounded-2xl border border-navy/15 bg-white px-4 py-3 text-sm outline-none transition focus:border-navy focus:ring-2 focus:ring-gold/50">
          </div>
        </div>
        <div class="mt-4">
          <label for="contactStore" class="mb-1.5 block text-sm font-semibold text-navy">Preferred counter</label>
          <select id="contactStore" name="store" class="w-full rounded-2xl border border-navy/15 bg-white px-4 py-3 text-sm outline-none transition focus:border-navy focus:ring-2 focus:ring-gold/50">
            <option value="Either counter">Either counter</option>
            <option value="Orchid Petals, Sector 49">Orchid Petals, Sector 49</option>
            <option value="Sapphire 83 Mall, Sector 83">Sapphire 83 Mall, Sector 83</option>
          </select>
        </div>
        <div class="mt-4">
          <label for="contactMessage" class="mb-1.5 block text-sm font-semibold text-navy">Message / Requirements</label>
          <textarea id="contactMessage" name="message" required rows="4" class="w-full rounded-2xl border border-navy/15 bg-white px-4 py-3 text-sm outline-none transition focus:border-navy focus:ring-2 focus:ring-gold/50" placeholder="Sector, pieces, or a question about a fabric"></textarea>
        </div>
        <button type="submit" id="contactSubmitBtn" class="mt-5 inline-flex w-full items-center justify-center rounded-full bg-gold px-6 py-3.5 font-display text-sm font-extrabold text-navy transition duration-300 hover:-translate-y-0.5 hover:bg-gold-deep sm:w-auto">Send message</button>
        <p id="contactMsg" class="mt-3 hidden text-sm font-semibold" role="status"></p>
      </form>
    </div>
  </section>

  <!-- Interactive Map Section -->
  <section class="bg-cream" aria-labelledby="mapTitle">
    <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-8 sm:flex-row sm:items-end sm:justify-between sm:px-6 lg:px-8">
      <div>
        <p class="text-xs font-bold uppercase tracking-[0.18em] text-gold-deep">On the map</p>
        <h2 id="mapTitle" class="mt-2 font-display text-3xl font-extrabold text-navy">Both counters</h2>
      </div>
      <div class="flex flex-wrap gap-2" role="tablist" aria-label="Choose a store map">
        <button type="button" role="tab" id="mapOrchid" data-map-src="https://maps.google.com/maps?q=Shop%20No.%2019%2C%201st%20Floor%2C%20B%20Block%20Shopping%20Plaza%2C%20Orchid%20Petals%2C%20South%20City%20II%2C%20Sector%2049%2C%20Gurgaon%2C%20Haryana%20122018&amp;z=16&amp;output=embed" aria-selected="true" class="map-tab rounded-full bg-navy px-4 py-2.5 font-display text-sm font-extrabold text-white">Orchid Petals (Sec 49)</button>
        <button type="button" role="tab" id="mapSapphire" data-map-src="https://maps.google.com/maps?q=F-193%2C%20First%20Floor%2C%20Sapphire%2083%20Mall%2C%20Sector%2083%2C%20Gurgaon%20122004&amp;z=16&amp;output=embed" aria-selected="false" class="map-tab rounded-full bg-white px-4 py-2.5 font-display text-sm font-extrabold text-navy shadow-card">Sapphire 83 Mall (Sec 83)</button>
      </div>
    </div>
    <iframe id="storeMap" title="Orchid Petals, Sector 49, on Google Maps" class="block h-[420px] w-full border-0 sm:h-[520px] lg:h-[560px]" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="https://maps.google.com/maps?q=Shop%20No.%2019%2C%201st%20Floor%2C%20B%20Block%20Shopping%20Plaza%2C%20Orchid%20Petals%2C%20South%20City%20II%2C%20Sector%2049%2C%20Gurgaon%2C%20Haryana%20122018&amp;z=16&amp;output=embed"></iframe>
  </section>

  <!-- CTA Banner -->
  <section class="bg-navy">
    <div class="mx-auto flex max-w-7xl flex-col items-start justify-between gap-6 px-4 py-12 sm:px-6 md:flex-row md:items-center lg:px-8">
      <div class="max-w-xl">
        <p class="text-xs font-bold uppercase tracking-[0.18em] text-gold">Free pickup &amp; delivery</p>
        <h2 class="mt-2 font-display text-3xl font-extrabold text-white">Tell us the sector and what is in the bag.</h2>
        <p class="mt-2 text-sm leading-relaxed text-white/75">Morning, afternoon or evening. We confirm on WhatsApp before the rider leaves.</p>
      </div>
      <div class="flex w-full flex-col gap-3 sm:w-auto sm:flex-row">
        <button type="button" data-open-book data-service="Mixed pickup" class="inline-flex items-center justify-center rounded-full bg-gold px-6 py-3.5 font-display text-sm font-extrabold text-navy transition duration-300 hover:-translate-y-0.5 hover:bg-gold-deep">Schedule a Pickup</button>
        <a href="tel:{{ preg_replace('/[^0-9\+]/', '', $site['phone'] ?? '+919582466445') }}" class="inline-flex items-center justify-center rounded-full border-2 border-white/25 px-6 py-3.5 font-display text-sm font-extrabold text-white transition duration-300 hover:-translate-y-0.5 hover:bg-white hover:text-navy">Call the store</a>
      </div>
    </div>
  </section>
</main>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  // Map Tabs
  const mapEl = document.getElementById('storeMap');
  const tabs = document.querySelectorAll('.map-tab');
  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      tabs.forEach(t => {
        t.setAttribute('aria-selected', 'false');
        t.className = 'map-tab rounded-full bg-white px-4 py-2.5 font-display text-sm font-extrabold text-navy shadow-card';
      });
      tab.setAttribute('aria-selected', 'true');
      tab.className = 'map-tab rounded-full bg-navy px-4 py-2.5 font-display text-sm font-extrabold text-white';
      if (mapEl && tab.dataset.mapSrc) {
        mapEl.src = tab.dataset.mapSrc;
      }
    });
  });

  // Contact Form AJAX
  const cForm = document.getElementById('contactPageForm');
  const cMsg = document.getElementById('contactMsg');
  const cBtn = document.getElementById('contactSubmitBtn');

  if (cForm) {
    cForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      const originalText = cBtn.textContent;
      cBtn.disabled = true;
      cBtn.textContent = 'Sending...';

      const formData = new FormData(cForm);
      const name = formData.get('name') || '';
      const phone = formData.get('phone') || '';
      const store = formData.get('store') || '';
      const message = formData.get('message') || '';

      try {
        const res = await fetch("{{ route('contact.submit') }}", {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}",
            'Accept': 'application/json'
          },
          body: formData
        });

        const data = await res.json();

        cMsg.classList.remove('hidden', 'text-red-600', 'text-green-700');
        cMsg.classList.add('text-green-700');
        cMsg.textContent = 'Thank you! Redirecting to WhatsApp desk...';

        const waText = encodeURIComponent(`Hi Tenderfold,\nName: ${name}\nPhone: ${phone}\nCounter: ${store}\nMessage: ${message}`);
        const waUrl = `https://wa.me/{{ preg_replace('/[^0-9]/', '', $site['whatsapp_number'] ?? '919582466445') }}?text=${waText}`;

        setTimeout(() => {
          window.open(waUrl, '_blank');
          cForm.reset();
          cBtn.disabled = false;
          cBtn.textContent = originalText;
        }, 1200);

      } catch (err) {
        cMsg.classList.remove('hidden', 'text-green-700');
        cMsg.classList.add('text-red-600');
        cMsg.textContent = 'Something went wrong. Opening WhatsApp directly...';
        
        const waText = encodeURIComponent(`Hi Tenderfold,\nName: ${name}\nPhone: ${phone}\nCounter: ${store}\nMessage: ${message}`);
        const waUrl = `https://wa.me/{{ preg_replace('/[^0-9]/', '', $site['whatsapp_number'] ?? '919582466445') }}?text=${waText}`;
        window.open(waUrl, '_blank');
        
        cBtn.disabled = false;
        cBtn.textContent = originalText;
      }
    });
  }
});
</script>
@endpush
