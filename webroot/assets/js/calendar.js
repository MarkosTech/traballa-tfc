/**
 * Calendar JavaScript functionality
 * Handles calendar events, modals, and user interactions
 */

document.addEventListener('DOMContentLoaded', function() {
    // Custom modal handling (without requiring Bootstrap)
    const eventModalEl = document.getElementById('eventModal');
    const eventDetailsModalEl = document.getElementById('eventDetailsModal');
    
    // Custom modal implementation
    const eventModal = {
        show: function() {
            eventModalEl.classList.add('show');
            eventModalEl.style.display = 'block';
        },
        hide: function() {
            eventModalEl.classList.remove('show');
            eventModalEl.style.display = 'none';
        }
    };
    
    const eventDetailsModal = {
        show: function() {
            eventDetailsModalEl.classList.add('show');
            eventDetailsModalEl.style.display = 'block';
        },
        hide: function() {
            eventDetailsModalEl.classList.remove('show');
            eventDetailsModalEl.style.display = 'none';
        }
    };
    
    // Close modal buttons
    document.querySelectorAll('.modal-close-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            eventModal.hide();
            eventDetailsModal.hide();
        });
    });
    
    // Close modal when clicking on X
    document.querySelectorAll('.btn-close').forEach(btn => {
        btn.addEventListener('click', function() {
            eventModal.hide();
            eventDetailsModal.hide();
        });
    });
    
    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target === eventModalEl) {
            eventModal.hide();
        }
        if (event.target === eventDetailsModalEl) {
            eventDetailsModal.hide();
        }
    });
    
    const eventForm = document.getElementById('event-form');
    const eventTypeSelect = document.getElementById('event_type');
    const projectField = document.getElementById('projectField');
    const organizationField = document.getElementById('organizationField');
    const deleteEventBtn = document.getElementById('deleteEventBtn');
    const editEventBtn = document.getElementById('editEventBtn');
    
    let currentEventId = null;
    
    // Handle event type change
    eventTypeSelect.addEventListener('change', function() {
        const type = this.value;
        
        // Hide both fields first
        projectField.style.display = 'none';
        organizationField.style.display = 'none';
        
        // Reset selections
        document.getElementById('project_id').value = '';
        document.getElementById('organization_id').value = '';
        
        // Then show the appropriate field
        if (type === 'project') {
            projectField.style.display = 'block';
        } else if (type === 'organization') {
            organizationField.style.display = 'block';
        }
    });
    
    // Handle calendar day clicks
    document.querySelectorAll('.calendar-day').forEach(day => {
        day.addEventListener('click', function(e) {
            // If clicking on an event, don't trigger the day click
            if (e.target.closest('.event-item')) {
                return;
            }
            
            const date = this.getAttribute('data-date');
            if (date) {
                document.getElementById('start_date').value = date + 'T00:00';
                document.getElementById('end_date').value = date + 'T01:00';
                resetForm();
                eventModal.show();
            }
        });
    });
    
    // Handle event form submission
    document.getElementById('saveEventBtn').addEventListener('click', function() {
        const eventType = document.getElementById('event_type').value;
        
        // Make sure required fields are set based on event type
        if (eventType === 'project' && !document.getElementById('project_id').value) {
            alert('Please select a project for this event.');
            return;
        }
        
        if (eventType === 'organization' && !document.getElementById('organization_id').value) {
            alert('Please select an organization for this event.');
            return;
        }
        
        // Submit form via AJAX instead of regular form submission
        const formData = new FormData(document.getElementById('event-form'));
        
        fetch('/ajax/calendar-api.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                eventModal.hide();
                window.location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error saving event:', error);
            alert('Error saving event. Please try again.');
        });
    });
    
    // Handle event clicks
    document.querySelectorAll('.event-item').forEach(item => {
        item.addEventListener('click', function(e) {
            e.stopPropagation();
            const eventId = this.getAttribute('data-event-id');
            currentEventId = eventId;
            
            showEventDetails(eventId);
        });
    });
    
    // Handle edit button click
    editEventBtn.addEventListener('click', function() {
        if (currentEventId) {
            eventDetailsModal.hide();
            loadEventForEditing(currentEventId);
        }
    });
    
    // Handle delete button click
    deleteEventBtn.addEventListener('click', function() {
        if (currentEventId && confirm('Are you sure you want to delete this event?')) {
            deleteEvent(currentEventId);
        }
    });
    
    // Add Event button handler
    document.getElementById('addEventBtn').addEventListener('click', function() {
        // Set current date and time
        const now = new Date();
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        
        startDateInput.value = formatDateTimeForInput(now);
        
        // Set end date to 1 hour later by default
        const endDate = new Date(now);
        endDate.setHours(endDate.getHours() + 1);
        endDateInput.value = formatDateTimeForInput(endDate);
        
        // Reset form to add mode
        resetForm();
        
        // Show the modal
        eventModal.show();
    });
    
    // Helper function to reset form
    function resetForm() {
        document.getElementById('modalTitle').textContent = 'Add Event';
        document.getElementById('form-action').value = 'add_event';
        document.getElementById('event-id').value = '';
        document.getElementById('title').value = '';
        document.getElementById('description').value = '';
        document.getElementById('event_type').value = 'personal';
        document.getElementById('project_id').value = '';
        document.getElementById('organization_id').value = '';
        
        // Hide project and organization fields initially
        projectField.style.display = 'none';
        organizationField.style.display = 'none';
    }
    
    // Helper function to display event details
    function showEventDetails(eventId) {
        // Debug log
        console.log("Fetching event details for ID:", eventId);
        
        // Use the new AJAX endpoint
        fetch(`/ajax/calendar-api.php?action=get_event&event_id=${eventId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Server returned ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(event => {
            console.log("Event data received:", event);
            
            document.getElementById('detailsModalTitle').textContent = event.title;
            document.getElementById('detailsModalDescription').textContent = event.description || 'No description';
            
            const startDate = new Date(event.start_date);
            const endDate = event.end_date ? new Date(event.end_date) : null;
            
            document.getElementById('detailsModalStartDate').textContent = formatDateTime(startDate);
            document.getElementById('detailsModalEndDate').textContent = endDate ? formatDateTime(endDate) : 'Not specified';
            
            let eventType = 'Personal';
            if (event.event_type === 'project') {
                eventType = 'Project: ' + (event.project_name || 'Unknown');
            } else if (event.event_type === 'organization') {
                eventType = 'Organization: ' + (event.organization_name || 'Unknown');
            }
            
            document.getElementById('detailsModalEventType').textContent = eventType;
            currentEventId = event.id;
            
            // Show the details modal
            eventDetailsModal.show();
        })
        .catch(error => {
            console.error('Error fetching event details:', error);
            alert('Error loading event data. Please try again. Details: ' + error.message);
        });
    }
    
    // Delete event
    function deleteEvent(eventId) {
        fetch('/ajax/calendar-api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=delete_event&event_id=${eventId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                eventDetailsModal.hide();
                window.location.reload();
            } else {
                throw new Error(data.message || 'Failed to delete event');
            }
        })
        .catch(error => {
            console.error('Error deleting event:', error);
            alert('Error deleting event: ' + error.message);
        });
    }
    
    // Format date for display
    function formatDateTime(date) {
        return date.toLocaleString();
    }
    
    // Format date for input
    function formatDateTimeForInput(date) {
        const year = date.getFullYear();
        const month = (date.getMonth() + 1).toString().padStart(2, '0');
        const day = date.getDate().toString().padStart(2, '0');
        const hours = date.getHours().toString().padStart(2, '0');
        const minutes = date.getMinutes().toString().padStart(2, '0');
        
        return `${year}-${month}-${day}T${hours}:${minutes}`;
    }
    
    // Load event data for editing
    function loadEventForEditing(eventId) {
        console.log("Loading event for editing, ID:", eventId);
        
        // Use the new AJAX endpoint
        fetch(`/ajax/calendar-api.php?action=get_event&event_id=${eventId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Server returned ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(event => {
            console.log("Event data for editing:", event);
            
            // Set form data
            document.getElementById('modalTitle').textContent = 'Edit Event';
            document.getElementById('form-action').value = 'update_event';
            document.getElementById('event-id').value = event.id;
            document.getElementById('title').value = event.title;
            document.getElementById('description').value = event.description || '';
            document.getElementById('event_type').value = event.event_type;
            
            // Set project or organization
            if (event.event_type === 'project' && event.project_id) {
                document.getElementById('project_id').value = event.project_id;
                projectField.style.display = 'block';
                organizationField.style.display = 'none';
            } else if (event.event_type === 'organization' && event.organization_id) {
                document.getElementById('organization_id').value = event.organization_id;
                organizationField.style.display = 'block';
                projectField.style.display = 'none';
            } else {
                // For personal events
                projectField.style.display = 'none';
                organizationField.style.display = 'none';
            }
            
            // Set dates
            const startDate = new Date(event.start_date);
            const endDate = event.end_date ? new Date(event.end_date) : null;
            
            document.getElementById('start_date').value = formatDateTimeForInput(startDate);
            if (endDate) {
                document.getElementById('end_date').value = formatDateTimeForInput(endDate);
            } else {
                document.getElementById('end_date').value = '';
            }
            
            // Show the edit modal
            eventModal.show();
        })
        .catch(error => {
            console.error('Error loading event for editing:', error);
            alert('Error loading event data. Please try again.');
        });
    }
});
