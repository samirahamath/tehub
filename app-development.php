<?php require_once __DIR__ . '/visitor_logger.php'; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>TEHUB · Mobile App Development — iOS &amp; Android Apps</title>
  <meta name="description" content="Premium mobile app development by TEHUB. Specialized cross-platform and native engineering using Flutter and React Native." />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Boldonse&family=Inter+Tight:wght@400;500;600;700&family=Geist+Mono:wght@400;500&display=swap" />
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
    <a href="solutions.php">Solutions</a>
    <a href="contact.php">Contact</a>
  </div>

  <main id="main">
    <!-- Hero -->
    <section class="hero">
      <div class="container container--wide">
        <div class="hero-grid">
          <div class="hero-text">
            <span class="eyebrow"><span class="dot" aria-hidden="true"></span>Flutter · React Native · Native</span>
            <h1 class="hero-headline">
              Premium Mobile<br/>
              <span class="lime">App Engineering</span><br/>
              for Android &amp; iOS.
            </h1>
            <p class="hero-sub">
              We design and develop high-performance mobile applications. Using cross-platform frameworks like Flutter and React Native, we deliver near-native execution with optimal performance and unified codebases.
            </p>
            <div class="hero-cta-row">
              <a class="btn btn--primary btn--lg" href="contact.php">Start your mobile project
                <svg class="arrow" width="16" height="10" viewBox="0 0 14 10" fill="none" aria-hidden="true"><path d="M1 5h12m0 0L9 1m4 4L9 9" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
              </a>
            </div>
          </div>
          <div class="hero-media">
            <img src="assets/img/work-still-1.png" alt="A mobile dashboard interface layout." />
            <div class="floating-tag ft-top">
              <span class="pill">Deploy</span>
              App Store · Play Store
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
            <span class="eyebrow eyebrow--on-tile"><span class="dot" aria-hidden="true"></span>Mobile Core</span>
            <h2>Custom Mobile Solutions.</h2>
          </div>
          <p class="lede" style="color: var(--ink-000);">
            Our mobile systems are built to survive intermittent network connections and provide extremely smooth transitions. We write robust native modules to link with on-device hardware components.
          </p>
        </div>

        <div class="faq-grid">
          <div class="faq-card">
            <h3 style="color: var(--ink-000);">Cross-Platform Architecture</h3>
            <p style="color: rgba(10,10,12,0.85);">Develop once, deploy to both platforms. We write Flutter or React Native applications with clean architectural patterns to minimize regressions and maintenance overhead.</p>
          </div>
          <div class="faq-card">
            <h3 style="color: var(--ink-000);">Offline-First Data Syncing</h3>
            <p style="color: rgba(10,10,12,0.85);">Integrate local databases (SQLite / Room / Hive) that securely persist user data locally. Automatically queue API requests and synchronize database state with cloud servers once connection returns.</p>
          </div>
          <div class="faq-card">
            <h3 style="color: var(--ink-000);">Hardware &amp; API Integration</h3>
            <p style="color: rgba(10,10,12,0.85);">Secure integrations with native device features, including biometric authentication (FaceID / Fingerprint), camera barcodes scanners, push alert configurations, and location telemetry.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Technical Specs -->
    <section>
      <div class="container container--wide">
        <div class="section-head">
          <div>
            <span class="eyebrow"><span class="dot" aria-hidden="true"></span>Mobile Metrics</span>
            <h2>Engineered for responsiveness.</h2>
          </div>
        </div>
        
        <div class="stat-strip" style="grid-template-columns: repeat(3, 1fr);">
          <div class="stat-cell">
            <div class="stat-num">60 FPS</div>
            <div class="stat-label">Smooth UI Animation Rate</div>
          </div>
          <div class="stat-cell">
            <div class="stat-num">Offline</div>
            <div class="stat-label">Local Cache Database Sync</div>
          </div>
          <div class="stat-cell">
            <div class="stat-num">&lt;100ms</div>
            <div class="stat-label">Push Notification Delivery</div>
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
                    <div class="duns-badge" style="display: inline-flex; align-items: center; gap: 8px; background: rgba(212, 255, 61, 0.06); border: 1px solid rgba(212, 255, 61, 0.22); border-radius: 6px; padding: 6px 12px; margin: 12px 0 10px; font-family: var(--font-mono, monospace); font-size: 11px; color: var(--fg, #f4f4f0); letter-spacing: 0.02em;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#D4FF3D" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
            <span><strong>D-U-N-S&reg; Registered&trade;</strong> &middot; <span style="color: #D4FF3D; font-weight: 700;">30-704-2520</span></span>
          </div>
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
    <a href="contact.php" class="mobile-bottom-nav__item">
      <svg class="mobile-bottom-nav__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
      <span class="mobile-bottom-nav__label">Contact</span>
    </a>
  </nav>
</body>
</html>
