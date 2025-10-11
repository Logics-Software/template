<div class="row">
    <div class="col-12">
        <div class="form-container modules-form-loading" id="moduleFormCard">
            <div class="form-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Tambah Modul Baru</h5>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="<?php echo APP_URL; ?>/dashboard" class="text-decoration-none">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="<?php echo APP_URL; ?>/modules" class="text-decoration-none">Daftar Module</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
            <div class="form-body" id="moduleFormBody">
                <form method="POST" action="<?php echo APP_URL; ?>/modules" id="createModuleForm">
                    <input type="hidden" name="_token" value="<?php echo $csrf_token; ?>">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3 loading" id="captionField">
                                <input type="text" class="form-control" id="caption" name="caption" placeholder="Caption" required disabled>
                                <label for="caption">Caption <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3 loading" id="linkField">
                                <select class="form-select" id="link" name="link" required disabled>
                                    <option value="">Loading routes...</option>
                                </select>
                                <label for="link">Link <span class="text-danger">*</span></label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3 pr-5">
                                <label for="logo" class="form-label">Icon Modul <span class="text-danger">*</span></label>
                                <div class="icon-picker-loading" id="iconPickerLoading">
                                    <!-- Icon picker will be loaded here -->
                                </div>
                                <div id="iconPickerContainer" style="display: none;">
                                    <?php require_once __DIR__ . '/icon_picker.php'; ?>
                                    <?php renderIconPicker('', 'logo', 'logo', $available_icons); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <label class="form-label mb-0 me-2">Hak Akses Role</label>
                                    <div class="d-flex gap-1">
                                        <button type="button" class="btn btn-outline-secondary btn-sm px-3" id="toggleAllRoles" title="Pilih Semua Role">
                                            <i class="fas fa-check-double me-1"></i>
                                            <span>Pilih Semua</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="row" id="roleCheckboxesContainer">
                                    <div class="col-md-2">
                                        <div class="form-check">
                                            <input class="form-check-input role-checkbox loading" type="checkbox" id="admin" name="admin" disabled>
                                            <label class="form-check-label" for="admin">
                                                <span class="badge bg-danger">Admin</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-check">
                                            <input class="form-check-input role-checkbox loading" type="checkbox" id="manajemen" name="manajemen" disabled>
                                            <label class="form-check-label" for="manajemen">
                                                <span class="badge bg-primary">Manajemen</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-check">
                                            <input class="form-check-input role-checkbox loading" type="checkbox" id="user" name="user" disabled>
                                            <label class="form-check-label" for="user">
                                                <span class="badge bg-info">User</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-check">
                                            <input class="form-check-input role-checkbox loading" type="checkbox" id="marketing" name="marketing" disabled>
                                            <label class="form-check-label" for="marketing">
                                                <span class="badge bg-warning">Marketing</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-check">
                                            <input class="form-check-input role-checkbox loading" type="checkbox" id="customer" name="customer" disabled>
                                            <label class="form-check-label" for="customer">
                                                <span class="badge bg-success">Customer</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </form>
            </div>
            
            <!-- Form Footer -->
            <div class="form-footer d-flex justify-content-between align-items-center">
                <a href="<?php echo APP_URL; ?>/modules" class="btn btn-secondary">
                    <i class="fas fa-times me-1"></i>Batal
                </a>
                <button type="submit" form="createModuleForm" class="btn btn-primary loading" id="createBtn" disabled>
                    <i class="fas fa-save me-1"></i>Simpan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    
    // Check if all required elements exist
    const requiredElements = [
        'moduleFormCard',
        'captionField', 
        'linkField',
        'iconPickerLoading',
        'iconPickerContainer',
        'createBtn'
    ];
    
    const missingElements = requiredElements.filter(id => !document.getElementById(id));
    if (missingElements.length > 0) {
        console.error('Missing required elements:', missingElements);
    }
    
    // Initialize loading state
    initializeFormLoading();
    
    // Toggle All Roles functionality
    const toggleAllBtn = document.getElementById('toggleAllRoles');
    const roleCheckboxes = document.querySelectorAll('.role-checkbox');
    
    
    // Function to initialize form loading
    function initializeFormLoading() {
        
        // Simulate data loading with CSS styling first
        setTimeout(() => {
            loadFormData();
        }, 1000); // 1 second delay to show loading state
    }
    
    // Function to load form data
    function loadFormData() {
        
        // Remove loading states
        const formCard = document.getElementById('moduleFormCard');
        const captionField = document.getElementById('captionField');
        const linkField = document.getElementById('linkField');
        
        if (formCard) {
            formCard.classList.remove('modules-form-loading');
        }
        
        if (captionField) {
            captionField.classList.remove('loading');
        }
        
        if (linkField) {
            linkField.classList.remove('loading');
        }
        
        // Enable form fields
        const captionInput = document.getElementById('caption');
        const linkSelect = document.getElementById('link');
        
        if (captionInput) {
            captionInput.disabled = false;
        }
        
        if (linkSelect) {
            linkSelect.disabled = false;
        }
        
        // Load routes data
        loadRoutesData();
        
        // Load icon picker
        loadIconPicker();
        
        // Enable role checkboxes
        enableRoleCheckboxes();
        
        // Enable submit button
        const createBtn = document.getElementById('createBtn');
        if (createBtn) {
            createBtn.disabled = false;
            createBtn.classList.remove('loading');
        }
        
    }
    
    // Function to load routes data
    function loadRoutesData() {
        const linkSelect = document.getElementById('link');
        
        if (!linkSelect) {
            return;
        }
        
        // Clear loading option
        linkSelect.innerHTML = '<option value="">Pilih route/link...</option>';
        
        // Check if routes data is available
        try {
            const routes = <?php echo json_encode($available_routes ?? []); ?>;
            
            if (routes && routes.length > 0) {
                // Add routes
                routes.forEach(route => {
                    const option = document.createElement('option');
                    option.value = route.value;
                    option.setAttribute('data-description', route.description);
                    option.textContent = route.label + ' (' + route.value + ')';
                    linkSelect.appendChild(option);
                });
            } else {
                // Fallback routes if no data available
                const fallbackRoutes = [
                    { value: '/dashboard', label: 'Dashboard', description: 'Main dashboard page' },
                    { value: '/users', label: 'Users', description: 'User management page' },
                    { value: '/modules', label: 'Modules', description: 'Module management page' },
                    { value: '/menu', label: 'Menu', description: 'Menu management page' }
                ];
                
                fallbackRoutes.forEach(route => {
                    const option = document.createElement('option');
                    option.value = route.value;
                    option.setAttribute('data-description', route.description);
                    option.textContent = route.label + ' (' + route.value + ')';
                    linkSelect.appendChild(option);
                });
            }
        } catch (error) {
            // Fallback routes
            const fallbackRoutes = [
                { value: '/dashboard', label: 'Dashboard', description: 'Main dashboard page' },
                { value: '/users', label: 'Users', description: 'User management page' }
            ];
            
            fallbackRoutes.forEach(route => {
                const option = document.createElement('option');
                option.value = route.value;
                option.setAttribute('data-description', route.description);
                option.textContent = route.label + ' (' + route.value + ')';
                linkSelect.appendChild(option);
            });
        }
    }
    
    // Function to load icon picker
    function loadIconPicker() {
        const loadingDiv = document.getElementById('iconPickerLoading');
        const containerDiv = document.getElementById('iconPickerContainer');
        
        if (!loadingDiv) {
            return;
        }
        
        if (!containerDiv) {
            return;
        }
        
        // Hide loading, show picker
        loadingDiv.style.display = 'none';
        containerDiv.style.display = 'block';
    }
    
    // Function to enable role checkboxes
    function enableRoleCheckboxes() {
        const checkboxes = document.querySelectorAll('.role-checkbox');
        
        checkboxes.forEach((checkbox, index) => {
            checkbox.classList.remove('loading');
            checkbox.disabled = false;
        });
    }
    
    // Check initial state and update button
    function updateToggleButton() {
        const checkedCount = document.querySelectorAll('.role-checkbox:checked').length;
        const totalCount = roleCheckboxes.length;
        
        if (checkedCount === totalCount) {
            // All selected - show "Unselect All"
            toggleAllBtn.innerHTML = '<i class="fas fa-times"></i>';
            toggleAllBtn.className = 'btn btn-outline-danger btn-sm';
            toggleAllBtn.title = 'Batalkan Semua Role';
        } else {
            // Not all selected - show "Select All"
            toggleAllBtn.innerHTML = '<i class="fas fa-check-double">';
            toggleAllBtn.className = 'btn btn-outline-secondary btn-sm';
            toggleAllBtn.title = 'Pilih Semua Role';
        }
    }
    
    // Toggle functionality
    toggleAllBtn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const checkedCount = document.querySelectorAll('.role-checkbox:checked').length;
        const totalCount = roleCheckboxes.length;
        
        // If all are selected, unselect all; otherwise select all
        const shouldSelectAll = checkedCount !== totalCount;
        
        roleCheckboxes.forEach(checkbox => {
            checkbox.checked = shouldSelectAll;
        });
        
        // Update button state
        updateToggleButton();
        
        // Add animation feedback
        this.style.transform = 'scale(0.95)';
        setTimeout(() => {
            this.style.transform = '';
        }, 150);
    });
    
    // Listen for individual checkbox changes to update toggle button
    roleCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateToggleButton);
    });
    
    // Initialize button state
    updateToggleButton();
    
});
</script>
