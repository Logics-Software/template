<?php
// Generate CSRF token ONCE at the beginning
$csrfToken = Session::generateCSRF();
?>
<!-- Menu Builder -->
<div class="row">
    <!-- Menu Builder Panel -->
    <div class="col-md-12">
        <div class="form-container">
            <div class="form-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Detail Menu</h5>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?php echo APP_URL; ?>/dashboard">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo APP_URL; ?>/menu">Daftar Group Menu</a></li>
                        <li class="breadcrumb-item active">Detail Menu</li>
                    </ol>
                </div>
            </div>

            <div class="form-body">
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
                            // TAMPILKAN URUT DARI ATAS KE BAWAH
                            echo '<table class="table table-hover table-borderless">';
                            echo '<thead class="table-light">';
                            echo '<tr>';
                            echo '<th width="5%"><i class="fas fa-grip-vertical text-muted"></i></th>';
                            echo '<th width="35%">Caption Menu</th>';
                            echo '<th width="20%">Link/Route</th>';
                            echo '<th width="10%">Order</th>';
                            echo '<th width="10%">Status</th>';
                            echo '<th width="15%"></th>';
                            echo '</tr>';
                            echo '</thead>';
                            echo '<tbody id="sortable-menu-items">';
                            
                            $counter = 1;
                            foreach ($menuItems as $item) {
                                $isActive = $item['is_active'] ? '' : 'table-secondary';
                                
                                // JIKA ADA PARENT_ID = INDENTASI
                                $indentStyle = !empty($item['parent_id']) ? 'padding-left: 30px;' : '';
                                
                                echo '<tr class="' . $isActive . ' draggable-row" draggable="true" data-id="' . $item['id'] . '" data-menu-item-id="' . $item['id'] . '">';
                                echo '<td>';
                                echo '<i class="fas fa-grip-vertical text-muted drag-handle cursor-grab"></i>';
                                echo '</td>';
                                echo '<td class="text-secondary" style="' . $indentStyle . '">';
                                echo '<i class="' . ($item['icon'] ?? 'fas fa-circle') . '"></i>&nbsp;';
                                echo ' <strong>' . htmlspecialchars($item['name']) . '</strong></div>';
                                echo '</td>';
                                echo '<td>';
                                // Cek apakah ada module_url dari join atau url dari menu_items
                                $url = $item['module_url'] ?? $item['url'] ?? null;
                                if (!empty($url)) {
                                    echo '<small><a href="' . htmlspecialchars($url) . '" target="_blank" class="text-decoration-none">';
                                    echo '<i class="fas fa-link me-1"></i>' . htmlspecialchars($url);
                                    echo '</a></small>';
                                } else {
                                    echo '<small class="text-muted">-</small>';
                                }
                                echo '</td>';
                                echo '<td>' . $item['sort_order'] . '</td>';
                                echo '<td>';
                                if ($item['is_active']) {
                                    echo '<span class="badge bg-success">Active</span>';
                                } else {
                                    echo '<span class="badge bg-secondary">Inactive</span>';
                                }
                                echo '</td>';
                                echo '<td>';
                                echo '<div class="d-flex gap-1 min-w-80">';
                                echo '<button class="btn btn-primary btn-sm btn-action" onclick="editMenuItem(' . $item['id'] . ')" data-bs-toggle="tooltip" data-bs-title="Edit Detail Menu">';
                                                echo '<i class="fas fa-edit"></i>';
                                                echo '</button>';
                                echo '<button class="btn btn-danger btn-sm btn-action" onclick="deleteMenuItem(' . $item['id'] . ')" data-bs-toggle="tooltip" data-bs-title="Hapus Detail Menu">';
                                                echo '<i class="fas fa-trash-can"></i>';
                                                echo '</button>';
                                                echo '</div>';
                                echo '</td>';
                                echo '</tr>';
                                
                                $counter++;
                            }
                            
                            echo '</tbody>';
                            echo '</table>';
                            ?>
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
                <h5 class="modal-title" id="menuItemModalLabel">Tambah Detail Menu</h5>
                <button type="button" class="btn-close" onclick="closeMenuItemModal()"></button>
            </div>
            <form id="menuItemForm">
                <div class="modal-body p-4">
                    <input type="hidden" id="menuItemId" name="id">
                    <input type="hidden" id="menuItemGroupId" name="group_id" value="<?php echo $selected_group['id']; ?>">
                    <input type="hidden" name="_token" value="<?php echo $csrfToken; ?>">
                    
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="menuItemName" name="name" placeholder="" required>
                        <label for="menuItemName">
                            <i class="fas fa-tag me-2"></i>Caption Menu
                        </label>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-floating mb-3">
                                <select class="form-control" id="menuItemParent" name="parent_id">
                                    <option value="">Root Menu / Dropdown Menu</option>
                                </select>
                                <label for="menuItemParent">
                                    <i class="fas fa-sitemap me-2"></i>Tipe Menu
                                </label>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="menuItemIsParent" name="is_parent" value="1">
                                <label class="form-check-label" for="menuItemIsParent">
                                    Menu Induk / Dropdown Menu
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <select class="form-control" id="menuItemModule" name="module_id">
                            <option value="">Tanpa Modul</option>
                            <!-- Routes will be loaded dynamically -->
                        </select>
                        <label for="menuItemModule">
                            <i class="fas fa-cube me-2"></i>Modul
                        </label>
                    </div>
                    
                    <div class="mb-3">
                        <div class="row g-2">
                            <!-- Icon Preview -->
                            <div class="col-auto">
                                <div class="d-flex align-items-center justify-content-center bg-light border rounded" style="width: 50px; height: 38px;">
                                    <i id="menuItemIconPreview" class="fas fa-circle text-primary"></i>
                                </div>
                            </div>

                            <!-- Input Field -->
                            <div class="col">
                                <input type="text" class="form-control p-2" id="menuItemIcon" name="icon" value="fas fa-circle" readonly>
                            </div>
                            <!-- Choose Icon Button -->
                            <div class="col-auto">
                                <button type="button" class="btn btn-warning" onclick="openIconPicker('menuItemIcon')">
                                    <i class="fas fa-search me-1"></i>Pilih Icon
                                    </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control no-spinners" id="menuItemSortOrder" name="sort_order" value="0" placeholder="">
                                <label for="menuItemSortOrder">
                                    <i class="fas fa-sort me-2"></i>Nomor Urut
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="menuItemIsActive" name="is_active" value="1" checked>
                                <label class="form-check-label" for="menuItemIsActive">
                                    Aktif
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeMenuItemModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Icon Picker Modal -->
<div class="modal fade" id="iconPickerModal" tabindex="-1" aria-labelledby="iconPickerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content d-flex flex-column" style="height: 80vh;">
            <!-- Sticky Header -->
            <div class="modal-header sticky-top bg-white border-bottom">
                <h5 class="modal-title" id="iconPickerModalLabel">Pilih Icon</h5>
                <button type="button" class="btn-close" onclick="closeIconPickerModal()"></button>
            </div>
            
            <!-- Sticky Search -->
            <div class="px-3 py-2 sticky-top bg-white border-bottom mt-2" style="top: 60px; z-index: 1040;">
                <div class="input-group">
                    <input type="text" class="form-control" id="iconSearch" placeholder="Search icons by name or category...">
                    <span class="input-group-text" id="searchIcon" style="border-top-right-radius: 0.375rem; border-bottom-right-radius: 0.375rem; background-color: #f8f9fa; border-color: #ced4da;">
                        <i class="fas fa-search"></i>
                    </span>
                    <button class="btn btn-outline-secondary" type="button" id="clearSearch" style="display: none; border-top-right-radius: 0.375rem; border-bottom-right-radius: 0.375rem; background-color: #f8f9fa; border-color: #ced4da;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="mt-2">
                    <small class="text-muted">
                        <span id="iconCount">0</span> icon tersedia
                    </small>
                </div>
            </div>
            
            <!-- Scrollable Content -->
            <div class="modal-body flex-grow-1 overflow-auto" style="max-height: calc(80vh - 200px);">
                <div id="iconPickerContainer" class="icon-picker-container">
                    <!-- Icons will be loaded here -->
                </div>
            </div>
            
            <!-- Sticky Footer -->
            <div class="modal-footer sticky-bottom bg-white border-top">
                <div class="d-flex justify-content-between w-100">
                    <div>
                        <small class="text-muted">
                            <span class="fw-bold" id="selectedIconName">Tidak ada</span>
                        </small>
                    </div>
                    <div>
                        <button type="button" class="btn btn-secondary me-2" onclick="closeIconPickerModal()">Batal</button>
                        <button type="button" class="btn btn-primary" onclick="selectIcon()" id="selectIconBtn" disabled>Pilih Icon</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Anda yakin akan menghapu data detail menu ini? Proses ini tidak bisa dibatalkan.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Hapus</button>
            </div>
        </div>
    </div>
</div>

<script>
// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeMenuBuilder();
});

// Global variables
let selectedIconData = null;
let currentIconTarget = null;
let deleteMenuItemId = null;

// Load main routes for module dropdown
function loadMainRoutes() {
    return fetch('<?php echo APP_URL; ?>/menu/get-main-routes', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.routes) {
            const moduleSelect = document.getElementById('menuItemModule');
            if (moduleSelect) {
                // Clear existing options except "No Module"
                moduleSelect.innerHTML = '<option value="">No Module</option>';
                
                // Add main routes as options (using module ID as value)
                data.routes.forEach(route => {
                    const option = document.createElement('option');
                    option.value = route.id; // Use module ID instead of path
                    option.textContent = route.name;
                    option.setAttribute('data-path', route.path);
                    moduleSelect.appendChild(option);
                });
            }
        }
    })
    .catch(error => {
        console.error('Error loading main routes:', error);
    });
}

// Menu builder functions
function initializeMenuBuilder() {
    
    // Load main routes for module dropdown
    loadMainRoutes();
    
    // Prevent body scroll when modal is open
    const menuItemModal = document.getElementById('menuItemModal');
    if (menuItemModal) {
        menuItemModal.addEventListener('show.bs.modal', function() {
            document.body.style.overflow = 'hidden';
            document.body.style.height = '100vh';
        });
        
        menuItemModal.addEventListener('hide.bs.modal', function() {
            document.body.style.overflow = '';
            document.body.style.height = '';
        });
    }
    
    // Form submissions
    const menuItemForm = document.getElementById('menuItemForm');
    if (menuItemForm) {
        menuItemForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validation Rule 3: If "Is Parent Menu" is unchecked, Module must be selected
            const isParentChecked = document.getElementById('menuItemIsParent').checked;
            const moduleValue = document.getElementById('menuItemModule').value;
            const parentValue = document.getElementById('menuItemParent').value;
            
            // If not a parent menu and not a child menu (no parent selected), module is required
            if (!isParentChecked && !parentValue && !moduleValue) {
                window.Notify.error('Module harus dipilih jika bukan Parent Menu dan bukan Child Menu!');
                return;
            }
            
            const formData = new FormData(this);
            const menuItemId = document.getElementById('menuItemId').value;
            const url = menuItemId ? 
                '<?php echo APP_URL; ?>/menu/update-menu-item' : 
                '<?php echo APP_URL; ?>/menu/create-menu-item';
            
            // Get CSRF token from hidden field (don't generate new one!)
            const csrfToken = this.querySelector('input[name="_token"]').value;
            
            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (response.status === 403) {
                    window.Notify.error('Access denied. Please refresh the page and try again.');
                    return;
                }
                return response.json();
            })
            .then(data => {
                if (data && data.success) {
                    window.Notify.success(data.message);
                    // Hide modal using Bootstrap Modal API
                    const modal = bootstrap.Modal.getInstance(document.getElementById('menuItemModal'));
                    if (modal) {
                        modal.hide();
                    }
                    // Delay reload to allow notification to be visible
                    window.delayedReload();
                } else if (data && data.error) {
                    window.Notify.error(data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                window.Notify.error('An error occurred while saving the menu item.');
            });
        });
    }
    
    // Delete confirmation
    const confirmDeleteBtn = document.getElementById('confirmDelete');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function() {
            confirmDeleteMenuItem();
        });
    }
    
    // Validation logic for Parent Menu, Module, and Tipe Menu
    const isParentCheckbox = document.getElementById('menuItemIsParent');
    const moduleSelect = document.getElementById('menuItemModule');
    const parentSelect = document.getElementById('menuItemParent');
    
    // Event listeners
    if (isParentCheckbox && moduleSelect && parentSelect) {
        // When "Is Parent Menu" checkbox changes
        isParentCheckbox.addEventListener('change', function() {
            updateMenuFormState();
        });
        
        // When "Tipe Menu" (parent) dropdown changes
        parentSelect.addEventListener('change', function() {
            updateMenuFormState();
        });
    }
}

// Global function to update form state based on selections
window.updateMenuFormState = function() {
    const isParentCheckbox = document.getElementById('menuItemIsParent');
    const moduleSelect = document.getElementById('menuItemModule');
    const parentSelect = document.getElementById('menuItemParent');
    
    if (!isParentCheckbox || !moduleSelect || !parentSelect) return;
    
    const isParentChecked = isParentCheckbox.checked;
    const hasParent = parentSelect.value !== '';
    
    // Rule 1: If Tipe Menu is selected (not Root/Dropdown), disable and uncheck "Is Parent Menu"
    if (hasParent) {
        isParentCheckbox.checked = false;
        isParentCheckbox.disabled = true;
    } else {
        isParentCheckbox.disabled = false;
    }
    
    // Rule 2: If "Is Parent Menu" is checked, disable Module and set to "No Module"
    if (isParentChecked) {
        moduleSelect.value = '';
        moduleSelect.disabled = true;
    } else {
        moduleSelect.disabled = false;
    }
}

// Global functions that can be called from onclick
window.addMenuItem = function() {
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
    
    // Update form state for validation
    setTimeout(() => {
        updateMenuFormState();
    }, 100);
    
    // Show modal using Bootstrap Modal API
    const menuItemModal = document.getElementById('menuItemModal');
    if (menuItemModal) {
        // Get existing instance or create new one
        let modal = bootstrap.Modal.getInstance(menuItemModal);
        if (!modal) {
            modal = new bootstrap.Modal(menuItemModal);
        }
        modal.show();
    }
};

window.editMenuItem = function(id) {
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
            const menuItemIcon = document.getElementById('menuItemIcon');
            const menuItemSortOrder = document.getElementById('menuItemSortOrder');
            const menuItemParent = document.getElementById('menuItemParent');
            const menuItemModule = document.getElementById('menuItemModule');
            const menuItemIsActive = document.getElementById('menuItemIsActive');
            const menuItemIsParent = document.getElementById('menuItemIsParent');
            
            if (menuItemId) menuItemId.value = data.menuItem.id;
            if (menuItemName) menuItemName.value = data.menuItem.name;
            if (menuItemIcon) menuItemIcon.value = data.menuItem.icon || 'fas fa-circle';
            if (menuItemSortOrder) menuItemSortOrder.value = data.menuItem.sort_order || 0;
            if (menuItemIsActive) menuItemIsActive.checked = data.menuItem.is_active == 1;
            if (menuItemIsParent) menuItemIsParent.checked = data.menuItem.is_parent == 1;

            // Update icon display
            const iconClass = data.menuItem.icon || 'fas fa-circle';
            const iconPreview = document.getElementById('menuItemIconPreview');
            const iconClassDisplay = document.getElementById('menuItemIconClassDisplay');
            
            if (iconPreview) iconPreview.className = iconClass;
            if (iconClassDisplay) iconClassDisplay.textContent = iconClass;
            
            // Load both parent items and main routes, then set values
            Promise.all([
                loadParentItems(data.menuItem.id),
                loadMainRoutes()
            ]).then(() => {
                // Set parent value
                if (menuItemParent && data.menuItem.parent_id) {
                    menuItemParent.value = data.menuItem.parent_id;
                }
                
                // Set module value (now using ID directly)
                if (menuItemModule && data.menuItem.module_id) {
                    // module_id is already an integer ID from database
                    menuItemModule.value = data.menuItem.module_id;
                }
                
                // Update form state for validation after all data is loaded
                setTimeout(() => {
                    updateMenuFormState();
                }, 100);
            }).catch(error => {
                console.error('Error loading data:', error);
            });
            
            // Update modal label
            const modalLabel = document.getElementById('menuItemModalLabel');
            if (modalLabel) modalLabel.textContent = 'Edit Menu Item';
            
            // Show modal using Bootstrap Modal API
            const menuItemModal = document.getElementById('menuItemModal');
            if (menuItemModal) {
                // Get existing instance or create new one
                let modal = bootstrap.Modal.getInstance(menuItemModal);
                if (!modal) {
                    modal = new bootstrap.Modal(menuItemModal);
                }
                modal.show();
            }
        } else {
            console.error('Failed to load menu item data:', data.error);
            window.Notify.error('Failed to load menu item data');
        }
    })
    .catch(error => {
        console.error('Error loading menu item data:', error);
        window.Notify.error('An error occurred while loading menu item data');
    });
};

window.addSubMenuItem = function(parentId) {
    // Clear form fields
    const menuItemId = document.getElementById('menuItemId');
    const menuItemName = document.getElementById('menuItemName');
    const menuItemIcon = document.getElementById('menuItemIcon');
    const menuItemSortOrder = document.getElementById('menuItemSortOrder');
    const menuItemParent = document.getElementById('menuItemParent');
    const menuItemIsActive = document.getElementById('menuItemIsActive');
    const menuItemModule = document.getElementById('menuItemModule');
    
    if (menuItemId) menuItemId.value = '';
    if (menuItemName) menuItemName.value = '';
    if (menuItemIcon) menuItemIcon.value = 'fas fa-circle';
    if (menuItemSortOrder) menuItemSortOrder.value = '';
    if (menuItemIsActive) menuItemIsActive.checked = true;
    if (menuItemModule) menuItemModule.value = '';
    
    // Set parent value
    if (menuItemParent && parentId) {
        menuItemParent.value = parentId;
    }
    
    // Update icon preview
    const iconPreview = document.getElementById('iconPreview');
    if (iconPreview) {
        iconPreview.className = 'fas fa-circle';
    }
    
    // Load parent items
    loadParentItems();
    
    // Update form state for validation
    setTimeout(() => {
        updateMenuFormState();
    }, 100);
    
    // Update modal label
    const modalLabel = document.getElementById('menuItemModalLabel');
    if (modalLabel) modalLabel.textContent = 'Add Sub Menu Item';
    
    // Show modal using Bootstrap Modal API
    const menuItemModal = document.getElementById('menuItemModal');
    if (menuItemModal) {
        // Get existing instance or create new one
        let modal = bootstrap.Modal.getInstance(menuItemModal);
        if (!modal) {
            modal = new bootstrap.Modal(menuItemModal);
        }
        modal.show();
    }
};

window.deleteMenuItem = function(id) {
    deleteMenuItemId = id;
    const deleteModal = document.getElementById("deleteModal");
    // Get existing instance or create new one
    let modal = bootstrap.Modal.getInstance(deleteModal);
    if (!modal) {
        modal = new bootstrap.Modal(deleteModal);
    }
    modal.show();
};

function confirmDeleteMenuItem() {
    const id = deleteMenuItemId;
    if (!id) return;
    
    // Get CSRF token from form (don't generate new one!)
    const csrfToken = document.querySelector('input[name="_token"]').value;
    
    fetch('<?php echo APP_URL; ?>/menu/delete-menu-item', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            id: id,
            _token: csrfToken
        })
    })
    .then(response => {
        if (response.status === 403) {
            window.Notify.error('Access denied. Please refresh the page and try again.');
            return;
        }
        return response.json();
    })
    .then(data => {
        if (data && data.success) {
            window.Notify.success(data.message || 'Menu item deleted successfully');
            // Hide modal
            const modal = bootstrap.Modal.getInstance(document.getElementById("deleteModal"));
            if (modal) {
                modal.hide();
            }
            // Delay reload to allow notification to be visible
            window.delayedReload();
        } else if (data && data.error) {
            window.Notify.error(data.error || 'Failed to delete menu item');
        }
    })
    .catch(error => {
        console.error('Error deleting menu item:', error);
        window.Notify.error('An error occurred while deleting the menu item');
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
    if (!parentSelect) return Promise.resolve();
    
    const groupId = document.getElementById('menuItemGroupId').value;
    if (!groupId) return Promise.resolve();
    
    // Clear existing options except the first one
    parentSelect.innerHTML = '<option value="">Root Menu / Dropdown Menu</option>';
    
    return fetch(`<?php echo APP_URL; ?>/menu/get-parent-items/${groupId}`, {
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
    
    // Load icon picker content
    const container = document.getElementById('iconPickerContainer');
    if (!container) {
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
            // Initialize search functionality after icons are loaded
            initializeIconSearch();
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
    
    icons.forEach((icon, index) => {
        const iconClass = icon.class;
        const iconLabel = icon.label;
        const iconCategory = icon.category;
        
        html += `
            <div class="col-md-3 col-sm-4 col-6 mb-3">
                <div class="icon-item d-flex align-items-center p-2 border rounded cursor-pointer" 
                     data-icon="${iconClass}" 
                     data-label="${iconLabel}"
                     data-category="${iconCategory}"
                     data-search-text="${iconLabel.toLowerCase()} ${iconCategory.toLowerCase()} ${iconClass.toLowerCase()}"
                     style="cursor: pointer; transition: all 0.2s;">
                    <div class="form-check me-2">
                        <input class="form-check-input" type="checkbox" value="${iconClass}" id="icon_${index}">
                    </div>
                    <i class="${iconClass} me-2"></i>
                    <span class="small">${iconLabel}</span>
                </div>
            </div>
        `;
    });
    
    html += '</div>';
    container.innerHTML = html;

    // Add hover effects and selection logic
    const iconItems = container.querySelectorAll('.icon-item');
    iconItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            if (!this.classList.contains('selected')) {
                this.style.backgroundColor = '#f8f9fa';
            }
        });
        
        item.addEventListener('mouseleave', function() {
            if (!this.classList.contains('selected')) {
                this.style.backgroundColor = '';
            }
        });
        
        item.addEventListener('click', function() {
            const checkbox = this.querySelector('input[type="checkbox"]');
            if (checkbox) {
                checkbox.checked = !checkbox.checked;
                
                if (checkbox.checked) {
                    // First, uncheck all other icons (single selection)
                    const allCheckboxes = document.querySelectorAll('#iconPickerContainer input[type="checkbox"]');
                    const allIconItems = document.querySelectorAll('#iconPickerContainer .icon-item');
                    
                    allCheckboxes.forEach(cb => {
                        cb.checked = false;
                    });
                    
                    allIconItems.forEach(i => {
                        i.classList.remove('selected');
                    });
                    
                    // Check current checkbox and select current item
                    checkbox.checked = true;
                    this.classList.add('selected');
                    
                    // Store selected icon data
                    selectedIconData = {
                        class: this.dataset.icon,
                        label: this.dataset.label,
                        category: this.dataset.category
                    };
                    
                    // Update selected icon info
                    updateSelectedIconInfo();
                } else {
                    this.classList.remove('selected');
                    selectedIconData = null;
                    updateSelectedIconInfo();
                }
            }
        });
        
        // Checkbox change handler
        const checkbox = item.querySelector('input[type="checkbox"]');
        checkbox.addEventListener('change', function() {
            const iconItem = this.closest('.icon-item');
            
            if (this.checked) {
                // First, uncheck all other checkboxes (single selection)
                const allCheckboxes = document.querySelectorAll('#iconPickerContainer input[type="checkbox"]');
                const allIconItems = document.querySelectorAll('#iconPickerContainer .icon-item');
                
                allCheckboxes.forEach(cb => {
                    if (cb !== this) {
                        cb.checked = false;
                    }
                });
                
                allIconItems.forEach(i => {
                    i.classList.remove('selected');
                });
                
                // Select current item
            iconItem.classList.add('selected');

            // Store selected icon data
            selectedIconData = {
                class: iconItem.dataset.icon,
                label: iconItem.dataset.label,
                category: iconItem.dataset.category
            };

                // Update selected icon info
                updateSelectedIconInfo();
            } else {
                iconItem.classList.remove('selected');
                selectedIconData = null;
                updateSelectedIconInfo();
            }
        });
    });
}

function updateSelectedIconInfo() {
    const selectedIconName = document.getElementById('selectedIconName');
    const selectIconBtn = document.getElementById('selectIconBtn');
    
    if (selectedIconData) {
        selectedIconName.textContent = selectedIconData.label;
        selectIconBtn.disabled = false;
    } else {
        selectedIconName.textContent = 'None';
        selectIconBtn.disabled = true;
    }
}

// Initialize search functionality
function initializeIconSearch() {
    const searchInput = document.getElementById('iconSearch');
    const clearBtn = document.getElementById('clearSearch');
    const searchIcon = document.getElementById('searchIcon');
    
    if (!searchInput) return;
    
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        const iconItems = document.querySelectorAll('.icon-item');
        let visibleCount = 0;
        
        iconItems.forEach(item => {
            const searchText = item.dataset.searchText || '';
            const isVisible = searchText.includes(searchTerm);
            
            if (isVisible) {
                item.closest('.col-md-3').style.display = '';
                visibleCount++;
            } else {
                item.closest('.col-md-3').style.display = 'none';
            }
        });
        
        // Update icon count
        document.getElementById('iconCount').textContent = visibleCount;
        
        // Switching logic: Show search icon when empty, show clear button when has text
        if (searchTerm) {
            searchIcon.style.display = 'none';
            clearBtn.style.display = '';
        } else {
            searchIcon.style.display = '';
            clearBtn.style.display = 'none';
        }
    });
    
    // Clear search functionality
    clearBtn.addEventListener('click', function() {
        searchInput.value = '';
        searchInput.dispatchEvent(new Event('input'));
        searchInput.focus();
    });
    
    // Initialize: Show search icon, hide clear button
    searchIcon.style.display = '';
    clearBtn.style.display = 'none';
}

window.selectIcon = function() {
    if (!selectedIconData) {
        window.Notify.warning('Please select an icon first');
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

    window.Notify.success('Icon selected successfully');
};

// Legacy showToast functions removed - now using window.Notify system
</script>

<!-- Include drag and drop utility -->
<script src="<?php echo BASE_URL; ?>assets/js/drag-drop.js"></script>

<script>
// Initialize drag and drop for menu items
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize if there are menu items
    const sortableBody = document.getElementById('sortable-menu-items');
    if (sortableBody && sortableBody.children.length > 0) {
        initDragDrop({
            sortOrderUrl: '<?php echo BASE_URL; ?>menu/update-menu-sort',
            csrfToken: '<?php echo $csrfToken; ?>',
            onSuccess: function(data) {
                window.Notify.success(data.message || 'Urutan menu berhasil diperbarui!');
            },
            onError: function(data) {
                window.Notify.error(data.error || 'Gagal memperbarui urutan menu');
                window.delayedReload();
            }
        });
    }
});
</script>

        <style>
        .menu-modules .menu-module {
            border: 0px;
            border-radius: 0px;
            margin-bottom: 4px;
            color: black;
            transition: all 0.2s ease;
        }

        .menu-modules .menu-child-indent {
            margin-left: 20px !important;
            padding-left: 16px;
            position: relative;
        }

        /* Fix dropdown select visibility */
        .form-floating select.form-control {
            color: #212529 !important;
            background-color: #fff !important;
        }

        .form-floating select.form-control option {
            color: #212529 !important;
            background-color: #fff !important;
        }

        .form-floating select.form-control:focus {
            color: #212529 !important;
            background-color: #fff !important;
            border-color: #86b7fe !important;
        }

        /* Prevent body scroll when modal is open */
        body.modal-open {
            overflow: hidden !important;
            height: 100vh !important;
        }

        /* Ensure modal backdrop covers full screen */
        .modal-backdrop {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            z-index: 1040 !important;
        }

        /* Modal content positioning */
        .modal {
            z-index: 1050 !important;
        }

        /* Icon Picker Styles */
        .icon-picker-container .icon-item {
            transition: all 0.2s ease;
            cursor: pointer;
            user-select: none;
        }

        .icon-picker-container .icon-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .icon-picker-container .icon-item.selected {
            background-color: #e3f2fd !important;
            border-color: #2196f3 !important;
            box-shadow: 0 0 0 2px rgba(33, 150, 243, 0.2);
        }

        .icon-picker-container .icon-item.selected .form-check-input {
            border-color: #2196f3;
            background-color: #2196f3;
        }

        .icon-picker-container .icon-item .form-check-input {
            margin-top: 0;
            margin-bottom: 0;
        }

        .icon-picker-container .icon-item .form-check-input:checked {
            background-color: #2196f3;
            border-color: #2196f3;
        }

        /* Disable text selection on icon items */
        .icon-picker-container .icon-item * {
            pointer-events: none;
        }

        .icon-picker-container .icon-item .form-check-input {
            pointer-events: auto;
        }

        /* Remove spinners from number input */
        .no-spinners::-webkit-outer-spin-button,
        .no-spinners::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .no-spinners {
            -moz-appearance: textfield;
            appearance: textfield;
        }
</style>