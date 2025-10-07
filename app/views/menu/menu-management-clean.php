<?php
$title = $title ?? 'Menu Management';
$current_page = 'menu-management';
// Generate CSRF token for forms and AJAX requests
$csrf_token = Session::generateCSRF();
// Start output buffering to capture content
ob_start();
?>
<!-- Action Buttons -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Menu Management</h5>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="<?php echo APP_URL; ?>/dashboard" class="text-decoration-none">Home</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Menu Management</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="card-body">
                <!-- Menu Groups -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0"><i class="fas fa-server me-2"></i> Group Menu</h6>
                            <button class="btn btn-sm btn-primary" onclick="addGroup()">
                                <i class="fas fa-plus"></i> Tambah Group Menu
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
                                                    <div class="d-flex align-items-center">
                                                        <i class="<?php echo $group['icon'] ?? 'fas fa-folder'; ?> me-2"></i>
                                                        <span class="fw-medium"><?php echo htmlspecialchars($group['name']); ?></span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="text-muted"><?php echo htmlspecialchars($group['description'] ?? ''); ?></span>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary" onclick="toggleDetailMenu(<?php echo $group['id']; ?>)">
                                                        <i class="fas fa-eye"></i> View Detail Menu
                                                    </button>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <button class="btn btn-outline-primary" onclick="editGroup(<?php echo $group['id']; ?>)">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-outline-success" onclick="addDetailMenu(<?php echo $group['id']; ?>)">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                        <button class="btn btn-outline-danger" onclick="deleteGroup(<?php echo $group['id']; ?>)">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr id="detail-row-<?php echo $group['id']; ?>" class="detail-row" style="display: none;">
                                                <td colspan="4">
                                                    <div class="p-3 bg-light">
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                            <h6 class="mb-0">Menu Items in this Group</h6>
                                                            <button class="btn btn-sm btn-primary" onclick="addDetailMenu(<?php echo $group['id']; ?>)">
                                                                <i class="fas fa-plus"></i> Add Menu Item
                                                            </button>
                                                        </div>
                                                        <div id="menu-items-<?php echo $group['id']; ?>" class="menu-items-container">
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
                                            <td colspan="4" class="text-center text-muted py-4">
                                                <i class="fas fa-folder-open"></i>
                                                <p class="mb-0">No menu groups found</p>
                                            </td>
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

<!-- Group Modal -->
<div class="modal fade" id="groupModal" tabindex="-1" aria-labelledby="groupModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="groupModalLabel">Add Menu Group</h5>
                <button type="button" class="btn-close" onclick="closeGroupModal()"></button>
            </div>
            <form id="groupForm">
                <div class="modal-body">
                    <input type="hidden" id="groupId" name="id">
                    <div class="mb-3">
                        <label for="groupName" class="form-label">Group Name</label>
                        <input type="text" class="form-control" id="groupName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="groupDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="groupDescription" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="groupIcon" class="form-label">Icon</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="groupIcon" name="icon" value="fas fa-folder" readonly>
                            <button type="button" class="btn btn-outline-secondary" onclick="openIconPicker()">
                                <i class="fas fa-search"></i> Choose Icon
                            </button>
                        </div>
                        <div class="mt-2">
                            <div class="d-flex align-items-center">
                                <i id="iconPreview" class="fas fa-folder me-2"></i>
                                <span id="iconClassDisplay" class="text-muted">fas fa-folder</span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="isCollapsible" name="is_collapsible" value="1">
                            <label class="form-check-label" for="isCollapsible">
                                Collapsible Group
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeGroupModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Group</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Icon Picker Modal -->
<div class="modal fade" id="iconPickerModal" tabindex="-1" aria-labelledby="iconPickerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="iconPickerModalLabel">Choose Icon</h5>
                <button type="button" class="btn-close" onclick="closeIconPickerModal()"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <div class="input-group">
                        <input type="text" class="form-control" id="iconSearch" placeholder="Search icons...">
                        <button class="btn btn-outline-secondary" type="button" id="clearSearch">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div id="iconPickerContainer" class="icon-picker-container">
                    <!-- Icons will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeIconPickerModal()">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="selectIcon()">Select Icon</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this group? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing menu management...');
    initializeMenuManagement();
});

// Global functions that can be called from onclick
window.editGroup = function(id) {
    const modalLabel = document.getElementById('groupModalLabel');
    const groupForm = document.getElementById('groupForm');
    const groupIdField = document.getElementById('groupId');
    
    if (modalLabel) modalLabel.textContent = 'Edit Menu Group';
    if (groupForm) groupForm.reset();
    if (groupIdField) groupIdField.value = id;

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
            const groupName = document.getElementById('groupName');
            const groupIcon = document.getElementById('groupIcon');
            const groupDescription = document.getElementById('groupDescription');
            const isCollapsible = document.getElementById('isCollapsible');
            
            if (groupName) groupName.value = data.group.name;
            if (groupIcon) groupIcon.value = data.group.icon || 'fas fa-folder';
            if (groupDescription) groupDescription.value = data.group.description;
            if (isCollapsible) isCollapsible.checked = data.group.is_collapsible == 1;

            // Update icon display
            const iconClass = data.group.icon || 'fas fa-folder';
            const iconPreview = document.getElementById('iconPreview');
            const iconClassDisplay = document.getElementById('iconClassDisplay');
            
            if (iconPreview) iconPreview.className = iconClass;
            if (iconClassDisplay) iconClassDisplay.textContent = iconClass;
        } else {
            console.error('Failed to load group data:', data.error);
            showToast('error', 'Failed to load group data');
        }
    })
    .catch(error => {
        console.error('Error loading group data:', error);
        showToast('error', 'An error occurred while loading group data');
    });

    // Show modal using Bootstrap Modal API
    const groupModal = document.getElementById('groupModal');
    if (groupModal) {
        const modal = new bootstrap.Modal(groupModal);
        modal.show();
    }
};

window.addGroup = function() {
    console.log('addGroup() called');
    
    // Reset form and show modal for adding new group
    const modalLabel = document.getElementById('groupModalLabel');
    const groupForm = document.getElementById('groupForm');
    const groupIdField = document.getElementById('groupId');
    
    if (modalLabel) modalLabel.textContent = 'Add Menu Group';
    if (groupForm) groupForm.reset();
    if (groupIdField) groupIdField.value = '';
    
    // Reset icon display
    const groupIcon = document.getElementById('groupIcon');
    const iconPreview = document.getElementById('iconPreview');
    const iconClassDisplay = document.getElementById('iconClassDisplay');
    
    if (groupIcon) groupIcon.value = 'fas fa-folder';
    if (iconPreview) iconPreview.className = 'fas fa-folder';
    if (iconClassDisplay) iconClassDisplay.textContent = 'fas fa-folder';
    
    console.log('Form reset completed');
    
    // Show modal using Bootstrap Modal API
    const groupModal = document.getElementById('groupModal');
    if (groupModal) {
        const modal = new bootstrap.Modal(groupModal);
        modal.show();
    }
    
    console.log('Modal displayed successfully');
};

window.addDetailMenu = function(groupId) {
    // Redirect to Menu Builder with group parameter
    window.location.href = '<?php echo APP_URL; ?>/menu/builder?group_id=' + groupId;
};

// Close Group Modal
window.closeGroupModal = function() {
    const groupModal = document.getElementById('groupModal');
    if (groupModal) {
        const modal = bootstrap.Modal.getInstance(groupModal);
        if (modal) {
            modal.hide();
        }
    }
};

// Close Icon Picker Modal
window.closeIconPickerModal = function() {
    const iconPickerModal = document.getElementById('iconPickerModal');
    if (iconPickerModal) {
        const modal = bootstrap.Modal.getInstance(iconPickerModal);
        if (modal) {
            modal.hide();
        }
    }
};

// Make toggleDetailMenu globally available
window.toggleDetailMenu = function(groupId) {
    console.log('toggleDetailMenu called with groupId:', groupId);
    
    const detailRow = document.getElementById(`detail-row-${groupId}`);
    const menuItemsContainer = document.getElementById(`menu-items-${groupId}`);
    const toggleButton = document.querySelector(`.btn[onclick="toggleDetailMenu(${groupId})"]`);

    if (!detailRow || !menuItemsContainer || !toggleButton) {
        console.error('Required elements not found');
        return;
    }

    if (detailRow.classList.contains('show')) {
        // Hide the row with animation
        detailRow.style.transition = 'all 0.3s ease';
        detailRow.style.maxHeight = '0';
        detailRow.style.overflow = 'hidden';
        detailRow.style.opacity = '0';
        
        setTimeout(() => {
            detailRow.classList.remove('show');
            detailRow.style.display = 'none';
        }, 300);
        
        const icon = toggleButton.querySelector('i');
        if (icon) {
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
        toggleButton.setAttribute('title', 'View Detail Menu');
    } else {
        // Show the row with animation
        detailRow.style.display = 'table-row';
        detailRow.style.transition = 'all 0.3s ease';
        detailRow.style.maxHeight = 'none';
        detailRow.style.overflow = 'visible';
        detailRow.style.opacity = '1';
        
        setTimeout(() => {
            detailRow.classList.add('show');
        }, 10);
        
        const icon = toggleButton.querySelector('i');
        if (icon) {
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        }
        toggleButton.setAttribute('title', 'Hide Detail Menu');

        // Load menu items if not already loaded
        if (menuItemsContainer.querySelector('.fa-spinner')) {
            loadMenuItemsForGroup(groupId);
        }
    }
};

function loadMenuItemsForGroup(groupId) {
    const menuItemsContainer = document.getElementById(`menu-items-${groupId}`);

    if (!menuItemsContainer) {
        console.error('Menu items container not found');
        return;
    }

    // Show loading state
    menuItemsContainer.innerHTML = `
        <div class="text-center text-muted py-3">
            <i class="fas fa-spinner fa-spin"></i> Loading menu items...
        </div>
    `;

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
            menuItemsContainer.innerHTML = `
                <div class="text-center text-muted py-3">
                    <i class="fas fa-folder-open"></i>
                    <p class="mb-0">No menu items found in this group</p>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error loading menu items:', error);
        menuItemsContainer.innerHTML = `
            <div class="text-center text-danger py-3">
                <i class="fas fa-exclamation-triangle"></i>
                <p class="mb-0">Error loading menu items</p>
            </div>
        `;
    });
}

function renderMenuItems(container, menuItems) {
    if (menuItems.length === 0) {
        container.innerHTML = `
            <div class="text-center text-muted py-3">
                <i class="fas fa-folder-open"></i>
                <p class="mb-0">No menu items found in this group</p>
            </div>
        `;
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

        html += `
            <div class="menu-module ${indentClass} ${isActive} ${parentClass}" data-menu-item-id="${item.id}">
                <div class="menu-module-info">
                    <div class="menu-item-content">
                        <div class="menu-item-main">
                            <i class="${item.icon || 'fas fa-circle'} me-2"></i>
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

    container.innerHTML = html;
}

function addMenuItemToGroup(groupId) {
    // Redirect to menu builder with group parameter
    window.location.href = `<?php echo APP_URL; ?>/menu/builder?group_id=${groupId}`;
}

let deleteGroupId = null;
window.deleteGroup = function(id) {
    deleteGroupId = id;
    const modal = new bootstrap.Modal(document.getElementById("deleteModal"));
    modal.show();
};

// Delete group confirmation
document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("confirmDelete").addEventListener("click", function() {
        if (deleteGroupId) {
            // Delete group from database
            fetch('<?php echo APP_URL; ?>/menu/delete-group', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-Token': '<?php echo $csrf_token; ?>'
                },
                body: JSON.stringify({
                    id: deleteGroupId,
                    _token: '<?php echo $csrf_token; ?>'
                })
            })
            .then(response => {
                if (response.status === 403) {
                    showToast('error', 'Access denied. Please refresh the page and try again.');
                    return;
                }
                return response.json();
            })
            .then(data => {
                if (data && data.success) {
                    showToast('success', data.message || 'Group deleted successfully');
                    // Hide modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById("deleteModal"));
                    modal.hide();
                    // Reload page to refresh the list
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else if (data && data.error) {
                    showToast('error', data.error || 'Failed to delete group');
                }
            })
            .catch(error => {
                console.error('Error deleting group:', error);
                showToast('error', 'An error occurred while deleting the group');
            });
        }
    });
});

// Export configuration
function exportConfig() {
    window.location.href = '<?php echo APP_URL; ?>/menu/export-config';
}

function initializeMenuManagement() {
    console.log('Initializing menu management functions...');
    
    // Form submissions
    const groupForm = document.getElementById('groupForm');
    if (groupForm) {
        groupForm.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Group form submitted');
            
            const formData = new FormData(this);
            const groupId = document.getElementById('groupId').value;
            const url = groupId ? 
                '<?php echo APP_URL; ?>/menu/update-group' : 
                '<?php echo APP_URL; ?>/menu/create-group';
            
            console.log('Submitting to:', url);
            
            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-Token': '<?php echo $csrf_token; ?>'
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (response.status === 403) {
                    showToast('error', 'Access denied. Please refresh the page and try again.');
                    return;
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data && data.success) {
                    showToast('success', data.message);
                    // Hide modal using Bootstrap Modal API
                    const modal = bootstrap.Modal.getInstance(document.getElementById('groupModal'));
                    if (modal) {
                        modal.hide();
                    }
                    location.reload();
                } else if (data && data.error) {
                    showToast('error', data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('error', 'An error occurred while saving the group.');
            });
        });
    }
    
    console.log('Menu management initialization completed');
}

// Icon picker functions
let selectedIconData = null;

window.openIconPicker = function() {
    console.log('openIconPicker() called');
    
    // Load icon picker content
    const container = document.getElementById('iconPickerContainer');
    if (!container) {
        console.error('Icon picker container not found');
        return;
    }

    // Show loading state
    container.innerHTML = `
        <div class="text-center py-4">
            <i class="fas fa-spinner fa-spin"></i>
            <p class="mb-0 mt-2">Loading icons...</p>
        </div>
    `;

    // Load icons from server
    fetch('<?php echo APP_URL; ?>/menu/get-icons', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.icons) {
            renderIconPicker(data.icons);
        } else {
            container.innerHTML = `
                <div class="text-center text-danger py-4">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p class="mb-0 mt-2">Failed to load icons</p>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error loading icons:', error);
        container.innerHTML = `
            <div class="text-center text-danger py-4">
                <i class="fas fa-exclamation-triangle"></i>
                <p class="mb-0 mt-2">Error loading icons</p>
            </div>
        `;
    });

    // Show modal using Bootstrap Modal API
    const iconPickerModal = document.getElementById('iconPickerModal');
    if (iconPickerModal) {
        const modal = new bootstrap.Modal(iconPickerModal, {
            backdrop: 'static',
            keyboard: false
        });
        modal.show();
    }
};

function renderIconPicker(icons) {
    const container = document.getElementById('iconPickerContainer');
    if (!container) return;

    let html = '<div class="row">';
    
    icons.forEach(icon => {
        html += `
            <div class="col-md-3 col-sm-4 col-6 mb-3">
                <div class="icon-item d-flex align-items-center p-2 border rounded cursor-pointer" 
                     data-icon="${icon.class}" 
                     data-label="${icon.label}"
                     data-category="${icon.category}">
                    <i class="${icon.class} me-2"></i>
                    <span class="small">${icon.label}</span>
                </div>
            </div>
        `;
    });
    
    html += '</div>';
    container.innerHTML = html;

    // Icon selection using event delegation
    document.addEventListener('click', function(e) {
        if (e.target.closest('.icon-item')) {
            const iconItem = e.target.closest('.icon-item');
            const allIconItems = document.querySelectorAll('.icon-item');

            // Remove selected class from all items
            allIconItems.forEach(i => i.classList.remove('selected'));

            // Add selected class to clicked item
            iconItem.classList.add('selected');

            // Store selected icon data
            selectedIconData = {
                class: iconItem.dataset.icon,
                label: iconItem.dataset.label,
                category: iconItem.dataset.category
            };

            console.log('Icon selected:', selectedIconData);
        }
    });
}

window.selectIcon = function() {
    if (!selectedIconData) {
        showToast('warning', 'Please select an icon first');
        return;
    }

    // Update form fields
    const groupIcon = document.getElementById('groupIcon');
    const iconPreview = document.getElementById('iconPreview');
    const iconClassDisplay = document.getElementById('iconClassDisplay');

    if (groupIcon) groupIcon.value = selectedIconData.class;
    if (iconPreview) iconPreview.className = selectedIconData.class;
    if (iconClassDisplay) iconClassDisplay.textContent = selectedIconData.class;

    // Close modal
    const iconPickerModal = document.getElementById('iconPickerModal');
    if (iconPickerModal) {
        const modal = bootstrap.Modal.getInstance(iconPickerModal);
        if (modal) {
            modal.hide();
        }
    }

    showToast('success', 'Icon selected successfully');
};

// Toast notification functions
function showToast(type, message) {
    const toastContainer = document.getElementById('toast-container') || createToastContainer();
    
    const iconClass = {
        'success': 'fas fa-check-circle text-success',
        'error': 'fas fa-exclamation-circle text-danger',
        'warning': 'fas fa-exclamation-triangle text-warning',
        'info': 'fas fa-info-circle text-info'
    }[type] || 'fas fa-info-circle text-info';

    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type === 'error' ? 'danger' : type} border-0`;
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');
    
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-${iconClass.split(' ')[1].replace('fa-', '')} me-2"></i>
                ${message}
                <button type="button" class="btn-close btn-close-white ms-auto" onclick="this.parentElement.parentElement.parentElement.remove()"></button>
            </div>
        </div>
    `;
    
    toastContainer.appendChild(toast);
    
    // Show toast
    const bsToast = new bootstrap.Toast(toast, {
        autohide: true,
        delay: 5000
    });
    bsToast.show();
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (toast.parentNode) {
            toast.remove();
        }
    }, 5000);
}

function createToastContainer() {
    const container = document.createElement('div');
    container.id = 'toast-container';
    container.className = 'toast-container position-fixed top-0 end-0 p-3';
    container.style.zIndex = '9999';
    document.body.appendChild(container);
    return container;
}
</script>

<?php
// End output buffering and get content
$content = ob_get_clean();
?>
