<?php
$title = $title ?? 'Menu Builder';
$current_page = 'menu-builder';

// Generate CSRF token early for forms
$csrf_token = Session::generateCSRF();

// Start output buffering to capture content
ob_start();
?>

<div class="row">
    <!-- Menu Builder Panel -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Detail Menu</h5>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?php echo APP_URL; ?>/dashboard">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo APP_URL; ?>/menu">Menu Management</a></li>
                        <li class="breadcrumb-item active">Menu Builder</li>
                    </ol>
                </div>
            </div>

            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0"><i class="fas fa-server me-2"></i> Struktur Menu Group "<?php echo htmlspecialchars($selected_group['name']); ?>"</h6>
                    <button class="btn btn-sm btn-primary" onclick="addMenuItem()">
                        <i class="fas fa-plus"></i> Tambah Detail Menu
                    </button>
                </div>

                <div id="menuBuilder" class="menu-builder">
                    <div class="menu-items" id="sortableMenu">
                        <?php if (!empty($menuItems)): ?>
                            <?php 
                            // Group menu items by group_id and parent_id
                            $groupedMenuItems = [];
                            foreach ($menuItems as $item) {
                                $groupId = $item['group_id'] ?? 'standalone';
                                $parentId = $item['parent_id'] ?? 'standalone';
                                if (!isset($groupedMenuItems[$groupId])) {
                                    $groupedMenuItems[$groupId] = [];
                                }
                                if (!isset($groupedMenuItems[$groupId][$parentId])) {
                                    $groupedMenuItems[$groupId][$parentId] = [];
                                }
                                $groupedMenuItems[$groupId][$parentId][] = $item;
                            }
                            
                            // Filter groups if in group mode
                            $groupsToShow = $groups;
                            if (isset($selected_group_id) && $selected_group_id) {
                                $groupsToShow = array_filter($groups, function($group) use ($selected_group_id) {
                                    return $group['id'] == $selected_group_id;
                                });
                            }
                            
                            // Render groups first
                            foreach ($groupsToShow as $group): 
                                $groupItems = $groupedMenuItems[$group['id']] ?? [];
                                $totalItems = array_sum(array_map('count', $groupItems));
                            ?>
                                <div class="menu-group" data-group-id="<?php echo $group['id']; ?>">
                                    <div class="menu-group-content">
                                        <div class="menu-modules" data-group-id="<?php echo $group['id']; ?>">
                                            <?php 
                                            // Organize menu items into hierarchical structure
                                            $menuStructure = [];
                                            $menuMap = [];
                                            
                                            // Create map of all menu items
                                            foreach ($menuItems as $item) {
                                                if ($item['group_id'] == $group['id']) {
                                                    $menuMap[$item['id']] = $item;
                                                    $menuStructure[$item['id']] = array_merge($item, ['children' => []]);
                                                }
                                            }
                                            
                                            // Build hierarchy
                                            $rootItems = [];
                                            foreach ($menuStructure as $id => $item) {
                                                if (!empty($item['parent_id']) && isset($menuStructure[$item['parent_id']])) {
                                                    $menuStructure[$item['parent_id']]['children'][] = &$menuStructure[$id];
                                                } else {
                                                    $rootItems[] = &$menuStructure[$id];
                                                }
                                            }
                                            
                                            // Sort by sort_order
                                            function sortMenuItems(&$items) {
                                                usort($items, function($a, $b) {
                                                    return ($a['sort_order'] ?? 0) - ($b['sort_order'] ?? 0);
                                                });
                                                foreach ($items as &$item) {
                                                    if (!empty($item['children'])) {
                                                        sortMenuItems($item['children']);
                                                    }
                                                }
                                            }
                                            sortMenuItems($rootItems);
                                            
                                            // Render menu items
                                            function renderMenuItem($item, $level = 0) {
                                                $indentClass = $level > 0 ? 'menu-child-indent' : '';
                                                $isActive = $item['is_active'] ? '' : 'menu-inactive';
                                                $hasChildren = !empty($item['children']);
                                                $parentClass = $hasChildren ? 'menu-parent-item' : '';
                                                
                                                echo '<div class="menu-module ' . $indentClass . ' ' . $isActive . ' ' . $parentClass . '" data-menu-item-id="' . $item['id'] . '">';
                                                echo '<div class="menu-module-info">';
                                                echo '<div class="menu-item-content">';
                                                echo '<div class="menu-item-main">';
                                                echo '<i class="' . ($item['icon'] ?? 'fas fa-circle') . ' me-2"></i>';
                                                echo '<span class="menu-item-name">' . htmlspecialchars($item['name']) . '</span>';
                                                if ($hasChildren) {
                                                    echo '<i class="fas fa-chevron-down ms-2 parent-indicator"></i>';
                                                }
                                                echo '</div>';
                                                echo '<div class="menu-item-meta">';
                                                if (!$item['is_active']) {
                                                    echo '<span class="badge bg-secondary badge-sm me-1">Inactive</span>';
                                                }
                                                echo '</div>';
                                                echo '</div>';
                                                echo '</div>';
                                                echo '<div class="menu-item-actions">';
                                                echo '<button class="btn btn-sm btn-outline-primary" onclick="editMenuItem(' . $item['id'] . ')">';
                                                echo '<i class="fas fa-edit"></i>';
                                                echo '</button>';
                                                echo '<button class="btn btn-sm btn-outline-danger" onclick="deleteMenuItem(' . $item['id'] . ')">';
                                                echo '<i class="fas fa-trash"></i>';
                                                echo '</button>';
                                                echo '</div>';
                                                echo '</div>';
                                                
                                                // Render children
                                                if ($hasChildren) {
                                                    foreach ($item['children'] as $child) {
                                                        renderMenuItem($child, $level + 1);
                                                    }
                                                }
                                            }
                                            
                                            // Render all root items
                                            foreach ($rootItems as $item) {
                                                renderMenuItem($item);
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-folder-open"></i>
                                <p class="mb-0">No menu items found</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Menu Item Modal -->
<div class="modal fade" id="menuItemModal" tabindex="-1" aria-labelledby="menuItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="menuItemModalLabel">Add Menu Item</h5>
                <button type="button" class="btn-close" onclick="closeMenuItemModal()"></button>
            </div>
            <form id="menuItemForm">
                <div class="modal-body">
                    <input type="hidden" id="menuItemId" name="id">
                    <input type="hidden" id="menuItemGroupId" name="group_id" value="<?php echo $selected_group['id']; ?>">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="menuItemName" class="form-label">Menu Name</label>
                                <input type="text" class="form-control" id="menuItemName" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="menuItemUrl" class="form-label">URL</label>
                                <input type="text" class="form-control" id="menuItemUrl" name="url" placeholder="/dashboard">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="menuItemIcon" class="form-label">Icon</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="menuItemIcon" name="icon" value="fas fa-circle" readonly>
                                    <button type="button" class="btn btn-outline-secondary" onclick="openIconPicker('menuItemIcon')">
                                        <i class="fas fa-search"></i> Choose Icon
                                    </button>
                                </div>
                                <div class="mt-2">
                                    <div class="d-flex align-items-center">
                                        <i id="menuItemIconPreview" class="fas fa-circle me-2"></i>
                                        <span id="menuItemIconClassDisplay" class="text-muted">fas fa-circle</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="menuItemSortOrder" class="form-label">Sort Order</label>
                                <input type="number" class="form-control" id="menuItemSortOrder" name="sort_order" value="0">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="menuItemParent" class="form-label">Parent Menu</label>
                                <select class="form-control" id="menuItemParent" name="parent_id">
                                    <option value="">No Parent (Root Menu)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="menuItemModule" class="form-label">Module</label>
                                <select class="form-control" id="menuItemModule" name="module_id">
                                    <option value="">No Module</option>
                                    <?php if (!empty($modules)): ?>
                                        <?php foreach ($modules as $module): ?>
                                            <option value="<?php echo $module['id']; ?>"><?php echo htmlspecialchars($module['name']); ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="menuItemDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="menuItemDescription" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="menuItemIsActive" name="is_active" value="1" checked>
                                <label class="form-check-label" for="menuItemIsActive">
                                    Active
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="menuItemIsParent" name="is_parent" value="1">
                                <label class="form-check-label" for="menuItemIsParent">
                                    Is Parent Menu
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeMenuItemModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Menu Item</button>
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
                Are you sure you want to delete this menu item? This action cannot be undone.
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
    console.log('DOM loaded, initializing menu builder...');
    initializeMenuBuilder();
});

// Global variables
let selectedIconData = null;
let currentIconTarget = null;
let deleteMenuItemId = null;

// Menu builder functions
function initializeMenuBuilder() {
    console.log('Initializing menu builder functions...');
    
    // Form submissions
    const menuItemForm = document.getElementById('menuItemForm');
    if (menuItemForm) {
        menuItemForm.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Menu item form submitted');
            
            const formData = new FormData(this);
            const menuItemId = document.getElementById('menuItemId').value;
            const url = menuItemId ? 
                '<?php echo APP_URL; ?>/menu/update-menu-item' : 
                '<?php echo APP_URL; ?>/menu/create-menu-item';
            
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
                    const modal = bootstrap.Modal.getInstance(document.getElementById('menuItemModal'));
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
                showToast('error', 'An error occurred while saving the menu item.');
            });
        });
    }
    
    // Delete confirmation
    const confirmDeleteBtn = document.getElementById('confirmDelete');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function() {
            if (deleteMenuItemId) {
                deleteMenuItem(deleteMenuItemId);
            }
        });
    }
    
    // Is Parent Menu checkbox control
    const isParentCheckbox = document.getElementById('menuItemIsParent');
    const moduleSelect = document.getElementById('menuItemModule');
    
    if (isParentCheckbox && moduleSelect) {
        isParentCheckbox.addEventListener('change', function() {
            if (this.checked) {
                moduleSelect.value = '';
                moduleSelect.disabled = true;
            } else {
                moduleSelect.disabled = false;
            }
        });
    }
    
    console.log('Menu builder initialization completed');
}

// Global functions that can be called from onclick
window.addMenuItem = function() {
    console.log('addMenuItem() called');
    
    // Reset form and show modal for adding new menu item
    const modalLabel = document.getElementById('menuItemModalLabel');
    const menuItemForm = document.getElementById('menuItemForm');
    const menuItemIdField = document.getElementById('menuItemId');
    
    if (modalLabel) modalLabel.textContent = 'Add Menu Item';
    if (menuItemForm) menuItemForm.reset();
    if (menuItemIdField) menuItemIdField.value = '';
    
    // Reset icon display
    const menuItemIcon = document.getElementById('menuItemIcon');
    const iconPreview = document.getElementById('menuItemIconPreview');
    const iconClassDisplay = document.getElementById('menuItemIconClassDisplay');
    
    if (menuItemIcon) menuItemIcon.value = 'fas fa-circle';
    if (iconPreview) iconPreview.className = 'fas fa-circle';
    if (iconClassDisplay) iconClassDisplay.textContent = 'fas fa-circle';
    
    // Load parent items
    loadParentItems();
    
    console.log('Form reset completed');
    
    // Show modal using Bootstrap Modal API
    const menuItemModal = document.getElementById('menuItemModal');
    if (menuItemModal) {
        const modal = new bootstrap.Modal(menuItemModal);
        modal.show();
    }
    
    console.log('Modal displayed successfully');
};

window.editMenuItem = function(id) {
    console.log('editMenuItem() called with id:', id);
    
    // Load menu item data and populate form
    fetch('<?php echo APP_URL; ?>/menu/get-menu-item/' + id, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.menuItem) {
            // Populate form fields with menu item data
            const menuItemId = document.getElementById('menuItemId');
            const menuItemName = document.getElementById('menuItemName');
            const menuItemUrl = document.getElementById('menuItemUrl');
            const menuItemIcon = document.getElementById('menuItemIcon');
            const menuItemSortOrder = document.getElementById('menuItemSortOrder');
            const menuItemParent = document.getElementById('menuItemParent');
            const menuItemModule = document.getElementById('menuItemModule');
            const menuItemDescription = document.getElementById('menuItemDescription');
            const menuItemIsActive = document.getElementById('menuItemIsActive');
            const menuItemIsParent = document.getElementById('menuItemIsParent');
            
            if (menuItemId) menuItemId.value = data.menuItem.id;
            if (menuItemName) menuItemName.value = data.menuItem.name;
            if (menuItemUrl) menuItemUrl.value = data.menuItem.url || '';
            if (menuItemIcon) menuItemIcon.value = data.menuItem.icon || 'fas fa-circle';
            if (menuItemSortOrder) menuItemSortOrder.value = data.menuItem.sort_order || 0;
            if (menuItemDescription) menuItemDescription.value = data.menuItem.description || '';
            if (menuItemIsActive) menuItemIsActive.checked = data.menuItem.is_active == 1;
            if (menuItemIsParent) menuItemIsParent.checked = data.menuItem.is_parent == 1;

            // Update icon display
            const iconClass = data.menuItem.icon || 'fas fa-circle';
            const iconPreview = document.getElementById('menuItemIconPreview');
            const iconClassDisplay = document.getElementById('menuItemIconClassDisplay');
            
            if (iconPreview) iconPreview.className = iconClass;
            if (iconClassDisplay) iconClassDisplay.textContent = iconClass;
            
            // Load parent items
            loadParentItems(data.menuItem.id);
            
            // Set module value
            if (menuItemModule && data.menuItem.module_id) {
                menuItemModule.value = data.menuItem.module_id;
            }
            
            // Set parent value
            if (menuItemParent && data.menuItem.parent_id) {
                menuItemParent.value = data.menuItem.parent_id;
            }
            
            // Update modal label
            const modalLabel = document.getElementById('menuItemModalLabel');
            if (modalLabel) modalLabel.textContent = 'Edit Menu Item';
            
            // Show modal using Bootstrap Modal API
            const menuItemModal = document.getElementById('menuItemModal');
            if (menuItemModal) {
                const modal = new bootstrap.Modal(menuItemModal);
                modal.show();
            }
        } else {
            console.error('Failed to load menu item data:', data.error);
            showToast('error', 'Failed to load menu item data');
        }
    })
    .catch(error => {
        console.error('Error loading menu item data:', error);
        showToast('error', 'An error occurred while loading menu item data');
    });
};

window.deleteMenuItem = function(id) {
    deleteMenuItemId = id;
    const modal = new bootstrap.Modal(document.getElementById("deleteModal"));
    modal.show();
};

function deleteMenuItem(id) {
    if (!id) return;
    
    console.log('Deleting menu item with id:', id);
    
    fetch('<?php echo APP_URL; ?>/menu/delete-menu-item', {
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
    .then(response => {
        if (response.status === 403) {
            showToast('error', 'Access denied. Please refresh the page and try again.');
            return;
        }
        return response.json();
    })
    .then(data => {
        if (data && data.success) {
            showToast('success', data.message || 'Menu item deleted successfully');
            // Hide modal
            const modal = bootstrap.Modal.getInstance(document.getElementById("deleteModal"));
            if (modal) {
                modal.hide();
            }
            // Reload page to refresh the list
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else if (data && data.error) {
            showToast('error', data.error || 'Failed to delete menu item');
        }
    })
    .catch(error => {
        console.error('Error deleting menu item:', error);
        showToast('error', 'An error occurred while deleting the menu item');
    });
}

// Close Menu Item Modal
window.closeMenuItemModal = function() {
    const menuItemModal = document.getElementById('menuItemModal');
    if (menuItemModal) {
        const modal = bootstrap.Modal.getInstance(menuItemModal);
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

// Load parent items for dropdown
function loadParentItems(excludeId = null) {
    const parentSelect = document.getElementById('menuItemParent');
    if (!parentSelect) return;
    
    const groupId = document.getElementById('menuItemGroupId').value;
    if (!groupId) return;
    
    // Clear existing options except the first one
    parentSelect.innerHTML = '<option value="">No Parent (Root Menu)</option>';
    
    fetch(`<?php echo APP_URL; ?>/menu/get-parent-items/${groupId}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.parentItems && parentSelect) {
            data.parentItems.forEach(item => {
                // Skip the item being edited to prevent self-parenting
                if (excludeId && item.id == excludeId) return;
                
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.name;
                parentSelect.appendChild(option);
            });
        }
    })
    .catch(error => {
        console.error('Error loading parent items:', error);
    });
}

// Icon picker functions
window.openIconPicker = function(target) {
    currentIconTarget = target;
    console.log('openIconPicker() called for target:', target);
    
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

    // Update form fields based on current target
    if (currentIconTarget === 'menuItemIcon') {
        const menuItemIcon = document.getElementById('menuItemIcon');
        const iconPreview = document.getElementById('menuItemIconPreview');
        const iconClassDisplay = document.getElementById('menuItemIconClassDisplay');

        if (menuItemIcon) menuItemIcon.value = selectedIconData.class;
        if (iconPreview) iconPreview.className = selectedIconData.class;
        if (iconClassDisplay) iconClassDisplay.textContent = selectedIconData.class;
    }

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
