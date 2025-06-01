<?php
/**
 * Traballa - Workly assistant
 * 
 * @copyright 2025 Marcos Núñez Fernández
 * @license   MIT License
 * @link      https://github.com/markostech/traballa-tfc
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 */

?>
<style>
#workly-container {
  position: fixed;
  bottom: 20px;
  right: 20px;
  max-width: 500px;
  width: 90vw;
  background: white;
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.2);
  font-family: Arial, sans-serif;
  z-index: 1000;
  overflow: hidden;
  transition: all 0.3s ease;
  box-sizing: border-box;
}

/* Expanded state */
#workly-container:not(.workly-collapsed) .workly-content {
  display: flex;
  flex-direction: column;
  height: 550px;
}

#workly-container:not(.workly-collapsed) .workly-minimized {
  display: none;
}

/* Collapsed state */
#workly-container.workly-collapsed {
  width: 150px;
  height: 40px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  pointer-events: auto;
}

#workly-container.workly-collapsed .workly-header,
#workly-container.workly-collapsed .workly-content {
  display: none;
}

#workly-container.workly-collapsed .workly-minimized {
  display: flex;
  align-items: center;
  padding: 0 10px;
  height: 100%;
  cursor: pointer;
}

.workly-minimized-icon {
  font-size: 20px;
  margin-right: 8px;
}

.workly-header {
  background: #4285f4;
  color: white;
  padding: 10px 15px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.workly-header h3 {
  margin: 0;
  font-size: 16px;
}

.workly-toggle {
  background: none;
  border: none;
  color: white;
  font-size: 16px;
  cursor: pointer;
  padding: 5px;
  transition: transform 0.3s ease;
}

.workly-toggle:hover {
  transform: scale(1.1);
}

.workly-messages {
  flex-grow: 1;
  overflow-y: auto;
  padding: 10px;
  background: #f9f9f9;
  display: flex;
  flex-direction: column;
}

/* Chat Suggestions */
.workly-suggestions {
  display: flex;
  flex-wrap: wrap;
  gap: 5px;
  padding: 10px;
  border-bottom: 1px solid #eee;
  background: #f1f1f1;
}

.workly-suggestions button {
  background: #e0e0e0;
  border: none;
  border-radius: 15px;
  padding: 5px 10px;
  font-size: 12px;
  cursor: pointer;
  transition: background 0.3s ease;
}

.workly-suggestions button:hover {
  background: #d6d6d6;
}

.workly-input {
  display: flex;
  padding: 10px;
  border-top: 1px solid #eee;
}

.workly-input input {
  flex-grow: 1;
  padding: 8px;
  border: 1px solid #ddd;
  border-radius: 4px;
  margin-right: 5px;
}

.workly-input button {
  background: #4285f4;
  color: white;
  border: none;
  border-radius: 4px;
  padding: 8px 15px;
  cursor: pointer;
  transition: background 0.3s ease;
}

.workly-input button:hover {
  background: #357ae8;
}

.message {
  margin-bottom: 10px;
  padding: 8px 12px;
  border-radius: 18px;
  max-width: 80%;
  word-wrap: break-word;
}

.message.workly {
  background: #e3e3e3;
  align-self: flex-start;
}

.message.user {
  background: #4285f4;
  color: white;
  align-self: flex-end;
}
</style>

<div id="workly-container" class="workly-collapsed">
  <div class="workly-header">
    <h3>Workly Assistant</h3>
    <button class="workly-toggle" aria-label="Toggle Workly">↑</button>
  </div>

  <div class="workly-content">
    <!-- Chat Suggestions -->
    <div class="workly-suggestions">
      <button onclick="setSuggestion('Clock in')">Clock In</button>
      <button onclick="setSuggestion('Clock out')">Clock Out</button>
      <button onclick="setSuggestion('How many hours have I worked today?')">Today's Traballa</button>
      <button onclick="setSuggestion('How many hours did I work yesterday?')">Yesterday's Traballa</button>
      <button onclick="setSuggestion('What is my total work time this week?')">Weekly Work Summary</button>
      <button onclick="setSuggestion('Do I have any overtime this month?')">Overtime Summary</button>
    </div>

    <div class="workly-messages">
      <!-- Messages will appear here -->
    </div>
    
    <div class="workly-input">
      <input type="text" id="workly-input" placeholder="Ask Workly..." aria-label="Workly Input">
      <button class="workly-send" aria-label="Send Message">Send</button>
    </div>
  </div>

  <div class="workly-minimized">
    <div class="workly-minimized-icon">💼</div>
    <span>Workly</span>
  </div>
</div>

<script>
  function setSuggestion(text) {
    document.getElementById("workly-input").value = text;
  }
</script>

<script src="/assets/js/workly.js?v=latest"></script>
