<div class="row">
    <div class="col-12">
        <div class="form-container">
            <div class="form-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit User Menu Access</h5>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="<?php echo APP_URL; ?>/dashboard" class="text-decoration-none">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="<?php echo APP_URL; ?>/users-menu" class="text-decoration-none">User Menu Access</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Edit</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
            <div class="form-body">
                <!-- User Info Card -->
                <div class="card mb-4">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-3">
                                <p class="mb-1 text-muted small">Username</p>
                                <p class="fw-bold"><?php echo htmlspecialchars($user['username']); ?></p>
                            </div>
                            <div class="col-md-3">
                                <p class="mb-1 text-muted small">Nama Lengkap</p>
                                <p class="fw-bold"><?php echo htmlspecialchars($user['namalengkap']); ?></p>
                            </div>
                            <div class="col-md-3">
                                <p class="mb-1 text-muted small">Email</p>
                                <p class="fw-bold"><?php echo htmlspecialchars($user['email']); ?></p>
                            </div>
                            <div class="col-md-3">
                                <p class="mb-1 text-muted small">Role</p>
                                <?php
                                $roleClass = match($user['role'] ?? '') {
                                    'admin' => 'danger',
                                    'manajemen' => 'primary',
                                    'marketing' => 'warning',
                                    'customer' => 'success',
                                    default => 'info'
                                };
                                ?>
                                <p><span class="badge bg-<?php echo $roleClass; ?>"><?php echo ucfirst($user['role']); ?></span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Menu Groups Selection Form -->
                <form id="menuAccessForm">
                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                    
                    <div class="form-container">
                        <div class="form-header">
                            <h6 class="mb-0"><i class="fas fa-bars me-2"></i>Select Menu Groups</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php if (!empty($allMenuGroups)): ?>
                                    <?php foreach ($allMenuGroups as $group): ?>
                                        <?php
                                        $isChecked = in_array($group['id'], $userGroupIds);
                                        ?>
                                        <div class="col-md-6 mb-3">
                                            <div class="form-check p-3 border rounded <?php echo $isChecked ? 'bg-light' : ''; ?>">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="group_ids[]" 
                                                       value="<?php echo $group['id']; ?>" 
                                                       id="group_<?php echo $group['id']; ?>"
                                                       <?php echo $isChecked ? 'checked' : ''; ?>>
                                                <label class="form-check-label w-100" for="group_<?php echo $group['id']; ?>">
                                                    <div class="d-flex align-items-start">
                                                        <div class="me-3">
                                                            <i class="<?php echo htmlspecialchars($group['icon'] ?? 'fas fa-folder'); ?> fa-2x text-primary"></i>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1"><?php echo htmlspecialchars($group['name']); ?></h6>
                                                            <p class="text-muted small mb-0">
                                                                <?php echo htmlspecialchars($group['description'] ?? 'No description'); ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="col-12">
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            No menu groups available
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="<?php echo APP_URL; ?>/users-menu" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back
                        </a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save me-2"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const menuAccessForm = document.getElementById('menuAccessForm');
    const submitBtn = document.getElementById('submitBtn');
    
    // Handle form submission
    menuAccessForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Disable submit button to prevent double submission
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
        
        // Get selected group IDs
        const checkboxes = document.querySelectorAll('input[name="group_ids[]"]:checked');
        const groupIds = Array.from(checkboxes).map(cb => cb.value);
        
        // Send AJAX request
        fetch('<?php echo APP_URL; ?>/users-menu/<?php echo $user['id']; ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-Token': '<?php echo $csrf_token; ?>'
            },
            body: JSON.stringify({
                user_id: <?php echo $user['id']; ?>,
                group_ids: groupIds
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.Notify.success(data.message || 'Menu access updated successfully');
                
                // Redirect to list page after delay to allow notification to be visible
                setTimeout(() => {
                    window.location.href = '<?php echo APP_URL; ?>/users-menu';
                }, 2000);
            } else {
                window.Notify.error(data.error || 'Failed to update menu access');
                
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Save Changes';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            window.Notify.error('An error occurred while updating menu access');
            
            // Re-enable submit button
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Save Changes';
        });
    });
    
    // Add visual feedback when checkboxes are toggled
    const checkboxes = document.querySelectorAll('input[name="group_ids[]"]');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const formCheck = this.closest('.form-check');
            if (this.checked) {
                formCheck.classList.add('bg-light');
            } else {
                formCheck.classList.remove('bg-light');
            }
        });
    });
});
</script>

<style>
.form-check {
    transition: background-color 0.2s ease;
}

.form-check:hover {
    background-color: #f8f9fa !important;
}

.form-check-input:checked ~ .form-check-label {
    font-weight: 500;
}
</style>

