<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <h5 class="mb-0">Pesan Terkirim</h5>
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="<?php echo APP_URL; ?>/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo APP_URL; ?>/messages">Pesan Masuk</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Pesan Terkirim</li>
                    </ol>
                </nav>
            </div>
            
            <div class="card-body">
                <!-- Search Form with Action Buttons -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <form method="GET" action="<?php echo APP_URL; ?>/messages/sent" class="d-flex">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Cari pesan terkirim..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
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
                            <a href="<?php echo APP_URL; ?>/messages" class="btn btn-secondary">
                                <i class="fas fa-inbox me-1"></i>Pesan Masuk
                            </a>
                        </div>
                    </div>
                </div>

                <?php if (!empty($search)): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-search me-1"></i>
                        Hasil pencarian untuk: "<strong><?php echo htmlspecialchars($search); ?></strong>"
                        <a href="<?php echo APP_URL; ?>/messages/sent" class="btn btn-sm btn-outline-secondary ms-2">
                            <i class="fas fa-times me-1"></i>Hapus Filter
                        </a>
                    </div>
                <?php endif; ?>

                <?php if (empty($messages)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-paper-plane fa-3x text-muted mb-3"></i>
                        <?php if (!empty($search)): ?>
                            <h5 class="text-muted">Tidak ada hasil pencarian</h5>
                            <p class="text-muted">Tidak ada pesan terkirim yang sesuai dengan pencarian "<strong><?php echo htmlspecialchars($search); ?></strong>"</p>
                            <a href="<?php echo APP_URL; ?>/messages/sent" class="btn btn-secondary">
                                <i class="fas fa-list me-1"></i>Lihat Semua Pesan Terkirim
                            </a>
                        <?php else: ?>
                            <h5 class="text-muted">Belum ada pesan terkirim</h5>
                            <p class="text-muted">Anda belum mengirim pesan apapun.</p>
                            <a href="<?php echo APP_URL; ?>/messages/create" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>Tulis Pesan Pertama
                            </a>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="5%"></th>
                                    <th width="40%">Subjek</th>
                                    <th width="30%">Penerima</th>
                                    <th width="15%">Tanggal</th>
                                    <th width="12%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($messages as $message): ?>
                                    <tr>
                                        <td>
                                            <i class="fas fa-paper-plane text-primary"></i>
                                        </td>
                                        <td>
                                            <div class="fw-bold"><?php echo htmlspecialchars($message['subject'] ?? ''); ?></div>
                                            <small class="text-muted">
                                                <?php echo htmlspecialchars(substr(strip_tags($message['content'] ?? ''), 0, 100)); ?>
                                                <?php if (strlen(strip_tags($message['content'] ?? '')) > 100): ?>...<?php endif; ?>
                                            </small>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <?php 
                                                $recipients = $message['recipient_names'] ?? '';
                                                $recipientCount = $message['recipient_count'] ?? 0;
                                                
                                                if (empty($recipients) || $recipientCount == 0) {
                                                    echo '<span class="text-muted">Tidak ada penerima</span>';
                                                } else {
                                                    // Truncate long recipient names
                                                    $displayRecipients = $recipients;
                                                    if (strlen($recipients) > 50) {
                                                        $displayRecipients = substr($recipients, 0, 50) . '...';
                                                    }
                                                    echo htmlspecialchars($displayRecipients);
                                                    if ($recipientCount > 1) {
                                                        echo ' <span class="badge bg-success">' . $recipientCount . '</span>';
                                                    }
                                                }
                                                ?>
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
                            <nav aria-label="Sent Messages pagination">
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
                                            <a class="page-link" href="<?php echo APP_URL; ?>/messages/sent?page=<?php echo $pagination['current_page'] - 1; ?>&<?php echo $queryString; ?>">Previous</a>
                                        </li>
                                    <?php endif; ?>

                                    <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                                        <?php $activeClass = $i == $pagination['current_page'] ? ' active' : ''; ?>
                                        <li class="page-item<?php echo $activeClass; ?>">
                                            <a class="page-link" href="<?php echo APP_URL; ?>/messages/sent?page=<?php echo $i; ?>&<?php echo $queryString; ?>"><?php echo $i; ?></a>
                                        </li>
                                    <?php endfor; ?>

                                    <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="<?php echo APP_URL; ?>/messages/sent?page=<?php echo $pagination['current_page'] + 1; ?>&<?php echo $queryString; ?>">Next</a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>

                            <!-- Pagination Info -->
                            <div class="text-center text-muted">
                                Showing <?php echo (($pagination['current_page'] - 1) * $pagination['per_page']) + 1; ?> to 
                                <?php echo min($pagination['current_page'] * $pagination['per_page'], $pagination['total_items']); ?> 
                                of <?php echo $pagination['total_items']; ?> sent messages
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