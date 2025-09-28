<?php
$content = '
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Users Management</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="' . APP_URL . '/users/create" class="btn btn-primary">
            <i class="fas fa-plus-circle me-1"></i>Add User
        </a>
    </div>
</div>

<!-- Search and Filter -->
<div class="card shadow mb-4">
    <div class="card-body">
        <form method="GET" action="' . APP_URL . '/users" class="row g-3">
            <div class="col-md-4">
                <label for="search" class="form-label">Search</label>
                <input type="text" class="form-control" id="search" name="search" value="' . htmlspecialchars($search) . '" placeholder="Search by name or email...">
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">All Status</option>
                    <option value="aktif"' . ($status === 'aktif' ? ' selected' : '') . '>Aktif</option>
                    <option value="non_aktif"' . ($status === 'non_aktif' ? ' selected' : '') . '>Non Aktif</option>
                    <option value="register"' . ($status === 'register' ? ' selected' : '') . '>Register</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="per_page" class="form-label">Per Page</label>
                <select class="form-select" id="per_page" name="per_page">
                    <option value="10"' . ($users['per_page'] == 10 ? ' selected' : '') . '>10</option>
                    <option value="25"' . ($users['per_page'] == 25 ? ' selected' : '') . '>25</option>
                    <option value="50"' . ($users['per_page'] == 50 ? ' selected' : '') . '>50</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-grid">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-search me-1"></i>Filter
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Users Table -->
<div class="card shadow">
    <div class="card-header">
        <h6 class="m-0 font-weight-bold text-primary">Users List</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="usersTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Picture</th>
                        <th>Username</th>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Last Login</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
';

foreach ($users['data'] as $user) {
    // Status color mapping
    $statusClass = match($user['status'] ?? '') {
        'aktif' => 'success',
        'non_aktif' => 'danger',
        'register' => 'warning',
        default => 'secondary'
    };
    
    // Role color mapping
    $roleClass = match($user['role'] ?? '') {
        'admin' => 'danger',
        'manajemen' => 'primary',
        'marketing' => 'info',
        'customer' => 'warning',
        default => 'info'
    };
    
    // Picture handling
    $pictureUrl = $user['picture'] ?? null;
    $pictureHtml = $pictureUrl ? 
        '<img src="' . htmlspecialchars($pictureUrl) . '" alt="User Picture" class="rounded-circle profile-img-sm">' :
        '<div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center">
            <i class="fas fa-user text-white" style="font-size: 14px;"></i>
        </div>';
    
    // Last login formatting
    $lastLogin = $user['lastlogin'] ? 
        '<small class="text-muted">' . date('M d, Y H:i', strtotime($user['lastlogin'])) . '</small>' :
        '<small class="text-muted">Never</small>';
    
    $content .= '
                    <tr>
                        <td>' . $user['id'] . '</td>
                        <td>' . $pictureHtml . '</td>
                        <td>' . htmlspecialchars($user['username'] ?? 'N/A') . '</td>
                        <td>' . htmlspecialchars($user['namalengkap'] ?? 'N/A') . '</td>
                        <td>' . htmlspecialchars($user['email'] ?? 'N/A') . '</td>
                        <td>
                            <span class="badge bg-' . $roleClass . '">' . ucfirst($user['role'] ?? 'N/A') . '</span>
                        </td>
                        <td>
                            <span class="badge bg-' . $statusClass . '">' . ucfirst(str_replace('_', ' ', $user['status'] ?? 'N/A')) . '</span>
                        </td>
                        <td>' . $lastLogin . '</td>
                        <td>' . date('M d, Y', strtotime($user['created_at'] ?? 'now')) . '</td>
                        <td>
                            <div class="btn-group" role="group">';
    
    // Show different actions based on user status
    if ($user['status'] === 'register') {
        // For pending registration users - show approve/reject buttons
        $content .= '
                                <button class="btn btn-sm btn-success" onclick="approveUser(' . $user['id'] . ')">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="rejectUser(' . $user['id'] . ')">
                                    <i class="fas fa-times"></i>
                                </button>
                                <a href="' . APP_URL . '/users/' . $user['id'] . '" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>';
    } else {
        // For active/inactive users - show normal actions
        $content .= '
                                <a href="' . APP_URL . '/users/' . $user['id'] . '" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="' . APP_URL . '/users/' . $user['id'] . '/edit" class="btn btn-sm btn-outline-warning">
                                    <i class="fas fa-pencil"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteUser(' . $user['id'] . ')">
                                    <i class="fas fa-trash"></i>
                                </button>';
    }
    
    $content .= '
                            </div>
                        </td>
                    </tr>
    ';
}

$content .= '
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <nav>
            <ul class="pagination justify-content-center">
';

// Previous page
if ($users['current_page'] > 1) {
    $content .= '
                <li class="page-item">
                    <a class="page-link" href="' . APP_URL . '/users?page=' . ($users['current_page'] - 1) . '&search=' . urlencode($search) . '&status=' . urlencode($status) . '">Previous</a>
                </li>
    ';
}

// Page numbers
for ($i = 1; $i <= $users['last_page']; $i++) {
    $activeClass = $i == $users['current_page'] ? ' active' : '';
    $content .= '
                <li class="page-item' . $activeClass . '">
                    <a class="page-link" href="' . APP_URL . '/users?page=' . $i . '&search=' . urlencode($search) . '&status=' . urlencode($status) . '">' . $i . '</a>
                </li>
    ';
}

// Next page
if ($users['current_page'] < $users['last_page']) {
    $content .= '
                <li class="page-item">
                    <a class="page-link" href="' . APP_URL . '/users?page=' . ($users['current_page'] + 1) . '&search=' . urlencode($search) . '&status=' . urlencode($status) . '">Next</a>
                </li>
    ';
}

$content .= '
            </ul>
        </nav>

        <!-- Pagination Info -->
        <div class="text-center text-muted">
            Showing ' . $users['from'] . ' to ' . $users['to'] . ' of ' . $users['total'] . ' entries
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this user? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Approve Confirmation Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Approval</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to approve this user? They will be able to login to the system.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirmApprove">Approve</button>
            </div>
        </div>
    </div>
</div>

<!-- Reject Confirmation Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Rejection</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to reject this user? This action will permanently delete their account and cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmReject">Reject</button>
            </div>
        </div>
    </div>
</div>

<script>
let deleteUserId = null;
let approveUserId = null;
let rejectUserId = null;

function deleteUser(id) {
    deleteUserId = id;
    const modal = new bootstrap.Modal(document.getElementById("deleteModal"));
    modal.show();
}

function approveUser(id) {
    approveUserId = id;
    const modal = new bootstrap.Modal(document.getElementById("approveModal"));
    modal.show();
}

function rejectUser(id) {
    rejectUserId = id;
    const modal = new bootstrap.Modal(document.getElementById("rejectModal"));
    modal.show();
}

// Delete user confirmation
document.getElementById("confirmDelete").addEventListener("click", function() {
    if (deleteUserId) {
        fetch("' . APP_URL . '/users/" + deleteUserId, {
            method: "DELETE",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-Token": window.csrfToken,
                "X-Requested-With": "XMLHttpRequest"
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert("Error: " + (data.error || "Failed to delete user"));
            }
        })
        .catch(error => {
            alert("An error occurred while deleting the user");
        });
    }
});

// Approve user confirmation
document.getElementById("confirmApprove").addEventListener("click", function() {
    if (approveUserId) {
        fetch("' . APP_URL . '/users/" + approveUserId + "/activate", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-Token": window.csrfToken,
                "X-Requested-With": "XMLHttpRequest"
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert("Error: " + (data.error || "Failed to approve user"));
            }
        })
        .catch(error => {
            alert("An error occurred while approving the user");
        });
    }
});

// Reject user confirmation
document.getElementById("confirmReject").addEventListener("click", function() {
    if (rejectUserId) {
        fetch("' . APP_URL . '/users/" + rejectUserId + "/reject", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-Token": window.csrfToken,
                "X-Requested-With": "XMLHttpRequest"
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert("Error: " + (data.error || "Failed to reject user"));
            }
        })
        .catch(error => {
            alert("An error occurred while rejecting the user");
        });
    }
});
</script>
';
?>
