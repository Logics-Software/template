<!-- Setting Menu -->
<div class="row">
    <div class="col-12">
        <div class="form-container">
            <div class="form-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Daftar Group Menu</h5>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="<?php echo APP_URL; ?>/dashboard" class="text-decoration-none">Home</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Daftar Group Menu</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="form-body">
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
                            <table class="table table-md">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Item</th>
                                        <th>Default Role</th>
                                        <th></th>
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
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <div class="d-flex align-items-center">
                                                            <span class="badge bg-primary me-2"><?php echo $group['menu_items_count'] ?? 0; ?></span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-wrap gap-1">
                                                        <?php if (isset($group['default_admin']) && $group['default_admin'] == 1): ?>
                                                            <span class="badge bg-danger">Admin</span>
                                                        <?php endif; ?>
                                                        <?php if (isset($group['default_manajemen']) && $group['default_manajemen'] == 1): ?>
                                                            <span class="badge bg-primary">Manajemen</span>
                                                        <?php endif; ?>
                                                        <?php if (isset($group['default_user']) && $group['default_user'] == 1): ?>
                                                            <span class="badge bg-info">User</span>
                                                        <?php endif; ?>
                                                        <?php if (isset($group['default_marketing']) && $group['default_marketing'] == 1): ?>
                                                            <span class="badge bg-warning">Marketing</span>
                                                        <?php endif; ?>
                                                        <?php if (isset($group['default_customer']) && $group['default_customer'] == 1): ?>
                                                            <span class="badge bg-success">Customer</span>
                                                        <?php endif; ?>
                                                        <?php 
                                                        $hasDefault = (isset($group['default_admin']) && $group['default_admin'] == 1) ||
                                                                      (isset($group['default_manajemen']) && $group['default_manajemen'] == 1) ||
                                                                      (isset($group['default_user']) && $group['default_user'] == 1) ||
                                                                      (isset($group['default_marketing']) && $group['default_marketing'] == 1) ||
                                                                      (isset($group['default_customer']) && $group['default_customer'] == 1);
                                                        if (!$hasDefault):
                                                        ?>
                                                            <span class="text-muted small">-</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-1 min-w-80">
                                                        <button class="btn btn-info btn-sm btn-action" onclick="toggleDetailMenu(<?php echo $group['id']; ?>)"
                                                        data-bs-toggle="tooltip" data-bs-title="Tampilkan Struktur Menu">
                                                            <i class="fas fa-eye"></i>&nbsp;Struktur
                                                        </button>
                                                        <button class="btn btn-success btn-sm btn-action" onclick="editGroup(<?php echo $group['id']; ?>)"
                                                        data-bs-toggle="tooltip" data-bs-title="Edit Group Menu">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-primary btn-sm btn-action" onclick="addDetailMenu(<?php echo $group['id']; ?>)"
                                                        data-bs-toggle="tooltip" data-bs-title="Atur Detail Menu">
                                                            <i class="fas fa-list-check"></i>
                                                        </button>
                                                        <button class="btn btn-danger btn-sm btn-action" onclick="deleteGroup(<?php echo $group['id']; ?>)"
                                                        data-bs-toggle="tooltip" data-bs-title="Hapus Group Menu">
                                                            <i class="fas fa-trash-can"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr id="detail-row-<?php echo $group['id']; ?>" class="detail-row" style="display: none;">
                                                <td colspan="5">
                                                    <div class="p-3 bg-light">
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                            <h6 class="mb-0">Struktur Menu Detail</h6>
                                                            <button class="btn btn-sm btn-primary" onclick="addDetailMenu(<?php echo $group['id']; ?>)">
                                                                <i class="fas fa-plus"></i> Edit/Tambah Detail Menu
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
                                            <td colspan="5" class="text-center text-muted py-4">
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
                <h5 class="modal-title" id="groupModalLabel">Tambag Menu Group</h5>
                <button type="button" class="btn-close" onclick="closeGroupModal()"></button>
            </div>
            <form id="groupForm">
                <div class="modal-body p-4">
                    <input type="hidden" id="groupId" name="id">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="groupName" name="name" placeholder="" required>
                        <label for="groupName">
                            <i class="fas fa-tag me-2"></i>Nama Group Menu
                        </label>
                    </div>
                    <div class="form-floating mb-3">
                        <textarea class="form-control" id="groupDescription" name="description" placeholder="" style="height: 100px"></textarea>
                        <label for="groupDescription">
                            <i class="fas fa-align-left me-2"></i>Deskripsi
                        </label>
                    </div>
                    <div class="mb-3">
                        <div class="row g-2">
                            <!-- Icon Preview -->
                            <div class="col-auto">
                                <div class="d-flex align-items-center justify-content-center bg-light border rounded" style="width: 50px; height: 38px;">
                                    <i id="iconPreview" class="fas fa-folder text-primary"></i>
                                </div>
                            </div>
                            <!-- Input Field -->
                            <div class="col">
                                <input type="text" class="form-control p-2" id="groupIcon" name="icon" value="fas fa-folder" readonly>
                            </div>
                            <!-- Choose Icon Button -->
                            <div class="col-auto">
                                <button type="button" class="btn btn-warning" onclick="openIconPicker()">
                                    <i class="fas fa-search me-1"></i>Pilih Icon
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Collapsible Option -->
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="isCollapsible" name="is_collapsible" value="1">
                            <label class="form-check-label" for="isCollapsible">
                                Dropdown Menu Tertutup (Collapsed)
                            </label>
                        </div>
                    </div>

                    <!-- Default Role Access -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Group Menu (System) ini adalah default untuk role:
                        </label>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="defaultAdmin" name="default_admin" value="1">
                                    <label class="form-check-label" for="defaultAdmin">
                                        <span class="badge bg-danger">Admin</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="defaultManajemen" name="default_manajemen" value="1">
                                    <label class="form-check-label" for="defaultManajemen">
                                        <span class="badge bg-primary">Manajemen</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="defaultUser" name="default_user" value="1">
                                    <label class="form-check-label" for="defaultUser">
                                        <span class="badge bg-info">User</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="defaultMarketing" name="default_marketing" value="1">
                                    <label class="form-check-label" for="defaultMarketing">
                                        <span class="badge bg-warning">Marketing</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="defaultCustomer" name="default_customer" value="1">
                                    <label class="form-check-label" for="defaultCustomer">
                                        <span class="badge bg-success">Customer</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeGroupModal()">Batal</button>
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
                Anda yakun untuk menghapus data group menu ini? Proses ini tidak bisa dibatalkan.
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

            // Populate default role checkboxes
            const defaultAdmin = document.getElementById('defaultAdmin');
            const defaultManajemen = document.getElementById('defaultManajemen');
            const defaultUser = document.getElementById('defaultUser');
            const defaultMarketing = document.getElementById('defaultMarketing');
            const defaultCustomer = document.getElementById('defaultCustomer');
            
            if (defaultAdmin) defaultAdmin.checked = data.group.default_admin == 1;
            if (defaultManajemen) defaultManajemen.checked = data.group.default_manajemen == 1;
            if (defaultUser) defaultUser.checked = data.group.default_user == 1;
            if (defaultMarketing) defaultMarketing.checked = data.group.default_marketing == 1;
            if (defaultCustomer) defaultCustomer.checked = data.group.default_customer == 1;

            // Update icon display
            const iconClass = data.group.icon || 'fas fa-folder';
            const iconPreview = document.getElementById('iconPreview');
            const iconClassDisplay = document.getElementById('iconClassDisplay');
            
            if (iconPreview) iconPreview.className = iconClass;
            if (iconClassDisplay) iconClassDisplay.textContent = iconClass;
        } else {
            console.error('Failed to load group data:', data.error);
            window.Notify.error('Failed to load group data');
        }
    })
    .catch(error => {
        console.error('Error loading group data:', error);
        window.Notify.error('An error occurred while loading group data');
    });

    // Show modal using Bootstrap Modal API
    const groupModal = document.getElementById('groupModal');
    if (groupModal) {
        const modal = new bootstrap.Modal(groupModal);
        modal.show();
    }
};

window.addGroup = function() {
    
    // Reset form and show modal for adding new group
    const modalLabel = document.getElementById('groupModalLabel');
    const groupForm = document.getElementById('groupForm');
    const groupIdField = document.getElementById('groupId');
    
    if (modalLabel) modalLabel.textContent = 'Tambah Menu Group';
    if (groupForm) groupForm.reset();
    if (groupIdField) groupIdField.value = '';
    
    // Reset icon display
    const groupIcon = document.getElementById('groupIcon');
    const iconPreview = document.getElementById('iconPreview');
    const iconClassDisplay = document.getElementById('iconClassDisplay');
    
    if (groupIcon) groupIcon.value = 'fas fa-folder';
    if (iconPreview) iconPreview.className = 'fas fa-folder';
    if (iconClassDisplay) iconClassDisplay.textContent = 'fas fa-folder';
    
    // Reset default role checkboxes
    const defaultAdmin = document.getElementById('defaultAdmin');
    const defaultManajemen = document.getElementById('defaultManajemen');
    const defaultUser = document.getElementById('defaultUser');
    const defaultMarketing = document.getElementById('defaultMarketing');
    const defaultCustomer = document.getElementById('defaultCustomer');
    
    if (defaultAdmin) defaultAdmin.checked = false;
    if (defaultManajemen) defaultManajemen.checked = false;
    if (defaultUser) defaultUser.checked = false;
    if (defaultMarketing) defaultMarketing.checked = false;
    if (defaultCustomer) defaultCustomer.checked = false;
    
    // Show modal using Bootstrap Modal API
    const groupModal = document.getElementById('groupModal');
    if (groupModal) {
        const modal = new bootstrap.Modal(groupModal);
        modal.show();
    }
    
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
                    'X-CSRF-Token': '<?php echo Session::generateCSRF(); ?>'
                },
                body: JSON.stringify({
                    id: deleteGroupId,
                    _token: '<?php echo Session::generateCSRF(); ?>'
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
                    window.Notify.success(data.message || 'Group deleted successfully');
                    // Hide modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById("deleteModal"));
                    modal.hide();
                    // Delay reload to allow notification to be visible
                    window.delayedReload();
                } else if (data && data.error) {
                    window.Notify.error(data.error || 'Failed to delete group');
                }
            })
            .catch(error => {
                console.error('Error deleting group:', error);
                window.Notify.error('An error occurred while deleting the group');
            });
        }
    });
});

// Export configuration
function exportConfig() {
    window.location.href = '<?php echo APP_URL; ?>/menu/export-config';
}

function initializeMenuManagement() {
    
    // Form submissions
    const groupForm = document.getElementById('groupForm');
    if (groupForm) {
        groupForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const groupId = document.getElementById('groupId').value;
            const url = groupId ? 
                '<?php echo APP_URL; ?>/menu/update-group' : 
                '<?php echo APP_URL; ?>/menu/create-group';
            
            
            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-Token': '<?php echo Session::generateCSRF(); ?>'
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (response.status === 403) {
                    window.Notify.error('Access denied. Please refresh the page and try again.');
                    return;
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data && data.success) {
                    window.Notify.success(data.message);
                    // Hide modal using Bootstrap Modal API
                    const modal = bootstrap.Modal.getInstance(document.getElementById('groupModal'));
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
                window.Notify.error('An error occurred while saving the group.');
            });
        });
    }
    
}

// Icon picker functions
let selectedIconData = null;

window.openIconPicker = function() {
    
    // Clear any previous selection
    window.selectedIcon = null;
    updateSelectedIconInfo();
    
    // Clear search input
    const searchInput = document.getElementById('iconSearch');
    if (searchInput) {
        searchInput.value = '';
    }
    
    // Hide clear button
    const clearBtn = document.getElementById('clearSearch');
    if (clearBtn) {
        clearBtn.style.display = 'none';
    }
    
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


    if (!icons || icons.length === 0) {
        container.innerHTML = `
            <div class="text-center text-warning py-4">
                <i class="fas fa-exclamation-triangle"></i>
                <p class="mb-0 mt-2">No icons available</p>
            </div>
        `;
        return;
    }

    // Store icons globally for search functionality
    window.allIcons = icons;
    
    let html = '<div class="row">';
    
    icons.forEach((icon, index) => {
        // Handle both old format (string) and new format (object)
        const iconClass = typeof icon === 'string' ? icon : icon.class;
        const iconLabel = typeof icon === 'string' ? icon : icon.label;
        const iconCategory = typeof icon === 'string' ? 'General' : icon.category;
        
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
                    <i class="${iconClass} me-2" style="font-size: 1.2em;"></i>
                    <div class="flex-grow-1">
                        <div class="small fw-bold">${iconLabel}</div>
                        <div class="small text-muted">${iconCategory}</div>
                    </div>
                </div>
            </div>
        `;
    });
    
    html += '</div>';
    
    // Update icon count
    document.getElementById('iconCount').textContent = icons.length;
    container.innerHTML = html;

    // Add hover effects and selection logic
    const iconItems = container.querySelectorAll('.icon-item');
    iconItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            if (!this.classList.contains('selected')) {
                this.style.backgroundColor = '#f8f9fa';
                this.style.borderColor = '#007bff';
            }
        });
        
        item.addEventListener('mouseleave', function() {
            if (!this.classList.contains('selected')) {
                this.style.backgroundColor = '';
                this.style.borderColor = '';
            }
        });
        
        // Click to select/deselect
        item.addEventListener('click', function(e) {
            // Don't trigger if clicking on checkbox
            if (e.target.type === 'checkbox') return;
            
            const checkbox = this.querySelector('input[type="checkbox"]');
            
            // If this icon is already selected, deselect it
            if (checkbox.checked) {
                checkbox.checked = false;
                this.classList.remove('selected');
                this.style.backgroundColor = '';
                this.style.borderColor = '';
                
                window.selectedIcon = null;
                updateSelectedIconInfo();
            } else {
                // First, uncheck all other icons (single selection)
                const allCheckboxes = document.querySelectorAll('#iconPickerContainer input[type="checkbox"]');
                const allIconItems = document.querySelectorAll('#iconPickerContainer .icon-item');
                
                allCheckboxes.forEach(cb => {
                    cb.checked = false;
                });
                
                allIconItems.forEach(item => {
                    item.classList.remove('selected');
                    item.style.backgroundColor = '';
                    item.style.borderColor = '';
                });
                
                // Now select this icon
                checkbox.checked = true;
                this.classList.add('selected');
                this.style.backgroundColor = '#e3f2fd';
                this.style.borderColor = '#2196f3';
                
                // Update selected icon info
                window.selectedIcon = {
                    class: this.dataset.icon,
                    label: this.dataset.label,
                    category: this.dataset.category
                };
                
                updateSelectedIconInfo();
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
                
                allIconItems.forEach(item => {
                    if (item !== iconItem) {
                        item.classList.remove('selected');
                        item.style.backgroundColor = '';
                        item.style.borderColor = '';
                    }
                });
                
                // Now select this icon
                iconItem.classList.add('selected');
                iconItem.style.backgroundColor = '#e3f2fd';
                iconItem.style.borderColor = '#2196f3';
                
                window.selectedIcon = {
                    class: iconItem.dataset.icon,
                    label: iconItem.dataset.label,
                    category: iconItem.dataset.category
                };
                updateSelectedIconInfo();
            } else {
                iconItem.classList.remove('selected');
                iconItem.style.backgroundColor = '';
                iconItem.style.borderColor = '';
                
                window.selectedIcon = null;
                updateSelectedIconInfo();
            }
        });
    });
    
    // Initialize search functionality
    initializeIconSearch();
}

// Helper function to update selected icon info in footer
function updateSelectedIconInfo() {
    const selectedIconName = document.getElementById('selectedIconName');
    const selectIconBtn = document.getElementById('selectIconBtn');
    
    if (window.selectedIcon) {
        selectedIconName.textContent = window.selectedIcon.label;
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
    if (!window.selectedIcon) {
        window.Notify.warning('Please select an icon first');
        return;
    }

    // Update form fields
    const groupIcon = document.getElementById('groupIcon');
    const iconPreview = document.getElementById('iconPreview');
    const iconClassDisplay = document.getElementById('iconClassDisplay');

    if (groupIcon) groupIcon.value = window.selectedIcon.class;
    if (iconPreview) iconPreview.className = window.selectedIcon.class;
    if (iconClassDisplay) iconClassDisplay.textContent = window.selectedIcon.class;

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

<style>
.menu-modules .menu-module {
    border: 0px;
    border-radius: 0px;
    margin-bottom: 4px;
    color: black;
    transition: all 0.2s ease;
}

.menu-modules .menu-child-indent {
    margin-left: 20px;
    padding-left: 16px;
    position: relative;
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
</style>