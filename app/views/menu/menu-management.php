<?php
$title = $title ?? 'Menu Management';
$current_page = 'menu-management';

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
                        <li class="breadcrumb-item active">Menu Management</li>
                    </ol>
                </div>
                <h4 class="page-title">Menu Management</h4>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card widget-flat">
                <div class="card-body">
                    <div class="float-end">
                        <i class="fas fa-puzzle-piece widget-icon bg-primary"></i>
                    </div>
                    <h5 class="text-muted fw-normal mt-0" title="Total Modules">Total Modules</h5>
                    <h3 class="mt-3 mb-3"><?php echo $stats['total_modules'] ?? 0; ?></h3>
                    <p class="mb-0 text-muted">
                        <span class="text-success me-2"><i class="mdi mdi-arrow-up-bold"></i> 5.27%</span>
                        <span class="text-nowrap">Since last month</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card widget-flat">
                <div class="card-body">
                    <div class="float-end">
                        <i class="fas fa-folder widget-icon bg-success"></i>
                    </div>
                    <h5 class="text-muted fw-normal mt-0" title="Menu Groups">Menu Groups</h5>
                    <h3 class="mt-3 mb-3"><?php echo $stats['total_groups'] ?? 0; ?></h3>
                    <p class="mb-0 text-muted">
                        <span class="text-success me-2"><i class="mdi mdi-arrow-up-bold"></i> 1.08%</span>
                        <span class="text-nowrap">Since last month</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card widget-flat">
                <div class="card-body">
                    <div class="float-end">
                        <i class="fas fa-shield-alt widget-icon bg-warning"></i>
                    </div>
                    <h5 class="text-muted fw-normal mt-0" title="Total Permissions">Total Permissions</h5>
                    <h3 class="mt-3 mb-3"><?php echo $stats['total_permissions'] ?? 0; ?></h3>
                    <p class="mb-0 text-muted">
                        <span class="text-danger me-2"><i class="mdi mdi-arrow-down-bold"></i> 7.00%</span>
                        <span class="text-nowrap">Since last month</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card widget-flat">
                <div class="card-body">
                    <div class="float-end">
                        <i class="fas fa-users widget-icon bg-info"></i>
                    </div>
                    <h5 class="text-muted fw-normal mt-0" title="Active Users">Active Users</h5>
                    <h3 class="mt-3 mb-3">24</h3>
                    <p class="mb-0 text-muted">
                        <span class="text-success me-2"><i class="mdi mdi-arrow-up-bold"></i> 8.87%</span>
                        <span class="text-nowrap">Since last month</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h5 class="card-title">Menu Management</h5>
                            <p class="text-muted">Manage your application's menu structure and permissions.</p>
                        </div>
                        <div class="col-md-6">
                            <div class="text-md-end">
                                <a href="<?php echo APP_URL; ?>/menu/builder" class="btn btn-primary me-2">
                                    <i class="fas fa-plus"></i> Menu Builder
                                </a>
                                <a href="<?php echo APP_URL; ?>/menu/permissions" class="btn btn-success me-2">
                                    <i class="fas fa-shield-alt"></i> Permissions
                                </a>
                                <button class="btn btn-info" onclick="exportConfig()">
                                    <i class="fas fa-download"></i> Export
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Menu Groups -->
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold">Menu Groups</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Modules</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($groups)): ?>
                                            <?php foreach ($groups as $group): ?>
                                                <tr>
                                                    <td>
                                                        <i class="<?php echo $group['icon']; ?> me-2"></i>
                                                        <?php echo htmlspecialchars($group['name']); ?>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-primary">
                                                            <?php echo count(array_filter($modules, function($m) use ($group) { return $m['parent_id'] == $group['id']; })); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm btn-outline-primary" onclick="editGroup(<?php echo $group['id']; ?>)">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-outline-danger" onclick="deleteGroup(<?php echo $group['id']; ?>)">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="3" class="text-center text-muted">No menu groups found</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Menu Modules -->
                        <div class="col-md-6">
                            <h6 class="fw-bold">Menu Modules</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Group</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($modules)): ?>
                                            <?php foreach ($modules as $module): ?>
                                                <tr>
                                                    <td>
                                                        <i class="<?php echo $module['menu_icon'] ?? 'fas fa-circle'; ?> me-2"></i>
                                                        <?php echo htmlspecialchars($module['caption']); ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($module['parent_id']): ?>
                                                            <?php 
                                                            $parentGroup = array_filter($groups, function($g) use ($module) { 
                                                                return $g['id'] == $module['parent_id']; 
                                                            });
                                                            $groupName = !empty($parentGroup) ? array_values($parentGroup)[0]['name'] : 'Unknown';
                                                            ?>
                                                            <span class="badge bg-secondary"><?php echo htmlspecialchars($groupName); ?></span>
                                                        <?php else: ?>
                                                            <span class="badge bg-light text-dark">Standalone</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm btn-outline-primary" onclick="editModule(<?php echo $module['id']; ?>)">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-outline-warning" onclick="toggleVisibility(<?php echo $module['id']; ?>)">
                                                            <i class="fas fa-eye<?php echo $module['is_menu_item'] ? '' : '-slash'; ?>"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="3" class="text-center text-muted">No modules found</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Group Modal -->
<div class="modal fade" id="groupModal" tabindex="-1" aria-labelledby="groupModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="groupModalLabel">Add Menu Group</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="groupForm">
                <div class="modal-body">
                    <input type="hidden" id="groupId" name="id">
                    <div class="mb-3">
                        <label for="groupName" class="form-label">Group Name</label>
                        <input type="text" class="form-control" id="groupName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="groupIcon" class="form-label">Icon</label>
                        <input type="text" class="form-control" id="groupIcon" name="icon" placeholder="fas fa-folder">
                    </div>
                    <div class="mb-3">
                        <label for="groupDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="groupDescription" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="isCollapsible" name="is_collapsible" checked>
                            <label class="form-check-label" for="isCollapsible">
                                Collapsible
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Group</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Module Modal -->
<div class="modal fade" id="moduleModal" tabindex="-1" aria-labelledby="moduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="moduleModalLabel">Edit Module</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="moduleForm">
                <div class="modal-body">
                    <input type="hidden" id="moduleId" name="id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="moduleCaption" class="form-label">Module Name</label>
                                <input type="text" class="form-control" id="moduleCaption" name="caption" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="moduleIcon" class="form-label">Icon</label>
                                <input type="text" class="form-control" id="moduleIcon" name="menu_icon">
                            </div>
                            <div class="mb-3">
                                <label for="moduleGroup" class="form-label">Group</label>
                                <select class="form-select" id="moduleGroup" name="parent_id">
                                    <option value="">Select Group</option>
                                    <?php foreach ($groups as $group): ?>
                                        <option value="<?php echo $group['id']; ?>"><?php echo htmlspecialchars($group['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="moduleDescription" class="form-label">Description</label>
                                <textarea class="form-control" id="moduleDescription" name="menu_description" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="isExternal" name="is_external">
                                    <label class="form-check-label" for="isExternal">
                                        External Link
                                    </label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="openInNewTab" name="open_in_new_tab">
                                    <label class="form-check-label" for="openInNewTab">
                                        Open in New Tab
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

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

// Global functions that can be called from onclick
function editGroup(id) {
    // Check if jQuery is available
    if (typeof $ === 'undefined') {
        console.error('jQuery is not loaded. Please refresh the page.');
        alert('Error: jQuery is not loaded. Please refresh the page.');
        return;
    }
    
    // Implementation for editing group
    $('#groupModalLabel').text('Edit Menu Group');
    $('#groupForm')[0].reset();
    $('#groupId').val(id);
    
    // Load group data and populate form
    // This would be implemented with AJAX call
    
    $('#groupModal').modal('show');
}

function deleteGroup(id) {
    if (confirm('Are you sure you want to delete this group? This will also delete all modules within this group.')) {
        // Delete group from database
        fetch('<?php echo APP_URL; ?>/menu/delete-group', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-Token': window.csrfToken || ''
            },
            body: JSON.stringify({
                id: id,
                _token: window.csrfToken || ''
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('success', data.message || 'Group deleted successfully');
                // Reload page to refresh the list
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                showToast('error', data.error || 'Failed to delete group');
            }
        })
        .catch(error => {
            console.error('Error deleting group:', error);
            showToast('error', 'An error occurred while deleting the group');
        });
    }
}

// Module management functions
function editModule(id) {
    // Check if jQuery is available
    if (typeof $ === 'undefined') {
        console.error('jQuery is not loaded. Please refresh the page.');
        alert('Error: jQuery is not loaded. Please refresh the page.');
        return;
    }
    
    $('#moduleModalLabel').text('Edit Module');
    $('#moduleForm')[0].reset();
    $('#moduleId').val(id);
    
    // Load module data and populate form
    // This would be implemented with AJAX call
    
    $('#moduleModal').modal('show');
}

function toggleVisibility(id) {
    // Implementation for toggling module visibility
    // Toggle visibility for module
}

// Export configuration
function exportConfig() {
    window.location.href = '<?php echo APP_URL; ?>/menu/export-config';
}

function initializejQuery() {
    // Form submissions
    $('#groupForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const groupId = $('#groupId').val();
        const url = groupId ? 
            '<?php echo APP_URL; ?>/menu/update-group' : 
            '<?php echo APP_URL; ?>/menu/create-group';
        
        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('success', data.message);
                $('#groupModal').modal('hide');
                location.reload();
            } else {
                showToast('error', data.error);
            }
        })
        .catch(error => {
            showToast('error', 'An error occurred');
        });
    });

    $('#moduleForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('<?php echo APP_URL; ?>/menu/update-module', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('success', data.message);
                $('#moduleModal').modal('hide');
                location.reload();
            } else {
                showToast('error', data.error);
            }
        })
        .catch(error => {
            showToast('error', 'An error occurred');
        });
    });
}

// Toast notification function
function showToast(type, message) {
    // Implementation for toast notifications
    // Show toast notification
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    waitForjQuery();
});

</script>

<?php
// End output buffering and capture content
$content = ob_get_clean();

// Generate CSRF token for AJAX requests
$csrf_token = Session::generateCSRF();

// Include the main layout with content
include __DIR__ . '/../layouts/app.php';
?>
