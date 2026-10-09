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
        <p class="mt-5 text-xs font-bold uppercase tracking-[0.18em] text-gold-deep">Two Gurgaon counters · Dedicated Support</p>
        <h1 class="mt-3 font-display text-4xl font-extrabold text-navy sm:text-5xl">Call, WhatsApp, or ask for a pickup.</h1>
        <p class="mt-4 text-lg text-mute">Reach our Orchid Petals (Sec 49) store at <a href="tel:+919582466445" class="font-semibold text-navy hover:text-gold-deep transition">095824 66445</a> or Sapphire 83 Mall (Sec 83) at <a href="tel:+919899622294" class="font-semibold text-navy hover:text-gold-deep transition">098996 22294</a>. A pickup request opens WhatsApp so the team can confirm a window.</p>
        <div class="mt-8 flex flex-col gap-3 sm:flex-row">
          <button type="button" data-open-book class="inline-flex items-center justify-center rounded-full bg-gold px-6 py-3.5 font-display text-sm font-extrabold text-navy transition duration-300 hover:-translate-y-0.5 hover:bg-gold-deep">Schedule a Pickup</button>
          <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $site['whatsapp_number'] ?? '919582466445') }}?text={{ urlencode('Hi Tenderfold, I have a query about laundry pickup & services in Gurgaon.') }}" target="_blank" rel="noopener" class="inline-flex items-center justify-center rounded-full border-2 border-navy/15 bg-white px-6 py-3.5 font-display text-sm font-extrabold text-navy transition duration-300 hover:-translate-y-0.5 hover:border-navy hover:bg-navy hover:text-white">WhatsApp the desk</a>
        </div>
      </div>

      <div class="mt-12 grid gap-6 lg:grid-cols-2">
        <!-- Sector 49 Card -->
        <article class="reveal rounded-3xl bg-white p-7 shadow-card">
          <p class="text-xs font-bold uppercase tracking-[0.16em] text-gold-deep">Sector 49 Counter</p>
          <h2 class="mt-2 font-display text-2xl font-extrabold text-navy">Orchid Petals</h2>
          <p class="mt-3 text-sm leading-relaxed text-mute">Shop No. 19, 1st Floor, B Block Shopping Plaza, Orchid Petals, South City II, Sector 49, Gurgaon, Haryana 122018</p>
          <div class="mt-4 flex flex-wrap items-center gap-4 text-sm font-semibold">
            <a href="tel:+919582466445" class="inline-flex items-center gap-1.5 text-navy transition hover:text-gold-deep">
              <svg class="h-4 w-4 text-gold-deep" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
              <span>095824 66445</span>
            </a>
            <a href="https://share.google/SXSjDFUalRginesFu" target="_blank" rel="noopener noreferrer" class="inline-flex font-display text-sm font-extrabold text-navy transition hover:text-gold-deep">Get directions →</a>
          </div>
        </article>

        <!-- Sector 83 Card -->
        <article class="reveal rounded-3xl bg-white p-7 shadow-card" style="animation-delay:80ms">
          <p class="text-xs font-bold uppercase tracking-[0.16em] text-gold-deep">Sector 83 Counter</p>
          <h2 class="mt-2 font-display text-2xl font-extrabold text-navy">Sapphire 83 Mall</h2>
          <p class="mt-3 text-sm leading-relaxed text-mute">F-193, First Floor, Sapphire 83 Mall, Sector 83, Gurgaon, Haryana 122004</p>
          <div class="mt-4 flex flex-wrap items-center gap-4 text-sm font-semibold">
            <a href="tel:+919899622294" class="inline-flex items-center gap-1.5 text-navy transition hover:text-gold-deep">
              <svg class="h-4 w-4 text-gold-deep" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
              <span>098996 22294</span>
            </a>
            <a href="https://share.google/UZlhkLsEqN9ICLwTa" target="_blank" rel="noopener noreferrer" class="inline-flex font-display text-sm font-extrabold text-navy transition hover:text-gold-deep">Get directions →</a>
          </div>
        </article>
      </div>

      <div class="reveal mt-6 grid gap-4 rounded-3xl bg-navy p-6 text-white sm:grid-cols-2 lg:grid-cols-4 sm:p-8">
        <a href="tel:+919582466445" class="rounded-2xl bg-white/10 px-5 py-4 transition hover:bg-white/15">
          <p class="text-xs font-bold uppercase tracking-wider text-gold">Orchid Petals (Sec 49)</p>
          <p class="mt-1 font-display text-lg font-extrabold">095824 66445</p>
        </a>
        <a href="tel:+919899622294" class="rounded-2xl bg-white/10 px-5 py-4 transition hover:bg-white/15">
          <p class="text-xs font-bold uppercase tracking-wider text-gold">Sapphire 83 (Sec 83)</p>
          <p class="mt-1 font-display text-lg font-extrabold">098996 22294</p>
        </a>
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $site['whatsapp_number'] ?? '919582466445') }}" target="_blank" rel="noopener" class="rounded-2xl bg-white/10 px-5 py-4 transition hover:bg-white/15">
          <p class="text-xs font-bold uppercase tracking-wider text-gold">WhatsApp Desk</p>
          <p class="mt-1 font-display text-lg font-extrabold">Message the desk</p>
        </a>
        <div class="rounded-2xl bg-white/10 px-5 py-4">
          <span class="block text-xs font-bold uppercase tracking-wider text-gold">Pickup &amp; Delivery</span>
          <span class="mt-1 block font-display text-lg font-extrabold">Free across Gurgaon</span>
        </div>
      </div>

      @if(!empty($site['social_instagram']) || !empty($site['social_facebook']) || !empty($site['social_twitter']) || !empty($site['social_linkedin']) || !empty($site['social_youtube']) || !empty($site['social_practo']))
        <div class="reveal mt-6 flex flex-wrap items-center justify-between gap-4 rounded-3xl bg-white p-6 shadow-card sm:p-7">
          <div>
            <p class="text-xs font-bold uppercase tracking-[0.16em] text-gold-deep">Social Channels</p>
            <h3 class="mt-1 font-display text-xl font-extrabold text-navy">Follow Tenderfold on Social Media</h3>
            <p class="text-sm text-mute mt-0.5">Stay updated with fabric care tips, exclusive discounts & behind-the-scenes.</p>
          </div>
          <div class="flex flex-wrap items-center gap-3">
            @if(!empty($site['social_instagram']))
              <a href="{{ $site['social_instagram'] }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-full bg-gradient-to-tr from-[#f09433] via-[#dc2743] to-[#bc1888] px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:opacity-95 hover:shadow-md" title="Instagram">
                <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd"/></svg>
                <span>Instagram</span>
              </a>
            @endif
            @if(!empty($site['social_facebook']))
              <a href="{{ $site['social_facebook'] }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-full bg-[#1877F2] px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:opacity-95 hover:shadow-md" title="Facebook">
                <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"/></svg>
                <span>Facebook</span>
              </a>
            @endif
            @if(!empty($site['social_twitter']))
              <a href="{{ $site['social_twitter'] }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-full bg-black px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:opacity-95 hover:shadow-md" title="Twitter">
                <svg class="h-3.5 w-3.5 fill-current" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                <span>X (Twitter)</span>
              </a>
            @endif
            @if(!empty($site['social_practo']))
              <a href="{{ $site['social_practo'] }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-full bg-[#4285F4] px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:opacity-95 hover:shadow-md" title="Google Maps">
                <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5z"/></svg>
                <span>Google Maps</span>
              </a>
            @endif
          </div>
        </div>
      @endif
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
