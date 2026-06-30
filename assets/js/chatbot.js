/* THE EXPERT HUB — Premium FAQ Chatbot Script */

(function () {
  'use strict';

  // FAQ Database
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

  // Helper to format line breaks into HTML
  function formatResponse(text) {
    return text.replace(/\n/g, '<br>');
  }

  // Inject Chatbot HTML
  function injectChatbot() {
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
  }

  injectChatbot();

  // Cache elements
  const widget = document.getElementById('tehub-chatbot');
  const trigger = document.getElementById('tehub-chat-trigger');
  const closeBtn = document.getElementById('tehub-chat-close');
  const messagesContainer = document.getElementById('tehub-chat-messages');
  const inputEl = document.getElementById('tehub-chat-input');
  const sendBtn = document.getElementById('tehub-chat-send');
  const quickRepliesContainer = document.getElementById('tehub-quick-replies-list');
  const badge = document.getElementById('tehub-chat-badge');

  let hasOpened = false;

  // Toggle chat
  function toggleChat() {
    widget.classList.toggle('open');
    if (widget.classList.contains('open')) {
      if (badge) badge.style.display = 'none';
      hasOpened = true;
      setTimeout(() => {
        inputEl.focus();
        scrollToBottom();
      }, 300);
    }
  }

  trigger.addEventListener('click', toggleChat);
  closeBtn.addEventListener('click', toggleChat);

  // Scroll to bottom
  function scrollToBottom() {
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
  }

  // Add Message to UI
  function addMessage(text, isUser = false) {
    const msg = document.createElement('div');
    msg.className = `tehub-message tehub-message--${isUser ? 'user' : 'bot'}`;
    msg.innerHTML = text;
    messagesContainer.appendChild(msg);
    scrollToBottom();
  }

  // Show typing indicator
  function showTypingIndicator() {
    const indicator = document.createElement('div');
    indicator.className = 'tehub-typing-indicator';
    indicator.id = 'tehub-typing-indicator';
    indicator.innerHTML = '<span></span><span></span><span></span>';
    messagesContainer.appendChild(indicator);
    scrollToBottom();
    return indicator;
  }

  // Load Quick Replies
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

  // Handle bot reply logic
  function handleBotReply(userInput) {
    const indicator = showTypingIndicator();
    const cleanInput = userInput.toLowerCase().trim();

    setTimeout(() => {
      // Remove typing indicator
      if (indicator && indicator.parentNode) {
        indicator.parentNode.removeChild(indicator);
      }

      // Check greetings
      if (cleanInput === 'hi' || cleanInput === 'hello' || cleanInput === 'hey' || cleanInput === 'yo') {
        addMessage("Hello there! How can I assist you with your business, website, or software needs today?");
        return;
      }

      // Search database
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

  // Handle user input
  function handleUserMessage(text) {
    if (!text.trim()) return;
    addMessage(text, true);
    handleBotReply(text);
  }

  // Input events
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

  // Prompt chat badge after 5 seconds if not opened
  setTimeout(() => {
    if (!hasOpened && badge) {
      badge.style.display = 'block';
    }
  }, 5000);

})();
