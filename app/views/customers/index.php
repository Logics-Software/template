<?php 
$this->layout('layouts/app', ['title' => $title]) ?>

<?php $this->section('content') ?>
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-users me-2"></i>Data Customer</h5>
                    <a href="<?= BASE_URL ?>customers/create" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Tambah Customer
                    </a>
                </div>
                <div class="card-body">
                    <!-- Search & Filter -->
                    <form method="GET" class="mb-4">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="Cari nama/kode customer..." 
                                       value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                            </div>
                            <div class="col-md-2">
                                <select name="type" class="form-select">
                                    <option value="">-- Semua Type --</option>
                                    <option value="retail" <?= ($_GET['type'] ?? '') == 'retail' ? 'selected' : '' ?>>Retail</option>
                                    <option value="wholesale" <?= ($_GET['type'] ?? '') == 'wholesale' ? 'selected' : '' ?>>Wholesale</option>
                                    <option value="distributor" <?= ($_GET['type'] ?? '') == 'distributor' ? 'selected' : '' ?>>Distributor</option>
                                    <option value="other" <?= ($_GET['type'] ?? '') == 'other' ? 'selected' : '' ?>>Lainnya</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="status" class="form-select">
                                    <option value="">-- Semua Status --</option>
                                    <option value="active" <?= ($_GET['status'] ?? '') == 'active' ? 'selected' : '' ?>>Active</option>
                                    <option value="inactive" <?= ($_GET['status'] ?? '') == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                    <option value="prospect" <?= ($_GET['status'] ?? '') == 'prospect' ? 'selected' : '' ?>>Prospect</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="marketing" class="form-select">
                                    <option value="">-- Semua Marketing --</option>
                                    <?php foreach ($marketingList ?? [] as $marketing): ?>
                                        <option value="<?= $marketing['id'] ?>" <?= ($_GET['marketing'] ?? '') == $marketing['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($marketing['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Table -->
                    <?php if (!empty($customers)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama Customer</th>
                                        <th>Alamat</th>
                                        <th>Telepon</th>
                                        <th>Type</th>
                                        <th>Marketing</th>
                                        <th>Status</th>
                                        <th>Last Visit</th>
                                        <th>Total Visits</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($customers as $customer): ?>
                                        <tr>
                                            <td><strong><?= htmlspecialchars($customer['customer_code']) ?></strong></td>
                                            <td><?= htmlspecialchars($customer['customer_name']) ?></td>
                                            <td>
                                                <small><?= htmlspecialchars(substr($customer['address'], 0, 50)) ?><?= strlen($customer['address']) > 50 ? '...' : '' ?></small>
                                            </td>
                                            <td><?= htmlspecialchars($customer['phone'] ?? '-') ?></td>
                                            <td>
                                                <?php
                                                $typeBadges = [
                                                    'retail' => 'primary',
                                                    'wholesale' => 'info',
                                                    'distributor' => 'success',
                                                    'other' => 'secondary'
                                                ];
                                                $badge = $typeBadges[$customer['customer_type']] ?? 'secondary';
                                                ?>
                                                <span class="badge bg-<?= $badge ?>"><?= ucfirst($customer['customer_type']) ?></span>
                                            </td>
                                            <td>
                                                <small><?= htmlspecialchars($customer['marketing_name'] ?? 'Belum ditugaskan') ?></small>
                                            </td>
                                            <td>
                                                <?php
                                                $statusBadges = [
                                                    'active' => 'success',
                                                    'inactive' => 'secondary',
                                                    'prospect' => 'warning'
                                                ];
                                                $statusBadge = $statusBadges[$customer['status']] ?? 'secondary';
                                                ?>
                                                <span class="badge bg-<?= $statusBadge ?>"><?= ucfirst($customer['status']) ?></span>
                                            </td>
                                            <td>
                                                <small><?= $customer['last_visit_date'] ? date('d M Y', strtotime($customer['last_visit_date'])) : '-' ?></small>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-info"><?= $customer['total_visits'] ?></span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="<?= BASE_URL ?>customers/<?= $customer['id'] ?>" 
                                                       class="btn btn-info" title="Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="<?= BASE_URL ?>customers/<?= $customer['id'] ?>/edit" 
                                                       class="btn btn-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-danger" 
                                                            onclick="deleteCustomer(<?= $customer['id'] ?>)" 
                                                            title="Delete">
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
                        <?php if ($pagination['total_pages'] > 1): ?>
                            <nav aria-label="Page navigation" class="mt-4">
                                <ul class="pagination justify-content-center">
                                    <?php if ($pagination['current_page'] > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?page=<?= $pagination['current_page'] - 1 ?>&<?= http_build_query(array_filter($_GET, fn($k) => $k !== 'page', ARRAY_FILTER_USE_KEY)) ?>">
                                                Previous
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                                        <li class="page-item <?= $i == $pagination['current_page'] ? 'active' : '' ?>">
                                            <a class="page-link" href="?page=<?= $i ?>&<?= http_build_query(array_filter($_GET, fn($k) => $k !== 'page', ARRAY_FILTER_USE_KEY)) ?>">
                                                <?= $i ?>
                                            </a>
                                        </li>
                                    <?php endfor; ?>
                                    
                                    <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?page=<?= $pagination['current_page'] + 1 ?>&<?= http_build_query(array_filter($_GET, fn($k) => $k !== 'page', ARRAY_FILTER_USE_KEY)) ?>">
                                                Next
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Tidak ada data customer</p>
                            <a href="<?= BASE_URL ?>customers/create" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah Customer Pertama
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus customer ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Hapus</button>
            </div>
        </div>
    </div>
</div>
<?php $this->end() ?>

<?php $this->section('js') ?>
<script>
    let deleteId = null;
    
    function deleteCustomer(id) {
        deleteId = id;
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    }
    
    document.getElementById('confirmDelete').addEventListener('click', function() {
        if (!deleteId) return;
        
        fetch('<?= BASE_URL ?>customers/' + deleteId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?= $csrf_token ?>'
            },
            body: JSON.stringify({ _method: 'DELETE' })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert(data.error || 'Gagal menghapus customer');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
        });
    });
</script>
<?php $this->end() ?>

