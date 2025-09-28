<?php
$content = '
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-user me-2"></i>User Details
                </h5>
            </div>
            <div class="card-body">
                <!-- Profile Picture Section -->
                <div class="row mb-4">
                    <div class="col-md-12 text-center">
                        <div class="mb-3">
                            ' . ($user['picture'] ? 
                                '<img src="' . htmlspecialchars($user['picture']) . '" alt="Profile Picture" class="rounded-circle profile-img-lg">' :
                                '<div class="d-inline-flex align-items-center justify-content-center bg-primary rounded-circle text-white profile-img-lg">
                                    <i class="fas fa-user" style="font-size: 48px;"></i>
                                </div>'
                            ) . '
                        </div>
                        <h4 class="mb-1">' . htmlspecialchars($user['namalengkap'] ?? 'N/A') . '</h4>
                        <p class="text-muted mb-0">@' . htmlspecialchars($user['username'] ?? 'N/A') . '</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Username</label>
                            <p class="form-control-plaintext">' . htmlspecialchars($user['username'] ?? 'N/A') . '</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Lengkap</label>
                            <p class="form-control-plaintext">' . htmlspecialchars($user['namalengkap'] ?? 'N/A') . '</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email Address</label>
                            <p class="form-control-plaintext">' . htmlspecialchars($user['email'] ?? 'N/A') . '</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Role</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-' . match($user['role'] ?? '') {
                                    'admin' => 'danger',
                                    'manajemen' => 'primary',
                                    'marketing' => 'info',
                                    'customer' => 'secondary',
                                    default => 'light'
                                } . '">' . ucfirst($user['role'] ?? 'N/A') . '</span>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Status</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-' . match($user['status'] ?? '') {
                                    'aktif' => 'success',
                                    'non_aktif' => 'danger',
                                    'register' => 'warning',
                                    default => 'secondary'
                                } . '">' . ucfirst(str_replace('_', ' ', $user['status'] ?? 'N/A')) . '</span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Last Login</label>
                            <p class="form-control-plaintext">' . ($user['lastlogin'] ? date('M d, Y H:i', strtotime($user['lastlogin'])) : 'Never') . '</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Created At</label>
                            <p class="form-control-plaintext">' . date('M d, Y H:i', strtotime($user['created_at'] ?? '')) . '</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Last Updated</label>
                            <p class="form-control-plaintext">' . date('M d, Y H:i', strtotime($user['updated_at'] ?? '')) . '</p>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="' . APP_URL . '/users" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Back to Users
                    </a>
                    <div>
                        <a href="' . APP_URL . '/users/' . $user['id'] . '/edit" class="btn btn-warning">
                            <i class="fas fa-pencil me-1"></i>Edit User
                        </a>
                        <button class="btn btn-danger" onclick="deleteUser(' . $user['id'] . ')">
                            <i class="fas fa-trash me-1"></i>Delete User
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function deleteUser(id) {
    if (confirm("Are you sure you want to delete this user? This action cannot be undone.")) {
        fetch("' . APP_URL . '/users/" + id, {
            method: "DELETE",
            headers: {
                "X-CSRF-Token": window.csrfToken,
                "Content-Type": "application/json"
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                window.location.href = "' . APP_URL . '/users";
            } else {
                alert(data.error || "An error occurred");
            }
        })
        .catch(error => {
            alert("An error occurred while deleting the user");
        });
    }
}
</script>
';
?>

