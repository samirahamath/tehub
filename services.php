<?php
require_once 'db.php';

$services = [];
if ($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM services WHERE status='active' ORDER BY id ASC");
        $services = $stmt->fetchAll();
    } catch (Exception $e) {
        $services = [];
    }
}

if (empty($services)) {
    // Fallback static data if DB connection is offline or empty
    $services = [
        [
            'id' => 1,
            'title' => 'MVP & Automation',
            'short_description' => 'For early-stage startups needing a functional prototype to showcase to investors, or businesses seeking custom workflow scripting and integrations.',
            'price' => 10000,
            'price_prefix' => 'From ₹',
            'duration_info' => '2 — 4 weeks',
            'features' => "1 Lead architect · 1 developer\nFully functional application prototype\nClean TypeScript backend & Next.js frontend\nCustom workflow integrations (Zapier, Make, custom APIs)\nZero-downtime cloud hosting setup (Vercel / AWS)\n3-month code warranty & updates"
        ],
        [
            'id' => 2,
            'title' => 'Custom App & Web',
            'short_description' => 'Our core tier — custom web platforms, native or cross-platform mobile apps (Flutter / React Native), high-performance databases, and custom API systems built for scaling.',
            'price' => 25000,
            'price_prefix' => 'From ₹',
            'duration_info' => '2 — 3 months',
            'features' => "1 Project lead · 2 developers · 1 DevOps\nProduction-grade web or mobile app codebase\nAutomated unit and integration testing pipelines\nAdvanced cloud config (AWS / GCP / Docker)\nFull database architecture & schema migrations\n12-month hosting maintenance & backups\nComprehensive API docs & system runbooks"
        ],
        [
            'id' => 3,
            'title' => 'Enterprise Partnership',
            'short_description' => 'A long-term engineering partnership. THE EXPERT HUB functions as your dedicated engineering and product squad, delivering weekly sprints and feature updates.',
            'price' => 75000,
            'price_prefix' => 'From ₹',
            'duration_info' => 'retainer scale',
            'features' => "Dedicated lead engineer, frontend, backend, & QA\nContinuous integration & deployment (CI/CD)\nPriority SLA on bug fixes and incident response\nBi-weekly sprint planning & demo presentations\nComprehensive security audits & code reviews\nFull access to private packages & shared modules\n24/7 server health and load monitoring"
        ]
    ];
}
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>THE EXPERT HUB · Services &amp; Rates — Premium Development Agency</title>
  <meta name="description"
    content="THE EXPERT HUB's three-tier service offering — MVP &amp; Automation, Custom App &amp; Web, and Enterprise Partnerships — with full agile process, rates, and FAQ." />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Boldonse&family=Inter+Tight:wght@400;500;600;700&family=Geist+Mono:wght@400;500&display=swap" />
  <link rel="stylesheet" href="assets/css/styles.css" />
</head>

<body>
  <a class="skip-link" href="#main">Skip to content</a>

  <header class="site-header">
    <div class="container container--wide">
      <nav class="nav" aria-label="Primary">
        <a class="brand" href="index.html"><span class="brand-mark" aria-hidden="true"></span> THE EXPERT HUB</a>
        <div class="nav-links" role="navigation">
          <a href="index.html">Index</a>
          <a href="work.html">Work</a>
          
          <a href="services.php" aria-current="page">Services</a>
          <a href="sales.php">Sales</a>
          <a href="contact.html">Contact</a>
        </div>
        <div class="nav-cta-row">
          <?php if(!empty($_SESSION['client_user'])): ?>
            <a href="client_dashboard.php" class="btn btn--ghost btn--sm">Client Dashboard</a>
          <?php else: ?>
            <a href="client_login.php" class="btn btn--ghost btn--sm">Client Portal</a>
          <?php endif; ?>
          <a href="admin_login.php" class="btn btn--ghost btn--sm" style="opacity:0.8;">Admin</a>
          <a href="contact.html" class="btn btn--primary btn--sm">Start a project
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
    <a href="index.html">Index</a>
    <a href="work.html">Work</a>
    <a href="services.php">Services</a>
    <a href="client_login.php">Client Portal</a>
    <a href="admin_login.php">Admin Login</a>
    <a href="contact.html">Contact</a>
  </div>

  <main id="main">

    <!-- Hero -->
    <section class="hero">
      <div class="container container--wide">
        <div class="hero-grid">
          <div class="hero-text">
            <span class="eyebrow"><span class="dot" aria-hidden="true"></span>Three tiers · One agency</span>
            <h1 class="hero-headline">
              Three working<br />
              <span class="lime">scopes,</span> one<br />
              honest rate card.
            </h1>
            <p class="hero-sub">
              Most digital agencies bury their pricing in a "request a quote" form. We do not — every development brief we have shipped in
              the last three years has fit into one of three tiers, and the starting rate is on the card. The conversation we
              want to have is about your product roadmap, not the budget.
            </p>
            <div class="hero-cta-row">
              <a class="btn btn--primary btn--lg" href="#rates">See the rates</a>
              <a class="btn btn--ghost btn--lg" href="#process">See the process</a>
            </div>
            <div class="hero-meta">
              <span><strong>3 — 6</strong> · weeks MVP delivery target</span>
              <span aria-hidden="true">·</span>
              <span><strong>3 — 5</strong> · months booking lead time</span>
              <span aria-hidden="true">·</span>
              <span><strong>12 — 15</strong> · projects per year</span>
            </div>
          </div>
          <div class="hero-media">
            <img src="assets/img/services-hero.svg"
              alt="A developer looking at lines of code and cloud deployment logs on a modern dashboard." />
            <div class="floating-tag ft-top">
              <span class="pill">Tier 02</span>
              Web App · ATRIUM
            </div>
            <div class="floating-tag ft-bottom">
              8 Sprints · Production Release
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Tiers -->
    <section id="rates">
      <div class="container container--wide">
        <div class="section-head">
          <div>
            <span class="eyebrow"><span class="dot" aria-hidden="true"></span>Service tiers</span>
            <h2>MVP. Custom App.<br />Enterprise.</h2>
          </div>
          <p class="lede">
            Our tiers map to the size and complexity of the codebase, not the size of the client company. A small startup can sit in Enterprise if they need a complex tech stack; a large brand can sit in MVP for automated pipelines. We will align on the right tier during our first call.
          </p>
        </div>

        <div class="tier-grid">
          <?php 
          $tier_num = 1;
          foreach($services as $s):
            $is_featured = (strpos(strtolower($s['title']), 'custom app') !== false);
            $tier_label = "Tier " . str_pad($tier_num++, 2, '0', STR_PAD_LEFT) . ($is_featured ? ' · most booked' : '');
            $btn_class = $is_featured ? 'btn--dark' : 'btn--ghost';
          ?>
            <article class="tier-card <?= $is_featured ? 'featured' : '' ?>">
              <span class="label"><?= htmlspecialchars($tier_label) ?></span>
              <h3><?= htmlspecialchars($s['title']) ?></h3>
              <span class="tier-meta"><?= htmlspecialchars($s['price_prefix'] ?? 'From ₹') ?><?= number_format($s['price']) ?> · <?= htmlspecialchars($s['duration_info'] ?? '') ?></span>
              <hr class="tier-divider" />
              <p><?= htmlspecialchars($s['short_description']) ?></p>
              <ul class="tier-list">
                <?php 
                  $features = explode("\n", $s['features']);
                  foreach($features as $f):
                    if(trim($f) !== ''):
                ?>
                  <li><?= htmlspecialchars(trim($f)) ?></li>
                <?php 
                    endif;
                  endforeach; 
                ?>
              </ul>
              <a class="btn <?= $btn_class ?>" href="checkout.php?service_id=<?= $s['id'] ?>">Order <?= htmlspecialchars($s['title']) ?></a>
            </article>
          <?php endforeach; ?>
        </div>

        <p class="mono" style="text-align: center; color: var(--fg-mute); margin-top: var(--space-7);">
          All rates are starting points · managed dynamically via Admin · 100% intellectual property transfer upon invoice clearance.
        </p>
      </div>
    </section>

    <!-- Process -->
    <section id="process">
      <div class="container container--wide">
        <div class="section-head">
          <div>
            <span class="eyebrow"><span class="dot" aria-hidden="true"></span>How we work</span>
            <h2>From the first<br />call to the<br />live system.</h2>
          </div>
          <p class="lede">
            Every product moves through the same eight phases, regardless of tier. The sprint cycles change, the principles do not. Most custom apps take two months; the shortest automation shipped in 9 days; the longest enterprise cycle has run for over two years.
          </p>
        </div>

        <div class="process-grid">
          <article class="process-card">
            <h3>Discovery</h3>
            <p>20-minute kickoff call with a technical lead. Review product specifications, workflows, and tech stack preferences. Free, always.</p>
          </article>
          <article class="process-card">
            <h3>Scope</h3>
            <p>Within 72 hours: a detailed scope of work, technical architecture roadmap, and a fixed cost estimate. No budget creep.</p>
          </article>
          <article class="process-card">
            <h3>Architecture</h3>
            <p>Establish database schemas, API endpoints, wireframes, and design system tokens before a single line of production code is written.</p>
          </article>
          <article class="process-card">
            <h3>Sprint Kickoff</h3>
            <p>One-hour kickoff meeting to establish backlog priorities, milestones, and testing configurations on staging environments.</p>
          </article>
          <article class="process-card">
            <h3>Development</h3>
            <p>2-week agile sprints. High-concurrency database models, custom API logic, and pixel-perfect frontends integrated seamlessly.</p>
          </article>
          <article class="process-card">
            <h3>QA &amp; Audit</h3>
            <p>Every PR goes through automated testing pipelines, security audits, and manual developer verification on staging environments.</p>
          </article>
          <article class="process-card">
            <h3>Deployment</h3>
            <p>Zero-downtime launch. DNS redirection, SSL certificate setup, automated backups, and cloud monitoring tools activated.</p>
          </article>
          <article class="process-card">
            <h3>Handover</h3>
            <p>Complete source code repo transfer, API Postman collections, developer runbooks, and team training sessions. All yours.</p>
          </article>
        </div>
      </div>
    </section>

  </main>
</body>
</html>
