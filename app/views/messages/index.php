<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <h5 class="mb-0">
                        Pesan Masuk
                        <?php if ($unread_count > 0): ?>
                            <span class="badge bg-danger ms-2"><?php echo $unread_count; ?></span>
                        <?php endif; ?>
                    </h5>
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="<?php echo APP_URL; ?>/dashboard">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Pesan Masuk</li>
                    </ol>
                </nav>
            </div>
            
            <div class="card-body">
                <!-- Search Form with Action Buttons -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <form method="GET" action="<?php echo APP_URL; ?>/messages/search" class="d-flex">
                            <div class="input-group">
                                <input type="text" name="q" class="form-control" placeholder="Cari pesan..." value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>">
                                <button type="submit" class="btn btn-secondary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" id="per_page" name="per_page" onchange="this.form.submit()">
                            <option value="10"<?php echo ($pagination['per_page'] ?? 20) == 10 ? ' selected' : ''; ?>>10</option>
                            <option value="20"<?php echo ($pagination['per_page'] ?? 20) == 20 ? ' selected' : ''; ?>>20</option>
                            <option value="30"<?php echo ($pagination['per_page'] ?? 20) == 30 ? ' selected' : ''; ?>>30</option>
                            <option value="50"<?php echo ($pagination['per_page'] ?? 20) == 50 ? ' selected' : ''; ?>>50</option>
                            <option value="100"<?php echo ($pagination['per_page'] ?? 20) == 100 ? ' selected' : ''; ?>>100</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="<?php echo APP_URL; ?>/messages/create" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>Tulis Pesan
                            </a>
                            <a href="<?php echo APP_URL; ?>/messages/sent" class="btn btn-secondary">
                                <i class="fas fa-paper-plane me-1"></i>Pesan Terkirim
                            </a>
                        </div>
                    </div>
                </div>

                <?php if (empty($messages)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Tidak ada pesan</h5>
                        <p class="text-muted">Belum ada pesan masuk untuk Anda.</p>
                        <a href="<?php echo APP_URL; ?>/messages/create" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>Tulis Pesan Pertama
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="5%"></th>
                                    <th width="25%">Pengirim</th>
                                    <th width="40%">Subjek</th>
                                    <th width="15%">Tanggal</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($messages as $message): ?>
                                    <tr class="<?php echo !$message['is_read'] ? 'table-warning' : ''; ?>">
                                        <td>
                                            <?php if (!$message['is_read']): ?>
                                                <i class="fas fa-circle text-primary"></i>
                                            <?php else: ?>
                                                <i class="fas fa-check-circle text-success"></i>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <?php if (!empty($message['sender_picture'])): ?>
                                                    <img src="<?php echo APP_URL; ?>/<?php echo htmlspecialchars($message['sender_picture']); ?>" 
                                                            alt="<?php echo htmlspecialchars($message['sender_name']); ?>" 
                                                            class="avatar-sm rounded-circle me-2" 
                                                            style="width: 32px; height: 32px; object-fit: cover;">
                                                <?php else: ?>
                                                    <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                                        <?php echo strtoupper(substr($message['sender_name'], 0, 1)); ?>
                                                    </div>
                                                <?php endif; ?>
                                                <div>
                                                    <div class="fw-bold"><?php echo htmlspecialchars($message['sender_name'] ?? 'Unknown'); ?></div>
                                                    <small class="text-muted"><?php echo htmlspecialchars($message['sender_email'] ?? '-'); ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-bold"><?php echo htmlspecialchars($message['subject'] ?? '(No Subject)'); ?></div>
                                            <small class="text-muted">
                                                <?php echo htmlspecialchars(substr(strip_tags($message['content'] ?? ''), 0, 100)); ?>
                                                <?php if (strlen(strip_tags($message['content'] ?? '')) > 100): ?>...<?php endif; ?>
                                            </small>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <?php echo date('d/m/Y H:i', strtotime($message['created_at'])); ?>
                                            </small>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1" style="min-width: 80px;">
                                                <a href="<?php echo APP_URL; ?>/messages/<?php echo $message['id']; ?>" class="btn btn-outline-primary btn-sm" style="min-width: 32px; padding: 0.25rem 0.5rem;" title="Lihat Pesan">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-danger btn-sm" style="min-width: 32px; padding: 0.25rem 0.5rem;" onclick="deleteMessage(<?php echo $message['id']; ?>)" title="Hapus Pesan">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php if (isset($pagination)): ?>
                    <div class="row">
                        <div class="col-12">
                            <nav aria-label="Messages pagination">
                                <ul class="pagination justify-content-center">
                                    <?php
                                    // Build query parameters
                                    $queryParams = [];
                                    if (!empty($search)) $queryParams['search'] = $search;
                                    if (!empty($pagination['per_page'])) $queryParams['per_page'] = $pagination['per_page'];

                                    $queryString = http_build_query($queryParams);
                                    ?>
                                    
                                    <?php if ($pagination['current_page'] > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="<?php echo APP_URL; ?>/messages?page=<?php echo $pagination['current_page'] - 1; ?>&<?php echo $queryString; ?>">Previous</a>
                                        </li>
                                    <?php endif; ?>

                                    <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                                        <?php $activeClass = $i == $pagination['current_page'] ? ' active' : ''; ?>
                                        <li class="page-item<?php echo $activeClass; ?>">
                                            <a class="page-link" href="<?php echo APP_URL; ?>/messages?page=<?php echo $i; ?>&<?php echo $queryString; ?>"><?php echo $i; ?></a>
                                        </li>
                                    <?php endfor; ?>

                                    <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="<?php echo APP_URL; ?>/messages?page=<?php echo $pagination['current_page'] + 1; ?>&<?php echo $queryString; ?>">Next</a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>

                            <!-- Pagination Info -->
                            <div class="text-center text-muted">
                                Showing <?php echo (($pagination['current_page'] - 1) * $pagination['per_page']) + 1; ?> to 
                                <?php echo min($pagination['current_page'] * $pagination['per_page'], $pagination['total_items']); ?> 
                                of <?php echo $pagination['total_items']; ?> messages
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteMessageModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this message? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteMessage">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
let deleteMessageId = null;

function deleteMessage(messageId) {
    deleteMessageId = messageId;
    const modal = new bootstrap.Modal(document.getElementById("deleteMessageModal"));
    modal.show();
}

// Delete message confirmation
document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("confirmDeleteMessage").addEventListener("click", function() {
        if (deleteMessageId) {
        fetch(`<?php echo APP_URL; ?>/messages/${deleteMessageId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                // Close modal first
                const modal = bootstrap.Modal.getInstance(document.getElementById("deleteMessageModal"));
                modal.hide();
                
                // Show error alert
                showToast('error', data.message || 'Gagal menghapus pesan');
            }
        })
        .catch(error => {
            // Close modal first
            const modal = bootstrap.Modal.getInstance(document.getElementById("deleteMessageModal"));
            modal.hide();
            
            // Show error alert
            showToast('error', 'Terjadi kesalahan saat menghapus pesan');
        });
        }
    });
});
</script>
