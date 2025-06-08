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
    
    // Elementos del DOM usando jQuery
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
    // Crear contenedor principal usando jQuery
    this.$container = $('<div>', {
      id: 'pomodoro-container',
      class: 'pomodoro-container'
    });
    
    // Crear el HTML para el temporizador
    this.$container.html(`
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
    `);
    
    // Añadir al body o a un contenedor específico usando jQuery
    $('body').append(this.$container);
    
    // Configurar eventos
    this.setupEventListeners();
    
    // Actualizar la UI con el estado actual
    this.updateDisplay();
    this.updateCycleIndicators();
    
    // Actualizar los botones según el estado
    $('#pomodoro-start').prop('disabled', this.isRunning && !this.isPaused);
    $('#pomodoro-pause').prop('disabled', !this.isRunning || this.isPaused);
  }
  
  setupEventListeners() {
    // Botones de control usando jQuery
    $('#pomodoro-start').on('click', () => this.start());
    $('#pomodoro-pause').on('click', () => this.pause());
    $('#pomodoro-reset').on('click', () => this.reset());
    
    // Configuración usando jQuery
    $('#pomodoro-settings-toggle').on('click', () => {
      $('#pomodoro-settings-panel').toggle();
    });
    
    $('#pomodoro-save-settings').on('click', () => {
      const workTime = parseInt($('#pomodoro-work-time').val(), 10) * 60;
      const shortBreakTime = parseInt($('#pomodoro-short-break').val(), 10) * 60;
      const longBreakTime = parseInt($('#pomodoro-long-break').val(), 10) * 60;
      const cycles = parseInt($('#pomodoro-cycles').val(), 10);
      
      this.updateSettings({
        workTime,
        shortBreakTime,
        longBreakTime,
        cycles
      });
      
      $('#pomodoro-settings-panel').hide();
    });
    
    // Guardado automático cuando el navegador se va a cerrar usando jQuery
    $(window).on('beforeunload', () => {
      this.saveStateToStorage();
    });
  }
  
  toggleExpandedMode() {
    if (this.$container.hasClass('pomodoro-expanded')) {
      // Contraer usando jQuery
      this.$container.removeClass('pomodoro-expanded');
      $('#pomodoro-expand').html('<i class="fas fa-expand"></i>');
      
      // Eliminar el backdrop usando jQuery
      $('.pomodoro-backdrop').remove();
    } else {
      // Expandir usando jQuery
      this.$container.addClass('pomodoro-expanded');
      $('#pomodoro-expand').html('<i class="fas fa-compress"></i>');
      
      // Añadir backdrop usando jQuery
      const $backdrop = $('<div>', {
        class: 'pomodoro-backdrop'
      });
      $backdrop.on('click', () => this.toggleExpandedMode());
      $('body').append($backdrop);
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
    // Eliminar indicadores existentes usando jQuery
    const $cyclesContainer = $('#pomodoro-cycles');
    $cyclesContainer.empty();
    
    // Crear nuevos indicadores basados en el número de ciclos usando jQuery
    for (let i = 1; i <= this.cycles; i++) {
      const $indicator = $('<span>', {
        class: 'cycle-indicator' + (i === this.currentCycle ? ' active' : '')
      });
      $cyclesContainer.append($indicator);
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
    
    // Actualizar UI usando jQuery
    $('#pomodoro-start').prop('disabled', true);
    $('#pomodoro-pause').prop('disabled', false);
    
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
    
    // Actualizar UI usando jQuery
    $('#pomodoro-start').prop('disabled', false);
    $('#pomodoro-pause').prop('disabled', true);
    $('#pomodoro-status').text('Paused');
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
    
    // Actualizar UI usando jQuery
    $('#pomodoro-start').prop('disabled', false);
    $('#pomodoro-pause').prop('disabled', true);
    $('#pomodoro-status').text('Ready');
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
    
    // Añadir clase de advertencia cuando queden menos de 30 segundos usando jQuery
    if (this.timeLeft <= 30) {
      $('#pomodoro-time').addClass('warning');
    } else {
      $('#pomodoro-time').removeClass('warning');
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
    
    // Quitar clase de advertencia usando jQuery
    $('#pomodoro-time').removeClass('warning');
    
    this.updateCycleIndicators();
    this.onComplete(this.mode);
    
    // Guardar estado en localStorage
    this.saveStateToStorage();
    
    // Actualizar UI usando jQuery
    $('#pomodoro-status').text(
      this.mode === 'work' ? 'Working' : 
      this.mode === 'shortBreak' ? 'Short Break' : 'Long Break'
    );
    
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
    $('#pomodoro-time').text(
      `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`
    );
    
    // Actualizar también el estado del temporizador y la clase CSS usando jQuery
    $('#pomodoro-status').text(
      this.isRunning ? 
        (this.mode === 'work' ? 'Working' : 
        this.mode === 'shortBreak' ? 'Short Break' : 'Long Break') : 
        (this.isPaused ? 'Paused' : 'Ready')
    );
    
    let expanded = this.$container.hasClass('pomodoro-expanded');
    // Actualizar la clase del container según el modo usando jQuery
    this.$container.attr('class', `pomodoro-container pomodoro-mode-${this.mode} ${expanded ? 'pomodoro-expanded' : ''}`);
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
  
  // Método para añadir el Pomodoro a un contenedor específico usando jQuery
  attachTo(containerId) {
    const $container = $('#' + containerId);
    if ($container.length) {
      // Remover del DOM actual si ya está añadido
      if (this.$container.parent().length) {
        this.$container.detach();
      }
      
      $container.append(this.$container);
    }
  }
}