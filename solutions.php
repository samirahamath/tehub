<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Solutions — IT Services, SaaS & Custom Software | THE EXPERT HUB</title>
  <meta name="description"
    content="Explore 22+ ready-to-deploy SaaS products and custom development services by THE EXPERT HUB — CRM, IVR, School ERP, Restaurant POS, Mobile Apps, Digital Marketing & more." />
  <meta name="robots" content="index, follow" />
  <meta name="author" content="THE EXPERT HUB" />
  <link rel="canonical" href="https://tehub.in/solutions.php" />
  <meta property="og:type" content="website" />
  <meta property="og:title" content="Solutions — IT Services, SaaS & Custom Software | THE EXPERT HUB" />
  <meta property="og:description" content="22+ ready-to-deploy SaaS products and custom development services. CRM, IVR, School ERP, Restaurant POS, Mobile Apps & more." />
  <meta property="og:url" content="https://tehub.in/solutions.php" />
  <meta property="og:site_name" content="THE EXPERT HUB" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Boldonse&family=Inter+Tight:wght@400;500;600;700&family=Geist+Mono:wght@400;500&display=swap" />
  <link rel="stylesheet" href="assets/css/styles.css?v=1.5" />
  <link rel="stylesheet" href="assets/css/chatbot.css" />
  <style>
    /* ── Solutions Page Specific Styles ── */
    .solutions-hero {
      padding: var(--space-10) 0 var(--space-8);
      text-align: center;
    }
    .solutions-hero .eyebrow {
      margin-bottom: var(--space-4);
    }
    .solutions-hero h1 {
      font-family: var(--font-display);
      font-size: clamp(2.4rem, 5vw, 4.5rem);
      line-height: 1.08;
      letter-spacing: -0.03em;
      margin-bottom: var(--space-5);
    }
    .solutions-hero .hero-sub {
      max-width: 60ch;
      margin: 0 auto var(--space-6);
      color: var(--fg-soft);
      font-size: var(--text-lg);
      line-height: 1.6;
    }
    .solutions-hero .hero-cta-row {
      justify-content: center;
    }
    .solutions-stats {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 0;
      border: 1px solid var(--rule);
      border-radius: var(--radius-md);
      overflow: hidden;
      margin-top: var(--space-7);
    }
    .solutions-stat {
      padding: var(--space-5) var(--space-4);
      text-align: center;
      border-right: 1px solid var(--rule);
    }
    .solutions-stat:last-child {
      border-right: none;
    }
    .solutions-stat strong {
      display: block;
      font-family: var(--font-display);
      font-size: var(--text-3xl);
      color: var(--lime);
      margin-bottom: 4px;
    }
    .solutions-stat span {
      font-family: var(--font-mono);
      font-size: var(--text-xs);
      text-transform: uppercase;
      letter-spacing: 0.1em;
      color: var(--fg-mute);
    }

    /* Section titles */
    .section-label {
      font-family: var(--font-mono);
      font-size: var(--text-xs);
      text-transform: uppercase;
      letter-spacing: 0.15em;
      color: var(--lime);
      margin-bottom: var(--space-3);
    }
    .section-title {
      font-family: var(--font-display);
      font-size: clamp(1.8rem, 3.5vw, 3rem);
      letter-spacing: -0.02em;
      margin-bottom: var(--space-3);
    }
    .section-desc {
      color: var(--fg-soft);
      font-size: var(--text-md);
      max-width: 55ch;
      margin-bottom: var(--space-7);
    }

    /* Product cards grid */
    .products-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
      gap: var(--space-5);
    }
    .product-card {
      background: var(--bg-alt);
      border: 1px solid var(--rule);
      border-radius: var(--radius-md);
      padding: var(--space-6);
      display: flex;
      flex-direction: column;
      gap: var(--space-4);
      transition: border-color var(--dur-base) var(--ease-out), transform var(--dur-base) var(--ease-out);
      position: relative;
      overflow: hidden;
    }
    .product-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 3px;
      background: linear-gradient(90deg, var(--lime), transparent);
      opacity: 0;
      transition: opacity var(--dur-base) var(--ease-out);
    }
    .product-card:hover {
      border-color: var(--lime-soft);
      transform: translateY(-4px);
    }
    .product-card:hover::before {
      opacity: 1;
    }
    .product-card__icon {
      width: 48px;
      height: 48px;
      border-radius: 12px;
      background: rgba(212, 255, 61, 0.08);
      border: 1px solid rgba(212, 255, 61, 0.15);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
    }
    .product-card__title {
      font-family: var(--font-body);
      font-size: var(--text-lg);
      font-weight: 600;
      color: var(--fg);
    }
    .product-card__desc {
      font-size: var(--text-sm);
      color: var(--fg-soft);
      line-height: 1.6;
      flex: 1;
    }
    .product-card__tags {
      display: flex;
      flex-wrap: wrap;
      gap: 6px;
    }
    .product-card__tag {
      font-family: var(--font-mono);
      font-size: 10px;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      padding: 4px 10px;
      border-radius: 100px;
      background: rgba(212, 255, 61, 0.06);
      border: 1px solid rgba(212, 255, 61, 0.12);
      color: var(--fg-mute);
    }
    .product-card__cta {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      font-family: var(--font-mono);
      font-size: var(--text-xs);
      text-transform: uppercase;
      letter-spacing: 0.1em;
      color: var(--lime);
      text-decoration: none;
      margin-top: auto;
      transition: gap var(--dur-fast) var(--ease-out);
    }
    .product-card__cta:hover {
      gap: 10px;
    }

    /* Services section */
    .services-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      gap: var(--space-5);
    }
    .service-card {
      background: var(--bg-alt);
      border: 1px solid var(--rule);
      border-radius: var(--radius-md);
      padding: var(--space-6);
      display: flex;
      flex-direction: column;
      gap: var(--space-3);
      transition: border-color var(--dur-base) var(--ease-out), transform var(--dur-base) var(--ease-out);
    }
    .service-card:hover {
      border-color: var(--lime-soft);
      transform: translateY(-2px);
    }
    .service-card__num {
      font-family: var(--font-mono);
      font-size: var(--text-xs);
      color: var(--lime);
      letter-spacing: 0.15em;
    }
    .service-card__title {
      font-size: var(--text-md);
      font-weight: 600;
    }
    .service-card__desc {
      font-size: var(--text-sm);
      color: var(--fg-soft);
      line-height: 1.6;
    }

    /* Custom CTA section */
    .custom-cta {
      text-align: center;
      padding: var(--space-9) 0;
      border-top: 1px solid var(--rule);
      border-bottom: 1px solid var(--rule);
      margin-top: var(--space-8);
    }
    .custom-cta h2 {
      font-family: var(--font-display);
      font-size: clamp(1.8rem, 3.5vw, 3rem);
      letter-spacing: -0.02em;
      margin-bottom: var(--space-4);
    }
    .custom-cta p {
      color: var(--fg-soft);
      font-size: var(--text-lg);
      max-width: 55ch;
      margin: 0 auto var(--space-6);
      line-height: 1.6;
    }

    @media (max-width: 720px) {
      .solutions-stats {
        grid-template-columns: repeat(2, 1fr);
      }
      .solutions-stat:nth-child(2) {
        border-right: none;
      }
      .solutions-stat:nth-child(1),
      .solutions-stat:nth-child(2) {
        border-bottom: 1px solid var(--rule);
      }
      .products-grid {
        grid-template-columns: 1fr;
      }
      .services-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
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
    <section class="solutions-hero">
      <div class="container container--wide">
        <span class="eyebrow"><span class="dot" aria-hidden="true"></span>22+ Products & Services · SaaS · Custom Dev</span>
        <h1>
          Solutions<br />
          <span class="lime">built for scale.</span>
        </h1>
        <p class="hero-sub">
          From ready-to-deploy SaaS platforms to fully custom software — everything your business needs under one roof. We deliver in 7 days.
        </p>
        <div class="hero-cta-row">
          <a class="btn btn--primary btn--lg" href="#products">Explore Products</a>
          <a class="btn btn--ghost btn--lg" href="contact.php">Get Free Consultation</a>
        </div>

        <div class="solutions-stats">
          <div class="solutions-stat">
            <strong>22+</strong>
            <span>Products</span>
          </div>
          <div class="solutions-stat">
            <strong>7</strong>
            <span>Day Delivery</span>
          </div>
          <div class="solutions-stat">
            <strong>100%</strong>
            <span>Customizable</span>
          </div>
          <div class="solutions-stat">
            <strong>24/7</strong>
            <span>Support</span>
          </div>
        </div>
      </div>
    </section>

    <!-- Ready SaaS Products -->
    <section id="products" style="padding: var(--space-8) 0;">
      <div class="container container--wide">
        <div class="section-label">Ready-to-Deploy</div>
        <h2 class="section-title">SaaS Products & Platforms</h2>
        <p class="section-desc">Enterprise-grade software you can deploy immediately. Fully customizable, white-labeled, and cloud-hosted.</p>

        <div class="products-grid">

          <!-- 1. CRM Solutions -->
          <div class="product-card">
            <div class="product-card__icon">📊</div>
            <h3 class="product-card__title">CRM Solutions</h3>
            <p class="product-card__desc">Complete customer relationship management system. Track leads, manage pipelines, automate follow-ups, and close deals faster.</p>
            <div class="product-card__tags">
              <span class="product-card__tag">Lead Tracking</span>
              <span class="product-card__tag">Pipeline</span>
              <span class="product-card__tag">Automation</span>
            </div>
            <a href="contact.php" class="product-card__cta">Get Demo →</a>
          </div>

          <!-- 2. IVR Services -->
          <div class="product-card">
            <div class="product-card__icon">📞</div>
            <h3 class="product-card__title">IVR Services</h3>
            <p class="product-card__desc">Interactive Voice Response with your own number at affordable pricing. Automated calling, call routing, and voice menus for businesses.</p>
            <div class="product-card__tags">
              <span class="product-card__tag">Own Number</span>
              <span class="product-card__tag">Auto-Calling</span>
              <span class="product-card__tag">Affordable</span>
            </div>
            <a href="contact.php" class="product-card__cta">Get Demo →</a>
          </div>

          <!-- 3. WhatsApp Bulk Messaging -->
          <div class="product-card">
            <div class="product-card__icon">💬</div>
            <h3 class="product-card__title">WhatsApp Bulk Messaging</h3>
            <p class="product-card__desc">High-volume WhatsApp message delivery solution. Send promotional, transactional, and OTP messages at scale with API integration.</p>
            <div class="product-card__tags">
              <span class="product-card__tag">Bulk Send</span>
              <span class="product-card__tag">API</span>
              <span class="product-card__tag">OTP</span>
            </div>
            <a href="contact.php" class="product-card__cta">Get Demo →</a>
          </div>

          <!-- 4. Telegram Bulk Messaging -->
          <div class="product-card">
            <div class="product-card__icon">✈️</div>
            <h3 class="product-card__title">Telegram Bulk Messaging</h3>
            <p class="product-card__desc">High-volume Telegram message delivery system. Broadcast to groups, channels, and individual users with bot automation.</p>
            <div class="product-card__tags">
              <span class="product-card__tag">Broadcast</span>
              <span class="product-card__tag">Bot</span>
              <span class="product-card__tag">Channels</span>
            </div>
            <a href="contact.php" class="product-card__cta">Get Demo →</a>
          </div>

          <!-- 5. Restaurant Management System -->
          <div class="product-card">
            <div class="product-card__icon">🍽️</div>
            <h3 class="product-card__title">Restaurant Management System</h3>
            <p class="product-card__desc">Complete restaurant POS with QR code scan-to-order, automatic billing & payments without manual staff, and NFC table integration.</p>
            <div class="product-card__tags">
              <span class="product-card__tag">QR Order</span>
              <span class="product-card__tag">NFC</span>
              <span class="product-card__tag">Auto Billing</span>
            </div>
            <a href="https://rest.tehub.in" target="_blank" rel="noopener" class="product-card__cta">Live Demo →</a>
          </div>

          <!-- 6. School Management System -->
          <div class="product-card">
            <div class="product-card__icon">🏫</div>
            <h3 class="product-card__title">School Management System</h3>
            <p class="product-card__desc">Complete school ERP with dedicated apps for Parents, Students, Staff & Admin. Manage admissions, billing, attendance, exams, and scheduling.</p>
            <div class="product-card__tags">
              <span class="product-card__tag">Apps</span>
              <span class="product-card__tag">ERP</span>
              <span class="product-card__tag">Multi-Role</span>
            </div>
            <a href="contact.php" class="product-card__cta">Get Demo →</a>
          </div>

          <!-- 7. Learning Management System -->
          <div class="product-card">
            <div class="product-card__icon">📚</div>
            <h3 class="product-card__title">Learning Management System (LMS)</h3>
            <p class="product-card__desc">Feature-rich LMS with Vendor, Admin & Super Admin controls. Course creation, student progress tracking, assessments, and certificates.</p>
            <div class="product-card__tags">
              <span class="product-card__tag">Multi-Vendor</span>
              <span class="product-card__tag">Courses</span>
              <span class="product-card__tag">Certificates</span>
            </div>
            <a href="contact.php" class="product-card__cta">Get Demo →</a>
          </div>

          <!-- 8. Farmhouse Management System -->
          <div class="product-card">
            <div class="product-card__icon">🌾</div>
            <h3 class="product-card__title">Farmhouse Management System</h3>
            <p class="product-card__desc">End-to-end farmhouse booking and management platform with dedicated mobile app. Reservation calendar, payment tracking, and guest management.</p>
            <div class="product-card__tags">
              <span class="product-card__tag">Mobile App</span>
              <span class="product-card__tag">Bookings</span>
              <span class="product-card__tag">Payments</span>
            </div>
            <a href="contact.php" class="product-card__cta">Get Demo →</a>
          </div>

          <!-- 9. PG/Hostel Management System -->
          <div class="product-card">
            <div class="product-card__icon">🏠</div>
            <h3 class="product-card__title">PG/Hostel Management System</h3>
            <p class="product-card__desc">Complete PG and hostel management with mobile app. Room allocation, rent collection, tenant management, and automated reminders.</p>
            <div class="product-card__tags">
              <span class="product-card__tag">Mobile App</span>
              <span class="product-card__tag">Rent</span>
              <span class="product-card__tag">Tenants</span>
            </div>
            <a href="contact.php" class="product-card__cta">Get Demo →</a>
          </div>

          <!-- 10. Real Estate Management System -->
          <div class="product-card">
            <div class="product-card__icon">🏗️</div>
            <h3 class="product-card__title">Real Estate Management System</h3>
            <p class="product-card__desc">Property listing, buyer/seller matching, site visit scheduling, and deal tracking — with mobile apps for agents and clients.</p>
            <div class="product-card__tags">
              <span class="product-card__tag">Mobile Apps</span>
              <span class="product-card__tag">Listings</span>
              <span class="product-card__tag">Deals</span>
            </div>
            <a href="contact.php" class="product-card__cta">Get Demo →</a>
          </div>

          <!-- 11. HR Management System -->
          <div class="product-card">
            <div class="product-card__icon">👥</div>
            <h3 class="product-card__title">HR Management System</h3>
            <p class="product-card__desc">Complete HR platform — employee onboarding, attendance, payroll, leave management, performance reviews, and compliance tracking.</p>
            <div class="product-card__tags">
              <span class="product-card__tag">Payroll</span>
              <span class="product-card__tag">Attendance</span>
              <span class="product-card__tag">Performance</span>
            </div>
            <a href="contact.php" class="product-card__cta">Get Demo →</a>
          </div>

          <!-- 12. Background Verification System -->
          <div class="product-card">
            <div class="product-card__icon">🔍</div>
            <h3 class="product-card__title">Background Verification System</h3>
            <p class="product-card__desc">Automated employee and vendor background verification platform. Document verification, criminal record checks, and compliance reports.</p>
            <div class="product-card__tags">
              <span class="product-card__tag">Verification</span>
              <span class="product-card__tag">Compliance</span>
              <span class="product-card__tag">Reports</span>
            </div>
            <a href="contact.php" class="product-card__cta">Get Demo →</a>
          </div>

          <!-- 13. Goods Transport Management -->
          <div class="product-card">
            <div class="product-card__icon">🚛</div>
            <h3 class="product-card__title">Goods Transport Management</h3>
            <p class="product-card__desc">Complete trip management with toll tracking, fuel management, payment settlements, and multi-state operations support.</p>
            <div class="product-card__tags">
              <span class="product-card__tag">Trip Mgmt</span>
              <span class="product-card__tag">Toll</span>
              <span class="product-card__tag">Multi-State</span>
            </div>
            <a href="contact.php" class="product-card__cta">Get Demo →</a>
          </div>

          <!-- 14. Ticket Booking System -->
          <div class="product-card">
            <div class="product-card__icon">🎫</div>
            <h3 class="product-card__title">Ticket Booking Management</h3>
            <p class="product-card__desc">Full ticket booking platform with live tracking, 3D seat visualization, real-time availability, and payment gateway integration.</p>
            <div class="product-card__tags">
              <span class="product-card__tag">Live Tracking</span>
              <span class="product-card__tag">3D View</span>
              <span class="product-card__tag">Payments</span>
            </div>
            <a href="contact.php" class="product-card__cta">Get Demo →</a>
          </div>

          <!-- 15. Wi-Fi Hardware Solutions -->
          <div class="product-card">
            <div class="product-card__icon">📡</div>
            <h3 class="product-card__title">Wi-Fi Hardware Solutions</h3>
            <p class="product-card__desc">Long-range Wi-Fi coverage up to 1 KM for CCTV cameras in remote areas — with solar power setup for off-grid locations.</p>
            <div class="product-card__tags">
              <span class="product-card__tag">1 KM Range</span>
              <span class="product-card__tag">CCTV</span>
              <span class="product-card__tag">Solar</span>
            </div>
            <a href="contact.php" class="product-card__cta">Get Quote →</a>
          </div>

        </div>
      </div>
    </section>

    <!-- Custom Development Services -->
    <section id="services" style="padding: var(--space-8) 0; border-top: 1px solid var(--rule);">
      <div class="container container--wide">
        <div class="section-label">Custom Development</div>
        <h2 class="section-title">Services We Offer</h2>
        <p class="section-desc">Don't see what you need above? We build fully custom solutions tailored to your exact business requirements.</p>

        <div class="services-grid">
          <div class="service-card">
            <span class="service-card__num">01</span>
            <h3 class="service-card__title">Website Design & Development</h3>
            <p class="service-card__desc">Responsive, modern, SEO-optimized websites. From landing pages to full-stack web applications — designed to convert.</p>
          </div>
          <div class="service-card">
            <span class="service-card__num">02</span>
            <h3 class="service-card__title">Custom Mobile App Development</h3>
            <p class="service-card__desc">Native Android & iOS apps, or cross-platform with Flutter & React Native. From concept to App Store launch.</p>
          </div>
          <div class="service-card">
            <span class="service-card__num">03</span>
            <h3 class="service-card__title">Custom Software Development</h3>
            <p class="service-card__desc">Tailored software solutions for unique business workflows. If you can imagine it, we can build it.</p>
          </div>
          <div class="service-card">
            <span class="service-card__num">04</span>
            <h3 class="service-card__title">Digital Marketing</h3>
            <p class="service-card__desc">SEO, social media marketing, Google Ads, branding, and growth strategy. Drive traffic, leads, and revenue.</p>
          </div>
          <div class="service-card">
            <span class="service-card__num">05</span>
            <h3 class="service-card__title">AI & Animated Video Creation</h3>
            <p class="service-card__desc">Professional promotional and explainer videos powered by AI. Engage your audience with stunning visual content.</p>
          </div>
          <div class="service-card">
            <span class="service-card__num">06</span>
            <h3 class="service-card__title">Cloud-Based SaaS Solutions</h3>
            <p class="service-card__desc">Scalable, multi-tenant cloud platforms built for growth. From MVP to enterprise-grade SaaS architecture.</p>
          </div>
          <div class="service-card">
            <span class="service-card__num">07</span>
            <h3 class="service-card__title">ERP & Business Automation</h3>
            <p class="service-card__desc">End-to-end business process automation. Integrate systems, eliminate manual work, and scale operations efficiently.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Custom CTA -->
    <section class="custom-cta">
      <div class="container container--wide">
        <h2>Have a <span class="lime">unique idea?</span></h2>
        <p>We develop fully customized software and SaaS platforms based on your business requirements. If you have a unique idea or workflow, we can build a complete solution tailored to your needs.</p>
        <div class="hero-cta-row" style="justify-content: center;">
          <a class="btn btn--primary btn--lg" href="contact.php">Tell Us Your Idea →</a>
          <a class="btn btn--ghost btn--lg" href="tel:+919876543210">Call Us Now</a>
        </div>
      </div>
    </section>

  </main>

  <footer class="site-footer">
    <div class="container container--wide">
      <div class="footer-top">
        <div class="footer-brand">
          <span class="brand"><span class="brand-mark" aria-hidden="true"></span> THE EXPERT HUB</span>
          <p>Brutalist software development and startup incubation agency. Based in Chennai, working worldwide.</p>
        </div>
        <div>
          <h4>Platform</h4>
          <ul>
            <li><a href="index.php">Index</a></li>
            <li><a href="d-r.php">D-R Startup</a></li>
            <li><a href="services.php">Services</a></li>
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

  <!-- Mobile Bottom Navigation Bar -->
  <nav class="mobile-bottom-nav" aria-label="Mobile Navigation">
    <a href="index.php" class="mobile-bottom-nav__item">
      <svg class="mobile-bottom-nav__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
      <span class="mobile-bottom-nav__label">Home</span>
    </a>
    <a href="d-r.php" class="mobile-bottom-nav__item">
      <svg class="mobile-bottom-nav__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/></svg>
      <span class="mobile-bottom-nav__label">D-R</span>
    </a>
    <a href="solutions.php" class="mobile-bottom-nav__item" aria-current="page">
      <svg class="mobile-bottom-nav__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
      <span class="mobile-bottom-nav__label">Solutions</span>
    </a>
    <a href="services.php" class="mobile-bottom-nav__item">
      <svg class="mobile-bottom-nav__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
      <span class="mobile-bottom-nav__label">Services</span>
    </a>
    <a href="contact.php" class="mobile-bottom-nav__item">
      <svg class="mobile-bottom-nav__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
      <span class="mobile-bottom-nav__label">Contact</span>
    </a>
  </nav>

</body>

</html>
