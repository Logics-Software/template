<?php
$title = $title ?? 'Menu Management';
$current_page = 'menu-management';

// Generate CSRF token for forms and AJAX requests
$csrf_token = Session::generateCSRF();

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
                        <div class="col-md-12">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold mb-0">Menu Groups</h6>
                                <button class="btn btn-primary btn-sm" onclick="addGroup()">
                                    <i class="fas fa-plus"></i> Add Group
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Item Menu</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($groups)): ?>
                                            <?php foreach ($groups as $group): ?>
                                                <tr class="group-row" data-group-id="<?php echo $group['id']; ?>">
                                                    <td>
                                                        <i class="<?php echo $group['icon']; ?> me-2"></i>
                                                        <?php echo htmlspecialchars($group['name']); ?>
                                                    </td>
                                                    <td>
                                                        <?php if (!empty($group['description'])): ?>
                                                            <span class="text-muted small">
                                                                <?php echo htmlspecialchars($group['description']); ?>
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="text-muted small fst-italic">No description</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-primary">
                                                            <?php echo $group['menu_items_count'] ?? 0; ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm btn-outline-success me-1" onclick="addDetailMenu(<?php echo $group['id']; ?>)" title="Add Detail Menu">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-outline-info me-1" onclick="toggleDetailMenu(<?php echo $group['id']; ?>)" title="View Detail Menu">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-outline-primary me-1" onclick="editGroup(<?php echo $group['id']; ?>)" title="Edit Group">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-outline-danger" onclick="deleteGroup(<?php echo $group['id']; ?>)" title="Delete Group">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                <!-- Expandable row for menu items -->
                                                <tr class="detail-row collapse" id="detail-row-<?php echo $group['id']; ?>">
                                                    <td colspan="4">
                                                        <div class="detail-content p-3 bg-light">
                                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                                <h6 class="mb-0">Menu Items in "<?php echo htmlspecialchars($group['name']); ?>"</h6>
                                                                <button class="btn btn-sm btn-outline-primary" onclick="addMenuItemToGroup(<?php echo $group['id']; ?>)">
                                                                    <i class="fas fa-plus"></i> Add Item
                                                                </button>
                                                            </div>
                                                            <div class="menu-items-list" id="menu-items-<?php echo $group['id']; ?>">
                                                                <div class="text-center text-muted py-3">
                                                                    <i class="fas fa-spinner fa-spin"></i> Loading menu items...
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">No menu groups found</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Menu Modules -->
                        <!-- <div class="col-md-6">
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
                        </div> -->
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
                    <input type="hidden" name="_token" value="<?php echo $csrf_token; ?>">
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
                    <input type="hidden" name="_token" value="<?php echo $csrf_token; ?>">
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
    fetch('<?php echo APP_URL; ?>/menu/get-group/' + id, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.group) {
            // Populate form fields with group data
            $('#groupName').val(data.group.name);
            $('#groupIcon').val(data.group.icon);
            $('#groupDescription').val(data.group.description);
            $('#isCollapsible').prop('checked', data.group.is_collapsible == 1);
        } else {
            console.error('Failed to load group data:', data.error);
            showToast('error', 'Failed to load group data');
        }
    })
    .catch(error => {
        console.error('Error loading group data:', error);
        showToast('error', 'An error occurred while loading group data');
    });
    
    $('#groupModal').modal('show');
}

function addGroup() {
    // Check if jQuery is available
    if (typeof $ === 'undefined') {
        console.error('jQuery is not loaded. Please refresh the page.');
        alert('Error: jQuery is not loaded. Please refresh the page.');
        return;
    }
    
    // Reset form and show modal for adding new group
    $('#groupModalLabel').text('Add Menu Group');
    $('#groupForm')[0].reset();
    $('#groupId').val(''); // Clear ID field
    
    $('#groupModal').modal('show');
}

function addDetailMenu(groupId) {
    // Check if jQuery is available
    if (typeof $ === 'undefined') {
        console.error('jQuery is not loaded. Please refresh the page.');
        alert('Error: jQuery is not loaded. Please refresh the page.');
        return;
    }
    
    // Redirect to Menu Builder with group parameter
    window.location.href = '<?php echo APP_URL; ?>/menu/builder?group_id=' + groupId;
}

function toggleDetailMenu(groupId) {
    // Check if jQuery is available
    if (typeof $ === 'undefined') {
        console.error('jQuery is not loaded. Please refresh the page.');
        alert('Error: jQuery is not loaded. Please refresh the page.');
        return;
    }
    
    const detailRow = $(`#detail-row-${groupId}`);
    const menuItemsContainer = $(`#menu-items-${groupId}`);
    const toggleButton = $(`.btn[onclick="toggleDetailMenu(${groupId})"]`);
    
    if (detailRow.hasClass('show')) {
        // Collapse the row
        detailRow.collapse('hide');
        toggleButton.find('i').removeClass('fa-eye-slash').addClass('fa-eye');
        toggleButton.attr('title', 'View Detail Menu');
    } else {
        // Expand the row
        detailRow.collapse('show');
        toggleButton.find('i').removeClass('fa-eye').addClass('fa-eye-slash');
        toggleButton.attr('title', 'Hide Detail Menu');
        
        // Load menu items if not already loaded
        if (menuItemsContainer.find('.fa-spinner').length > 0) {
            loadMenuItemsForGroup(groupId);
        }
    }
}

function loadMenuItemsForGroup(groupId) {
    const menuItemsContainer = $(`#menu-items-${groupId}`);
    
    // Show loading state
    menuItemsContainer.html(`
        <div class="text-center text-muted py-3">
            <i class="fas fa-spinner fa-spin"></i> Loading menu items...
        </div>
    `);
    
    // Fetch menu items for this group
    fetch(`<?php echo APP_URL; ?>/menu/get-group-items/${groupId}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.menuItems) {
            renderMenuItems(menuItemsContainer, data.menuItems);
        } else {
            menuItemsContainer.html(`
                <div class="text-center text-muted py-3">
                    <i class="fas fa-folder-open"></i>
                    <p class="mb-0">No menu items found in this group</p>
                </div>
            `);
        }
    })
    .catch(error => {
        console.error('Error loading menu items:', error);
        menuItemsContainer.html(`
            <div class="text-center text-danger py-3">
                <i class="fas fa-exclamation-triangle"></i>
                <p class="mb-0">Error loading menu items</p>
            </div>
        `);
    });
}

function renderMenuItems(container, menuItems) {
    if (menuItems.length === 0) {
        container.html(`
            <div class="text-center text-muted py-3">
                <i class="fas fa-folder-open"></i>
                <p class="mb-0">No menu items found in this group</p>
            </div>
        `);
        return;
    }
    
    // Build hierarchical structure
    const menuMap = {};
    const rootItems = [];
    
    // First pass: create map of all items
    menuItems.forEach(item => {
        menuMap[item.id] = {
            ...item,
            children: []
        };
    });
    
    // Second pass: build hierarchy
    menuItems.forEach(item => {
        if (item.parent_id && menuMap[item.parent_id]) {
            menuMap[item.parent_id].children.push(menuMap[item.id]);
        } else {
            rootItems.push(menuMap[item.id]);
        }
    });
    
    // Sort root items by sort_order
    rootItems.sort((a, b) => (a.sort_order || 0) - (b.sort_order || 0));
    
    // Sort children recursively
    function sortChildren(items) {
        items.forEach(item => {
            if (item.children && item.children.length > 0) {
                item.children.sort((a, b) => (a.sort_order || 0) - (b.sort_order || 0));
                sortChildren(item.children);
            }
        });
    }
    sortChildren(rootItems);
    
    // Render hierarchical structure
    let html = `<div class="menu-modules">`;
    
    function renderItem(item, level = 0) {
        const isActive = item.is_active ? '' : 'menu-inactive';
        const hasChildren = item.children && item.children.length > 0;
        const parentClass = hasChildren ? 'menu-parent-item' : '';
        const indentClass = level > 0 ? 'menu-child-indent' : '';
        
        // Add indentation for child items
        const indentation = level > 0 ? '<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>' : '';
        
        html += `
            <div class="menu-module ${indentClass} ${isActive} ${parentClass}" data-menu-item-id="${item.id}">
                <div class="menu-module-info">
                    <div class="menu-item-content">
                        <div class="menu-item-main">
                            ${indentation}<i class="${item.icon || 'fas fa-circle'} me-2"></i>
                            <span class="menu-item-name">${item.name}</span>
                            ${hasChildren ? '<i class="fas fa-chevron-down ms-2 parent-indicator"></i>' : ''}
                        </div>
                        <div class="menu-item-meta">
                            ${!item.is_active ? '<span class="badge bg-secondary badge-sm me-1">Inactive</span>' : ''}
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Render children if they exist
        if (hasChildren) {
            item.children.forEach(child => {
                renderItem(child, level + 1);
            });
        }
    }
    
    // Render all root items and their children
    rootItems.forEach(item => {
        renderItem(item);
    });
    
    html += `</div>`;
    
    container.html(html);
}


function addMenuItemToGroup(groupId) {
    // Redirect to menu builder with group parameter
    window.location.href = `<?php echo APP_URL; ?>/menu/builder?group_id=${groupId}`;
}

function deleteGroup(id) {
    if (confirm('Are you sure you want to delete this group? This will also delete all modules within this group.')) {
        // Delete group from database
        fetch('<?php echo APP_URL; ?>/menu/delete-group', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-Token': '<?php echo $csrf_token; ?>'
            },
            body: JSON.stringify({
                id: id,
                _token: '<?php echo $csrf_token; ?>'
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

<style>
/* Menu Preview Styling */
.menu-preview-container {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    background: #f8f9fa;
}

.menu-preview-header {
    background: #e9ecef;
    padding: 12px 16px;
    border-bottom: 1px solid #dee2e6;
    border-radius: 8px 8px 0 0;
}

.menu-preview-body {
    padding: 16px;
    background: white;
    border-radius: 0 0 8px 8px;
}

.sidebar-menu-preview {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}

.sidebar-menu-preview .nav {
    gap: 0;
}

.menu-item-wrapper {
    position: relative;
    border: 1px solid #e9ecef;
    border-radius: 6px;
    margin-bottom: 4px;
    background: white;
    transition: all 0.2s ease;
}

.menu-item-wrapper:hover {
    border-color: #007bff;
    box-shadow: 0 2px 4px rgba(0,123,255,0.1);
}

.menu-item-wrapper.disabled {
    opacity: 0.6;
    background: #f8f9fa;
}

.menu-item-content {
    padding: 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.menu-item-main {
    display: flex;
    align-items: center;
    flex: 1;
}

.menu-icon {
    width: 20px;
    text-align: center;
    margin-right: 8px;
    color: #6c757d;
    font-size: 14px;
}

.menu-text {
    font-weight: 500;
    color: #495057;
    font-size: 14px;
}

.menu-arrow {
    margin-left: auto;
    margin-right: 8px;
    font-size: 12px;
    color: #6c757d;
}

.menu-item-actions {
    display: flex;
    gap: 4px;
    opacity: 0;
    transition: opacity 0.2s ease;
}

.menu-item-wrapper:hover .menu-item-actions {
    opacity: 1;
}

.menu-item-actions .btn-xs {
    padding: 2px 6px;
    font-size: 10px;
    line-height: 1.2;
}

.menu-description {
    padding: 0 12px 8px 12px;
    font-size: 12px;
    color: #6c757d;
    font-style: italic;
}

.menu-meta {
    padding: 0 12px 12px 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.menu-meta .badge-sm {
    font-size: 10px;
    padding: 2px 6px;
}

.menu-order {
    font-size: 11px;
    color: #6c757d;
}

.sidebar-menu-preview .nav-pills .nav-item {
    margin-bottom: 0;
}

.sidebar-menu-preview .nav-pills .nav-link {
    padding: 0;
    border-radius: 0;
    background: none;
}

/* Child menu items indentation */
.sidebar-menu-preview ul ul {
    margin-top: 4px;
}

.sidebar-menu-preview ul ul .menu-item-wrapper {
    border-left: 3px solid #e9ecef;
    margin-left: 0;
}

.sidebar-menu-preview ul ul ul .menu-item-wrapper {
    border-left-color: #dee2e6;
}

/* Menu Builder Style for Menu Management */
.menu-modules .menu-module {
    border: 1px solid #e9ecef;
    border-radius: 6px;
    margin-bottom: 4px;
    background: white;
    transition: all 0.2s ease;
}

.menu-modules .menu-module:hover {
    border-color: #007bff;
    box-shadow: 0 2px 4px rgba(0,123,255,0.1);
}

.menu-modules .menu-child-indent {
    margin-left: 30px !important;
    border-left: 3px solid #e9ecef;
    padding-left: 16px !important;
    background: #f8f9fa;
    position: relative;
}

.menu-modules .menu-inactive {
    opacity: 0.7;
    background: #f8f9fa;
}

.menu-modules .menu-inactive .menu-item-name {
    color: #6c757d;
    text-decoration: line-through;
}

.menu-modules .menu-module-info {
    padding: 0px;
}

.menu-modules .menu-item-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
}

.menu-modules .menu-item-main {
    display: flex;
    align-items: center;
    flex: 1;
}

.menu-modules .menu-item-name {
    font-weight: 500;
    color: #495057;
    font-size: 14px;
}

.menu-modules .menu-item-meta {
    display: flex;
    align-items: center;
    gap: 4px;
}

.menu-modules .badge-sm {
    font-size: 10px;
    padding: 2px 6px;
}

.menu-modules .menu-module-actions {
    display: flex;
    gap: 4px;
    opacity: 0;
    transition: opacity 0.2s ease;
}

.menu-modules .menu-module:hover .menu-module-actions {
    opacity: 1;
}

.menu-modules .menu-module-actions .btn {
    padding: 4px 8px;
    font-size: 12px;
}

.menu-modules .menu-parent-item {
    border-left: 4px solid #007bff;
}

.menu-modules .menu-parent-item .menu-item-name {
    font-weight: 600;
}

.menu-modules .parent-indicator {
    color: #28a745;
    font-size: 14px;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}
</style>

<?php
// End output buffering and capture content
$content = ob_get_clean();

// Include the main layout with content
include __DIR__ . '/../layouts/app.php';
?>
