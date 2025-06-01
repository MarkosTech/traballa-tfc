/**
 * Traballa - Pomodoro Timer
 * Implementa la técnica Pomodoro para gestión del tiempo
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

class PomodoroTimer {
  constructor(options = {}) {
    // Configuración por defecto
    this.workTime = options.workTime || 25 * 60; // 25 minutos en segundos
    this.shortBreakTime = options.shortBreakTime || 5 * 60; // 5 minutos en segundos
    this.longBreakTime = options.longBreakTime || 15 * 60; // 15 minutos en segundos
    this.cycles = options.cycles || 4; // Número de ciclos antes de un descanso largo
    
    // Cargar configuración y estado de localStorage si existe
    this.loadStateFromStorage();
    
    // Estado del temporizador (si no se cargó del storage)
    if (!this.stateLoaded) {
      this.timeLeft = this.workTime;
      this.isRunning = false;
      this.isPaused = false;
      this.currentCycle = 1;
      this.mode = 'work'; // work, shortBreak, longBreak
    }
    
    this.timer = null;
    
    // Callbacks
    this.onTick = options.onTick || function() {};
    this.onComplete = options.onComplete || function() {};
    this.onStateChange = options.onStateChange || function() {};
    
    // Elementos del DOM
    this.initUI();
    
    // Si estaba corriendo cuando se guardó el estado, reiniciar el temporizador
    if (this.isRunning && !this.isPaused) {
      this.start();
    }
  }
  
  loadStateFromStorage() {
    try {
      // Intentar cargar las configuraciones guardadas
      const savedSettings = localStorage.getItem('pomodoroSettings');
      if (savedSettings) {
        const settings = JSON.parse(savedSettings);
        this.workTime = settings.workTime || this.workTime;
        this.shortBreakTime = settings.shortBreakTime || this.shortBreakTime;
        this.longBreakTime = settings.longBreakTime || this.longBreakTime;
        this.cycles = settings.cycles || this.cycles;
      }
      
      // Intentar cargar el estado actual
      const savedState = localStorage.getItem('pomodoroState');
      if (savedState) {
        const state = JSON.parse(savedState);
        this.timeLeft = state.timeLeft;
        this.isRunning = state.isRunning;
        this.isPaused = state.isPaused;
        this.currentCycle = state.currentCycle;
        this.mode = state.mode;
        
        // Verificar si el timer estaba activo pero el tiempo ha pasado (si el navegador estaba cerrado)
        if (this.isRunning && !this.isPaused) {
          const lastTimestamp = state.timestamp || 0;
          const currentTimestamp = new Date().getTime();
          const elapsedSeconds = Math.floor((currentTimestamp - lastTimestamp) / 1000);
          
          if (elapsedSeconds > 0) {
            this.timeLeft = Math.max(0, this.timeLeft - elapsedSeconds);
            
            // Si el tiempo ha terminado mientras estaba cerrado, pasar al siguiente ciclo
            if (this.timeLeft <= 0) {
              this.completeCurrentCycle();
            }
          }
          setTimeout(() => {
            this.isRunning = false;
            this.isPaused = true;
            this.start();
          }, 500);
        }
        
        this.stateLoaded = true;
      } else {
        this.stateLoaded = false;
      }
    } catch(e) {
      console.error('Error loading Pomodoro state from storage:', e);
      this.stateLoaded = false;
    }
  }
  
  saveStateToStorage() {
    try {
      // Guardar configuraciones
      const settings = {
        workTime: this.workTime,
        shortBreakTime: this.shortBreakTime,
        longBreakTime: this.longBreakTime,
        cycles: this.cycles
      };
      localStorage.setItem('pomodoroSettings', JSON.stringify(settings));
      
      // Guardar estado actual con timestamp
      const state = {
        timeLeft: this.timeLeft,
        isRunning: this.isRunning,
        isPaused: this.isPaused,
        currentCycle: this.currentCycle,
        mode: this.mode,
        timestamp: new Date().getTime()
      };
      localStorage.setItem('pomodoroState', JSON.stringify(state));
    } catch(e) {
      console.error('Error saving Pomodoro state to storage:', e);
    }
  }
  
  initUI() {
    // Crear contenedor principal
    this.container = document.createElement('div');
    this.container.id = 'pomodoro-container';
    this.container.className = 'pomodoro-container';
    
    // Crear el HTML para el temporizador
    this.container.innerHTML = `
      <div class="pomodoro-header">
        <h5>Pomodoro timer</h5>
      </div>
      <div class="pomodoro-timer">
        <div class="pomodoro-time" id="pomodoro-time">25:00</div>
        <div class="pomodoro-status" id="pomodoro-status">Ready</div>
        <div class="pomodoro-cycles" id="pomodoro-cycles">
          <span class="cycle-indicator active"></span>
          <span class="cycle-indicator"></span>
          <span class="cycle-indicator"></span>
          <span class="cycle-indicator"></span>
        </div>
      </div>
      <div class="pomodoro-controls">
        <button class="btn btn-primary" id="pomodoro-start">Start</button>
        <button class="btn btn-secondary" id="pomodoro-pause" disabled>Pause</button>
        <button class="btn btn-danger" id="pomodoro-reset">Reset</button>
      </div>
      <div class="pomodoro-settings">
        <button class="btn btn-sm btn-outline-secondary" id="pomodoro-settings-toggle">
          <i class="fas fa-cog"></i> Settings
        </button>
        <div class="pomodoro-settings-panel" id="pomodoro-settings-panel" style="display: none;">
          <div class="mb-2">
            <label>Work Time (minutes)</label>
            <input type="number" id="pomodoro-work-time" class="form-control" value="${Math.floor(this.workTime / 60)}" min="1" max="60">
          </div>
          <div class="mb-2">
            <label>Short Break (minutes)</label>
            <input type="number" id="pomodoro-short-break" class="form-control" value="${Math.floor(this.shortBreakTime / 60)}" min="1" max="30">
          </div>
          <div class="mb-2">
            <label>Long Break (minutes)</label>
            <input type="number" id="pomodoro-long-break" class="form-control" value="${Math.floor(this.longBreakTime / 60)}" min="1" max="60">
          </div>
          <div class="mb-2">
            <label>Cycles before long break</label>
            <input type="number" id="pomodoro-cycles" class="form-control" value="${this.cycles}" min="1" max="10">
          </div>
          <button class="btn btn-primary" id="pomodoro-save-settings">Save</button>
        </div>
      </div>
    `;
    
    // Añadir al body o a un contenedor específico
    document.body.appendChild(this.container);
    
    // Configurar eventos
    this.setupEventListeners();
    
    // Actualizar la UI con el estado actual
    this.updateDisplay();
    this.updateCycleIndicators();
    
    // Actualizar los botones según el estado
    document.getElementById('pomodoro-start').disabled = this.isRunning && !this.isPaused;
    document.getElementById('pomodoro-pause').disabled = !this.isRunning || this.isPaused;
  }
  
  setupEventListeners() {
    // Botones de control
    document.getElementById('pomodoro-start').addEventListener('click', () => this.start());
    document.getElementById('pomodoro-pause').addEventListener('click', () => this.pause());
    document.getElementById('pomodoro-reset').addEventListener('click', () => this.reset());
    
    // Configuración
    document.getElementById('pomodoro-settings-toggle').addEventListener('click', () => {
      const panel = document.getElementById('pomodoro-settings-panel');
      panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
    });
    
    document.getElementById('pomodoro-save-settings').addEventListener('click', () => {
      const workTime = parseInt(document.getElementById('pomodoro-work-time').value, 10) * 60;
      const shortBreakTime = parseInt(document.getElementById('pomodoro-short-break').value, 10) * 60;
      const longBreakTime = parseInt(document.getElementById('pomodoro-long-break').value, 10) * 60;
      const cycles = parseInt(document.getElementById('pomodoro-cycles').value, 10);
      
      this.updateSettings({
        workTime,
        shortBreakTime,
        longBreakTime,
        cycles
      });
      
      document.getElementById('pomodoro-settings-panel').style.display = 'none';
    });
    
    // Guardado automático cuando el navegador se va a cerrar
    window.addEventListener('beforeunload', () => {
      this.saveStateToStorage();
    });
  }
  
  toggleExpandedMode() {
    if (this.container.classList.contains('pomodoro-expanded')) {
      // Contraer
      this.container.classList.remove('pomodoro-expanded');
      document.getElementById('pomodoro-expand').innerHTML = '<i class="fas fa-expand"></i>';
      
      // Eliminar el backdrop
      const backdrop = document.querySelector('.pomodoro-backdrop');
      if (backdrop) backdrop.remove();
    } else {
      // Expandir
      this.container.classList.add('pomodoro-expanded');
      document.getElementById('pomodoro-expand').innerHTML = '<i class="fas fa-compress"></i>';
      
      // Añadir backdrop
      const backdrop = document.createElement('div');
      backdrop.className = 'pomodoro-backdrop';
      backdrop.addEventListener('click', () => this.toggleExpandedMode());
      document.body.appendChild(backdrop);
    }
  }
  
  updateSettings(options) {
    this.workTime = options.workTime || this.workTime;
    this.shortBreakTime = options.shortBreakTime || this.shortBreakTime;
    this.longBreakTime = options.longBreakTime || this.longBreakTime;
    this.cycles = options.cycles || this.cycles;
    
    // Si el temporizador no está corriendo, actualizar el tiempo mostrado
    if (!this.isRunning) {
      this.timeLeft = this.workTime;
      this.updateDisplay();
    }
    
    // Actualizar los indicadores de ciclo
    this.updateCycleIndicators();
    
    // Guardar configuración en localStorage
    this.saveStateToStorage();
  }
  
  updateCycleIndicators() {
    // Eliminar indicadores existentes
    const cyclesContainer = document.getElementById('pomodoro-cycles');
    cyclesContainer.innerHTML = '';
    
    // Crear nuevos indicadores basados en el número de ciclos
    for (let i = 1; i <= this.cycles; i++) {
      const indicator = document.createElement('span');
      indicator.className = 'cycle-indicator' + (i === this.currentCycle ? ' active' : '');
      cyclesContainer.appendChild(indicator);
    }
  }
  
  start() {
    if (this.isRunning && !this.isPaused) return;
    
    if (this.isPaused) {
      this.isPaused = false;
    } else {
      // Si no está pausado, iniciamos un nuevo temporizador
      this.timeLeft = this.mode === 'work' ? this.workTime : 
                      this.mode === 'shortBreak' ? this.shortBreakTime : 
                      this.longBreakTime;
    }
    
    this.isRunning = true;
    this.onStateChange(this.mode, true);
    
    // Guardar estado en localStorage
    this.saveStateToStorage();
    
    // Actualizar UI
    document.getElementById('pomodoro-start').disabled = true;
    document.getElementById('pomodoro-pause').disabled = false;
    
    // Iniciar el temporizador
    this.timer = setInterval(() => this.tick(), 1000);
  }
  
  pause() {
    if (!this.isRunning) return;
    
    clearInterval(this.timer);
    this.isPaused = true;
    this.isRunning = false;
    this.onStateChange(this.mode, false);
    
    // Guardar estado en localStorage
    this.saveStateToStorage();
    
    // Actualizar UI
    document.getElementById('pomodoro-start').disabled = false;
    document.getElementById('pomodoro-pause').disabled = true;
    document.getElementById('pomodoro-status').textContent = 'Paused';
  }
  
  reset() {
    clearInterval(this.timer);
    this.isPaused = false;
    this.isRunning = false;
    this.currentCycle = 1;
    this.mode = 'work';
    this.timeLeft = this.workTime;
    this.onStateChange('reset', false);
    
    // Guardar estado en localStorage
    this.saveStateToStorage();
    
    // Actualizar UI
    document.getElementById('pomodoro-start').disabled = false;
    document.getElementById('pomodoro-pause').disabled = true;
    document.getElementById('pomodoro-status').textContent = 'Ready';
    this.updateDisplay();
    this.updateCycleIndicators();
  }
  
  tick() {
    this.timeLeft--;
    this.updateDisplay();
    this.onTick(this.timeLeft, this.mode);
    
    // Guardar el estado periódicamente (cada 15 segundos para no sobrecargar)
    if (this.timeLeft % 15 === 0) {
      this.saveStateToStorage();
    }
    
    // Añadir clase de advertencia cuando queden menos de 30 segundos
    if (this.timeLeft <= 30) {
      document.getElementById('pomodoro-time').classList.add('warning');
    } else {
      document.getElementById('pomodoro-time').classList.remove('warning');
    }
    
    if (this.timeLeft <= 0) {
      this.completeCurrentCycle();
    }
  }
  
  completeCurrentCycle() {
    clearInterval(this.timer);
    
    // Reproducir sonido de notificación
    this.playNotificationSound();
    
    // Cambiar al siguiente modo
    if (this.mode === 'work') {
      // Si completamos todos los ciclos, descanso largo
      if (this.currentCycle >= this.cycles) {
        this.mode = 'longBreak';
        this.timeLeft = this.longBreakTime;
        this.currentCycle = 1;
      } else {
        this.mode = 'shortBreak';
        this.timeLeft = this.shortBreakTime;
        this.currentCycle++;
      }
    } else {
      // Después de cualquier descanso, volver al trabajo
      this.mode = 'work';
      this.timeLeft = this.workTime;
    }
    
    // Quitar clase de advertencia
    document.getElementById('pomodoro-time').classList.remove('warning');
    
    this.updateCycleIndicators();
    this.onComplete(this.mode);
    
    // Guardar estado en localStorage
    this.saveStateToStorage();
    
    // Actualizar UI
    document.getElementById('pomodoro-status').textContent = 
      this.mode === 'work' ? 'Working' : 
      this.mode === 'shortBreak' ? 'Short Break' : 'Long Break';
    
    // Auto-iniciar el siguiente ciclo
    setTimeout(() => {
      this.isRunning = false;
      this.isPaused = true;
      this.start();
    }, 1000);
    
    // Mostrar notificación
    this.showNotification();
  }
  
  updateDisplay() {
    const minutes = Math.floor(this.timeLeft / 60);
    const seconds = this.timeLeft % 60;
    document.getElementById('pomodoro-time').textContent = 
      `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    
    // Actualizar también el estado del temporizador y la clase CSS
    document.getElementById('pomodoro-status').textContent = 
      this.isRunning ? 
        (this.mode === 'work' ? 'Working' : 
        this.mode === 'shortBreak' ? 'Short Break' : 'Long Break') : 
        (this.isPaused ? 'Paused' : 'Ready');
    
    let expanded = this.container.classList.contains('pomodoro-expanded');
    // Actualizar la clase del container según el modo
    this.container.className = `pomodoro-container pomodoro-mode-${this.mode} ${expanded ? 'pomodoro-expanded' : ''}`;
  }
  
  playNotificationSound() {
    // Crear un elemento de audio y reproducirlo
    const audio = new Audio('/assets/sounds/notification.mp3');
    audio.play().catch(e => console.log('Error playing notification sound', e));
  }
  
  showNotification() {
    // Comprobar si las notificaciones están disponibles
    if (!('Notification' in window)) {
      console.log('Este navegador no soporta notificaciones de escritorio');
      return;
    }
    
    // Comprobar si ya tenemos permiso
    if (Notification.permission === 'granted') {
      this.createNotification();
    } 
    // Si no hemos pedido permiso antes
    else if (Notification.permission !== 'denied') {
      Notification.requestPermission().then(permission => {
        if (permission === 'granted') {
          this.createNotification();
        }
      });
    }
  }
  
  createNotification() {
    const title = this.mode === 'work' ? 'Time to work!' : 'Time for a break!';
    const options = {
      body: this.mode === 'work' ? 'Focus on your task now.' : 
            this.mode === 'shortBreak' ? 'Take a short break!' : 'Take a long break!',
      icon: '/assets/img/favicon.ico'
    };
    
    const notification = new Notification(title, options);
    
    // Auto-cerrar después de 5 segundos
    setTimeout(() => notification.close(), 5000);
  }
  
  // Método para añadir el Pomodoro a un contenedor específico
  attachTo(containerId) {
    const container = document.getElementById(containerId);
    if (container) {
      // Remover del DOM actual si ya está añadido
      if (this.container.parentNode) {
        this.container.parentNode.removeChild(this.container);
      }
      
      container.appendChild(this.container);
    }
  }
}