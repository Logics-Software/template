<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <h5 class="mb-0">
                        Tulis Pesan Baru
                    </h5>
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="<?php echo APP_URL; ?>/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo APP_URL; ?>/messages">Pesan Masuk</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tulis Pesan Baru</li>
                    </ol>
                </nav>
            </div>
            
            <div class="card-body">
                <form id="messageForm" method="POST" action="<?php echo APP_URL; ?>/messages">
                    <input type="hidden" name="_token" value="<?php echo Session::generateCSRF(); ?>">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="subject" name="subject" 
                                        placeholder="Subjek" 
                                        value="<?php 
                                            if (isset($reply_data) && $reply_data) {
                                                echo 'Reply: ' . htmlspecialchars($reply_data['subject']);
                                            } elseif (isset($forward_data) && $forward_data) {
                                                echo 'Forward: ' . htmlspecialchars($forward_data['subject']);
                                            }
                                        ?>" 
                                        required>
                                <label for="subject">Subjek <span class="text-danger">*</span></label>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Penerima <span class="text-danger">*</span></label>
                                
                                <!-- Search and Filter Controls -->
                                <div class="row mb-3">
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-search"></i>
                                            </span>
                                            <input type="text" class="form-control" id="userSearch" placeholder="Cari berdasarkan nama, username, atau email...">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-select" id="roleFilter">
                                            <option value="">Semua Role</option>
                                            <option value="admin">Admin</option>
                                            <option value="manajemen">Manajemen</option>
                                            <option value="user">User</option>
                                            <option value="marketing">Marketing</option>
                                            <option value="customer">Customer</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="btn-group d-flex justify-content-end" role="group">
                                            <button type="button" class="btn btn-outline-primary btn-sm" id="selectAllBtn">
                                                <i class="fas fa-check-double me-1"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger btn-sm" id="clearAllBtn">
                                                <i class="fas fa-times me-1"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Users List -->
                                <div class="border rounded max-h-300 overflow-y-auto">
                                    <div id="usersList">
                                        <div class="p-3 text-center">
                                            <div class="spinner-border spinner-border-sm" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            <span class="ms-2">Memuat daftar pengguna...</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Selected Recipients -->
                                <div class="mt-2">
                                    <small class="text-muted">Penerima terpilih:</small>
                                    <div id="selectedRecipientsList" class="mt-1">
                                        <span class="text-muted">Belum ada penerima yang dipilih</span>
                                    </div>
                                </div>
                                
                                <!-- Hidden input for form submission -->
                                <input type="hidden" id="selectedRecipients" name="recipients[]" value="">
                            </div>
                            
                            <div class="mb-3">
                                <label for="content" class="form-label">Isi Pesan <span class="text-danger">*</span></label>
                                <div id="quill-editor" class="quill-editor"></div>
                                <textarea id="content" name="content" class="d-none" required></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="attachments" class="form-label">Lampiran</label>
                                <input type="file" class="form-control" id="attachments" name="attachments[]" multiple accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png,.gif">
                                <div class="form-text">Maksimal 5MB per file. Format yang didukung: PDF, DOC, DOCX, TXT, JPG, PNG, GIF</div>
                            </div>
                            
                            <?php if (isset($forward_data) && $forward_data && !empty($forward_data['attachments'])): ?>
                            <div class="mb-3">
                                <label class="form-label">Lampiran dari Pesan Asli</label>
                                <div class="card">
                                    <div class="card-body">
                                        <small class="text-muted mb-2 d-block">Lampiran berikut akan ikut diteruskan:</small>
                                        <?php foreach ($forward_data['attachments'] as $attachment): ?>
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-file me-2 text-primary"></i>
                                            <div class="flex-grow-1">
                                                <div class="fw-bold"><?php echo htmlspecialchars($attachment['original_name']); ?></div>
                                                <small class="text-muted">
                                                    <?php echo number_format($attachment['file_size'] / 1024, 1); ?> KB
                                                </small>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Form Footer -->
            <div class="card-footer d-flex justify-content-between align-items-center">
                <a href="<?php echo APP_URL; ?>/messages" class="btn btn-secondary">
                    <i class="fas fa-times me-1"></i>Batal
                </a>
                <div>
                    <button type="submit" form="messageForm" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-1"></i>Kirim Pesan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quill JS Editor -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

<!-- User selection styles moved to complete.css -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Quill Editor
    const quill = new Quill('#quill-editor', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'indent': '-1'}, { 'indent': '+1' }],
                [{ 'align': [] }],
                ['link', 'image'],
                ['clean']
            ]
        },
        placeholder: 'Tulis pesan Anda di sini...'
    });
    
    // Make quill globally available
    window.quill = quill;
    
    // Update hidden textarea when content changes
    quill.on('text-change', function() {
        document.getElementById('content').value = quill.root.innerHTML;
    });
    
    // Auto-select recipient for reply
    <?php if (isset($reply_data) && $reply_data): ?>
    const replySenderId = <?php echo $reply_data['reply_sender']['id']; ?>;
    const replySenderName = "<?php echo htmlspecialchars($reply_data['reply_sender']['name']); ?>";
    const replySenderEmail = "<?php echo htmlspecialchars($reply_data['reply_sender']['email']); ?>";
    
    // Auto-select the sender as recipient
    setTimeout(() => {
        if (replySenderId) {
            // Find and select the sender in the user list
            const userCard = document.querySelector(`[data-user-id="${replySenderId}"]`);
            if (userCard) {
                const checkbox = userCard.querySelector('input[type="checkbox"]');
                if (checkbox && !checkbox.checked) {
                    checkbox.checked = true;
                    toggleUser(replySenderId);
                }
            }
        }
    }, 1000); // Wait for users to load
    <?php endif; ?>
    
    // Auto-fill content for forward
    <?php if (isset($forward_data) && $forward_data): ?>
    const forwardSenderId = <?php echo $forward_data['forward_sender']['id']; ?>;
    const forwardSenderName = "<?php echo htmlspecialchars($forward_data['forward_sender']['name']); ?>";
    const forwardSenderEmail = "<?php echo htmlspecialchars($forward_data['forward_sender']['email']); ?>";
    const forwardContent = `<?php echo addslashes($forward_data['content']); ?>`;
    const forwardSubject = "<?php echo htmlspecialchars($forward_data['subject']); ?>";
    const forwardDate = "<?php echo date('d F Y, H:i', strtotime($forward_data['created_at'])); ?>";
    
    // No auto-select for forward - user chooses recipients manually
    
    // Fill content with forwarded message
    setTimeout(() => {
        // Ensure Quill is fully initialized
        if (window.quill && !window.quill.isReady) {
            setTimeout(() => {
                fillForwardContent();
            }, 500);
            return;
        }
        
        fillForwardContent();
    }, 1500); // Wait for Quill to initialize
    
    function fillForwardContent() {
        const forwardMessage = `
            <div class="notice-box">
                <div class="notice-box-header">
                    <strong>Diteruskan dari:</strong> ${forwardSenderName} (${forwardSenderEmail})<br>
                    <strong>Tanggal:</strong> ${forwardDate}<br>
                    <strong>Subjek:</strong> ${forwardSubject}
                </div>
                <div class="notice-box-footer">
                    ${forwardContent}
                </div>
            </div>
        `;
        
        // Try multiple methods to fill content
        if (window.quill) {
            // Method 1: Use Quill's setContents method (recommended)
            try {
                // Parse HTML to Delta format
                const delta = window.quill.clipboard.convert(forwardMessage);
                window.quill.setContents(delta);
                document.getElementById('content').value = forwardMessage;
            } catch (error) {
                // Method 2: Use pasteHTML method
                try {
                    window.quill.clipboard.dangerouslyPasteHTML(forwardMessage);
                    document.getElementById('content').value = forwardMessage;
                } catch (error2) {
                    // Method 3: Direct innerHTML (final fallback)
                    window.quill.root.innerHTML = forwardMessage;
                    document.getElementById('content').value = forwardMessage;
                }
            }
        } else {
            document.getElementById('content').value = forwardMessage;
        }
    }
    
    // Additional fallback with longer timeout
    setTimeout(() => {
        if (window.quill && window.quill.root.innerHTML.trim() === '') {
            fillForwardContent();
        }
    }, 3000);
    <?php endif; ?>
    const userSearch = document.getElementById('userSearch');
    const roleFilter = document.getElementById('roleFilter');
    const usersList = document.getElementById('usersList');
    const selectedRecipientsList = document.getElementById('selectedRecipientsList');
    const selectedRecipientsInput = document.getElementById('selectedRecipients');
    
    let selectedUsers = [];
    let allUsers = [];
    
    // Load users on page load
    loadUsers();
    
    // Search functionality
    userSearch.addEventListener('input', function() {
        debounceSearch();
        // Update button states after search
        setTimeout(() => {
            updateBulkSelectButtons();
        }, 400);
    });
    
    // Filter functionality
    roleFilter.addEventListener('change', function() {
        debounceSearch();
        // Update button states after filter
        setTimeout(() => {
            updateBulkSelectButtons();
        }, 400);
    });
    
    // Bulk select functionality
    const selectAllBtn = document.getElementById('selectAllBtn');
    const clearAllBtn = document.getElementById('clearAllBtn');
    
    selectAllBtn.addEventListener('click', function() {
        // Get all currently displayed users
        const displayedUsers = getCurrentDisplayedUsers();
        
        displayedUsers.forEach(user => {
            if (!selectedUsers.some(selected => selected.id == user.id)) {
                selectedUsers.push(user);
            }
        });
        
        updateSelectedRecipients();
        displayUsers(allUsers);
    });
    
    clearAllBtn.addEventListener('click', function() {
        selectedUsers = [];
        updateSelectedRecipients();
        displayUsers(allUsers);
    });
    
    // Debounce search to avoid too many requests
    let searchTimeout;
    function debounceSearch() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            loadUsers();
        }, 300);
    }
    
    // Load users from API
    function loadUsers() {
        const search = userSearch.value;
        const role = roleFilter.value;
        
        
        const params = new URLSearchParams();
        if (search) params.append('search', search);
        if (role) params.append('role', role);
        
        const url = `<?php echo APP_URL; ?>/api/messages/search-users?${params.toString()}`;
        
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('HTTP error! status: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    allUsers = data.users;
                    displayUsers(data.users);
                } else {
                    usersList.innerHTML = '<div class="p-3 text-center text-danger">Error: ' + data.message + '</div>';
                }
            })
            .catch(error => {
                usersList.innerHTML = '<div class="p-3 text-center text-danger">Error loading users: ' + error.message + '</div>';
            });
    }
    
    // Get currently displayed users (for bulk select)
    function getCurrentDisplayedUsers() {
        // Get users based on current search and filter
        let filteredUsers = allUsers;
        
        const search = userSearch.value.toLowerCase();
        const role = roleFilter.value;
        
        if (search) {
            filteredUsers = filteredUsers.filter(user => 
                user.namalengkap.toLowerCase().includes(search) ||
                user.username.toLowerCase().includes(search) ||
                user.email.toLowerCase().includes(search)
            );
        }
        
        if (role) {
            filteredUsers = filteredUsers.filter(user => user.role === role);
        }
        
        return filteredUsers;
    }
    
    
    // Update bulk select button states
    function updateBulkSelectButtons() {
        const displayedUsers = getCurrentDisplayedUsers();
        const selectedCount = displayedUsers.filter(user => 
            selectedUsers.some(selected => selected.id == user.id)
        ).length;
        
        // Update select all button
        if (selectedCount === displayedUsers.length && displayedUsers.length > 0) {
            selectAllBtn.innerHTML = '<i class="fas fa-check-double me-1"></i>';
            selectAllBtn.disabled = true;
        } else {
            selectAllBtn.innerHTML = '<i class="fas fa-check-double me-1"></i>';
            selectAllBtn.disabled = false;
        }
        
        // Update clear all button
        if (selectedUsers.length === 0) {
            clearAllBtn.innerHTML = '<i class="fas fa-times me-1"></i>';
            clearAllBtn.disabled = true;
        } else {
            clearAllBtn.innerHTML = `<i class="fas fa-times me-1"></i> (${selectedUsers.length})`;
            clearAllBtn.disabled = false;
        }
    }
    
    // Display users in the list
    function displayUsers(users) {
        
        if (users.length === 0) {
            usersList.innerHTML = '<div class="p-3 text-center text-muted">Tidak ada pengguna yang ditemukan</div>';
            return;
        }
        
        const usersHtml = users.map(user => {
            const isSelected = selectedUsers.some(selected => selected.id == user.id);
            const userPicture = user.picture ? 
                `<img src="<?php echo APP_URL; ?>/${user.picture}" alt="${user.namalengkap}" class="avatar-sm rounded-circle me-2 avatar-32">` :
                `<div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2 avatar-fallback">${user.namalengkap.charAt(0).toUpperCase()}</div>`;
            
            return `
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 mb-1">
                    <div class="card user-selection-item ${isSelected ? 'selected' : ''}" data-user-id="${user.id}" class="user-selection-item">
                        <div class="card-body p-1 h-100 d-flex align-items-center">
                            <input type="checkbox" class="form-check-input me-1" ${isSelected ? 'checked' : ''} onchange="toggleUser(${user.id})" class="scale-80">
                            ${userPicture}
                            <div class="flex-grow-1 flex-grow-1">
                                <div class="fw-bold text-truncate text-xs-leading-tight">${user.namalengkap}</div>
                                <small class="text-muted d-block text-truncate text-xxs">${user.username} / ${user.email} / ${user.role}</small>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }).join('');
        
        usersList.innerHTML = `<div class="row g-1">${usersHtml}</div>`;
        
        // Add click handlers to the new cards
        setTimeout(() => {
            addCardClickHandlers();
        }, 100);
        
        // Update bulk select button states
        updateBulkSelectButtons();
    }
    
    // Toggle user selection
    window.toggleUser = function(userId) {
        const user = allUsers.find(u => u.id == userId);
        if (!user) return;
        
        const existingIndex = selectedUsers.findIndex(u => u.id == user.id);
        if (existingIndex >= 0) {
            selectedUsers.splice(existingIndex, 1);
        } else {
            selectedUsers.push(user);
        }
        
        updateSelectedRecipients();
        displayUsers(allUsers);
        updateBulkSelectButtons();
    };
    
    // Add click functionality to user cards
    function addCardClickHandlers() {
        const userCards = document.querySelectorAll('.user-selection-item');
        
        userCards.forEach(card => {
            // Remove existing listeners to avoid duplicates
            card.removeEventListener('click', handleCardClick);
            card.addEventListener('click', handleCardClick);
        });
    }
    
    // Separate function for card click handling
    function handleCardClick(e) {
        
        // Don't trigger if clicking on checkbox
        if (e.target.type === 'checkbox') {
            return;
        }
        
        const userId = parseInt(this.dataset.userId);
        
        const checkbox = this.querySelector('input[type="checkbox"]');
        if (checkbox) {
            checkbox.checked = !checkbox.checked;
            toggleUser(userId);
        } else {
        }
    }
    
    // Update selected recipients display
    function updateSelectedRecipients() {
        if (selectedUsers.length === 0) {
            selectedRecipientsList.innerHTML = '<span class="text-muted">Belum ada penerima yang dipilih</span>';
            selectedRecipientsInput.value = '';
        } else {
            const recipientsHtml = selectedUsers.map(user => 
                `<span class="badge bg-primary me-1 mb-1">${user.namalengkap} <i class="fas fa-times ms-1" onclick="removeUser(${user.id})" class="cursor-pointer"></i></span>`
            ).join('');
            selectedRecipientsList.innerHTML = recipientsHtml;
            selectedRecipientsInput.value = selectedUsers.map(u => u.id).join(',');
        }
    }
    
    // Remove user from selection
    window.removeUser = function(userId) {
        selectedUsers = selectedUsers.filter(u => u.id != userId);
        updateSelectedRecipients();
        displayUsers(allUsers);
    };
    
    // Form submission
    document.getElementById('messageForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (selectedUsers.length === 0) {
            AlertManager.warning('Pilih minimal satu penerima');
            return;
        }
        
        const formData = new FormData(this);
        
        // Update content with Quill HTML
        let quillContent = '';
        try {
            if (window.quill && window.quill.root) {
                quillContent = window.quill.root.innerHTML;
            } else {
                quillContent = document.getElementById('content').value;
            }
        } catch (error) {
            quillContent = document.getElementById('content').value;
        }
        formData.set('content', quillContent);
        
        // Add recipients to form data
        formData.delete('recipients[]');
        selectedUsers.forEach(user => {
            formData.append('recipients[]', user.id);
        });
        
        // Handle attachments
        const attachmentFiles = document.getElementById('attachments').files;
        for (let i = 0; i < attachmentFiles.length; i++) {
            formData.append('attachments[]', attachmentFiles[i]);
        }
        
        // Show loading
        const submitBtn = this.querySelector('button[type="submit"]');
        if (submitBtn) {
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1" tabindex="-1"></i>Mengirim...';
            submitBtn.disabled = true;
        }
        
        fetch(this.action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Clear form and localStorage before redirect
                document.getElementById('messageForm').reset();
                localStorage.removeItem('message_draft');
                window.location.href = data.redirect || '<?php echo APP_URL; ?>/messages?sent=true';
            } else {
                AlertManager.error('Gagal mengirim pesan: ' + data.message);
            }
        })
        .catch(error => {
            AlertManager.error('Terjadi kesalahan saat mengirim pesan');
        })
        .finally(() => {
            if (submitBtn) {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        });
    });
});

</script>
