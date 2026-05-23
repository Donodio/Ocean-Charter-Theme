/**
 * Ocean Charter — main.js
 * Handles: scroll-reveal, counter-up, hamburger nav, gallery lightbox, package filter tabs
 */
(function () {
  'use strict';

  /* ============================================================
     SCROLL REVEAL (IntersectionObserver)
     ============================================================ */
  function initScrollReveal() {
    const elements = document.querySelectorAll('[data-animate]');
    if (!elements.length) return;

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            const el = entry.target;
            const delay = parseFloat(el.dataset.delay || 0);
            el.style.transitionDelay = delay + 's';
            el.classList.add('is-visible');
            observer.unobserve(el);
          }
        });
      },
      { threshold: 0.15, rootMargin: '0px 0px -40px 0px' }
    );

    elements.forEach((el) => observer.observe(el));
  }

  /* ============================================================
     COUNTER UP (stats bar)
     ============================================================ */
  function animateCounter(el) {
    const target = parseFloat(el.dataset.target);
    const suffix = el.dataset.suffix || '';
    const isDecimal = target % 1 !== 0;
    const duration = 1500;
    const start = performance.now();

    // Respect reduced motion
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
      el.textContent = (isDecimal ? target.toFixed(1) : target) + suffix;
      return;
    }

    function easeOutQuart(t) { return 1 - Math.pow(1 - t, 4); }

    function update(now) {
      const elapsed = now - start;
      const progress = Math.min(elapsed / duration, 1);
      const eased = easeOutQuart(progress);
      const current = target * eased;

      el.textContent = (isDecimal ? current.toFixed(1) : Math.round(current)) + suffix;

      if (progress < 1) {
        requestAnimationFrame(update);
      } else {
        el.textContent = (isDecimal ? target.toFixed(1) : target) + suffix;
      }
    }

    requestAnimationFrame(update);
  }

  function initCounterUp() {
    const counters = document.querySelectorAll('.oc-stat__number[data-target]');
    if (!counters.length) return;

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            animateCounter(entry.target);
            observer.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.5 }
    );

    counters.forEach((el) => observer.observe(el));
  }

  /* ============================================================
     HAMBURGER NAV
     ============================================================ */
  function initHamburger() {
    const btn = document.getElementById('oc-hamburger');
    const nav = document.getElementById('oc-mobile-nav');
    if (!btn || !nav) return;

    function openMenu() {
      btn.classList.add('is-open');
      btn.setAttribute('aria-expanded', 'true');
      btn.setAttribute('aria-label', 'Close menu');
      nav.classList.add('is-open');
      nav.removeAttribute('hidden');
      document.body.classList.add('menu-open');
      document.addEventListener('click', handleOutsideClick);
      document.addEventListener('keydown', handleEsc);
    }

    function closeMenu() {
      btn.classList.remove('is-open');
      btn.setAttribute('aria-expanded', 'false');
      btn.setAttribute('aria-label', 'Open menu');
      nav.classList.remove('is-open');
      document.body.classList.remove('menu-open');
      document.removeEventListener('click', handleOutsideClick);
      document.removeEventListener('keydown', handleEsc);
    }

    function handleOutsideClick(e) {
      const header = document.getElementById('oc-header');
      if (header && !header.contains(e.target)) closeMenu();
    }

    function handleEsc(e) {
      if (e.key === 'Escape') closeMenu();
    }

    btn.addEventListener('click', (e) => {
      e.stopPropagation();
      btn.classList.contains('is-open') ? closeMenu() : openMenu();
    });

    // Close on nav link click
    nav.querySelectorAll('a').forEach((link) => {
      link.addEventListener('click', closeMenu);
    });
  }

  /* ============================================================
     SCROLL HEADER EFFECT
     ============================================================ */
  function initScrollHeader() {
    const header = document.getElementById('oc-header');
    if (!header) return;

    let ticking = false;

    function updateHeader() {
      if (window.scrollY > 80) {
        header.classList.add('scrolled');
      } else {
        header.classList.remove('scrolled');
      }
      ticking = false;
    }

    window.addEventListener('scroll', () => {
      if (!ticking) {
        requestAnimationFrame(updateHeader);
        ticking = true;
      }
    }, { passive: true });

    updateHeader();
  }

  /* ============================================================
     PACKAGE FILTER TABS
     ============================================================ */
  function initPackageFilter() {
    const tabContainer = document.querySelector('.oc-pkg-tabs');
    if (!tabContainer) return;

    const tabs = tabContainer.querySelectorAll('[data-tab]');
    const cards = document.querySelectorAll('.oc-pkg-grid [data-type]');

    tabs.forEach((tab) => {
      tab.addEventListener('click', () => {
        tabs.forEach((t) => t.classList.remove('is-active'));
        tab.classList.add('is-active');

        const filter = tab.dataset.tab;

        cards.forEach((card) => {
          if (filter === 'all' || card.dataset.type === filter) {
            card.style.display = '';
          } else {
            card.style.display = 'none';
          }
        });
      });
    });
  }

  /* ============================================================
     DESTINATION REGION FILTER
     ============================================================ */
  function initDestinationFilter() {
    const tabContainer = document.querySelector('.oc-dest-tabs');
    if (!tabContainer) return;

    const tabs = tabContainer.querySelectorAll('[data-tab]');
    const cards = document.querySelectorAll('.oc-dest-grid [data-region]');

    tabs.forEach((tab) => {
      tab.addEventListener('click', () => {
        tabs.forEach((t) => t.classList.remove('is-active'));
        tab.classList.add('is-active');

        const filter = tab.dataset.tab;

        cards.forEach((card) => {
          if (filter === 'all' || card.dataset.region === filter) {
            card.style.display = '';
            card.style.animation = 'fadeIn 0.3s ease both';
          } else {
            card.style.display = 'none';
          }
        });
      });
    });
  }

  /* ============================================================
     CSS LIGHTBOX ESC KEY SUPPORT
     ============================================================ */
  function initLightboxEsc() {
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && window.location.hash.startsWith('#photo-')) {
        history.pushState('', document.title, window.location.pathname + window.location.search);
      }
    });
  }

  /* ============================================================
     FLEET FILTER (archive-boat.php)
     ============================================================ */
  function initFleetFilter() {
    // Pre-fill destination input from URL params (for search form on fleet page if present)
    const params = new URLSearchParams(window.location.search);
    const filterForm = document.getElementById('oc-fleet-filter');
    if (filterForm) {
      filterForm.querySelectorAll('[name]').forEach((input) => {
        const val = params.get(input.name);
        if (val) input.value = val;
      });
    }
  }

  /* ============================================================
     HOMEPAGE SEARCH — Flatpickr date picker
     ============================================================ */
  function initHomeSearch() {
    const dateInput = document.getElementById('oc-search-dates')
                   || document.querySelector('input[name="dates"]');
    if (!dateInput || typeof flatpickr === 'undefined') return;
    // Skip if already initialized
    if (dateInput._flatpickr) return;

    flatpickr(dateInput, {
      mode: 'range',
      minDate: 'today',
      dateFormat: 'Y-m-d',
      disableMobile: false,
      allowInput: false,
    });
  }

  /* ============================================================
     BBC SEARCH FORM — Replace native date pickers with Flatpickr
     ============================================================ */
  function initBBCDatePickers() {
    if (typeof flatpickr === 'undefined') return;

    const fromEl = document.querySelector('.bbc-date-input[name="date_from"]');
    const toEl   = document.querySelector('.bbc-date-input[name="date_to"]');

    if (!fromEl && !toEl) return;

    // type="date" means the browser handles the picker natively — Flatpickr can't
    // intercept it. Switching to "text" hands full control to Flatpickr.
    if (fromEl) { fromEl.type = 'text'; fromEl.placeholder = 'Departure date'; }
    if (toEl)   { toEl.type   = 'text'; toEl.placeholder   = 'Return date';    }

    let toFP = null;

    if (toEl && !toEl._flatpickr) {
      toFP = flatpickr(toEl, {
        dateFormat: 'Y-m-d',      // value submitted in the URL
        altInput: true,
        altFormat: 'D, d M Y',   // friendly display in the input
        minDate: 'today',
        disableMobile: true,
        allowInput: false,
      });
    } else if (toEl) {
      toFP = toEl._flatpickr;
    }

    if (fromEl && !fromEl._flatpickr) {
      flatpickr(fromEl, {
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'D, d M Y',
        minDate: 'today',
        disableMobile: true,
        allowInput: false,
        onChange(selected) {
          if (toFP && selected[0]) toFP.set('minDate', selected[0]);
        },
      });
    }

  }

  /* ============================================================
     GOOGLE PLACES AUTOCOMPLETE — homepage destination
     Called as callback by Google Maps JS loader
     ============================================================ */
  window.initOCPlaces = function () {
    if (typeof google === 'undefined' || !google.maps || !google.maps.places) return;

    const input = document.getElementById('oc-search-destination')
               || document.querySelector('input[name="location"]')
               || document.querySelector('input[name="destination"]');
    if (!input) return;

    const autocomplete = new google.maps.places.Autocomplete(input, {
      types: ['(regions)'],
    });

    autocomplete.addListener('place_changed', function () {
      const place = autocomplete.getPlace();
      if (place && place.name) {
        input.value = place.name;
      }
    });

    // Also init the date picker now that Google Maps has loaded
    initHomeSearch();
  };

  /* ============================================================
     INIT
     ============================================================ */
  document.addEventListener('DOMContentLoaded', () => {
    initScrollReveal();
    initCounterUp();
    initHamburger();
    initScrollHeader();
    initPackageFilter();
    initDestinationFilter();
    initLightboxEsc();
    initFleetFilter();
    initHomeSearch();
    initBBCDatePickers();
  });
})();
