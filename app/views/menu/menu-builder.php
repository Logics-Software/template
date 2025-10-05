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
                    <button class="btn btn-sm btn-outline-primary" onclick="addMenuItem()">
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
                                            
                                            // Render menu structure
                                            function renderMenuStructure($items, $level = 0) {
                                                if (empty($items)) return '';
                                                
                                                $html = '';
                                                foreach ($items as $item) {
                                                    $isActive = $item['is_active'] ? '' : 'menu-inactive';
                                                    $hasChildren = !empty($item['children']);
                                                    $parentClass = $hasChildren ? 'menu-parent-item' : '';
                                                    
                                                    $html .= '
                                                    <div class="menu-module ' . $isActive . ' ' . $parentClass . '" data-menu-item-id="' . $item['id'] . '">
                                                    <div class="menu-module-info">
                                                            <div class="menu-item-content">
                                                                <div class="menu-item-main">

                                                                    ' . ($level > 0 ? 
                                                                        '<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><i class="' . ($item['icon'] ?? 'fas fa-circle') . ' me-2"></i><span class="menu-item-name">' . htmlspecialchars($item['name']) . '</span>' : 
                                                                        '<i class="' . ($item['icon'] ?? 'fas fa-circle') . ' me-2"></i><span class="menu-item-name">' . htmlspecialchars($item['name']) . '</span>') . '

                                                                    ' . ($hasChildren && $level == 0 ? '<i class="fas fa-chevron-down ms-2 parent-indicator"></i>' : '') . '
                                                                </div>
                                                                <div class="menu-item-meta">
                                                                    ' . (!$item['is_active'] ? '<span class="badge bg-secondary badge-sm me-1">Inactive</span>' : '') . '
                                                                    
                                                    </div>
                                                </div>
                                                        </div>
                                                        <div class="menu-module-actions">
                                                            <button class="btn btn-sm btn-outline-primary" onclick="editMenuItem(' . $item['id'] . ')" title="Edit">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-outline-danger" onclick="deleteMenuItem(' . $item['id'] . ')" title="Delete">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-outline-secondary drag-handle" title="Drag to reorder">
                                                                <i class="fas fa-grip-vertical"></i>
                                                            </button>
                                                        </div>
                                                    </div>';
                                                    
                                                    // Render children with proper indentation
                                                    if ($hasChildren) {
                                                        $html .= renderMenuStructure($item['children'], $level + 1);
                                                    }
                                                }
                                                return $html;
                                            }
                                            
                                            echo renderMenuStructure($rootItems);
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-folder-open fa-3x text-muted"></i>
                                <h5 class="mt-3">
                                    <?php if (isset($selected_group) && $selected_group): ?>
                                        No Menu Items in "<?php echo htmlspecialchars($selected_group['name']); ?>"
                                    <?php else: ?>
                                        No Menu Items
                                    <?php endif; ?>
                                </h5>
                                <p class="text-muted">
                                    <?php if (isset($selected_group) && $selected_group): ?>
                                        Start by adding menu items to this group using the "Add Menu Item" button above.
                                    <?php else: ?>
                                        Start by adding menu groups or modules to build your menu structure.
                                    <?php endif; ?>
                                </p>
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
            <div class="modal-header m-0 p-4 bg-secondary">
                <h5 class="modal-title" id="menuItemModalLabel">Add Menu Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="menuItemForm">
                <div class="modal-body m-3">
                    <input type="hidden" id="menuItemId" name="id">
                    <input type="hidden" id="menuItemCsrfToken" name="_token" value="<?php echo $csrf_token; ?>">
                    <input type="hidden" id="menuItemGroupHidden" name="group_id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="menuItemGroup" name="group_id" required disabled>
                                    <option value="">Select Group</option>
                                    <?php foreach ($groups as $group): ?>
                                        <option value="<?php echo $group['id']; ?>"><?php echo htmlspecialchars($group['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <label for="menuItemGroup">Group Menu <span class="text-danger">*</span></label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="menuItemName" name="name" placeholder="Menu Name" required>
                                <label for="menuItemName">Nama Menu <span class="text-danger">*</span></label>
                        </div>
                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <div class="form-floating">
                                        <select class="form-select" id="menuItemParent" name="parent_id">
                                            <option value="">Link Tunggal/Dropdown</option>
                                            <?php 
                                            // Filter menu items by selected group
                                            $selectedGroupId = $selected_group_id ?? null;
                                            if ($selectedGroupId) {
                                                foreach ($menuItems as $item): 
                                                    if ($item['is_parent'] && $item['group_id'] == $selectedGroupId): ?>
                                                        <option value="<?php echo $item['id']; ?>"><?php echo htmlspecialchars($item['name']); ?></option>
                                                    <?php endif; 
                                                endforeach;
                                            } else {
                                                // If no specific group selected, show all parent items
                                                foreach ($menuItems as $item): 
                                                    if ($item['is_parent']): ?>
                                                        <option value="<?php echo $item['id']; ?>"><?php echo htmlspecialchars($item['name']); ?></option>
                                                    <?php endif; 
                                                endforeach;
                                            }
                                            ?>
                                        </select>
                                        <label for="menuItemParent">Induk Menu</label>
                            </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check h-100 d-flex align-items-center">
                                        <input class="form-check-input" type="checkbox" id="menuItemIsParent" name="is_parent">
                                        <label class="form-check-label ms-2" for="menuItemIsParent">
                                            Dropdown
                                    </label>
                                </div>
        </div>
    </div>
</div>

                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="menuItemModule" name="module_id">
                                    <option value="">Pilih Modul (Opsional)</option>
                                    <?php foreach ($modules as $module): ?>
                                        <option value="<?php echo $module['id']; ?>"><?php echo htmlspecialchars($module['caption']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <label for="menuItemModule">Module <span class="text-danger module-required-asterisk d-none">*</span></label>
                            </div>
                            <div class="mb-3">
                                <div class="icon-preview-container">
                                    <div class="icon-preview-section">
                                        <div class="icon-preview-circle">
                                            <i id="menuItemIconPreview" class="fas fa-circle"></i>
                                </div>
                                        <div class="icon-preview-info">
                                            <div class="icon-preview-name">Select Icon</div>
                                            <div class="icon-preview-class">fas fa-circle</div>
                            </div>
                        </div>
                                    <button type="button" class="btn btn-primary choose-icon-btn" onclick="openIconPicker('menuItemIcon')">
                                        <i class="fas fa-search me-2"></i>Choose Icon
                                    </button>
                            </div>
                                <input type="hidden" id="menuItemIcon" name="icon" value="fas fa-circle">
                            </div>

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-floating">
                                        <input type="number" class="form-control" id="menuItemSortOrder" name="sort_order" value="1" placeholder="Sort Order" class="number-input">
                                        <label for="menuItemSortOrder">Urutan Menu</label>
                                </div>
                            </div>
                                <div class="col-md-4 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="menuItemIsActive" name="is_active" checked>
                                    <label class="form-check-label" for="menuItemIsActive">
                                        Active
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                <div class="modal-footer m-0 p-3 bg-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Menu Item</button>
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
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="iconPickerContainer">
                    <!-- Icon picker will be loaded here -->
                                </div>
                                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="selectIcon()">Select Icon</button>
                            </div>
                                </div>
                            </div>
                        </div>
                        
<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteMenuItemModal" tabindex="-1">
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
                <button type="button" class="btn btn-danger" id="confirmDeleteMenuItem">Delete</button>
            </div>
        </div>
    </div>
</div>

<style>
.menu-builder {
    min-height: 400px;
}

/* Remove spinner from number input */
#menuItemSortOrder::-webkit-outer-spin-button,
#menuItemSortOrder::-webkit-inner-spin-button {
    -webkit-appearance: none;
    appearance: none;
    margin: 0;
}

#menuItemSortOrder[type=number] {
    -moz-appearance: textfield;
    appearance: textfield;
}

/* Icon Preview Container Styling */
.icon-preview-container {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 12px;
    background: #f8f9fa;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 0;
}

.icon-preview-container:hover {
    border-color: #007bff;
    background: white;
}

.icon-preview-section {
    display: flex;
    align-items: center;
    gap: 15px;
}

.icon-preview-circle {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.icon-preview-circle i {
    font-size: 20px;
    color: white;
}

.icon-preview-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.icon-preview-name {
    font-size: 14px;
    font-weight: 600;
    color: #495057;
    margin: 0;
}

.icon-preview-class {
    font-size: 12px;
    color: #6c757d;
    font-family: 'Courier New', monospace;
    background: #e9ecef;
    padding: 2px 6px;
    border-radius: 4px;
    margin: 0;
}

.choose-icon-btn {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    border: none;
    border-radius: 6px;
    padding: 8px 16px;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 123, 255, 0.3);
}

.choose-icon-btn:hover {
    background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.4);
}

.choose-icon-btn:active {
    transform: translateY(0);
    box-shadow: 0 2px 8px rgba(0, 123, 255, 0.3);
}


.menu-group {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    margin-bottom: 15px;
    background: #fff;
}


.menu-group-content {
    padding: 15px;
}

.menu-module {
    border: 1px solid #e9ecef;
    border-radius: 6px;
    padding: 0px;
    padding-right: 10px;
    margin-bottom: 8px;
    background: #f8f9fa;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.menu-module-info {
    display: flex;
    align-items: center;
}

.menu-module-info i {
    margin-right: 8px;
    color: #495057;
}

.menu-module-actions {
    display: flex;
    gap: 5px;
}

.menu-module.parent-item {
    background-color: #e3f2fd;
    border-left: 3px solid #2196f3;
    font-weight: 600;
}

.menu-module.child-item {
    background-color: #f8f9fa;
    border-left: 3px solid #007bff;
    margin-left: 20px;
}


.drag-handle {
    cursor: move;
}

.drag-handle:hover {
    background-color: #e9ecef;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #6c757d;
}

.icon-item {
    text-align: center;
    padding: 10px;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s;
}

.icon-item:hover {
    background-color: #f8f9fa;
    border-color: #007bff;
}

.icon-item.selected {
    background-color: #e3f2fd;
    border-color: #2196f3;
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

let selectedIconTarget = null;
let selectedIcon = null;
let selectedIconData = null;

// Initialize drag and drop
document.addEventListener('DOMContentLoaded', function() {
    // Initialize sortable functionality
    // initSortable(); // Removed - not implemented
    
    // Initialize icon picker
    // initIconPicker(); // Removed - not implemented
});



// Menu builder functions will be defined globally below

// Check jQuery availability
if (typeof $ === 'undefined') {
    console.error('jQuery is not loaded. Please refresh the page.');
    document.addEventListener('DOMContentLoaded', function() {
        AlertManager.error('Error: jQuery is not loaded. Please refresh the page.');
    });
}

// Form submissions


    // Menu Item Form submission
    $('#menuItemForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const menuItemId = $('#menuItemId').val();
        const url = menuItemId ? '<?php echo APP_URL; ?>/menu/update-menu-item' : '<?php echo APP_URL; ?>/menu/create-menu-item';
        
        
        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-Token': window.csrfToken || ''
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showToast('success', data.message);
                $('#menuItemModal').modal('hide');
                location.reload();
                } else {
                showToast('error', data.error);
                }
        })
        .catch(error => {
            console.error('Form submission error:', error);
            showToast('error', 'An error occurred: ' + error.message);
        });
    });
    
// Toast notification function
} // End of initializejQuery()

// Global functions

// Toast notification function
function showToast(type, message) {
    // Create toast container if it doesn't exist
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
        toastContainer.style.zIndex = '9999';
        document.body.appendChild(toastContainer);
    }
    
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast-notification toast-${type} alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} alert-dismissible fade show`;
    toast.style.marginBottom = '10px';
    toast.style.minWidth = '300px';
    
    // Set icon based on type
    let icon = 'fas fa-info-circle';
    if (type === 'success') icon = 'fas fa-check-circle';
    else if (type === 'error') icon = 'fas fa-exclamation-circle';
    else if (type === 'warning') icon = 'fas fa-exclamation-triangle';
    
    toast.innerHTML = `
        <i class="${icon} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    // Add to container
    toastContainer.appendChild(toast);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (toast.parentNode) {
            toast.classList.remove('show');
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 300);
        }
    }, 5000);
}

function editMenuItem(id) {
    // Check if jQuery is available
    if (typeof $ === 'undefined') {
        console.error('jQuery is not loaded. Please refresh the page.');
        AlertManager.error('Error: jQuery is not loaded. Please refresh the page.');
        return;
    }
    
    $('#menuItemModalLabel').text('Edit Menu Item');
    $('#menuItemForm')[0].reset();
    $('#menuItemId').val(id);
    
    // Load menu item data via AJAX
    fetch(`<?php echo APP_URL; ?>/menu/get-menu-item/${id}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
        .then(data => {
            if (data.success) {
                const item = data.menuItem;
                $('#menuItemName').val(item.name);
                $('#menuItemGroup').val(item.group_id);
                $('#menuItemGroupHidden').val(item.group_id);
                $('#menuItemModule').val(item.module_id || '');
                $('#menuItemIcon').val(item.icon);
                $('#menuItemParent').val(item.parent_id || '');
                $('#menuItemSortOrder').val(item.sort_order);
                $('#menuItemIsParent').prop('checked', item.is_parent == 1);
                $('#menuItemIsActive').prop('checked', item.is_active == 1);
                
                // Control "Is Parent Menu" checkbox based on parent selection
                const parentValue = $('#menuItemParent').val();
                const isParentCheckbox = $('#menuItemIsParent');
                
                if (parentValue && parentValue !== '') {
                    isParentCheckbox.prop('checked', false);
                    isParentCheckbox.prop('disabled', true);
                } else {
                    isParentCheckbox.prop('disabled', false);
                }
                
                // Control Module dropdown based on "Dropdown" checkbox state
                const isChecked = isParentCheckbox.is(':checked');
                const moduleSelect = $('#menuItemModule');
                const requiredAsterisk = $('.module-required-asterisk');
                
                if (isChecked) {
                    moduleSelect.prop('disabled', true);
                    moduleSelect.val('');
                    moduleSelect.prop('required', false);
                    requiredAsterisk.hide();
                } else {
                    moduleSelect.prop('disabled', false);
                    moduleSelect.prop('required', true);
                    requiredAsterisk.show();
                }
                
                $('#menuItemModal').modal('show');
            } else {
                console.error('API error:', data.error);
                showToast('error', data.error);
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            showToast('error', 'Error loading menu item data: ' + error.message);
        });
}

let deleteMenuItemId = null;

function deleteMenuItem(id) {
    // Check if jQuery is available
    if (typeof $ === 'undefined') {
        console.error('jQuery is not loaded. Please refresh the page.');
        AlertManager.error('Error: jQuery is not loaded. Please refresh the page.');
        return;
    }
    
    deleteMenuItemId = id;
    const modal = new bootstrap.Modal(document.getElementById("deleteMenuItemModal"));
    modal.show();
}

function addMenuItem() {
    // Check if jQuery is available
    if (typeof $ === 'undefined') {
        console.error('jQuery is not loaded. Please refresh the page.');
        AlertManager.error('Error: jQuery is not loaded. Please refresh the page.');
        return;
    }
    
    $('#menuItemModalLabel').text('Add Menu Item');
    $('#menuItemForm')[0].reset();
    $('#menuItemId').val('');
    
    // Reset "Is Parent Menu" checkbox state
    $('#menuItemIsParent').prop('disabled', false);
    
    // Reset Module dropdown state
    $('#menuItemModule').prop('disabled', false);
    $('#menuItemModule').prop('required', true);
    $('.module-required-asterisk').show();
    
    // If we're in group mode, pre-select the group
    <?php if (isset($selected_group_id) && $selected_group_id): ?>
    $('#menuItemGroup').val('<?php echo $selected_group_id; ?>');
    $('#menuItemGroupHidden').val('<?php echo $selected_group_id; ?>');
    <?php endif; ?>
    
    // Update hidden input when disabled select changes
    $('#menuItemGroup').on('change', function() {
        $('#menuItemGroupHidden').val($(this).val());
        updateParentDropdown($(this).val());
    });
    
    // Control "Is Parent Menu" checkbox based on Parent Menu selection
    $('#menuItemParent').on('change', function() {
        const parentValue = $(this).val();
        const isParentCheckbox = $('#menuItemIsParent');
        
        if (parentValue && parentValue !== '') {
            // If parent is selected, uncheck and disable "Is Parent Menu"
            isParentCheckbox.prop('checked', false);
            isParentCheckbox.prop('disabled', true);
        } else {
            // If no parent selected, enable "Is Parent Menu"
            isParentCheckbox.prop('disabled', false);
        }
    });
    
    // Control Module dropdown based on "Dropdown" checkbox
    $('#menuItemIsParent').on('change', function() {
        const isChecked = $(this).is(':checked');
        const moduleSelect = $('#menuItemModule');
        const requiredAsterisk = $('.module-required-asterisk');
        
        if (isChecked) {
            // If "Dropdown" is checked, disable module dropdown and reset to default
            moduleSelect.prop('disabled', true);
            moduleSelect.val('');
            moduleSelect.prop('required', false);
            requiredAsterisk.hide();
        } else {
            // If "Dropdown" is unchecked, enable module dropdown and make it required
            moduleSelect.prop('disabled', false);
            moduleSelect.prop('required', true);
            requiredAsterisk.show();
        }
    });
    
    // Function to update parent dropdown based on selected group
    function updateParentDropdown(groupId) {
        const parentSelect = $('#menuItemParent');
        const currentValue = parentSelect.val();
        
        // Clear existing options except "No Parent"
        parentSelect.find('option:not(:first)').remove();
        
        if (groupId) {
            // Fetch parent items for the selected group
            fetch(`<?php echo APP_URL; ?>/menu/get-parent-items/${groupId}`, {
                method: 'GET',
        headers: {
                    'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
                if (data.success && data.parentItems) {
                    data.parentItems.forEach(item => {
                        parentSelect.append(`<option value="${item.id}">${item.name}</option>`);
                    });
                    
                    // Restore previous selection if it's still valid
                    if (currentValue && parentSelect.find(`option[value="${currentValue}"]`).length > 0) {
                        parentSelect.val(currentValue);
        } else {
                        parentSelect.val('');
                    }
                    
                    // Control "Is Parent Menu" checkbox after dropdown update
                    const parentValue = parentSelect.val();
                    const isParentCheckbox = $('#menuItemIsParent');
                    
                    if (parentValue && parentValue !== '') {
                        isParentCheckbox.prop('checked', false);
                        isParentCheckbox.prop('disabled', true);
        } else {
                        isParentCheckbox.prop('disabled', false);
                    }
                    
                    // Control Module dropdown required state based on checkbox
                    const isChecked = isParentCheckbox.is(':checked');
                    const moduleSelect = $('#menuItemModule');
                    const requiredAsterisk = $('.module-required-asterisk');
                    
                    if (isChecked) {
                        moduleSelect.prop('required', false);
                        requiredAsterisk.hide();
            } else {
                        moduleSelect.prop('required', true);
                        requiredAsterisk.show();
                    }
            }
        })
        .catch(error => {
                console.error('Error loading parent items:', error);
            });
        }
    }
    
    $('#menuItemModal').modal('show');
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
                // Remove group from UI
                const groupElement = document.querySelector(`[data-group-id="${id}"]`);
                if (groupElement) {
                    groupElement.remove();
                }
                // Or reload page to refresh the list
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

function openIconPicker(target) {
    // Check if jQuery is available
    if (typeof $ === 'undefined') {
        console.error('jQuery is not loaded. Please refresh the page.');
        AlertManager.error('Error: jQuery is not loaded. Please refresh the page.');
        return;
    }
    
    selectedIconTarget = target;
    selectedIcon = null;
    selectedIconData = null;
    
    // Load icon picker content
    const container = document.getElementById('iconPickerContainer');
    
    // Use the same icon data structure as icon_picker.php
    let availableIcons = <?php echo json_encode($available_icons ?? []); ?>;
    
    // Ensure we have icons from ModuleController.php
    if (!availableIcons || Object.keys(availableIcons).length === 0) {
        console.error('No icons available from ModuleController.php getAvailableIcons()');
        AlertManager.error('Error: No icons available. Please check the ModuleController.php configuration.');
        return;
    }
    
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
                                <button type="button" class="search-clear d-none" id="clearModalSearch">
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
    
    // Show modal
    $('#iconPickerModal').modal('show');
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
    const clearSearchBtn = document.getElementById('clearModalSearch');
    
    // Icon selection
    iconItems.forEach(item => {
        item.addEventListener('click', function() {
            // Remove selected class from all items
            iconItems.forEach(i => i.classList.remove('selected'));
            
            // Add selected class to clicked item
            this.classList.add('selected');
            
            // Store selected icon data
            selectedIconData = {
                icon: this.dataset.icon,
                label: this.dataset.label,
                category: this.dataset.category
            };
            
            // Update preview
            modalIconPreview.className = this.dataset.icon;
            
            // Update preview info
            if (modalIconName) modalIconName.textContent = this.dataset.label;
            if (modalIconClass) modalIconClass.textContent = this.dataset.icon;
            
            // Add selection animation
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    });
    
    // Search functionality
    let searchTimeout;
    const searchIcon = document.querySelector('.search-icon');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const searchTerm = this.value.toLowerCase();
            
            iconItems.forEach(item => {
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
    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', function() {
        searchInput.value = '';
        searchInput.focus();
        
        iconItems.forEach(item => {
            item.style.display = '';
        });
        
        this.style.display = 'none';
        if (searchIcon) searchIcon.style.display = 'block';
    });
    }
    
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

function selectIcon() {
    // Check if jQuery is available
    if (typeof $ === 'undefined') {
        console.error('jQuery is not loaded. Please refresh the page.');
        AlertManager.error('Error: jQuery is not loaded. Please refresh the page.');
        return;
    }
    
    if (selectedIconData) {
        // Update the main form
        document.getElementById(selectedIconTarget).value = selectedIconData.icon;
        
        // Update preview for menu item icon
        if (selectedIconTarget === 'menuItemIcon') {
            const previewIcon = document.getElementById('menuItemIconPreview');
            const previewClass = document.querySelector('.icon-preview-class');
            const previewName = document.querySelector('.icon-preview-name');
            if (previewIcon) {
                previewIcon.className = selectedIconData.icon;
            }
            if (previewClass) {
                previewClass.textContent = selectedIconData.icon;
            }
            if (previewName) {
                previewName.textContent = selectedIconData.label;
            }
        }
        
        // Close modal
        $('#iconPickerModal').modal('hide');
        
        // Reset selected data
        selectedIconData = null;
    } else {
        AlertManager.warning('Please select an icon first!');
    }
}


// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    waitForjQuery();
    
    // Delete menu item confirmation
    document.getElementById("confirmDeleteMenuItem").addEventListener("click", function() {
        if (deleteMenuItemId) {
            // Delete menu item from database
            fetch('<?php echo APP_URL; ?>/menu/delete-menu-item', {
                method: 'POST',
        headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-Token': window.csrfToken || ''
                },
                body: JSON.stringify({
                    id: deleteMenuItemId,
                    _token: window.csrfToken || ''
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
                    const modal = bootstrap.Modal.getInstance(document.getElementById("deleteMenuItemModal"));
                    modal.hide();
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
    });
});

</script>

<style>
/* Menu Builder Hierarchical Structure Styling */
.menu-module {
    border: 1px solid #e9ecef;
    border-radius: 6px;
    margin-bottom: 4px;
    background: white;
    transition: all 0.2s ease;
}

.menu-module:hover {
    border-color: #007bff;
    box-shadow: 0 2px 4px rgba(0,123,255,0.1);
}

.menu-child-indent {
    margin-left: 30px;
    border-left: 3px solid #e9ecef;
    padding-left: 16px;
    background: #f8f9fa;
    position: relative;
}

.menu-inactive {
    opacity: 0.7;
    background: #f8f9fa;
}

.menu-inactive .menu-item-name {
    color: #6c757d;
    text-decoration: line-through;
}

.menu-module-info {
    padding: 12px;
}

.menu-item-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
}

.menu-item-main {
    display: flex;
    align-items: center;
    flex: 1;
}

.menu-item-name {
    font-weight: 500;
    color: #495057;
    font-size: 14px;
}

.menu-item-meta {
    display: flex;
    align-items: center;
    gap: 4px;
}

.badge-sm {
    font-size: 10px;
    padding: 2px 6px;
}

.menu-module-actions {
    display: flex;
    gap: 4px;
    opacity: 0;
    transition: opacity 0.2s ease;
}

.menu-module:hover .menu-module-actions {
    opacity: 1;
}

.menu-module-actions .btn {
    padding: 4px 8px;
    font-size: 12px;
}

/* Visual hierarchy indicators */
.menu-child-indent .menu-item-main i {
    margin-left: 0;
}

/* Parent item styling */
.menu-parent-item {
    border-left: 4px solid #007bff;
}

.menu-parent-item .menu-item-name {
    font-weight: 600;
}

.parent-indicator {
    color: #28a745;
    font-size: 14px;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

/* Enhanced child indentation */
.menu-child-indent::before {
    content: '';
    position: absolute;
    left: -3px;
    top: 0;
    bottom: 0;
    width: 3px;
    background: linear-gradient(to bottom, #007bff, #e9ecef);
    border-radius: 0 2px 2px 0;
}

/* Empty state styling */
.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #6c757d;
}

.empty-state i {
    margin-bottom: 16px;
}

/* Group header styling */
.menu-group-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 2px solid #dee2e6;
}

.menu-group-info {
    display: flex;
    align-items: center;
}

.menu-group-info i {
    color: #007bff;
    margin-right: 8px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .menu-item-content {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    
    .menu-item-meta {
        align-self: flex-end;
    }
    
    .menu-child-indent {
        margin-left: 20px;
        padding-left: 12px;
    }
    
    .parent-indicator {
        font-size: 12px;
    }
}

/* Additional spacing improvements */
.menu-module {
    margin-bottom: 6px;
}

.menu-child-indent {
    margin-bottom: 4px;
}

/* Visual connection between parent and children */
.menu-parent-item + .menu-child-indent {
    margin-top: -2px;
    border-top-left-radius: 0;
    border-top-right-radius: 6px;
}

/* Icon Picker Modal Styling - Copied from menu-management.php */
#iconPickerModal .modal-dialog {
    max-width: 95vw;
    margin: 1rem auto;
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
@media (max-width: 992px) {
    .icon-picker-container .search-section {
        min-width: 200px;
    }
    
    .icon-picker-container .icon-preview-section {
        min-width: 60px;
    }
}

@media (max-width: 768px) {
    #iconPickerModal .modal-dialog {
        max-width: 95vw;
        margin: 0.5rem;
    }
    
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
</style>

<?php
// End output buffering and capture content
$content = ob_get_clean();

// Include the main layout with content
include __DIR__ . '/../layouts/app.php';
?>

