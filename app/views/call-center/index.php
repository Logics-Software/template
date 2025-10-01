<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Call Center</h5>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="<?php echo APP_URL; ?>/dashboard" class="text-decoration-none">Home</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Call Center</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
            <div class="card-body">
                <!-- Search and Action Buttons -->
                <div class="mb-4">
                    <form method="GET" action="<?php echo APP_URL; ?>/call-center" class="row g-3">
                        <div class="col-md-6">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" class="form-control" id="search" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search by title, number, or description...">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-outline-primary flex-fill">
                                    <i class="fas fa-search me-1"></i>Search
                                </button>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <a href="<?php echo APP_URL; ?>/call-center/create" class="btn btn-primary flex-fill">
                                    <i class="fas fa-plus me-1"></i>Add Call Center
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
                
                <?php if (empty($callCenters)): ?>
                    <div class="text-center py-5">
                        <i class="fab fa-whatsapp fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No call center entries found</h5>
                        <p class="text-muted">Start by adding your first call center entry.</p>
                        <a href="<?php echo APP_URL; ?>/call-center/create" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>Add First Entry
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="5%"></th>
                                    <th width="25%">Judul</th>
                                    <th width="20%">Nomor WhatsApp</th>
                                    <th width="35%">Deskripsi</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($callCenters as $callCenter): ?>
                                    <tr>
                                        <td>
                                            <i class="fab fa-whatsapp text-success"></i>
                                        </td>
                                        <td>
                                            <div class="fw-bold"><?php echo htmlspecialchars($callCenter['judul']); ?></div>
                                        </td>
                                        <td>
                                            <div class="text-muted"><?php echo htmlspecialchars($callCenter['nomorwa']); ?></div>
                                        </td>
                                        <td>
                                            <div class="text-muted">
                                                <?php echo htmlspecialchars(substr($callCenter['deskripsi'], 0, 100)); ?>
                                                <?php if (strlen($callCenter['deskripsi']) > 100): ?>...<?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1" style="min-width: 80px;">
                                                <a href="<?php echo APP_URL; ?>/call-center/<?php echo $callCenter['id']; ?>" class="btn btn-outline-primary btn-sm" style="min-width: 32px; padding: 0.25rem 0.5rem;" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?php echo APP_URL; ?>/call-center/<?php echo $callCenter['id']; ?>/edit" class="btn btn-outline-warning btn-sm" style="min-width: 32px; padding: 0.25rem 0.5rem;" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-danger btn-sm" style="min-width: 32px; padding: 0.25rem 0.5rem;" onclick="deleteCallCenter(<?php echo $callCenter['id']; ?>)" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteCallCenterModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this call center entry? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteCallCenter">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
let deleteCallCenterId = null;

function deleteCallCenter(id) {
    deleteCallCenterId = id;
    const modal = new bootstrap.Modal(document.getElementById("deleteCallCenterModal"));
    modal.show();
}

// Delete call center confirmation
document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("confirmDeleteCallCenter").addEventListener("click", function() {
        if (deleteCallCenterId) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `<?php echo APP_URL; ?>/call-center/${deleteCallCenterId}/delete`;
            
            const tokenInput = document.createElement('input');
            tokenInput.type = 'hidden';
            tokenInput.name = '_token';
            tokenInput.value = '<?php echo Session::generateCSRF(); ?>';
            
            form.appendChild(tokenInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
});
</script>
