<?php
/**
 * Icon Picker Component
 * Modern icon picker with visual preview
 */

function renderIconPicker($selectedValue = '', $inputName = 'logo', $inputId = 'logo', $iconsData = null) {
    // Use icons from controller - this variable is passed from ModuleController
    global $available_icons;
    $icons = $iconsData ?? $available_icons ?? [
        'General' => [
            'fas fa-home' => 'Home',
            'fas fa-dashboard' => 'Dashboard',
            'fas fa-cog' => 'Settings',
            'fas fa-user' => 'User',
            'fas fa-users' => 'Users',
            'fas fa-puzzle-piece' => 'Modules',
            'fas fa-envelope' => 'Messages',
            'fas fa-phone' => 'Call Center',
            'fas fa-chart-bar' => 'Analytics',
            'fas fa-key' => 'Password',
            'fas fa-globe' => 'Globe',
            'fas fa-calendar' => 'Calendar',
            'fas fa-clock' => 'Clock',
            'fas fa-map-marker' => 'Location',
            'fas fa-star' => 'Star',
            'fas fa-heart' => 'Heart',
            'fas fa-thumbs-up' => 'Thumbs Up',
            'fas fa-thumbs-down' => 'Thumbs Down',
        ]
    ];
    ?>
    
    <div class="icon-picker-container">
        <!-- Hidden input for form submission -->
        <input type="hidden" id="<?php echo $inputId; ?>" name="<?php echo $inputName; ?>" value="<?php echo htmlspecialchars($selectedValue); ?>" required>
        
        <!-- Modern selected icon display with integrated search -->
        <div class="selected-icon-display mb-4">
            <div class="selected-icon-card">
                <div class="selected-icon-preview">
                    <div class="icon-preview-section">
                        <div class="icon-preview-circle">
                            <i id="selectedIconPreview" class="<?php echo htmlspecialchars($selectedValue ?: 'fas fa-home'); ?>"></i>
                        </div>
                    </div>
                    
                    <!-- Search box and category dropdown positioned to the right -->
                    <div class="search-section">
                        <div class="search-box">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" class="form-control search-input" id="iconSearch" placeholder="Search icons...">
                            <button type="button" class="search-clear d-none" id="clearSearch">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        
                        <!-- Category dropdown positioned next to search -->
                        <div class="category-dropdown">
                            <select class="form-select category-select" id="categorySelect">
                                <!-- All Icons Option -->
                                <option value="all-icons" selected data-target="#all-icons">
                                    <?php 
                                    // Calculate total icons count
                                    $totalIcons = 0;
                                    foreach ($icons as $category => $categoryIcons) {
                                        $totalIcons += count($categoryIcons);
                                    }
                                    ?>
                                    <i class="fas fa-th-large"></i> Semua Icon (<?php echo $totalIcons; ?> icons)
                                </option>
                                
                                <!-- Category Options -->
                                <?php foreach ($icons as $category => $categoryIcons): ?>
                                    <option value="<?php echo strtolower(str_replace([' ', '&'], ['-', ''], $category)); ?>" 
                                            data-target="#<?php echo strtolower(str_replace([' ', '&'], ['-', ''], $category)); ?>">
                                        <?php
                                        // Set icon for each category
                                        $categoryIcons_map = [
                                            'General' => 'fas fa-star',
                                            'Business & Finance' => 'fas fa-briefcase',
                                            'Communication & Social' => 'fas fa-comments',
                                            'Files & Documents' => 'fas fa-file',
                                            'Interface & Controls' => 'fas fa-sliders-h',
                                            'Navigation & Arrows' => 'fas fa-arrows-alt',
                                            'Status & Alerts' => 'fas fa-exclamation-triangle',
                                            'Technology & Devices' => 'fas fa-laptop',
                                            'Transportation & Travel' => 'fas fa-car',
                                            'Food & Health' => 'fas fa-utensils',
                                            'Sports & Recreation' => 'fas fa-futbol',
                                            'Weather & Nature' => 'fas fa-sun',
                                            'Education & Learning' => 'fas fa-graduation-cap'
                                        ];
                                        $icon = $categoryIcons_map[$category] ?? 'fas fa-folder';
                                        ?>
                                        <?php echo htmlspecialchars($category); ?> (<?php echo count($categoryIcons); ?> icons)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            
        <!-- Modern icon grid -->
        <div class="icon-grid-container">
            <div class="tab-content" id="iconTabContent">
                <!-- All Icons Tab - Shows all icons from all categories -->
                <div class="tab-pane fade show active" id="all-icons" role="tabpanel">
                    <div class="icon-grid">
                        <?php foreach ($icons as $category => $categoryIcons): ?>
                            <?php foreach ($categoryIcons as $value => $label): ?>
                                <div class="icon-item <?php echo ($selectedValue === $value) ? 'selected' : ''; ?>" 
                                     data-icon="<?php echo htmlspecialchars($value); ?>" 
                                     data-label="<?php echo htmlspecialchars($label); ?>"
                                     data-category="<?php echo htmlspecialchars($category); ?>"
                                     title="<?php echo htmlspecialchars($label); ?> (<?php echo htmlspecialchars($category); ?>)">
                                    <div class="icon-item-inner">
                                        <div class="icon-wrapper">
                                            <i class="<?php echo htmlspecialchars($value); ?>"></i>
                                        </div>
                                        <div class="icon-label"><?php echo htmlspecialchars($label); ?></div>
                                        <div class="icon-category"><?php echo htmlspecialchars($category); ?></div>
                                    </div>
                                    <div class="selection-indicator">
                                        <i class="fas fa-check"></i>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Category Tabs -->
                <?php foreach ($icons as $category => $categoryIcons): ?>
                    <div class="tab-pane fade" id="<?php echo strtolower(str_replace([' ', '&'], ['-', ''], $category)); ?>" role="tabpanel">
                        <div class="icon-grid">
                            <?php foreach ($categoryIcons as $value => $label): ?>
                                <div class="icon-item <?php echo ($selectedValue === $value) ? 'selected' : ''; ?>" 
                                     data-icon="<?php echo htmlspecialchars($value); ?>" 
                                     data-label="<?php echo htmlspecialchars($label); ?>"
                                     data-category="<?php echo htmlspecialchars($category); ?>"
                                     title="<?php echo htmlspecialchars($label); ?>">
                                    <div class="icon-item-inner">
                                        <div class="icon-wrapper">
                                            <i class="<?php echo htmlspecialchars($value); ?>"></i>
                                        </div>
                                        <div class="icon-label"><?php echo htmlspecialchars($label); ?></div>
                                    </div>
                                    <div class="selection-indicator">
                                        <i class="fas fa-check"></i>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    
    <style>
    /* Modern Icon Picker Styles */
    .icon-picker-container {
        max-height: 500px;
        overflow-y: auto;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-radius: 12px;
        padding: 10px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }
    
    /* Selected Icon Display */
    .selected-icon-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        padding: 20px;
        color: white;
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
    }
    
    .selected-icon-header {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
        font-size: 14px;
        opacity: 0.9;
    }
    
    .selected-icon-preview {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .icon-preview-section {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 auto;
        min-width: 80px;
    }
    
    .search-section {
        flex: 1;
        min-width: 250px;
        max-width: none;
        display: flex;
        gap: 10px;
        align-items: center;
    }
    
    .search-box {
        flex: 1;
        position: relative;
    }
    
    .category-dropdown {
        flex: 0 0 auto;
        min-width: 200px;
        max-width: 250px;
    }
    
    .icon-preview-circle {
        width: 60px;
        height: 60px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255,255,255,0.3);
    }
    
    .icon-preview-circle i {
        font-size: 24px;
        color: white;
    }
    
    
    /* Search Container - Side by side layout */
    .search-section {
        position: relative;
    }
    
    .search-box {
        position: relative;
        display: flex;
        align-items: center;
    }
    
    .search-icon {
        position: absolute;
        right: 15px;
        color: rgba(0,0,0,0.6);
        z-index: 2;
    }
    
    .search-input {
        padding-left: 15px;
        padding-right: 45px;
        border: 2px solid rgba(255,255,255,0.3);
        border-radius: 25px;
        height: 40px;
        font-size: 13px;
        transition: all 0.3s ease;
        background: rgba(255,255,255,0.15);
        color: white;
        backdrop-filter: blur(10px);
    }
    
    .search-input::placeholder {
        color: rgba(255,255,255,0.7);
    }
    
    .search-input:focus {
        border-color: rgba(255,255,255,0.6);
        box-shadow: 0 0 0 3px rgba(255,255,255,0.1);
        outline: none;
        background: rgba(255,255,255,0.25);
    }
    
    .search-clear {
        position: absolute;
        right: 15px;
        background: none;
        border: none;
        color: rgba(255,255,255,0.8);
        cursor: pointer;
        z-index: 2;
        padding: 5px;
        border-radius: 50%;
        transition: all 0.2s ease;
    }
    
    .search-clear:hover {
        background: rgba(255,255,255,0.2);
        color: #ff6b6b;
    }
    
    
    /* Category Dropdown Selector */
    .category-select {
        border: 2px solid rgba(255,255,255,0.3);
        border-radius: 25px;
        padding: 10px 15px;
        font-size: 13px;
        font-weight: 500;
        color: white;
        background: rgba(255,255,255,0.15);
        transition: all 0.3s ease;
        cursor: pointer;
        backdrop-filter: blur(10px);
        height: 40px;
    }
    
    .category-select option {
        background: #667eea;
        color: white;
        padding: 8px;
    }
    
    .category-select:focus {
        border-color: rgba(255,255,255,0.6);
        box-shadow: 0 0 0 3px rgba(255,255,255,0.1);
        outline: none;
        background: rgba(255,255,255,0.25);
    }
    
    .category-select:hover {
        border-color: rgba(255,255,255,0.6);
        background: rgba(255,255,255,0.25);
    }
    
    .form-label {
        color: #495057;
        font-size: 14px;
        margin-bottom: 8px;
    }
    
    .form-label i {
        color: #667eea;
    }
    
    /* Icon Grid */
    .icon-grid-container {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .icon-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 15px;
        padding: 10px 0;
    }
    
    .icon-item {
        position: relative;
        background: white;
        border: 2px solid #f1f3f4;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    
    .icon-item:hover {
        border-color: #667eea;
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.15);
    }
    
    .icon-item.selected {
        border-color: #667eea;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
    }
    
    .icon-item-inner {
        padding: 20px 15px;
        text-align: center;
        position: relative;
        z-index: 1;
    }
    
    .icon-wrapper {
        width: 40px;
        height: 40px;
        margin: 0 auto 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8f9fa;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    
    .icon-item:hover .icon-wrapper {
        background: #e3f2fd;
        transform: scale(1.1);
    }
    
    .icon-item.selected .icon-wrapper {
        background: rgba(255,255,255,0.2);
    }
    
    .icon-wrapper i {
        font-size:20px;
        color: #6c757d;
        transition: all 0.3s ease;
    }
    
    .icon-item:hover .icon-wrapper i {
        color: #667eea;
        transform: scale(1.1);
    }
    
    .icon-item.selected .icon-wrapper i {
        color: white;
    }
    
    .icon-label {
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 5px;
        color: #495057;
        line-height: 1.2;
    }
    
    .icon-item.selected .icon-label {
        color: white;
    }
    
    .icon-category {
        font-size: 10px;
        font-weight: 500;
        color: #6c757d;
        margin-top: 2px;
        opacity: 0.8;
        line-height: 1.1;
    }
    
    .icon-item.selected .icon-category {
        color: rgba(255,255,255,0.8);
    }
    
    
    .selection-indicator {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 20px;
        height: 20px;
        background: #28a745;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transform: scale(0);
        transition: all 0.3s ease;
    }
    
    .icon-item.selected .selection-indicator {
        opacity: 1;
        transform: scale(1);
    }
    
    .selection-indicator i {
        font-size: 10px;
        color: white;
    }
    
    /* Responsive Design */
    @media (max-width: 992px) {
        .search-section {
            min-width: 200px;
        }
        
        .icon-preview-section {
            min-width: 60px;
        }
    }
    
    @media (max-width: 768px) {
        .selected-icon-preview {
            flex-direction: column;
            align-items: stretch;
            gap: 15px;
        }
        
        .icon-preview-section {
            justify-content: center;
            min-width: auto;
        }
        
        .search-section {
            flex-direction: column;
            gap: 10px;
            width: 100%;
            min-width: auto;
        }
        
        .search-box {
            width: 100%;
        }
        
        .category-dropdown {
            width: 100%;
            min-width: auto;
            max-width: none;
        }
        
        .icon-grid {
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 10px;
        }
        
        .icon-item-inner {
            padding: 15px 10px;
        }
        
        .icon-wrapper {
            width: 35px;
            height: 35px;
        }
        
        .icon-wrapper i {
            font-size: 16px;
        }
        
        .category-tabs {
            justify-content: center;
        }
        
        .category-select {
            font-size: 13px;
            padding: 10px 12px;
        }
    }
    
    @media (max-width: 480px) {
        .selected-icon-preview {
            gap: 10px;
        }
        
        .icon-preview-section {
            gap: 10px;
        }
        
        .icon-preview-circle {
            width: 50px;
            height: 50px;
        }
        
        .icon-preview-circle i {
            font-size: 20px;
        }
        
        
        
        .search-input {
            height: 35px;
            font-size: 12px;
        }
        
        .icon-grid {
            grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
        }
        
        .icon-item-inner {
            padding: 12px 8px;
        }
        
        .icon-label {
            font-size: 11px;
        }
        
    }
    </style>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const iconItems = document.querySelectorAll('.icon-item');
        const hiddenInput = document.getElementById('<?php echo $inputId; ?>');
        const selectedIconPreview = document.getElementById('selectedIconPreview');
        const searchInput = document.getElementById('iconSearch');
        const clearSearchBtn = document.getElementById('clearSearch');
        
        // Icon selection with enhanced feedback
        iconItems.forEach(item => {
            item.addEventListener('click', function() {
                // Remove selected class from all items
                iconItems.forEach(i => i.classList.remove('selected'));
                
                // Add selected class to clicked item
                this.classList.add('selected');
                
                // Update hidden input
                const iconClass = this.dataset.icon;
                const iconLabel = this.dataset.label;
                hiddenInput.value = iconClass;
                
                // Update preview with animation
                selectedIconPreview.className = iconClass;
                
                // Add selection animation
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
                
                // Trigger change event for form validation
                hiddenInput.dispatchEvent(new Event('change'));
            });
        });
        
        // Enhanced search functionality
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const searchTerm = this.value.toLowerCase();
                let visibleCount = 0;
                
                // Get active tab pane
                const activePane = document.querySelector('.tab-pane.show.active');
                const activeCategoryItems = activePane ? activePane.querySelectorAll('.icon-item') : iconItems;
                
                activeCategoryItems.forEach(item => {
                    const iconLabel = item.dataset.label.toLowerCase();
                    const iconClass = item.dataset.icon.toLowerCase();
                    const iconCategory = item.dataset.category ? item.dataset.category.toLowerCase() : '';
                    
                    if (iconLabel.includes(searchTerm) || iconClass.includes(searchTerm) || iconCategory.includes(searchTerm)) {
                        item.style.display = '';
                        visibleCount++;
                    } else {
                        item.style.display = 'none';
                    }
                });
                
                // Update clear button visibility
                if (searchTerm) {
                    clearSearchBtn.style.display = 'block';
                } else {
                    clearSearchBtn.style.display = 'none';
                }
            }, 300);
        });
        
        
        // Clear search functionality
        clearSearchBtn.addEventListener('click', function() {
            searchInput.value = '';
            searchInput.focus();
            
            // Get active tab pane and show all icons in that category
            const activePane = document.querySelector('.tab-pane.show.active');
            const activeCategoryItems = activePane ? activePane.querySelectorAll('.icon-item') : iconItems;
            
            activeCategoryItems.forEach(item => {
                item.style.display = '';
            });
            
            this.style.display = 'none';
        });
        
        // Category dropdown functionality
        const categorySelect = document.getElementById('categorySelect');
        const tabPanes = document.querySelectorAll('.tab-pane');
        
        categorySelect.addEventListener('change', function() {
            const selectedValue = this.value;
            const selectedOption = this.options[this.selectedIndex];
            const targetId = selectedOption.getAttribute('data-target');
            
            // Hide all tab panes
            tabPanes.forEach(pane => {
                pane.classList.remove('show', 'active');
            });
            
            // Show selected tab pane
            const targetPane = document.querySelector(targetId);
            if (targetPane) {
                targetPane.classList.add('show', 'active');
            }
            
            // Clear search when switching categories
            searchInput.value = '';
            clearSearchBtn.style.display = 'none';
            
            // Show all icons in the new category
            const newCategoryItems = targetPane ? targetPane.querySelectorAll('.icon-item') : [];
            newCategoryItems.forEach(item => {
                item.style.display = '';
            });
            
            // Add selection animation
            this.style.transform = 'scale(0.98)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
        
        // Initialize with selected icon if any
        if (hiddenInput.value) {
            const selectedItem = document.querySelector(`[data-icon="${hiddenInput.value}"]`);
            if (selectedItem) {
                selectedItem.classList.add('selected');
            }
        }
    });
    </script>
    <?php
}
?>
