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
                <!-- Action Buttons -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="<?php echo APP_URL; ?>/messages/create" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>Tulis Pesan
                            </a>
                            <a href="<?php echo APP_URL; ?>/messages" class="btn btn-outline-secondary">
                                <i class="fas fa-inbox me-1"></i>Pesan Masuk
                            </a>
                        </div>
                    </div>
                </div>

                <?php if (empty($messages)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-paper-plane fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum ada pesan terkirim</h5>
                        <p class="text-muted">Anda belum mengirim pesan apapun.</p>
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
                                    <th width="40%">Subjek</th>
                                    <th width="30%">Penerima</th>
                                    <th width="15%">Tanggal</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($messages as $message): ?>
                                    <tr>
                                        <td>
                                            <i class="fas fa-paper-plane text-primary" title="Pesan terkirim"></i>
                                        </td>
                                        <td>
                                            <div class="fw-bold"><?php echo htmlspecialchars($message['subject']); ?></div>
                                            <small class="text-muted">
                                                <?php echo htmlspecialchars(substr(strip_tags($message['content']), 0, 100)); ?>
                                                <?php if (strlen(strip_tags($message['content'])) > 100): ?>...<?php endif; ?>
                                            </small>
                                        </td>
                                        <td>
                                            <small class="text-muted"><?php echo htmlspecialchars($message['recipients']); ?></small>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <?php echo date('d/m/Y H:i', strtotime($message['created_at'])); ?>
                                            </small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="<?php echo APP_URL; ?>/messages/<?php echo $message['id']; ?>" class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="deleteMessage(<?php echo $message['id']; ?>)">
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
                    <?php if ($page > 1): ?>
                        <nav aria-label="Message pagination">
                            <ul class="pagination justify-content-center">
                                <?php if ($page > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=<?php echo $page - 1; ?>">Sebelumnya</a>
                                    </li>
                                <?php endif; ?>
                                
                                <li class="page-item active">
                                    <span class="page-link"><?php echo $page; ?></span>
                                </li>
                                
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $page + 1; ?>">Selanjutnya</a>
                                </li>
                            </ul>
                        </nav>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function deleteMessage(messageId) {
    if (confirm('Apakah Anda yakin ingin menghapus pesan ini?')) {
        fetch(`<?php echo APP_URL; ?>/messages/${messageId}`, {
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
                alert('Gagal menghapus pesan: ' + data.message);
            }
        })
        .catch(error => {
            alert('Terjadi kesalahan saat menghapus pesan');
        });
    }
}
</script>
