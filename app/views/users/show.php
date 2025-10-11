<div class="row">
    <div class="col-12">
        <div class="form-container">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Lihat Detal User</h5>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="<?php echo APP_URL; ?>/dashboard" class="text-decoration-none">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="<?php echo APP_URL; ?>/users" class="text-decoration-none">Daftar User</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Lihat Data</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
            <div class="card-body">
                <!-- Profile Picture Section -->
                <div class="row mb-4">
                    <div class="col-md-12 text-center">
                        <div class="mb-3">
                            <?php if (!empty($user['picture']) && file_exists($user['picture'])): ?>
                                <img src="<?php echo APP_URL; ?>/<?php echo htmlspecialchars($user['picture']); ?>" alt="Profile Picture" class="rounded-circle profile-img-lg">
                            <?php else: ?>
                                <img src="<?php echo APP_URL; ?>/assets/images/users/avatar.svg" alt="Default Avatar" class="rounded-circle profile-img-lg">
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Username</label>
                            <div class="form-control-plaintext bg-light p-3 rounded border"><?php echo htmlspecialchars($user['username'] ?? 'N/A'); ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Lengkap</label>
                            <div class="form-control-plaintext bg-light p-3 rounded border"><?php echo htmlspecialchars($user['namalengkap'] ?? 'N/A'); ?></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Alamaat Email</label>
                            <div class="form-control-plaintext bg-light p-3 rounded border"><?php echo htmlspecialchars($user['email'] ?? 'N/A'); ?></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Role</label>
                            <div class="form-control-plaintext bg-light p-3 rounded border">
                                <?php
                                $roleClass = match($user['role'] ?? '') {
                                    'admin' => 'danger',
                                    'manajemen' => 'primary',
                                    'marketing' => 'info',
                                    'customer' => 'secondary',
                                    default => 'warning'
                                };
                                ?>
                                <span class="badge bg-<?php echo $roleClass; ?>"><?php echo ucfirst($user['role'] ?? 'N/A'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Status</label>
                            <div class="form-control-plaintext bg-light p-3 rounded border">
                                <?php
                                $statusClass = match($user['status'] ?? '') {
                                    'aktif' => 'success',
                                    'non_aktif' => 'danger',
                                    'register' => 'warning',
                                    default => 'secondary'
                                };
                                ?>
                                <span class="badge bg-<?php echo $statusClass; ?>"><?php echo ucfirst(str_replace('_', ' ', $user['status'] ?? 'N/A')); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Login Terakhir</label>
                            <div class="form-control-plaintext bg-light p-3 rounded border"><?php echo $user['lastlogin'] ? date('M d, Y H:i', strtotime($user['lastlogin'])) : 'Never'; ?></div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Dibuat Pada</label>
                            <div class="form-control-plaintext bg-light p-3 rounded border"><?php echo date('M d, Y H:i', strtotime($user['created_at'] ?? '')); ?></div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Terakhir Update</label>
                            <div class="form-control-plaintext bg-light p-3 rounded border"><?php echo date('M d, Y H:i', strtotime($user['updated_at'] ?? '')); ?></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="<?php echo APP_URL; ?>/users" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Kembali ke Daftar User
                    </a>
                    <div>
                        <a href="<?php echo APP_URL; ?>/users/<?php echo $user['id']; ?>/edit" class="btn btn-warning">
                            <i class="fas fa-pencil me-1"></i>Edit
                        </a>
                        <button class="btn btn-danger" onclick="deleteUser(<?php echo $user['id']; ?>)">
                            <i class="fas fa-trash me-1"></i>Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
async function deleteUser(id) {
    const confirmed = await window.Notify.confirm("Are you sure you want to delete this user? This action cannot be undone.");
    
    if (confirmed) {
        fetch("<?php echo APP_URL; ?>/users/" + id, {
            method: "DELETE",
            headers: {
                "X-CSRF-Token": window.csrfToken,
                "Content-Type": "application/json"
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.Notify.success(data.message || 'User deleted successfully');
                window.delayedRedirect("<?php echo APP_URL; ?>/users");
            } else {
                window.Notify.error(data.error || "An error occurred");
            }
        })
        .catch(error => {
            console.error('Error:', error);
            window.Notify.error("An error occurred while deleting the user");
        });
    }
}
</script>