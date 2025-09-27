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
                <form method="POST" action="' . APP_URL . '/users/' . $user['id'] . '" id="editUserForm">
                    <input type="hidden" name="_token" value="' . $csrf_token . '">
                    <input type="hidden" name="_method" value="PUT">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" value="' . htmlspecialchars($user['name'] ?? '') . '" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" value="' . htmlspecialchars($user['email'] ?? '') . '" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Leave blank to keep current password">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
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
                                    <option value="user"' . (($user['role'] ?? '') === 'user' ? ' selected' : '') . '>User</option>
                                    <option value="moderator"' . (($user['role'] ?? '') === 'moderator' ? ' selected' : '') . '>Moderator</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="active"' . (($user['status'] ?? '') === 'active' ? ' selected' : '') . '>Active</option>
                                    <option value="inactive"' . (($user['status'] ?? '') === 'inactive' ? ' selected' : '') . '>Inactive</option>
                                </select>
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
        method: "PUT",
        body: formData,
        headers: {
            "X-CSRF-Token": window.csrfToken
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
            // Show error message
            const alertDiv = document.createElement("div");
            alertDiv.className = "alert alert-danger alert-dismissible fade show";
            alertDiv.innerHTML = `
                ${data.error || "An error occurred"}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.querySelector(".card-body").insertBefore(alertDiv, document.querySelector("form"));
        }
    })
    .catch(error => {
        console.error("Error:", error);
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

