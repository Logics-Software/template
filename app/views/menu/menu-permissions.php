<?php
$title = $title ?? 'Menu Permissions';
$current_page = 'menu-permissions';

// Start output buffering to capture content
ob_start();
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?php echo APP_URL; ?>/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo APP_URL; ?>/menu">Menu Management</a></li>
                        <li class="breadcrumb-item active">Menu Permissions</li>
                    </ol>
                </div>
                <h4 class="page-title">Menu Permissions</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Role Selection -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Select Role</h5>
                </div>
                <div class="card-body">
                    <div class="list-group" id="roleList">
                        <?php foreach ($roles as $role): ?>
                            <button class="list-group-item list-group-item-action <?php echo $role === 'admin' ? 'active' : ''; ?>" 
                                    data-role="<?php echo $role; ?>" onclick="selectRole('<?php echo $role; ?>')">
                                <div class="d-flex w-100 justify-content-between align-items-center">
                                    <h6 class="mb-1"><?php echo ucfirst($role); ?></h6>
                                    <span class="badge bg-primary rounded-pill">
                                        <?php 
                                        $count = 0;
                                        foreach ($permissions as $permission) {
                                            if ($permission['role_id'] === $role) {
                                                $count++;
                                            }
                                        }
                                        echo $count;
                                        ?>
                                    </span>
                                </div>
                                <p class="mb-1 text-muted">Manage permissions for <?php echo $role; ?> role</p>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Permission Matrix -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Permission Matrix</h5>
                        <div>
                            <button class="btn btn-sm btn-outline-primary" onclick="selectAll()">
                                <i class="fas fa-check-square"></i> Select All
                            </button>
                            <button class="btn btn-sm btn-outline-secondary" onclick="deselectAll()">
                                <i class="fas fa-square"></i> Deselect All
                            </button>
                            <button class="btn btn-sm btn-success" onclick="savePermissions()">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="permissionMatrix">
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-shield-alt fa-3x mb-3"></i>
                            <h5>Select a role to manage permissions</h5>
                            <p>Choose a role from the left panel to view and edit its menu permissions.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Permission Statistics -->
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Permission Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php foreach ($roles as $role): ?>
                            <div class="col-md-2">
                                <div class="text-center">
                                    <h4 class="mb-1"><?php echo ucfirst($role); ?></h4>
                                    <div class="progress mb-2 h-8">
                                        <?php 
                                        $totalModules = count($modules);
                                        $allowedModules = 0;
                                        foreach ($permissions as $permission) {
                                            if ($permission['role_id'] === $role) {
                                                $allowedModules++;
                                            }
                                        }
                                        $percentage = $totalModules > 0 ? ($allowedModules / $totalModules) * 100 : 0;
                                        ?>
                                        <div class="progress-bar" role="progressbar" style="width: <?php echo $percentage; ?>%"></div>
                                    </div>
                                    <small class="text-muted"><?php echo $allowedModules; ?> / <?php echo $totalModules; ?> modules</small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Permission Modal -->
<div class="modal fade" id="bulkPermissionModal" tabindex="-1" aria-labelledby="bulkPermissionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkPermissionModalLabel">Bulk Permission Update</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="bulkAction" class="form-label">Action</label>
                    <select class="form-select" id="bulkAction">
                        <option value="grant">Grant Permission</option>
                        <option value="revoke">Revoke Permission</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="bulkPermissionType" class="form-label">Permission Type</label>
                    <select class="form-select" id="bulkPermissionType">
                        <option value="view">View Only</option>
                        <option value="full">Full Access</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Target Items</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="bulkModules" checked>
                        <label class="form-check-label" for="bulkModules">
                            All Modules
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="bulkGroups" checked>
                        <label class="form-check-label" for="bulkGroups">
                            All Groups
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="applyBulkPermission()">Apply Changes</button>
            </div>
        </div>
    </div>
</div>

<style>
.permission-matrix {
    max-height: 600px;
    overflow-y: auto;
}

.permission-item {
    border: 1px solid #dee2e6;
    border-radius: 6px;
    padding: 15px;
    margin-bottom: 10px;
    background: #fff;
    transition: all 0.2s;
}

.permission-item:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.permission-item-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.permission-item-info {
    display: flex;
    align-items: center;
}

.permission-item-info i {
    margin-right: 10px;
    color: #6c757d;
}

.permission-controls {
    display: flex;
    gap: 10px;
    align-items: center;
}

.permission-toggle {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 24px;
}

.permission-toggle input {
    opacity: 0;
    width: 0;
    height: 0;
}

.permission-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: .4s;
    border-radius: 24px;
}

.permission-slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}

.permission-toggle input:checked + .permission-slider {
    background-color: #2196F3;
}

.permission-toggle input:checked + .permission-slider:before {
    transform: translateX(26px);
}

.permission-type-select {
    min-width: 120px;
}

.role-permissions {
    display: none;
}

.role-permissions.active {
    display: block;
}

.permission-group {
    margin-bottom: 20px;
}

.permission-group-header {
    background: #f8f9fa;
    padding: 10px 15px;
    border-radius: 6px 6px 0 0;
    border: 1px solid #dee2e6;
    border-bottom: none;
    font-weight: 600;
    color: #495057;
}

.permission-group-content {
    border: 1px solid #dee2e6;
    border-top: none;
    border-radius: 0 0 6px 6px;
    padding: 15px;
}
</style>

<script>
// Wait for jQuery to be loaded
function waitForjQuery() {
    if (typeof $ === 'undefined') {
        // Waiting for jQuery to load...
        setTimeout(waitForjQuery, 100);
        return;
    }
    
    // jQuery loaded successfully
    initializejQuery();
}

function initializejQuery() {

let currentRole = 'admin';
let permissions = <?php echo json_encode($permissions); ?>;
let modules = <?php echo json_encode($modules); ?>;
let groups = <?php echo json_encode($groups); ?>;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize with admin role
    selectRole('admin');
});

function selectRole(role) {
    currentRole = role;
    
    // Update active role in list
    document.querySelectorAll('#roleList .list-group-item').forEach(item => {
        item.classList.remove('active');
    });
    document.querySelector(`[data-role="${role}"]`).classList.add('active');
    
    // Load permissions for this role
    loadRolePermissions(role);
}

function loadRolePermissions(role) {
    const rolePermissions = permissions.filter(p => p.role_id === role);
    
    let html = `
        <div class="permission-matrix">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">Permissions for <strong>${role}</strong> role</h6>
                <button class="btn btn-sm btn-outline-primary" onclick="openBulkPermissionModal()">
                    <i class="fas fa-cogs"></i> Bulk Actions
                </button>
            </div>
    `;
    
    // Group permissions
    if (groups.length > 0) {
        html += `
            <div class="permission-group">
                <div class="permission-group-header">
                    <i class="fas fa-folder me-2"></i>Menu Groups
                </div>
                <div class="permission-group-content">
        `;
        
        groups.forEach(group => {
            const hasPermission = rolePermissions.some(p => p.group_id == group.id);
            const permissionType = hasPermission ? 
                rolePermissions.find(p => p.group_id == group.id)?.permission_type || 'view' : 'none';
            
            html += `
                <div class="permission-item">
                    <div class="permission-item-header">
                        <div class="permission-item-info">
                            <i class="${group.icon}"></i>
                            <div>
                                <strong>${group.name}</strong>
                                <br><small class="text-muted">${group.description || 'Menu group'}</small>
                            </div>
                        </div>
                        <div class="permission-controls">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="group_${group.id}" 
                                       ${hasPermission ? 'checked' : ''} 
                                       onchange="toggleGroupPermission(${group.id}, this.checked)">
                                <label class="form-check-label" for="group_${group.id}">
                                    Access
                                </label>
                            </div>
                            <select class="form-select form-select-sm permission-type-select" 
                                    id="group_type_${group.id}" 
                                    ${!hasPermission ? 'disabled' : ''}
                                    onchange="updateGroupPermissionType(${group.id}, this.value)">
                                <option value="view" ${permissionType === 'view' ? 'selected' : ''}>View</option>
                                <option value="full" ${permissionType === 'full' ? 'selected' : ''}>Full</option>
                            </select>
                        </div>
                    </div>
                </div>
            `;
        });
        
        html += `
                </div>
            </div>
        `;
    }
    
    // Module permissions
    html += `
        <div class="permission-group">
            <div class="permission-group-header">
                <i class="fas fa-puzzle-piece me-2"></i>Menu Modules
            </div>
            <div class="permission-group-content">
    `;
    
    modules.forEach(module => {
        const hasPermission = rolePermissions.some(p => p.module_id == module.id);
        const permissionType = hasPermission ? 
            rolePermissions.find(p => p.module_id == module.id)?.permission_type || 'view' : 'none';
        
        html += `
            <div class="permission-item">
                <div class="permission-item-header">
                    <div class="permission-item-info">
                        <i class="${module.menu_icon || 'fas fa-circle'}"></i>
                        <div>
                            <strong>${module.caption}</strong>
                            <br><small class="text-muted">${module.menu_description || module.link}</small>
                        </div>
                    </div>
                    <div class="permission-controls">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="module_${module.id}" 
                                   ${hasPermission ? 'checked' : ''} 
                                   onchange="toggleModulePermission(${module.id}, this.checked)">
                            <label class="form-check-label" for="module_${module.id}">
                                Access
                            </label>
                        </div>
                        <select class="form-select form-select-sm permission-type-select" 
                                id="module_type_${module.id}" 
                                ${!hasPermission ? 'disabled' : ''}
                                onchange="updateModulePermissionType(${module.id}, this.value)">
                            <option value="view" ${permissionType === 'view' ? 'selected' : ''}>View</option>
                            <option value="full" ${permissionType === 'full' ? 'selected' : ''}>Full</option>
                        </select>
                    </div>
                </div>
            </div>
        `;
    });
    
    html += `
            </div>
        </div>
    </div>
    `;
    
    document.getElementById('permissionMatrix').innerHTML = html;
}

function toggleGroupPermission(groupId, hasAccess) {
    const typeSelect = document.getElementById(`group_type_${groupId}`);
    typeSelect.disabled = !hasAccess;
    
    if (!hasAccess) {
        typeSelect.value = 'view';
    }
}

function toggleModulePermission(moduleId, hasAccess) {
    const typeSelect = document.getElementById(`module_type_${moduleId}`);
    typeSelect.disabled = !hasAccess;
    
    if (!hasAccess) {
        typeSelect.value = 'view';
    }
}

function updateGroupPermissionType(groupId, permissionType) {
    // Implementation for updating group permission type
    // Update group permission
}

function updateModulePermissionType(moduleId, permissionType) {
    // Implementation for updating module permission type
    // Update module permission
}

function selectAll() {
    document.querySelectorAll(`#permissionMatrix input[type="checkbox"]`).forEach(checkbox => {
        checkbox.checked = true;
        const id = checkbox.id.replace('group_', '').replace('module_', '');
        const isGroup = checkbox.id.startsWith('group_');
        
        if (isGroup) {
            document.getElementById(`group_type_${id}`).disabled = false;
        } else {
            document.getElementById(`module_type_${id}`).disabled = false;
        }
    });
}

function deselectAll() {
    document.querySelectorAll(`#permissionMatrix input[type="checkbox"]`).forEach(checkbox => {
        checkbox.checked = false;
        const id = checkbox.id.replace('group_', '').replace('module_', '');
        const isGroup = checkbox.id.startsWith('group_');
        
        if (isGroup) {
            document.getElementById(`group_type_${id}`).disabled = true;
        } else {
            document.getElementById(`module_type_${id}`).disabled = true;
        }
    });
}

function openBulkPermissionModal() {
    $('#bulkPermissionModal').modal('show');
}

function applyBulkPermission() {
    const action = document.getElementById('bulkAction').value;
    const permissionType = document.getElementById('bulkPermissionType').value;
    const includeModules = document.getElementById('bulkModules').checked;
    const includeGroups = document.getElementById('bulkGroups').checked;
    
    if (action === 'grant') {
        if (includeModules) {
            document.querySelectorAll(`#permissionMatrix input[id^="module_"]`).forEach(checkbox => {
                checkbox.checked = true;
                const moduleId = checkbox.id.replace('module_', '');
                document.getElementById(`module_type_${moduleId}`).disabled = false;
                document.getElementById(`module_type_${moduleId}`).value = permissionType;
            });
        }
        
        if (includeGroups) {
            document.querySelectorAll(`#permissionMatrix input[id^="group_"]`).forEach(checkbox => {
                checkbox.checked = true;
                const groupId = checkbox.id.replace('group_', '');
                document.getElementById(`group_type_${groupId}`).disabled = false;
                document.getElementById(`group_type_${groupId}`).value = permissionType;
            });
        }
    } else {
        if (includeModules) {
            document.querySelectorAll(`#permissionMatrix input[id^="module_"]`).forEach(checkbox => {
                checkbox.checked = false;
                const moduleId = checkbox.id.replace('module_', '');
                document.getElementById(`module_type_${moduleId}`).disabled = true;
            });
        }
        
        if (includeGroups) {
            document.querySelectorAll(`#permissionMatrix input[id^="group_"]`).forEach(checkbox => {
                checkbox.checked = false;
                const groupId = checkbox.id.replace('group_', '');
                document.getElementById(`group_type_${groupId}`).disabled = true;
            });
        }
    }
    
    $('#bulkPermissionModal').modal('hide');
}

function savePermissions() {
    const rolePermissions = [];
    
    // Collect group permissions
    document.querySelectorAll(`#permissionMatrix input[id^="group_"]`).forEach(checkbox => {
        if (checkbox.checked) {
            const groupId = checkbox.id.replace('group_', '');
            const permissionType = document.getElementById(`group_type_${groupId}`).value;
            
            rolePermissions.push({
                group_id: parseInt(groupId),
                permission_type: permissionType
            });
        }
    });
    
    // Collect module permissions
    document.querySelectorAll(`#permissionMatrix input[id^="module_"]`).forEach(checkbox => {
        if (checkbox.checked) {
            const moduleId = checkbox.id.replace('module_', '');
            const permissionType = document.getElementById(`module_type_${moduleId}`).value;
            
            rolePermissions.push({
                module_id: parseInt(moduleId),
                permission_type: permissionType
            });
        }
    });
    
    // Send to server
    fetch('<?php echo APP_URL; ?>/menu/update-permissions', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            role_id: currentRole,
            permissions: rolePermissions
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('success', 'Permissions saved successfully');
            // Update local permissions array
            permissions = permissions.filter(p => p.role_id !== currentRole);
            rolePermissions.forEach(perm => {
                permissions.push({
                    role_id: currentRole,
                    ...perm
                });
            });
        } else {
            showToast('error', data.error);
        }
    })
    .catch(error => {
        showToast('error', 'An error occurred while saving permissions');
    });
}

// Toast notification function
function showToast(type, message) {
    // Implementation for toast notifications
    // Show toast notification
}

} // End of initializejQuery()

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    waitForjQuery();
});

</script>

<?php
// End output buffering and capture content
$content = ob_get_clean();

// Include the main layout with content
include __DIR__ . '/../layouts/app.php';
?>
