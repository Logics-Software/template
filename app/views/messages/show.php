<div class="row">
    <div class="col-12">
        <div class="form-container">
            <div class="form-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">
                        <?php echo htmlspecialchars($message['subject'] ?? '(No Subject)'); ?>
                    </h5>
                    <small class="text-muted">
                        Dari: <?php echo htmlspecialchars($message['sender_name'] ?? 'Unknown'); ?> 
                        (<?php echo htmlspecialchars($message['sender_email'] ?? '-'); ?>)
                    </small>
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="<?php echo APP_URL; ?>/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo APP_URL; ?>/messages">Pesan Masuk</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail Pesan</li>
                    </ol>
                </nav>
            </div>
            
            <div class="form-body">
                <div class="row">
                    <div class="col-md-8">
                        <!-- Message Content -->
                        <div class="message-content">
                            <div class="mb-4">
                                <div class="d-flex align-items-center mb-3">
                                    <?php if (!empty($message['sender_picture'])): ?>
                                        <img src="<?php echo APP_URL; ?>/<?php echo htmlspecialchars($message['sender_picture']); ?>" 
                                                alt="<?php echo htmlspecialchars($message['sender_name']); ?>" 
                                                class="avatar-lg rounded-circle me-3 avatar-48"
                                                style="width: 32px; height: 32px;">
                                    <?php else: ?>
                                        <div class="avatar-lg bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                            <?php echo strtoupper(substr($message['sender_name'], 0, 1)); ?>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <h6 class="mb-1"><?php echo htmlspecialchars($message['sender_name'] ?? 'Unknown'); ?></h6>
                                        <small class="text-muted">
                                            <?php echo date('d F Y, H:i', strtotime($message['created_at'] ?? 'now')); ?>
                                            <?php if (($message['status'] ?? '') === 'read'): ?>
                                                <span class="badge bg-success ms-2">Sudah dibaca</span>
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="message-body">
                                <?php echo $message['content'] ?? ''; ?>
                            </div>
                            
                            <?php if (!empty($message['attachments'])): ?>
                            <div class="mt-4">
                                <h6 class="mb-3">
                                    <i class="fas fa-paperclip me-1"></i>Lampiran
                                </h6>
                                <div class="row">
                                    <?php foreach ($message['attachments'] as $attachment): ?>
                                    <div class="col-md-6 mb-2">
                                        <div class="card">
                                            <div class="card-body p-2">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-file me-2 text-primary"></i>
                                                    <div class="flex-grow-1">
                                                        <div class="fw-bold"><?php echo htmlspecialchars($attachment['original_name']); ?></div>
                                                        <small class="text-muted">
                                                            <?php echo number_format($attachment['file_size'] / 1024, 1); ?> KB
                                                        </small>
                                                    </div>
                                                    <a href="<?php echo APP_URL; ?>/<?php echo $attachment['file_path']; ?>" 
                                                        class="btn btn-sm btn-outline-primary" 
                                                        target="_blank" 
                                                        download="<?php echo htmlspecialchars($attachment['original_name']); ?>">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        
                        <!-- Recipients - Only show for sent messages (not for received messages) -->
                        <?php if (!empty($message['recipients']) && !$is_recipient): ?>
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fas fa-users me-1"></i>Penerima
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <?php foreach ($message['recipients'] as $recipient): ?>
                                        <div class="d-flex align-items-center mb-2">
                                            <?php if (!empty($recipient['recipient_picture'])): ?>
                                                <img src="<?php echo APP_URL; ?>/<?php echo htmlspecialchars($recipient['recipient_picture']); ?>" 
                                                        alt="<?php echo htmlspecialchars($recipient['recipient_name']); ?>" 
                                                        class="avatar-sm rounded-circle me-2 avatar-32"
                                                        style="width: 32px; height: 32px;">
                                            <?php else: ?>
                                                <div class="avatar-sm bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                                    <?php echo strtoupper(substr($recipient['recipient_name'], 0, 1)); ?>
                                                </div>
                                            <?php endif; ?>
                                            <div class="flex-grow-1">
                                                <div class="fw-bold"><?php echo htmlspecialchars($recipient['recipient_name']); ?></div>
                                                <small class="text-muted"><?php echo htmlspecialchars($recipient['recipient_email']); ?></small>
                                            </div>
                                            <div>
                                                <?php if ($recipient['is_read']): ?>
                                                    <i class="fas fa-check-circle text-success"></i>
                                                <?php else: ?>
                                                    <i class="fas fa-clock text-warning"></i>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                    </div>
                </div>
            </div>
            
            <!-- Card Footer -->
            <div class="form-footer d-flex justify-content-between align-items-center">
                <div class="d-flex gap-2">
                    <a href="<?php echo APP_URL; ?>/messages" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                </div>
                <div class="d-flex gap-2">
                    <a href="<?php echo APP_URL; ?>/messages/create?reply=<?php echo $message['id']; ?>" class="btn btn-primary">
                        <i class="fas fa-reply me-1"></i>Balas
                    </a>
                    <a href="<?php echo APP_URL; ?>/messages/create?forward=<?php echo $message['id']; ?>" class="btn btn-primary">
                        <i class="fas fa-share me-1"></i>Teruskan
                    </a>
                    <button type="button" class="btn btn-primary" onclick="printMessage()">
                        <i class="fas fa-print me-1"></i>Cetak
                    </button>
                    <button type="button" class="btn btn-primary" onclick="deleteMessage(<?php echo $message['id']; ?>)">
                        <i class="fas fa-trash me-1"></i>Hapus
                    </button>
                </div>
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

// Delete message confirmation and mark as read handler
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
                window.location.href = '<?php echo APP_URL; ?>/messages';
            } else {
                // Close modal first
                const modal = bootstrap.Modal.getInstance(document.getElementById("deleteMessageModal"));
                modal.hide();
                
                // Show error alert
                const alertDiv = document.createElement("div");
                alertDiv.className = "alert alert-danger alert-dismissible fade show";
                alertDiv.innerHTML = `
                    <i class="fas fa-exclamation-circle me-1"></i>${data.message || 'Gagal menghapus pesan'}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.querySelector(".card-body").insertBefore(alertDiv, document.querySelector(".message-content"));
            }
        })
        .catch(error => {
            // Close modal first
            const modal = bootstrap.Modal.getInstance(document.getElementById("deleteMessageModal"));
            modal.hide();
            
            // Show error alert
            const alertDiv = document.createElement("div");
            alertDiv.className = "alert alert-danger alert-dismissible fade show";
            alertDiv.innerHTML = `
                <i class="fas fa-exclamation-circle me-1"></i>Terjadi kesalahan saat menghapus pesan
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.querySelector(".card-body").insertBefore(alertDiv, document.querySelector(".message-content"));
        });
        }
    });

    // Mark as read when page loads
    <?php if ($is_recipient): ?>
    fetch('<?php echo APP_URL; ?>/api/messages/mark-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-Token': '<?php echo Session::generateCSRF(); ?>'
        },
        body: JSON.stringify({
            message_id: <?php echo $message['id']; ?>,
            _token: '<?php echo Session::generateCSRF(); ?>'
        })
    });
    <?php endif; ?>
});

function printMessage() {
    window.print();
}
</script>
