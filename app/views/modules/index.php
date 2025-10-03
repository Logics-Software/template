<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Modules List</h5>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="<?php echo APP_URL; ?>/dashboard" class="text-decoration-none">Home</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Modules</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
            <div class="card-body">
                <!-- Search and Filter -->
                <div class="mb-4">
                    <form method="GET" action="<?php echo APP_URL; ?>/modules" class="row g-3">
                        <div class="col-md-4">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" class="form-control" id="search" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search by caption or link...">
                        </div>
                        <div class="col-md-3">
                            <label for="role" class="form-label">Role Access</label>
                            <select class="form-select" id="role" name="role">
                                <option value="">All Roles</option>
                                <option value="admin"<?php echo $role === 'admin' ? ' selected' : ''; ?>>Admin</option>
                                <option value="manajemen"<?php echo $role === 'manajemen' ? ' selected' : ''; ?>>Manajemen</option>
                                <option value="user"<?php echo $role === 'user' ? ' selected' : ''; ?>>User</option>
                                <option value="marketing"<?php echo $role === 'marketing' ? ' selected' : ''; ?>>Marketing</option>
                                <option value="customer"<?php echo $role === 'customer' ? ' selected' : ''; ?>>Customer</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <label for="per_page" class="form-label">Per Page</label>
                            <select class="form-select" id="per_page" name="per_page">
                                <option value="5"<?php echo $modules['per_page'] == 5 ? ' selected' : ''; ?>>5</option>
                                <option value="10"<?php echo $modules['per_page'] == 10 ? ' selected' : ''; ?>>10</option>
                                <option value="15"<?php echo $modules['per_page'] == 15 ? ' selected' : ''; ?>>15</option>
                                <option value="20"<?php echo $modules['per_page'] == 20 ? ' selected' : ''; ?>>20</option>
                                <option value="25"<?php echo $modules['per_page'] == 25 ? ' selected' : ''; ?>>25</option>
                                <option value="50"<?php echo $modules['per_page'] == 50 ? ' selected' : ''; ?>>50</option>
                                <option value="100"<?php echo $modules['per_page'] == 100 ? ' selected' : ''; ?>>100</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-outline-primary flex-fill">
                                    <i class="fas fa-search me-1"></i>Filter
                                </button>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <a href="<?php echo APP_URL; ?>/modules/create" class="btn btn-primary flex-fill" title="Tambah Modul Baru">
                                    <i class="fas fa-plus-circle me-1"></i>Add Module
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-sm" id="modulesTable">
                        <thead>
                            <tr>
                                <th>Logo</th>
                                <th class="sortable" data-sort="caption">
                                    Caption 
                                    <i class="fas fa-sort text-muted ms-1"></i>
                                </th>
                                <th class="sortable" data-sort="link">
                                    Link 
                                    <i class="fas fa-sort text-muted ms-1"></i>
                                </th>
                                <th>Role Access</th>
                                <th class="sortable" data-sort="created_at">
                                    Created At 
                                    <i class="fas fa-sort text-muted ms-1"></i>
                                </th>
                                <th class="sortable" data-sort="updated_at">
                                    Last Update 
                                    <i class="fas fa-sort text-muted ms-1"></i>
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
                                        <div class="d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="<?php echo htmlspecialchars($module['logo']); ?>" style="font-size: 20px; color: #6c757d;"></i>
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
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle action-menu-toggle action-btn-ellipsis" type="button" id="actionMenu<?php echo $module['id']; ?>" data-bs-toggle="dropdown" aria-expanded="false" title="Menu Aksi">
                                                <i class="fas fa-ellipsis-h"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end action-menu-dropdown" aria-labelledby="actionMenu<?php echo $module['id']; ?>">
                                                <li>
                                                    <a class="dropdown-item action-menu-item" href="<?php echo APP_URL; ?>/modules/<?php echo $module['id']; ?>">
                                                        <i class="fas fa-eye me-2"></i>View Details
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item action-menu-item" href="<?php echo APP_URL; ?>/modules/<?php echo $module['id']; ?>/edit">
                                                        <i class="fas fa-pencil me-2"></i>Edit Module
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <a class="dropdown-item action-menu-item text-danger" href="#" onclick="deleteModule(<?php echo $module['id']; ?>)">
                                                        <i class="fas fa-trash me-2"></i>Delete Module
                                                    </a>
                                                </li>
                                            </ul>
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
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this module? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
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
                alert("Error: " + (data.error || "Failed to delete module"));
            }
        })
        .catch(error => {
            alert("An error occurred while deleting the module");
        });
    }
});
</script>
