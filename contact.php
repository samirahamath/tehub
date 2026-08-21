<?php require_once __DIR__ . '/visitor_logger.php'; ?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>THE EXPERT HUB · Contact — Premium Development Agency</title>
  <meta name="description"
    content="Contact THE EXPERT HUB for project inquiries, press, or careers. Full inquiry form, office addresses &amp; open roles. We reply within 48 hours on working days." />
  <meta name="robots" content="index, follow" />
  <meta name="author" content="THE EXPERT HUB" />
  <link rel="canonical" href="https://tehub.in/contact.php" />
  <meta property="og:type" content="website" />
  <meta property="og:title" content="THE EXPERT HUB · Contact — Premium Development Agency" />
  <meta property="og:description" content="Contact THE EXPERT HUB for project inquiries, press, or careers. Full inquiry form, office addresses &amp; open roles. We reply within 48 hours on working days." />
  <meta property="og:url" content="https://tehub.in/contact.php" />
  <meta property="og:site_name" content="THE EXPERT HUB" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Boldonse&family=Inter+Tight:wght@400;500;600;700&family=Geist+Mono:wght@400;500&display=swap" />
  <link rel="stylesheet" href="assets/css/styles.css?v=1.4" />
  <link rel="stylesheet" href="assets/css/chatbot.css" />
</head>

<body>
  <a class="skip-link" href="#main">Skip to content</a>

  <header class="site-header">
    <div class="container container--wide">
      <nav class="nav" aria-label="Primary">
        <a class="brand" href="index.php"><span class="brand-mark" aria-hidden="true"></span> THE EXPERT HUB</a>
        <div class="nav-links" role="navigation">
          <a href="index.php">Index</a>
          <a href="d-r.php">D-R</a>
          <a href="services.php">Services</a>
          <a href="solutions.php">Solutions</a>
          <a href="contact.php" aria-current="page">Contact</a>
        </div>
        <div class="nav-cta-row">
          <a href="#intake" class="btn btn--primary btn--sm">Start a project
            <svg class="arrow" width="14" height="10" viewBox="0 0 14 10" fill="none" aria-hidden="true">
              <path d="M1 5h12m0 0L9 1m4 4L9 9" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"
                stroke-linejoin="round" />
            </svg>
          </a>
          <button class="nav-toggle" aria-label="Open menu" aria-expanded="false" aria-controls="mobile-drawer"><span
              aria-hidden="true"></span></button>
        </div>
      </nav>
    </div>
  </header>

  <div class="mobile-drawer" id="mobile-drawer" aria-hidden="true">
    <button class="drawer-close" aria-label="Close menu">Close</button>
    <a href="index.php">Index</a>
    <a href="d-r.php">D-R</a>
    <a href="services.php">Services</a>
    <a href="solutions.php">Solutions</a>
    <a href="contact.php">Contact</a>
  </div>

  <main id="main">

    <!-- Hero -->
    <section class="hero">
      <div class="container container--wide">
        <div class="hero-grid">
          <div class="hero-text">
            <span class="eyebrow"><span class="dot" aria-hidden="true"></span>Reply within 48 working hours</span>
            <h1 class="hero-headline">
              Send a real<br />
              <span class="lime">first note,</span> get<br />
              a real reply.
            </h1>
            <p class="hero-sub">
              We do not staff our intake inbox with bot autoresponders. Every note that lands in our inbox is read by Anya or Eli — our product managers — and answered with a real technical opinion. We aim to reply within 48 hours.
            </p>
            <div class="hero-cta-row">
              <a class="btn btn--primary btn--lg" href="#intake">New project</a>
              <a class="btn btn--ghost btn--lg" href="#channels">Other ways to reach us</a>
            </div>
            <div class="hero-meta">
              <span><strong>D-U-N-S&reg; 30-704-2520</strong> &middot; Verified Entity</span>
              <span aria-hidden="true">&middot;</span>
              <span><strong>48hr</strong> &middot; target reply</span>
              <span aria-hidden="true">&middot;</span>
              <span><strong>8yr</strong> &middot; in business</span>
            </div>
          </div>
          <div class="hero-media">
            <video src="assets/img/contact.mp4" autoplay loop muted playsinline alt="THE EXPERT HUB contact hero video showcase"></video>
            <div class="floating-tag ft-top">
              <span class="pill">Inbox</span>
              ANYA · NEW YORK
            </div>
            <div class="floating-tag ft-bottom">
              hello @ tehub.in
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Channels -->
    <section id="channels">
      <div class="container container--wide">
        <div class="section-head">
          <div>
            <span class="eyebrow"><span class="dot" aria-hidden="true"></span>Three channels</span>
            <h2>Pick the right<br />door to come<br />through.</h2>
          </div>
          <p class="lede">
            Project enquiries land with the product managers. Press and open-source inquiries land with Joon. Careers and recruitment land with Felix. We keep these routes separate so that every note gets to the right person instantly.
          </p>
        </div>

        <div class="channel-grid">
          <article class="channel-card">
            <span class="label">01 / New project</span>
            <h3>For development briefs.</h3>
            <p>The right door for web dev, mobile app, custom software, and automation enquiries. Describe your technical specifications: who, what, when, tech stack.</p>
            <a class="channel-line" href="mailto:hello@tehub.in">hello @ tehub.in</a>
            <span class="mono" style="color: var(--fg-mute);">Anya Stenmark · Eli Wender · 48hr reply target</span>
          </article>

          <article class="channel-card" id="press">
            <span class="label label--lime">02 / Press &amp; Open Source</span>
            <h3>For industry news &amp; talks.</h3>
            <p>Technical publications, podcast appearances, open-source sponsorship queries, and academic research collaborations. Joon answers personally.</p>
            <a class="channel-line" href="mailto:press@tehub.in">press @ tehub.in</a>
            <span class="mono" style="color: var(--fg-mute);">Joon Park · 5-day reply target · press kit below</span>
          </article>

          <article class="channel-card" id="careers">
            <span class="label">03 / Careers</span>
            <h3>For developers &amp; designers.</h3>
            <p>Currently hiring a junior developer (Berlin) and freelance cloud DevOps engineer (NYC). Open applications welcome. No PDFs &gt; 8&nbsp;MB please.</p>
            <a class="channel-line" href="mailto:careers@tehub.in">careers @ tehub.in</a>
            <span class="mono" style="color: var(--fg-mute);">Felix Vahl · 7 — 14 day reply window</span>
          </article>
        </div>
      </div>
    </section>

    <!-- Intake form -->
    <section id="intake">
      <div class="container container--narrow">
        <div class="section-head">
          <div>
            <span class="eyebrow"><span class="dot" aria-hidden="true"></span>New project intake</span>
            <h2>Tell us the<br />shape of the<br />brief.</h2>
          </div>
          <p class="lede">
            We promise to read every submission and respond with concrete technical feedback within 48 working hours. No endless questionnaire — a paragraph is enough.
          </p>
        </div>

        <form class="form-card" id="contact-form">
          <div class="form-row">
            <div class="form-field">
              <label for="f-name">Your name *</label>
              <input id="f-name" name="name" type="text" required placeholder="Mira Halden" />
            </div>
            <div class="form-field">
              <label for="f-email">Email *</label>
              <input id="f-email" name="email" type="email" required placeholder="mira@atrium.studio" />
            </div>
          </div>

          <div class="form-row">
            <div class="form-field">
              <label for="f-phone">WhatsApp Mobile Number *</label>
              <input id="f-phone" name="phone" type="tel" required placeholder="9876543210 or +91 98765 43210" />
            </div>
            <div class="form-field">
              <label for="f-role">Your role</label>
              <input id="f-role" name="role" type="text" placeholder="Product manager · founder" />
            </div>
          </div>

          <div class="form-row">
            <div class="form-field">
              <label for="f-brand">Company / startup</label>
              <input id="f-brand" name="brand" type="text" placeholder="Atrium · Lisbon" />
            </div>
            <div class="form-field">
              <label for="f-tier">Probable tier</label>
              <select id="f-tier" name="tier">
                <option>MVP &amp; Automation — prototyping / script setup</option>
                <option selected>Custom App &amp; Web — custom backend / scaling frontend</option>
                <option>Enterprise Partnership — dedicated engineering squad</option>
                <option>Not sure — please advise</option>
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-field">
              <label for="f-dates">Desired launch window</label>
              <input id="f-dates" name="dates" type="text" placeholder="Mid-March, 2-month build" />
            </div>
            <div class="form-field">
              <label for="f-where">Project Type</label>
              <input id="f-where" name="where" type="text"
                placeholder="SaaS Platform · iOS/Android App · Web Dashboard · Workflow Integration" />
            </div>
          </div>

          <div class="form-row form-row--full">
            <div class="form-field">
              <label for="f-brief">The requirements — one paragraph *</label>
              <textarea id="f-brief" name="brief" required
                placeholder="What is the project, what databases do you use, what third-party APIs need to be integrated? Bullet points are fine."></textarea>
            </div>
          </div>

          <div class="form-row form-row--full">
            <div class="form-field">
              <label for="f-refs">Reference link / Repo (optional)</label>
              <input id="f-refs" name="refs" type="url" placeholder="https://… GitHub repo / Figma design / brief PDF" />
            </div>
          </div>

          <div class="form-actions">
            <small>By submitting, you agree to WhatsApp OTP verification. No marketing spam.</small>
            <button type="submit" id="btn-submit-brief" class="btn btn--primary btn--lg">Verify &amp; Send Brief
              <svg class="arrow" width="16" height="10" viewBox="0 0 14 10" fill="none" aria-hidden="true">
                <path d="M1 5h12m0 0L9 1m4 4L9 9" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"
                  stroke-linejoin="round" />
              </svg>
            </button>
          </div>
        </form>

        <div id="form-success-message" style="display: none; background: rgba(212, 255, 61, 0.08); border: 1px solid var(--lime); border-radius: var(--radius-md); padding: var(--space-7); text-align: center; margin-top: var(--space-6);">
          <div style="font-size: 40px; margin-bottom: 12px;">✅</div>
          <h3 style="font-family: var(--font-display); font-size: var(--text-2xl); margin-bottom: 8px;">Brief Verified &amp; Sent!</h3>
          <p style="color: var(--fg-soft); max-width: 50ch; margin: 0 auto 16px; font-size: var(--text-md);">
            We have sent a WhatsApp confirmation to your mobile. Our lead architect will review your brief and contact you within 48 working hours.
          </p>
          <a href="index.php" class="btn btn--primary btn--sm">Back to Home</a>
        </div>
      </div>
    </section>

    <!-- WhatsApp OTP Modal Overlay -->
    <div id="otp-modal-overlay" style="display: none; position: fixed; inset: 0; background: rgba(10, 10, 12, 0.88); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); z-index: 10000; align-items: center; justify-content: center; padding: 20px;">
      <div style="background: var(--bg-alt); border: 1px solid var(--lime); border-radius: 20px; max-width: 440px; width: 100%; padding: 32px; box-shadow: 0 20px 50px rgba(0,0,0,0.8); position: relative;">
        <button id="otp-modal-close" style="position: absolute; top: 16px; right: 16px; background: transparent; border: none; color: var(--fg-mute); font-size: 20px; cursor: pointer;">✕</button>
        
        <div style="text-align: center; margin-bottom: 24px;">
          <div style="width: 56px; height: 56px; background: rgba(212,255,61,0.1); border: 1px solid rgba(212,255,61,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; font-size: 24px;">🔐</div>
          <h3 style="font-family: var(--font-display); font-size: var(--text-xl); margin-bottom: 8px; color: var(--fg);">WhatsApp Verification</h3>
          <p style="font-size: var(--text-xs); color: var(--fg-soft); line-height: 1.5;">
            We've sent a 6-digit OTP code to your WhatsApp number <strong id="otp-target-phone" style="color: var(--lime);">+91 XXXXX XXXXX</strong>.
          </p>
        </div>

        <div id="otp-alert-box" style="display: none; padding: 10px 14px; border-radius: 8px; font-size: 13px; margin-bottom: 16px; text-align: center;"></div>

        <div style="margin-bottom: 24px;">
          <label style="display: block; font-family: var(--font-mono); font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; color: var(--fg-mute); margin-bottom: 8px; text-align: center;">Enter 6-Digit OTP Code</label>
          <input type="text" id="otp-code-input" maxlength="6" placeholder="0 0 0 0 0 0" style="width: 100%; height: 56px; background: var(--bg); border: 1px solid var(--rule); border-radius: 12px; color: var(--lime); font-family: var(--font-mono); font-size: 24px; font-weight: 700; text-align: center; letter-spacing: 0.3em; outline: none; transition: border-color 0.2s;" />
        </div>

        <button id="btn-verify-otp" class="btn btn--primary btn--lg" style="width: 100%; justify-content: center; margin-bottom: 12px;">Verify &amp; Submit Inquiry →</button>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 16px; font-size: 12px; font-family: var(--font-mono);">
          <button id="btn-resend-otp" style="background: none; border: none; color: var(--lime); cursor: pointer; text-decoration: underline; padding: 0;">Resend OTP</button>
          <button id="btn-edit-phone" style="background: none; border: none; color: var(--fg-mute); cursor: pointer; text-decoration: underline; padding: 0;">Change Phone Number</button>
        </div>
      </div>
    </div>
      </div>
    </section>

    <!-- Studio locations split -->
    <section class="snug tile-section">
      <div class="container container--wide">
        <div class="split">
          <div class="split-text">
            <span class="eyebrow eyebrow--on-tile"><span class="dot" aria-hidden="true"></span>Registered Office &amp; Verification</span>
            <h2>Visit our office by booking.</h2>
            <p>
              Our Chennai office is an active coding workshop. We are an official <strong>Dun &amp; Bradstreet (D&amp;B) Verified Registered Company</strong>. We welcome enterprise clients, startup founders, and developers for scheduled sessions and deep-dive technical reviews. Write to <strong>hello@tehub.in</strong> to coordinate a visit.
            </p>
            <ul class="split-fact-list">
              <li><b>Entity Name</b><span>THE EXPERT HUB</span></li>
              <li><b>D-U-N-S&reg; No.</b><span style="color: var(--lime); font-weight: 700; font-family: var(--font-mono);">30-704-2520 (Dun &amp; Bradstreet Verified)</span></li>
              <li><b>Registered Office</b><span>No. 20, 2nd Floor, Choolaipalam Venkatraman Salai, Chennai, Tamil Nadu 600078, India</span></li>
              <li><b>Office hours</b><span>Mon–Fri 09:00–18:00 IST · By prior appointment</span></li>
              <li><b>Support Email</b><span>hello@tehub.in</span></li>
            </ul>
            <a class="btn btn--ghost-on-tile btn--lg" href="#intake">Send a first note
              <svg class="arrow" width="16" height="10" viewBox="0 0 14 10" fill="none" aria-hidden="true">
                <path d="M1 5h12m0 0L9 1m4 4L9 9" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"
                  stroke-linejoin="round" />
              </svg>
            </a>
          </div>
          <div class="split-img">
            <img src="assets/img/contact-room.svg"
              alt="A clean collaborative developer workspace with notebook and code layouts." />
            <span class="ft-corner">Chennai office</span>
          </div>
        </div>
      </div>
    </section>

    <!-- Press kit -->
    <section>
      <div class="container container--wide">
        <div class="section-head">
          <div>
            <span class="eyebrow"><span class="dot" aria-hidden="true"></span>Press assets</span>
            <h2>Logo resources<br />and boilerplates<br />in one folder.</h2>
          </div>
          <p class="lede">
            High-resolution TEHUB logos, founder portraits, office photographs, a 500-word boiler-plate, and usage guidelines. Write to <strong>press@tehub.in</strong> for credential access.
          </p>
        </div>

        <div class="channel-grid">
          <article class="channel-card">
            <span class="label">Press pack · ZIP</span>
            <h3>Brand assets.</h3>
            <p>TEHUB logo (SVG, PNG format), office interiors, founder headshots, agency one-liner, and 500-word boilerplate text. 12&nbsp;MB.</p>
            <a class="channel-line" href="#">Download press pack ↓</a>
            <span class="mono" style="color: var(--fg-mute);">Last updated · 02 Feb 2026</span>
          </article>
          <article class="channel-card">
            <span class="label">Bio · founders</span>
            <h3>Founder profiles.</h3>
            <p>Short bios and quotes for Mira, Joon, and Tomas in English and German. Quote permissions granted for editorial use.</p>
            <a class="channel-line" href="#">Read profiles →</a>
            <span class="mono" style="color: var(--fg-mute);">EN · DE · last reviewed Jan 2026</span>
          </article>
          <article class="channel-card">
            <span class="label">Open Source</span>
            <h3>Technical writeups.</h3>
            <p>Access our library of open source case studies, benchmarks, and deployment metrics. Free to share with proper attribution.</p>
            <a class="channel-line" href="mailto:press@tehub.in">press @ tehub.in</a>
            <span class="mono" style="color: var(--fg-mute);">Joon Park · weekly digests</span>
          </article>
        </div>
      </div>
    </section>

    <!-- Closing -->
    <section class="closing-cta">
      <div class="container container--narrow">
        <span class="label" style="color: rgba(10,10,12,0.6);">Final note</span>
        <h2>Write directly.<br />We will write<br />directly back.</h2>
        <p class="lede">
          Forms are simple ways to structure your requirements, but we welcome direct emails too. Every inbox listed is monitored by active engineers and product leads on our team.
        </p>
        <div class="cta-row">
          <a class="btn btn--dark btn--lg" href="#intake">Open the form
            <svg class="arrow" width="16" height="10" viewBox="0 0 14 10" fill="none" aria-hidden="true">
              <path d="M1 5h12m0 0L9 1m4 4L9 9" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"
                stroke-linejoin="round" />
            </svg>
          </a>
          <a class="btn btn--ghost btn--lg" href="mailto:hello@tehub.in">Or email directly</a>
        </div>
      </div>
    </section>

  </main>

  <footer class="site-footer">
    <div class="container container--wide">
      <div class="footer-top">
        <div class="footer-brand">
          <span class="brand"><span class="brand-mark" aria-hidden="true"></span> THE EXPERT HUB</span>
          <p>A premium digital agency engineering custom web applications, mobile apps, software platforms, and
            automations between Chennai & Hyderabad.</p>
          <span class="label">Studio · 2021-2026 · 2018-2026 </span>
        </div>
        <div>
          <h4>Agency</h4>
          <ul>
            <li></li>
            <li></li>
            <li><a href="services.php">Services</a></li>
            <li><a href="services.php#rates">Rates</a></li>
          </ul>
        </div>
        <div>
          <h4>Explore</h4>
          <ul>
            <li><a href="d-r.php">D-R (Dream to Real)</a></li>
            <li><a href="solutions.php">Solutions</a></li>
          </ul>
        </div>
        <div>
          <h4>Connect</h4>
          <ul>
            <li><a href="contact.php">Start a project</a></li>
            <li><a href="https://www.linkedin.com/company/142877064/" target="_blank" rel="noopener">LinkedIn</a></li>
            <li><a href="https://www.facebook.com/share/1HXUyXrCCS/" target="_blank" rel="noopener">Facebook</a></li>
            <li><a href="https://www.instagram.com/the_expert.hub_?igsh=MjBnNGQ2d3BkMmFp" target="_blank" rel="noopener">Instagram</a></li>
          </ul>
        </div>
      </div>
      <div class="footer-bottom">
        <span>© 2026 THE EXPERT HUB · Engineered for performance. Distributed by <a href="https://tehub.in/"
            target="_blank">The Expert Hub</a></span>
        <div class="footer-meta-links">
          <a href="privacy.php">Privacy</a>
          <a href="terms.php">Terms</a>
          <a href="sitemap.php">Sitemap</a>
        </div>
      </div>
    </div>
  </footer>

  <script src="assets/js/site.js" defer></script>
  <script src="assets/js/chatbot.js" defer></script>

  <!-- Mobile Bottom Navigation Bar (App View) -->
  <nav class="mobile-bottom-nav" aria-label="Mobile Navigation">
    <a href="index.php" class="mobile-bottom-nav__item">
      <svg class="mobile-bottom-nav__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
      <span class="mobile-bottom-nav__label">Home</span>
    </a>
    <a href="d-r.php" class="mobile-bottom-nav__item">
      <svg class="mobile-bottom-nav__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/></svg>
      <span class="mobile-bottom-nav__label">D-R</span>
    </a>
    <a href="services.php" class="mobile-bottom-nav__item">
      <svg class="mobile-bottom-nav__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
      <span class="mobile-bottom-nav__label">Services</span>
    </a>
    <a href="solutions.php" class="mobile-bottom-nav__item">
      <svg class="mobile-bottom-nav__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
      <span class="mobile-bottom-nav__label">Solutions</span>
    </a>
    <a href="contact.php" class="mobile-bottom-nav__item" aria-current="page">
      <svg class="mobile-bottom-nav__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
      <span class="mobile-bottom-nav__label">Contact</span>
    </a>
  </nav>

  <script>
  document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contact-form');
    const otpModalOverlay = document.getElementById('otp-modal-overlay');
    const otpModalClose = document.getElementById('otp-modal-close');
    const otpTargetPhone = document.getElementById('otp-target-phone');
    const otpCodeInput = document.getElementById('otp-code-input');
    const btnVerifyOtp = document.getElementById('btn-verify-otp');
    const btnResendOtp = document.getElementById('btn-resend-otp');
    const btnEditPhone = document.getElementById('btn-edit-phone');
    const otpAlertBox = document.getElementById('otp-alert-box');
    const formSuccessMessage = document.getElementById('form-success-message');
    const btnSubmitBrief = document.getElementById('btn-submit-brief');

    let formDataCache = {};

    function showAlert(msg, isError) {
      otpAlertBox.style.display = 'block';
      otpAlertBox.style.background = isError ? 'rgba(255, 74, 74, 0.15)' : 'rgba(212, 255, 61, 0.15)';
      otpAlertBox.style.color = isError ? '#ff4a4a' : 'var(--lime)';
      otpAlertBox.style.border = isError ? '1px solid rgba(255, 74, 74, 0.3)' : '1px solid rgba(212, 255, 61, 0.3)';
      otpAlertBox.textContent = msg;
    }

    function hideAlert() {
      otpAlertBox.style.display = 'none';
    }

    // 1. Submit Form -> Trigger OTP
    if (contactForm) {
      contactForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const name = document.getElementById('f-name').value.trim();
        const email = document.getElementById('f-email').value.trim();
        const phone = document.getElementById('f-phone').value.trim();
        const brief = document.getElementById('f-brief').value.trim();

        if (!name || !email || !phone || !brief) {
          alert('Please fill out all required fields (*).');
          return;
        }

        formDataCache = {
          name: name,
          email: email,
          phone: phone,
          brand: document.getElementById('f-brand').value.trim(),
          role: document.getElementById('f-role').value.trim(),
          tier: document.getElementById('f-tier').value,
          dates: document.getElementById('f-dates').value.trim(),
          where: document.getElementById('f-where').value.trim(),
          brief: brief,
          refs: document.getElementById('f-refs').value.trim()
        };

        btnSubmitBrief.disabled = true;
        btnSubmitBrief.textContent = 'Sending OTP to WhatsApp...';

        const sendParams = new URLSearchParams();
        sendParams.append('name', name);
        sendParams.append('phone', phone);

        fetch('send_otp', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: sendParams.toString()
        })
        .then(res => res.json())
        .then(data => {
          btnSubmitBrief.disabled = false;
          btnSubmitBrief.innerHTML = 'Verify &amp; Send Brief <svg class="arrow" width="16" height="10" viewBox="0 0 14 10" fill="none" aria-hidden="true"><path d="M1 5h12m0 0L9 1m4 4L9 9" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" /></svg>';

          if (data.status === 'success') {
            otpTargetPhone.textContent = data.phone || phone;
            otpCodeInput.value = '';
            hideAlert();
            otpModalOverlay.style.display = 'flex';
            setTimeout(() => otpCodeInput.focus(), 100);
          } else {
            alert(data.message || 'Could not send OTP. Please check your mobile number.');
          }
        })
        .catch(err => {
          btnSubmitBrief.disabled = false;
          btnSubmitBrief.innerHTML = 'Verify &amp; Send Brief';
          alert('Connection error. Please try again.');
        });
      });
    }

    // 2. Verify OTP
    if (btnVerifyOtp) {
      btnVerifyOtp.addEventListener('click', function() {
        const otp = otpCodeInput.value.trim();
        if (!otp || otp.length < 4) {
          showAlert('Please enter the 6-digit OTP code sent to your WhatsApp.', true);
          return;
        }

        btnVerifyOtp.disabled = true;
        btnVerifyOtp.textContent = 'Verifying...';

        const payload = Object.assign({}, formDataCache, { otp: otp });
        const verifyParams = new URLSearchParams();
        for (const key in payload) {
          verifyParams.append(key, payload[key]);
        }

        fetch('verify_otp', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: verifyParams.toString()
        })
        .then(res => res.json())
        .then(data => {
          btnVerifyOtp.disabled = false;
          btnVerifyOtp.textContent = 'Verify & Submit Inquiry →';

          if (data.status === 'success') {
            otpModalOverlay.style.display = 'none';
            contactForm.style.display = 'none';
            formSuccessMessage.style.display = 'block';
            formSuccessMessage.scrollIntoView({ behavior: 'smooth' });
          } else {
            showAlert(data.message || 'Invalid OTP code. Please try again.', true);
          }
        })
        .catch(err => {
          btnVerifyOtp.disabled = false;
          btnVerifyOtp.textContent = 'Verify & Submit Inquiry →';
          showAlert('Verification failed. Please try again.', true);
        });
      });
    }

    // Resend OTP
    if (btnResendOtp) {
      btnResendOtp.addEventListener('click', function() {
        btnResendOtp.disabled = true;
        btnResendOtp.textContent = 'Sending...';

        const resendParams = new URLSearchParams();
        resendParams.append('name', formDataCache.name || '');
        resendParams.append('phone', formDataCache.phone || '');

        fetch('send_otp', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: resendParams.toString()
        })
        .then(res => res.json())
        .then(data => {
          btnResendOtp.disabled = false;
          btnResendOtp.textContent = 'Resend OTP';
          showAlert('New OTP sent to your WhatsApp!', false);
        });
      });
    }

    // Edit Phone
    if (btnEditPhone) {
      btnEditPhone.addEventListener('click', function() {
        otpModalOverlay.style.display = 'none';
        document.getElementById('f-phone').focus();
      });
    }

    if (otpModalClose) {
      otpModalClose.addEventListener('click', function() {
        otpModalOverlay.style.display = 'none';
      });
    }
  });
  </script>
</body>

</html>
