<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Users List</h5>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="<?php echo APP_URL; ?>/dashboard" class="text-decoration-none">Home</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Users</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
            <div class="card-body">
                <!-- Search and Filter -->
                <div class="mb-4">
                    <form method="GET" action="<?php echo APP_URL; ?>/users" class="row g-3">
                        <div class="col-md-3">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" class="form-control" id="search" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search by name or email...">
                        </div>
                        <div class="col-md-2">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">All Status</option>
                                <option value="aktif"<?php echo $status === 'aktif' ? ' selected' : ''; ?>>Aktif</option>
                                <option value="non_aktif"<?php echo $status === 'non_aktif' ? ' selected' : ''; ?>>Non Aktif</option>
                                <option value="register"<?php echo $status === 'register' ? ' selected' : ''; ?>>Register</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" id="role" name="role">
                                <option value="">All Roles</option>
                                <option value="admin"<?php echo $role === 'admin' ? ' selected' : ''; ?>>Admin</option>
                                <option value="manajemen"<?php echo $role === 'manajemen' ? ' selected' : ''; ?>>Manajemen</option>
                                <option value="marketing"<?php echo $role === 'marketing' ? ' selected' : ''; ?>>Marketing</option>
                                <option value="customer"<?php echo $role === 'customer' ? ' selected' : ''; ?>>Customer</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <label for="per_page" class="form-label">Per Page</label>
                            <select class="form-select" id="per_page" name="per_page">
                                <option value="5"<?php echo $users['per_page'] == 5 ? ' selected' : ''; ?>>5</option>
                                <option value="10"<?php echo $users['per_page'] == 10 ? ' selected' : ''; ?>>10</option>
                                <option value="15"<?php echo $users['per_page'] == 15 ? ' selected' : ''; ?>>15</option>
                                <option value="20"<?php echo $users['per_page'] == 20 ? ' selected' : ''; ?>>20</option>
                                <option value="25"<?php echo $users['per_page'] == 25 ? ' selected' : ''; ?>>25</option>
                                <option value="50"<?php echo $users['per_page'] == 50 ? ' selected' : ''; ?>>50</option>
                                <option value="100"<?php echo $users['per_page'] == 100 ? ' selected' : ''; ?>>100</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-outline-primary flex-fill">
                                    <i class="fas fa-search me-1"></i>Filter
                                </button>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <a href="<?php echo APP_URL; ?>/users/create" class="btn btn-primary flex-fill">
                                    <i class="fas fa-plus-circle me-1"></i>Add User
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-bordered" id="usersTable">
                        <thead>
                            <tr>
                                <th>Picture</th>
                                <th class="sortable" data-sort="username">
                                    Username 
                                    <i class="fas fa-sort text-muted ms-1"></i>
                                </th>
                                <th class="sortable" data-sort="namalengkap">
                                    Nama Lengkap 
                                    <i class="fas fa-sort text-muted ms-1"></i>
                                </th>
                                <th class="sortable" data-sort="email">
                                    Email 
                                    <i class="fas fa-sort text-muted ms-1"></i>
                                </th>
                                <th class="sortable" data-sort="role">
                                    Role 
                                    <i class="fas fa-sort text-muted ms-1"></i>
                                </th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users['data'] as $user): ?>
                                <?php
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
                                ?>
                                <tr>
                                    <td><?php echo $pictureHtml; ?></td>
                                    <td><?php echo htmlspecialchars($user['username'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($user['namalengkap'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($user['email'] ?? 'N/A'); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $roleClass; ?>"><?php echo ucfirst($user['role'] ?? 'N/A'); ?></span>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo $statusClass; ?>"><?php echo ucfirst(str_replace('_', ' ', $user['status'] ?? 'N/A')); ?></span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <?php if ($user['status'] === 'register'): ?>
                                                <!-- For pending registration users - show approve/reject buttons -->
                                                <button class="btn btn-sm btn-success" onclick="approveUser(<?php echo $user['id']; ?>)" data-registration-reason="<?php echo htmlspecialchars($user['registration_reason'] ?? 'Tidak ada alasan yang diberikan'); ?>">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger" onclick="rejectUser(<?php echo $user['id']; ?>)" data-registration-reason="<?php echo htmlspecialchars($user['registration_reason'] ?? 'Tidak ada alasan yang diberikan'); ?>">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                                <a href="<?php echo APP_URL; ?>/users/<?php echo $user['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            <?php else: ?>
                                                <!-- For active/inactive users - show normal actions -->
                                                <a href="<?php echo APP_URL; ?>/users/<?php echo $user['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?php echo APP_URL; ?>/users/<?php echo $user['id']; ?>/edit" class="btn btn-sm btn-outline-warning">
                                                    <i class="fas fa-pencil"></i>
                                                </a>
                                                <button class="btn btn-sm btn-outline-danger" onclick="deleteUser(<?php echo $user['id']; ?>)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <nav>
                    <ul class="pagination justify-content-center">
                        <?php
                        // Build query parameters
                        $queryParams = [];
                        if (!empty($search)) $queryParams['search'] = $search;
                        if (!empty($status)) $queryParams['status'] = $status;
                        if (!empty($role)) $queryParams['role'] = $role;
                        if (!empty($users['per_page'])) $queryParams['per_page'] = $users['per_page'];
                        if (!empty($_GET['sort'])) $queryParams['sort'] = $_GET['sort'];
                        if (!empty($_GET['order'])) $queryParams['order'] = $_GET['order'];

                        $currentPerPage = $_GET['per_page'] ?? $users['per_page'] ?? DEFAULT_PAGE_SIZE;
                        $queryParams['per_page'] = $currentPerPage;

                        $queryString = http_build_query($queryParams);
                        ?>
                        
                        <?php if ($users['current_page'] > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo APP_URL; ?>/users?page=<?php echo $users['current_page'] - 1; ?>&<?php echo $queryString; ?>">Previous</a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $users['last_page']; $i++): ?>
                            <?php $activeClass = $i == $users['current_page'] ? ' active' : ''; ?>
                            <li class="page-item<?php echo $activeClass; ?>">
                                <a class="page-link" href="<?php echo APP_URL; ?>/users?page=<?php echo $i; ?>&<?php echo $queryString; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($users['current_page'] < $users['last_page']): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo APP_URL; ?>/users?page=<?php echo $users['current_page'] + 1; ?>&<?php echo $queryString; ?>">Next</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>

                <!-- Pagination Info -->
                <div class="text-center text-muted">
                    Showing <?php echo $users['from']; ?> to <?php echo $users['to']; ?> of <?php echo $users['total']; ?> entries
                </div>
            </div>
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
                <p class="mb-3">Are you sure you want to approve this user? They will be able to login to the system.</p>
                <div class="mb-3">
                    <label class="form-label fw-bold">Alasan Registrasi:</label>
                    <div class="form-control-plaintext bg-light p-3 rounded border" id="approveRegistrationReason">
                        <!-- Alasan registrasi akan diisi via JavaScript -->
                    </div>
                </div>
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
                <p class="mb-3">Are you sure you want to reject this user? This action will permanently delete their account and cannot be undone.</p>
                <div class="mb-3">
                    <label class="form-label fw-bold">Alasan Registrasi:</label>
                    <div class="form-control-plaintext bg-light p-3 rounded border" id="rejectRegistrationReason">
                        <!-- Alasan registrasi akan diisi via JavaScript -->
                    </div>
                </div>
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

// Table sorting functionality
document.addEventListener("DOMContentLoaded", function() {
    const sortableHeaders = document.querySelectorAll(".sortable");
    
    sortableHeaders.forEach(header => {
        header.addEventListener("click", function() {
            const sortField = this.getAttribute("data-sort");
            const currentSort = new URLSearchParams(window.location.search).get("sort");
            const currentOrder = new URLSearchParams(window.location.search).get("order");
            
            let newOrder = "asc";
            if (currentSort === sortField && currentOrder === "asc") {
                newOrder = "desc";
            }
            
            // Update URL with new sort parameters
            const url = new URL(window.location);
            url.searchParams.set("sort", sortField);
            url.searchParams.set("order", newOrder);
            
            // Redirect to new URL
            window.location.href = url.toString();
        });
    });
    
    // Update sort indicators based on current sort
    const currentSort = new URLSearchParams(window.location.search).get("sort");
    const currentOrder = new URLSearchParams(window.location.search).get("order");
    
    if (currentSort) {
        const activeHeader = document.querySelector("[data-sort=\"" + currentSort + "\"]");
        if (activeHeader) {
            activeHeader.classList.add("sort-" + (currentOrder || "asc"));
        }
    }
    
    // Auto-submit form when per_page changes
    const perPageSelect = document.getElementById("per_page");
    if (perPageSelect) {
        perPageSelect.addEventListener("change", function() {
            this.form.submit();
        });
    }
    
    // Auto-submit form when role changes
    const roleSelect = document.getElementById("role");
    if (roleSelect) {
        roleSelect.addEventListener("change", function() {
            this.form.submit();
        });
    }
});

function deleteUser(id) {
    deleteUserId = id;
    const modal = new bootstrap.Modal(document.getElementById("deleteModal"));
    modal.show();
}

function approveUser(id) {
    approveUserId = id;
    // Get registration reason from button data attribute
    const button = event.target.closest('button');
    const registrationReason = button.getAttribute('data-registration-reason') || 'Tidak ada alasan yang diberikan';
    
    // Set the registration reason in modal
    document.getElementById('approveRegistrationReason').textContent = registrationReason;
    
    const modal = new bootstrap.Modal(document.getElementById("approveModal"));
    modal.show();
}

function rejectUser(id) {
    rejectUserId = id;
    // Get registration reason from button data attribute
    const button = event.target.closest('button');
    const registrationReason = button.getAttribute('data-registration-reason') || 'Tidak ada alasan yang diberikan';
    
    // Set the registration reason in modal
    document.getElementById('rejectRegistrationReason').textContent = registrationReason;
    
    const modal = new bootstrap.Modal(document.getElementById("rejectModal"));
    modal.show();
}

// Delete user confirmation
document.getElementById("confirmDelete").addEventListener("click", function() {
    if (deleteUserId) {
        fetch("<?php echo APP_URL; ?>/users/" + deleteUserId, {
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
        fetch("<?php echo APP_URL; ?>/users/" + approveUserId + "/activate", {
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
        fetch("<?php echo APP_URL; ?>/users/" + rejectUserId + "/reject", {
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

<!-- CSRF Token for AJAX requests -->
<script>
window.csrfToken = "<?php echo Session::generateCSRF(); ?>";
</script>