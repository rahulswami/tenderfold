@php
    $site = \App\Models\SiteParameter::all()->pluck('value', 'id')->toArray();
    $navServices = \App\Models\Service::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get();
@endphp
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  @php
    $defaultTitle = ($site['site_name'] ?? 'Tenderfold') . ' | ' . ($site['site_tagline'] ?? 'Freshness in Every Single Fold - Premium Laundry & Dry Cleaner in Gurgaon');
    $defaultDesc = $site['meta_description'] ?? 'Tenderfold is a premium laundry and dry cleaner in Gurgaon. Doorstep pickup for wash & fold, steam ironing, dry cleaning and specialized fabric care. Call ' . ($site['phone'] ?? '+91 95824 66445') . '.';
    $defaultKeywords = $site['meta_keywords'] ?? 'laundry gurgaon, dry cleaning gurgaon, wash and fold, steam ironing, shoe cleaning, carpet cleaning, sector 49, sector 83, tenderfold';
    $canonicalUrl = View::hasSection('canonical') ? View::getSection('canonical') : url()->current();
    $pageTitle = View::hasSection('title') ? View::getSection('title') : $defaultTitle;
    $pageDesc = View::hasSection('meta_description') ? View::getSection('meta_description') : $defaultDesc;
    $ogImage = View::hasSection('og_image') ? View::getSection('og_image') : asset($site['og_image_path'] ?? $site['logo_path'] ?? 'assets/logo.png');
    $gtagId = $site['google_tag_id'] ?? $site['google_analytics_id'] ?? $site['ga_tracking_id'] ?? env('GOOGLE_TAG_ID') ?? 'G-TENDERFOLD';
  @endphp

  <title>@yield('title', $defaultTitle)</title>
  <meta name="description" content="@yield('meta_description', $defaultDesc)">
  <meta name="keywords" content="@yield('meta_keywords', $defaultKeywords)">
  <meta name="theme-color" content="#143572">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="robots" content="index, follow">

  <!-- Canonical URL -->
  <link rel="canonical" href="{{ $canonicalUrl }}">

  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="@yield('og_type', 'website')">
  <meta property="og:url" content="{{ $canonicalUrl }}">
  <meta property="og:title" content="{{ $pageTitle }}">
  <meta property="og:description" content="{{ $pageDesc }}">
  <meta property="og:image" content="{{ $ogImage }}">
  <meta property="og:site_name" content="{{ $site['site_name'] ?? 'Tenderfold' }}">
  <meta property="og:locale" content="en_IN">

  <!-- Twitter Cards -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:url" content="{{ $canonicalUrl }}">
  <meta name="twitter:title" content="{{ $pageTitle }}">
  <meta name="twitter:description" content="{{ $pageDesc }}">
  <meta name="twitter:image" content="{{ $ogImage }}">
  <meta name="twitter:site" content="{{ $site['twitter_handle'] ?? '@tenderfold' }}">

  <!-- Google Analytics / Google Tag (gtag.js) -->
  @if(!empty($gtagId))
  <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gtagId }}"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', '{{ $gtagId }}');
  </script>
  @endif

  @if(!empty($site['header_scripts']))
    {!! $site['header_scripts'] !!}
  @endif

  <script>document.documentElement.classList.add("js");</script>
  <link rel="icon" href="{{ asset($site['favicon_path'] ?? 'assets/logo.png') }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&family=Nunito:wght@700;800&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            navy: "#143572",
            "navy-dark": "#0C2454",
            "navy-mid": "#1E4A96",
            gold: "#F5B400",
            "gold-deep": "#E0A400",
            cream: "#F4F7FB",
            ink: "#1A2744",
            mute: "#5C6A82"
          },
          fontFamily: {
            display: ["Nunito", "ui-sans-serif", "system-ui", "sans-serif"],
            sans: ["Manrope", "ui-sans-serif", "system-ui", "sans-serif"]
          },
          boxShadow: {
            card: "0 10px 40px rgba(20, 53, 114, 0.08)",
            lift: "0 22px 50px rgba(20, 53, 114, 0.16)"
          }
        }
      }
    };
  </script>
  <style>
    @keyframes fadeUp {
      from { opacity: 0; translate: 0 28px; }
      to { opacity: 1; translate: 0 0; }
    }
    .js .reveal {
      animation: fadeUp 0.75s ease both;
      animation-play-state: paused;
    }
    .js .reveal.is-in { animation-play-state: running; }
    summary::-webkit-details-marker { display: none; }
    .hero-swiper .swiper-pagination { position: static; display: flex; gap: 8px; justify-content: flex-start; }
    .hero-swiper .swiper-pagination-bullet {
      width: 10px; height: 10px; margin: 0 !important;
      background: #143572; opacity: 0.25; transition: width .3s ease, opacity .3s ease, background-color .3s ease;
    }
    .hero-swiper .swiper-pagination-bullet-active {
      width: 28px; border-radius: 999px; background: #F5B400; opacity: 1;
    }
    .modal-scroll {
      scrollbar-width: thin;
      scrollbar-color: #143572 transparent;
      overscroll-behavior: contain;
    }
    .modal-scroll::-webkit-scrollbar {
      width: 10px;
    }
    .modal-scroll::-webkit-scrollbar-track {
      background: transparent;
      margin: 18px 0;
    }
    .modal-scroll::-webkit-scrollbar-thumb {
      background-color: rgba(20, 53, 114, 0.45);
      border: 3px solid transparent;
      border-radius: 999px;
      background-clip: padding-box;
    }
    .modal-scroll::-webkit-scrollbar-thumb:hover {
      background-color: #F5B400;
      background-clip: padding-box;
    }
    .services-swiper .swiper-slide { height: auto; display: flex; }
    .services-swiper .swiper-slide > article { width: 100%; }
    @media (prefers-reduced-motion: reduce) {
      .reveal { animation: none; opacity: 1; }
    }
  </style>
  @stack('styles')
  @yield('schema')
</head>
<body class="overflow-x-hidden bg-white font-sans text-ink antialiased">
  <a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-[80] focus:rounded-full focus:bg-gold focus:px-4 focus:py-2 focus:font-bold focus:text-navy">Skip to content</a>

  <header id="siteHeader" class="sticky top-0 z-50 bg-white">
    <div class="border-b border-navy/10">
      <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-x-6 gap-y-2 px-4 py-2.5 text-[13px] font-semibold text-ink sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-center gap-x-5 gap-y-2">
          <a href="tel:{{ preg_replace('/[^0-9+]/', '', $site['phone'] ?? '+919582466445') }}" class="inline-flex items-center gap-2 transition hover:text-navy">
            <span class="flex h-7 w-7 items-center justify-center rounded-full bg-gold text-navy" aria-hidden="true">
              <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M6.5 3.5h3L11 8l-2 1.5a12 12 0 0 0 5.5 5.5L16 13l4.5 1.5v3A2 2 0 0 1 18.5 19 15 15 0 0 1 5 5.5a2 2 0 0 1 1.5-2Z" stroke-linejoin="round"/></svg>
            </span>
            Phone: {{ $site['phone_display'] ?? $site['phone'] ?? '+91 95824 66445' }}
          </a>
          <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $site['whatsapp_number'] ?? '919582466445') }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 transition hover:text-navy">
            <span class="flex h-7 w-7 items-center justify-center rounded-full bg-gold text-navy" aria-hidden="true">
              <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2C6.58 2 2.15 6.4 2.15 11.83c0 1.74.46 3.44 1.34 4.94L2 22l5.39-1.4a10.1 10.1 0 0 0 4.65 1.12h.01c5.46 0 9.89-4.4 9.89-9.84C21.94 6.4 17.5 2 12.04 2Zm5.76 14.04c-.24.68-1.4 1.3-1.94 1.38-.5.08-1.12.11-1.81-.11-.42-.14-.95-.31-1.64-.6-2.88-1.24-4.76-4.14-4.9-4.33-.14-.19-1.16-1.54-1.16-2.94s.73-2.08 1-2.37c.24-.28.64-.41 1.02-.41h.37c.12 0 .28-.04.44.34.16.4.56 1.38.61 1.48.06.1.1.22.02.36-.08.14-.12.22-.24.34l-.36.42c-.12.12-.24.25-.1.48.14.24.62 1.02 1.33 1.66.92.82 1.7 1.08 1.94 1.2.24.12.38.1.52-.06.14-.16.6-.7.76-.94.16-.24.32-.2.54-.12.22.08 1.4.66 1.64.78.24.12.4.18.46.28.06.1.06.6-.18 1.28Z"/></svg>
            </span>
            WhatsApp
          </a>
        </div>
        <div class="hidden items-center gap-5 md:flex">
          <p class="inline-flex items-center gap-2">
            <span class="flex h-7 w-7 items-center justify-center rounded-full bg-gold text-navy" aria-hidden="true">
              <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="8"/><path d="M12 8v4l2.5 2" stroke-linecap="round"/></svg>
            </span>
            Free pickup &amp; delivery
          </p>
          <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 transition hover:text-navy">
            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-gold text-navy" aria-hidden="true">
              <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 21s7-6.2 7-11a7 7 0 1 0-14 0c0 4.8 7 11 7 11Z"/><circle cx="12" cy="10" r="2.2"/></svg>
            </span>
            Sector 49 &amp; Sector 83, Gurgaon
          </a>
          @if(!empty($site['social_instagram']) || !empty($site['social_facebook']) || !empty($site['social_twitter']) || !empty($site['social_linkedin']) || !empty($site['social_youtube']) || !empty($site['social_practo']))
            <div class="flex items-center gap-2 border-l border-navy/15 pl-4">
              @if(!empty($site['social_instagram']))
                <a href="{{ $site['social_instagram'] }}" target="_blank" rel="noopener noreferrer" class="flex h-7 w-7 items-center justify-center rounded-full bg-navy/5 text-navy transition hover:bg-gradient-to-tr hover:from-[#f09433] hover:via-[#dc2743] hover:to-[#bc1888] hover:text-white" aria-label="Instagram" title="Instagram">
                  @if(!empty($site['social_instagram_svg']))
                    {!! $site['social_instagram_svg'] !!}
                  @else
                    <svg class="h-3.5 w-3.5 fill-current" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd"/></svg>
                  @endif
                </a>
              @endif
              @if(!empty($site['social_facebook']))
                <a href="{{ $site['social_facebook'] }}" target="_blank" rel="noopener noreferrer" class="flex h-7 w-7 items-center justify-center rounded-full bg-navy/5 text-navy transition hover:bg-[#1877F2] hover:text-white" aria-label="Facebook" title="Facebook">
                  @if(!empty($site['social_facebook_svg']))
                    {!! $site['social_facebook_svg'] !!}
                  @else
                    <svg class="h-3.5 w-3.5 fill-current" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"/></svg>
                  @endif
                </a>
              @endif
              @if(!empty($site['social_twitter']))
                <a href="{{ $site['social_twitter'] }}" target="_blank" rel="noopener noreferrer" class="flex h-7 w-7 items-center justify-center rounded-full bg-navy/5 text-navy transition hover:bg-black hover:text-white" aria-label="X (Twitter)" title="X / Twitter">
                  @if(!empty($site['social_twitter_svg']))
                    {!! $site['social_twitter_svg'] !!}
                  @else
                    <svg class="h-3 w-3 fill-current" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                  @endif
                </a>
              @endif
              @if(!empty($site['social_practo']))
                <a href="{{ $site['social_practo'] }}" target="_blank" rel="noopener noreferrer" class="flex h-7 w-7 items-center justify-center rounded-full bg-navy/5 text-navy transition hover:bg-[#4285F4] hover:text-white" aria-label="Google Maps" title="Google Maps">
                  @if(!empty($site['social_practo_svg']))
                    {!! $site['social_practo_svg'] !!}
                  @else
                    <svg class="h-3.5 w-3.5 fill-current" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5z"/></svg>
                  @endif
                </a>
              @endif
            </div>
          @endif
        </div>
      </div>
    </div>

    <div class="mx-auto flex max-w-7xl items-center justify-between gap-3 px-4 py-3 sm:px-6 lg:gap-5 lg:px-8">
      <a href="{{ route('home') }}" class="shrink-0" aria-label="Tender Fold home">
        <img src="{{ asset($site['logo_path'] ?? 'assets/logo.png') }}" alt="{{ $site['site_name'] ?? 'Tender Fold' }} — Freshness in every fold" class="h-11 w-auto sm:h-[3.4rem]" width="685" height="193">
      </a>

      <div class="flex min-w-0 flex-1 items-center justify-end gap-2 sm:gap-3">
        <div class="flex min-w-0 items-center rounded-full bg-navy py-1.5 pl-1.5 pr-1.5 text-white lg:px-5">
          <nav class="hidden items-center lg:flex" aria-label="Primary">
            <a href="{{ route('home') }}" class="rounded-full px-3 py-2 text-sm font-semibold transition hover:text-gold xl:px-3.5 {{ request()->routeIs('home') ? 'text-gold' : '' }}">Home</a>
            <a href="{{ route('about') }}" class="rounded-full px-3 py-2 text-sm font-semibold transition hover:text-gold xl:px-3.5 {{ request()->routeIs('about') ? 'text-gold' : '' }}">About</a>
            <div class="relative" id="servicesMenu">
              <button type="button" id="servicesBtn" class="inline-flex items-center gap-1 rounded-full px-3 py-2 text-sm font-semibold transition hover:text-gold xl:px-3.5 {{ request()->routeIs('services*') ? 'text-gold' : '' }}" aria-expanded="false" aria-haspopup="true" aria-controls="servicesDropdown">
                Services
                <svg id="servicesChevron" class="h-3.5 w-3.5 transition duration-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true"><path d="M6 9l6 6 6-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
              </button>
              <div id="servicesDropdown" class="absolute left-0 top-full z-50 hidden pt-4">
                <div class="min-w-[14.5rem] rounded-2xl bg-white py-2.5 shadow-lift" role="menu" aria-label="Services">
                  @foreach($navServices as $nService)
                    <a role="menuitem" href="{{ route('services.show', $nService->slug) }}" class="block px-5 py-2.5 text-[15px] font-semibold text-navy transition hover:bg-cream">{{ $nService->service_name }}</a>
                  @endforeach
                  <a role="menuitem" href="{{ route('services') }}" class="mt-1 block border-t border-navy/10 px-5 py-2.5 text-[15px] font-semibold text-navy transition hover:bg-cream">All services</a>
                </div>
              </div>
            </div>
            <a href="{{ route('pricing') }}" class="rounded-full px-3 py-2 text-sm font-semibold transition hover:text-gold xl:px-3.5 {{ request()->routeIs('pricing') ? 'text-gold' : '' }}">Pricing</a>
            <a href="{{ route('blog') }}" class="rounded-full px-3 py-2 text-sm font-semibold transition hover:text-gold xl:px-3.5 {{ request()->routeIs('blog*') ? 'text-gold' : '' }}">Journal</a>
            <a href="{{ route('contact') }}" class="rounded-full px-3 py-2 text-sm font-semibold transition hover:text-gold xl:px-3.5 {{ request()->routeIs('contact') ? 'text-gold' : '' }}">Contact</a>
          </nav>
          <button type="button" id="menuBtn" class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-white transition hover:bg-white/10 lg:hidden" aria-expanded="false" aria-controls="mobileMenu" aria-label="Open menu">
            <svg id="iconOpen" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16" stroke-linecap="round"/></svg>
            <svg id="iconClose" class="hidden h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18" stroke-linecap="round"/></svg>
          </button>
        </div>
        <button type="button" data-open-book class="inline-flex shrink-0 items-center justify-center rounded-full bg-gold px-4 py-3 font-display text-xs font-extrabold uppercase tracking-wide text-navy shadow-md transition duration-300 hover:-translate-y-0.5 hover:bg-gold-deep hover:shadow-lg focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-navy sm:px-6 sm:text-sm">
          Schedule a Pickup
        </button>
      </div>
    </div>

    <div id="mobileMenu" class="hidden border-t border-navy/10 bg-white">
      <nav class="mx-auto grid max-w-7xl gap-1 px-4 py-4 sm:grid-cols-2 sm:px-6 lg:px-8" aria-label="Menu">
        <a href="{{ route('home') }}" class="rounded-xl px-3 py-3 font-semibold text-navy hover:bg-cream {{ request()->routeIs('home') ? 'bg-cream text-gold' : '' }}">Home</a>
        <a href="{{ route('about') }}" class="rounded-xl px-3 py-3 font-semibold text-navy hover:bg-cream {{ request()->routeIs('about') ? 'bg-cream text-gold' : '' }}">About</a>
        <button type="button" id="mobileServicesBtn" class="flex items-center justify-between rounded-xl px-3 py-3 text-left font-semibold text-navy hover:bg-cream sm:col-span-2" aria-expanded="false" aria-controls="mobileServices">
          Services
          <svg id="mobileServicesChevron" class="h-4 w-4 transition duration-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true"><path d="M6 9l6 6 6-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
        <div id="mobileServices" class="hidden sm:col-span-2">
          @foreach($navServices as $nService)
            <a href="{{ route('services.show', $nService->slug) }}" class="block rounded-xl px-6 py-2.5 font-medium text-navy hover:bg-cream">{{ $nService->service_name }}</a>
          @endforeach
          <a href="{{ route('services') }}" class="block rounded-xl px-6 py-2.5 font-semibold text-navy hover:bg-cream">All services</a>
        </div>
        <a href="{{ route('pricing') }}" class="rounded-xl px-3 py-3 font-semibold text-navy hover:bg-cream {{ request()->routeIs('pricing') ? 'bg-cream text-gold' : '' }}">Pricing</a>
        <a href="{{ route('blog') }}" class="rounded-xl px-3 py-3 font-semibold text-navy hover:bg-cream {{ request()->routeIs('blog*') ? 'bg-cream text-gold' : '' }}">Journal</a>
        <a href="{{ route('contact') }}" class="rounded-xl px-3 py-3 font-semibold text-navy hover:bg-cream {{ request()->routeIs('contact') ? 'bg-cream text-gold' : '' }}">Contact</a>
        
        @if(!empty($site['social_instagram']) || !empty($site['social_facebook']) || !empty($site['social_twitter']) || !empty($site['social_linkedin']) || !empty($site['social_youtube']) || !empty($site['social_practo']))
          <div class="mt-2 flex items-center gap-3 border-t border-navy/10 pt-3 px-3 sm:col-span-2">
            <span class="text-xs font-bold uppercase tracking-wider text-mute">Follow Us:</span>
            <div class="flex items-center gap-2">
              @if(!empty($site['social_instagram']))
                <a href="{{ $site['social_instagram'] }}" target="_blank" rel="noopener noreferrer" class="flex h-8 w-8 items-center justify-center rounded-full bg-navy/5 text-navy transition hover:bg-gradient-to-tr hover:from-[#f09433] hover:via-[#dc2743] hover:to-[#bc1888] hover:text-white" aria-label="Instagram">
                  @if(!empty($site['social_instagram_svg']))
                    {!! $site['social_instagram_svg'] !!}
                  @else
                    <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd"/></svg>
                  @endif
                </a>
              @endif
              @if(!empty($site['social_facebook']))
                <a href="{{ $site['social_facebook'] }}" target="_blank" rel="noopener noreferrer" class="flex h-8 w-8 items-center justify-center rounded-full bg-navy/5 text-navy transition hover:bg-[#1877F2] hover:text-white" aria-label="Facebook">
                  @if(!empty($site['social_facebook_svg']))
                    {!! $site['social_facebook_svg'] !!}
                  @else
                    <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"/></svg>
                  @endif
                </a>
              @endif
              @if(!empty($site['social_twitter']))
                <a href="{{ $site['social_twitter'] }}" target="_blank" rel="noopener noreferrer" class="flex h-8 w-8 items-center justify-center rounded-full bg-navy/5 text-navy transition hover:bg-black hover:text-white" aria-label="X (Twitter)">
                  @if(!empty($site['social_twitter_svg']))
                    {!! $site['social_twitter_svg'] !!}
                  @else
                    <svg class="h-3.5 w-3.5 fill-current" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                  @endif
                </a>
              @endif
            </div>
          </div>
        @endif
      </nav>
    </div>
  </header>

  @if(session('success'))
    <div class="bg-emerald-500 text-white text-center py-3 px-4 font-semibold text-sm shadow-md">
      {{ session('success') }}
    </div>
  @endif

  <main id="main">
    @yield('content')
  </main>

  <footer id="contact" class="scroll-mt-36 bg-navy-dark text-white">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 py-14 sm:px-6 md:grid-cols-2 lg:grid-cols-4 lg:px-8">
      <div>
        <img src="{{ asset('assets/logo-light.png') }}" alt="{{ $site['site_name'] ?? 'Tender Fold' }}" class="h-12 w-auto" width="685" height="193">
        <p class="mt-4 max-w-xs text-sm leading-relaxed text-white/70">{{ $site['site_tagline'] ?? 'Premium laundry and dry cleaning in Gurgaon. More than laundry — it is a care routine.' }}</p>
        
        @if(!empty($site['social_instagram']) || !empty($site['social_facebook']) || !empty($site['social_twitter']) || !empty($site['social_linkedin']) || !empty($site['social_youtube']) || !empty($site['social_practo']))
          <div class="mt-6">
            <span class="block text-xs font-extrabold uppercase tracking-wider text-gold">Follow Our Care Journey</span>
            <div class="mt-3 flex flex-wrap items-center gap-2.5">
              @if(!empty($site['social_instagram']))
                <a href="{{ $site['social_instagram'] }}" target="_blank" rel="noopener noreferrer" class="flex h-9 w-9 items-center justify-center rounded-full bg-white/10 text-white transition duration-300 hover:-translate-y-0.5 hover:bg-gradient-to-tr hover:from-[#f09433] hover:via-[#dc2743] hover:to-[#bc1888] hover:text-white hover:shadow-lg" aria-label="Follow Tenderfold on Instagram" title="Instagram">
                  @if(!empty($site['social_instagram_svg']))
                    {!! $site['social_instagram_svg'] !!}
                  @else
                    <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd"/></svg>
                  @endif
                </a>
              @endif
              @if(!empty($site['social_facebook']))
                <a href="{{ $site['social_facebook'] }}" target="_blank" rel="noopener noreferrer" class="flex h-9 w-9 items-center justify-center rounded-full bg-white/10 text-white transition duration-300 hover:-translate-y-0.5 hover:bg-[#1877F2] hover:text-white hover:shadow-lg" aria-label="Follow Tenderfold on Facebook" title="Facebook">
                  @if(!empty($site['social_facebook_svg']))
                    {!! $site['social_facebook_svg'] !!}
                  @else
                    <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"/></svg>
                  @endif
                </a>
              @endif
              @if(!empty($site['social_twitter']))
                <a href="{{ $site['social_twitter'] }}" target="_blank" rel="noopener noreferrer" class="flex h-9 w-9 items-center justify-center rounded-full bg-white/10 text-white transition duration-300 hover:-translate-y-0.5 hover:bg-black hover:text-white hover:shadow-lg" aria-label="Follow Tenderfold on X (Twitter)" title="X / Twitter">
                  @if(!empty($site['social_twitter_svg']))
                    {!! $site['social_twitter_svg'] !!}
                  @else
                    <svg class="h-3.5 w-3.5 fill-current" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                  @endif
                </a>
              @endif
              @if(!empty($site['social_linkedin']))
                <a href="{{ $site['social_linkedin'] }}" target="_blank" rel="noopener noreferrer" class="flex h-9 w-9 items-center justify-center rounded-full bg-white/10 text-white transition duration-300 hover:-translate-y-0.5 hover:bg-[#0A66C2] hover:text-white hover:shadow-lg" aria-label="Follow Tenderfold on LinkedIn" title="LinkedIn">
                  @if(!empty($site['social_linkedin_svg']))
                    {!! $site['social_linkedin_svg'] !!}
                  @else
                    <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.28 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.75M6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37h2.77z" clip-rule="evenodd"/></svg>
                  @endif
                </a>
              @endif
              @if(!empty($site['social_youtube']))
                <a href="{{ $site['social_youtube'] }}" target="_blank" rel="noopener noreferrer" class="flex h-9 w-9 items-center justify-center rounded-full bg-white/10 text-white transition duration-300 hover:-translate-y-0.5 hover:bg-[#FF0000] hover:text-white hover:shadow-lg" aria-label="Subscribe to Tenderfold on YouTube" title="YouTube">
                  @if(!empty($site['social_youtube_svg']))
                    {!! $site['social_youtube_svg'] !!}
                  @else
                    <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                  @endif
                </a>
              @endif
              @if(!empty($site['social_practo']))
                <a href="{{ $site['social_practo'] }}" target="_blank" rel="noopener noreferrer" class="flex h-9 w-9 items-center justify-center rounded-full bg-white/10 text-white transition duration-300 hover:-translate-y-0.5 hover:bg-[#4285F4] hover:text-white hover:shadow-lg" aria-label="View Tenderfold on Google Maps" title="Google Maps / Reviews">
                  @if(!empty($site['social_practo_svg']))
                    {!! $site['social_practo_svg'] !!}
                  @else
                    <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5z"/></svg>
                  @endif
                </a>
              @endif
            </div>
          </div>
        @endif
      </div>

      <div>
        <h2 class="font-display text-sm font-extrabold uppercase tracking-[0.16em] text-gold">Services</h2>
        <ul class="mt-4 space-y-2 text-sm text-white/75">
          @foreach($navServices as $nService)
            <li><a href="{{ route('services.show', $nService->slug) }}" class="transition hover:text-gold">{{ $nService->service_name }}</a></li>
          @endforeach
          <li><a href="{{ route('pricing') }}" class="transition hover:text-gold font-semibold text-gold/90">Rate Card &amp; Pricing</a></li>
        </ul>
      </div>

      <div>
        <h2 class="font-display text-sm font-extrabold uppercase tracking-[0.16em] text-gold">Contact</h2>
        <ul class="mt-4 space-y-3 text-sm text-white/75">
          <li>
            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $site['phone'] ?? '+919582466445') }}" class="font-semibold text-white transition hover:text-gold">{{ $site['phone_display'] ?? $site['phone'] ?? '+91 95824 66445' }}</a>
          </li>
          <li>
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $site['whatsapp_number'] ?? '919582466445') }}" target="_blank" rel="noopener" class="transition hover:text-gold inline-flex items-center gap-1.5">
              <span>WhatsApp a pickup</span>
              <svg class="h-3.5 w-3.5 text-emerald-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12.04 2C6.58 2 2.15 6.4 2.15 11.83c0 1.74.46 3.44 1.34 4.94L2 22l5.39-1.4a10.1 10.1 0 0 0 4.65 1.12h.01c5.46 0 9.89-4.4 9.89-9.84C21.94 6.4 17.5 2 12.04 2Z"/></svg>
            </a>
          </li>
          <li>
            <a href="https://www.google.com/maps/search/?api=1&amp;query=Shop%20No.%2019%2C%201st%20Floor%2C%20B%20Block%20Shopping%20Plaza%2C%20Orchid%20Petals%2C%20South%20City%20II%2C%20Sector%2049%2C%20Gurgaon%2C%20Haryana%20122018" target="_blank" rel="noopener" class="block leading-relaxed transition hover:text-gold">
              <span class="block font-semibold text-white">Orchid Petals</span>
              Shop No. 19, 1st Floor, B Block Shopping Plaza, Orchid Petals, South City II, Sector 49, Gurgaon - 122018
            </a>
          </li>
          <li>
            <a href="https://www.google.com/maps/search/?api=1&amp;query=F-193%20First%20Floor%20Sapphire%2083%20Mall%20Sector%2083%20Gurgaon%20122004" target="_blank" rel="noopener" class="block leading-relaxed transition hover:text-gold">
              <span class="block font-semibold text-white">Sapphire 83 Mall</span>
              F-193, First Floor, Sapphire 83 Mall, Sector 83, Gurgaon - 122004
            </a>
          </li>
          <li>Free pickup &amp; delivery across Gurgaon</li>
        </ul>
      </div>

      <div>
        <h2 class="font-display text-sm font-extrabold uppercase tracking-[0.16em] text-gold">Care notes</h2>
        <p class="mt-4 text-sm text-white/70">Leave an email and we will send pickup windows and fabric tips on WhatsApp.</p>
        <form id="newsForm" class="mt-4 flex flex-col gap-2">
          <label for="newsEmail" class="sr-only">Email address</label>
          <input id="newsEmail" name="email" type="email" required placeholder="Email address" autocomplete="email" class="w-full rounded-full border border-white/15 bg-white/10 px-4 py-3 text-sm text-white placeholder:text-white/45 focus:border-gold focus:outline-none">
          <button type="submit" class="inline-flex items-center justify-center rounded-full bg-gold px-5 py-3 font-display text-sm font-extrabold text-navy transition duration-300 hover:-translate-y-0.5 hover:bg-gold-deep">Join the list</button>
          <p id="newsMsg" class="hidden text-sm text-gold" role="status"></p>
        </form>
      </div>
    </div>
    <div class="border-t border-white/10">
      <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 pb-20 pt-5 text-xs text-white/55 sm:flex-row sm:items-center sm:justify-between sm:px-6 sm:pb-5 lg:px-8">
        <p>© <span id="year">{{ date('Y') }}</span> {{ $site['site_name'] ?? 'Tender Fold' }}. Freshness in every fold.</p>
        @if(!empty($site['social_instagram']) || !empty($site['social_facebook']) || !empty($site['social_twitter']) || !empty($site['social_linkedin']) || !empty($site['social_youtube']) || !empty($site['social_practo']))
          <div class="flex items-center gap-3">
            <span class="text-white/40">Connect:</span>
            @if(!empty($site['social_instagram']))
              <a href="{{ $site['social_instagram'] }}" target="_blank" rel="noopener noreferrer" class="text-white/70 transition hover:text-gold" aria-label="Instagram">Instagram</a>
            @endif
            @if(!empty($site['social_facebook']))
              <a href="{{ $site['social_facebook'] }}" target="_blank" rel="noopener noreferrer" class="text-white/70 transition hover:text-gold" aria-label="Facebook">Facebook</a>
            @endif
            @if(!empty($site['social_twitter']))
              <a href="{{ $site['social_twitter'] }}" target="_blank" rel="noopener noreferrer" class="text-white/70 transition hover:text-gold" aria-label="Twitter">Twitter</a>
            @endif
            @if(!empty($site['social_practo']))
              <a href="{{ $site['social_practo'] }}" target="_blank" rel="noopener noreferrer" class="text-white/70 transition hover:text-gold" aria-label="Google Maps">Google Maps</a>
            @endif
          </div>
        @endif
        <p>Cleaner · Fresher · Happier you</p>
      </div>
    </div>
  </footer>

  <!-- Floating WhatsApp Action -->
  <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $site['whatsapp_number'] ?? '919582466445') }}" target="_blank" rel="noopener" class="fixed bottom-5 right-5 z-40 flex h-14 w-14 items-center justify-center rounded-full bg-[#25D366] text-white shadow-lift transition duration-300 hover:-translate-y-0.5 hover:scale-105" aria-label="Chat with Tender Fold on WhatsApp">
    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12.04 2C6.58 2 2.15 6.4 2.15 11.83c0 1.74.46 3.44 1.34 4.94L2 22l5.39-1.4a10.1 10.1 0 0 0 4.65 1.12h.01c5.46 0 9.89-4.4 9.89-9.84C21.94 6.4 17.5 2 12.04 2Zm5.76 14.04c-.24.68-1.4 1.3-1.94 1.38-.5.08-1.12.11-1.81-.11-.42-.14-.95-.31-1.64-.6-2.88-1.24-4.76-4.14-4.9-4.33-.14-.19-1.16-1.54-1.16-2.94s.73-2.08 1-2.37c.24-.28.64-.41 1.02-.41h.37c.12 0 .28-.04.44.34.16.4.56 1.38.61 1.48.06.1.1.22.02.36-.08.14-.12.22-.24.34l-.36.42c-.12.12-.24.25-.1.48.14.24.62 1.02 1.33 1.66.92.82 1.7 1.08 1.94 1.2.24.12.38.1.52-.06.14-.16.6-.7.76-.94.16-.24.32-.2.54-.12.22.08 1.4.66 1.64.78.24.12.4.18.46.28.06.1.06.6-.18 1.28Z"/></svg>
  </a>

  <!-- CRM Pickup Scheduler Modal & Integration -->
  <style>
    body.m-o { overflow: hidden; }
    #o-pickup-scheduler-us0z1bz0a {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(11, 27, 44, 0.75);
      backdrop-filter: blur(4px);
      -webkit-backdrop-filter: blur(4px);
      z-index: 999;
    }
    #c-pickup-scheduler-us0z1bz0a {
      display: none;
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 90%;
      max-width: 900px;
      height: 90vh;
      z-index: 1000;
    }
    #i-pickup-scheduler-us0z1bz0a {
      display: flex;
      flex-direction: column;
      width: 100%;
      height: 100%;
      background: #fff;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.35);
    }
    #h-pickup-scheduler-us0z1bz0a {
      display: flex;
      justify-content: flex-end;
      padding: 12px 16px;
      border-bottom: 1px solid #eee;
      background: #fff;
      z-index: 10;
    }
    #x-pickup-scheduler-us0z1bz0a {
      border: 0;
      background: #f5f5f5;
      border-radius: 50%;
      width: 32px;
      height: 32px;
      font-size: 18px;
      cursor: pointer;
      transition: .2s;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #0b1b2c;
    }
    #x-pickup-scheduler-us0z1bz0a:hover {
      background: #e0e0e0;
    }
    #f-pickup-scheduler-us0z1bz0a {
      flex: 1;
      width: calc(100% + 20px);
      margin-right: -20px;
      border: 0;
      scrollbar-width: none;
    }
    #f-pickup-scheduler-us0z1bz0a::-webkit-scrollbar {
      display: none;
    }
    #o-pickup-scheduler-us0z1bz0a.s,
    #c-pickup-scheduler-us0z1bz0a.s {
      display: block;
    }
    @media (max-width: 768px) {
      #c-pickup-scheduler-us0z1bz0a {
        width: 95%;
        height: 85vh;
      }
      #f-pickup-scheduler-us0z1bz0a {
        width: 100%;
        margin-right: 0;
      }
    }
  </style>

  <div id="o-pickup-scheduler-us0z1bz0a"></div>
  <div id="c-pickup-scheduler-us0z1bz0a" role="dialog" aria-modal="true" aria-label="Schedule Pickup">
    <div id="i-pickup-scheduler-us0z1bz0a">
      <div id="h-pickup-scheduler-us0z1bz0a">
        <button id="x-pickup-scheduler-us0z1bz0a" type="button" aria-label="Close">×</button>
      </div>
      <iframe id="f-pickup-scheduler-us0z1bz0a" title="Tender Fold Pickup Scheduler" src="https://pickup-scheduler.quickdrycleaning.com/en/TenderFold/pickup"></iframe>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <script src="{{ asset('assets/site.js') }}"></script>
  <script>
    !function() {
      const d = document,
            o = d.getElementById("o-pickup-scheduler-us0z1bz0a"),
            c = d.getElementById("c-pickup-scheduler-us0z1bz0a"),
            x = d.getElementById("x-pickup-scheduler-us0z1bz0a"),
            f = d.getElementById("f-pickup-scheduler-us0z1bz0a");

      if (!o || !c || !x || !f) return;

      const openModal = function(e) {
        if (e && e.preventDefault) e.preventDefault();
        if (!f.src || f.src === "about:blank") {
          f.src = "https://pickup-scheduler.quickdrycleaning.com/en/TenderFold/pickup";
        }
        o.classList.add("s");
        c.classList.add("s");
        d.body.classList.add("m-o");
      };

      const closeModal = function() {
        o.classList.remove("s");
        c.classList.remove("s");
        d.body.classList.remove("m-o");
      };

      window.openPickupScheduler = openModal;
      window.closePickupScheduler = closeModal;

      x.onclick = closeModal;
      o.onclick = closeModal;
      d.onkeydown = function(e) {
        if (e.key === "Escape" && c.classList.contains("s")) closeModal();
      };

      function bindTriggers() {
        d.querySelectorAll('[data-open-book], [data-open-pickup], #pickup-scheduler-us0z1bz0a, a[href="#bookModal"]').forEach(function(btn) {
          if (!btn._crmBound) {
            btn._crmBound = true;
            btn.addEventListener('click', openModal);
          }
        });
      }

      bindTriggers();
      d.addEventListener('DOMContentLoaded', bindTriggers);

      let l = 0;
      f.onload = function() {
        if (++l > 1) {
          f.onload = null;
          closeModal();
          window.location.href = "{{ route('thank-you') }}";
          f.src = "about:blank";
        }
      };
    }();
  </script>
  @stack('scripts')
</body>
</html>
