<div class="row">
    <div class="col-12">
        <div class="form-container">
            <div class="form-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Daftar Modul</h5>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="<?php echo APP_URL; ?>/dashboard" class="text-decoration-none">Home</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Daftar Modul</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
            <div class="form-body">
                <!-- Search and Filter -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <form method="GET" action="<?php echo APP_URL; ?>/modules" class="d-flex" id="searchForm">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Cari modul..." value="<?php echo htmlspecialchars($search); ?>" id="searchInput">
                                <button type="button" class="btn btn-secondary" id="searchToggleBtn" title="Search">
                                    <i class="fas fa-search" id="searchIcon"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    <form method="GET" action="<?php echo APP_URL; ?>/modules" class="col-md-3">
                        <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
                        <input type="hidden" name="per_page" value="<?php echo htmlspecialchars($modules['per_page']); ?>">
                        <select class="form-select p-2" id="role" name="role" onchange="this.form.submit()">
                            <option value="">Semua Role</option>
                            <option value="admin"<?php echo $role === 'admin' ? ' selected' : ''; ?>>Admin</option>
                            <option value="manajemen"<?php echo $role === 'manajemen' ? ' selected' : ''; ?>>Manajemen</option>
                            <option value="user"<?php echo $role === 'user' ? ' selected' : ''; ?>>User</option>
                            <option value="marketing"<?php echo $role === 'marketing' ? ' selected' : ''; ?>>Marketing</option>
                            <option value="customer"<?php echo $role === 'customer' ? ' selected' : ''; ?>>Customer</option>
                        </select>
                    </form>
                    <form method="GET" action="<?php echo APP_URL; ?>/modules" class="col-md-2">
                        <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
                        <input type="hidden" name="role" value="<?php echo htmlspecialchars($role); ?>">
                        <select class="form-select p-2" id="per_page" name="per_page" onchange="this.form.submit()">
                            <option value="5"<?php echo $modules['per_page'] == 5 ? ' selected' : ''; ?>>5</option>
                            <option value="10"<?php echo $modules['per_page'] == 10 ? ' selected' : ''; ?>>10</option>
                            <option value="15"<?php echo $modules['per_page'] == 15 ? ' selected' : ''; ?>>15</option>
                            <option value="20"<?php echo $modules['per_page'] == 20 ? ' selected' : ''; ?>>20</option>
                            <option value="25"<?php echo $modules['per_page'] == 25 ? ' selected' : ''; ?>>25</option>
                            <option value="50"<?php echo $modules['per_page'] == 50 ? ' selected' : ''; ?>>50</option>
                            <option value="100"<?php echo $modules['per_page'] == 100 ? ' selected' : ''; ?>>100</option>
                        </select>
                    </form>
                    <div class="col-md-3">
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="<?php echo APP_URL; ?>/modules/create" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>Tambah Modul
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-md modules-table" id="modulesTable">
                        <thead>
                            <tr>
                                <th>Logo</th>
                                <th class="sortable" data-sort="caption">
                                    Caption 
                                    <i class="fas fa-sort color-muted ms-1"></i>
                                </th>
                                <th class="sortable" data-sort="link">
                                    Link 
                                    <i class="fas fa-sort color-muted ms-1"></i>
                                </th>
                                <th>Hak Akses Role</th>
                                <th class="sortable" data-sort="created_at">
                                    Dibuat Pada 
                                    <i class="fas fa-sort color-muted ms-1"></i>
                                </th>
                                <th class="sortable" data-sort="updated_at">
                                    Update 
                                    <i class="fas fa-sort color-muted ms-1"></i>
                                </th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($modules['data'] as $module): ?>
                                <?php
                                // Role access badges
                                $roleBadges = [];
                                if ($module['admin']) $roleBadges[] = '<span class="badge bg-danger">Admin</span>';
                                if ($module['manajemen']) $roleBadges[] = '<span class="badge bg-primary">Manajemen</span>';
                                if ($module['user']) $roleBadges[] = '<span class="badge bg-info">User</span>';
                                if ($module['marketing']) $roleBadges[] = '<span class="badge bg-warning">Marketing</span>';
                                if ($module['customer']) $roleBadges[] = '<span class="badge bg-success">Customer</span>';
                                
                                // Created at formatting
                                $createdAt = $module['created_at'] ? 
                                    '<small class="text-muted">' . date('M d, Y H:i', strtotime($module['created_at'])) . '</small>' :
                                    '<small class="text-muted">Never</small>';
                                
                                // Updated at formatting
                                $updatedAt = $module['updated_at'] ? 
                                    '<small class="text-muted">' . date('M d, Y H:i', strtotime($module['updated_at'])) . '</small>' :
                                    '<small class="text-muted">Never</small>';
                                ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center justify-content-center avatar-40">
                                            <i class="<?php echo htmlspecialchars($module['logo']); ?> icon-20"></i>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($module['caption'] ?? 'N/A'); ?></td>
                                    <td>
                                        <?php if (!empty($module['link']) && $module['link'] !== 'N/A'): ?>
                                            <a href="<?php echo APP_URL . $module['link']; ?>" target="_blank" class="module-link text-decoration-none" title="Open <?php echo htmlspecialchars($module['link']); ?> in new tab">
                                                <code class="text-primary">
                                                    <i class="fas fa-external-link-alt me-1"></i>
                                                    <?php echo htmlspecialchars($module['link']); ?>
                                                </code>
                                            </a>
                                        <?php else: ?>
                                            <code class="text-muted">N/A</code>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php echo implode(' ', $roleBadges); ?>
                                    </td>
                                    <td class="text-muted small">
                                        <?php echo $createdAt; ?>
                                    </td>
                                    <td class="text-muted small">
                                        <?php echo $updatedAt; ?>
                                    </td>
                                    <td align="center">
                                        <div class="d-flex gap-1 min-w-80">
                                            <a href="<?php echo APP_URL; ?>/modules/<?php echo $module['id']; ?>" class="btn btn-info btn-sm btn-action" data-bs-toggle="tooltip" data-bs-title="Tampilkan Data Modul">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?php echo APP_URL; ?>/modules/<?php echo $module['id']; ?>/edit" class="btn btn-success btn-sm btn-action" data-bs-toggle="tooltip" data-bs-title="Edit Modul">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm btn-action" onclick="deleteModule(<?php echo $module['id']; ?>)" data-bs-toggle="tooltip" data-bs-title="Hapus Modul">
                                                <i class="fas fa-trash-can"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <nav>
                    <ul class="pagination justify-content-center">
                        <?php
                        // Build query parameters
                        $queryParams = [];
                        if (!empty($search)) $queryParams['search'] = $search;
                        if (!empty($role)) $queryParams['role'] = $role;
                        if (!empty($modules['per_page'])) $queryParams['per_page'] = $modules['per_page'];
                        if (!empty($_GET['sort'])) $queryParams['sort'] = $_GET['sort'];
                        if (!empty($_GET['order'])) $queryParams['order'] = $_GET['order'];

                        $currentPerPage = $_GET['per_page'] ?? $modules['per_page'] ?? DEFAULT_PAGE_SIZE;
                        $queryParams['per_page'] = $currentPerPage;

                        $queryString = http_build_query($queryParams);
                        ?>
                        
                        <?php if ($modules['current_page'] > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo APP_URL; ?>/modules?page=<?php echo $modules['current_page'] - 1; ?>&<?php echo $queryString; ?>">Previous</a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $modules['last_page']; $i++): ?>
                            <?php $activeClass = $i == $modules['current_page'] ? ' active' : ''; ?>
                            <li class="page-item<?php echo $activeClass; ?>">
                                <a class="page-link" href="<?php echo APP_URL; ?>/modules?page=<?php echo $i; ?>&<?php echo $queryString; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($modules['current_page'] < $modules['last_page']): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo APP_URL; ?>/modules?page=<?php echo $modules['current_page'] + 1; ?>&<?php echo $queryString; ?>">Next</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>

                <!-- Pagination Info -->
                <div class="text-center text-muted">
                    Showing <?php echo $modules['from']; ?> to <?php echo $modules['to']; ?> of <?php echo $modules['total']; ?> entries
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Anda yakin akan menghapus modul ini? Proses ini tidak dapat dibatalkan.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Hapus</button>
            </div>
        </div>
    </div>
</div>

<style>
.module-link {
    transition: all 0.2s ease;
}

.module-link:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.module-link code {
    background-color: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 4px;
    padding: 4px 8px;
    font-size: 0.875em;
    transition: all 0.2s ease;
}

.module-link:hover code {
    background-color: #e3f2fd;
    border-color: #2196f3;
    color: #1976d2 !important;
}

.module-link i {
    font-size: 0.75em;
}

</style>

<script>
let deleteModuleId = null;

// Table sorting functionality
document.addEventListener("DOMContentLoaded", function() {
    const sortableHeaders = document.querySelectorAll(".sortable");
    
    sortableHeaders.forEach(header => {
        header.addEventListener("click", function() {
            const sortField = this.getAttribute("data-sort");
            const currentSort = new URLSearchParams(window.location.search).get("sort");
            const currentOrder = new URLSearchParams(window.location.search).get("order");
            
            let newOrder = "asc";
            if (currentSort === sortField && currentOrder === "asc") {
                newOrder = "desc";
            }
            
            // Update URL with new sort parameters
            const url = new URL(window.location);
            url.searchParams.set("sort", sortField);
            url.searchParams.set("order", newOrder);
            
            // Redirect to new URL
            window.location.href = url.toString();
        });
    });
    
    // Update sort indicators based on current sort
    const currentSort = new URLSearchParams(window.location.search).get("sort");
    const currentOrder = new URLSearchParams(window.location.search).get("order");
    
    if (currentSort) {
        const activeHeader = document.querySelector("[data-sort=\"" + currentSort + "\"]");
        if (activeHeader) {
            activeHeader.classList.add("sort-" + (currentOrder || "asc"));
        }
    }
    
    // Auto-submit form when per_page changes
    const perPageSelect = document.getElementById("per_page");
    if (perPageSelect) {
        perPageSelect.addEventListener("change", function() {
            this.form.submit();
        });
    }
    
    // Auto-submit form when role changes
    const roleSelect = document.getElementById("role");
    if (roleSelect) {
        roleSelect.addEventListener("change", function() {
            this.form.submit();
        });
    }
});

function deleteModule(id) {
    deleteModuleId = id;
    const modal = new bootstrap.Modal(document.getElementById("deleteModal"));
    modal.show();
}

// Delete module confirmation
document.getElementById("confirmDelete").addEventListener("click", function() {
    if (deleteModuleId) {
        fetch("<?php echo APP_URL; ?>/modules/" + deleteModuleId, {
            method: "DELETE",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-Token": window.csrfToken,
                "X-Requested-With": "XMLHttpRequest"
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                showToast('error', data.error || "Failed to delete module");
            }
        })
        .catch(error => {
            showToast('error', "An error occurred while deleting the module");
        });
    }
});

// Search/Reset Toggle Functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('searchForm');
    const searchInput = document.getElementById('searchInput');
    const searchToggleBtn = document.getElementById('searchToggleBtn');
    const searchIcon = document.getElementById('searchIcon');
    
    let isSearchMode = true;
    
    // Check if there's a search value to determine initial mode
    if (searchInput.value.trim() !== '') {
        isSearchMode = false;
        updateButtonState();
    }
    
    function updateButtonState() {
        if (isSearchMode) {
            searchToggleBtn.title = 'Search';
            searchIcon.className = 'fas fa-search';
            searchToggleBtn.onclick = function() {
                searchForm.submit();
            };
        } else {
            searchToggleBtn.title = 'Reset';
            searchIcon.className = 'fas fa-times';
            searchToggleBtn.onclick = function() {
                searchInput.value = '';
                searchForm.submit();
            };
        }
    }
    
    // Toggle mode when input changes
    searchInput.addEventListener('input', function() {
        const hasValue = this.value.trim() !== '';
        if (hasValue && isSearchMode) {
            isSearchMode = false;
            updateButtonState();
        } else if (!hasValue && !isSearchMode) {
            isSearchMode = true;
            updateButtonState();
        }
    });
    
    // Initialize button state
    updateButtonState();
});
</script>