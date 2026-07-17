<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SumitZ Orthopaedic & Physiotherapy Clinic in Baner, Pune')</title>
    <meta name="description" content="@yield('meta_description', 'SumitZ Orthopaedic & Physiotherapy Clinic is Pune\'s premier clinic in Baner. Led by Dr. Sumit, we offer expert joint, spine, fracture and sports injury care.')">
    <meta name="keywords" content="@yield('meta_keywords', 'orthopedic clinic in baner, physiotherapy clinic in baner, orthopedic doctor in baner, knee replacement pune, dr sumit diwan')">
    <link rel="canonical" href="{{ url(Request::url()) }}" />

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website" />
    <meta property="og:title" content="@yield('title', 'SumitZ Orthopaedic & Physiotherapy Clinic in Baner, Pune')" />
    <meta property="og:description" content="@yield('meta_description', 'SumitZ Orthopaedic & Physiotherapy Clinic is Pune\'s premier clinic in Baner. Led by Dr. Sumit, we offer expert joint, spine, fracture and sports injury care.')" />
    <meta property="og:url" content="{{ url(Request::url()) }}" />
    <meta property="og:site_name" content="Drsumitz" />
    <meta property="og:image" content="https://www.drsumitz.com/public/uploads/gallery/614487085_25_06_2021_01_19_55.jpg" />

    <!-- Twitter -->
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:site" content="@drsumitzguptao1" />
    <meta name="twitter:title" content="@yield('title', 'SumitZ Orthopaedic & Physiotherapy Clinic in Baner, Pune')" />
    <meta name="twitter:description" content="@yield('meta_description', 'SumitZ Orthopaedic & Physiotherapy Clinic is Pune\'s premier clinic in Baner. Led by Dr. Sumit, we offer expert joint, spine, fracture and sports injury care.')" />
    <meta name="twitter:image" content="https://www.drsumitz.com/public/uploads/gallery/614487085_25_06_2021_01_19_55.jpg" />

    
    <!-- Dynamic Favicon -->
    @if(!empty($site['favicon_path']))
        <link rel="icon" type="image/webp" href="{{ site_image($site['favicon_path']) }}">
    @else
        <link rel="icon" type="image/x-icon" href="/favicon.ico">
    @endif

    <!-- Google Fonts: Outfit & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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
</head>
<body>

    <!-- Sticky Header -->
    <header class="header">
        <div class="container nav-container">
            <a href="{{ route('home') }}" class="logo">
                @if(!empty($site['logo_path']))
                    <img src="{{ site_image($site['logo_path']) }}" alt="{{ $site['site_name'] ?? 'SumitZ Clinic' }}" style="height: 55px; width: auto; object-fit: contain;">
                @else
                    <svg class="logo-icon" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" style="stroke: var(--accent-teal); stroke-width: 4;">
                        <circle cx="50" cy="50" r="40" stroke="currentColor" fill="none"/>
                        <path d="M50 25 V75 M25 50 H75" stroke="currentColor" stroke-width="12" stroke-linecap="round"/>
                    </svg>
                    <div>
                        <span class="logo-text">{{ $site['site_name'] ?? 'SumitZ Clinic' }}</span>
                        <span class="logo-tagline">{{ $site['site_tagline'] ?? 'Orthopaedic & Physiotherapy Speciality' }}</span>
                    </div>
                @endif
            </a>
            
            <nav>
                <ul class="nav-links">
                    <li><a href="{{ route('home') }}" class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
                    <li><a href="{{ route('about') }}" class="nav-item {{ request()->routeIs('about') ? 'active' : '' }}">About Us</a></li>
                    
                    <li class="nav-dropdown-item">
                        <a href="{{ route('services') }}" class="nav-item {{ request()->routeIs('services') || request()->routeIs('services.*') ? 'active' : '' }}">Services <i data-lucide="chevron-down" style="width: 14px; height: 14px; display: inline-block; vertical-align: middle; margin-left: 2px;"></i></a>
                        <ul class="dropdown-menu">
                            @foreach($globalServices as $gs)
                                <li><a href="{{ route('services.show', $gs->slug) }}">{{ $gs->service_name }}</a></li>
                            @endforeach
                        </ul>
                    </li>

                    <li class="nav-dropdown-item">
                        <a href="{{ route('treatments') }}" class="nav-item {{ request()->routeIs('treatments') || request()->routeIs('treatments.*') ? 'active' : '' }}">Treatments <i data-lucide="chevron-down" style="width: 14px; height: 14px; display: inline-block; vertical-align: middle; margin-left: 2px;"></i></a>
                        <ul class="dropdown-menu">
                            @foreach($globalTreatments->take(8) as $gt)
                                <li><a href="{{ route('treatments.show', $gt->slug) }}">{{ $gt->name }}</a></li>
                            @endforeach
                            @if($globalTreatments->count() > 8)
                                <li><a href="{{ route('treatments') }}" style="font-weight: 600; color: var(--accent-teal);">View All Treatments</a></li>
                            @endif
                        </ul>
                    </li>

                    <li class="nav-dropdown-item">
                        <a href="{{ route('specialists') }}" class="nav-item {{ request()->routeIs('specialists') || request()->routeIs('specialists.*') ? 'active' : '' }}">Specialists <i data-lucide="chevron-down" style="width: 14px; height: 14px; display: inline-block; vertical-align: middle; margin-left: 2px;"></i></a>
                        <ul class="dropdown-menu">
                            @foreach($globalSpecialists as $gspec)
                                <li><a href="{{ route('specialists.show', $gspec->slug) }}">{{ $gspec->name }}</a></li>
                            @endforeach
                        </ul>
                    </li>

                    <li><a href="{{ route('testimonials') }}" class="nav-item {{ request()->routeIs('testimonials') ? 'active' : '' }}">Testimonials</a></li>
                    <li><a href="{{ route('blog') }}" class="nav-item {{ request()->routeIs('blog') || request()->routeIs('blog.*') ? 'active' : '' }}">Blog</a></li>
                    <li><a href="{{ route('contact') }}" class="nav-item {{ request()->routeIs('contact') ? 'active' : '' }}">Contact Us</a></li>
                </ul>
            </nav>
            
            <div class="nav-actions">
                <a href="#appointment-section" class="btn btn-primary btn-sm" onclick="event.preventDefault(); openDialog('callback-dialog');"><i data-lucide="calendar"></i> Book Appointment</a>
            </div>
            
            <button class="mobile-toggle" aria-label="Toggle Menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </header>

    @yield('content')

    <!-- Footer -->
    <footer class="footer">
        <div class="container footer-grid">
            <div class="footer-col footer-col-about">
                <div class="logo" style="margin-bottom:1rem;">
                    @if(!empty($site['logo_path']))
                        <img src="{{ site_image($site['logo_path']) }}" alt="{{ $site['site_name'] ?? 'SumitZ Clinic' }}" style="height: 55px; width: auto; object-fit: contain;">
                    @else
                        <svg class="logo-icon" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" style="stroke: var(--accent-teal); stroke-width: 4;">
                            <circle cx="50" cy="50" r="40" stroke="currentColor" fill="none"/>
                            <path d="M50 25 V75 M25 50 H75" stroke="currentColor" stroke-width="12" stroke-linecap="round"/>
                        </svg>
                        <div>
                            <span class="logo-text" style="color:white;">{{ $site['site_name'] ?? 'SumitZ Clinic' }}</span>
                            <span class="footer-logo-tagline">{{ $site['site_tagline'] ?? 'Orthopaedic & Physiotherapy Speciality' }}</span>
                        </div>
                    @endif
                </div>
                <p>{{ $pageContents['footer']['about']['description'] ?? (($site['site_name'] ?? 'SumitZ Clinic') . " is Pune's leading Orthopaedic and Physiotherapy center. Directed by Dr. Sumit, we offer state-of-the-art treatments for joint pain, spine issues, fractures, and sports injuries.") }}</p>
                <div class="social-links">
                    <a href="{{ !empty($site['social_facebook']) ? $site['social_facebook'] : '#' }}" target="_blank" class="social-icon" aria-label="Facebook" title="Facebook"><i data-lucide="facebook"></i></a>
                    <a href="{{ !empty($site['social_instagram']) ? $site['social_instagram'] : '#' }}" target="_blank" class="social-icon" aria-label="Instagram" title="Instagram"><i data-lucide="instagram"></i></a>
                    <a href="{{ !empty($site['social_linkedin']) ? $site['social_linkedin'] : '#' }}" target="_blank" class="social-icon" aria-label="LinkedIn" title="LinkedIn"><i data-lucide="linkedin"></i></a>
                    <a href="{{ !empty($site['social_twitter']) ? $site['social_twitter'] : '#' }}" target="_blank" class="social-icon" aria-label="Twitter" title="Twitter"><i data-lucide="twitter"></i></a>
                    <a href="{{ !empty($site['social_practo']) ? $site['social_practo'] : '#' }}" target="_blank" class="social-icon" aria-label="Practo" title="Practo"><i data-lucide="plus-circle"></i></a>
                </div>
            </div>
            
            <div class="footer-col">
                <h3>Quick Links</h3>
                <ul class="footer-links-list">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('about') }}">About Us</a></li>
                    <li><a href="{{ route('services') }}">Services</a></li>
                    <li><a href="{{ route('treatments') }}">Treatments</a></li>
                    <li><a href="{{ route('specialists') }}">Our Specialists</a></li>
                    <li><a href="{{ route('testimonials') }}">Testimonials</a></li>
                    <li><a href="{{ route('blog') }}">Health Blog</a></li>
                </ul>
            </div>
            
            <div class="footer-col">
                <h3>Our Treatments</h3>
                <ul class="footer-links-list">
                    @foreach($globalTreatments->take(6) as $gt)
                        <li><a href="{{ route('treatments.show', $gt->slug) }}">{{ $gt->name }}</a></li>
                    @endforeach
                </ul>
            </div>
            
            <div class="footer-col">
                <h3>Contact Info</h3>
                <ul class="footer-contact-info">
                    <li style="align-items: flex-start;">
                        <i data-lucide="map-pin" style="margin-top: 4px;"></i>
                        <div style="display: flex; flex-direction: column; gap: 4px;">
                            <span><strong>Baner:</strong> First Floor, Krishna Avenue, Baner, Pune - 411045</span>
                            <span><strong>Hinjewadi:</strong> Atlanta Society, Hinjewadi, Pune - 411057</span>
                        </div>
                    </li>
                    <li style="align-items: flex-start;">
                        <i data-lucide="phone" style="margin-top: 4px;"></i>
                        <div style="display: flex; flex-direction: column; gap: 4px;">
                            <a href="tel:+919545559434">+91 95455 59434</a>
                            <a href="tel:+918855950433">+91 88559 50433</a>
                            <a href="tel:+919404963781">+91 94049 63781</a>
                            <a href="tel:+919158143325">+91 91581 43325</a>
                            <span style="font-size: 0.8rem; opacity: 0.7; margin-top: 2px;">(24/7 General inquiry)</span>
                        </div>
                    </li>
                    <li>
                        <i data-lucide="mail"></i>
                        <a href="mailto:{{ $site['email'] ?? 'info@drsumitz.com' }}">{{ $site['email'] ?? 'info@drsumitz.com' }}</a>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="container footer-bottom">
            <p>&copy; {{ date('Y') }} {{ $site['site_name'] ?? 'SumitZ Clinic' }}. All Rights Reserved. | <a href="/disclaimer" style="color: inherit; text-decoration: underline;">Disclaimer</a> | <a href="/privacy-policy" style="color: inherit; text-decoration: underline;">Privacy Policy</a></p>
        </div>
    </footer>

    <!-- Floating Conversion Action Buttons -->
    <div class="floating-widgets">
        <a href="https://wa.me/{{ $site['whatsapp_number'] ?? '919672777749' }}?text=Hi%20SumitZ%20Clinic,%20I'm%20interested%20in%20booking%20a%20consultation." target="_blank" class="widget-btn widget-whatsapp" aria-label="WhatsApp Us">
            <i data-lucide="message-circle"></i>
        </a>
        <a href="tel:{{ $site['phone'] ?? '+919672777749' }}" class="widget-btn widget-call" aria-label="Call Us Now">
            <i data-lucide="phone"></i>
        </a>
        <button class="widget-btn widget-top" aria-label="Back to Top">
            <i data-lucide="arrow-up"></i>
        </button>
    </div>

    <!-- Mobile Sticky Action Bar -->
    <div class="mobile-action-bar">
        <a href="tel:{{ $site['phone'] ?? '+919672777749' }}" class="mobile-action-btn call">
            <i data-lucide="phone"></i> Call Now
        </a>
        <a href="https://wa.me/{{ $site['whatsapp_number'] ?? '919672777749' }}?text=Hi%20SumitZ%20Clinic,%20I'm%20interested%20in%20booking%20a%20consultation." target="_blank" class="mobile-action-btn whatsapp">
            <i data-lucide="message-circle"></i> WhatsApp
        </a>
    </div>

    <!-- Callback Modals Overlay (Appointment) -->
    <div class="dialog-overlay" id="callback-dialog">
        <div class="dialog-modal">
            <button class="dialog-close" aria-label="Close Modal" onclick="closeDialog('callback-dialog')">&times;</button>
            <div class="dialog-header">
                <h3>Book an Appointment</h3>
                <p>Provide your contact details and our medical coordinator will call you back to confirm your slot.</p>
            </div>
            <form action="{{ route('contact.submit') }}" method="POST">
                @csrf
                <input type="hidden" name="source" value="Appointment Request Modal">
                <div class="form-group">
                    <label for="cb-name">Your Full Name</label>
                    <input type="text" id="cb-name" name="name" class="form-control" placeholder="Enter your name" required>
                </div>
                <div class="form-group">
                    <label for="cb-phone">Phone Number</label>
                    <input type="tel" id="cb-phone" name="phone" class="form-control" placeholder="Enter 10-digit number" pattern="[0-9]{10}" required>
                </div>
                <div class="form-group">
                    <label for="cb-dept">Preferred Treatment / Service</label>
                    <select id="cb-dept" name="service" class="form-control" required>
                        <option value="">-- Select Treatment/Service --</option>
                        <option value="physiotherapy">Physiotherapy Care</option>
                        <option value="joint-replacement">Joint Replacement Surgery</option>
                        <option value="spine-care">Spine & Back Care</option>
                        <option value="fracture-treatment">Fracture Treatment</option>
                        <option value="sports-injury">Sports Injury Rehabilitation</option>
                        <option value="other">Other Consultation</option>
                    </select>
                </div>
                @if(!empty($site['recaptcha_enabled']) && $site['recaptcha_enabled'] == '1' && !empty($site['recaptcha_site_key']))
                    <div class="form-group" style="margin-bottom: 1rem; display: flex; justify-content: center;">
                        <div class="g-recaptcha" data-sitekey="{{ $site['recaptcha_site_key'] }}"></div>
                    </div>
                @endif
                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Confirm Request</button>
            </form>
        </div>
    </div>

    <!-- Exit Intent Modal Overlay -->
    <div class="dialog-overlay" id="exit-intent-dialog">
        <div class="dialog-modal">
            <button class="dialog-close" aria-label="Close Modal" onclick="closeDialog('exit-intent-dialog')">&times;</button>
            <div class="dialog-header">
                <h3 style="color: var(--accent-teal);">Schedule Your Joint Consultation!</h3>
                <p>Get a comprehensive diagnostic plan & consultation slot with Dr. Sumit.</p>
            </div>
            <form action="{{ route('contact.submit') }}" method="POST">
                @csrf
                <input type="hidden" name="source" value="Exit Intent Modal">
                <div class="form-group">
                    <label for="exit-name">Your Full Name</label>
                    <input type="text" id="exit-name" name="name" class="form-control" placeholder="Enter your name" required>
                </div>
                <div class="form-group">
                    <label for="exit-phone">Phone Number</label>
                    <input type="tel" id="exit-phone" name="phone" class="form-control" placeholder="Enter 10-digit number" pattern="[0-9]{10}" required>
                </div>
                @if(!empty($site['recaptcha_enabled']) && $site['recaptcha_enabled'] == '1' && !empty($site['recaptcha_site_key']))
                    <div class="form-group" style="margin-bottom: 1rem; display: flex; justify-content: center;">
                        <div class="g-recaptcha" data-sitekey="{{ $site['recaptcha_site_key'] }}"></div>
                    </div>
                @endif
                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Book Consultation</button>
            </form>
        </div>
    </div>

    <!-- Success Modal Overlay -->
    <div class="dialog-overlay" id="success-dialog" style="{{ session('success') ? 'display: flex;' : '' }}">
        <div class="dialog-modal text-center">
            <button class="dialog-close" aria-label="Close Modal" onclick="closeDialog('success-dialog')">&times;</button>
            <div class="success-icon-wrap" style="background: rgba(92, 203, 179, 0.1); color: var(--accent-teal);">
                <i data-lucide="check"></i>
            </div>
            <h3>Request Submitted Successfully!</h3>
            <p style="margin-top:1rem; color: var(--text-muted);">{{ session('success') ?? 'Thank you for contacting SumitZ Clinic. Our medical coordinator will get in touch with you shortly to confirm your appointment.' }}</p>
            <button onclick="closeDialog('success-dialog')" class="btn btn-primary" style="margin-top: 2rem; width: 150px;">OK</button>
        </div>
    </div>

    <!-- Error Modal Overlay -->
    <div class="dialog-overlay" id="error-dialog" style="{{ session('error') ? 'display: flex;' : '' }}">
        <div class="dialog-modal text-center">
            <button class="dialog-close" aria-label="Close Modal" onclick="closeDialog('error-dialog')">&times;</button>
            <div class="success-icon-wrap" style="background: rgba(232, 92, 36, 0.1); color: #e85c24;">
                <i data-lucide="alert-triangle"></i>
            </div>
            <h3 style="color: #e85c24;">Submission Failed</h3>
            <p style="margin-top:1rem; color: var(--text-muted);">{{ session('error') }}</p>
            <button onclick="closeDialog('error-dialog')" class="btn btn-primary" style="margin-top: 2rem; width: 150px; background-color: #e85c24; border-color: #e85c24;">OK</button>
        </div>
    </div>

    <!-- Lucide Icons CDN and Initializer -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
        
        // Modal Control Functions
        function openDialog(id) {
            const dialog = document.getElementById(id);
            if (dialog) {
                dialog.style.display = 'flex';
                setTimeout(() => {
                    dialog.classList.add('open');
                }, 10);
                document.body.style.overflow = 'hidden';
            }
        }
        function closeDialog(id) {
            const dialog = document.getElementById(id);
            if (dialog) {
                dialog.classList.remove('open');
                setTimeout(() => {
                    if (!dialog.classList.contains('open')) {
                        dialog.style.display = 'none';
                    }
                }, 300);
                document.body.style.overflow = '';
            }
        }
        
        // 1-Minute Auto-Popup Trigger for Appointment Booking
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                const hasOpenedCallback = sessionStorage.getItem('callback_auto_triggered');
                const hasInteractedWithForm = sessionStorage.getItem('form_interacted');
                if (!hasOpenedCallback && !hasInteractedWithForm) {
                    sessionStorage.setItem('callback_auto_triggered', 'true');
                    openDialog('callback-dialog');
                }
            }, 60000); // 1 minute (60,000 milliseconds)
            
            document.querySelectorAll('input, select, textarea').forEach(el => {
                el.addEventListener('focus', () => {
                    sessionStorage.setItem('form_interacted', 'true');
                });
            });
        });
    </script>
    
    <!-- Floating Chat Widget -->
    <div id="clinic-chat-widget" style="position: fixed; bottom: 20px; right: 20px; z-index: 1000; font-family: 'Outfit', sans-serif;">
        <!-- Floating Toggle Button -->
        <button id="chat-toggle-btn" onclick="toggleChatWindow()" style="width: 60px; height: 60px; border-radius: 50%; background-color: var(--primary-teal); color: white; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.15); cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; position: relative;">
            <div id="chat-icon-open" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center;">
                <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 100%; height: 100%;">
                    <!-- Headband -->
                    <path d="M16 32C16 19.85 23.16 10 32 10C40.84 10 48 19.85 48 32" stroke="white" stroke-width="3" stroke-linecap="round"/>
                    <!-- Shoulders/Body -->
                    <path d="M16 54C16 47.37 21.37 42 28 42H36C42.63 42 48 47.37 48 54V56H16V54Z" fill="white"/>
                    <!-- Neck -->
                    <path d="M28 38V43H36V38H28Z" fill="#e2e8f0"/>
                    <!-- Face -->
                    <rect x="23" y="21" width="18" height="18" rx="9" fill="#f8fafc"/>
                    <!-- Hair/Fringe -->
                    <path d="M23 25C25 22 29 23 32 24C35 23 39 22 41 25C41 25 39 18 32 18C25 18 23 25 23 25Z" fill="#334155"/>
                    <!-- Earcups -->
                    <rect x="14" y="28" width="4" height="8" rx="2" fill="#334155"/>
                    <rect x="46" y="28" width="4" height="8" rx="2" fill="#334155"/>
                    <!-- Mic -->
                    <path d="M46 34C41 38 37 38 37 38" stroke="#334155" stroke-width="2" stroke-linecap="round"/>
                    <circle cx="36" cy="38" r="1.5" fill="#334155"/>
                </svg>
            </div>
            <i data-lucide="x" id="chat-icon-close" style="width: 28px; height: 28px; display: none;"></i>
            <span id="chat-unread-badge" style="position: absolute; top: -5px; right: -5px; background-color: #ef4444; color: white; font-size: 0.75rem; font-weight: 700; width: 22px; height: 22px; border-radius: 50%; display: none; align-items: center; justify-content: center; border: 2px solid white;">0</span>
        </button>

        <!-- Chat Window -->
        <div id="chat-window" style="position: absolute; bottom: 75px; right: 0; width: 350px; height: 480px; background-color: white; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.15); display: none; flex-direction: column; overflow: hidden; border: 1px solid var(--border-color); transition: all 0.3s ease;">
            <!-- Chat Header -->
            <div style="background: linear-gradient(135deg, var(--primary-teal) 0%, var(--primary-teal-light) 100%); color: white; padding: 1rem 1.25rem; display: flex; align-items: center; gap: 12px; border-bottom: 1px solid rgba(255,255,255,0.1);">
                <div style="position: relative; flex-shrink: 0;">
                    @if(!empty($site['chat_assistant_avatar']))
                        <img src="{{ site_image($site['chat_assistant_avatar']) }}" alt="Avatar" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid white;">
                    @else
                        <div style="width: 40px; height: 40px; border-radius: 50%; background-color: var(--secondary-teal); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; border: 2px solid white; font-size: 0.9rem;">
                            {{ substr($site['chat_assistant_name'] ?? 'Clinic Assistant', 0, 1) }}{{ substr(strrchr($site['chat_assistant_name'] ?? 'Clinic Assistant', ' '), 1, 1) }}
                        </div>
                    @endif
                    <div style="position: absolute; bottom: 0; right: 0; width: 12px; height: 12px; background-color: #22c55e; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 4px #22c55e;"></div>
                </div>
                <div>
                    <strong style="display: block; font-size: 1rem; font-weight: 700;">{{ $site['chat_assistant_name'] ?? 'Clinic Assistant' }}</strong>
                    <span style="font-size: 0.75rem; color: rgba(255,255,255,0.8);">SumitZ Live Chat support</span>
                </div>
            </div>
            <!-- Chat Messages Body -->
            <div id="chat-messages-body" style="flex-grow: 1; padding: 1rem; overflow-y: auto; background-color: #f8fafc; display: flex; flex-direction: column; gap: 10px;">
                <!-- Messages will populate here via JS -->
            </div>
            <!-- Chat Footer Input -->
            <div style="padding: 0.75rem; border-top: 1px solid var(--border-color); display: flex; gap: 8px; background-color: white;">
                <input type="text" id="chat-input-message" placeholder="Type a message..." onkeydown="handleChatKey(event)" style="flex-grow: 1; border: 1px solid var(--border-color); padding: 0.6rem 0.8rem; border-radius: 20px; font-size: 0.9rem; outline: none; transition: border-color 0.2s;" onfocus="this.style.borderColor='var(--primary-teal)'" onblur="this.style.borderColor='var(--border-color)'">
                <button onclick="sendChatMessage()" style="background-color: var(--primary-teal); color: white; border: none; width: 36px; height: 36px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#163638'" onmouseout="this.style.backgroundColor='var(--primary-teal)'">
                    <i data-lucide="send" style="width: 16px; height: 16px;"></i>
                </button>
            </div>
        </div>
    </div>

    <script>
        // Setup localStorage session ID
        let chatSessionId = localStorage.getItem('clinic_chat_session');
        if (!chatSessionId) {
            chatSessionId = 'sess_' + Math.random().toString(36).substr(2, 9) + '_' + Date.now();
            localStorage.setItem('clinic_chat_session', chatSessionId);
        }

        let isChatOpen = false;
        let autoMessageTimer = null;
        let pollTimer = null;

        document.addEventListener('DOMContentLoaded', () => {
            // Check if widget should open or load history
            // Visitor stays for 1 minute (60,000ms) to trigger welcoming message
            autoMessageTimer = setTimeout(() => {
                triggerAutoMessage();
            }, 60000);

            // Initial silent message check
            checkNewMessagesSilently();
            
            // Poll for messages in background every 15s even if closed
            setInterval(checkNewMessagesSilently, 15000);
        });

        function toggleChatWindow() {
            const chatWindow = document.getElementById('chat-window');
            const openIcon = document.getElementById('chat-icon-open');
            const closeIcon = document.getElementById('chat-icon-close');

            if (isChatOpen) {
                chatWindow.style.display = 'none';
                openIcon.style.display = 'flex';
                closeIcon.style.display = 'none';
                isChatOpen = false;
                clearInterval(pollTimer);
            } else {
                chatWindow.style.display = 'flex';
                openIcon.style.display = 'none';
                closeIcon.style.display = 'block';
                isChatOpen = true;
                loadChatHistory();
                // Start polling every 5s when open
                pollTimer = setInterval(loadChatHistory, 5000);
            }
        }

        function triggerAutoMessage() {
            fetch('/chat/auto-trigger', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ session_id: chatSessionId })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    if (!isChatOpen) {
                        toggleChatWindow();
                    } else {
                        loadChatHistory();
                    }
                }
            })
            .catch(err => console.error('Error triggering auto message:', err));
        }

        function loadChatHistory() {
            fetch(`/chat/history?session_id=${chatSessionId}`)
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        renderMessages(data.messages);
                    }
                });
        }

        function checkNewMessagesSilently() {
            if (isChatOpen) return;
            fetch(`/chat/history?session_id=${chatSessionId}`)
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        const unreadCount = data.messages.filter(m => m.sender === 'admin' && !m.is_read).length;
                        const badge = document.getElementById('chat-unread-badge');
                        if (unreadCount > 0) {
                            badge.textContent = unreadCount;
                            badge.style.display = 'flex';
                        } else {
                            badge.style.display = 'none';
                        }
                    }
                });
        }

        function renderMessages(messages) {
            const body = document.getElementById('chat-messages-body');
            body.innerHTML = '';
            
            if (messages.length === 0) {
                body.innerHTML = `<div style="text-align: center; color: #94a3b8; font-size: 0.85rem; margin-top: 2rem;">How can we help you today? Type below to start talking to our team.</div>`;
                return;
            }

            messages.forEach(msg => {
                const isMsgAdmin = msg.sender === 'admin';
                const msgDiv = document.createElement('div');
                msgDiv.style.display = 'flex';
                msgDiv.style.justifyContent = isMsgAdmin ? 'flex-start' : 'flex-end';
                
                const time = new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

                msgDiv.innerHTML = `
                    <div style="max-width: 80%; background-color: ${isMsgAdmin ? '#e2e8f0' : 'var(--primary-teal)'}; color: ${isMsgAdmin ? '#1e293b' : 'white'}; padding: 0.6rem 0.9rem; border-radius: 14px; position: relative; box-shadow: 0 1px 2px rgba(0,0,0,0.05); font-size: 0.9rem; line-height: 1.4; margin-bottom: 4px;">
                        <div style="word-break: break-word;">${escapeHTML(msg.message)}</div>
                        <div style="font-size: 0.65rem; text-align: right; margin-top: 3px; color: ${isMsgAdmin ? '#64748b' : 'rgba(255,255,255,0.7)'};">${time}</div>
                    </div>
                `;
                body.appendChild(msgDiv);
            });

            document.getElementById('chat-unread-badge').style.display = 'none';
            body.scrollTop = body.scrollHeight;
        }

        function escapeHTML(str) {
            return str.replace(/[&<>'"]/g, 
                tag => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#39;', '"': '&quot;' }[tag] || tag)
            );
        }

        function handleChatKey(e) {
            if (e.key === 'Enter') {
                sendChatMessage();
            }
        }

        function sendChatMessage() {
            const input = document.getElementById('chat-input-message');
            const msg = input.value.trim();
            if (!msg) return;

            input.value = '';
            clearTimeout(autoMessageTimer);

            fetch('/chat/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    session_id: chatSessionId,
                    message: msg
                })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    loadChatHistory();
                }
            });
        }
    </script>
    
    <script src="{{ asset('assets/js/main.js') }}"></script>
    @yield('scripts')

    @if(!empty($site['cloudflare_analytics_token']))
        <!-- Cloudflare Web Analytics -->
        <script defer src="https://static.cloudflareinsights.com/beacon.min.js" data-cf-beacon='{"token": "{{ $site['cloudflare_analytics_token'] }}"}'></script>
        <!-- End Cloudflare Web Analytics -->
    @endif

    <!-- Combined Cookie & Notification Consent Module -->
    <div id="consent-banner" style="position: fixed; bottom: 20px; left: 20px; right: 20px; max-width: 480px; z-index: 999999; background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(12px); border: 1px solid rgba(92, 203, 179, 0.25); border-radius: 12px; padding: 1.25rem; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.5); display: none; color: white; animation: slideUpBanner 0.4s cubic-bezier(0.16, 1, 0.3, 1);">
        <style>
            @keyframes slideUpBanner {
                from { transform: translateY(100px); opacity: 0; }
                to { transform: translateY(0); opacity: 1; }
            }
            .consent-btn {
                padding: 8px 14px;
                border-radius: 6px;
                font-size: 0.8rem;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.2s;
                border: none;
            }
            .consent-allow {
                background-color: var(--primary-teal, #5ccbb3);
                color: #0b1e36;
            }
            .consent-allow:hover {
                filter: brightness(1.1);
            }
            .consent-deny {
                background: rgba(255,255,255,0.06);
                border: 1px solid rgba(255,255,255,0.1);
                color: white;
            }
            .consent-deny:hover {
                background: rgba(255,255,255,0.1);
            }
            .toast-alert {
                position: fixed;
                bottom: 20px;
                right: 20px;
                max-width: 350px;
                background: rgba(15, 23, 42, 0.98);
                border-left: 4px solid var(--primary-teal, #5ccbb3);
                border-radius: 8px;
                padding: 12px;
                box-shadow: 0 10px 15px -3px rgba(0,0,0,0.3);
                z-index: 9999999;
                color: white;
                display: flex;
                gap: 10px;
                cursor: pointer;
                animation: slideInToast 0.3s ease;
            }
            @keyframes slideInToast {
                from { transform: translateX(100px); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
        </style>
        <div style="display: flex; gap: 12px; align-items: start;">
            <div style="width: 38px; height: 38px; border-radius: 50%; background-color: rgba(92, 203, 179, 0.15); display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: #5ccbb3;">
                <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"></path><path d="M12 16v-4"></path><path d="M12 8h.01"></path></svg>
            </div>
            <div>
                <strong style="font-size: 0.9rem; font-family: 'Outfit', sans-serif; display: block; margin-bottom: 2px;">Cookie & Notification Request</strong>
                <p style="margin: 0; font-size: 0.75rem; color: #94a3b8; line-height: 1.4;">
                    We use cookies to optimize clinic experience. Would you also like to receive push notifications for dynamic recovery updates and support chat alerts?
                </p>
                <div style="margin-top: 10px; display: flex; gap: 8px;">
                    <button class="consent-btn consent-allow" onclick="acceptAllConsent()">Accept All</button>
                    <button class="consent-btn consent-deny" onclick="acceptCookiesOnly()">Cookies Only</button>
                    <button class="consent-btn consent-deny" onclick="closeConsentBanner()">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Client-side notification toasts container -->
    <div id="client-toast-container" style="position: fixed; bottom: 20px; right: 20px; z-index: 9999999; display: flex; flex-direction: column; gap: 10px;"></div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // 1. Auto-open Live Chat if open_chat=1 query parameter is set
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('open_chat') === '1') {
                setTimeout(() => {
                    if (typeof toggleChatWindow === 'function' && !isChatOpen) {
                        toggleChatWindow();
                    }
                }, 800);
            }

            // 2. Manage Consent Banner Visibility
            const cookiesAccepted = localStorage.getItem('cookies_accepted');
            const consentViewed = localStorage.getItem('consent_viewed');
            
            if (!consentViewed && !cookiesAccepted) {
                setTimeout(() => {
                    const banner = document.getElementById('consent-banner');
                    if (banner) banner.style.display = 'block';
                }, 2000);
            }

            // 3. Start Notification Polling if subscribed
            const notifSubscribed = localStorage.getItem('notifications_subscribed');
            if (notifSubscribed === 'true') {
                startNotificationPolling();
            }
        });

        function closeConsentBanner() {
            const banner = document.getElementById('consent-banner');
            if (banner) banner.style.display = 'none';
            localStorage.setItem('consent_viewed', 'true');
        }

        function acceptCookiesOnly() {
            localStorage.setItem('cookies_accepted', 'true');
            closeConsentBanner();
        }

        function acceptAllConsent() {
            localStorage.setItem('cookies_accepted', 'true');
            localStorage.setItem('notifications_subscribed', 'true');
            
            // Request native notification permission
            if ('Notification' in window) {
                Notification.requestPermission().then(permission => {
                    if (permission === 'granted') {
                        // Subscribe session in DB
                        fetch('/api/push-subscribe', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ session_id: chatSessionId })
                        })
                        .then(() => {
                            startNotificationPolling();
                        });
                    }
                });
            }
            closeConsentBanner();
        }

        let notificationPollTimer = null;
        let lastReceivedNotificationId = parseInt(localStorage.getItem('last_received_notification_id') || '0');

        function startNotificationPolling() {
            if (notificationPollTimer) return;
            
            // Poll immediately
            pollNotifications();
            // Poll every 8s
            notificationPollTimer = setInterval(pollNotifications, 8000);
        }

        function pollNotifications() {
            fetch(`/api/notifications/poll?session_id=${chatSessionId}&last_id=${lastReceivedNotificationId}`)
                .then(r => r.json())
                .then(data => {
                    if (data.success && data.notifications.length > 0) {
                        data.notifications.forEach(notif => {
                            // Update last received id
                            if (notif.id > lastReceivedNotificationId) {
                                lastReceivedNotificationId = notif.id;
                                localStorage.setItem('last_received_notification_id', lastReceivedNotificationId.toString());
                            }
                            
                            // Deliver Alert
                            deliverWebNotification(notif);
                        });
                    }
                })
                .catch(err => console.error('Error polling alerts:', err));
        }

        function deliverWebNotification(notif) {
            // Check if native Notification is permitted
            if ('Notification' in window && Notification.permission === 'granted') {
                const n = new Notification(notif.title, {
                    body: notif.message,
                    icon: '/favicon.ico',
                    tag: 'notif_' + notif.id
                });
                
                n.onclick = function() {
                    window.focus();
                    if (notif.url) {
                        window.location.href = notif.url;
                    }
                };
            } else {
                // Fallback to in-app webpage toast alert
                showInAppToast(notif);
            }
        }

        function showInAppToast(notif) {
            const container = document.getElementById('client-toast-container');
            if (!container) return;
            
            const toast = document.createElement('div');
            toast.className = 'toast-alert';
            toast.onclick = () => {
                if (notif.url) {
                    window.location.href = notif.url;
                }
            };
            
            toast.innerHTML = `
                <div style="width: 32px; height: 32px; border-radius: 6px; background-color: var(--primary-teal, #5ccbb3); display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: #0d1718;">
                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2.5" fill="none"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                </div>
                <div style="flex-grow: 1;">
                    <strong style="font-size: 0.85rem; font-weight: 700; color: white; display: block; margin-bottom: 2px;">${escapeHTML(notif.title)}</strong>
                    <p style="margin: 0; font-size: 0.75rem; color: #cbd5e1; line-height: 1.3;">${escapeHTML(notif.message)}</p>
                    ${notif.url ? `<span style="font-size: 0.65rem; color: var(--primary-teal, #5ccbb3); margin-top: 3px; display: block; text-decoration: underline;">Click to open update link</span>` : ''}
                </div>
            `;
            
            container.appendChild(toast);
            
            // Remove after 6s
            setTimeout(() => {
                toast.style.transition = 'opacity 0.5s ease';
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 500);
            }, 6000);
        }
    </script>
</body>
</html>

