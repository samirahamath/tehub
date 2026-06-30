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
})();

/* ==========================================
   THE EXPERT HUB — Premium FAQ Chatbot
   ========================================== */
(function () {
  'use strict';

  // 1. Inject Chatbot CSS dynamically
  const css = `
    :root {
      --chat-bg: rgba(10, 10, 12, 0.85);
      --chat-card: rgba(24, 24, 28, 0.95);
      --chat-border: rgba(244, 244, 240, 0.08);
      --chat-glow: rgba(212, 255, 61, 0.15);
    }
    .tehub-chat-widget {
      position: fixed;
      bottom: 24px;
      right: 24px;
      z-index: 1000;
      font-family: var(--font-body), sans-serif;
      display: flex;
      flex-direction: column;
      align-items: flex-end;
    }
    .tehub-chat-trigger {
      width: 56px;
      height: 56px;
      border-radius: 50%;
      background: var(--lime);
      color: var(--ink-000);
      border: none;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 8px 32px var(--lime-glow), 0 4px 12px rgba(0, 0, 0, 0.3);
      transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
      position: relative;
      outline: none;
    }
    .tehub-chat-trigger:hover {
      transform: scale(1.08) translateY(-2px);
      background: var(--lime-soft);
      box-shadow: 0 12px 40px rgba(212, 255, 61, 0.3), 0 6px 16px rgba(0, 0, 0, 0.4);
    }
    .tehub-chat-trigger:active {
      transform: scale(0.95);
    }
    .tehub-chat-badge {
      position: absolute;
      top: -2px;
      right: -2px;
      width: 14px;
      height: 14px;
      background: #ff4a4a;
      border: 2px solid var(--ink-deep);
      border-radius: 50%;
      animation: pulse-red 2s infinite;
      display: none;
    }
    .tehub-chat-icon-open,
    .tehub-chat-icon-close {
      transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .tehub-chat-widget.open .tehub-chat-icon-open {
      transform: rotate(-90deg) scale(0);
      opacity: 0;
      position: absolute;
    }
    .tehub-chat-widget:not(.open) .tehub-chat-icon-close {
      transform: rotate(90deg) scale(0);
      opacity: 0;
      position: absolute;
    }
    .tehub-chat-window {
      width: 380px;
      max-width: calc(100vw - 48px);
      height: 520px;
      max-height: calc(100vh - 120px);
      background: var(--chat-bg);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      border: 1px solid var(--chat-border);
      border-radius: 20px;
      box-shadow: 0 16px 48px rgba(0, 0, 0, 0.5), 0 0 1px rgba(255, 255, 255, 0.1) inset;
      display: flex;
      flex-direction: column;
      overflow: hidden;
      margin-bottom: 16px;
      opacity: 0;
      transform: translateY(20px) scale(0.95);
      pointer-events: none;
      transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .tehub-chat-widget.open .tehub-chat-window {
      opacity: 1;
      transform: translateY(0) scale(1);
      pointer-events: all;
    }
    .tehub-chat-header {
      padding: 16px 20px;
      background: rgba(24, 24, 28, 0.6);
      border-bottom: 1px solid var(--chat-border);
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .tehub-chat-bot-profile {
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .tehub-chat-avatar {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: var(--chat-border);
      border: 1.5px solid var(--lime);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      position: relative;
    }
    .tehub-chat-status-dot {
      position: absolute;
      bottom: 0;
      right: 0;
      width: 9px;
      height: 9px;
      background: #39d353;
      border: 1.5px solid var(--ink-deep);
      border-radius: 50%;
    }
    .tehub-chat-bot-info h4 {
      font-size: 0.9375rem;
      font-weight: 600;
      margin: 0;
      color: var(--paper);
      line-height: 1.2;
    }
    .tehub-chat-bot-info span {
      font-size: 0.75rem;
      color: var(--paper-soft);
      display: flex;
      align-items: center;
      gap: 4px;
    }
    .tehub-chat-close {
      background: transparent;
      border: none;
      color: var(--paper-mute);
      cursor: pointer;
      padding: 4px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.2s ease;
    }
    .tehub-chat-close:hover {
      color: var(--paper);
      background: var(--chat-border);
    }
    .tehub-chat-messages {
      flex: 1;
      padding: 20px;
      overflow-y: auto;
      display: flex;
      flex-direction: column;
      gap: 16px;
      scroll-behavior: smooth;
    }
    .tehub-chat-messages::-webkit-scrollbar {
      width: 5px;
    }
    .tehub-chat-messages::-webkit-scrollbar-track {
      background: transparent;
    }
    .tehub-chat-messages::-webkit-scrollbar-thumb {
      background: rgba(255, 255, 255, 0.1);
      border-radius: 10px;
    }
    .tehub-chat-messages::-webkit-scrollbar-thumb:hover {
      background: rgba(255, 255, 255, 0.2);
    }
    .tehub-message {
      max-width: 82%;
      padding: 12px 16px;
      font-size: 0.875rem;
      line-height: 1.45;
      animation: message-pop 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    .tehub-message--bot {
      background: var(--chat-card);
      border: 1px solid var(--chat-border);
      color: var(--paper);
      align-self: flex-start;
      border-radius: 0 16px 16px 16px;
    }
    .tehub-message--user {
      background: var(--lime);
      color: var(--ink-000);
      align-self: flex-end;
      border-radius: 16px 16px 0 16px;
      font-weight: 500;
    }
    .tehub-typing-indicator {
      display: flex;
      gap: 4px;
      padding: 12px 16px;
      background: var(--chat-card);
      border: 1px solid var(--chat-border);
      border-radius: 0 16px 16px 16px;
      align-self: flex-start;
      width: fit-content;
    }
    .tehub-typing-indicator span {
      width: 6px;
      height: 6px;
      background: var(--paper-soft);
      border-radius: 50%;
      animation: typing-dots 1.4s infinite ease-in-out;
    }
    .tehub-typing-indicator span:nth-child(2) { animation-delay: 0.2s; }
    .tehub-typing-indicator span:nth-child(3) { animation-delay: 0.4s; }
    .tehub-quick-replies {
      display: flex;
      flex-direction: column;
      gap: 8px;
      padding: 12px 20px;
      border-top: 1px solid var(--chat-border);
      background: rgba(24, 24, 28, 0.3);
    }
    .tehub-quick-replies-title {
      font-size: 0.75rem;
      color: var(--paper-mute);
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      margin-bottom: 2px;
    }
    .tehub-quick-replies-list {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      max-height: 96px;
      overflow-y: auto;
    }
    .tehub-reply-btn {
      background: var(--chat-card);
      border: 1px solid var(--chat-border);
      color: var(--paper-soft);
      padding: 8px 12px;
      border-radius: 30px;
      font-size: 0.8125rem;
      cursor: pointer;
      transition: all 0.2s ease;
      white-space: nowrap;
      font-family: var(--font-body), sans-serif;
    }
    .tehub-reply-btn:hover {
      background: var(--chat-border);
      color: var(--lime);
      border-color: var(--lime-soft);
      transform: translateY(-1px);
    }
    .tehub-chat-input-container {
      padding: 12px 20px;
      background: rgba(10, 10, 12, 0.95);
      border-top: 1px solid var(--chat-border);
      display: flex;
      gap: 8px;
      align-items: center;
    }
    .tehub-chat-input {
      flex: 1;
      background: var(--chat-card);
      border: 1px solid var(--chat-border);
      color: var(--paper);
      padding: 10px 14px;
      border-radius: 24px;
      font-size: 0.875rem;
      outline: none;
      font-family: var(--font-body), sans-serif;
      transition: border-color 0.2s ease;
    }
    .tehub-chat-input:focus {
      border-color: var(--lime);
    }
    .tehub-chat-input::placeholder {
      color: var(--paper-mute);
    }
    .tehub-chat-send {
      background: var(--lime);
      color: var(--ink-000);
      border: none;
      width: 36px;
      height: 36px;
      border-radius: 50%;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.2s ease;
      flex-shrink: 0;
    }
    .tehub-chat-send:hover {
      background: var(--lime-soft);
      transform: scale(1.05);
    }
    .tehub-chat-send:active {
      transform: scale(0.95);
    }
    @keyframes pulse-red {
      0% { box-shadow: 0 0 0 0 rgba(255, 74, 74, 0.7); }
      70% { box-shadow: 0 0 0 6px rgba(255, 74, 74, 0); }
      100% { box-shadow: 0 0 0 0 rgba(255, 74, 74, 0); }
    }
    @keyframes typing-dots {
      0%, 100% { transform: translateY(0); opacity: 0.4; }
      50% { transform: translateY(-4px); opacity: 1; }
    }
    @keyframes message-pop {
      from { opacity: 0; transform: translateY(8px) scale(0.96); }
      to { opacity: 1; transform: translateY(0) scale(1); }
    }
    @media (max-width: 480px) {
      .tehub-chat-widget { bottom: 16px; right: 16px; }
      .tehub-chat-window { width: calc(100vw - 32px); height: 480px; bottom: 12px; }
    }
  `;
  const styleEl = document.createElement('style');
  styleEl.textContent = css;
  document.head.appendChild(styleEl);

  // 2. Chatbot Database
  const faqData = [
    {
      keywords: ['d-r', 'dream to real', 'startup', 'launchpad', 'incubation', 'business'],
      question: "What is D-R Launchpad?",
      answer: "Dream to Real (D-R) is our comprehensive startup incubation launchpad. We guide new business owners from concept to cashflow, covering location analysis, company registration/partnership docs, low-cost website design (from ₹3,000), digital marketing (from ₹3,000/mo), and permanent support."
    },
    {
      keywords: ['website', 'price', 'cost', 'rate', 'budget', 'pricing', 'charge', 'fees'],
      question: "How much does a website cost?",
      answer: "Our entry-level responsive website designs start at just ₹3,000. For more complex custom apps, platforms, or automation, we offer transparent rates based on project scope. Check our Services page for rates or contact us for a free quote!"
    },
    {
      keywords: ['software', 'products', 'ready', 'systems', 'sms', 'ivr', 'whatsapp', 'school'],
      question: "What ready software do you sell?",
      answer: "We offer several ready-to-deploy platforms:\n• School Management System (complete ERP)\n• IVR Auto-Calling System\n• Bulk WhatsApp/Telegram SMS Solution\nAll platforms are fully customizable to your brand. Details on our Sales page!"
    },
    {
      keywords: ['location', 'where', 'address', 'office', 'hyderabad', 'chennai', 'india'],
      question: "Where are you located?",
      answer: "THE EXPERT HUB is based in India, operating principally between Chennai and Hyderabad. We collaborate with clients globally. You can request a video call or meeting via our contact form."
    },
    {
      keywords: ['contact', 'email', 'start', 'hire', 'phone', 'reach', 'interested', 'call'],
      question: "How do I start a project?",
      answer: "You can easily start a project by filling out the form on our <a href='contact.html' style='color: var(--lime); text-decoration: underline;'>Contact page</a>, or by emailing us directly at hello@tehub.in. Our team will get back to you within 24 hours."
    },
    {
      keywords: ['services', 'dev', 'web', 'app', 'android', 'ios', 'automation', 'custom'],
      question: "What services do you offer?",
      answer: "We specialize in:\n1. Custom software development\n2. Mobile app & web application engineering\n3. MVP development & process automation\n4. D-R Startup Incubation\nVisit our Services page for our full agile workflow details."
    }
  ];

  function formatResponse(text) {
    return text.replace(/\n/g, '<br>');
  }

  // 3. Inject Chatbot HTML
  function injectChatbot() {
    // Avoid double injection
    if (document.getElementById('tehub-chatbot')) return;

    const widget = document.createElement('div');
    widget.className = 'tehub-chat-widget';
    widget.id = 'tehub-chatbot';

    widget.innerHTML = `
      <div class="tehub-chat-window" id="tehub-chat-window">
        <div class="tehub-chat-header">
          <div class="tehub-chat-bot-profile">
            <div class="tehub-chat-avatar">
              🤖
              <span class="tehub-chat-status-dot"></span>
            </div>
            <div class="tehub-chat-bot-info">
              <h4>TEHUB Assistant</h4>
              <span>Online · Quick replies</span>
            </div>
          </div>
          <button class="tehub-chat-close" id="tehub-chat-close" aria-label="Close Chat">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="18" y1="6" x2="6" y2="18"></line>
              <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
          </button>
        </div>
        
        <div class="tehub-chat-messages" id="tehub-chat-messages">
          <div class="tehub-message tehub-message--bot">
            Hi! 👋 Welcome to THE EXPERT HUB. I'm your digital assistant. How can I help you today?
          </div>
        </div>

        <div class="tehub-quick-replies">
          <div class="tehub-quick-replies-title">Frequently Asked Questions</div>
          <div class="tehub-quick-replies-list" id="tehub-quick-replies-list"></div>
        </div>

        <div class="tehub-chat-input-container">
          <input type="text" class="tehub-chat-input" id="tehub-chat-input" placeholder="Type your question..." aria-label="Ask a question">
          <button class="tehub-chat-send" id="tehub-chat-send" aria-label="Send Message">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <line x1="22" y1="2" x2="11" y2="13"></line>
              <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
            </svg>
          </button>
        </div>
      </div>

      <button class="tehub-chat-trigger" id="tehub-chat-trigger" aria-label="Open Chat">
        <span class="tehub-chat-badge" id="tehub-chat-badge"></span>
        <svg class="tehub-chat-icon-open" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
        </svg>
        <svg class="tehub-chat-icon-close" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="18" y1="6" x2="6" y2="18"></line>
          <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
      </button>
    `;

    document.body.appendChild(widget);

    // Setup element references and event listeners
    const trigger = document.getElementById('tehub-chat-trigger');
    const closeBtn = document.getElementById('tehub-chat-close');
    const messagesContainer = document.getElementById('tehub-chat-messages');
    const inputEl = document.getElementById('tehub-chat-input');
    const sendBtn = document.getElementById('tehub-chat-send');
    const quickRepliesContainer = document.getElementById('tehub-quick-replies-list');
    const badge = document.getElementById('tehub-chat-badge');

    let hasOpened = false;

    function toggleChat() {
      widget.classList.toggle('open');
      if (widget.classList.contains('open')) {
        if (badge) badge.style.display = 'none';
        hasOpened = true;
        setTimeout(() => {
          inputEl.focus();
          messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }, 300);
      }
    }

    trigger.addEventListener('click', toggleChat);
    closeBtn.addEventListener('click', toggleChat);

    function addMessage(text, isUser = false) {
      const msg = document.createElement('div');
      msg.className = `tehub-message tehub-message--${isUser ? 'user' : 'bot'}`;
      msg.innerHTML = text;
      messagesContainer.appendChild(msg);
      messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    function showTypingIndicator() {
      const indicator = document.createElement('div');
      indicator.className = 'tehub-typing-indicator';
      indicator.id = 'tehub-typing-indicator';
      indicator.innerHTML = '<span></span><span></span><span></span>';
      messagesContainer.appendChild(indicator);
      messagesContainer.scrollTop = messagesContainer.scrollHeight;
      return indicator;
    }

    function loadQuickReplies() {
      quickRepliesContainer.innerHTML = '';
      faqData.forEach(item => {
        const btn = document.createElement('button');
        btn.className = 'tehub-reply-btn';
        btn.textContent = item.question;
        btn.addEventListener('click', () => {
          handleUserMessage(item.question);
        });
        quickRepliesContainer.appendChild(btn);
      });
    }

    loadQuickReplies();

    function handleBotReply(userInput) {
      const indicator = showTypingIndicator();
      const cleanInput = userInput.toLowerCase().trim();

      setTimeout(() => {
        if (indicator && indicator.parentNode) {
          indicator.parentNode.removeChild(indicator);
        }

        if (cleanInput === 'hi' || cleanInput === 'hello' || cleanInput === 'hey') {
          addMessage("Hello there! How can I assist you with your business, website, or software needs today?");
          return;
        }

        let bestMatch = null;
        let highestScore = 0;

        faqData.forEach(item => {
          let score = 0;
          item.keywords.forEach(keyword => {
            if (cleanInput.includes(keyword)) {
              score++;
            }
          });
          if (score > highestScore) {
            highestScore = score;
            bestMatch = item;
          }
        });

        if (bestMatch && highestScore > 0) {
          addMessage(formatResponse(bestMatch.answer));
        } else {
          addMessage("I'm not entirely sure about that, but our team can help! You can send us an inquiry on our <a href='contact.html' style='color: var(--lime); text-decoration: underline;'>Contact page</a> or email hello@tehub.in.");
        }
      }, 900);
    }

    function handleUserMessage(text) {
      if (!text.trim()) return;
      addMessage(text, true);
      handleBotReply(text);
    }

    sendBtn.addEventListener('click', () => {
      const text = inputEl.value;
      inputEl.value = '';
      handleUserMessage(text);
    });

    inputEl.addEventListener('keydown', (e) => {
      if (e.key === 'Enter') {
        const text = inputEl.value;
        inputEl.value = '';
        handleUserMessage(text);
      }
    });

    setTimeout(() => {
      if (!hasOpened && badge) {
        badge.style.display = 'block';
      }
    }, 5000);
  }

  // Load when document is fully loaded
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', injectChatbot);
  } else {
    injectChatbot();
  }
})();
