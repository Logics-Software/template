<?php
// Load call center data
require_once APP_PATH . '/app/models/CallCenter.php';
$callCenters = CallCenter::getAll();
?>
<!-- Footer -->
<footer class="footer">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="footer-left">
                    <p class="mb-0">&copy; <?php echo date('Y'); ?> - Developed by <a href="https://www.logics-ti.com" target="_blank">Logics Software</a></p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="footer-right">
                    <div class="d-flex align-items-center justify-content-end">
                        <div class="footer-links">
                            <a href="#" class="text-muted me-3">
                                <i class="fas fa-question-circle"></i>
                            </a>
                            <a href="#" class="text-muted me-3" data-bs-toggle="modal" data-bs-target="#whatsappModal">
                                <i class="fas fa-headset"></i>
                            </a>
                            <a href="#" class="text-muted me-3">
                                <i class="fas fa-book"></i>
                            </a>
                            <a href="#" class="text-muted">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- WhatsApp Contact Modal -->
<div class="modal fade" id="whatsappModal" tabindex="-1" aria-labelledby="whatsappModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="whatsappModalLabel">
                    <i class="fab fa-whatsapp text-success me-2"></i>Hubungi Kami via WhatsApp
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-4">Pilih kontak WhatsApp yang ingin Anda hubungi:</p>
                
                <?php if (empty($callCenters)): ?>
                    <div class="text-center py-5">
                        <i class="fab fa-whatsapp fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No call center entries found</h5>
                        <p class="text-muted">Please add call center entries from the settings menu.</p>
                    </div>
                <?php else: ?>
                    <div class="row g-3">
                        <?php foreach ($callCenters as $index => $callCenter): ?>
                            <div class="col-md-6">
                                <div class="card h-100 whatsapp-contact-card" 
                                     data-whatsapp="<?php echo preg_replace('/[^0-9]/', '', $callCenter['nomorwa']); ?>" 
                                     data-name="<?php echo htmlspecialchars($callCenter['judul']); ?>">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="fab fa-whatsapp fa-3x text-success"></i>
                                        </div>
                                        <h6 class="card-title"><?php echo htmlspecialchars($callCenter['judul']); ?></h6>
                                        <p class="card-text text-muted small"><?php echo htmlspecialchars($callCenter['nomorwa']); ?></p>
                                        <?php if (!empty($callCenter['deskripsi'])): ?>
                                            <p class="card-text small"><?php echo htmlspecialchars(substr($callCenter['deskripsi'], 0, 60)); ?><?php echo strlen($callCenter['deskripsi']) > 60 ? '...' : ''; ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<style>
.whatsapp-contact-card {
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.whatsapp-contact-card:hover {
    border-color: #25d366;
    box-shadow: 0 4px 12px rgba(37, 211, 102, 0.2);
    transform: translateY(-2px);
}

.whatsapp-contact-card:hover .fab {
    transform: scale(1.1);
    transition: transform 0.3s ease;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // WhatsApp contact click handler
    document.querySelectorAll('.whatsapp-contact-card').forEach(card => {
        card.addEventListener('click', function() {
            const phoneNumber = this.getAttribute('data-whatsapp');
            const contactName = this.getAttribute('data-name');
            
            // Create WhatsApp URL
            const whatsappUrl = `https://wa.me/${phoneNumber}?text=Halo ${contactName}, saya ingin bertanya tentang...`;
            
            // Open WhatsApp in new tab
            window.open(whatsappUrl, '_blank');
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('whatsappModal'));
            modal.hide();
        });
    });
});
</script>
