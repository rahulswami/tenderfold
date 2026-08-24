<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0A2540">
    <title>@yield('title', ($site['meta_title'] ?? 'SumitZ Orthopaedic & Physiotherapy Clinic in Baner & Hinjewadi, Pune'))</title>
    <meta name="description" content="@yield('meta_description', ($site['meta_description'] ?? 'SumitZ Orthopaedic & Physiotherapy Clinic is Pune\'s premier clinic in Baner & Hinjewadi. Led by Dr. Sumit, we offer expert joint, spine, fracture and sports injury care.'))">
    <meta name="keywords" content="@yield('meta_keywords', ($site['meta_keywords'] ?? 'orthopedic clinic in baner, physiotherapy clinic in baner, orthopedic doctor in baner, knee replacement pune, dr sumit diwan'))">
    <link rel="canonical" href="{{ url(Request::url()) }}" />

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website" />
    <meta property="og:title" content="@yield('title', ($site['site_name'] ?? 'SumitZ Clinic'))" />
    <meta property="og:description" content="@yield('meta_description', ($site['meta_description'] ?? 'SumitZ Orthopaedic & Physiotherapy Clinic'))" />
    <meta property="og:url" content="{{ url(Request::url()) }}" />
    <meta property="og:site_name" content="{{ $site['site_name'] ?? 'SumitZ Clinic' }}" />
    <meta property="og:image" content="{{ !empty($site['logo_path']) ? site_image($site['logo_path']) : 'https://www.drsumitz.com/public/uploads/gallery/614487085_25_06_2021_01_19_55.jpg' }}" />

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="@yield('title', ($site['site_name'] ?? 'SumitZ Clinic'))" />
    <meta name="twitter:description" content="@yield('meta_description', ($site['meta_description'] ?? 'SumitZ Orthopaedic & Physiotherapy Clinic'))" />
    <meta name="twitter:image" content="{{ !empty($site['logo_path']) ? site_image($site['logo_path']) : 'https://www.drsumitz.com/public/uploads/gallery/614487085_25_06_2021_01_19_55.jpg' }}" />

    <!-- Performance & Preconnect -->
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="dns-prefetch" href="//cdn.jsdelivr.net">
    <link rel="dns-prefetch" href="//unpkg.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Manrope:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Dynamic Favicon -->
    @if(!empty($site['favicon_path']))
        <link rel="icon" type="image/webp" href="{{ site_image($site['favicon_path']) }}">
    @else
        <link rel="icon" type="image/x-icon" href="/favicon.ico">
    @endif

    <link rel="preload" as="style" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    @yield('styles')

    <!-- Google Tag (gtag.js) -->
    @if(!empty($site['google_tag_id']))
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $site['google_tag_id'] }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ $site['google_tag_id'] }}');
        </script>
    @endif

    <!-- Custom Header Tracking Scripts -->
    @if(!empty($site['header_scripts']))
        {!! $site['header_scripts'] !!}
    @endif

    <!-- Structured SEO Schemas -->
    @yield('schema')

    @if(!empty($site['recaptcha_enabled']) && $site['recaptcha_enabled'] == '1' && !empty($site['recaptcha_site_key']))
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif

    <style>
        /* Floating Chat Support Widget */
        .chat-widget-btn {
            position: fixed;
            bottom: 24px;
            right: 24px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: var(--brand-blue);
            color: #fff;
            box-shadow: 0 8px 24px rgba(0, 133, 200, 0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 2500;
            transition: transform 0.3s ease, background-color 0.3s ease;
            border: 2px solid #fff;
        }
        .chat-widget-btn:hover {
            transform: scale(1.08);
            background-color: var(--brand-blue-hover);
        }
        .chat-widget-box {
            position: fixed;
            bottom: 96px;
            right: 24px;
            width: 360px;
            max-width: calc(100vw - 32px);
            height: 480px;
            max-height: calc(100vh - 120px);
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 16px 40px rgba(10, 37, 64, 0.2);
            border: 1px solid var(--border-color);
            z-index: 2500;
            display: none;
            flex-direction: column;
            overflow: hidden;
        }
        .chat-header {
            background-color: var(--primary-navy);
            color: #fff;
            padding: 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .chat-body {
            flex-grow: 1;
            padding: 16px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 12px;
            background-color: var(--bg-surface);
        }
        .chat-msg {
            max-width: 80%;
            padding: 10px 14px;
            border-radius: 14px;
            font-size: 14px;
            line-height: 1.5;
        }
        .chat-msg.visitor {
            align-self: flex-end;
            background-color: var(--brand-blue);
            color: #fff;
            border-bottom-right-radius: 2px;
        }
        .chat-msg.admin {
            align-self: flex-start;
            background-color: #fff;
            color: var(--heading-navy);
            border: 1px solid var(--border-color);
            border-bottom-left-radius: 2px;
        }
        .chat-footer {
            padding: 12px 16px;
            background: #fff;
            border-top: 1px solid var(--border-color);
            display: flex;
            gap: 8px;
        }
        @media (max-width: 991px) {
            .chat-widget-btn { bottom: 80px; right: 16px; width: 52px; height: 52px; }
            .chat-widget-box { bottom: 140px; right: 16px; }
        }
    </style>
</head>
<body>
    <a href="#main-content" class="skip-to-content">Skip to main content</a>

    <!-- Top Utility Bar -->
    <div class="top-bar">
        <div class="container top-bar-inner">
            <div class="top-bar-info">
                <div class="top-bar-item">
                    <i data-lucide="clock"></i>
                    <span>{{ $site['clinic_timings'] ?? 'Mon - Sat: 9:00 AM - 9:00 PM | Sun: By Appointment' }}</span>
                </div>
                <div class="top-bar-item">
                    <i data-lucide="map-pin"></i>
                    <span>{{ $site['clinic_locations_short'] ?? 'Baner & Hinjewadi, Pune' }}</span>
                </div>
            </div>
            <div class="top-bar-actions">
                <a href="tel:{{ $site['phone'] ?? '+919545559434' }}" class="top-bar-link">
                    <i data-lucide="phone"></i> {{ $site['phone_display'] ?? ($site['phone'] ?? '+91 95455 59434') }}
                </a>
                <a href="https://wa.me/{{ $site['whatsapp_number'] ?? '919545559434' }}?text=Hi%20SumitZ%20Clinic,%20I'm%20interested%20in%20booking%20a%20consultation." target="_blank" class="top-bar-link" style="color: var(--brand-blue-soft);">
                    <i data-lucide="message-circle"></i> WhatsApp
                </a>
                @if(!empty($site['social_instagram']))
                    <a href="{{ $site['social_instagram'] }}" target="_blank" class="top-bar-link" title="Instagram @drsumitz" style="color: #e1306c; font-weight: 600;">
                        <i data-lucide="instagram" style="width: 14px; height: 14px;"></i> Instagram
                    </a>
                @endif
                @if(!empty($site['social_facebook']))
                    <a href="{{ $site['social_facebook'] }}" target="_blank" class="top-bar-link" title="Facebook Page" style="color: #1877f2;">
                        <i data-lucide="facebook" style="width: 14px; height: 14px;"></i>
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <header class="header">
        <div class="container nav-container">
            <a href="{{ route('home') }}" class="logo">
                @if(!empty($site['logo_path']))
                    <img src="{{ site_image($site['logo_path']) }}" alt="{{ $site['site_name'] ?? 'SumitZ Clinic' }}" class="logo-img">
                @else
                    <svg class="logo-icon" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 44px; height: 44px;">
                        <circle cx="50" cy="50" r="40" stroke="var(--brand-blue)" stroke-width="5" fill="none"/>
                        <path d="M50 25 V75 M25 50 H75" stroke="var(--brand-blue)" stroke-width="10" stroke-linecap="round"/>
                        <circle cx="50" cy="50" r="8" fill="var(--accent-red)"/>
                    </svg>
                    <div class="logo-text-wrapper">
                        <span class="logo-text">{{ $site['site_name'] ?? 'SumitZ' }}</span>
                        <span class="logo-tagline">{{ $site['site_tagline'] ?? 'ORTHOPAEDIC & PHYSIOTHERAPY CENTER' }}</span>
                    </div>
                @endif
            </a>
            
            <nav aria-label="Main Navigation">
                <ul class="nav-links">
                    <li><a href="{{ route('about') }}" class="nav-item {{ request()->routeIs('about') ? 'active' : '' }}">About Us</a></li>
                    
                    <li class="nav-dropdown-item mega-dropdown">
                        <a href="{{ route('services') }}" class="nav-item {{ request()->routeIs('services') || request()->routeIs('services.*') ? 'active' : '' }}">
                            Services <i data-lucide="chevron-down" style="width: 14px; height: 14px; display: inline-block; vertical-align: middle; margin-left: 2px;"></i>
                        </a>
                        <ul class="dropdown-menu mega-menu">
                            @foreach(($globalServices ?? collect()) as $gs)
                                @php
                                    $gSlug = is_object($gs) ? $gs->slug : (is_array($gs) ? ($gs['slug'] ?? '') : '');
                                    $gName = is_object($gs) ? $gs->service_name : (is_array($gs) ? ($gs['service_name'] ?? '') : $gs);
                                @endphp
                                @if(!empty($gSlug))
                                    <li><a href="{{ route('services.show', $gSlug) }}">{{ $gName }}</a></li>
                                @endif
                            @endforeach
                        </ul>
                    </li>

                    <li class="nav-dropdown-item mega-dropdown">
                        <a href="{{ route('treatments') }}" class="nav-item {{ request()->routeIs('treatments') || request()->routeIs('treatments.*') ? 'active' : '' }}">
                            Treatments <i data-lucide="chevron-down" style="width: 14px; height: 14px; display: inline-block; vertical-align: middle; margin-left: 2px;"></i>
                        </a>
                        <ul class="dropdown-menu mega-menu mega-menu-wide">
                            @foreach(($globalTreatments ?? collect()) as $gt)
                                @php
                                    $gtSlug = is_object($gt) ? $gt->slug : (is_array($gt) ? ($gt['slug'] ?? '') : '');
                                    $gtName = is_object($gt) ? $gt->name : (is_array($gt) ? ($gt['name'] ?? '') : $gt);
                                @endphp
                                @if(!empty($gtSlug))
                                    <li><a href="{{ route('treatments.show', $gtSlug) }}">{{ $gtName }}</a></li>
                                @endif
                            @endforeach
                        </ul>
                    </li>

                    <li class="nav-dropdown-item">
                        <a href="{{ route('specialists') }}" class="nav-item {{ request()->routeIs('specialists') || request()->routeIs('specialists.*') ? 'active' : '' }}">
                            Specialists <i data-lucide="chevron-down" style="width: 14px; height: 14px; display: inline-block; vertical-align: middle; margin-left: 2px;"></i>
                        </a>
                        <ul class="dropdown-menu">
                            @foreach(($globalSpecialists ?? collect()) as $gspec)
                                @php
                                    $specSlug = is_object($gspec) ? $gspec->slug : (is_array($gspec) ? ($gspec['slug'] ?? '') : '');
                                    $specName = is_object($gspec) ? $gspec->name : (is_array($gspec) ? ($gspec['name'] ?? '') : $gspec);
                                @endphp
                                @if(!empty($specSlug))
                                    <li><a href="{{ route('specialists.show', $specSlug) }}">{{ $specName }}</a></li>
                                @endif
                            @endforeach
                        </ul>
                    </li>

                    <li><a href="{{ route('blog') }}" class="nav-item {{ request()->routeIs('blog') || request()->routeIs('blog.*') ? 'active' : '' }}">Health Hub</a></li>
                    <li><a href="{{ route('contact') }}" class="nav-item {{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a></li>
                </ul>
            </nav>
            
            <div class="header-actions">
                <!-- Top Navigation Live Autocomplete Search -->
                <div class="nav-search-wrapper">
                    <div class="nav-search-box">
                        <i data-lucide="search" class="nav-search-icon"></i>
                        <input type="text" id="nav-search-input" class="nav-search-input" placeholder="Search treatments, services, blogs..." autocomplete="off" onkeyup="handleNavSearch(this.value, 'desktop')">
                        <button id="nav-search-clear" class="nav-search-clear-btn" onclick="clearNavSearch('desktop')">&times;</button>
                    </div>

                    <!-- Desktop Autocomplete Results Dropdown -->
                    <div id="nav-search-dropdown" class="nav-search-dropdown">
                        <div id="nav-search-loading" class="nav-search-status" style="display: none;">
                            <span class="search-spinner"></span> Searching treatments, services, specialists...
                        </div>
                        <div id="nav-search-results"></div>
                    </div>
                </div>

                <button class="btn btn-primary btn-sm" onclick="openDialog('callback-dialog');">
                    <i data-lucide="calendar"></i> Book Appointment
                </button>
            </div>
            
            <button class="mobile-nav-toggle" aria-label="Toggle Navigation Menu" onclick="toggleMobileDrawer()">
                <i data-lucide="menu" style="width: 28px; height: 28px;"></i>
            </button>
        </div>
    </header>

    <!-- Mobile Navigation Drawer -->
    <div class="mobile-drawer-overlay" id="mobile-overlay" onclick="toggleMobileDrawer()"></div>
    <div class="mobile-drawer" id="mobile-drawer">
        <div class="mobile-drawer-header">
            <span class="logo-text" style="font-size: 18px;">{{ $site['site_name'] ?? 'SumitZ Clinic' }}</span>
            <button class="mobile-drawer-close" aria-label="Close Navigation" onclick="toggleMobileDrawer()">&times;</button>
        </div>

        <!-- Mobile Live Search Box -->
        <div class="nav-search-wrapper" style="margin-bottom: 20px;">
            <div class="nav-search-box" style="width: 100%;">
                <i data-lucide="search" class="nav-search-icon"></i>
                <input type="text" id="mobile-search-input" class="nav-search-input" placeholder="Search treatments, services, blogs..." autocomplete="off" onkeyup="handleNavSearch(this.value, 'mobile')">
                <button id="mobile-search-clear" class="nav-search-clear-btn" onclick="clearNavSearch('mobile')">&times;</button>
            </div>
            <div id="mobile-search-dropdown" class="nav-search-dropdown" style="position: static; width: 100%; box-shadow: none; border-radius: 8px; margin-top: 8px;">
                <div id="mobile-search-loading" class="nav-search-status" style="display: none;">
                    <span class="search-spinner"></span> Searching...
                </div>
                <div id="mobile-search-results"></div>
            </div>
        </div>

        <ul style="display: flex; flex-direction: column; gap: 16px; margin-bottom: 32px;">
            <li><a href="{{ route('about') }}" class="nav-item">About Us</a></li>
            <li><a href="{{ route('services') }}" class="nav-item">Services</a></li>
            <li><a href="{{ route('treatments') }}" class="nav-item">Treatments</a></li>
            <li><a href="{{ route('specialists') }}" class="nav-item">Specialists</a></li>
            <li><a href="{{ route('blog') }}" class="nav-item">Health Hub</a></li>
            <li><a href="{{ route('contact') }}" class="nav-item">Contact Us</a></li>
        </ul>
        <div style="border-top: 1px solid var(--border-color); padding-top: 20px;">
            <a href="tel:{{ $site['phone'] ?? '+919545559434' }}" class="btn btn-secondary btn-full" style="margin-bottom: 12px;">
                <i data-lucide="phone"></i> Call Clinic
            </a>
            <button class="btn btn-primary btn-full" onclick="toggleMobileDrawer(); openDialog('callback-dialog');">
                <i data-lucide="calendar"></i> Request Appointment
            </button>
        </div>
    </div>

    <!-- Main Content Area -->
    <main id="main-content">
        @yield('content')
    </main>

    <!-- Global Floating Live Chat Widget -->
    <div class="chat-widget-btn" id="chat-toggle-btn" onclick="toggleChatWidget()" title="Live Chat Support" style="position: fixed;">
        <i data-lucide="message-square" style="width: 24px; height: 24px; color: white;"></i>
        <span id="chat-unread-badge" style="position: absolute; top: -4px; right: -4px; background: #ef4444; color: #fff; font-size: 11px; font-weight: bold; width: 22px; height: 22px; border-radius: 50%; display: none; align-items: center; justify-content: center; border: 2px solid white; box-shadow: 0 2px 6px rgba(0,0,0,0.3);">0</span>
    </div>
    
    <div class="chat-widget-box" id="chat-box">
        <div class="chat-header">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 32px; height: 32px; border-radius: 50%; background: white; display: flex; align-items: center; justify-content: center; color: var(--brand-blue); font-weight: bold; font-size: 14px;">
                    SZ
                </div>
                <div>
                    <h4 style="margin: 0; color: white; font-size: 15px; font-weight: 600;">SumitZ Assistant</h4>
                    <small style="color: rgba(255,255,255,0.8); font-size: 11px;">● Online - Patient Care</small>
                </div>
            </div>
            <button onclick="toggleChatWidget()" style="background: none; border: none; color: white; font-size: 20px; cursor: pointer;">&times;</button>
        </div>
        <div class="chat-body" id="chat-messages-container">
            <!-- Messages rendered dynamically -->
        </div>
        <form class="chat-footer" id="chat-form" onsubmit="sendChatMessage(event)">
            <input type="text" id="chat-input" class="form-control" placeholder="Ask a question or request appointment..." required style="height: 40px; font-size: 14px;">
            <button type="submit" class="btn btn-primary btn-sm" style="padding: 0 16px;">
                <i data-lucide="send" style="width: 16px;"></i>
            </button>
        </form>
    </div>

    <!-- Global Footer -->
    <footer class="footer">
        <div class="container footer-grid">
            <div>
                <div class="footer-logo">
                    <span class="logo-text" style="color: white; font-size: 22px;">{{ $site['site_name'] ?? 'SumitZ' }}</span>
                    <span class="logo-tagline" style="color: var(--brand-blue-soft); display: block;">{{ $site['site_tagline'] ?? 'ORTHOPAEDIC & PHYSIOTHERAPY CENTER' }}</span>
                </div>
                <p class="footer-text">
                    {{ $pageContents['footer']['about']['description'] ?? ($site['footer_description'] ?? (($site['site_name'] ?? 'SumitZ Clinic') . " is Pune's leading Orthopaedic and Physiotherapy recovery centre in Baner and Hinjewadi. Directed by Dr. Sumit, we offer patient-focused care for joint, spine, fracture, and sports injury rehabilitation.")) }}
                </p>
                <div style="display: flex; gap: 10px; margin-top: 16px; flex-wrap: wrap;">
                    @if(!empty($site['social_instagram']))
                        <a href="{{ $site['social_instagram'] }}" target="_blank" style="color: white; background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888); width: 38px; height: 38px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.3);" aria-label="Instagram @drsumitz" title="Follow Dr. Sumit on Instagram"><i data-lucide="instagram" style="width: 20px; height: 20px; color: white;"></i></a>
                    @endif
                    @if(!empty($site['social_facebook']))
                        <a href="{{ $site['social_facebook'] }}" target="_blank" style="color: white; background: #1877f2; width: 38px; height: 38px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.3);" aria-label="Facebook" title="Facebook Page"><i data-lucide="facebook" style="width: 20px; height: 20px; color: white;"></i></a>
                    @endif
                    @if(!empty($site['social_linkedin']))
                        <a href="{{ $site['social_linkedin'] }}" target="_blank" style="color: white; background: #0a66c2; width: 38px; height: 38px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.3);" aria-label="LinkedIn" title="LinkedIn Profile"><i data-lucide="linkedin" style="width: 20px; height: 20px; color: white;"></i></a>
                    @endif
                    @if(!empty($site['social_twitter']))
                        <a href="{{ $site['social_twitter'] }}" target="_blank" style="color: white; background: #1da1f2; width: 38px; height: 38px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.3);" aria-label="Twitter / X" title="Twitter Profile"><i data-lucide="twitter" style="width: 20px; height: 20px; color: white;"></i></a>
                    @endif
                    @if(!empty($site['social_practo']))
                        <a href="{{ $site['social_practo'] }}" target="_blank" style="color: white; background: #28328c; width: 38px; height: 38px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px; box-shadow: 0 2px 8px rgba(0,0,0,0.3);" aria-label="Practo Profile" title="Book via Practo">P</a>
                    @endif
                    @if(!empty($site['google_my_business_link']))
                        <a href="{{ $site['google_my_business_link'] }}" target="_blank" style="color: white; background: #4285f4; width: 38px; height: 38px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.3);" aria-label="Google Reviews" title="Read Google Reviews"><i data-lucide="star" style="width: 20px; height: 20px; color: #fbbf24;"></i></a>
                    @endif
                </div>
            </div>
            
            <div>
                <h3 class="footer-title">Quick Links</h3>
                <ul class="footer-links">
                    <li><a href="{{ route('about') }}">About Us</a></li>
                    <li><a href="{{ route('services') }}">Multidisciplinary Services</a></li>
                    <li><a href="{{ route('treatments') }}">Specialized Treatments</a></li>
                    <li><a href="{{ route('specialists') }}">Our Specialists</a></li>
                    <li><a href="{{ route('testimonials') }}">Patient Stories</a></li>
                    <li><a href="{{ route('blog') }}">Health & Recovery Blog</a></li>
                    <li><a href="{{ route('contact') }}">Contact Us</a></li>
                </ul>
            </div>
            
            <div>
                <h3 class="footer-title">Popular Treatments</h3>
                <ul class="footer-links">
                    @foreach($globalTreatments->take(6) as $gt)
                        <li><a href="{{ route('treatments.show', $gt->slug) }}">{{ $gt->name }}</a></li>
                    @endforeach
                </ul>
            </div>
            
            <div>
                <h3 class="footer-title">Clinic Locations</h3>
                <div style="font-size: 14px; line-height: 1.6; color: rgba(255,255,255,0.85); display: flex; flex-direction: column; gap: 16px;">
                    <div>
                        <strong style="color: #fff; display: block; margin-bottom: 2px;">Baner Clinic:</strong>
                        <span>{{ $site['address_baner'] ?? 'First Floor, Krishna Avenue, Baner, Pune - 411045' }}</span>
                    </div>
                    <div>
                        <strong style="color: #fff; display: block; margin-bottom: 2px;">Hinjewadi Clinic:</strong>
                        <span>{{ $site['address_hinjewadi'] ?? 'Atlanta Society, Hinjewadi, Pune - 411057' }}</span>
                    </div>
                    <div>
                        <strong style="color: #fff; display: block; margin-bottom: 2px;">Helplines:</strong>
                        <a href="tel:{{ $site['phone'] ?? '+919545559434' }}" style="color: var(--brand-blue-soft); font-weight: 600;">{{ $site['phone_display'] ?? ($site['phone'] ?? '+91 95455 59434') }}</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="container footer-bottom">
            <p>{{ $site['copyright_text'] ?? ('© ' . date('Y') . ' ' . ($site['site_name'] ?? 'SumitZ Clinic') . '. All Rights Reserved.') }} | <a href="/disclaimer" style="color: inherit; text-decoration: underline;">Disclaimer</a> | <a href="/privacy-policy" style="color: inherit; text-decoration: underline;">Privacy Policy</a></p>
            <p>Designed for Patient Reassurance & Accessibility</p>
        </div>
    </footer>

    <!-- Mobile Sticky Action Bar -->
    <div class="mobile-sticky-bar">
        <div class="mobile-sticky-inner">
            <a href="tel:{{ $site['phone'] ?? '+919545559434' }}" class="btn btn-secondary btn-sm">
                <i data-lucide="phone"></i> Call Clinic
            </a>
            <button onclick="openDialog('callback-dialog')" class="btn btn-primary btn-sm">
                <i data-lucide="calendar"></i> Book Appointment
            </button>
        </div>
    </div>

    <!-- Appointment Callback Modal Overlay -->
    <div class="dialog-overlay" id="callback-dialog" style="display: none; position: fixed; inset: 0; background: rgba(10, 37, 64, 0.6); backdrop-filter: blur(4px); z-index: 3000; align-items: center; justify-content: center; padding: 20px;">
        <div class="form-card" style="width: 100%; max-width: 480px; position: relative;">
            <button aria-label="Close Dialog" onclick="closeDialog('callback-dialog')" style="position: absolute; top: 16px; right: 16px; background: none; border: none; font-size: 24px; cursor: pointer; color: var(--heading-navy);">&times;</button>
            <div style="margin-bottom: 20px;">
                <h3 style="font-size: 22px; margin-bottom: 6px;">Book an Appointment</h3>
                <p style="font-size: 14px; color: var(--muted-text);">Select your preferred service and our medical coordinator will confirm your slot.</p>
            </div>
            <form action="{{ route('contact.submit') }}" method="POST">
                @csrf
                <input type="hidden" name="source" value="Appointment Request Modal">
                <div class="form-group">
                    <label for="cb-name" class="form-label">Full Name</label>
                    <input type="text" id="cb-name" name="name" class="form-control" placeholder="Enter your full name" required>
                </div>
                <div class="form-group">
                    <label for="cb-phone" class="form-label">Phone Number</label>
                    <input type="tel" id="cb-phone" name="phone" class="form-control" placeholder="10-digit mobile number" pattern="[0-9]{10}" required>
                </div>
                <div class="form-group">
                    <label for="cb-dept" class="form-label">Service / Treatment Needed</label>
                    <select id="cb-dept" name="service" class="form-control" required>
                        <option value="">-- Select Service / Treatment --</option>
                        @foreach($globalServices as $gs)
                            <option value="{{ $gs->slug }}">{{ $gs->service_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="cb-message" class="form-label">Additional Message (Optional)</label>
                    <textarea id="cb-message" name="message" class="form-control" rows="2" placeholder="Briefly state your concern..."></textarea>
                </div>
                @if(!empty($site['recaptcha_enabled']) && $site['recaptcha_enabled'] == '1' && !empty($site['recaptcha_site_key']))
                    <div class="form-group" style="margin-bottom: 1rem; display: flex; justify-content: center;">
                        <div class="g-recaptcha" data-sitekey="{{ $site['recaptcha_site_key'] }}"></div>
                    </div>
                @endif
                <button type="submit" class="btn btn-primary btn-full" style="margin-top: 12px;">Confirm Appointment Request</button>
            </form>
        </div>
    </div>

    <!-- Success Modal Overlay -->
    <div class="dialog-overlay" id="success-dialog" style="{{ session('success') ? 'display: flex;' : 'display: none;' }} position: fixed; inset: 0; background: rgba(10, 37, 64, 0.6); z-index: 3000; align-items: center; justify-content: center; padding: 20px;">
        <div class="form-card text-center" style="width: 100%; max-width: 420px; position: relative;">
            <button aria-label="Close Dialog" onclick="closeDialog('success-dialog')" style="position: absolute; top: 16px; right: 16px; background: none; border: none; font-size: 24px; cursor: pointer;">&times;</button>
            <div style="width: 56px; height: 56px; border-radius: 50%; background-color: var(--brand-blue-light); color: var(--brand-blue); display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                <i data-lucide="check-circle" style="width: 32px; height: 32px;"></i>
            </div>
            <h3 style="font-size: 22px; margin-bottom: 8px;">Appointment Request Sent!</h3>
            <p style="font-size: 14.5px; color: var(--body-text); margin-bottom: 24px;">{{ session('success') ?? 'Thank you for contacting SumitZ Clinic. Our medical coordinator will get in touch with you shortly.' }}</p>
            <button onclick="closeDialog('success-dialog')" class="btn btn-primary btn-full">OK, Got It</button>
        </div>
    </div>

    <!-- Error Modal Overlay -->
    <div class="dialog-overlay" id="error-dialog" style="{{ session('error') ? 'display: flex;' : 'display: none;' }} position: fixed; inset: 0; background: rgba(10, 37, 64, 0.6); z-index: 3000; align-items: center; justify-content: center; padding: 20px;">
        <div class="form-card text-center" style="width: 100%; max-width: 420px; position: relative;">
            <button aria-label="Close Dialog" onclick="closeDialog('error-dialog')" style="position: absolute; top: 16px; right: 16px; background: none; border: none; font-size: 24px; cursor: pointer;">&times;</button>
            <div style="width: 56px; height: 56px; border-radius: 50%; background-color: var(--accent-red-light); color: var(--accent-red); display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                <i data-lucide="alert-triangle" style="width: 32px; height: 32px;"></i>
            </div>
            <h3 style="font-size: 22px; color: var(--accent-red); margin-bottom: 8px;">Submission Issue</h3>
            <p style="font-size: 14.5px; color: var(--body-text); margin-bottom: 24px;">{{ session('error') }}</p>
            <button onclick="closeDialog('error-dialog')" class="btn btn-accent btn-full">Try Again</button>
        </div>
    </div>

    <!-- Lucide Icons CDN and Script Initializer -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();

        // Get or Create Chat Session ID
        function getChatSessionId() {
            let id = localStorage.getItem('sumitz_chat_session');
            if (!id) {
                id = 'session_' + Math.random().toString(36).substr(2, 9);
                localStorage.setItem('sumitz_chat_session', id);
            }
            return id;
        }

        function toggleChatWidget() {
            const box = document.getElementById('chat-box');
            const badge = document.getElementById('chat-unread-badge');
            const isOpen = box.style.display === 'flex';
            if (isOpen) {
                box.style.display = 'none';
            } else {
                box.style.display = 'flex';
                if (badge) badge.style.display = 'none';
                fetchChatHistory();
            }
        }

        function fetchChatHistory() {
            const sessionId = getChatSessionId();
            fetch('/chat/history?session_id=' + sessionId)
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        renderChatMessages(data.messages);
                        if (data.messages.length === 0) {
                            triggerAutoGreeting();
                        }
                    }
                })
                .catch(err => console.error(err));
        }

        function triggerAutoGreeting() {
            const sessionId = getChatSessionId();
            fetch('/chat/auto-trigger', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ session_id: sessionId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success && data.message) {
                    fetchChatHistory();
                }
            });
        }

        function renderChatMessages(messages) {
            const box = document.getElementById('chat-box');
            const badge = document.getElementById('chat-unread-badge');
            const isOpen = box && box.style.display === 'flex';
            
            if (!isOpen) {
                const unreadAdminCount = messages.filter(m => m.sender === 'admin' && !m.is_read).length;
                if (badge) {
                    if (unreadAdminCount > 0) {
                        badge.textContent = unreadAdminCount;
                        badge.style.display = 'flex';
                    } else {
                        badge.style.display = 'none';
                    }
                }
            } else {
                if (badge) badge.style.display = 'none';
            }

            const container = document.getElementById('chat-messages-container');
            if (container) {
                container.innerHTML = '';
                messages.forEach(msg => {
                    const div = document.createElement('div');
                    div.className = 'chat-msg ' + (msg.sender === 'visitor' ? 'visitor' : 'admin');
                    div.innerText = msg.message;
                    container.appendChild(div);
                });
                container.scrollTop = container.scrollHeight;
            }
        }

        function sendChatMessage(event) {
            event.preventDefault();
            const input = document.getElementById('chat-input');
            const message = input.value.trim();
            if (!message) return;

            const sessionId = getChatSessionId();
            input.value = '';

            fetch('/chat/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ session_id: sessionId, message: message })
            })
            .then(res => res.json())
            .then(data => {
                fetchChatHistory();
            });
        }

        // Only poll chat history when tab is visible and chat box is open to conserve Hostinger MySQL connections
        setInterval(() => {
            const chatBox = document.getElementById('chat-box');
            if (!document.hidden && chatBox && chatBox.style.display === 'flex') {
                fetchChatHistory();
            }
        }, 30000);

        function toggleMobileDrawer() {
            const drawer = document.getElementById('mobile-drawer');
            const overlay = document.getElementById('mobile-overlay');
            if (drawer && overlay) {
                drawer.classList.toggle('active');
                overlay.classList.toggle('active');
            }
        }

        function openDialog(id) {
            const dialog = document.getElementById(id);
            if (!dialog) return;

            // Check if any other popup dialog is currently active/visible
            const activeOverlays = Array.from(document.querySelectorAll('.dialog-overlay, .modal-overlay')).filter(
                el => el.id !== id && (el.style.display === 'flex' || el.style.display === 'block' || el.classList.contains('active') || el.classList.contains('show'))
            );

            if (activeOverlays.length > 0) {
                // Block opening new popup if one popup is already open
                return;
            }

            dialog.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
        function closeDialog(id) {
            const dialog = document.getElementById(id);
            if (dialog) {
                dialog.style.display = 'none';
                document.body.style.overflow = '';
            }
        }

        // Auto-check for chat open query param
        if (window.location.search.includes('open_chat=1')) {
            toggleChatWidget();
        }

        // Live Navigation Autocomplete Search Handler
        let navSearchDebounce = null;

        function handleNavSearch(query, mode = 'desktop') {
            const isMobile = mode === 'mobile';
            const clearBtn = document.getElementById(isMobile ? 'mobile-search-clear' : 'nav-search-clear');
            const dropdown = document.getElementById(isMobile ? 'mobile-search-dropdown' : 'nav-search-dropdown');
            const loading = document.getElementById(isMobile ? 'mobile-search-loading' : 'nav-search-loading');
            const resultsContainer = document.getElementById(isMobile ? 'mobile-search-results' : 'nav-search-results');

            query = query.trim();

            if (query.length > 0) {
                if (clearBtn) clearBtn.style.display = 'block';
            } else {
                if (clearBtn) clearBtn.style.display = 'none';
                if (dropdown) dropdown.style.display = 'none';
                return;
            }

            if (query.length < 2) {
                if (dropdown) dropdown.style.display = 'none';
                return;
            }

            if (dropdown) dropdown.style.display = 'block';
            if (loading) loading.style.display = 'flex';
            if (resultsContainer) resultsContainer.innerHTML = '';

            clearTimeout(navSearchDebounce);
            navSearchDebounce = setTimeout(() => {
                fetch(`/api/search?q=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(data => {
                        if (loading) loading.style.display = 'none';
                        if (data.results && data.results.length > 0) {
                            let html = '<div class="nav-search-results-list">';
                            data.results.forEach(item => {
                                html += `
                                    <a href="${item.url}" class="nav-search-result-item">
                                        <img src="${item.image}" alt="${item.title}" class="nav-search-thumb">
                                        <div class="nav-search-details">
                                            <div class="nav-search-title-row">
                                                <span class="nav-search-title">${item.title}</span>
                                                <span class="nav-search-badge" style="background-color: ${item.badge_bg}; color: ${item.badge_color};">${item.type}</span>
                                            </div>
                                            <div class="nav-search-sub">${item.subtitle || ''}</div>
                                        </div>
                                    </a>
                                `;
                            });
                            html += '</div>';
                            if (resultsContainer) resultsContainer.innerHTML = html;
                        } else {
                            if (resultsContainer) {
                                resultsContainer.innerHTML = `
                                    <div class="nav-search-empty">
                                        No treatments, services or blogs found matching "<strong>${escapeHtml(query)}</strong>"
                                    </div>
                                `;
                            }
                        }
                    })
                    .catch(err => {
                        if (loading) loading.style.display = 'none';
                        if (resultsContainer) {
                            resultsContainer.innerHTML = '<div class="nav-search-empty">Error fetching search results. Please try again.</div>';
                        }
                    });
            }, 250);
        }

        function clearNavSearch(mode = 'desktop') {
            const inputId = mode === 'mobile' ? 'mobile-search-input' : 'nav-search-input';
            const input = document.getElementById(inputId);
            if (input) {
                input.value = '';
                handleNavSearch('', mode);
                input.focus();
            }
        }

        function escapeHtml(str) {
            return str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
        }

        // Close search dropdown on click outside or Escape key
        document.addEventListener('click', function(e) {
            const desktopWrapper = document.querySelector('.header-actions .nav-search-wrapper');
            if (desktopWrapper && !desktopWrapper.contains(e.target)) {
                const dd = document.getElementById('nav-search-dropdown');
                if (dd) dd.style.display = 'none';
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const dd1 = document.getElementById('nav-search-dropdown');
                const dd2 = document.getElementById('mobile-search-dropdown');
                if (dd1) dd1.style.display = 'none';
                if (dd2) dd2.style.display = 'none';
            }
        });
    </script>
</body>
</html>
