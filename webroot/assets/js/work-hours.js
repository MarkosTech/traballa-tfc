// Work hours page JavaScript functionality

// Break functionality variables
let breakTimer;
let breakStartTime;

// Function to export table to CSV
function exportTableToCSV(filename) {
    const csv = [];
    const rows = document.querySelectorAll('#workHoursTable tr');
    
    for (let i = 0; i < rows.length; i++) {
        const row = [], cols = rows[i].querySelectorAll('td, th');
        
        for (let j = 0; j < cols.length; j++) {
            // Skip the actions column
            if (j === 7) continue;
            // Get the text content and replace any commas to avoid CSV issues
            let data = cols[j].textContent.replace(/,/g, ';');
            // Remove any line breaks
            data = data.replace(/(\r\n|\n|\r)/gm, '');
            // Add the data to the row
            row.push('"' + data + '"');
        }
        
        csv.push(row.join(','));
    }
    
    // Download CSV file
    downloadCSV(csv.join('\n'), filename);
}

function downloadCSV(csv, filename) {
    const csvFile = new Blob([csv], {type: 'text/csv'});
    const downloadLink = document.createElement('a');
    
    // File name
    downloadLink.download = filename;
    
    // Create a link to the file
    downloadLink.href = window.URL.createObjectURL(csvFile);
    
    // Hide download link
    downloadLink.style.display = 'none';
    
    // Add the link to DOM
    document.body.appendChild(downloadLink);
    
    // Click download link
    downloadLink.click();
    
    // Remove link from DOM
    document.body.removeChild(downloadLink);
}

// Edit hours modal
document.querySelectorAll('.edit-hours-btn').forEach(button => {
    button.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        const clockIn = this.getAttribute('data-clock-in');
        const clockOut = this.getAttribute('data-clock-out');
        const notes = this.getAttribute('data-notes');
        
        // Format datetime for datetime-local input
        const formatDatetimeLocal = (datetime) => {
            return datetime.replace(' ', 'T').substring(0, 16);
        };
        
        document.getElementById('edit_work_id').value = id;
        document.getElementById('edit_clock_in').value = formatDatetimeLocal(clockIn);
        document.getElementById('edit_clock_out').value = formatDatetimeLocal(clockOut);
        document.getElementById('edit_notes').value = notes;
    });
});

// Delete hours modal
document.querySelectorAll('.delete-hours-btn').forEach(button => {
    button.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        const date = this.getAttribute('data-date');
        const project = this.getAttribute('data-project');
        
        document.getElementById('delete_work_id').value = id;
        document.getElementById('delete_date').textContent = date;
        document.getElementById('delete_project').textContent = project;
    });
});

// Break functionality
document.querySelectorAll('.break-btn').forEach(button => {
    button.addEventListener('click', function() {
        const workHourId = this.getAttribute('data-id');
        // Store the work hour ID for later use
        document.getElementById('break_work_hour_id').value = workHourId;
        // Load active break and break history
        loadBreakData(workHourId);
    });
});

function loadBreakData(workHourId) {
    // Clear previous data
    document.getElementById('breakHistoryBody').innerHTML = '';
    document.getElementById('activeBreakSection').classList.add('d-none');
    
    // Fetch break data
    fetch(`ajax/manage-breaks.php?action=get_breaks&work_hour_id=${workHourId}`)
        .then(response => response.json())
        .then(data => {
            // Check if there's an active break
            if (data.active_break) {
                showActiveBreak(data.active_break);
            }
            
            // Show break history
            if (data.break_history && data.break_history.length > 0) {
                showBreakHistory(data.break_history);
            }
        })
        .catch(error => {
            console.error('Error loading break data:', error);
        });
}

function showActiveBreak(activeBreak) {
    const activeBreakSection = document.getElementById('activeBreakSection');
    activeBreakSection.classList.remove('d-none');
    
    // Update active break info
    document.getElementById('active_break_type').textContent = capitalizeFirstLetter(activeBreak.type);
    document.getElementById('active_break_start').textContent = formatDateTime(activeBreak.start_time);
    
    // Set up break timer
    breakStartTime = new Date(activeBreak.start_time).getTime();
    startBreakTimer();
    
    // Set up end break button
    document.getElementById('endBreakBtn').onclick = function() {
        endBreak(activeBreak.id);
    };
}

function showBreakHistory(breakHistory) {
    const historyBody = document.getElementById('breakHistoryBody');
    
    breakHistory.forEach(breakItem => {
        const row = document.createElement('tr');
        
        const typeCell = document.createElement('td');
        typeCell.textContent = capitalizeFirstLetter(breakItem.type);
        
        const startCell = document.createElement('td');
        startCell.textContent = formatTime(breakItem.start_time);
        
        const endCell = document.createElement('td');
        endCell.textContent = breakItem.end_time ? formatTime(breakItem.end_time) : '-';
        
        const durationCell = document.createElement('td');
        durationCell.textContent = breakItem.duration ? formatDuration(breakItem.duration * 3600) : '-';
        
        row.appendChild(typeCell);
        row.appendChild(startCell);
        row.appendChild(endCell);
        row.appendChild(durationCell);
        
        historyBody.appendChild(row);
    });
}

function startBreakTimer() {
    // Clear any existing timer
    if (breakTimer) {
        clearInterval(breakTimer);
    }
    
    // Update timer every second
    breakTimer = setInterval(function() {
        const now = new Date().getTime();
        const diff = now - breakStartTime;
        
        document.getElementById('active_break_duration').textContent = formatDuration(diff);
    }, 1000);
}

function endBreak(breakId) {
    fetch('ajax/manage-breaks.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=end_break&break_id=${breakId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Stop timer
            clearInterval(breakTimer);
            
            // Reload break data
            loadBreakData(document.getElementById('break_work_hour_id').value);
            
            // Show the break form again
            document.getElementById('breakForm').classList.remove('d-none');
        } else {
            alert('Error ending break: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error ending break:', error);
    });
}

// Handle break form submission
document.addEventListener('DOMContentLoaded', function() {
    const breakForm = document.getElementById('breakForm');
    if (breakForm) {
        breakForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('ajax/manage-breaks.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload break data
                    loadBreakData(document.getElementById('break_work_hour_id').value);
                } else {
                    alert('Error starting break: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error starting break:', error);
            });
        });
    }
});

// Helper functions
function formatDateTime(datetime) {
    const date = new Date(datetime);
    return date.toLocaleString();
}

function formatTime(datetime) {
    const date = new Date(datetime);
    return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
}

function formatDuration(milliseconds) {
    const seconds = Math.floor(milliseconds / 1000);
    const hours = Math.floor(seconds / 3600);
    const minutes = Math.floor((seconds % 3600) / 60);
    const remainingSeconds = seconds % 60;
    
    return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(remainingSeconds).padStart(2, '0')}`;
}

function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}
