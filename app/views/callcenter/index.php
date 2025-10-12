<div class="row">
    <div class="col-12">
        <div class="form-container">
            <div class="form-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                    Daftar Call Center</h5>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="<?php echo APP_URL; ?>/dashboard" class="text-decoration-none">Home</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Daftar Call Center</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
            <div class="form-body">
                <!-- Search and Action Buttons -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <form method="GET" action="<?php echo APP_URL; ?>/callcenter" class="d-flex" id="searchForm">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Cari call center..." value="<?php echo htmlspecialchars($search); ?>" id="searchInput">
                                <button type="button" class="btn btn-secondary" id="searchToggleBtn" title="Search">
                                    <i class="fas fa-search" id="searchIcon"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    <form method="GET" action="<?php echo APP_URL; ?>/callcenter" class="col-md-2">
                        <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
                        <select class="form-select p-2" id="per_page" name="per_page" onchange="this.form.submit()">
                            <option value="5"<?php echo ($pagination['per_page'] ?? 10) == 5 ? ' selected' : ''; ?>>5</option>
                            <option value="10"<?php echo ($pagination['per_page'] ?? 10) == 10 ? ' selected' : ''; ?>>10</option>
                            <option value="15"<?php echo ($pagination['per_page'] ?? 10) == 15 ? ' selected' : ''; ?>>15</option>
                            <option value="20"<?php echo ($pagination['per_page'] ?? 10) == 20 ? ' selected' : ''; ?>>20</option>
                            <option value="25"<?php echo ($pagination['per_page'] ?? 10) == 25 ? ' selected' : ''; ?>>25</option>
                            <option value="50"<?php echo ($pagination['per_page'] ?? 10) == 50 ? ' selected' : ''; ?>>50</option>
                            <option value="100"<?php echo ($pagination['per_page'] ?? 10) == 100 ? ' selected' : ''; ?>>100</option>
                            <option value="200"<?php echo ($pagination['per_page'] ?? 10) == 200 ? ' selected' : ''; ?>>200</option>
                        </select>
                    </form>
                    <div class="col-md-6">
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="<?php echo APP_URL; ?>/callcenter/create" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>Tambah Call Center
                            </a>
                        </div>
                    </div>
                </div>
                
                <?php if (empty($callCenters)): ?>
                    <div class="text-center py-5">
                        <i class="fab fa-whatsapp fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Tidak ada data call center</h5>
                        <p class="text-muted">Mulai memasukkan data Call Center.</p>
                        <a href="<?php echo APP_URL; ?>/callcenter/create" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>Tambah Data Call Center
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="5%"><i class="fas fa-grip-vertical text-muted"></i></th>
                                    <th width="25%">Judul</th>
                                    <th width="20%">Nomor WhatsApp</th>
                                    <th width="35%">Deskripsi</th>
                                    <th width="15%"></th>
                                </tr>
                            </thead>
                            <tbody id="sortable-call-center">
                                <?php foreach ($callCenters as $callCenter): ?>
                                    <tr draggable="true" data-id="<?php echo $callCenter['id']; ?>" class="draggable-row">
                                        <td>
                                            <i class="fas fa-grip-vertical text-muted drag-handle cursor-grab"></i>
                                        </td>
                                        <td>
                                            <div class="fw-bold"><?php echo htmlspecialchars($callCenter['judul']); ?></div>
                                        </td>
                                        <td>
                                            <div class="text-muted"><?php echo htmlspecialchars($callCenter['nomorwa']); ?></div>
                                        </td>
                                        <td>
                                            <div class="text-muted">
                                                <?php echo htmlspecialchars(substr($callCenter['deskripsi'], 0, 100)); ?>
                                                <?php if (strlen($callCenter['deskripsi']) > 100): ?>...<?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1 min-w-80">
                                                <a href="<?php echo APP_URL; ?>/callcenter/<?php echo $callCenter['id']; ?>" class="btn btn-info btn-sm btn-action" 
                                                data-bs-toggle="tooltip" data-bs-title="Menampilkan Data Call Center">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?php echo APP_URL; ?>/callcenter/<?php echo $callCenter['id']; ?>/edit" class="btn btn-success btn-sm btn-action" 
                                                data-bs-toggle="tooltip" data-bs-title="Edit Data Call Center">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm btn-action" onclick="deleteCallCenter(<?php echo $callCenter['id']; ?>)" 
                                                data-bs-toggle="tooltip" data-bs-title="Hapus Data Call Center">
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
                    <?php if (isset($pagination)): ?>
                    <div class="row">
                        <div class="col-12">
                            <nav aria-label="Call Center pagination">
                                <ul class="pagination justify-content-center">
                                    <?php
                                    // Build query parameters
                                    $queryParams = [];
                                    if (!empty($search)) $queryParams['search'] = $search;
                                    if (!empty($pagination['per_page'])) $queryParams['per_page'] = $pagination['per_page'];

                                    $queryString = http_build_query($queryParams);
                                    ?>
                                    
                                    <?php if ($pagination['current_page'] > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="<?php echo APP_URL; ?>/callcenter?page=<?php echo $pagination['current_page'] - 1; ?>&<?php echo $queryString; ?>">Previous</a>
                                        </li>
                                    <?php endif; ?>

                                    <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                                        <?php $activeClass = $i == $pagination['current_page'] ? ' active' : ''; ?>
                                        <li class="page-item<?php echo $activeClass; ?>">
                                            <a class="page-link" href="<?php echo APP_URL; ?>/callcenter?page=<?php echo $i; ?>&<?php echo $queryString; ?>"><?php echo $i; ?></a>
                                        </li>
                                    <?php endfor; ?>

                                    <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="<?php echo APP_URL; ?>/callcenter?page=<?php echo $pagination['current_page'] + 1; ?>&<?php echo $queryString; ?>">Next</a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>

                            <!-- Pagination Info -->
                            <div class="text-center text-muted">
                                Showing <?php echo (($pagination['current_page'] - 1) * $pagination['per_page']) + 1; ?> to 
                                <?php echo min($pagination['current_page'] * $pagination['per_page'], $pagination['total_items']); ?> 
                                of <?php echo $pagination['total_items']; ?> entries
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                <?php endif; ?>
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

<!-- Drag and Drop styles moved to assets/css/complete.css for reusability -->

<!-- Include drag and drop utility -->
<script src="<?php echo APP_URL; ?>/assets/js/drag-drop.js"></script>

<?php
// Generate CSRF token once
$csrfToken = $csrf_token;
?>

<!-- CSRF Token Meta Tag -->
<meta name="csrf-token" content="<?php echo $csrfToken; ?>">

<!-- Hidden CSRF Token Input -->
<input type="hidden" name="_token" value="<?php echo $csrfToken; ?>">

<script>
// Set global CSRF token
window.csrfToken = '<?php echo $csrfToken; ?>';

// Initialize drag and drop for call center
document.addEventListener('DOMContentLoaded', function() {
    initDragDrop({
        sortOrderUrl: '<?php echo APP_URL; ?>/callcenter/update-sort',
        csrfToken: '<?php echo $csrfToken; ?>',
        onSuccess: function(data) {
            showSuccessMessage(data.message || 'Urutan berhasil diperbarui!');
        },
        onError: function(data) {
            showErrorMessage(data.error || 'Gagal memperbarui urutan');
            window.delayedReload();
        }
    });
});

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
            tokenInput.value = '<?php echo $csrfToken; ?>';
            
            form.appendChild(tokenInput);
            document.body.appendChild(form);
            form.submit();
        }
    });

    // Drag and Drop functionality is automatically initialized by drag-drop.js
});

// Drag and Drop functionality is now handled by drag-drop.js utility
</script>

<script>
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


