<div class="row">
    <div class="col-12">
        <div class="form-container modules-form-loading" id="moduleFormCard">
            <div class="form-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Modul</h5>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="<?php echo APP_URL; ?>/dashboard" class="text-decoration-none">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="<?php echo APP_URL; ?>/modules" class="text-decoration-none">Daftar Modul</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Edit</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
            <div class="form-body" id="moduleFormBody">
                <form method="POST" action="<?php echo APP_URL; ?>/modules/<?php echo $module['id']; ?>" id="editModuleForm">
                    <input type="hidden" name="_token" value="<?php echo $csrf_token; ?>">
                    <input type="hidden" name="_method" value="PUT">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3 loading" id="captionField">
                                <input type="text" class="form-control" id="caption" name="caption" placeholder="Caption" value="" required disabled>
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
                            <div class="mb-3">
                                <label for="logo" class="form-label">Icon Logo <span class="text-danger">*</span></label>
                                <div class="icon-picker-loading" id="iconPickerLoading">
                                    <!-- Icon picker will be loaded here -->
                                </div>
                                <div id="iconPickerContainer" style="display: none;">
                                    <?php require_once __DIR__ . '/icon_picker.php'; ?>
                                    <?php renderIconPicker($module['logo'], 'logo', 'logo', $available_icons); ?>
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
                                        <button type="button" class="btn btn-outline-secondary btn-sm px-3" id="toggleAllRoles" 
                                        data-bs-toggle="tooltip" data-bs-title="Tambah Semua Role">
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
                <button type="submit" form="editModuleForm" class="btn btn-primary loading" id="updateBtn" disabled>
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
        'updateBtn'
    ];
    
    const missingElements = requiredElements.filter(id => !document.getElementById(id));
    if (missingElements.length > 0) {
        // console.error('Missing required elements:', missingElements);
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
        
        // Enable form fields and populate data
        const captionInput = document.getElementById('caption');
        const linkSelect = document.getElementById('link');
        
        if (captionInput) {
            captionInput.disabled = false;
            captionInput.value = '<?php echo htmlspecialchars($module['caption']); ?>';
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
        const updateBtn = document.getElementById('updateBtn');
        if (updateBtn) {
            updateBtn.disabled = false;
            updateBtn.classList.remove('loading');
        }
        
    }
    
    // Function to load routes data
    function loadRoutesData() {
        const linkSelect = document.getElementById('link');
        
        if (!linkSelect) {
            // console.error('Link select element not found!');
            return;
        }
        
        // Clear loading option
        linkSelect.innerHTML = '<option value="">Select a route...</option>';
        
        // Check if routes data is available
        try {
            const routes = <?php echo json_encode($available_routes ?? []); ?>;
            // console.log('Routes data:', routes);
            
            if (routes && routes.length > 0) {
                // console.log('Adding routes to select...');
                // Add routes
                routes.forEach(route => {
                    const option = document.createElement('option');
                    option.value = route.value;
                    option.setAttribute('data-description', route.description);
                    option.textContent = route.label + ' (' + route.value + ')';
                    
                    // Check if this is the selected route
                    if (route.value === '<?php echo htmlspecialchars($module['link']); ?>') {
                        option.selected = true;
                    }
                    
                    linkSelect.appendChild(option);
                });
                // console.log('Routes added successfully');
            } else {
                // console.log('No routes data available, using fallback routes');
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
                    
                    // Check if this is the selected route
                    if (route.value === '<?php echo htmlspecialchars($module['link']); ?>') {
                        option.selected = true;
                    }
                    
                    linkSelect.appendChild(option);
                });
                // console.log('Fallback routes added');
            }
        } catch (error) {
            // console.error('Error loading routes:', error);
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
                
                // Check if this is the selected route
                if (route.value === '<?php echo htmlspecialchars($module['link']); ?>') {
                    option.selected = true;
                }
                
                linkSelect.appendChild(option);
            });
            // console.log('Error fallback routes added');
        }
    }
    
    // Function to load icon picker
    function loadIconPicker() {
        // console.log('Loading icon picker...');
        const loadingDiv = document.getElementById('iconPickerLoading');
        const containerDiv = document.getElementById('iconPickerContainer');
        
        if (!loadingDiv) {
            // console.error('Icon picker loading div not found!');
            return;
        }
        
        if (!containerDiv) {
            // console.error('Icon picker container div not found!');
            return;
        }
        
        // Hide loading, show picker
        loadingDiv.style.display = 'none';
        containerDiv.style.display = 'block';
        // console.log('Icon picker loaded successfully');
    }
    
    // Function to enable role checkboxes
    function enableRoleCheckboxes() {
        // console.log('Enabling role checkboxes...');
        const checkboxes = document.querySelectorAll('.role-checkbox');
        // console.log('Found checkboxes:', checkboxes.length);
        
        // Module role data
        const moduleRoles = {
            admin: <?php echo $module['admin'] ? 'true' : 'false'; ?>,
            manajemen: <?php echo $module['manajemen'] ? 'true' : 'false'; ?>,
            user: <?php echo $module['user'] ? 'true' : 'false'; ?>,
            marketing: <?php echo $module['marketing'] ? 'true' : 'false'; ?>,
            customer: <?php echo $module['customer'] ? 'true' : 'false'; ?>
        };
        
        checkboxes.forEach((checkbox, index) => {
            checkbox.classList.remove('loading');
            checkbox.disabled = false;
            
            // Set checked state based on module data
            const roleName = checkbox.name;
            if (moduleRoles.hasOwnProperty(roleName)) {
                checkbox.checked = moduleRoles[roleName];
            }
            
            // console.log(`Enabled checkbox ${index + 1} (${roleName}): ${checkbox.checked}`);
        });
        // console.log('Role checkboxes enabled successfully');
    }
    
    // Check initial state and update button
    function updateToggleButton() {
        const checkedCount = document.querySelectorAll('.role-checkbox:checked').length;
        const totalCount = roleCheckboxes.length;
        
        if (checkedCount === totalCount) {
            // All selected - show "Unselect All"
            toggleAllBtn.innerHTML = '<i class="fas fa-times"></i>';
            toggleAllBtn.className = 'btn btn-outline-danger btn-sm';
            toggleAllBtn.title = 'Pilih Semua Role';
        } else {
            // Not all selected - show "Select All"
            toggleAllBtn.innerHTML = '<i class="fas fa-check-double"></i>';
            toggleAllBtn.className = 'btn btn-outline-secondary btn-sm';
            toggleAllBtn.title = 'Batalkan Semua Role';
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
