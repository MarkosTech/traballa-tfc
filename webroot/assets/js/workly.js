/**
 * Workly chat
 * 
 * @copyright 2025 Marcos Núñez Fernández
 * @license   MIT License
 * 
 */

class WorklyChat {
  constructor() {
    this.workHours = {
      start: 9, // 9 AM default
      end: 17   // 5 PM default
    };
    this.breaks = [];
    this.isCollapsed = true;
    
    this.initUI();
    this.setupEventListeners();
    
    // Only greet when first expanded
    this.hasGreeted = false;
  }
  
  initUI() {
    // This would be handled by MCP in your implementation
    this.container = document.getElementById('workly-container');
    this.toggleButton = document.querySelector('.workly-toggle');
    this.minimizedView = document.querySelector('.workly-minimized');
  }
  
  setupEventListeners() {
    // Toggle between collapsed/expanded states
    this.toggleButton.addEventListener('click', (e) => {
      e.stopPropagation();
      this.toggleChat();
    });
    
    // Click on minimized view to expand
    this.minimizedView.addEventListener('click', () => {
      this.expandChat();
    });
    
    // Send message
    document.querySelector('.workly-send').addEventListener('click', () => this.handleSend());
    document.querySelector('.workly-input input').addEventListener('keypress', (e) => {
      if (e.key === 'Enter') this.handleSend();
    });
  }
  
  toggleChat() {
    this.isCollapsed = !this.isCollapsed;
    this.container.classList.toggle('workly-collapsed');
    this.toggleButton.textContent = this.isCollapsed ? '↑' : '↓';
    
    if (!this.isCollapsed && !this.hasGreeted) {
      this.greetUser();
      this.hasGreeted = true;
    }
  }
  
  expandChat() {
    if (this.isCollapsed) {
      this.isCollapsed = false;
      this.container.classList.remove('workly-collapsed');
      this.toggleButton.textContent = '↓';
      
      if (!this.hasGreeted) {
        this.greetUser();
        this.hasGreeted = true;
      }
    }
  }
  
  collapseChat() {
    if (!this.isCollapsed) {
      this.isCollapsed = true;
      this.container.classList.add('workly-collapsed');
      this.toggleButton.textContent = '↑';
    }
  }
  
  greetUser() {
    const now = new Date();
    const hour = now.getHours();
    let greeting = "Hello!";
    
    if (hour < 12) greeting = "Good morning!";
    else if (hour < 18) greeting = "Good afternoon!";
    else greeting = "Good evening!";
    
    this.addMessage('workly', `${greeting} I'm Workly, your Traballa assistant. How can I help?`);
  }
  
  addMessage(sender, text) {
    const messagesDiv = document.querySelector('.workly-messages');
    const messageDiv = document.createElement('div');
    messageDiv.classList.add('message', sender);
    messageDiv.textContent = text;
    messagesDiv.appendChild(messageDiv);
    messagesDiv.scrollTop = messagesDiv.scrollHeight;
  }
  
  handleSend() {
    const input = document.querySelector('.workly-input input');
    const text = input.value.trim();
    
    if (text) {
      this.addMessage('user', text);
      input.value = '';
      this.processQuery(text);
    }
  }
  
  processQuery(query) {
    // Expand the chat if it's collapsed when user sends a message
    if (this.isCollapsed) {
      this.expandChat();
    }
    
    // Your existing query processing logic
    const lowerQuery = query.toLowerCase();
    
    if (lowerQuery.includes('hello') || lowerQuery.includes('hi')) {
      this.addMessage('workly', 'Hello there! How can I assist with your work schedule?');
    }
    else if (lowerQuery.includes('hours') || lowerQuery.includes('time')) {
      this.addMessage('workly', `Your Traballa are set from ${this.workHours.start}:00 to ${this.workHours.end}:00.`);
    }
    else if (lowerQuery.includes('break')) {
      if (this.breaks.length > 0) {
        let breakText = 'Your scheduled breaks:\n';
        this.breaks.forEach((b, i) => {
          breakText += `${i+1}. From ${b.start} to ${b.end}\n`;
        });
        this.addMessage('workly', breakText);
      } else {
        this.addMessage('workly', 'You have no breaks scheduled. Would you like to add one?');
      }
    }
    else {
      this.addMessage('workly', "I'm currently in development and will soon be able to ask questions using MCP and DeepSeek. Stay tuned!");
    }
  }
}

// Initialize when ready
document.addEventListener('DOMContentLoaded', () => {
  const workly = new WorklyChat();
  
  // Optional: Auto-collapse when clicking outside
  document.addEventListener('click', (e) => {
    if (!e.target.closest('#workly-container')) {
      workly.collapseChat();
    }
  });
});