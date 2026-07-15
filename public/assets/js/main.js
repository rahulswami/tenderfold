/**
 * MLG FINEDGE GLOBAL JAVASCRIPT
 * Description: Nav bars, accordions, carousel, exit-intent popup, form submissions, and animations.
 */

document.addEventListener('DOMContentLoaded', () => {
    initNavigation();
    initFAQ();
    initCarousel();
    initExitIntent();
    initForms();
    initScrollAnimations();
    initWidgets();
    initCounters();
    initParallax();
});

/* --- Navigation & Menu --- */
function initNavigation() {
    const header = document.querySelector('.header');
    const mobileToggle = document.querySelector('.mobile-toggle');
    const navLinks = document.querySelector('.nav-links');
    
    // Scroll event for styling header
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });
    
    // Mobile menu toggle
    if (mobileToggle && navLinks) {
        mobileToggle.addEventListener('click', () => {
            navLinks.classList.toggle('open');
            // Toggle hamburger animation state if needed
            const spans = mobileToggle.querySelectorAll('span');
            if (navLinks.classList.contains('open')) {
                spans[0].style.transform = 'rotate(45deg) translate(5px, 5px)';
                spans[1].style.opacity = '0';
                spans[2].style.transform = 'rotate(-45deg) translate(7px, -8px)';
            } else {
                spans[0].style.transform = 'none';
                spans[1].style.opacity = '1';
                spans[2].style.transform = 'none';
            }
        });
        
        // Close menu when clicking on links
        navLinks.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', (e) => {
                const isDropdownParent = link.closest('.nav-dropdown-item') && link.classList.contains('nav-item');
                if (isDropdownParent && window.innerWidth <= 1100) {
                    const parent = link.closest('.nav-dropdown-item');
                    const isOpen = parent.classList.contains('active-mobile');
                    if (!isOpen) {
                        e.preventDefault();
                        e.stopPropagation();
                        document.querySelectorAll('.nav-dropdown-item').forEach(el => el.classList.remove('active-mobile'));
                        parent.classList.add('active-mobile');
                        return;
                    }
                }
                
                navLinks.classList.remove('open');
                const spans = mobileToggle.querySelectorAll('span');
                if (spans.length >= 3) {
                    spans[0].style.transform = 'none';
                    spans[1].style.opacity = '1';
                    spans[2].style.transform = 'none';
                }
            });
        });
    }
}

/* --- FAQ Accordion --- */
function initFAQ() {
    const faqHeaders = document.querySelectorAll('.faq-header');
    
    faqHeaders.forEach(header => {
        header.addEventListener('click', () => {
            const item = header.parentElement;
            const body = item.querySelector('.faq-body');
            const isActive = item.classList.contains('active');
            
            // Close other items
            const activeItems = document.querySelectorAll('.faq-item.active');
            activeItems.forEach(activeItem => {
                if (activeItem !== item) {
                    activeItem.classList.remove('active');
                    activeItem.querySelector('.faq-body').style.maxHeight = '0px';
                }
            });
            
            if (isActive) {
                item.classList.remove('active');
                body.style.maxHeight = '0px';
            } else {
                item.classList.add('active');
                body.style.maxHeight = body.scrollHeight + 'px';
            }
        });
    });
}

/* --- Testimonials Carousel Slider --- */
function initCarousel() {
    const track = document.querySelector('.carousel-track');
    if (!track) return;
    
    const slides = Array.from(track.children);
    const prevBtn = document.querySelector('.carousel-btn-prev');
    const nextBtn = document.querySelector('.carousel-btn-next');
    const dotsContainer = document.querySelector('.carousel-dots');
    
    let currentIndex = 0;
    let slideWidth = slides[0].getBoundingClientRect().width;
    
    // Handle window resize for slide width adjustments
    window.addEventListener('resize', () => {
        slideWidth = slides[0].getBoundingClientRect().width;
        moveToSlide(currentIndex);
    });
    
    // Create navigation dots
    slides.forEach((_, index) => {
        const dot = document.createElement('div');
        dot.classList.add('carousel-dot');
        if (index === 0) dot.classList.add('active');
        dot.addEventListener('click', () => {
            currentIndex = index;
            moveToSlide(index);
        });
        dotsContainer.appendChild(dot);
    });
    
    const dots = Array.from(dotsContainer.children);
    
    function moveToSlide(index) {
        track.style.transform = `translateX(-${index * slideWidth}px)`;
        dots.forEach(d => d.classList.remove('active'));
        dots[index].classList.add('active');
    }
    
    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            if (currentIndex === slides.length - 1) {
                currentIndex = 0;
            } else {
                currentIndex++;
            }
            moveToSlide(currentIndex);
        });
    }
    
    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            if (currentIndex === 0) {
                currentIndex = slides.length - 1;
            } else {
                currentIndex--;
            }
            moveToSlide(currentIndex);
        });
    }
    
    // Auto slide transition (every 6 seconds)
    setInterval(() => {
        if (currentIndex === slides.length - 1) {
            currentIndex = 0;
        } else {
            currentIndex++;
        }
        moveToSlide(currentIndex);
    }, 6000);
}

/* --- Exit Intent & Modals System --- */
function initExitIntent() {
    let exitTriggered = false;
    
    // Listen for mouse movement to top of document
    document.addEventListener('mouseleave', (e) => {
        if (e.clientY < 15 && !exitTriggered) {
            // Check session storage so we don't annoy the user
            const hasSeen = sessionStorage.getItem('mlg_exit_pop_seen');
            if (!hasSeen) {
                exitTriggered = true;
                sessionStorage.setItem('mlg_exit_pop_seen', 'true');
                openDialog('exit-intent-dialog');
            }
        }
    });
    
    // Modal Overlay click listener (closes dialogs when clicking backdrop)
    document.querySelectorAll('.dialog-overlay').forEach(overlay => {
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                closeDialog(overlay.id);
            }
        });
    });
    
    // Close button click listener
    document.querySelectorAll('.dialog-close').forEach(btn => {
        btn.addEventListener('click', () => {
            const overlay = btn.closest('.dialog-overlay');
            if (overlay) closeDialog(overlay.id);
        });
    });
}

function openDialog(id) {
    const dialog = document.getElementById(id);
    if (dialog) {
        dialog.classList.add('open');
        document.body.style.overflow = 'hidden'; // disable background scrolling
    }
}

function closeDialog(id) {
    const dialog = document.getElementById(id);
    if (dialog) {
        dialog.classList.remove('open');
        document.body.style.overflow = ''; // restore scrolling
    }
}

/* --- Form Submissions (Mock Backend) & Multi-Step Logic --- */
function initForms() {
    // Multi-step Wizard Navigation
    const stepPanels = document.querySelectorAll('.form-step-panel');
    if (stepPanels.length > 0) {
        const dots = document.querySelectorAll('.form-step-dot');
        const nextBtns = document.querySelectorAll('.btn-next-step');
        const prevBtns = document.querySelectorAll('.btn-prev-step');
        let currentStep = 0;
        
        function showStep(stepIndex) {
            stepPanels.forEach((panel, idx) => {
                if (idx === stepIndex) {
                    panel.classList.add('active');
                } else {
                    panel.classList.remove('active');
                }
            });
            
            // Update step indicators
            dots.forEach((dot, idx) => {
                if (idx === stepIndex) {
                    dot.classList.add('active');
                    dot.classList.remove('completed');
                } else if (idx < stepIndex) {
                    dot.classList.add('completed');
                    dot.classList.remove('active');
                } else {
                    dot.classList.remove('active', 'completed');
                }
            });
        }
        
        function validateStep(stepIndex) {
            const currentPanel = stepPanels[stepIndex];
            const requiredInputs = currentPanel.querySelectorAll('[required]');
            let isValid = true;
            
            requiredInputs.forEach(input => {
                if (!input.value.trim()) {
                    isValid = false;
                    input.style.borderColor = 'red';
                    // Reset border color on input change
                    input.addEventListener('input', function resetBorder() {
                        input.style.borderColor = '';
                        input.removeEventListener('input', resetBorder);
                    });
                }
            });
            
            return isValid;
        }
        
        nextBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                if (validateStep(currentStep)) {
                    if (currentStep < stepPanels.length - 1) {
                        currentStep++;
                        showStep(currentStep);
                    }
                }
            });
        });
        
        prevBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                if (currentStep > 0) {
                    currentStep--;
                    showStep(currentStep);
                }
            });
        });
    }
    
    // Form submissions are handled natively by the HTML forms submitting to the backend routes.
}

/* --- Scroll Animations via IntersectionObserver --- */
function initScrollAnimations() {
    const animatedElements = document.querySelectorAll('.animate-on-scroll');
    
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animated');
                    observer.unobserve(entry.target); // trigger animation once
                }
            });
        }, {
            threshold: 0.15,
            rootMargin: '0px 0px -50px 0px'
        });
        
        animatedElements.forEach(el => observer.observe(el));
    } else {
        // Fallback for browsers that don't support IntersectionObserver
        animatedElements.forEach(el => el.classList.add('animated'));
    }
}

/* --- Floating Widget Toggles --- */
function initWidgets() {
    const btnTop = document.querySelector('.widget-top');
    
    window.addEventListener('scroll', () => {
        if (window.scrollY > 400) {
            if (btnTop) btnTop.style.display = 'flex';
        } else {
            if (btnTop) btnTop.style.display = 'none';
        }
    });
    
    if (btnTop) {
        btnTop.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }
}

// Global hook to trigger callback modal manually from button hooks
window.triggerCallbackModal = function() {
    openDialog('callback-dialog');
};

/* --- Animated Stats Counters --- */
function initCounters() {
    const counters = document.querySelectorAll('.counter-num');
    if (counters.length === 0) return;
 
    const animateCounter = (counter) => {
        const target = +counter.getAttribute('data-target');
        const duration = 1500; // 1.5 seconds count duration
        const stepTime = 30; // ~33 fps
        const totalSteps = duration / stepTime;
        let currentStep = 0;
 
        const updateValue = () => {
            currentStep++;
            const progress = currentStep / totalSteps;
            // Easing: easeOutQuad
            const easedProgress = progress * (2 - progress);
            const value = Math.floor(easedProgress * target);
 
            counter.innerText = value.toLocaleString('en-IN');
 
            if (currentStep < totalSteps) {
                setTimeout(updateValue, stepTime);
            } else {
                counter.innerText = target.toLocaleString('en-IN');
            }
        };
 
        updateValue();
    };
 
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const statsRow = entry.target;
                    statsRow.querySelectorAll('.counter-num').forEach(c => animateCounter(c));
                    observer.unobserve(statsRow); // animate once
                }
            });
        }, { threshold: 0.15 });
 
        const statsRow = document.querySelector('.stats-row');
        if (statsRow) observer.observe(statsRow);
    } else {
        counters.forEach(c => animateCounter(c));
    }
}

/* --- Premium Parallax Fallback for Unsupported Browsers --- */
function initParallax() {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    if (!CSS.supports('(animation-timeline: view()) and (animation-range: entry)')) {
        const shapes = document.querySelectorAll('.parallax-shape');
        const bgLayers = document.querySelectorAll('.parallax-bg-layer');

        if (shapes.length === 0 && bgLayers.length === 0) return;

        window.addEventListener('scroll', () => {
            const scrollY = window.scrollY;

            shapes.forEach(shape => {
                const parentHero = shape.closest('.hero');
                if (!parentHero) return;

                const rect = parentHero.getBoundingClientRect();
                if (rect.bottom > 0 && rect.top < window.innerHeight) {
                    const scrollFactor = (window.innerHeight - rect.top) / (window.innerHeight + rect.height);
                    const speed = shape.classList.contains('shape-1') ? -80 : 80;
                    shape.style.transform = `translateY(${scrollFactor * speed}px)`;
                }
            });

            bgLayers.forEach(bgLayer => {
                const parentSection = bgLayer.closest('.final-cta-section');
                if (!parentSection) return;

                const rect = parentSection.getBoundingClientRect();
                if (rect.bottom > 0 && rect.top < window.innerHeight) {
                    const scrollFactor = (window.innerHeight - rect.top) / (window.innerHeight + rect.height);
                    bgLayer.style.transform = `translateY(${scrollFactor * 80}px)`;
                }
            });
        });
    }
}
