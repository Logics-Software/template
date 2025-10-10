<div class="row">
    <div class="col-12">
        <div class="form-container">
            <div class="form-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Setting Akses Menu</h5>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="<?php echo APP_URL; ?>/dashboard" class="text-decoration-none">Home</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Setting Akses Menu</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
            <div class="form-body">
                <!-- Search and Filter -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <form method="GET" action="<?php echo APP_URL; ?>/menuakses" class="d-flex" id="searchForm">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Cari user..." value="<?php echo htmlspecialchars($search); ?>" id="searchInput">
                                <button type="button" class="btn btn-secondary" id="searchToggleBtn" title="Search">
                                    <i class="fas fa-search" id="searchIcon"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    <form method="GET" action="<?php echo APP_URL; ?>/menuakses" class="col-md-2">
                        <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
                        <input type="hidden" name="role" value="<?php echo htmlspecialchars($role); ?>">
                        <input type="hidden" name="per_page" value="<?php echo htmlspecialchars($users['per_page']); ?>">
                        <select class="form-select p-2" id="status" name="status" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="aktif"<?php echo $status === 'aktif' ? ' selected' : ''; ?>>Aktif</option>
                            <option value="non_aktif"<?php echo $status === 'non_aktif' ? ' selected' : ''; ?>>Non Aktif</option>
                            <option value="register"<?php echo $status === 'register' ? ' selected' : ''; ?>>Register</option>
                        </select>
                    </form>
                    <form method="GET" action="<?php echo APP_URL; ?>/menuakses" class="col-md-2">
                        <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
                        <input type="hidden" name="status" value="<?php echo htmlspecialchars($status); ?>">
                        <input type="hidden" name="per_page" value="<?php echo htmlspecialchars($users['per_page']); ?>">
                        <select class="form-select p-2" id="role" name="role" onchange="this.form.submit()">
                            <option value="">Semua Role</option>
                            <option value="admin"<?php echo $role === 'admin' ? ' selected' : ''; ?>>Admin</option>
                            <option value="manajemen"<?php echo $role === 'manajemen' ? ' selected' : ''; ?>>Manajemen</option>
                            <option value="user"<?php echo $role === 'user' ? ' selected' : ''; ?>>User</option>
                            <option value="marketing"<?php echo $role === 'marketing' ? ' selected' : ''; ?>>Marketing</option>
                            <option value="customer"<?php echo $role === 'customer' ? ' selected' : ''; ?>>Customer</option>
                        </select>
                    </form>
                    <form method="GET" action="<?php echo APP_URL; ?>/menuakses" class="col-md-2">
                        <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
                        <input type="hidden" name="status" value="<?php echo htmlspecialchars($status); ?>">
                        <input type="hidden" name="role" value="<?php echo htmlspecialchars($role); ?>">
                        <select class="form-select p-2" id="per_page" name="per_page" onchange="this.form.submit()">
                            <option value="5"<?php echo $users['per_page'] == 5 ? ' selected' : ''; ?>>5</option>
                            <option value="10"<?php echo $users['per_page'] == 10 ? ' selected' : ''; ?>>10</option>
                            <option value="15"<?php echo $users['per_page'] == 15 ? ' selected' : ''; ?>>15</option>
                            <option value="20"<?php echo $users['per_page'] == 20 ? ' selected' : ''; ?>>20</option>
                            <option value="25"<?php echo $users['per_page'] == 25 ? ' selected' : ''; ?>>25</option>
                            <option value="50"<?php echo $users['per_page'] == 50 ? ' selected' : ''; ?>>50</option>
                            <option value="100"<?php echo $users['per_page'] == 100 ? ' selected' : ''; ?>>100</option>
                        </select>
                    </form>
                    <div class="col-md-2">
                        <!-- Placeholder for alignment -->
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-md" id="usersMenuTable">
                        <thead>
                            <tr>
                                <th class="sortable" data-sort="username">
                                    Username 
                                    <i class="fas fa-sort color-muted ms-1"></i>
                                </th>
                                <th class="sortable" data-sort="namalengkap">
                                    Nama Lengkap 
                                    <i class="fas fa-sort color-muted ms-1"></i>
                                </th>
                                <th class="sortable" data-sort="role">
                                    Role 
                                    <i class="fas fa-sort color-muted ms-1"></i>
                                </th>
                                <th>Group Menu</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($users['data'])): ?>
                                <?php foreach ($users['data'] as $user): ?>
                                    <?php
                                    // Role color mapping
                                    $roleClass = match($user['role'] ?? '') {
                                        'admin' => 'danger',
                                        'manajemen' => 'primary',
                                        'marketing' => 'warning',
                                        'customer' => 'success',
                                        default => 'info'
                                    };
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($user['username'] ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($user['namalengkap'] ?? 'N/A'); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $roleClass; ?>"><?php echo ucfirst($user['role'] ?? 'N/A'); ?></span>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-1">
                                                <?php if (!empty($user['group_names']) && is_array($user['group_names'])): ?>
                                                    <?php foreach ($user['group_names'] as $index => $groupName): ?>
                                                        <?php 
                                                        $groupIcon = $user['group_icons'][$index] ?? 'fas fa-folder';
                                                        ?>
                                                        <span class="badge bg-primary" title="<?php echo htmlspecialchars($groupName); ?>">
                                                            <i class="<?php echo htmlspecialchars($groupIcon); ?>"></i>
                                                            <?php echo htmlspecialchars($groupName); ?>
                                                        </span>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <span class="text-muted small">No menu groups assigned</span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="<?php echo APP_URL; ?>/menuakses/<?php echo $user['id']; ?>/edit" class="btn btn-sm btn-outline-primary btn-action" title="Edit Menu Access">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="fas fa-users"></i>
                                        <p class="mb-0">No users found</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
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
                        if (!empty($status)) $queryParams['status'] = $status;
                        if (!empty($role)) $queryParams['role'] = $role;
                        if (!empty($users['per_page'])) $queryParams['per_page'] = $users['per_page'];
                        if (!empty($_GET['sort'])) $queryParams['sort'] = $_GET['sort'];
                        if (!empty($_GET['order'])) $queryParams['order'] = $_GET['order'];

                        $currentPerPage = $_GET['per_page'] ?? $users['per_page'] ?? DEFAULT_PAGE_SIZE;
                        $queryParams['per_page'] = $currentPerPage;

                        $queryString = http_build_query($queryParams);
                        ?>
                        
                        <?php if ($users['current_page'] > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo APP_URL; ?>/menuakses?page=<?php echo $users['current_page'] - 1; ?>&<?php echo $queryString; ?>">Previous</a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $users['last_page']; $i++): ?>
                            <?php $activeClass = $i == $users['current_page'] ? ' active' : ''; ?>
                            <li class="page-item<?php echo $activeClass; ?>">
                                <a class="page-link" href="<?php echo APP_URL; ?>/menuakses?page=<?php echo $i; ?>&<?php echo $queryString; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($users['current_page'] < $users['last_page']): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo APP_URL; ?>/menuakses?page=<?php echo $users['current_page'] + 1; ?>&<?php echo $queryString; ?>">Next</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>

                <!-- Pagination Info -->
                <div class="text-center text-muted">
                    Showing <?php echo $users['from']; ?> to <?php echo $users['to']; ?> of <?php echo $users['total']; ?> entries
                </div>
            </div>
        </div>
    </div>
</div>

<script>
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
});
</script>

