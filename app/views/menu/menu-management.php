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
                                                        <i class="fas fa-sliders-h"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-info me-1" onclick="toggleDetailMenu(<?php echo $group['id']; ?>)" title="View Detail Menu">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-warning me-1" onclick="editGroup(<?php echo $group['id']; ?>)" title="Edit Group">
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
                                                            <h6 class="mb-0">Struktur Menu "<?php echo htmlspecialchars($group['name']); ?></h6>
                                                            <button class="btn btn-sm btn-primary" onclick="addMenuItemToGroup(<?php echo $group['id']; ?>)">
                                                                <i class="fas fa-plus"></i> Tambah/Edit Item Menu
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
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Group Modal -->
<div class="modal fade" id="groupModal" tabindex="-1" aria-labelledby="groupModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content m-0">
            <div class="modal-header m-0 p-4 bg-secondary">
                <h5 class="modal-title" id="groupModalLabel">Add Menu Group</h5>
                <button type="button" class="btn-close" onclick="closeGroupModal()" aria-label="Close"></button>
            </div>
            <form id="groupForm">
                <div class="modal-body m-3">
                    <input type="hidden" id="groupId" name="id">
                    <input type="hidden" name="_token" value="<?php echo $csrf_token; ?>">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="groupName" name="name" placeholder="Group Name" required>
                        <label for="groupName">Group Name <span class="text-danger">*</span></label>
                    </div>
                    <div class="mb-3">
                        <label for="groupIcon" class="form-label">
                            <i class="fas fa-palette me-1"></i> Icon
                        </label>
                        <div class="icon-input-container">
                            <div class="selected-icon-display" id="selectedIconDisplay">
                                <div class="icon-preview">
                                    <i id="iconPreview" class="fas fa-folder"></i>
                                </div>
                                <div class="icon-info">
                                    <div class="icon-name">Select Icon</div>
                                    <div class="icon-class" id="iconClassDisplay">fas fa-folder</div>
                                </div>
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="openIconPicker()">
                                    <i class="fas fa-search me-1"></i> Choose Icon
                                </button>
                            </div>
                            <input type="hidden" id="groupIcon" name="icon" value="fas fa-folder">
                        </div>
                    </div>
                    <div class="form-floating mb-3">
                        <textarea class="form-control" id="groupDescription" name="description" placeholder="Description" class="textarea-100"></textarea>
                        <label for="groupDescription">Description</label>
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
                <div class="modal-footer m-0 p-3 bg-secondary">
                    <button type="button" class="btn btn-secondary" onclick="closeGroupModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Group</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Icon Picker Modal -->
<div class="modal fade" id="iconPickerModal" tabindex="-1" aria-labelledby="iconPickerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="iconPickerModalLabel">
                    <i class="fas fa-palette me-2"></i> Choose Icon
                </h5>
                <button type="button" class="btn-close" onclick="closeIconPickerModal()" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="iconPickerContainer">
                    <!-- Icon picker will be loaded here -->
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
<div class="modal fade" id="deleteModal" tabindex="-1">
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
// Wait for jQuery to be loaded
function waitForjQuery() {
    if (typeof $ === 'undefined') {
        // Waiting for jQuery to load...
        setTimeout(waitForjQuery, 100);
        return;
    }
    
    // jQuery loaded successfully
    console.log('jQuery loaded successfully, initializing...');
    initializejQuery();
}
// Global functions that can be called from onclick
window.editGroup = function(id) {
    // Implementation for editing group
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
            const iconName = document.querySelector('.icon-name');
            
            if (iconPreview) iconPreview.className = iconClass;
            if (iconClassDisplay) iconClassDisplay.textContent = iconClass;
            if (iconName) iconName.textContent = 'Selected Icon';
        } else {
            console.error('Failed to load group data:', data.error);
            showToast('error', 'Failed to load group data');
        }
    })
    .catch(error => {
        console.error('Error loading group data:', error);
        showToast('error', 'An error occurred while loading group data');
    });

    // Show modal using vanilla JavaScript
    const groupModal = document.getElementById('groupModal');
    if (groupModal) {
        groupModal.classList.add('show');
        groupModal.style.display = 'flex';
        groupModal.style.alignItems = 'center';
        groupModal.style.justifyContent = 'center';
        groupModal.style.minHeight = '100vh';
        groupModal.style.zIndex = '1055';
        groupModal.style.opacity = '1';
        
        // Ensure modal dialog is visible
        const modalDialog = groupModal.querySelector('.modal-dialog');
        if (modalDialog) {
            modalDialog.style.zIndex = '1056';
            modalDialog.style.position = 'relative';
            modalDialog.style.margin = '0';
            modalDialog.style.maxWidth = '500px';
            modalDialog.style.width = '90%';
        }
    }

    document.body.classList.add('modal-open');

    // Add backdrop
    if (!document.querySelector('.modal-backdrop')) {
        const backdrop = document.createElement('div');
        backdrop.className = 'modal-backdrop fade show';
        backdrop.style.zIndex = '1050';
        document.body.appendChild(backdrop);
    }
}
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
    const iconName = document.querySelector('.icon-name');
    
    if (groupIcon) groupIcon.value = 'fas fa-folder';
    if (iconPreview) iconPreview.className = 'fas fa-folder';
    if (iconClassDisplay) iconClassDisplay.textContent = 'fas fa-folder';
    if (iconName) iconName.textContent = 'Select Icon';
    
    console.log('Form reset completed');
    
    // Show modal using vanilla JavaScript
    const groupModal = document.getElementById('groupModal');
    if (groupModal) {
        groupModal.classList.add('show');
        groupModal.style.display = 'flex';
        groupModal.style.alignItems = 'center';
        groupModal.style.justifyContent = 'center';
        groupModal.style.minHeight = '100vh';
        groupModal.style.zIndex = '1055';
        groupModal.style.opacity = '1';
        
        // Ensure modal dialog is visible
        const modalDialog = groupModal.querySelector('.modal-dialog');
        if (modalDialog) {
            modalDialog.style.zIndex = '1056';
            modalDialog.style.position = 'relative';
            modalDialog.style.margin = '0';
            modalDialog.style.maxWidth = '500px';
            modalDialog.style.width = '90%';
        }
    }
    
    document.body.classList.add('modal-open');
    
    // Add backdrop
    if (!document.querySelector('.modal-backdrop')) {
        const backdrop = document.createElement('div');
        backdrop.className = 'modal-backdrop fade show';
        backdrop.style.zIndex = '1050';
        document.body.appendChild(backdrop);
    }
    
    console.log('Modal displayed successfully');
}
window.addDetailMenu = function(groupId) {
    // Redirect to Menu Builder with group parameter
    window.location.href = '<?php echo APP_URL; ?>/menu/builder?group_id=' + groupId;
}
// Close Group Modal
window.closeGroupModal = function() {
    const groupModal = document.getElementById('groupModal');
    if (groupModal) {
        groupModal.classList.remove('show');
        groupModal.style.display = 'none';
    }
    document.body.classList.remove('modal-open');
    const backdrops = document.querySelectorAll('.modal-backdrop');
    backdrops.forEach(backdrop => backdrop.remove());
}
// Close Icon Picker Modal
window.closeIconPickerModal = function() {
    const iconPickerModal = document.getElementById('iconPickerModal');
    if (iconPickerModal) {
        iconPickerModal.classList.remove('show');
        iconPickerModal.style.display = 'none';
    }
    document.body.classList.remove('modal-open');
    const backdrops = document.querySelectorAll('.modal-backdrop');
    backdrops.forEach(backdrop => backdrop.remove());
}
// Add backdrop click event listeners
// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Clean up any existing modal state on page load
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.classList.remove('show');
        modal.style.display = 'none';
    });
    document.body.classList.remove('modal-open');
    const backdrops = document.querySelectorAll('.modal-backdrop');
    backdrops.forEach(backdrop => backdrop.remove());

    // Close modal when clicking on backdrop
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-backdrop')) {
            const visibleModals = document.querySelectorAll('.modal:not([style*="display: none"])');
            visibleModals.forEach(modal => {
                modal.classList.remove('show');
                modal.style.display = 'none';
            });
            document.body.classList.remove('modal-open');
            const backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach(backdrop => backdrop.remove());
        }
    });

    // Close modal when pressing Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const visibleModals = document.querySelectorAll('.modal:not([style*="display: none"])');
            visibleModals.forEach(modal => {
                modal.classList.remove('show');
                modal.style.display = 'none';
            });
            document.body.classList.remove('modal-open');
            const backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach(backdrop => backdrop.remove());
        }
    });
});

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
}
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
}
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
function initializejQuery() {
    console.log('Initializing jQuery functions...');
    
    // Form submissions
    $('#groupForm').on('submit', function(e) {
        e.preventDefault();
        console.log('Group form submitted');
        
        const formData = new FormData(this);
        const groupId = $('#groupId').val();
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
                // Hide modal using jQuery instead of Bootstrap modal
                $('#groupModal').hide();
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
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
    
    console.log('jQuery initialization completed');
}
// Icon picker functions
let selectedIconData = null;
window.openIconPicker = function() {
    console.log('openIconPicker() called');
    
    // Load icon picker content
    const container = document.getElementById('iconPickerContainer');
    console.log('Icon picker container:', container);
    
    // Use the same icon data structure as icon_picker.php
    let availableIcons = <?php echo json_encode($available_icons ?? []); ?>;
    console.log('Available icons:', availableIcons);
    
    // Ensure we have icons from ModuleController.php
    if (!availableIcons || Object.keys(availableIcons).length === 0) {
        console.error('No icons available from ModuleController.php getAvailableIcons()');
        AlertManager.error('Error: No icons available. Please check the ModuleController.php configuration.');
        return;
    }
    
    console.log('Icons loaded successfully, proceeding with icon picker...');

    // Generate icon picker HTML
    let html = `
        <div class="icon-picker-container">
            <!-- Selected Icon Display -->
            <div class="selected-icon-display mb-4">
                <div class="selected-icon-card">
                    <div class="selected-icon-preview">
                        <div class="icon-preview-section">
                            <div class="icon-preview-circle">
                                <i id="modalIconPreview" class="fas fa-home"></i>
                            </div>
                        </div>

                        <!-- Search box and category dropdown positioned to the right -->
                        <div class="search-section">
                            <div class="search-box">
                                <i class="fas fa-search search-icon"></i>
                                <input type="text" class="form-control search-input" id="modalIconSearch" placeholder="Search icons...">
                                <button type="button" class="search-clear d-none" id="modalClearSearch">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>

                            <!-- Category dropdown positioned next to search -->
                            <div class="category-dropdown">
                                <select class="form-select category-select" id="modalCategorySelect">
                                    <option value="all-icons" selected>Semua Icon</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Icon Grid -->
            <div class="icon-grid-container">
                <div class="icon-grid">
    `;

    // Add all icons to grid
    Object.entries(availableIcons).forEach(([category, icons]) => {
        Object.entries(icons).forEach(([iconClass, iconName]) => {
            html += `
                <div class="icon-item"
                     data-icon="${iconClass}"
                     data-label="${iconName}"
                     data-category="${category}"
                     title="${iconName} (${category})">
                    <div class="icon-item-inner">
                        <div class="icon-wrapper">
                            <i class="${iconClass}"></i>
                        </div>
                        <div class="icon-label">${iconName}</div>
                    </div>
                    <div class="selection-indicator">
                        <i class="fas fa-check"></i>
                    </div>
                </div>
            `;
        });
    });

    html += `
                </div>
            </div>
        </div>
    `;

    container.innerHTML = html;

    // Populate category dropdown
    populateCategoryDropdown();

    // Initialize icon picker functionality
    initializeModalIconPicker();

    // Show modal using jQuery with proper styling
    $('#iconPickerModal').addClass('show').show().css({
        'display': 'flex',
        'align-items': 'center',
        'justify-content': 'center',
        'min-height': '100vh',
        'z-index': '1055',
        'opacity': '1'
    });

    // Ensure modal dialog is visible and centered
    $('#iconPickerModal .modal-dialog').css({
        'z-index': '1056',
        'position': 'relative',
        'margin': '0',
        'max-width': '95vw',
        'width': '95%'
    });

    $('body').addClass('modal-open');

    // Add backdrop
    if ($('.modal-backdrop').length === 0) {
        $('body').append('<div class="modal-backdrop fade show" style="z-index: 1050;"></div>');
    }
}
function populateCategoryDropdown() {
    const categorySelect = document.getElementById('modalCategorySelect');
    if (!categorySelect) return;

    // Clear existing options except "Semua Icon"
    categorySelect.innerHTML = '<option value="all-icons" selected>Semua Icon</option>';

    // Get available icons from the global variable
    let availableIcons = <?php echo json_encode($available_icons ?? []); ?>;

    // Ensure we have icons from ModuleController.php
    if (!availableIcons || Object.keys(availableIcons).length === 0) {
        console.error('No icons available from ModuleController.php getAvailableIcons()');
        return;
    }

    // Add category options
    Object.keys(availableIcons).forEach(category => {
        const option = document.createElement('option');
        option.value = category.toLowerCase().replace(/[^a-z0-9]/g, '-');
        option.textContent = `${category} (${Object.keys(availableIcons[category]).length} icons)`;
        categorySelect.appendChild(option);
    });
}
function initializeModalIconPicker() {
    const iconItems = document.querySelectorAll('.icon-item');
    const modalIconPreview = document.getElementById('modalIconPreview');
    const modalIconName = document.querySelector('.icon-preview-name');
    const modalIconClass = document.querySelector('.icon-preview-class');
    const searchInput = document.getElementById('modalIconSearch');
    const clearSearchBtn = document.getElementById('modalClearSearch');

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
                icon: iconItem.dataset.icon,
                label: iconItem.dataset.label,
                category: iconItem.dataset.category
            };

            // Update preview
            if (modalIconPreview) modalIconPreview.className = iconItem.dataset.icon;

            // Update preview info
            if (modalIconName) modalIconName.textContent = iconItem.dataset.label;
            if (modalIconClass) modalIconClass.textContent = iconItem.dataset.icon;

            // Add selection animation
            iconItem.style.transform = 'scale(0.95)';
            setTimeout(() => {
                iconItem.style.transform = '';
            }, 150);
        }
    });

    // Search functionality
    let searchTimeout;
    const searchIcon = document.querySelector('.search-icon');

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const searchTerm = this.value.toLowerCase();
                const allIconItems = document.querySelectorAll('.icon-item');

                allIconItems.forEach(item => {
                    const iconLabel = item.dataset.label.toLowerCase();
                    const iconClass = item.dataset.icon.toLowerCase();
                    const iconCategory = item.dataset.category.toLowerCase();

                    if (iconLabel.includes(searchTerm) || iconClass.includes(searchTerm) || iconCategory.includes(searchTerm)) {
                        item.style.display = '';
                    } else {
                        item.style.display = 'none';
                    }
                });

                // Update clear button and search icon visibility
                if (searchTerm) {
                    clearSearchBtn.style.display = 'block';
                    if (searchIcon) searchIcon.style.display = 'none';
                } else {
                    clearSearchBtn.style.display = 'none';
                    if (searchIcon) searchIcon.style.display = 'block';
                }
            }, 300);
        });
    }

    // Clear search
    clearSearchBtn.addEventListener('click', function() {
        searchInput.value = '';
        searchInput.focus();

        iconItems.forEach(item => {
            item.style.display = '';
        });

        this.style.display = 'none';
        if (searchIcon) searchIcon.style.display = 'block';
    });

    // Category dropdown functionality
    const categorySelect = document.getElementById('modalCategorySelect');
    if (categorySelect) {
        categorySelect.addEventListener('change', function() {
            const selectedCategory = this.value;

            if (selectedCategory === 'all-icons') {
                // Show all icons
                iconItems.forEach(item => {
                    item.style.display = '';
                });
            } else {
                // Show only icons from selected category
                iconItems.forEach(item => {
                    const itemCategory = item.dataset.category;
                    const normalizedCategory = itemCategory ? itemCategory.toLowerCase().replace(/[^a-z0-9]/g, '-') : '';

                    if (normalizedCategory === selectedCategory) {
                        item.style.display = '';
                    } else {
                        item.style.display = 'none';
                    }
                });
            }

            // Clear search when switching categories
            searchInput.value = '';
            clearSearchBtn.style.display = 'none';
            if (searchIcon) searchIcon.style.display = 'block';
        });
    }
}
window.selectIcon = function() {
    console.log('selectIcon() called');
    console.log('selectedIconData:', selectedIconData);

    if (selectedIconData) {
        // Update the main form
        document.getElementById('groupIcon').value = selectedIconData.icon;
        document.getElementById('iconPreview').className = selectedIconData.icon;
        document.getElementById('iconClassDisplay').textContent = selectedIconData.icon;
        document.querySelector('.icon-name').textContent = selectedIconData.label;

        console.log('Icon selected:', selectedIconData.icon);

        // Close modal
        // Hide icon picker modal using jQuery
        if (typeof $ !== 'undefined') {
            $('#iconPickerModal').removeClass('show').hide();
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
        } else {
            // Fallback to vanilla JavaScript
            const modal = document.getElementById('iconPickerModal');
            if (modal) {
                modal.classList.remove('show');
                modal.style.display = 'none';
            }
            document.body.classList.remove('modal-open');
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.remove();
            }
        }

        // Reset selected data
        selectedIconData = null;

        console.log('Modal closed and data reset');
    } else {
        console.log('No icon selected');
        AlertManager.warning('Please select an icon first!');
    }
}
// Toast notification function
function showToast(type, message) {
    console.log('showToast called:', type, message);
    
    // Check if AlertManager is available
    if (typeof AlertManager !== 'undefined' && typeof AlertManager.showToast === 'function') {
        console.log('Using AlertManager for toast');
        AlertManager.showToast(type, message);
    } else {
        console.warn('AlertManager not available, creating fallback toast');
        
        // Create simple toast notification
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : type} alert-dismissible fade show`;
        toast.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 1050; max-width: 400px;';
        
        const iconClass = type === 'success' ? 'check-circle' : 
                         type === 'error' ? 'exclamation-triangle' : 
                         type === 'warning' ? 'exclamation-triangle' : 'info-circle';
        
        toast.innerHTML = `
            <i class="fas fa-${iconClass} me-2"></i>
            ${message}
            <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
        `;
        
        document.body.appendChild(toast);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (toast.parentNode) {
                toast.remove();
            }
        }, 5000);
    }
}
// Initialize AlertManager if not available
if (typeof AlertManager === 'undefined') {
    console.log('Creating inline AlertManager for menu management...');
    window.AlertManager = {
        showToast: function(type, message, options = {}) {
            console.log(`Toast [${type}]: ${message}`);
            
            // Create simple toast
            const toast = document.createElement('div');
            const alertType = type === 'success' ? 'success' : 
                            type === 'error' ? 'danger' : 
                            type === 'warning' ? 'warning' : 'info';
            
            toast.className = `alert alert-${alertType} alert-dismissible fade show`;
            toast.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 1050; max-width: 400px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);';
            
            const iconClass = type === 'success' ? 'check-circle' : 
                            type === 'error' ? 'exclamation-triangle' : 
                            type === 'warning' ? 'exclamation-triangle' : 'info-circle';
            
            toast.innerHTML = `
                <i class="fas fa-${iconClass} me-2"></i>
                ${message}
                <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
            `;
            
            document.body.appendChild(toast);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.remove();
                }
            }, options.duration || 5000);
            
            return toast;
        },
        
        showAlert: function(type, message, options = {}) {
            return this.showToast(type, message, options);
        },
        
        info: function(message, options = {}) {
            return this.showToast('info', message, options);
        },
        
        success: function(message, options = {}) {
            return this.showToast('success', message, options);
        },
        
        error: function(message, options = {}) {
            return this.showToast('error', message, options);
        },
        
        warning: function(message, options = {}) {
            return this.showToast('warning', message, options);
        }
    };
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    waitForjQuery();
    
    // Log that toggleDetailMenu is available
    console.log('toggleDetailMenu function loaded:', typeof window.toggleDetailMenu);
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
    background: white;
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
/* Icon Input Styling */
.icon-input-container {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 12px;
    background: #f8f9fa;
    transition: all 0.3s ease;
}
.icon-input-container:hover {
    border-color: #007bff;
    background: white;
}
.selected-icon-display {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 8px;
}
.icon-preview {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}
.icon-preview i {
    font-size: 20px;
    color: white;
}
.icon-info {
    flex: 1;
}
.icon-name {
    font-weight: 600;
    color: #495057;
    margin-bottom: 2px;
}
.icon-class {
    font-size: 12px;
    color: #6c757d;
    font-family: 'Courier New', monospace;
    background: #e9ecef;
    padding: 2px 6px;
    border-radius: 4px;
    display: inline-block;
}
/* Modal Styling Fix */
.modal {
    z-index: 1055 !important;
    display: none !important;
    align-items: center !important;
    justify-content: center !important;
    min-height: 100vh !important;
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100% !important;
    height: 100% !important;
    background: rgba(0, 0, 0, 0.5) !important;
}
.modal.show {
    display: flex !important;
    opacity: 1 !important;
}
.modal:not(.show) {
    display: none !important;
}
.modal-dialog {
    z-index: 1056 !important;
    position: relative;
    margin: 0 !important;
    max-width: 500px;
    width: 90%;
}
.modal-backdrop {
    z-index: 1050 !important;
}
/* Icon Picker Modal Styling */
#iconPickerModal .modal-dialog {
    max-width: 95vw;
    margin: 0 !important;
    width: 95%;
}
#iconPickerModal .modal-content {
    border: none;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.15);
    overflow: hidden;
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
}
#iconPickerModal .modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 20px 30px;
    position: relative;
    overflow: hidden;
}
#iconPickerModal .modal-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="20" cy="20" r="1" fill="white" opacity="0.1"/><circle cx="80" cy="40" r="1" fill="white" opacity="0.05"/><circle cx="40" cy="80" r="1" fill="white" opacity="0.08"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    pointer-events: none;
}
#iconPickerModal .modal-title {
    font-weight: 700;
    font-size: 1.4rem;
    position: relative;
    z-index: 1;
}
#iconPickerModal .btn-close {
    filter: brightness(0) invert(1);
    opacity: 0.8;
    transition: all 0.3s ease;
    position: relative;
    z-index: 1;
}
#iconPickerModal .btn-close:hover {
    opacity: 1;
    transform: scale(1.1);
}
#iconPickerModal .modal-body {
    padding: 0;
    background: white;
}
#iconPickerContainer {
    max-height: 70vh;
    overflow-y: auto;
    padding: 0;
}
#iconPickerContainer::-webkit-scrollbar {
    width: 8px;
}
#iconPickerContainer::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}
#iconPickerContainer::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 4px;
}
#iconPickerContainer::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
}
/* Enhanced Icon Picker Container */
.icon-picker-container {
    background: white;
    border-radius: 0;
    padding: 0;
    box-shadow: none;
}
/* Enhanced Selected Icon Display */
.icon-picker-container .selected-icon-display {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    padding: 25px 30px;
    border-bottom: 1px solid #e9ecef;
    margin-bottom: 0;
    width: 100%;
    box-sizing: border-box;
}
.icon-picker-container .selected-icon-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 15px;
    padding: 20px 25px;
    color: white;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    position: relative;
    overflow: hidden;
    width: 100%;
    box-sizing: border-box;
}
.icon-picker-container .selected-icon-card::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: shimmer 3s ease-in-out infinite;
}
/* Updated layout for icon preview and search */
.icon-picker-container .selected-icon-preview {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    width: 100%;
    flex-wrap: wrap;
}
.icon-picker-container .icon-preview-section {
    display: flex;
    align-items: center;
    gap: 15px;
    flex: 0 0 auto;
}
.icon-picker-container .icon-preview-info {
    display: flex;
    flex-direction: column;
    gap: 5px;
}
.icon-picker-container .icon-preview-name {
    font-size: 14px;
    font-weight: 600;
    color: rgba(255,255,255,0.9);
}
.icon-picker-container .icon-preview-class {
    font-size: 12px;
    color: rgba(255,255,255,0.7);
    font-family: 'Courier New', monospace;
}
/* Search Container - Side by side layout */
.icon-picker-container .search-section {
    position: relative;
    display: flex;
    gap: 15px;
    align-items: center;
    flex: 1;
    min-width: 250px;
    max-width: none;
}
.icon-picker-container .search-box {
    flex: 1;
    position: relative;
    display: flex;
    align-items: center;
}
.icon-picker-container .category-dropdown {
    flex: 0 0 auto;
    min-width: 200px;
    max-width: 250px;
}
.icon-picker-container .category-select {
    border: none;
    border-radius: 30px;
    padding: 12px 20px;
    font-size: 14px;
    font-weight: 500;
    color: #333;
    background: white;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    cursor: pointer;
    height: 50px;
    width: 100%;
}
.icon-picker-container .category-select:focus {
    border: none;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    outline: none;
    background: white;
}
.icon-picker-container .category-select:hover {
    box-shadow: 0 4px 15px rgba(0,0,0,0.12);
}
.icon-picker-container .category-select option {
    background: white;
    color: #333;
    padding: 8px;
}
@keyframes shimmer {
    0%, 100% { transform: translateX(-100%) translateY(-100%) rotate(30deg); }
    50% { transform: translateX(100%) translateY(100%) rotate(30deg); }
}
.icon-picker-container .icon-preview-circle {
    width: 70px;
    height: 70px;
    background: rgba(255,255,255,0.25);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(15px);
    border: 3px solid rgba(255,255,255,0.3);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}
.icon-picker-container .icon-preview-circle:hover {
    transform: scale(1.05);
    box-shadow: 0 12px 35px rgba(0,0,0,0.15);
}
.icon-picker-container .icon-preview-circle i {
    font-size: 28px;
    color: white;
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
}
.icon-picker-container .search-box {
    position: relative;
    display: flex;
    align-items: center;
}
.icon-picker-container .search-icon {
    position: absolute;
    right: 15px;
    color: #666;
    z-index: 2;
}
.icon-picker-container .search-input {
    padding-left: 20px;
    padding-right: 50px;
    border: none;
    border-radius: 30px;
    height: 50px;
    font-size: 14px;
    transition: all 0.3s ease;
    background: white;
    color: #333;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    width: 100%;
}
.icon-picker-container .search-input:focus {
    border: none;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    outline: none;
    background: white;
}
.icon-picker-container .search-clear {
    position: absolute;
    right: 15px;
    background: none;
    border: none;
    color: #666;
    cursor: pointer;
    z-index: 2;
    padding: 5px;
    border-radius: 50%;
    transition: all 0.2s ease;
}
.icon-picker-container .search-clear:hover {
    background: rgba(0,0,0,0.1);
    color: #ff6b6b;
}
.icon-picker-container .search-input::placeholder {
    color: #999;
}
/* Responsive Design */
@media (max-width: 992px) {
    .icon-picker-container .search-section {
        min-width: 200px;
    }

    .icon-picker-container .icon-preview-section {
        min-width: 60px;
    }
}
@media (max-width: 768px) {
    .icon-picker-container .selected-icon-display {
        padding: 20px 15px;
    }

    .icon-picker-container .selected-icon-card {
        padding: 15px 20px;
    }

    .icon-picker-container .selected-icon-preview {
        flex-direction: column;
        align-items: stretch;
        gap: 15px;
    }

    .icon-picker-container .icon-preview-section {
        justify-content: center;
        min-width: auto;
    }

    .icon-picker-container .search-section {
        flex-direction: column;
        gap: 10px;
        width: 100%;
        min-width: auto;
        max-width: none;
    }

    .icon-picker-container .search-box {
        width: 100%;
    }

    .icon-picker-container .category-dropdown {
        width: 100%;
        min-width: auto;
        max-width: none;
    }
}
@media (max-width: 480px) {
    .icon-picker-container .selected-icon-display {
        padding: 15px 10px;
    }

    .icon-picker-container .selected-icon-card {
        padding: 12px 15px;
    }

    .icon-picker-container .selected-icon-preview {
        gap: 10px;
    }

    .icon-picker-container .icon-preview-section {
        gap: 10px;
    }

    .icon-picker-container .icon-preview-circle {
        width: 50px;
        height: 50px;
    }

    .icon-picker-container .icon-preview-circle i {
        font-size: 20px;
    }

    .icon-picker-container .search-input {
        height: 35px;
        font-size: 12px;
    }

    .icon-picker-container .category-select {
        height: 35px;
        font-size: 12px;
    }
}
/* Enhanced Icon Grid */
.icon-picker-container .icon-grid-container {
    background: white;
    padding: 30px;
    border-radius: 0;
    box-shadow: none;
}
.icon-picker-container .icon-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    gap: 20px;
    padding: 0;
}
.icon-picker-container .icon-item {
    background: white;
    border: 2px solid #f1f3f4;
    border-radius: 15px;
    cursor: pointer;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    position: relative;
}
.icon-picker-container .icon-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
    transition: left 0.6s ease;
}
.icon-picker-container .icon-item:hover::before {
    left: 100%;
}
.icon-picker-container .icon-item:hover {
    border-color: #667eea;
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 15px 40px rgba(102, 126, 234, 0.2);
}
.icon-picker-container .icon-item.selected {
    border-color: #667eea;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    transform: translateY(-5px) scale(1.05);
    box-shadow: 0 20px 50px rgba(102, 126, 234, 0.4);
}
.icon-picker-container .icon-item-inner {
    padding: 25px 20px;
    text-align: center;
    position: relative;
    z-index: 1;
}
.icon-picker-container .icon-wrapper {
    width: 50px;
    height: 50px;
    margin: 0 auto 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
    border-radius: 12px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}
.icon-picker-container .icon-wrapper::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
}
.icon-picker-container .icon-item:hover .icon-wrapper::before {
    opacity: 1;
}
.icon-picker-container .icon-item.selected .icon-wrapper {
    background: rgba(255,255,255,0.25);
    backdrop-filter: blur(10px);
}
.icon-picker-container .icon-wrapper i {
    font-size: 24px;
    color: #6c757d;
    transition: all 0.3s ease;
    position: relative;
    z-index: 1;
}
.icon-picker-container .icon-item:hover .icon-wrapper i {
    color: #667eea;
    transform: scale(1.1) rotate(5deg);
}
.icon-picker-container .icon-item.selected .icon-wrapper i {
    color: white;
    transform: scale(1.1);
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
}
.icon-picker-container .icon-label {
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 8px;
    color: #495057;
    line-height: 1.3;
}
.icon-picker-container .icon-item.selected .icon-label {
    color: white;
}
.icon-picker-container .selection-indicator {
    position: absolute;
    top: 12px;
    right: 12px;
    width: 24px;
    height: 24px;
    background: #28a745;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transform: scale(0) rotate(180deg);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
}
.icon-picker-container .icon-item.selected .selection-indicator {
    opacity: 1;
    transform: scale(1) rotate(0deg);
}
.icon-picker-container .selection-indicator i {
    font-size: 12px;
    color: white;
}
/* Modal Footer Styling */
#iconPickerModal .modal-footer {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border: none;
    padding: 20px 30px;
    border-top: 1px solid #e9ecef;
}
#iconPickerModal .modal-footer .btn {
    border-radius: 25px;
    padding: 10px 25px;
    font-weight: 600;
    transition: all 0.3s ease;
}
#iconPickerModal .modal-footer .btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}
#iconPickerModal .modal-footer .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
}
/* Responsive Design */
@media (max-width: 768px) {
    #iconPickerModal .modal-dialog {
        max-width: 95vw;
        margin: 0.5rem;
    }

    .icon-picker-container .icon-grid {
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 15px;
    }

    .icon-picker-container .icon-item-inner {
        padding: 20px 15px;
    }

    .icon-picker-container .icon-wrapper {
        width: 45px;
        height: 45px;
    }

    .icon-picker-container .icon-wrapper i {
        font-size: 20px;
    }
}

</style>
<?php
// End output buffering and capture content
$content = ob_get_clean();
// Include the main layout with content
include __DIR__ . '/../layouts/app.php';
?>