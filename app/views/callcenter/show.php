<div class="row">
    <div class="col-12">
        <div class="form-container">
            <div class="form-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Lihat Detail Call Center</h5>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="<?php echo APP_URL; ?>/dashboard" class="text-decoration-none">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="<?php echo APP_URL; ?>/callcenter" class="text-decoration-none">Daftar Call Center</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Detail Call Center</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
            <div class="form-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">Judul</h6>
                            <h4 class="mb-0"><?php echo htmlspecialchars($callCenter['judul']); ?></h4>
                        </div>
                        
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">Nomor WhatsApp</h6>
                            <div class="d-flex align-items-center">
                                <i class="fab fa-whatsapp text-success me-2"></i>
                                <span class="h5 mb-0"><?php echo htmlspecialchars($callCenter['nomorwa']); ?></span>
                            </div>
                        </div>
                        
                        <?php if (!empty($callCenter['deskripsi'])): ?>
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">Deskripsi</h6>
                            <p class="mb-0"><?php echo nl2br(htmlspecialchars($callCenter['deskripsi'])); ?></p>
                        </div>
                        <?php endif; ?>
                        
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">Created At</h6>
                            <p class="mb-0"><?php echo date('d F Y, H:i', strtotime($callCenter['created_at'])); ?></p>
                        </div>
                        
                        <?php if ($callCenter['updated_at'] !== $callCenter['created_at']): ?>
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">Last Updated</h6>
                            <p class="mb-0"><?php echo date('d F Y, H:i', strtotime($callCenter['updated_at'])); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <i class="fab fa-whatsapp fa-4x text-success mb-3"></i>
                                <h5 class="card-title">Contact via WhatsApp</h5>
                                <p class="card-text">Click the button below to start a WhatsApp conversation</p>
                                <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $callCenter['nomorwa']); ?>" 
                                   class="btn btn-success" target="_blank">
                                    <i class="fab fa-whatsapp me-1"></i>Chat on WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Card Footer -->
            <div class="form-footer d-flex justify-content-between align-items-center">
                <a href="<?php echo APP_URL; ?>/callcenter" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Kembali Ke Daftar Call Center
                </a>
                <div class="d-flex gap-2">
                    <a href="<?php echo APP_URL; ?>/callcenter/<?php echo $callCenter['id']; ?>/edit" class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i>Edit
                    </a>
                    <button type="button" class="btn btn-danger" onclick="deleteCallCenter(<?php echo $callCenter['id']; ?>)">
                        <i class="fas fa-trash me-1"></i>Delete
                    </button>
                </div>
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
            form.action = `<?php echo APP_URL; ?>/callcenter/${deleteCallCenterId}/delete`;
            
            const tokenInput = document.createElement('input');
            tokenInput.type = 'hidden';
            tokenInput.name = '_token';
            tokenInput.value = '<?php echo $csrf_token; ?>';
            
            form.appendChild(tokenInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
});
</script>
