/**
 * Projects
 * 
 * @copyright 2025 Marcos Núñez Fernández
 * @license   MIT License
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

// View project details
document.querySelectorAll('.view-project-btn').forEach(button => {
    button.addEventListener('click', function() {
        const projectId = this.getAttribute('data-id');
        const name = this.getAttribute('data-name');
        const description = this.getAttribute('data-description');
        const status = this.getAttribute('data-status');
        const created = this.getAttribute('data-created');
        const organization = this.getAttribute('data-organization');
        
        document.getElementById('view_name').textContent = name;
        document.getElementById('view_description').textContent = description || 'No description provided';
        document.getElementById('view_status').textContent = status.charAt(0).toUpperCase() + status.slice(1);
        document.getElementById('view_created').textContent = created;
        document.getElementById('view_organization').textContent = organization || 'Not specified';
        
        // Load project members
        fetch(`ajax/get-project-members.php?project_id=${projectId}`)
            .then(response => response.json())
            .then(data => {
                const membersList = document.getElementById('view_members');
                membersList.innerHTML = '';
                
                if (data.length === 0) {
                    membersList.innerHTML = '<p class="text-muted">No members in this project</p>';
                } else {
                    data.forEach(member => {
                        const item = document.createElement('div');
                        item.className = 'list-group-item d-flex justify-content-between align-items-center';
                        
                        const nameSpan = document.createElement('span');
                        nameSpan.textContent = member.name;
                        
                        const badgeSpan = document.createElement('span');
                        badgeSpan.className = member.is_manager ? 'badge bg-primary' : 'badge bg-secondary';
                        badgeSpan.textContent = member.is_manager ? 'Manager' : 'Member';
                        
                        item.appendChild(nameSpan);
                        item.appendChild(badgeSpan);
                        membersList.appendChild(item);
                    });
                }
            })
            .catch(error => {
                console.error('Error loading project members:', error);
                document.getElementById('view_members').innerHTML = '<p class="text-danger">Error loading members</p>';
            });
    });
});

// Edit project
document.querySelectorAll('.edit-project-btn').forEach(button => {
    button.addEventListener('click', function() {
        const projectId = this.getAttribute('data-id');
        const name = this.getAttribute('data-name');
        const description = this.getAttribute('data-description');
        const status = this.getAttribute('data-status');
        
        document.getElementById('edit_project_id').value = projectId;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_description').value = description;
        document.getElementById('edit_status').value = status;
    });
});

// Manage members
document.querySelectorAll('.manage-members-btn').forEach(button => {
    button.addEventListener('click', function() {
        const projectId = this.getAttribute('data-id');
        const name = this.getAttribute('data-name');
        
        document.getElementById('manage_project_id').value = projectId;
        document.getElementById('manage_project_name').textContent = name;
        
        // Load project members
        loadProjectMembers(projectId);
    });
});

// Load project members for management
function loadProjectMembers(projectId) {
    fetch(`ajax/get-project-members.php?project_id=${projectId}`)
        .then(response => response.json())
        .then(data => {
            const membersList = document.getElementById('members_list');
            membersList.innerHTML = '';
            
            if (data.length === 0) {
                const row = document.createElement('tr');
                row.innerHTML = '<td colspan="5" class="text-center">No members in this project</td>';
                membersList.appendChild(row);
            } else {
                data.forEach(member => {
                    const row = document.createElement('tr');
                    
                    const nameCell = document.createElement('td');
                    nameCell.textContent = member.name;
                    
                    const emailCell = document.createElement('td');
                    emailCell.textContent = member.email;
                    
                    const roleCell = document.createElement('td');
                    const roleBadge = document.createElement('span');
                    roleBadge.className = member.is_manager ? 'badge bg-primary' : 'badge bg-secondary';
                    roleBadge.textContent = member.is_manager ? 'Manager' : 'Member';
                    roleCell.appendChild(roleBadge);
                    
                    const joinedCell = document.createElement('td');
                    joinedCell.textContent = new Date(member.joined_at).toLocaleDateString();
                    
                    const actionsCell = document.createElement('td');
                    
                    // Don't allow removing yourself - the session user ID will be passed from PHP
                    if (member.user_id != window.currentUserId) {
                        const removeBtn = document.createElement('button');
                        removeBtn.className = 'btn btn-sm btn-outline-danger';
                        removeBtn.innerHTML = '<i class="fas fa-user-minus"></i>';
                        removeBtn.title = 'Remove from project';
                        removeBtn.addEventListener('click', function() {
                            if (confirm(`Are you sure you want to remove ${member.name} from this project?`)) {
                                const form = document.createElement('form');
                                form.method = 'post';
                                form.action = '';
                                
                                const actionInput = document.createElement('input');
                                actionInput.type = 'hidden';
                                actionInput.name = 'action';
                                actionInput.value = 'remove_member';
                                
                                const projectIdInput = document.createElement('input');
                                projectIdInput.type = 'hidden';
                                projectIdInput.name = 'project_id';
                                projectIdInput.value = projectId;
                                
                                const userIdInput = document.createElement('input');
                                userIdInput.type = 'hidden';
                                userIdInput.name = 'user_id';
                                userIdInput.value = member.user_id;
                                
                                // Add CSRF token
                                const csrfField = CSRFUtils.createField();
                                
                                form.appendChild(actionInput);
                                form.appendChild(projectIdInput);
                                form.appendChild(userIdInput);
                                if (csrfField) {
                                    form.appendChild(csrfField);
                                }
                                
                                document.body.appendChild(form);
                                form.submit();
                            }
                        });
                        
                        actionsCell.appendChild(removeBtn);
                    } else {
                        actionsCell.innerHTML = '<span class="text-muted">You</span>';
                    }
                    
                    row.appendChild(nameCell);
                    row.appendChild(emailCell);
                    row.appendChild(roleCell);
                    row.appendChild(joinedCell);
                    row.appendChild(actionsCell);
                    
                    membersList.appendChild(row);
                });
            }
        })
        .catch(error => {
            console.error('Error loading project members:', error);
            document.getElementById('members_list').innerHTML = '<tr><td colspan="5" class="text-danger">Error loading members</td></tr>';
        });
}

// Delete project
document.querySelectorAll('.delete-project-btn').forEach(button => {
    button.addEventListener('click', function() {
        const projectId = this.getAttribute('data-id');
        const name = this.getAttribute('data-name');
        
        document.getElementById('delete_project_id').value = projectId;
        document.getElementById('delete_project_name').textContent = name;
    });
});
