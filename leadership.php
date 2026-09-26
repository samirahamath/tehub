<?php require_once __DIR__ . '/visitor_logger.php'; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Company High Officials &amp; Executive Leadership · THE EXPERT HUB</title>
  <meta name="description" content="Meet the high officials and executive leadership team driving THE EXPERT HUB — Dun &amp; Bradstreet (D-U-N-S&reg; 30-704-2520) verified enterprise in Chennai, India." />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Boldonse&family=Inter+Tight:wght@400;500;600;700&family=Geist+Mono:wght@400;500&display=swap" />
  <link rel="stylesheet" href="assets/css/styles.css" />
  <link rel="stylesheet" href="assets/css/chatbot.css" />
  <style>
    .exec-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
      gap: 24px;
      margin-top: 40px;
    }
    .exec-card {
      background: var(--bg-alt, #121216);
      border: 1px solid var(--rule, rgba(255,255,255,0.08));
      border-radius: 18px;
      padding: 32px 28px;
      position: relative;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      transition: transform 0.25s, border-color 0.25s, box-shadow 0.25s;
    }
    .exec-card:hover {
      transform: translateY(-4px);
      border-color: rgba(212, 255, 61, 0.4);
      box-shadow: 0 15px 40px rgba(0,0,0,0.6);
    }
    .exec-card--primary {
      border-color: rgba(212, 255, 61, 0.35);
      background: linear-gradient(145deg, rgba(212,255,61,0.04) 0%, rgba(18,18,22,1) 100%);
    }
    .exec-header {
      display: flex;
      align-items: center;
      gap: 20px;
      margin-bottom: 20px;
    }
    .exec-avatar {
      width: 88px;
      height: 88px;
      flex-shrink: 0;
      border-radius: 16px;
      overflow: hidden;
      border: 1px solid var(--rule);
      background: #0A0A0C;
    }
    .exec-avatar img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    .exec-title-block h3 {
      font-size: 20px;
      font-weight: 700;
      margin: 0 0 4px;
      color: var(--fg);
    }
    .exec-role {
      font-family: var(--font-mono);
      font-size: 12px;
      color: var(--lime);
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }
    .exec-status {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      font-size: 11px;
      font-family: var(--font-mono);
      color: #22c55e;
      margin-top: 4px;
    }
    .exec-status-dot {
      width: 6px;
      height: 6px;
      background: #22c55e;
      border-radius: 50%;
      box-shadow: 0 0 6px #22c55e;
    }
    .exec-bio {
      color: var(--fg-soft);
      font-size: 14px;
      line-height: 1.6;
      margin-bottom: 20px;
    }
    .exec-chips {
      display: flex;
      flex-wrap: wrap;
      gap: 6px;
      margin-bottom: 24px;
    }
    .exec-chip {
      background: rgba(255,255,255,0.04);
      border: 1px solid var(--rule);
      color: var(--fg-soft);
      font-size: 11.5px;
      font-family: var(--font-mono);
      padding: 4px 9px;
      border-radius: 6px;
    }
    .exec-actions {
      display: flex;
      gap: 10px;
      border-top: 1px solid var(--rule);
      padding-top: 18px;
    }
    .gov-strip {
      background: rgba(0,0,0,0.4);
      border: 1px solid var(--rule);
      border-radius: 16px;
      padding: 28px 32px;
      margin: 48px 0;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 20px;
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
          <a href="studio.php">Studio</a>
          <a href="leadership.php" style="color: var(--lime);">Leadership</a>
          <a href="contact.php">Contact</a>
        </div>
        <div class="nav-cta-row">
          <a href="contact.php" class="btn btn--primary btn--sm">Start a project
            <svg class="arrow" width="14" height="10" viewBox="0 0 14 10" fill="none" aria-hidden="true">
              <path d="M1 5h12m0 0L9 1m4 4L9 9" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </a>
          <button class="nav-toggle" aria-label="Open menu" aria-expanded="false" aria-controls="mobile-drawer"><span aria-hidden="true"></span></button>
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
    <a href="studio.php">Studio</a>
    <a href="leadership.php">Leadership</a>
    <a href="contact.php">Contact</a>
  </div>

  <main id="main">

    <!-- Hero Section -->
    <section class="hero" style="padding-bottom: 20px;">
      <div class="container container--wide">
        <div class="section-head">
          <div>
            <span class="eyebrow"><span class="dot" aria-hidden="true"></span>Executive Board &middot; D-U-N-S&reg; 30-704-2520 Verified</span>
            <h1 class="hero-headline" style="font-size: clamp(32px, 5vw, 56px);">
              Company High Officials<br />
              <span class="lime">&amp; Leadership Portfolio.</span>
            </h1>
          </div>
          <p class="lede">
            The executive directors and lead architects governing THE EXPERT HUB. We build scalable software ecosystems, incubate high-potential startups, and deliver uncompromising engineering standards globally.
          </p>
        </div>

        <!-- Official Registry Banner -->
        <div class="gov-strip">
          <div style="display: flex; align-items: center; gap: 16px;">
            <div style="background: rgba(212,255,61,0.1); border: 1px solid var(--lime); width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px;">
              🛡️
            </div>
            <div>
              <strong style="display: block; font-size: 15px; color: var(--fg); font-family: var(--font-display);">Dun &amp; Bradstreet (D&amp;B) Registered Legal Entity</strong>
              <span style="font-size: 13px; color: var(--fg-mute);">Official Enterprise Registration &middot; D-U-N-S&reg; Number: <strong style="color: var(--lime); font-family: var(--font-mono);">30-704-2520</strong></span>
            </div>
          </div>
          <div>
            <span class="pill pill--lime" style="font-size: 12px;">Headquarters: Chennai, India</span>
          </div>
        </div>
      </div>
    </section>

    <!-- Executive Leadership Grid -->
    <section style="padding-top: 0;">
      <div class="container container--wide">
        <div class="exec-grid">

          <!-- Official 01: Samir Ahamed S (Founder & CEO) -->
          <article class="exec-card exec-card--primary">
            <div>
              <div class="exec-header">
                <div class="exec-avatar">
                  <img src="assets/img/team-samir.svg" alt="Samir Ahamed S — Founder &amp; CEO of THE EXPERT HUB" />
                </div>
                <div class="exec-title-block">
                  <span class="exec-role">Founder &amp; Chief Executive Officer</span>
                  <h3>Samir Ahamed S</h3>
                  <div class="exec-status"><span class="exec-status-dot"></span> Executive Board &middot; D&amp;B Signatory</div>
                </div>
              </div>

              <p class="exec-bio">
                Founded THE EXPERT HUB in 2018 with a mission to engineer high-velocity digital products and eliminate software agency bloat. Drives overarching corporate strategy, technological innovation, enterprise architecture, and startup venture incubation via the Dream to Real (D-R) launchpad.
              </p>

              <div class="exec-chips">
                <span class="exec-chip">Enterprise Architecture</span>
                <span class="exec-chip">Startup Incubation (D-R)</span>
                <span class="exec-chip">Cloud Infrastructure</span>
                <span class="exec-chip">Strategic Partnerships</span>
              </div>
            </div>

            <div class="exec-actions">
              <a href="contact.php" class="btn btn--primary btn--sm" style="flex: 1; justify-content: center;">Contact Office ✉️</a>
              <a href="d-r.php" class="btn btn--ghost btn--sm">D-R Platform ↗</a>
            </div>
          </article>

          <!-- Official 02: Chief Technology Officer -->
          <article class="exec-card">
            <div>
              <div class="exec-header">
                <div class="exec-avatar">
                  <img src="assets/img/team-cto.svg" alt="Chief Technology Officer &amp; Systems Architect" />
                </div>
                <div class="exec-title-block">
                  <span class="exec-role" style="color: #64B5F6;">Chief Technology Officer</span>
                  <h3>Chief Technology Officer</h3>
                  <div class="exec-status"><span class="exec-status-dot" style="background: #64B5F6; box-shadow: 0 0 6px #64B5F6;"></span> Core Architecture &middot; AI Systems</div>
                </div>
              </div>

              <p class="exec-bio">
                Directs core backend infrastructure, distributed microservices, AI neural models, and secure transaction workflows. Oversees serverless cloud topologies, high-throughput REST/GraphQL APIs, database sharding, and 256-bit cryptographic payment gateway pipelines.
              </p>

              <div class="exec-chips">
                <span class="exec-chip">Distributed Systems</span>
                <span class="exec-chip">AI &amp; Neural Models</span>
                <span class="exec-chip">Fintech &amp; Gateway APIs</span>
                <span class="exec-chip">High-Concurrency Scaling</span>
              </div>
            </div>

            <div class="exec-actions">
              <a href="solutions.php" class="btn btn--ghost btn--sm" style="flex: 1; justify-content: center;">Explore Architectures ⚡</a>
              <a href="contact.php" class="btn btn--ghost btn--sm">Technical Advisory</a>
            </div>
          </article>

          <!-- Official 03: Director of Product Engineering -->
          <article class="exec-card">
            <div>
              <div class="exec-header">
                <div class="exec-avatar">
                  <img src="assets/img/team-engineering.svg" alt="Director of Engineering &amp; Mobile Ecosystems" />
                </div>
                <div class="exec-title-block">
                  <span class="exec-role" style="color: #A855F7;">Director of Engineering</span>
                  <h3>Director of Engineering</h3>
                  <div class="exec-status"><span class="exec-status-dot" style="background: #A855F7; box-shadow: 0 0 6px #A855F7;"></span> Full-Stack &amp; Mobile Platforms</div>
                </div>
              </div>

              <p class="exec-bio">
                Spearheads frontend web architectures, reactive UI/UX frameworks, and cross-platform native mobile applications (Capacitor / Android / iOS). Ensures every product repository adheres to strict sub-second performance budgets and pixel-perfect design integrity.
              </p>

              <div class="exec-chips">
                <span class="exec-chip">Next.js &amp; TypeScript</span>
                <span class="exec-chip">Native Mobile &amp; Capacitor</span>
                <span class="exec-chip">Design Systems</span>
                <span class="exec-chip">Sub-Second UI</span>
              </div>
            </div>

            <div class="exec-actions">
              <a href="services.php" class="btn btn--ghost btn--sm" style="flex: 1; justify-content: center;">View Engineering Stack 📱</a>
              <a href="work.php" class="btn btn--ghost btn--sm">Case Studies</a>
            </div>
          </article>

          <!-- Official 04: Director of Operations & Compliance -->
          <article class="exec-card">
            <div>
              <div class="exec-header">
                <div class="exec-avatar">
                  <img src="assets/img/team-ops.svg" alt="Director of Operations &amp; Client Success" />
                </div>
                <div class="exec-title-block">
                  <span class="exec-role" style="color: #22C55E;">Director of Operations</span>
                  <h3>Director of Operations</h3>
                  <div class="exec-status"><span class="exec-status-dot"></span> Client Governance &amp; 24/7 SLA</div>
                </div>
              </div>

              <p class="exec-bio">
                Governs agile sprint delivery schedules, legal compliance, international client relations, and permanent support desk operations. Maintains our 99.9% uptime commitments across 140+ live systems and coordinates dedicated project lead assignments.
              </p>

              <div class="exec-chips">
                <span class="exec-chip">Agile Delivery</span>
                <span class="exec-chip">99.9% SLA Management</span>
                <span class="exec-chip">Regulatory Compliance</span>
                <span class="exec-chip">Client Governance</span>
              </div>
            </div>

            <div class="exec-actions">
              <a href="contact.php" class="btn btn--ghost btn--sm" style="flex: 1; justify-content: center;">Connect Operations 🤝</a>
              <a href="terms.php" class="btn btn--ghost btn--sm">Governance</a>
            </div>
          </article>

        </div>
      </div>
    </section>

    <!-- Executive Principles & Governance -->
    <section class="compact">
      <div class="container container--wide">
        <div class="section-head">
          <div>
            <span class="eyebrow"><span class="dot" aria-hidden="true"></span>Governance</span>
            <h2>Executive Governance<br />&amp; Corporate Standards.</h2>
          </div>
          <p class="lede">
            Our high officials maintain direct oversight over every codebase, ensuring transparent pricing, non-disclosure confidentiality, and guaranteed technical excellence.
          </p>
        </div>

        <div class="cap-bento">
          <article class="cap-card cap-card--std">
            <span class="cap-num">01</span>
            <h3>Direct Partner Access.</h3>
            <p>You speak directly with lead architects and executive officers — no junior account managers or communication bottlenecks.</p>
          </article>
          <article class="cap-card cap-card--std">
            <span class="cap-num">02</span>
            <h3>D&amp;B Verified Trust.</h3>
            <p>Official D-U-N-S&reg; 30-704-2520 registered enterprise with verified corporate standing, legal compliance, and fiscal stability.</p>
          </article>
          <article class="cap-card cap-card--std">
            <span class="cap-num">03</span>
            <h3>Intellectual Property 100% Client-Owned.</h3>
            <p>Every line of custom code, database schema, design asset, and patentable logic belongs strictly to your enterprise upon delivery.</p>
          </article>
        </div>
      </div>
    </section>

    <!-- Closing Call to Action -->
    <section class="closing-cta">
      <div class="container container--narrow">
        <span class="label" style="color: rgba(10,10,12,0.6);">Executive Advisory &middot; 2026</span>
        <h2>Schedule an executive session with our leadership team.</h2>
        <p class="lede">
          Have an enterprise application brief, startup launchpad inquiry, or custom software roadmap? Our leadership team reviews briefs and responds within 48 hours.
        </p>
        <div class="cta-row">
          <a class="btn btn--dark btn--lg" href="contact.php">Book an executive meeting
            <svg class="arrow" width="16" height="10" viewBox="0 0 14 10" fill="none" aria-hidden="true">
              <path d="M1 5h12m0 0L9 1m4 4L9 9" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </a>
          <a class="btn btn--ghost btn--lg" href="pay.php">Invoice Portal</a>
        </div>
      </div>
    </section>

  </main>

  <footer class="site-footer">
    <div class="container container--wide">
      <div class="footer-top">
        <div class="footer-brand">
          <span class="brand"><span class="brand-mark"></span> THE EXPERT HUB</span>
          <p>A premium digital agency engineering custom web applications, mobile apps, software platforms, and automations based in Chennai.</p>
          <div class="duns-badge" style="display: inline-flex; align-items: center; gap: 8px; background: rgba(212, 255, 61, 0.06); border: 1px solid rgba(212, 255, 61, 0.22); border-radius: 6px; padding: 6px 12px; margin: 12px 0 10px; font-family: var(--font-mono, monospace); font-size: 11px; color: var(--fg, #f4f4f0); letter-spacing: 0.02em;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#D4FF3D" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
            <span><strong>D-U-N-S&reg; Registered&trade;</strong> &middot; <span style="color: #D4FF3D; font-weight: 700;">30-704-2520</span></span>
          </div>
          <span class="label">Studio &middot; 2018-2026</span>
        </div>
        <div>
          <h4>Platform</h4>
          <ul>
            <li><a href="index.php">Index</a></li>
            <li><a href="d-r.php">D-R Startup</a></li>
            <li><a href="services.php">Services</a></li>
            <li><a href="solutions.php">Solutions</a></li>
            <li><a href="leadership.php">Leadership</a></li>
          </ul>
        </div>
        <div>
          <h4>Support &amp; Impressum</h4>
          <ul>
            <li><a href="contact.php">Contact &amp; HQ</a></li>
            <li><a href="pay.php">Pay Invoice</a></li>
            <li><a href="privacy.php">Privacy Policy</a></li>
            <li><a href="terms.php">Terms of Service</a></li>
          </ul>
        </div>
      </div>
      <div class="footer-bottom">
        <span>&copy; 2026 THE EXPERT HUB &middot; D-U-N-S&reg; 30-704-2520 &middot; Engineered for performance.</span>
      </div>
    </div>
  </footer>

  <script src="assets/js/site.js" defer></script>
  <script src="assets/js/chatbot.js" defer></script>
</body>
</html>
