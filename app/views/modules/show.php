<div class="row">
    <div class="col-12">
        <div class="form-container">
            <div class="form-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Detail Modul</h5>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="<?php echo APP_URL; ?>/dashboard" class="text-decoration-none">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="<?php echo APP_URL; ?>/modules" class="text-decoration-none">Daftar Modul</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Detail</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Caption</label>
                                    <div class="form-control-plaintext bg-light p-3 rounded border">
                                        <?php echo htmlspecialchars($module['caption']); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Link</label>
                                    <div class="form-control-plaintext bg-light p-3 rounded border">
                                        <code class="text-primary"><?php echo htmlspecialchars($module['link']); ?></code>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Icon</label>
                                    <div class="form-control-plaintext bg-light p-3 rounded border">
                                        <i class="<?php echo htmlspecialchars($module['logo']); ?> icon-24"></i>
                                        <code class="ms-2"><?php echo htmlspecialchars($module['logo']); ?></code>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Preview</label>
                                    <div class="border rounded p-3 bg-light">
                                        <i class="<?php echo htmlspecialchars($module['logo']); ?> icon-24"></i>
                                        <span class="ms-2 fs-6"><?php echo htmlspecialchars($module['caption']); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Hak Akses Role</label>
                                    <div class="form-control-plaintext bg-light p-3 rounded border">
                                        <div class="d-flex flex-wrap gap-2">
                                            <?php if ($module['admin']): ?>
                                                <span class="badge bg-danger fs-6">Admin</span>
                                            <?php endif; ?>
                                            <?php if ($module['manajemen']): ?>
                                                <span class="badge bg-primary fs-6">Manajemen</span>
                                            <?php endif; ?>
                                            <?php if ($module['user']): ?>
                                                <span class="badge bg-info fs-6">User</span>
                                            <?php endif; ?>
                                            <?php if ($module['marketing']): ?>
                                                <span class="badge bg-warning fs-6">Marketing</span>
                                            <?php endif; ?>
                                            <?php if ($module['customer']): ?>
                                                <span class="badge bg-success fs-6">Customer</span>
                                            <?php endif; ?>
                                            <?php if (!$module['admin'] && !$module['manajemen'] && !$module['user'] && !$module['marketing'] && !$module['customer']): ?>
                                                <span class="text-muted">No roles assigned</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex gap-2 mt-4">
                    <a href="<?php echo APP_URL; ?>/modules" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Kembali ke Daftar Modul
                    </a>
                    <a href="<?php echo APP_URL; ?>/modules/<?php echo $module['id']; ?>/edit" class="btn btn-warning">
                        <i class="fas fa-pencil me-1"></i>Edit
                    </a>
                    <a href="<?php echo APP_URL; ?>/modules/create" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Tambah Modul Baru
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
