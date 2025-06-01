/**
 * Organizations management
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

// Initialize organizations functionality when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeOrganizations();
});

function initializeOrganizations() {
    // View organization details
    document.querySelectorAll('.view-organization-btn').forEach(button => {
        button.addEventListener('click', function() {
            const organizationId = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const description = this.getAttribute('data-description');
            const created = this.getAttribute('data-created');
            
            document.getElementById('view_name').textContent = name;
            document.getElementById('view_description').textContent = description || 'No description provided';
            document.getElementById('view_created').textContent = created;
            
            // Load organization members
            fetch(`ajax/get-organization-members.php?organization_id=${organizationId}`)
                .then(response => response.json())
                .then(data => {
                    const membersList = document.getElementById('view_members');
                    membersList.innerHTML = '';
                    
                    if (data.length === 0) {
                        membersList.innerHTML = '<p class="text-muted">No members in this organization</p>';
                    } else {
                        data.forEach(member => {
                            const item = document.createElement('div');
                            item.className = 'list-group-item d-flex justify-content-between align-items-center';
                            
                            const nameSpan = document.createElement('span');
                            nameSpan.textContent = member.name;
                            
                            const badgeSpan = document.createElement('span');
                            badgeSpan.className = member.is_admin ? 'badge bg-primary' : 'badge bg-secondary';
                            badgeSpan.textContent = member.is_admin ? 'Admin' : 'Member';
                            
                            item.appendChild(nameSpan);
                            item.appendChild(badgeSpan);
                            membersList.appendChild(item);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error loading organization members:', error);
                    document.getElementById('view_members').innerHTML = '<p class="text-danger">Error loading members</p>';
                });
        });
    });

    // Edit organization
    document.querySelectorAll('.edit-organization-btn').forEach(button => {
        button.addEventListener('click', function() {
            const organizationId = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const description = this.getAttribute('data-description');
            
            document.getElementById('edit_organization_id').value = organizationId;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_description').value = description;
        });
    });

    // Manage members
    document.querySelectorAll('.manage-members-btn').forEach(button => {
        button.addEventListener('click', function() {
            const organizationId = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            
            document.getElementById('manage_organization_id').value = organizationId;
            document.getElementById('manage_organization_name').textContent = name;
            
            // Load organization members
            loadOrganizationMembers(organizationId);
        });
    });

    // Delete organization
    document.querySelectorAll('.delete-organization-btn').forEach(button => {
        button.addEventListener('click', function() {
            const organizationId = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            
            document.getElementById('delete_organization_id').value = organizationId;
            document.getElementById('delete_organization_name').textContent = name;
        });
    });
}

// Load organization members for management
function loadOrganizationMembers(organizationId) {
    fetch(`ajax/get-organization-members.php?organization_id=${organizationId}`)
        .then(response => response.json())
        .then(data => {
            const membersList = document.getElementById('members_list');
            membersList.innerHTML = '';
            
            if (data.length === 0) {
                const row = document.createElement('tr');
                row.innerHTML = '<td colspan="5" class="text-center">No members in this organization</td>';
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
                    roleBadge.className = member.is_admin ? 'badge bg-primary' : 'badge bg-secondary';
                    roleBadge.textContent = member.is_admin ? 'Admin' : 'Member';
                    roleCell.appendChild(roleBadge);
                    
                    const joinedCell = document.createElement('td');
                    joinedCell.textContent = new Date(member.joined_at).toLocaleDateString();
                    
                    const actionsCell = document.createElement('td');
                    
                    // Don't allow removing yourself - get current user ID from window object
                    if (member.user_id != window.currentUserId) {
                        const removeBtn = document.createElement('button');
                        removeBtn.className = 'btn btn-sm btn-outline-danger';
                        removeBtn.innerHTML = '<i class="fas fa-user-minus"></i>';
                        removeBtn.title = 'Remove from organization';
                        removeBtn.addEventListener('click', function() {
                            if (confirm(`Are you sure you want to remove ${member.name} from this organization?`)) {
                                const form = document.createElement('form');
                                form.method = 'post';
                                form.action = '';
                                
                                const actionInput = document.createElement('input');
                                actionInput.type = 'hidden';
                                actionInput.name = 'action';
                                actionInput.value = 'remove_member';
                                
                                const organizationIdInput = document.createElement('input');
                                organizationIdInput.type = 'hidden';
                                organizationIdInput.name = 'organization_id';
                                organizationIdInput.value = organizationId;
                                
                                const userIdInput = document.createElement('input');
                                userIdInput.type = 'hidden';
                                userIdInput.name = 'user_id';
                                userIdInput.value = member.user_id;
                                
                                form.appendChild(actionInput);
                                form.appendChild(organizationIdInput);
                                form.appendChild(userIdInput);
                                
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
            console.error('Error loading organization members:', error);
            document.getElementById('members_list').innerHTML = '<tr><td colspan="5" class="text-danger">Error loading members</td></tr>';
        });
}
