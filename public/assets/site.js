(function () {
  var year = document.getElementById("year");
  if (year) year.textContent = String(new Date().getFullYear());

  var header = document.getElementById("siteHeader");
  if (header) {
    var onScroll = function () {
      header.classList.toggle("shadow-card", window.scrollY > 8);
    };
    onScroll();
    window.addEventListener("scroll", onScroll, { passive: true });
  }

  var menuBtn = document.getElementById("menuBtn");
  var mobileMenu = document.getElementById("mobileMenu");
  var iconOpen = document.getElementById("iconOpen");
  var iconClose = document.getElementById("iconClose");

  function setMenu(open) {
    if (!mobileMenu || !menuBtn) return;
    mobileMenu.classList.toggle("hidden", !open);
    if (iconOpen) iconOpen.classList.toggle("hidden", open);
    if (iconClose) iconClose.classList.toggle("hidden", !open);
    menuBtn.setAttribute("aria-expanded", open ? "true" : "false");
    menuBtn.setAttribute("aria-label", open ? "Close menu" : "Open menu");
  }

  if (menuBtn && mobileMenu) {
    menuBtn.addEventListener("click", function () {
      setMenu(mobileMenu.classList.contains("hidden"));
    });
    mobileMenu.querySelectorAll("a").forEach(function (link) {
      link.addEventListener("click", function () { setMenu(false); });
    });
  }

  var mobileServicesBtn = document.getElementById("mobileServicesBtn");
  var mobileServices = document.getElementById("mobileServices");
  var mobileServicesChevron = document.getElementById("mobileServicesChevron");
  if (mobileServicesBtn && mobileServices) {
    mobileServicesBtn.addEventListener("click", function () {
      var open = mobileServices.classList.contains("hidden");
      mobileServices.classList.toggle("hidden", !open);
      if (mobileServicesChevron) mobileServicesChevron.classList.toggle("rotate-180", open);
      mobileServicesBtn.setAttribute("aria-expanded", open ? "true" : "false");
    });
  }

  var servicesMenu = document.getElementById("servicesMenu");
  var servicesBtn = document.getElementById("servicesBtn");
  var servicesDropdown = document.getElementById("servicesDropdown");
  var servicesChevron = document.getElementById("servicesChevron");

  function setServices(open) {
    if (!servicesDropdown || !servicesBtn) return;
    servicesDropdown.classList.toggle("hidden", !open);
    if (servicesChevron) servicesChevron.classList.toggle("rotate-180", open);
    servicesBtn.setAttribute("aria-expanded", open ? "true" : "false");
  }

  if (servicesBtn && servicesMenu && servicesDropdown) {
    servicesBtn.addEventListener("click", function (event) {
      event.stopPropagation();
      setServices(servicesDropdown.classList.contains("hidden"));
    });
    servicesMenu.addEventListener("mouseenter", function () { setServices(true); });
    servicesMenu.addEventListener("mouseleave", function () { setServices(false); });
    servicesDropdown.querySelectorAll("a").forEach(function (link) {
      link.addEventListener("click", function () { setServices(false); });
    });
    document.addEventListener("click", function (event) {
      if (!servicesMenu.contains(event.target)) setServices(false);
    });
    document.addEventListener("keydown", function (event) {
      if (event.key === "Escape") setServices(false);
    });
  }

  var reduce = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  var servicesSwiper = null;
  if (window.Swiper) {
    if (document.querySelector(".hero-swiper")) {
      new Swiper(".hero-swiper", {
        loop: true,
        speed: 700,
        autoplay: reduce ? false : { delay: 5600, disableOnInteraction: false, pauseOnMouseEnter: true },
        pagination: { el: ".hero-pagination", clickable: true },
        navigation: { nextEl: ".hero-next", prevEl: ".hero-prev" }
      });
    }
    if (document.querySelector(".services-swiper")) {
      servicesSwiper = new Swiper(".services-swiper", {
        loop: true,
        loopAdditionalSlides: 3,
        speed: 700,
        autoplay: reduce ? false : { delay: 4200, disableOnInteraction: false, pauseOnMouseEnter: true },
        slidesPerView: 1.12,
        spaceBetween: 16,
        watchOverflow: true,
        navigation: { nextEl: ".services-next", prevEl: ".services-prev" },
        breakpoints: {
          640: { slidesPerView: 2, spaceBetween: 20 },
          1024: { slidesPerView: 3, spaceBetween: 24 }
        }
      });
    }
  }

  document.querySelectorAll("[data-service-slide]").forEach(function (link) {
    link.addEventListener("click", function () {
      setServices(false);
      setMenu(false);
      if (servicesSwiper) servicesSwiper.slideToLoop(Number(link.getAttribute("data-service-slide")));
    });
  });

  var reveals = document.querySelectorAll(".reveal");
  if (reduce || !("IntersectionObserver" in window)) {
    reveals.forEach(function (el) { el.classList.add("is-in"); });
  } else {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add("is-in");
          io.unobserve(entry.target);
        }
      });
    }, { threshold: 0.16, rootMargin: "0px 0px -40px 0px" });
    reveals.forEach(function (el) { io.observe(el); });
  }

  var tabs = document.querySelectorAll(".quote-tab");
  var quoteText = document.getElementById("quoteText");
  var quoteName = document.getElementById("quoteName");
  var quotePlace = document.getElementById("quotePlace");
  if (quoteText && tabs.length) {
    tabs.forEach(function (tab) {
      tab.addEventListener("click", function () {
        tabs.forEach(function (other) {
          var active = other === tab;
          other.setAttribute("aria-selected", active ? "true" : "false");
          other.classList.toggle("h-14", active);
          other.classList.toggle("w-14", active);
          other.classList.toggle("h-12", !active);
          other.classList.toggle("w-12", !active);
          other.classList.toggle("opacity-70", !active);
          other.classList.toggle("ring-gold", active);
          other.classList.toggle("ring-offset-2", active);
          other.classList.toggle("ring-offset-cream", active);
          other.classList.toggle("ring-transparent", !active);
        });
        quoteText.textContent = "“" + tab.getAttribute("data-quote") + "”";
        quoteName.textContent = tab.getAttribute("data-name");
        quotePlace.textContent = tab.getAttribute("data-place");
      });
    });
  }

  function openBook(trigger) {
    setMenu(false);
    setServices(false);
    if (window.openPickupScheduler) {
      window.openPickupScheduler();
      return;
    }
    var o = document.getElementById("o-pickup-scheduler-us0z1bz0a");
    var c = document.getElementById("c-pickup-scheduler-us0z1bz0a");
    var f = document.getElementById("f-pickup-scheduler-us0z1bz0a");
    if (o && c) {
      if (f && (!f.src || f.src === "about:blank")) {
        f.src = "https://pickup-scheduler.quickdrycleaning.com/en/TenderFold/pickup";
      }
      o.classList.add("s");
      c.classList.add("s");
      document.body.classList.add("m-o");
    }
  }

  function closeBook() {
    if (window.closePickupScheduler) {
      window.closePickupScheduler();
      return;
    }
    var o = document.getElementById("o-pickup-scheduler-us0z1bz0a");
    var c = document.getElementById("c-pickup-scheduler-us0z1bz0a");
    if (o && c) {
      o.classList.remove("s");
      c.classList.remove("s");
      document.body.classList.remove("m-o");
    }
  }

  document.querySelectorAll("[data-open-book], [data-open-pickup], #pickup-scheduler-us0z1bz0a").forEach(function (btn) {
    btn.addEventListener("click", function (e) {
      if (e && e.preventDefault) e.preventDefault();
      openBook(btn);
    });
  });

  var contactForm = document.getElementById("contactForm");
  var contactMsg = document.getElementById("contactMsg");
  if (contactForm) {
    contactForm.addEventListener("submit", function (event) {
      event.preventDefault();
      var data = new FormData(contactForm);
      var text = [
        "Tender Fold contact message",
        "Name: " + data.get("name"),
        "Phone: " + data.get("phone"),
        "Counter: " + data.get("store"),
        "Message: " + data.get("message")
      ].join("\n");
      if (contactMsg) {
        contactMsg.textContent = "Opening WhatsApp with your message.";
        contactMsg.classList.remove("hidden");
      }
      window.open("https://wa.me/919582466445?text=" + encodeURIComponent(text), "_blank", "noopener");
    });
  }

  var storeMap = document.getElementById("storeMap");
  if (storeMap) {
    document.querySelectorAll("[data-map-src]").forEach(function (tab) {
      tab.addEventListener("click", function () {
        storeMap.src = tab.getAttribute("data-map-src");
        storeMap.title = tab.textContent.trim() + " on Google Maps";
        document.querySelectorAll("[data-map-src]").forEach(function (other) {
          var on = other === tab;
          other.setAttribute("aria-selected", on ? "true" : "false");
          other.classList.toggle("bg-navy", on);
          other.classList.toggle("text-white", on);
          other.classList.toggle("bg-white", !on);
          other.classList.toggle("text-navy", !on);
          other.classList.toggle("shadow-card", !on);
        });
      });
    });
  }

  var newsForm = document.getElementById("newsForm");
  var newsMsg = document.getElementById("newsMsg");
  if (newsForm) {
    newsForm.addEventListener("submit", function (event) {
      event.preventDefault();
      var email = new FormData(newsForm).get("email");
      var text = "Please add me to Tender Fold care notes.\nEmail: " + email;
      if (newsMsg) {
        newsMsg.textContent = "Opening WhatsApp so we can confirm this email.";
        newsMsg.classList.remove("hidden");
      }
      window.open("https://wa.me/919582466445?text=" + encodeURIComponent(text), "_blank", "noopener");
      newsForm.reset();
    });
  }

  var rates = document.getElementById("rates");
  if (rates) {
    document.querySelectorAll('a[href="#rates"]').forEach(function (link) {
      link.addEventListener("click", function () {
        rates.open = true;
      });
    });
  }
})();
