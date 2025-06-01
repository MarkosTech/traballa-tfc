/**
 * Traballa - Kanban
 * 
 * Handles all kanban-related functionality including:
 * - Task management (add, edit, delete, move)
 * - Column management (add, edit, delete, reorder)
 * - Tab management (add, edit, delete, switch)
 * - Drag and drop functionality
 * - Real-time updates and syncing
 * - Mobile touch support
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
 * 
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize kanban functionality
    initializeKanban();
});

function initializeKanban() {
    // Initialize sortable drag and drop
    initializeSortable();
    
    // Initialize periodic sync for real-time updates
    initializePeriodicSync();
    
    // Initialize mobile touch events
    initializeMobileTouchEvents();
    
    // Attach all event listeners
    attachEventListeners();
    
    // Handle browser back/forward navigation
    handleBrowserNavigation();
}

// Reusable modal cleanup function
function cleanupModal(modalId) {
    const modal = bootstrap.Modal.getInstance(document.getElementById(modalId));
    if (modal) {
        modal.hide();
    }
    
    // Force remove modal backdrop if it persists
    setTimeout(() => {
        const backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) {
            backdrop.remove();
        }
        // Remove modal-open class from body
        document.body.classList.remove('modal-open');
        document.body.style.removeProperty('overflow');
        document.body.style.removeProperty('padding-right');
    }, 100);
}

// Attach all event listeners
function attachEventListeners() {
    // Task management
    attachTaskEventListeners();
    
    // Column management
    attachColumnEventListeners();
    
    // Tab management
    attachTabEventListeners();
    
    // Status management
    attachStatusEventListeners();
}

// Task event listeners
function attachTaskEventListeners() {
    // Add Task Form Submission
    const addTaskForm = document.getElementById('addTaskForm');
    if (addTaskForm) {
        addTaskForm.addEventListener('submit', handleAddTask);
    }
    
    // Add Task Modal buttons
    document.querySelectorAll('.add-task-btn').forEach(button => {
        button.addEventListener('click', function() {
            const columnId = this.closest('.kanban-column').dataset.columnId;
            document.getElementById('add_task_column_id').value = columnId;
            document.getElementById('addTaskForm').reset();
        });
    });
    
    // Edit Task Form Submission
    const editTaskForm = document.getElementById('editTaskForm');
    if (editTaskForm) {
        editTaskForm.addEventListener('submit', handleEditTask);
    }
    
    // Edit Task Modal buttons
    document.querySelectorAll('.edit-task-btn').forEach(button => {
        button.addEventListener('click', function() {
            const taskId = this.dataset.taskId;
            const title = this.dataset.title;
            const description = this.dataset.description;
            const assignedTo = this.dataset.assignedTo;
            const dueDate = this.dataset.dueDate;
            const status = this.dataset.status;
            
            document.getElementById('edit_task_id').value = taskId;
            document.getElementById('edit_title').value = title;
            document.getElementById('edit_description').value = description || '';
            document.getElementById('edit_assigned_to').value = assignedTo || '';
            document.getElementById('edit_due_date').value = dueDate || '';
            document.getElementById('edit_status').value = status || 'active';
        });
    });
    
    // Delete Task Confirmation
    const confirmDeleteTaskBtn = document.getElementById('confirmDeleteTask');
    if (confirmDeleteTaskBtn) {
        confirmDeleteTaskBtn.addEventListener('click', handleDeleteTask);
    }
    
    // Delete Task Modal buttons
    document.querySelectorAll('.delete-task-btn').forEach(button => {
        button.addEventListener('click', function() {
            const taskId = this.dataset.taskId;
            const title = this.dataset.title;
            
            document.getElementById('delete_task_id').value = taskId;
            document.getElementById('delete_task_title').textContent = title;
        });
    });
}

// Column event listeners
function attachColumnEventListeners() {
    // Add Column Form Submission
    const addColumnForm = document.getElementById('addColumnForm');
    if (addColumnForm) {
        addColumnForm.addEventListener('submit', handleAddColumn);
    }
    
    // Edit Column Modal buttons
    document.querySelectorAll('.edit-column-btn').forEach(button => {
        button.addEventListener('click', function() {
            const columnId = this.dataset.columnId;
            const name = this.dataset.name;
            
            document.getElementById('edit_column_id').value = columnId;
            document.getElementById('edit_column_name').value = name;
        });
    });
    
    // Delete Column Modal buttons
    document.querySelectorAll('.delete-column-btn').forEach(button => {
        button.addEventListener('click', function() {
            const columnId = this.dataset.columnId;
            const name = this.dataset.name;
            
            document.getElementById('delete_column_id').value = columnId;
            document.getElementById('delete_column_name').textContent = name;
        });
    });
}

// Tab event listeners
function attachTabEventListeners() {
    // Tab switching
    document.querySelectorAll('.kanban-tabs .nav-link').forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            
            const tabId = this.dataset.tabId;
            const currentTabId = document.querySelector('.kanban-tabs .nav-link.active').dataset.tabId;
            
            // Don't reload if clicking on the same tab
            if (tabId === currentTabId) {
                return;
            }
            
            // Update the active tab visually
            document.querySelectorAll('.kanban-tabs .nav-link').forEach(link => {
                link.classList.remove('active');
            });
            this.classList.add('active');
            
            // Update hidden inputs for forms
            const addTaskTabId = document.getElementById('add_task_tab_id');
            const addColumnTabId = document.getElementById('add_column_tab_id');
            if (addTaskTabId) addTaskTabId.value = tabId;
            if (addColumnTabId) addColumnTabId.value = tabId;
            
            // Load the tab content and update URL
            loadTabContent(tabId);
        });
    });
    
    // Edit Tab Modal buttons
    document.querySelectorAll('.edit-tab-btn').forEach(button => {
        button.addEventListener('click', function() {
            const tabId = this.dataset.tabId;
            const name = this.dataset.name;
            
            document.getElementById('edit_tab_id').value = tabId;
            document.getElementById('edit_tab_name').value = name;
        });
    });
    
    // Delete Tab Modal buttons
    document.querySelectorAll('.delete-tab-btn').forEach(button => {
        button.addEventListener('click', function() {
            const tabId = this.dataset.tabId;
            const name = this.dataset.name;
            
            document.getElementById('delete_tab_id').value = tabId;
            document.getElementById('delete_tab_name').textContent = name;
        });
    });
}

// Status event listeners
function attachStatusEventListeners() {
    // Status update buttons
    document.querySelectorAll('.status-option').forEach(button => {
        button.addEventListener('click', function() {
            const taskId = this.dataset.taskId;
            const newStatus = this.dataset.status;
            
            if (taskId && newStatus) {
                updateTaskStatus(taskId, newStatus);
            }
        });
    });
}

// Task management functions
function handleAddTask(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const columnId = formData.get('column_id');
    
    fetch('/ajax/add-kanban-task.php', {
        method: 'POST',
        body: formData,
        credentials: 'same-origin',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            cleanupModal('addTaskModal');
            addTaskToColumn(data.task, data.assigned_name, columnId);
            showToast('success', 'Success', data.message || 'Task added successfully');
            document.getElementById('addTaskForm').reset();
        } else {
            showToast('error', 'Error', data.error || 'Could not add task');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', 'Error', 'Could not add task. Please try again.');
    });
}

function handleEditTask(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const taskId = formData.get('task_id');
    
    fetch('/ajax/update-kanban-task.php', {
        method: 'POST',
        body: formData,
        credentials: 'same-origin',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            cleanupModal('editTaskModal');
            updateTaskInUI(taskId, formData);
            showToast('success', 'Success', data.message || 'Task updated successfully');
        } else {
            showToast('error', 'Error', data.error || 'Could not update task');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', 'Error', 'Could not update task. Please try again.');
    });
}

function handleDeleteTask() {
    const taskId = document.getElementById('delete_task_id').value;
    
    if (!taskId) {
        showToast('error', 'Error', 'No task selected for deletion');
        return;
    }
    
    const formData = new FormData();
    formData.append('task_id', taskId);
    
    fetch('/ajax/delete-kanban-task.php', {
        method: 'POST',
        body: formData,
        credentials: 'same-origin',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            cleanupModal('deleteTaskModal');
            const task = document.querySelector(`.kanban-task[data-task-id="${taskId}"]`);
            if (task) {
                task.remove();
            }
            showToast('success', 'Success', data.message || 'Task deleted successfully');
        } else {
            showToast('error', 'Error', data.error || 'Could not delete task');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', 'Error', 'Could not delete task. Please try again.');
    });
}

// Column management functions
function handleAddColumn(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    // Validate required fields
    const projectId = formData.get('project_id');
    const tabId = formData.get('tab_id');
    const name = formData.get('name');
    
    if (!projectId || !name) {
        showToast('error', 'Error', 'Missing required information. Please refresh the page and try again.');
        return;
    }
    
    if (!tabId) {
        showToast('error', 'Error', 'No tab selected. Please select a tab first.');
        return;
    }
    
    fetch('/ajax/add-kanban-column.php', {
        method: 'POST',
        body: formData,
        credentials: 'same-origin',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            cleanupModal('addColumnModal');
            window.location.reload();
            showToast('success', 'Success', data.message || 'Column added successfully');
            document.getElementById('addColumnForm').reset();
        } else {
            showToast('error', 'Error', data.error || 'Could not add column');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', 'Error', 'Could not add column. Please try again.');
    });
}

// Status management functions
function updateTaskStatus(taskId, newStatus) {
    const formData = new FormData();
    formData.append('task_id', taskId);
    formData.append('status', newStatus);
    
    fetch('/ajax/update-kanban-task-status.php', {
        method: 'POST',
        body: formData,
        credentials: 'same-origin',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            const task = document.querySelector(`.kanban-task[data-task-id="${taskId}"]`);
            if (task) {
                const columnId = task.closest('.kanban-tasks').dataset.columnId;
                handleTaskStatusChange(taskId, newStatus, columnId);
            }
            showToast('success', 'Success', 'Task status updated successfully');
        } else {
            showToast('error', 'Error', data.error || 'Could not update task status');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', 'Error', 'Could not update task status. Please try again.');
    });
}

// Tab management functions
function loadTabContent(tabId) {
    const projectId = document.querySelector('.kanban-wrapper').dataset.projectId;
    
    if (!projectId || !tabId) {
        console.error('Missing project ID or tab ID');
        return;
    }
    
    // Update URL with current tab and store state for browser history
    const url = new URL(window.location);
    url.searchParams.set('tab', tabId);
    const stateData = { 
        tabId: tabId, 
        projectId: projectId, 
        timestamp: Date.now() 
    };
    window.history.pushState(stateData, `Tab ${tabId}`, url);
    
    // Show loading state
    const kanbanContainer = document.querySelector('.kanban-container');
    kanbanContainer.innerHTML = '<div class="text-center p-4"><i class="fas fa-spinner fa-spin me-2"></i>Loading...</div>';
    
    // Fetch tab-specific data
    fetch(`/ajax/get-kanban-updates.php?project_id=${projectId}&tab_id=${tabId}&full_data=true`, {
        method: 'GET',
        credentials: 'same-origin',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.columns) {
            updateKanbanBoard(data.columns, data.members);
            
            // Set new timestamp for this tab
            const timestamp = Math.floor(Date.now() / 1000).toString();
            localStorage.setItem(`kanban_last_update_${projectId}_${tabId}`, timestamp);
            
            showToast('success', 'Tab Switched', 'Successfully loaded tab content');
        } else {
            throw new Error('Invalid response data');
        }
    })
    .catch(error => {
        console.error('Error loading tab content:', error);
        showToast('error', 'Error', 'Could not load tab content. Please refresh the page.');
        
        // Fallback: reload the page with the new tab
        const url = new URL(window.location);
        url.searchParams.set('tab', tabId);
        window.location.href = url.toString();
    });
}

// Browser navigation handling
function handleBrowserNavigation() {
    window.addEventListener('popstate', function(event) {
        const urlParams = new URLSearchParams(window.location.search);
        const tabId = urlParams.get('tab');
        
        if (tabId && event.state) {
            loadTabContentSilent(tabId);
            updateActiveTabState(tabId);
        } else if (tabId) {
            window.location.reload();
        }
    });
}

function loadTabContentSilent(tabId) {
    const projectId = document.querySelector('.kanban-wrapper').dataset.projectId;
    
    if (!projectId || !tabId) {
        console.error('Missing project ID or tab ID');
        return;
    }
    
    // Show loading state
    const kanbanContainer = document.querySelector('.kanban-container');
    kanbanContainer.innerHTML = '<div class="text-center p-4"><i class="fas fa-spinner fa-spin me-2"></i>Loading...</div>';
    
    // Fetch tab-specific data
    fetch(`/ajax/get-kanban-updates.php?project_id=${projectId}&tab_id=${tabId}&full_data=true`, {
        method: 'GET',
        credentials: 'same-origin',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.columns) {
            updateKanbanBoard(data.columns, data.members);
            
            // Set new timestamp for this tab
            const timestamp = Math.floor(Date.now() / 1000).toString();
            localStorage.setItem(`kanban_last_update_${projectId}_${tabId}`, timestamp);
        } else {
            throw new Error('Invalid response data');
        }
    })
    .catch(error => {
        console.error('Error loading tab content:', error);
        showToast('error', 'Error', 'Could not load tab content. Please refresh the page.');
        
        // Fallback: reload the page with the new tab
        const url = new URL(window.location);
        url.searchParams.set('tab', tabId);
        window.location.href = url.toString();
    });
}

function updateActiveTabState(tabId) {
    // Remove active class from all tabs
    document.querySelectorAll('.nav-link').forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Add active class to current tab
    const activeTab = document.querySelector(`.nav-link[data-tab-id="${tabId}"]`);
    if (activeTab) {
        activeTab.classList.add('active');
    }
}

// UI Helper Functions
function addTaskToColumn(task, assignedName, columnId) {
    const column = document.querySelector(`.kanban-tasks[data-column-id="${columnId}"]`);
    
    if (!column) {
        console.error('Column not found');
        return;
    }
    
    // Create the task element
    const taskElement = createTaskElement(task, assignedName);
    
    // Determine where to add the task based on status
    if (task.status === 'completed') {
        // Handle completed tasks section
        let completedSection = column.querySelector('.completed-tasks-section');
        
        if (!completedSection) {
            completedSection = document.createElement('div');
            completedSection.className = 'completed-tasks-section';
            completedSection.innerHTML = `
                <div class="completed-tasks-header" data-bs-toggle="collapse" href="#completedTasks${columnId}" role="button" aria-expanded="false">
                    <i class="fas fa-chevron-down me-1 collapse-icon"></i> Completed tasks (1)
                </div>
                <div class="collapse" id="completedTasks${columnId}"></div>
            `;
            column.appendChild(completedSection);
        } else {
            // Update the count in the header
            const countElement = completedSection.querySelector('.completed-tasks-header');
            const count = completedSection.querySelectorAll('.kanban-task').length + 1;
            countElement.innerHTML = `<i class="fas fa-chevron-down me-1 collapse-icon"></i> Completed tasks (${count})`;
        }
        
        // Add the task to the completed tasks section
        const completedTasksContainer = completedSection.querySelector('.collapse');
        completedTasksContainer.appendChild(taskElement);
    } else {
        // Add the task to the column (before the completed tasks section if it exists)
        const completedSection = column.querySelector('.completed-tasks-section');
        if (completedSection) {
            column.insertBefore(taskElement, completedSection);
        } else {
            column.appendChild(taskElement);
        }
    }
    
    // Refresh the sortable instances
    refreshSortable();
}

function updateTaskInUI(taskId, formData) {
    const taskElement = document.querySelector(`.kanban-task[data-task-id="${taskId}"]`);
    if (taskElement) {
        const title = formData.get('title');
        const description = formData.get('description');
        const status = formData.get('status');
        
        // Update task title
        taskElement.querySelector('h6').textContent = title;
        
        // Update description
        let descElement = taskElement.querySelector('p.small.text-muted');
        if (description) {
            if (!descElement) {
                descElement = document.createElement('p');
                descElement.className = 'small text-muted mt-1 mb-2';
                taskElement.querySelector('.kanban-task-header').after(descElement);
            }
            descElement.innerHTML = description.replace(/\n/g, '<br>');
        } else if (descElement) {
            descElement.remove();
        }
        
        // Update status class if changed
        if (status !== taskElement.dataset.status) {
            handleTaskStatusChange(taskId, status, taskElement.closest('.kanban-tasks').dataset.columnId);
        }
        
        // Update the edit button's data attributes for future edits
        const editBtn = taskElement.querySelector('.edit-task-btn');
        if (editBtn) {
            editBtn.dataset.title = title;
            editBtn.dataset.description = description || '';
            editBtn.dataset.status = status;
        }
    }
}

function createTaskElement(task, assignedName) {
    const taskElement = document.createElement('div');
    taskElement.className = `kanban-task task-status-${task.status || 'active'}`;
    taskElement.dataset.taskId = task.id;
    taskElement.dataset.status = task.status || 'active';
    
    // Format due date if it exists
    let dueDateFormatted = '';
    if (task.due_date) {
        const dueDate = new Date(task.due_date);
        dueDateFormatted = `${dueDate.toLocaleString('default', { month: 'short' })} ${dueDate.getDate()}, ${dueDate.getFullYear()}`;
    }
    
    // Create task HTML
    taskElement.innerHTML = `
        <div class="kanban-task-header">
            <h6 class="mb-0">${task.title}</h6>
            <div class="dropdown">
                <button class="btn btn-sm btn-link p-0 text-dark" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <button class="dropdown-item edit-task-btn" data-bs-toggle="modal" data-bs-target="#editTaskModal"
                            data-task-id="${task.id}"
                            data-title="${task.title}"
                            data-description="${task.description || ''}"
                            data-assigned-to="${task.assigned_to || ''}"
                            data-due-date="${task.due_date || ''}"
                            data-status="${task.status || 'active'}">
                            <i class="fas fa-edit me-1"></i> Edit task
                        </button>
                    </li>
                    <li>
                        <button class="dropdown-item text-danger delete-task-btn" data-bs-toggle="modal" data-bs-target="#deleteTaskModal"
                            data-task-id="${task.id}"
                            data-title="${task.title}">
                            <i class="fas fa-trash me-1"></i> Delete task
                        </button>
                    </li>
                </ul>
            </div>
            <div class="task-status-buttons">
                <button type="button" class="btn btn-sm btn-status status-btn-active status-option" title="Mark as Active" data-status="active" data-task-id="${task.id}">
                    <i class="fas fa-play"></i>
                </button>
                <button type="button" class="btn btn-sm btn-status status-btn-pending status-option" title="Mark as Pending" data-status="pending" data-task-id="${task.id}">
                    <i class="fas fa-pause"></i>
                </button>
                <button type="button" class="btn btn-sm btn-status status-btn-completed status-option" title="Mark as Completed" data-status="completed" data-task-id="${task.id}">
                    <i class="fas fa-check"></i>
                </button>
            </div>
        </div>
        ${task.description ? `<p class="small text-muted mt-1 mb-2">${task.description.replace(/\n/g, '<br>')}</p>` : ''}
        <div class="kanban-task-footer">
            ${assignedName ? `
                <div class="small">
                    <i class="fas fa-user me-1"></i> ${assignedName}
                </div>
            ` : ''}
            ${task.due_date ? `
                <div class="small">
                    <i class="fas fa-calendar me-1"></i> ${dueDateFormatted}
                </div>
            ` : ''}
        </div>
    `;
    
    return taskElement;
}

function handleTaskStatusChange(taskId, newStatus, columnId) {
    const task = document.querySelector(`.kanban-task[data-task-id="${taskId}"]`);
    if (!task) return;
    
    // Update task status class
    task.className = task.className.replace(/task-status-\w+/, `task-status-${newStatus}`);
    task.dataset.status = newStatus;
    
    const column = document.querySelector(`.kanban-tasks[data-column-id="${columnId}"]`);
    if (!column) return;
    
    // If changing to completed status, move to completed section
    if (newStatus === 'completed') {
        let completedSection = column.querySelector('.completed-tasks-section');
        
        if (!completedSection) {
            // Create completed section if it doesn't exist
            completedSection = document.createElement('div');
            completedSection.className = 'completed-tasks-section';
            completedSection.innerHTML = `
                <div class="completed-tasks-header" data-bs-toggle="collapse" href="#completedTasks${columnId}" role="button" aria-expanded="false">
                    <i class="fas fa-chevron-down me-1 collapse-icon"></i> Completed tasks (1)
                </div>
                <div class="collapse" id="completedTasks${columnId}"></div>
            `;
            column.appendChild(completedSection);
        } else {
            // Update the count
            const countElement = completedSection.querySelector('.completed-tasks-header');
            const count = completedSection.querySelectorAll('.collapse .kanban-task').length + 1;
            countElement.innerHTML = `<i class="fas fa-chevron-down me-1 collapse-icon"></i> Completed tasks (${count})`;
        }
        
        // Add to completed section
        const completedTasksContainer = completedSection.querySelector('.collapse');
        completedTasksContainer.appendChild(task);
        
        // Refresh sortable to account for the moved task
        refreshSortable();
        
    } else if (task.closest('.completed-tasks-section')) {
        // If task is in completed section but status is not completed, move it out
        const completedSection = task.closest('.completed-tasks-section');
        column.insertBefore(task, completedSection);
        
        // Update the count in the completed section
        const countElement = completedSection.querySelector('.completed-tasks-header');
        const count = completedSection.querySelectorAll('.collapse .kanban-task').length;
        
        if (count === 0) {
            // Remove the completed section if no tasks remain
            completedSection.remove();
        } else {
            countElement.innerHTML = `<i class="fas fa-chevron-down me-1 collapse-icon"></i> Completed tasks (${count})`;
        }
        
        // Refresh sortable
        refreshSortable();
    }
}

function updateKanbanBoard(columns, members) {
    const kanbanContainer = document.querySelector('.kanban-container');
    kanbanContainer.innerHTML = ''; // Clear existing columns
    
    // Get project manager status from page data attribute
    const isProjectManager = document.querySelector('.kanban-wrapper').dataset.isProjectManager === 'true';
    
    columns.forEach(column => {
        const columnElement = document.createElement('div');
        columnElement.className = 'kanban-column';
        columnElement.dataset.columnId = column.id;
        
        // Create column header
        const columnHeader = document.createElement('div');
        columnHeader.className = 'kanban-column-header';
        columnHeader.innerHTML = `
            <h5>${column.name}</h5>
            ${isProjectManager ? `
                <div class="dropdown">
                    <button class="btn btn-sm btn-link text-dark" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <button class="dropdown-item edit-column-btn" data-bs-toggle="modal" data-bs-target="#editColumnModal"
                                data-column-id="${column.id}"
                                data-name="${column.name}">
                                <i class="fas fa-edit me-1"></i> Edit column
                            </button>
                        </li>
                        <li>
                            <button class="dropdown-item text-danger delete-column-btn" data-bs-toggle="modal" data-bs-target="#deleteColumnModal"
                                data-column-id="${column.id}"
                                data-name="${column.name}">
                                <i class="fas fa-trash me-1"></i> Delete column
                            </button>
                        </li>
                    </ul>
                </div>
            ` : ''}
        `;
        columnElement.appendChild(columnHeader);
        
        // Create tasks container
        const tasksContainer = document.createElement('div');
        tasksContainer.className = 'kanban-tasks';
        tasksContainer.dataset.columnId = column.id;
        columnElement.appendChild(tasksContainer);
        
        // Separate active and completed tasks
        const activeTasks = [];
        const completedTasks = [];
        
        if (column.tasks && Array.isArray(column.tasks)) {
            column.tasks.forEach(task => {
                if (task.status === 'completed') {
                    completedTasks.push(task);
                } else {
                    activeTasks.push(task);
                }
            });
        }
        
        // Add active tasks
        activeTasks.forEach(task => {
            const taskElement = createTaskElement(task, getMemberName(task.assigned_to, members));
            tasksContainer.appendChild(taskElement);
        });
        
        // Add completed tasks section if there are any
        if (completedTasks.length > 0) {
            const completedSection = document.createElement('div');
            completedSection.className = 'completed-tasks-section';
            completedSection.innerHTML = `
                <div class="completed-tasks-header" data-bs-toggle="collapse" href="#completedTasks${column.id}" role="button" aria-expanded="false">
                    <i class="fas fa-chevron-down me-1 collapse-icon"></i> Completed tasks (${completedTasks.length})
                </div>
                <div class="collapse" id="completedTasks${column.id}"></div>
            `;
            tasksContainer.appendChild(completedSection);
            
            const completedTasksContainer = completedSection.querySelector('.collapse');
            completedTasks.forEach(task => {
                const taskElement = createTaskElement(task, getMemberName(task.assigned_to, members));
                completedTasksContainer.appendChild(taskElement);
            });
        }
        
        // Add column footer
        const columnFooter = document.createElement('div');
        columnFooter.className = 'kanban-column-footer';
        columnFooter.innerHTML = `
            <button class="btn btn-sm btn-outline-primary w-100 add-task-btn" data-bs-toggle="modal" data-bs-target="#addTaskModal" data-column-id="${column.id}">
                <i class="fas fa-plus me-1"></i> Add Task
            </button>
        `;
        columnElement.appendChild(columnFooter);
        
        kanbanContainer.appendChild(columnElement);
    });
    
    // Re-attach event listeners to the new elements
    attachEventListeners();
    
    // Refresh sortable instances
    refreshSortable();
}

// Utility Functions
function getMemberName(memberId, members) {
    if (!memberId || !members) return '';
    
    for (let i = 0; i < members.length; i++) {
        if (members[i].id == memberId) {
            return members[i].name;
        }
    }
    return '';
}

function showToast(type, title, message) {
    // Create toast container if it doesn't exist
    let toastContainer = document.querySelector('.toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container';
        document.body.appendChild(toastContainer);
    }
    
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast ${type === 'error' ? 'bg-danger' : 'bg-success'} text-white`;
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');
    
    toast.innerHTML = `
        <div class="toast-header bg-${type === 'error' ? 'danger' : 'success'} text-white">
            <strong class="me-auto">${title}</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            ${message}
        </div>
    `;
    
    toastContainer.appendChild(toast);
    
    // Show the toast
    const bsToast = new bootstrap.Toast(toast, {
        delay: 3000,
        autohide: true
    });
    bsToast.show();
    
    // Remove toast after it's hidden
    toast.addEventListener('hidden.bs.toast', function() {
        toast.remove();
    });
}

// Drag and Drop Functionality
function initializeSortable() {
    // Mobile detection
    const isMobile = window.innerWidth <= 768 || 'ontouchstart' in window;
    
    // Column reordering - Enhanced for mobile
    new Sortable(document.querySelector('.kanban-container'), {
        animation: 150,
        handle: '.kanban-column-header',
        draggable: '.kanban-column',
        ghostClass: 'column-dragging',
        delay: isMobile ? 150 : 0,
        delayOnTouchStart: true,
        touchStartThreshold: 5,
        forceFallback: isMobile,
        fallbackTolerance: 5,
        onEnd: function(evt) {
            saveColumnOrder();
        }
    });

    // Tasks reordering (active tasks) - Enhanced for mobile
    document.querySelectorAll('.kanban-tasks').forEach(column => {
        new Sortable(column, {
            animation: 150,
            draggable: '.kanban-task',
            group: 'tasks',
            ghostClass: 'sortable-ghost',
            filter: '.completed-tasks-section, .dropdown-menu, .btn, .dropdown-toggle',
            delay: isMobile ? 150 : 0,
            delayOnTouchStart: true,
            touchStartThreshold: 10,
            forceFallback: isMobile,
            fallbackTolerance: 5,
            preventOnFilter: true,
            onEnd: function(evt) {
                const taskId = evt.item.dataset.taskId;
                const newColumnId = evt.to.dataset.columnId;
                
                // If moving to a new column
                if (evt.from !== evt.to) {
                    updateTaskColumn(taskId, newColumnId);
                }
                
                // Save the new order after a short delay
                setTimeout(() => {
                    saveTaskOrder(newColumnId);
                }, 100);
            },
            onStart: function(evt) {
                // Close any open dropdowns when starting to drag
                document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                    menu.classList.remove('show');
                });
            }
        });
    });

    // Initialize sortable for completed tasks sections
    document.querySelectorAll('.completed-tasks-section .collapse').forEach(container => {
        new Sortable(container, {
            animation: 150,
            draggable: '.kanban-task',
            group: 'completed-tasks',
            ghostClass: 'sortable-ghost',
            delay: isMobile ? 150 : 0,
            delayOnTouchStart: true,
            touchStartThreshold: 10,
            forceFallback: isMobile,
            fallbackTolerance: 5,
            filter: '.dropdown-menu, .btn, .dropdown-toggle',
            preventOnFilter: true,
            onEnd: function(evt) {
                const columnElement = evt.to.closest('.kanban-tasks');
                const columnId = columnElement ? columnElement.dataset.columnId : null;
                
                if (columnId) {
                    saveTaskOrder(columnId);
                }
            },
            onStart: function(evt) {
                document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                    menu.classList.remove('show');
                });
            }
        });
    });
}

function refreshSortable() {
    // Re-initialize sortable for any new elements
    initializeSortable();
}

function saveColumnOrder() {
    const columns = Array.from(document.querySelectorAll('.kanban-column')).map(column => {
        return column.dataset.columnId;
    });
    
    const formData = new FormData();
    formData.append('action', 'update_column_order');
    formData.append('column_order', JSON.stringify(columns));
    
    fetch('/ajax/update-kanban-task.php', {
        method: 'POST',
        body: formData,
        credentials: 'same-origin',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showToast('success', 'Success', 'Column order updated');
        } else {
            console.error('Error updating column order:', data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function saveTaskOrder(columnId) {
    const column = document.querySelector(`.kanban-tasks[data-column-id="${columnId}"]`);
    if (!column) return;
    
    // Get all active tasks
    const activeTasks = Array.from(column.querySelectorAll('.kanban-task:not(.completed-tasks-section .kanban-task)')).map(task => {
        return task.dataset.taskId;
    });
    
    // Get all completed tasks
    const completedTasksSection = column.querySelector('.completed-tasks-section .collapse');
    const completedTasks = completedTasksSection ? 
        Array.from(completedTasksSection.querySelectorAll('.kanban-task')).map(task => task.dataset.taskId) :
        [];
    
    // Combine both arrays
    const taskOrder = {
        active: activeTasks,
        completed: completedTasks
    };
    
    const formData = new FormData();
    formData.append('action', 'update_task_order');
    formData.append('column_id', columnId);
    formData.append('task_order', JSON.stringify(taskOrder));
    
    fetch('/ajax/update-kanban-task.php', {
        method: 'POST',
        body: formData,
        credentials: 'same-origin',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            console.log('Task order updated successfully');
        } else {
            console.error('Error updating task order:', data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function updateTaskColumn(taskId, columnId) {
    const task = document.querySelector(`.kanban-task[data-task-id="${taskId}"]`);
    const status = task ? task.dataset.status : 'active';
    
    console.log('Updating task', taskId, 'to column', columnId);
    
    const formData = new FormData();
    formData.append('task_id', taskId);
    formData.append('column_id', columnId);
    formData.append('status', status);
    formData.append('action', 'move_task');
    
    fetch('/ajax/update-kanban-task.php', {
        method: 'POST',
        body: formData,
        credentials: 'same-origin', 
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showToast('success', 'Success', 'Task moved successfully');
            
            // If this is a status change, update the UI accordingly
            if (data.task && data.task.status !== status) {
                handleTaskStatusChange(taskId, data.task.status, columnId);
            }
            
            // Save the task order in the new column
            saveTaskOrder(columnId);
        } else {
            console.error('Error moving task:', data.error);
            showToast('error', 'Error', data.error || 'Could not move task. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', 'Error', 'Could not move task. Please try again.');
    });
}

// Real-time Updates
function initializePeriodicSync() {
    let isUserActive = true;
    let syncInterval;
    
    const projectId = document.querySelector('.kanban-wrapper').dataset.projectId;
    
    const getCurrentTabId = () => {
        const activeTab = document.querySelector('.kanban-tabs .nav-link.active');
        return activeTab ? activeTab.dataset.tabId : null;
    };
    
    console.log('Trying to sync with project ID:', projectId);

    if (!projectId) return;
    
    function checkForUpdates() {
        if (!document.hasFocus()) return;
        
        const currentTabId = getCurrentTabId();
        if (!currentTabId) return;
        
        const lastUpdateTime = localStorage.getItem(`kanban_last_update_${projectId}_${currentTabId}`) || '0';
        
        fetch(`/ajax/get-kanban-updates.php?project_id=${projectId}&tab_id=${currentTabId}&last_update=${lastUpdateTime}`, {
            method: 'GET',
            credentials: 'same-origin',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.hasUpdates) {
                showToast('info', 'Updates', 'New changes detected. Updating board...');
                
                const currentTabId = getCurrentTabId();
                
                if (data.timestamp && currentTabId) {
                    localStorage.setItem(`kanban_last_update_${projectId}_${currentTabId}`, data.timestamp);
                }
                
                fetch(`/ajax/get-kanban-updates.php?project_id=${projectId}&tab_id=${currentTabId}&full_data=true`, {
                    method: 'GET',
                    credentials: 'same-origin',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(updatedData => {
                    if (updatedData.columns) {
                        updateKanbanBoard(updatedData.columns, updatedData.members);
                    }
                })
                .catch(error => {
                    console.error('Error fetching updated kanban data:', error);
                    showToast('error', 'Error', 'Could not refresh kanban data');
                });
            }
        })
        .catch(error => {
            console.error('Error checking for updates:', error);
        });
    }
    
    // Start the interval
    syncInterval = setInterval(checkForUpdates, 15000);
    
    // Set initial timestamp for current tab
    const initialTabId = getCurrentTabId();
    if (initialTabId) {
        const initialTimestamp = Math.floor(Date.now() / 1000).toString();
        localStorage.setItem(`kanban_last_update_${projectId}_${initialTabId}`, initialTimestamp);
    }
    
    // Detect user activity
    document.addEventListener('click', () => { isUserActive = true; });
    document.addEventListener('keypress', () => { isUserActive = true; });
    document.addEventListener('mousemove', () => { isUserActive = true; });
    
    // Pause syncing when page is hidden
    document.addEventListener('visibilitychange', () => {
        console.log('Visibility changed:', document.visibilityState);
        if (document.visibilityState === 'hidden') {
            isUserActive = false;
        } else {
            isUserActive = true;
            console.log('Page is visible again. Checking for updates...');
            checkForUpdates();
        }
    });
    
    // Clear interval when leaving the page
    window.addEventListener('beforeunload', () => {
        clearInterval(syncInterval);
    });
}

// Mobile Touch Events
function initializeMobileTouchEvents() {
    const isTouchDevice = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
    
    if (isTouchDevice) {
        // Add better touch handling for kanban tasks
        document.querySelectorAll('.kanban-task').forEach(task => {
            let touchStartY = 0;
            let touchStartX = 0;
            let isScrolling = false;
            
            task.addEventListener('touchstart', function(e) {
                touchStartY = e.touches[0].clientY;
                touchStartX = e.touches[0].clientX;
                isScrolling = false;
                this.style.transform = 'scale(0.98)';
            }, { passive: true });
            
            task.addEventListener('touchmove', function(e) {
                const touchY = e.touches[0].clientY;
                const touchX = e.touches[0].clientX;
                const deltaY = Math.abs(touchY - touchStartY);
                const deltaX = Math.abs(touchX - touchStartX);
                
                if (deltaY > 10 || deltaX > 10) {
                    isScrolling = true;
                    this.style.transform = '';
                }
            }, { passive: true });
            
            task.addEventListener('touchend', function(e) {
                this.style.transform = '';
                
                if (!isScrolling) {
                    this.style.backgroundColor = '#f8f9fa';
                    setTimeout(() => {
                        this.style.backgroundColor = '';
                    }, 150);
                }
            }, { passive: true });
        });
        
        // Improve dropdown touch handling
        document.querySelectorAll('.dropdown-toggle').forEach(dropdown => {
            dropdown.addEventListener('touchstart', function(e) {
                e.preventDefault();
                this.click();
            });
        });
        
        // Better modal touch handling
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('touchstart', function(e) {
                if (e.target === this) {
                    const modalInstance = bootstrap.Modal.getInstance(this);
                    if (modalInstance) {
                        modalInstance.hide();
                    }
                }
            });
        });
        
        // Improve tab switching on mobile
        document.querySelectorAll('.nav-tabs .nav-link').forEach(tab => {
            tab.addEventListener('touchstart', function(e) {
                e.preventDefault();
                this.click();
            });
        });
    }
}

// Initialize mobile touch events when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeMobileTouchEvents);
} else {
    initializeMobileTouchEvents();
}
