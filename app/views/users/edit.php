<?php
$content = '
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-user-edit me-2"></i>Edit User
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="' . APP_URL . '/users/' . $user['id'] . '" id="editUserForm" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="' . $csrf_token . '">
                    <input type="hidden" name="_method" value="PUT">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="username" name="username" value="' . htmlspecialchars($user['username'] ?? '') . '" required>
                                <div class="form-text">Username must be unique</div>
                                <div class="invalid-feedback" id="username-error"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="namalengkap" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="namalengkap" name="namalengkap" value="' . htmlspecialchars($user['namalengkap'] ?? '') . '" required>
                                <div class="invalid-feedback" id="namalengkap-error"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" value="' . htmlspecialchars($user['email'] ?? '') . '" required>
                                <div class="invalid-feedback" id="email-error"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Leave blank to keep current password">
                                <div class="invalid-feedback" id="password-error"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm new password">
                                <div class="invalid-feedback" id="password_confirmation-error"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="">Select Role</option>
                                    <option value="admin"' . (($user['role'] ?? '') === 'admin' ? ' selected' : '') . '>Administrator</option>
                                    <option value="manajemen"' . (($user['role'] ?? '') === 'manajemen' ? ' selected' : '') . '>Manajemen</option>
                                    <option value="user"' . (($user['role'] ?? '') === 'user' ? ' selected' : '') . '>User</option>
                                    <option value="marketing"' . (($user['role'] ?? '') === 'marketing' ? ' selected' : '') . '>Marketing</option>
                                    <option value="customer"' . (($user['role'] ?? '') === 'customer' ? ' selected' : '') . '>Customer</option>
                                </select>
                                <div class="invalid-feedback" id="role-error"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="aktif"' . (($user['status'] ?? '') === 'aktif' ? ' selected' : '') . '>Aktif</option>
                                    <option value="non_aktif"' . (($user['status'] ?? '') === 'non_aktif' ? ' selected' : '') . '>Non Aktif</option>
                                    <option value="register"' . (($user['status'] ?? '') === 'register' ? ' selected' : '') . '>Register</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="picture" class="form-label">Profile Picture</label>
                                <input type="file" class="form-control" id="picture" name="picture" accept="image/*">
                                <div class="form-text">Upload a new profile picture (optional)</div>';
                                
                                // Show current picture if exists
                                if (!empty($user['picture'])) {
                                    $content .= '
                                <div class="mt-2">
                                    <label class="form-label fw-bold">Current Picture:</label>
                                    <div>
                                        <img src="' . APP_URL . '/' . htmlspecialchars($user['picture']) . '" alt="Current Profile Picture" class="rounded-circle profile-img-md">
                                    </div>
                                </div>';
                                }
                                
                                $content .= '
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="' . APP_URL . '/users" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check-circle me-1"></i>Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Form submission with AJAX
document.getElementById("editUserForm").addEventListener("submit", function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector("button[type=submit]");
    const originalText = submitBtn.innerHTML;
    
    submitBtn.innerHTML = "<i class=\"fas fa-hourglass-split me-1\"></i>Updating...";
    submitBtn.disabled = true;
    
    fetch("' . APP_URL . '/users/' . $user['id'] . '", {
        method: "POST",
        body: formData,
        headers: {
            "X-CSRF-Token": window.csrfToken,
            "X-Requested-With": "XMLHttpRequest"
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            const alertDiv = document.createElement("div");
            alertDiv.className = "alert alert-success alert-dismissible fade show";
            alertDiv.innerHTML = `
                ${data.message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.querySelector(".card-body").insertBefore(alertDiv, document.querySelector("form"));
            
            // Redirect after 2 seconds
            setTimeout(() => {
                window.location.href = "' . APP_URL . '/users";
            }, 2000);
        } else {
            // Handle validation errors
            if (data.errors) {
                // Clear previous error styling
                document.querySelectorAll(".form-control").forEach(input => {
                    input.classList.remove("is-invalid");
                });
                document.querySelectorAll(".invalid-feedback").forEach(error => {
                    error.textContent = "";
                });
                
                // Show validation errors
                Object.keys(data.errors).forEach(field => {
                    const input = document.querySelector(`[name="${field}"]`);
                    const errorDiv = document.getElementById(`${field}-error`);
                    
                    if (input && errorDiv) {
                        input.classList.add("is-invalid");
                        errorDiv.textContent = data.errors[field][0] || data.errors[field];
                    }
                });
            } else {
                // Show general error message
                const alertDiv = document.createElement("div");
                alertDiv.className = "alert alert-danger alert-dismissible fade show";
                alertDiv.innerHTML = `
                    ${data.error || "An error occurred"}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.querySelector(".card-body").insertBefore(alertDiv, document.querySelector("form"));
            }
        }
    })
    .catch(error => {
        alert("An error occurred while updating the user");
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});
</script>
';
?>

