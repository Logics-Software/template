<?php
$content = '
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-user-plus me-2"></i>Create New User
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="' . APP_URL . '/users" id="createUserForm" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="' . $csrf_token . '">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="username" name="username" required>
                                <div class="form-text">Username must be unique</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="namalengkap" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="namalengkap" name="namalengkap" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="">Select Role</option>
                                    <option value="admin">Administrator</option>
                                    <option value="manajemen">Manajemen</option>
                                    <option value="user">User</option>
                                    <option value="marketing">Marketing</option>
                                    <option value="customer">Customer</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="aktif" selected>Aktif</option>
                                    <option value="non_aktif">Non Aktif</option>
                                    <option value="register">Register</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="picture" class="form-label">Profile Picture</label>
                                <input type="file" class="form-control" id="picture" name="picture" accept="image/*">
                                <div class="form-text">Upload a profile picture (optional)</div>
                            </div>
                        </div>
                    </div>


                    <div class="d-flex justify-content-between">
                        <a href="' . APP_URL . '/users" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Back to Users
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check-circle me-1"></i>Create User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Password toggle functionality
document.getElementById("togglePassword").addEventListener("click", function() {
    const passwordField = document.getElementById("password");
    const icon = this.querySelector("i");
    
    if (passwordField.type === "password") {
        passwordField.type = "text";
        icon.className = "fas fa-eye-slash";
    } else {
        passwordField.type = "password";
        icon.className = "fas fa-eye";
    }
});

// Form submission with AJAX
document.getElementById("createUserForm").addEventListener("submit", function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector("button[type=submit]");
    const originalText = submitBtn.innerHTML;
    
    submitBtn.innerHTML = "<i class=\"fas fa-hourglass-split me-1\"></i>Creating...";
    submitBtn.disabled = true;
    
    fetch("' . APP_URL . '/users", {
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
            
            // Reset form
            this.reset();
            
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
        const alertDiv = document.createElement("div");
        alertDiv.className = "alert alert-danger alert-dismissible fade show";
        alertDiv.innerHTML = `
            An error occurred while creating the user
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.querySelector(".card-body").insertBefore(alertDiv, document.querySelector("form"));
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});
</script>
';
?>
