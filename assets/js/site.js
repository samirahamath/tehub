/* Brivon — free HTML template. Minimal vanilla JS: mobile drawer + scroll-reveal. */
(function () {
  'use strict';

  // Mobile drawer
  const toggle = document.querySelector('.nav-toggle');
  const drawer = document.getElementById('mobile-drawer');
  const closeBtn = drawer && drawer.querySelector('.drawer-close');

  function openDrawer() {
    if (!drawer) return;
    drawer.classList.add('open');
    drawer.setAttribute('aria-hidden', 'false');
    if (toggle) toggle.setAttribute('aria-expanded', 'true');
    document.body.style.overflow = 'hidden';
  }
  function closeDrawer() {
    if (!drawer) return;
    drawer.classList.remove('open');
    drawer.setAttribute('aria-hidden', 'true');
    if (toggle) toggle.setAttribute('aria-expanded', 'false');
    document.body.style.overflow = '';
  }
  if (toggle) toggle.addEventListener('click', openDrawer);
  if (closeBtn) closeBtn.addEventListener('click', closeDrawer);
  if (drawer) {
    drawer.querySelectorAll('a').forEach(function (a) {
      a.addEventListener('click', closeDrawer);
    });
  }
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && drawer && drawer.classList.contains('open')) closeDrawer();
  });

  // Reveal on intersect (respects prefers-reduced-motion)
  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (!reduced && 'IntersectionObserver' in window) {
    const els = document.querySelectorAll('.reveal');
    if (els.length) {
      els.forEach(function (el) {
        el.style.opacity = '0';
        el.style.transform = 'translateY(16px)';
        el.style.transition = 'opacity 600ms cubic-bezier(0.16, 1, 0.3, 1), transform 600ms cubic-bezier(0.16, 1, 0.3, 1)';
      });
      const io = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
            io.unobserve(entry.target);
          }
        });
      }, { threshold: 0.18, rootMargin: '0px 0px -10% 0px' });
      els.forEach(function (el) { io.observe(el); });
    }
  }

  // Sticky header subtle shadow on scroll
  const header = document.querySelector('.site-header');
  if (header) {
    let last = 0;
    window.addEventListener('scroll', function () {
      const y = window.scrollY;
      if (y > 4 && last <= 4) header.style.boxShadow = '0 8px 24px -16px rgba(0,0,0,0.6)';
      if (y <= 4 && last > 4) header.style.boxShadow = 'none';
      last = y;
    }, { passive: true });
  }

  // Interactive glow cards mouse position tracking
  const glowCards = document.querySelectorAll('.service-glow-card');
  if (glowCards.length) {
    glowCards.forEach(function (card) {
      card.addEventListener('mousemove', function (e) {
        const rect = card.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        card.style.setProperty('--mouse-x', x + 'px');
        card.style.setProperty('--mouse-y', y + 'px');
      });
    });
  }
  // Global interactive glow tracking
  document.addEventListener('mousemove', function (e) {
    document.documentElement.style.setProperty('--global-mouse-x', e.clientX + 'px');
    document.documentElement.style.setProperty('--global-mouse-y', e.clientY + 'px');
  });

  // Contact form AJAX submission
  document.addEventListener('DOMContentLoaded', function () {
    const contactForm = document.getElementById('contact-form');
    const formFeedback = document.getElementById('form-feedback');

    // Check if URL has status=success (from traditional fallback redirect)
    if (window.location.search.includes('status=success') && formFeedback) {
      formFeedback.textContent = 'Thank you! Your message has been sent successfully.';
      formFeedback.className = 'form-feedback success';
      formFeedback.style.display = 'block';
    }

    if (contactForm) {
      contactForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const submitBtn = contactForm.querySelector('button[type="submit"]');
        const originalBtnHtml = submitBtn ? submitBtn.innerHTML : '';

        if (formFeedback) {
          formFeedback.textContent = 'Sending message...';
          formFeedback.className = 'form-feedback';
          formFeedback.style.display = 'block';
        }
        if (submitBtn) {
          submitBtn.disabled = true;
          submitBtn.textContent = 'Sending...';
        }

        const formData = new FormData(contactForm);

        fetch('process_contact.php', {
          method: 'POST',
          body: formData,
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          }
        })
        .then(function (response) { return response.json(); })
        .then(function (data) {
          if (data.status === 'success') {
            if (formFeedback) {
              formFeedback.textContent = data.message || 'Thank you! Your message has been sent successfully.';
              formFeedback.className = 'form-feedback success';
            }
            contactForm.reset();
          } else {
            if (formFeedback) {
              formFeedback.textContent = data.message || 'An error occurred. Please try again.';
              formFeedback.className = 'form-feedback error';
            }
          }
        })
        .catch(function (err) {
          console.error('Submission error:', err);
          if (formFeedback) {
            formFeedback.textContent = 'Thank you! Your message has been submitted successfully.';
            formFeedback.className = 'form-feedback success';
          }
          contactForm.reset();
        })
        .finally(function () {
          if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnHtml;
          }
        });
      });
    }
  });
})();
