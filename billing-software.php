<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>TEHUB · Billing &amp; POS Software Development</title>
  <meta name="description" content="Custom billing software and Point of Sale (POS) development by TEHUB. Enterprise invoice generation, GST sync, and ledger audits." />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Boldonse&family=Inter+Tight:wght@400;500;600;700&family=Geist+Mono:wght@400;500&display=swap" />
  <link rel="stylesheet" href="assets/css/styles.css?v=1.3" />
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
          <a href="sales.php">Sales</a>
          <a href="contact.php">Contact</a>
        </div>
        <div class="nav-cta-row">
          <a href="contact.php" class="btn btn--primary btn--sm">Start a project
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
    <a href="sales.php">Sales</a>
    <a href="contact.php">Contact</a>
  </div>

  <main id="main">
    <!-- Hero -->
    <section class="hero">
      <div class="container container--wide">
        <div class="hero-grid">
          <div class="hero-text">
            <span class="eyebrow"><span class="dot" aria-hidden="true"></span>POS · Invoices · GST Sync · Ledgers</span>
            <h1 class="hero-headline">
              Custom Billing<br/>
              <span class="lime">Software</span> &amp;<br/>
              POS Platforms.
            </h1>
            <p class="hero-sub">
              Maximize checkout efficiency and transaction accuracy. We design and construct custom billing systems, digital POS applications, PDF invoice builders, and real-time taxation synchronization services.
            </p>
            <div class="hero-cta-row">
              <a class="btn btn--primary btn--lg" href="contact.php">Start your billing project
                <svg class="arrow" width="16" height="10" viewBox="0 0 14 10" fill="none" aria-hidden="true"><path d="M1 5h12m0 0L9 1m4 4L9 9" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
              </a>
            </div>
          </div>
          <div class="hero-media">
            <img src="assets/img/work-food.png" alt="A billing system console tracking items and total checkout amounts." />
            <div class="floating-tag ft-top">
              <span class="pill">Engine</span>
              SQL Ledgers · PDF Generator
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Services Detail Section -->
    <section class="tile-section">
      <div class="container container--narrow">
        <div class="section-head">
          <div>
            <span class="eyebrow eyebrow--on-tile"><span class="dot" aria-hidden="true"></span>Billing Core</span>
            <h2>Custom Billing Solutions.</h2>
          </div>
          <p class="lede" style="color: var(--ink-000);">
            We build billing architectures that manage high-frequency printing and transaction logging. Sync ledger books across retail branches and calculate local taxes dynamically on checkout.
          </p>
        </div>

        <div class="faq-grid">
          <div class="faq-card">
            <h3 style="color: var(--ink-000);">Point of Sale (POS) Systems</h3>
            <p style="color: rgba(10,10,12,0.85);">Fast barcode scanning interfaces, touch-screen checkout configurations, offline transaction buffering, and integration with thermal receipt printers and card readers.</p>
          </div>
          <div class="faq-card">
            <h3 style="color: var(--ink-000);">Custom PDF Invoice Engines</h3>
            <p style="color: rgba(10,10,12,0.85);">Generate beautiful, customized invoices in real-time. Includes automatic invoice numbering, discount logic, QR code payment links, and auto-dispatch via email and messaging APIs.</p>
          </div>
          <div class="faq-card">
            <h3 style="color: var(--ink-000);">Taxation &amp; GST Syncing</h3>
            <p style="color: rgba(10,10,12,0.85);">Keep your books legally compliant. Auto-calculate state/national taxes (GST, VAT, Sales Tax) dynamically based on item categories and customer shipping addresses.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Technical Specs -->
    <section>
      <div class="container container--wide">
        <div class="section-head">
          <div>
            <span class="eyebrow"><span class="dot" aria-hidden="true"></span>Billing Security</span>
            <h2>Built for secure calculations.</h2>
          </div>
        </div>
        
        <div class="stat-strip" style="grid-template-columns: repeat(3, 1fr);">
          <div class="stat-cell">
            <div class="stat-num">Double</div>
            <div class="stat-label">Entry Bookkeeping Audits</div>
          </div>
          <div class="stat-cell">
            <div class="stat-num">&lt;500ms</div>
            <div class="stat-label">PDF Bill Generation Speed</div>
          </div>
          <div class="stat-cell">
            <div class="stat-num">PCI-DSS</div>
            <div class="stat-label">Secure Transaction Standards</div>
          </div>
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
            automations based in Chennai.</p>
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
            <li><a href="sales.php">Sales Solutions</a></li>
          </ul>
        </div>
        <div>
          <h4>Connect</h4>
          <ul>
            <li><a href="contact.php">Start a project</a></li>
            <li><a href="contact.php#press">Press</a></li>
            <li><a href="contact.php#careers">Careers</a></li>
            <li><a href="#">Newsletter</a></li>
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
    <a href="sales.php" class="mobile-bottom-nav__item">
      <svg class="mobile-bottom-nav__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
      <span class="mobile-bottom-nav__label">Sales</span>
    </a>
    <a href="contact.php" class="mobile-bottom-nav__item">
      <svg class="mobile-bottom-nav__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
      <span class="mobile-bottom-nav__label">Contact</span>
    </a>
  </nav>
</body>
</html>
