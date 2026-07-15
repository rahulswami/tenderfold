<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SumitZ Orthopaedic & Physiotherapy Clinic in Baner, Pune')</title>
    <meta name="description" content="@yield('meta_description', 'SumitZ Orthopaedic & Physiotherapy Clinic is Pune\'s premier clinic in Baner. Led by Dr. Sumit, we offer expert joint, spine, fracture and sports injury care.')">
    
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
                <a href="#appointment-section" class="btn btn-primary btn-sm" onclick="event.preventDefault(); document.getElementById('callback-dialog').style.display='flex';"><i data-lucide="calendar"></i> Book Appointment</a>
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
                    @if(!empty($site['social_facebook']))
                        <a href="{{ $site['social_facebook'] }}" target="_blank" class="social-icon" aria-label="Facebook"><i data-lucide="facebook"></i></a>
                    @endif
                    @if(!empty($site['social_instagram']))
                        <a href="{{ $site['social_instagram'] }}" target="_blank" class="social-icon" aria-label="Instagram"><i data-lucide="instagram"></i></a>
                    @endif
                    @if(!empty($site['social_linkedin']))
                        <a href="{{ $site['social_linkedin'] }}" target="_blank" class="social-icon" aria-label="LinkedIn"><i data-lucide="linkedin"></i></a>
                    @endif
                    @if(!empty($site['social_twitter']))
                        <a href="{{ $site['social_twitter'] }}" target="_blank" class="social-icon" aria-label="Twitter"><i data-lucide="twitter"></i></a>
                    @endif
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
                    <li>
                        <i data-lucide="map-pin"></i>
                        <span>{{ $site['address'] ?? 'Krishna Avenue, Above Dominos, Baner road, Baner, Pune, Maharashtra 411045' }}</span>
                    </li>
                    <li>
                        <i data-lucide="phone"></i>
                        <a href="tel:{{ $site['phone'] ?? '+919672777749' }}">{{ $site['phone'] ?? '+91 96727 77749' }}</a>
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
            <button class="dialog-close" aria-label="Close Modal">&times;</button>
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
            <button class="dialog-close" aria-label="Close Modal">&times;</button>
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
        function closeDialog(id) {
            document.getElementById(id).style.display = 'none';
        }
    </script>
    
    <!-- Floating Chat Widget -->
    <div id="clinic-chat-widget" style="position: fixed; bottom: 20px; right: 20px; z-index: 1000; font-family: 'Outfit', sans-serif;">
        <!-- Floating Toggle Button -->
        <button id="chat-toggle-btn" onclick="toggleChatWindow()" style="width: 60px; height: 60px; border-radius: 50%; background-color: var(--primary-teal); color: white; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.15); cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; position: relative;">
            <i data-lucide="message-square" id="chat-icon-open" style="width: 28px; height: 28px;"></i>
            <i data-lucide="x" id="chat-icon-close" style="width: 28px; height: 28px; display: none;"></i>
            <span id="chat-unread-badge" style="position: absolute; top: -5px; right: -5px; background-color: #ef4444; color: white; font-size: 0.75rem; font-weight: 700; width: 22px; height: 22px; border-radius: 50%; display: none; align-items: center; justify-content: center; border: 2px solid white;">0</span>
        </button>

        <!-- Chat Window -->
        <div id="chat-window" style="position: absolute; bottom: 75px; right: 0; width: 350px; height: 480px; background-color: white; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.15); display: none; flex-direction: column; overflow: hidden; border: 1px solid var(--border-color); transition: all 0.3s ease;">
            <!-- Chat Header -->
            <div style="background: linear-gradient(135deg, var(--primary-teal) 0%, #163638 100%); color: white; padding: 1.25rem; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid rgba(255,255,255,0.1);">
                <div style="width: 10px; height: 10px; background-color: #22c55e; border-radius: 50%; box-shadow: 0 0 8px #22c55e;"></div>
                <div>
                    <strong style="display: block; font-size: 1rem; font-weight: 700;">Clinic Assistant</strong>
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
                openIcon.style.display = 'block';
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
</body>
</html>
