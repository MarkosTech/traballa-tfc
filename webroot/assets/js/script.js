/**
 * Traballa Counter - Main JavaScript
 * 
 * @copyright 2025 Marcos Núñez Fernández
 * @license   MIT License
 * 
 */

// Document ready function
document.addEventListener("DOMContentLoaded", () => {
  // Initialize tooltips
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  var tooltipList = tooltipTriggerList.map((tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl))

  // Initialize popovers
  var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
  var popoverList = popoverTriggerList.map((popoverTriggerEl) => new bootstrap.Popover(popoverTriggerEl))

  // Auto-hide alerts after 5 seconds
  setTimeout(() => {
    var alerts = document.querySelectorAll(".alert:not(.alert-permanent)")
    alerts.forEach((alert) => {
      var bsAlert = bootstrap.Alert.getOrCreateInstance(alert)
      bsAlert.close()
    })
  }, 5000)
})

/**
 * Display a toast notification
 * @param {string} type - Type of toast (success, error, warning, info)
 * @param {string} title - Toast title
 * @param {string} message - Toast message content
 * @param {number} delay - Auto-hide delay in milliseconds (default: 3000ms, set to 0 to disable auto-hide)
 */
function showToast(type, title, message, delay = 3000) {
  // Check if we have Bootstrap toast functionality
  if (typeof bootstrap !== 'undefined' && bootstrap.Toast) {
    // Create toast element
    const toastEl = document.createElement('div');
    toastEl.className = `toast align-items-center text-white border-0 position-fixed bottom-0 start-50 translate-middle-x m-3`;
    
    // Set background color based on type
    switch (type) {
      case 'success':
        toastEl.classList.add('bg-success');
        break;
      case 'error':
        toastEl.classList.add('bg-danger');
        break;
      case 'warning':
        toastEl.classList.add('bg-warning', 'text-dark');
        break;
      case 'info':
        toastEl.classList.add('bg-info', 'text-dark');
        break;
      default:
        toastEl.classList.add('bg-primary');
    }
    
    toastEl.setAttribute('role', 'alert');
    toastEl.setAttribute('aria-live', 'assertive');
    toastEl.setAttribute('aria-atomic', 'true');
    toastEl.style.zIndex = '1090';
    
    toastEl.innerHTML = `
      <div class="d-flex">
        <div class="toast-body">
          <strong>${title}:</strong> ${message}
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    `;
    
    document.body.appendChild(toastEl);
    
    const toastOptions = {
      autohide: delay > 0,
      delay: delay
    };
    
    const toast = new bootstrap.Toast(toastEl, toastOptions);
    toast.show();
    
    // Remove toast after it's hidden
    toastEl.addEventListener('hidden.bs.toast', () => {
      document.body.removeChild(toastEl);
    });
  } else {
    // Fallback to alert if Bootstrap toast is not available
    alert(`${title}: ${message}`);
  }
}

/**
 * Format time in hours and minutes
 * @param {number} hours - Hours to format
 * @returns {string} - Formatted time string
 */
function formatHours(hours) {
  if (isNaN(hours) || hours === null) {
    return "0h 0m"
  }

  const wholeHours = Math.floor(hours)
  const minutes = Math.round((hours - wholeHours) * 60)

  if (minutes === 60) {
    return wholeHours + 1 + "h 0m"
  }

  return wholeHours + "h " + minutes + "m"
}

/**
 * Format date based on user preference
 * @param {string} dateString - Date string to format
 * @param {string} format - Format preference (mdy, dmy, ymd)
 * @returns {string} - Formatted date string
 */
function formatDate(dateString, format = "mdy") {
  const date = new Date(dateString)
  const day = date.getDate().toString().padStart(2, "0")
  const month = (date.getMonth() + 1).toString().padStart(2, "0")
  const year = date.getFullYear()

  switch (format) {
    case "dmy":
      return `${day}/${month}/${year}`
    case "ymd":
      return `${year}/${month}/${day}`
    case "mdy":
    default:
      return `${month}/${day}/${year}`
  }
}

/**
 * Format time based on user preference
 * @param {string} timeString - Time string to format
 * @param {string} format - Format preference (12 or 24)
 * @returns {string} - Formatted time string
 */
function formatTime(timeString, format = "12") {
  const date = new Date(timeString)
  const hours = date.getHours()
  const minutes = date.getMinutes().toString().padStart(2, "0")

  if (format === "24") {
    return `${hours.toString().padStart(2, "0")}:${minutes}`
  } else {
    const period = hours >= 12 ? "PM" : "AM"
    const displayHours = hours % 12 || 12
    return `${displayHours}:${minutes} ${period}`
  }
}

/**
 * Calculate the difference between two dates in days
 * @param {Date} date1 - First date
 * @param {Date} date2 - Second date
 * @returns {number} - Difference in days
 */
function dateDiffInDays(date1, date2) {
  const _MS_PER_DAY = 1000 * 60 * 60 * 24
  const utc1 = Date.UTC(date1.getFullYear(), date1.getMonth(), date1.getDate())
  const utc2 = Date.UTC(date2.getFullYear(), date2.getMonth(), date2.getDate())

  return Math.floor((utc2 - utc1) / _MS_PER_DAY)
}

/**
 * Generate random colors for charts
 * @param {number} count - Number of colors to generate
 * @returns {Array} - Array of color strings
 */
function generateChartColors(count) {
  const colors = []
  const baseColors = [
    "rgba(255, 99, 132, {opacity})",
    "rgba(54, 162, 235, {opacity})",
    "rgba(255, 206, 86, {opacity})",
    "rgba(75, 192, 192, {opacity})",
    "rgba(153, 102, 255, {opacity})",
    "rgba(255, 159, 64, {opacity})",
    "rgba(199, 199, 199, {opacity})",
  ]

  for (let i = 0; i < count; i++) {
    const colorIndex = i % baseColors.length
    const backgroundColor = baseColors[colorIndex].replace("{opacity}", "0.5")
    const borderColor = baseColors[colorIndex].replace("{opacity}", "1")

    colors.push({
      backgroundColor,
      borderColor,
    })
  }

  return colors
}

