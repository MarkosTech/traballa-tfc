// Users page JavaScript functionality

// Helper function to get role badge HTML
function getRoleBadgeHTML(role) {
    if (role === 'admin') {
        return '<span class="badge bg-danger">Admin</span>';
    } else if (role === 'employee') {
        return '<span class="badge bg-info">Employee</span>';
    } else {
        return '<span class="badge bg-secondary">User</span>';
    }
}

// View user modal
document.querySelectorAll('.view-user-btn').forEach(button => {
    button.addEventListener('click', function() {
        const name = this.getAttribute('data-name');
        const email = this.getAttribute('data-email');
        const role = this.getAttribute('data-role');
        const created = this.getAttribute('data-created');
        
        document.getElementById('view-name').textContent = name;
        document.getElementById('view-email').textContent = email;
        
        // Display role with badge styling consistent with table
        const roleElement = document.getElementById('view-role');
        roleElement.innerHTML = getRoleBadgeHTML(role);
        
        document.getElementById('view-created').textContent = created;
    });
});

// Delete user modal
document.querySelectorAll('.delete-user-btn').forEach(button => {
    button.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        const name = this.getAttribute('data-name');
        const role = this.getAttribute('data-role');
        
        document.getElementById('delete-user-id').value = id;
        document.getElementById('delete-user-name').textContent = name;
        
        // Display role with badge styling
        const roleElement = document.getElementById('delete-user-role');
        roleElement.innerHTML = getRoleBadgeHTML(role);
    });
});
