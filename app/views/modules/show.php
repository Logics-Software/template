<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Module Details</h5>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="<?php echo APP_URL; ?>/dashboard" class="text-decoration-none">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="<?php echo APP_URL; ?>/modules" class="text-decoration-none">Modules</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Details</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
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
                                    <label class="form-label fw-bold">Logo Icon</label>
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
                                        <i class="<?php echo htmlspecialchars($module['logo']); ?> icon-32"></i>
                                        <span class="ms-2 fs-5"><?php echo htmlspecialchars($module['caption']); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Role Access</label>
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
                    
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-header">
                                <h6 class="mb-0">Module Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label fw-bold small">Created At</label>
                                    <div class="text-muted small">
                                        <?php echo $module['created_at'] ? date('M d, Y H:i:s', strtotime($module['created_at'])) : 'N/A'; ?>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold small">Last Updated</label>
                                    <div class="text-muted small">
                                        <?php echo $module['updated_at'] ? date('M d, Y H:i:s', strtotime($module['updated_at'])) : 'N/A'; ?>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold small">Module ID</label>
                                    <div class="text-muted small">
                                        #<?php echo $module['id']; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex gap-2 mt-4">
                    <a href="<?php echo APP_URL; ?>/modules/<?php echo $module['id']; ?>/edit" class="btn btn-primary">
                        <i class="fas fa-pencil me-1"></i>Edit Module
                    </a>
                    <a href="<?php echo APP_URL; ?>/modules" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Back to Modules
                    </a>
                    <a href="<?php echo APP_URL; ?>/modules/create" class="btn btn-outline-primary">
                        <i class="fas fa-plus me-1"></i>Create New Module
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
