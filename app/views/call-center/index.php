<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Call Center</h5>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="<?php echo APP_URL; ?>/dashboard" class="text-decoration-none">Home</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Call Center</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
            <div class="card-body">
                <!-- Search and Action Buttons -->
                <div class="mb-4">
                    <form method="GET" action="<?php echo APP_URL; ?>/call-center" class="row g-3">
                        <div class="col-md-4">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" class="form-control" id="search" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search by title, number, or description...">
                        </div>
                        <div class="col-md-2">
                            <label for="per_page" class="form-label">Per Page</label>
                            <select class="form-select" id="per_page" name="per_page" onchange="this.form.submit()">
                                <option value="5"<?php echo ($pagination['per_page'] ?? 10) == 5 ? ' selected' : ''; ?>>5</option>
                                <option value="10"<?php echo ($pagination['per_page'] ?? 10) == 10 ? ' selected' : ''; ?>>10</option>
                                <option value="15"<?php echo ($pagination['per_page'] ?? 10) == 15 ? ' selected' : ''; ?>>15</option>
                                <option value="20"<?php echo ($pagination['per_page'] ?? 10) == 20 ? ' selected' : ''; ?>>20</option>
                                <option value="25"<?php echo ($pagination['per_page'] ?? 10) == 25 ? ' selected' : ''; ?>>25</option>
                                <option value="50"<?php echo ($pagination['per_page'] ?? 10) == 50 ? ' selected' : ''; ?>>50</option>
                                <option value="100"<?php echo ($pagination['per_page'] ?? 10) == 100 ? ' selected' : ''; ?>>100</option>
                                <option value="200"<?php echo ($pagination['per_page'] ?? 10) == 200 ? ' selected' : ''; ?>>200</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-outline-primary flex-fill">
                                    <i class="fas fa-search me-1"></i>Search
                                </button>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <a href="<?php echo APP_URL; ?>/call-center/create" class="btn btn-primary flex-fill">
                                    <i class="fas fa-plus me-1"></i>Add Call Center
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
                
                <?php if (empty($callCenters)): ?>
                    <div class="text-center py-5">
                        <i class="fab fa-whatsapp fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No call center entries found</h5>
                        <p class="text-muted">Start by adding your first call center entry.</p>
                        <a href="<?php echo APP_URL; ?>/call-center/create" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>Add First Entry
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
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="sortable-call-center">
                                <?php foreach ($callCenters as $callCenter): ?>
                                    <tr draggable="true" data-id="<?php echo $callCenter['id']; ?>" class="draggable-row">
                                        <td>
                                            <i class="fas fa-grip-vertical text-muted drag-handle" style="cursor: grab;"></i>
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
                                            <div class="d-flex gap-1" style="min-width: 80px;">
                                                <a href="<?php echo APP_URL; ?>/call-center/<?php echo $callCenter['id']; ?>" class="btn btn-outline-primary btn-sm" style="min-width: 32px; padding: 0.25rem 0.5rem;" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?php echo APP_URL; ?>/call-center/<?php echo $callCenter['id']; ?>/edit" class="btn btn-outline-warning btn-sm" style="min-width: 32px; padding: 0.25rem 0.5rem;" title="Edit Data">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-danger btn-sm" style="min-width: 32px; padding: 0.25rem 0.5rem;" onclick="deleteCallCenter(<?php echo $callCenter['id']; ?>)" title="Hapus Data">
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
                                            <a class="page-link" href="<?php echo APP_URL; ?>/call-center?page=<?php echo $pagination['current_page'] - 1; ?>&<?php echo $queryString; ?>">Previous</a>
                                        </li>
                                    <?php endif; ?>

                                    <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                                        <?php $activeClass = $i == $pagination['current_page'] ? ' active' : ''; ?>
                                        <li class="page-item<?php echo $activeClass; ?>">
                                            <a class="page-link" href="<?php echo APP_URL; ?>/call-center?page=<?php echo $i; ?>&<?php echo $queryString; ?>"><?php echo $i; ?></a>
                                        </li>
                                    <?php endfor; ?>

                                    <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="<?php echo APP_URL; ?>/call-center?page=<?php echo $pagination['current_page'] + 1; ?>&<?php echo $queryString; ?>">Next</a>
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

<style>
/* Drag and Drop Styling */
.draggable-row {
    transition: all 0.3s ease;
    cursor: move;
}

.draggable-row:hover {
    background-color: rgba(0, 123, 255, 0.05);
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.draggable-row.dragging {
    opacity: 0.6;
    transform: rotate(2deg);
    background-color: rgba(0, 123, 255, 0.1);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.draggable-row.drag-over {
    border-top: 3px solid #007bff;
    background-color: rgba(0, 123, 255, 0.1);
}

.drag-handle {
    transition: all 0.2s ease;
    opacity: 0.6;
}

.drag-handle:hover {
    color: #007bff !important;
    opacity: 1;
    transform: scale(1.1);
}

.draggable-row:hover .drag-handle {
    opacity: 1;
}

/* Visual feedback saat dragging */
.draggable-row.dragging .drag-handle {
    color: #007bff !important;
    transform: scale(1.2);
}

/* Smooth transitions untuk semua elemen dalam row */
.draggable-row td {
    transition: all 0.3s ease;
}

/* Disable text selection saat dragging */
.draggable-row.dragging {
    user-select: none;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
}

/* Hover effect untuk action buttons saat dragging */
.draggable-row.dragging .btn {
    opacity: 0.7;
    pointer-events: none;
}

/* Loading state saat update sort order */
.draggable-row.updating {
    opacity: 0.5;
    pointer-events: none;
}

.draggable-row.updating::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 2px solid #007bff;
    border-radius: 50%;
    border-top-color: transparent;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

/* Dark theme support */
[data-bs-theme="dark"] .draggable-row:hover {
    background-color: rgba(0, 123, 255, 0.1);
}

[data-bs-theme="dark"] .draggable-row.dragging {
    background-color: rgba(0, 123, 255, 0.2);
}

[data-bs-theme="dark"] .draggable-row.drag-over {
    background-color: rgba(0, 123, 255, 0.15);
}

/* Toast Notifications */
.toast-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    padding: 12px 20px;
    border-radius: 8px;
    color: white;
    font-weight: 500;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transform: translateX(100%);
    transition: transform 0.3s ease;
    max-width: 350px;
    word-wrap: break-word;
}

.toast-notification.show {
    transform: translateX(0);
}

.toast-success {
    background: linear-gradient(135deg, #28a745, #20c997);
}

.toast-error {
    background: linear-gradient(135deg, #dc3545, #e74c3c);
}

.toast-notification i {
    font-size: 16px;
}

/* Dark theme support for toasts */
[data-bs-theme="dark"] .toast-notification {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}
</style>

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
            form.action = `<?php echo APP_URL; ?>/call-center/${deleteCallCenterId}/delete`;
            
            const tokenInput = document.createElement('input');
            tokenInput.type = 'hidden';
            tokenInput.name = '_token';
            tokenInput.value = '<?php echo Session::generateCSRF(); ?>';
            
            form.appendChild(tokenInput);
            document.body.appendChild(form);
            form.submit();
        }
    });

    // Initialize Drag and Drop functionality
    initializeDragAndDrop();
});

// Drag and Drop functionality
function initializeDragAndDrop() {
    const sortableTable = document.getElementById('sortable-call-center');
    if (!sortableTable) return;

    let draggedElement = null;
    let draggedIndex = null;

    // Add drag event listeners to all rows
    document.querySelectorAll('.draggable-row').forEach(row => {
        row.addEventListener('dragstart', handleDragStart);
        row.addEventListener('dragend', handleDragEnd);
        row.addEventListener('dragover', handleDragOver);
        row.addEventListener('drop', handleDrop);
        row.addEventListener('dragenter', handleDragEnter);
        row.addEventListener('dragleave', handleDragLeave);
    });

    function handleDragStart(e) {
        draggedElement = this;
        draggedIndex = Array.from(this.parentNode.children).indexOf(this);
        this.classList.add('dragging');
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/html', this.outerHTML);
        
        // Add visual feedback
        this.style.cursor = 'grabbing';
    }

    function handleDragEnd(e) {
        this.classList.remove('dragging');
        this.style.cursor = 'move';
        document.querySelectorAll('.draggable-row').forEach(row => {
            row.classList.remove('drag-over');
        });
        draggedElement = null;
        draggedIndex = null;
    }

    function handleDragOver(e) {
        if (e.preventDefault) {
            e.preventDefault();
        }
        e.dataTransfer.dropEffect = 'move';
        return false;
    }

    function handleDragEnter(e) {
        if (this !== draggedElement) {
            this.classList.add('drag-over');
        }
    }

    function handleDragLeave(e) {
        // Only remove if we're actually leaving the element
        if (!this.contains(e.relatedTarget)) {
            this.classList.remove('drag-over');
        }
    }

    function handleDrop(e) {
        if (e.stopPropagation) {
            e.stopPropagation();
        }

        if (draggedElement !== this) {
            const dropIndex = Array.from(this.parentNode.children).indexOf(this);
            
            // Move DOM element
            if (draggedIndex < dropIndex) {
                this.parentNode.insertBefore(draggedElement, this.nextSibling);
            } else {
                this.parentNode.insertBefore(draggedElement, this);
            }
            
            // Update sort order in database
            updateSortOrder();
        }

        this.classList.remove('drag-over');
        return false;
    }

    function updateSortOrder() {
        const rows = document.querySelectorAll('.draggable-row');
        const orders = Array.from(rows).map((row, index) => ({
            id: parseInt(row.dataset.id),
            sort_order: index + 1
        }));

        // Show loading state
        rows.forEach(row => {
            row.classList.add('updating');
        });

        fetch('<?php echo APP_URL; ?>/call-center/update-sort', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-Token': '<?php echo Session::generateCSRF(); ?>'
            },
            body: JSON.stringify({ orders: orders })
        })
        .then(response => response.json())
        .then(data => {
            // Remove loading state
            rows.forEach(row => {
                row.classList.remove('updating');
            });

            if (data.success) {
                showSuccessMessage('Urutan berhasil diperbarui!');
            } else {
                showErrorMessage(data.error || 'Gagal memperbarui urutan');
                // Reload page to reset order
                setTimeout(() => {
                    location.reload();
                }, 2000);
            }
        })
        .catch(error => {
            // Remove loading state
            rows.forEach(row => {
                row.classList.remove('updating');
            });
            
            showErrorMessage('Terjadi kesalahan saat memperbarui urutan');
            // Reload page to reset order
            setTimeout(() => {
                location.reload();
            }, 2000);
        });
    }

    function showSuccessMessage(message) {
        // Create toast notification
        const toast = document.createElement('div');
        toast.className = 'toast-notification toast-success';
        toast.innerHTML = `
            <i class="fas fa-check-circle me-2"></i>
            ${message}
        `;
        document.body.appendChild(toast);
        
        // Show toast
        setTimeout(() => toast.classList.add('show'), 100);
        
        // Remove toast after 3 seconds
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => document.body.removeChild(toast), 300);
        }, 3000);
    }

    function showErrorMessage(message) {
        // Create toast notification
        const toast = document.createElement('div');
        toast.className = 'toast-notification toast-error';
        toast.innerHTML = `
            <i class="fas fa-exclamation-circle me-2"></i>
            ${message}
        `;
        document.body.appendChild(toast);
        
        // Show toast
        setTimeout(() => toast.classList.add('show'), 100);
        
        // Remove toast after 5 seconds
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => document.body.removeChild(toast), 300);
        }, 5000);
    }
}
</script>


