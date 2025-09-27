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
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Full Name</label>
                            <p class="form-control-plaintext">' . htmlspecialchars($user['name'] ?? '') . '</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email Address</label>
                            <p class="form-control-plaintext">' . htmlspecialchars($user['email'] ?? '') . '</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Role</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-primary">' . ucfirst($user['role'] ?? '') . '</span>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Status</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-' . (($user['status'] ?? '') === 'active' ? 'success' : 'danger') . '">' . ucfirst($user['status'] ?? '') . '</span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Created At</label>
                            <p class="form-control-plaintext">' . date('M d, Y H:i', strtotime($user['created_at'] ?? '')) . '</p>
                        </div>
                    </div>
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
            console.error("Error:", error);
            alert("An error occurred while deleting the user");
        });
    }
}
</script>
';
?>

