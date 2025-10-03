<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Module</h5>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="<?php echo APP_URL; ?>/dashboard" class="text-decoration-none">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="<?php echo APP_URL; ?>/modules" class="text-decoration-none">Modules</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Edit</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
            <div class="card-body">
                <form method="POST" action="<?php echo APP_URL; ?>/modules/<?php echo $module['id']; ?>" id="editModuleForm">
                    <input type="hidden" name="_token" value="<?php echo $csrf_token; ?>">
                    <input type="hidden" name="_method" value="PUT">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="caption" name="caption" placeholder="Caption" value="<?php echo htmlspecialchars($module['caption']); ?>" required>
                                <label for="caption">Caption <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="link" name="link" required>
                                    <option value="">Select a route...</option>
                                    <?php foreach ($available_routes as $key => $route): ?>
                                        <option value="<?php echo htmlspecialchars($route['value']); ?>" 
                                                data-description="<?php echo htmlspecialchars($route['description']); ?>"
                                                <?php echo ($module['link'] === $route['value']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($route['label']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <label for="link">Link <span class="text-danger">*</span></label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="logo" class="form-label">Logo Icon <span class="text-danger">*</span></label>
                                <?php require_once __DIR__ . '/icon_picker.php'; ?>
                                <?php renderIconPicker($module['logo'], 'logo', 'logo', $available_icons); ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <label class="form-label mb-0 me-2">Role Access</label>
                                    <div class="d-flex gap-1">
                                        <button type="button" class="btn btn-outline-secondary btn-sm px-3" id="toggleAllRoles" title="Toggle Semua Role">
                                            <i class="fas fa-check-double me-1"></i>
                                            <span>Select All</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-check">
                                            <input class="form-check-input role-checkbox" type="checkbox" id="admin" name="admin" <?php echo $module['admin'] ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="admin">
                                                <span class="badge bg-danger">Admin</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-check">
                                            <input class="form-check-input role-checkbox" type="checkbox" id="manajemen" name="manajemen" <?php echo $module['manajemen'] ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="manajemen">
                                                <span class="badge bg-primary">Manajemen</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-check">
                                            <input class="form-check-input role-checkbox" type="checkbox" id="user" name="user" <?php echo $module['user'] ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="user">
                                                <span class="badge bg-info">User</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-check">
                                            <input class="form-check-input role-checkbox" type="checkbox" id="marketing" name="marketing" <?php echo $module['marketing'] ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="marketing">
                                                <span class="badge bg-warning">Marketing</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-check">
                                            <input class="form-check-input role-checkbox" type="checkbox" id="customer" name="customer" <?php echo $module['customer'] ? 'checked' : ''; ?>>
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
            <div class="card-footer d-flex justify-content-between align-items-center">
                <a href="<?php echo APP_URL; ?>/modules" class="btn btn-secondary" title="Kembali ke Daftar Modul">
                    <i class="fas fa-arrow-left me-1"></i>Back to Modules
                </a>
                <button type="submit" form="editModuleForm" class="btn btn-primary" title="Perbarui Modul">
                    <i class="fas fa-save me-1"></i>Update Module
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Note: Preview functionality removed as preview elements don't exist in this form
    
    // Toggle All Roles functionality
    const toggleAllBtn = document.getElementById('toggleAllRoles');
    const roleCheckboxes = document.querySelectorAll('.role-checkbox');
    
    // Check initial state and update button
    function updateToggleButton() {
        const checkedCount = document.querySelectorAll('.role-checkbox:checked').length;
        const totalCount = roleCheckboxes.length;
        
        if (checkedCount === totalCount) {
            // All selected - show "Unselect All"
            toggleAllBtn.innerHTML = '<i class="fas fa-times me-1"></i><span>Unselect All</span>';
            toggleAllBtn.className = 'btn btn-outline-danger btn-sm px-3';
            toggleAllBtn.title = 'Unselect All Roles';
        } else {
            // Not all selected - show "Select All"
            toggleAllBtn.innerHTML = '<i class="fas fa-check-double me-1"></i><span>Select All</span>';
            toggleAllBtn.className = 'btn btn-outline-secondary btn-sm px-3';
            toggleAllBtn.title = 'Select All Roles';
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
