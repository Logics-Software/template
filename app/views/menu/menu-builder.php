<?php
$title = $title ?? 'Menu Builder';
$current_page = 'menu-builder';

// Generate CSRF token early for forms
$csrf_token = Session::generateCSRF();

// Start output buffering to capture content
ob_start();
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?php echo APP_URL; ?>/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo APP_URL; ?>/menu">Menu Management</a></li>
                        <li class="breadcrumb-item active">Menu Builder</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    Menu Builder
                    <?php if (isset($selected_group) && $selected_group): ?>
                        <small class="text-muted">- Adding items to "<?php echo htmlspecialchars($selected_group['name']); ?>"</small>
                    <?php endif; ?>
                </h4>
            </div>
        </div>
    </div>

    <?php if (isset($selected_group) && $selected_group): ?>
    <!-- Group Selection Alert -->
    <div class="row">
        <div class="col-12">
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Group Mode:</strong> You are adding menu items to the group "<strong><?php echo htmlspecialchars($selected_group['name']); ?></strong>". 
                All new menu items will be automatically assigned to this group.
                <a href="<?php echo APP_URL; ?>/menu/builder" class="btn btn-sm btn-outline-secondary ms-2">
                    <i class="fas fa-times"></i> Exit Group Mode
                </a>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="row">
        <!-- Menu Builder Panel -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Menu Structure</h5>
                    <div class="card-actions">
                        <?php if (!isset($selected_group_id) || !$selected_group_id): ?>
                        <button class="btn btn-sm btn-outline-primary" onclick="addGroup()">
                            <i class="fas fa-plus"></i> Add Group
                        </button>
                        <?php endif; ?>
                        <button class="btn btn-sm btn-outline-success" onclick="addMenuItem()">
                            <i class="fas fa-plus"></i> Add Menu Item
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="menuBuilder" class="menu-builder">
                        <div class="menu-items" id="sortableMenu">
                            <?php if (!empty($menuItems)): ?>
                                <?php 
                                // Group menu items by group_id and parent_id
                                $groupedMenuItems = [];
                                foreach ($menuItems as $item) {
                                    $groupId = $item['group_id'] ?? 'standalone';
                                    $parentId = $item['parent_id'] ?? 'standalone';
                                    if (!isset($groupedMenuItems[$groupId])) {
                                        $groupedMenuItems[$groupId] = [];
                                    }
                                    if (!isset($groupedMenuItems[$groupId][$parentId])) {
                                        $groupedMenuItems[$groupId][$parentId] = [];
                                    }
                                    $groupedMenuItems[$groupId][$parentId][] = $item;
                                }
                                
                                // Filter groups if in group mode
                                $groupsToShow = $groups;
                                if (isset($selected_group_id) && $selected_group_id) {
                                    $groupsToShow = array_filter($groups, function($group) use ($selected_group_id) {
                                        return $group['id'] == $selected_group_id;
                                    });
                                }
                                
                                // Render groups first
                                foreach ($groupsToShow as $group): 
                                    $groupItems = $groupedMenuItems[$group['id']] ?? [];
                                    $totalItems = array_sum(array_map('count', $groupItems));
                                ?>
                                    <div class="menu-group" data-group-id="<?php echo $group['id']; ?>">
                                        <div class="menu-group-header">
                                            <div class="menu-group-info">
                                                <i class="<?php echo $group['icon']; ?>"></i>
                                                <span><?php echo htmlspecialchars($group['name']); ?></span>
                                                <span class="badge bg-primary ms-2"><?php echo $totalItems; ?></span>
                                            </div>
                                            <div class="menu-group-actions">
                                                <button class="btn btn-sm btn-outline-primary" onclick="editGroup(<?php echo $group['id']; ?>)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" onclick="deleteGroup(<?php echo $group['id']; ?>)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-secondary drag-handle">
                                                    <i class="fas fa-grip-vertical"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="menu-group-content">
                                            <div class="menu-modules" data-group-id="<?php echo $group['id']; ?>">
                                                <?php 
                                                // Organize menu items into hierarchical structure
                                                $menuStructure = [];
                                                $menuMap = [];
                                                
                                                // Create map of all menu items
                                                    foreach ($menuItems as $item) {
                                                    if ($item['group_id'] == $group['id']) {
                                                        $menuMap[$item['id']] = $item;
                                                        $menuStructure[$item['id']] = array_merge($item, ['children' => []]);
                                                    }
                                                }
                                                
                                                // Build hierarchy
                                                $rootItems = [];
                                                foreach ($menuStructure as $id => $item) {
                                                    if (!empty($item['parent_id']) && isset($menuStructure[$item['parent_id']])) {
                                                        $menuStructure[$item['parent_id']]['children'][] = &$menuStructure[$id];
                                                    } else {
                                                        $rootItems[] = &$menuStructure[$id];
                                                    }
                                                }
                                                
                                                // Sort by sort_order
                                                function sortMenuItems(&$items) {
                                                    usort($items, function($a, $b) {
                                                        return ($a['sort_order'] ?? 0) - ($b['sort_order'] ?? 0);
                                                    });
                                                    foreach ($items as &$item) {
                                                        if (!empty($item['children'])) {
                                                            sortMenuItems($item['children']);
                                                        }
                                                    }
                                                }
                                                sortMenuItems($rootItems);
                                                
                                                // Render menu structure
                                                function renderMenuStructure($items, $level = 0) {
                                                    if (empty($items)) return '';
                                                    
                                                    $html = '';
                                                    foreach ($items as $item) {
                                                        $isActive = $item['is_active'] ? '' : 'menu-inactive';
                                                        $hasChildren = !empty($item['children']);
                                                        $parentClass = $hasChildren ? 'menu-parent-item' : '';
                                                        
                                                        $html .= '
                                                        <div class="menu-module ' . $isActive . ' ' . $parentClass . '" data-menu-item-id="' . $item['id'] . '">
                                                        <div class="menu-module-info">
                                                                <div class="menu-item-content">
                                                                    <div class="menu-item-main">

                                                                        ' . ($level > 0 ? 
                                                                            '<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><i class="' . ($item['icon'] ?? 'fas fa-circle') . ' me-2"></i><span class="menu-item-name">' . htmlspecialchars($item['name']) . '</span>' : 
                                                                            '<i class="' . ($item['icon'] ?? 'fas fa-circle') . ' me-2"></i><span class="menu-item-name">' . htmlspecialchars($item['name']) . '</span>') . '

                                                                        ' . ($hasChildren && $level == 0 ? '<i class="fas fa-chevron-down ms-2 parent-indicator"></i>' : '') . '
                                                                    </div>
                                                                    <div class="menu-item-meta">
                                                                        ' . (!$item['is_active'] ? '<span class="badge bg-secondary badge-sm me-1">Inactive</span>' : '') . '
                                                                        
                                                        </div>
                                                    </div>
                                                            </div>
                                                            <div class="menu-module-actions">
                                                                <button class="btn btn-sm btn-outline-primary" onclick="editMenuItem(' . $item['id'] . ')" title="Edit">
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                                <button class="btn btn-sm btn-outline-danger" onclick="deleteMenuItem(' . $item['id'] . ')" title="Delete">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                                <button class="btn btn-sm btn-outline-secondary drag-handle" title="Drag to reorder">
                                                                    <i class="fas fa-grip-vertical"></i>
                                                                </button>
                                                            </div>
                                                        </div>';
                                                        
                                                        // Render children with proper indentation
                                                        if ($hasChildren) {
                                                            $html .= renderMenuStructure($item['children'], $level + 1);
                                                        }
                                                    }
                                                    return $html;
                                                }
                                                
                                                echo renderMenuStructure($rootItems);
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="empty-state">
                                    <i class="fas fa-folder-open fa-3x text-muted"></i>
                                    <h5 class="mt-3">
                                        <?php if (isset($selected_group) && $selected_group): ?>
                                            No Menu Items in "<?php echo htmlspecialchars($selected_group['name']); ?>"
                                        <?php else: ?>
                                            No Menu Items
                                        <?php endif; ?>
                                    </h5>
                                    <p class="text-muted">
                                        <?php if (isset($selected_group) && $selected_group): ?>
                                            Start by adding menu items to this group using the "Add Menu Item" button above.
                                        <?php else: ?>
                                            Start by adding menu groups or modules to build your menu structure.
                                        <?php endif; ?>
                                    </p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Menu Builder Actions</h6>
                            <p class="text-muted mb-0">Save your changes and manage menu structure</p>
                        </div>
                        <div>
                            <button class="btn btn-outline-secondary me-2" onclick="resetMenu()">
                                <i class="fas fa-undo"></i> Reset
                            </button>
                            <button class="btn btn-outline-info me-2" onclick="previewMenu()">
                                <i class="fas fa-eye"></i> Preview
                            </button>
                            <button class="btn btn-primary" onclick="saveMenu()">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Group Modal -->
<div class="modal fade" id="groupModal" tabindex="-1" aria-labelledby="groupModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="groupModalLabel">Add Menu Group</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="groupForm">
                <div class="modal-body">
                    <input type="hidden" id="groupId" name="id">
                    <input type="hidden" id="groupCsrfToken" name="_token" value="<?php echo $csrf_token; ?>">
                    <div class="mb-3">
                        <label for="groupName" class="form-label">Group Name</label>
                        <input type="text" class="form-control" id="groupName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="groupIcon" class="form-label">Icon</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="groupIcon" name="icon" placeholder="fas fa-folder">
                            <button class="btn btn-outline-secondary" type="button" onclick="openIconPicker('groupIcon')">
                                <i class="fas fa-icons"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="groupDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="groupDescription" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="isCollapsible" name="is_collapsible" checked>
                            <label class="form-check-label" for="isCollapsible">
                                Collapsible
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Group</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Module Modal -->
<div class="modal fade" id="moduleModal" tabindex="-1" aria-labelledby="moduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="moduleModalLabel">Edit Module</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="moduleForm">
                <div class="modal-body">
                    <input type="hidden" id="moduleId" name="id">
                    <input type="hidden" id="moduleCsrfToken" name="_token" value="<?php echo $csrf_token; ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="moduleCaption" class="form-label">Module Name</label>
                                <input type="text" class="form-control" id="moduleCaption" name="caption" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="moduleIcon" class="form-label">Icon</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="moduleIcon" name="menu_icon">
                                    <button class="btn btn-outline-secondary" type="button" onclick="openIconPicker('moduleIcon')">
                                        <i class="fas fa-icons"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="moduleGroup" class="form-label">Group</label>
                                <select class="form-select" id="moduleGroup" name="parent_id">
                                    <option value="">Select Group</option>
                                    <?php foreach ($groups as $group): ?>
                                        <option value="<?php echo $group['id']; ?>"><?php echo htmlspecialchars($group['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="moduleDescription" class="form-label">Description</label>
                                <textarea class="form-control" id="moduleDescription" name="menu_description" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="isExternal" name="is_external">
                                    <label class="form-check-label" for="isExternal">
                                        External Link
                                    </label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="openInNewTab" name="open_in_new_tab">
                                    <label class="form-check-label" for="openInNewTab">
                                        Open in New Tab
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Menu Item Modal -->
<div class="modal fade" id="menuItemModal" tabindex="-1" aria-labelledby="menuItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="menuItemModalLabel">Add Menu Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="menuItemForm">
                <div class="modal-body">
                    <input type="hidden" id="menuItemId" name="id">
                    <input type="hidden" id="menuItemCsrfToken" name="_token" value="<?php echo $csrf_token; ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="menuItemName" class="form-label">Menu Name</label>
                                <input type="text" class="form-control" id="menuItemName" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="menuItemGroup" class="form-label">Group</label>
                                <select class="form-select" id="menuItemGroup" name="group_id" required>
                                    <option value="">Select Group</option>
                                    <?php foreach ($groups as $group): ?>
                                        <option value="<?php echo $group['id']; ?>"><?php echo htmlspecialchars($group['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="menuItemModule" class="form-label">Module</label>
                                <select class="form-select" id="menuItemModule" name="module_id">
                                    <option value="">Select Module (Optional)</option>
                                    <?php foreach ($modules as $module): ?>
                                        <option value="<?php echo $module['id']; ?>"><?php echo htmlspecialchars($module['caption']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="menuItemIcon" class="form-label">Icon</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="menuItemIcon" name="icon" value="fas fa-circle">
                                    <button class="btn btn-outline-secondary" type="button" onclick="openIconPicker('menuItemIcon')">
                                        <i class="fas fa-icons"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="menuItemParent" class="form-label">Parent Menu</label>
                                <select class="form-select" id="menuItemParent" name="parent_id">
                                    <option value="">No Parent (Single Link)</option>
                                    <?php foreach ($menuItems as $item): ?>
                                        <?php if ($item['is_parent']): ?>
                                            <option value="<?php echo $item['id']; ?>"><?php echo htmlspecialchars($item['name']); ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="menuItemSortOrder" class="form-label">Sort Order</label>
                                <input type="number" class="form-control" id="menuItemSortOrder" name="sort_order" value="1">
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="menuItemIsParent" name="is_parent">
                                    <label class="form-check-label" for="menuItemIsParent">
                                        Is Parent Menu (Dropdown)
                                    </label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="menuItemIsActive" name="is_active" checked>
                                    <label class="form-check-label" for="menuItemIsActive">
                                        Active
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Menu Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Icon Picker Modal -->
<div class="modal fade" id="iconPickerModal" tabindex="-1" aria-labelledby="iconPickerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="iconPickerModalLabel">Select Icon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="list-group" id="iconCategories">
                            <button class="list-group-item list-group-item-action active" data-category="all">
                                <i class="fas fa-th-large me-2"></i>All Icons
                            </button>
                            <?php foreach ($available_icons as $category => $icons): ?>
                                <button class="list-group-item list-group-item-action" data-category="<?php echo strtolower(str_replace(' ', '-', $category)); ?>">
                                    <i class="fas fa-folder me-2"></i><?php echo $category; ?>
                                    <span class="badge bg-secondary float-end"><?php echo count($icons); ?></span>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="row" id="iconGrid">
                            <?php foreach ($available_icons as $category => $icons): ?>
                                <?php foreach ($icons as $iconClass => $iconName): ?>
                                    <div class="col-md-2 mb-3">
                                        <div class="icon-item" data-icon="<?php echo $iconClass; ?>" data-category="<?php echo strtolower(str_replace(' ', '-', $category)); ?>">
                                            <div class="icon-preview">
                                                <i class="<?php echo $iconClass; ?> fa-2x"></i>
                                            </div>
                                            <div class="icon-name">
                                                <small><?php echo $iconName; ?></small>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="selectIcon()">Select Icon</button>
            </div>
        </div>
    </div>
</div>

<style>
.menu-builder {
    min-height: 400px;
}

.menu-group {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    margin-bottom: 15px;
    background: #fff;
}

.menu-group-header {
    padding: 15px;
    background: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-radius: 8px 8px 0 0;
}

.menu-group-info {
    display: flex;
    align-items: center;
}

.menu-group-info i {
    margin-right: 8px;
    color: #6c757d;
}

.menu-group-actions {
    display: flex;
    gap: 5px;
}

.menu-group-content {
    padding: 15px;
}

.menu-module {
    border: 1px solid #e9ecef;
    border-radius: 6px;
    padding: 10px;
    margin-bottom: 8px;
    background: #f8f9fa;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.menu-module-info {
    display: flex;
    align-items: center;
}

.menu-module-info i {
    margin-right: 8px;
    color: #495057;
}

.menu-module-actions {
    display: flex;
    gap: 5px;
}

.menu-module.parent-item {
    background-color: #e3f2fd;
    border-left: 3px solid #2196f3;
    font-weight: 600;
}

.menu-module.child-item {
    background-color: #f8f9fa;
    border-left: 3px solid #007bff;
    margin-left: 20px;
}

.menu-standalone {
    margin-top: 20px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
}

.drag-handle {
    cursor: move;
}

.drag-handle:hover {
    background-color: #e9ecef;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #6c757d;
}

.icon-item {
    text-align: center;
    padding: 10px;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s;
}

.icon-item:hover {
    background-color: #f8f9fa;
    border-color: #007bff;
}

.icon-item.selected {
    background-color: #e3f2fd;
    border-color: #2196f3;
}

.icon-preview {
    margin-bottom: 8px;
}

.icon-name {
    font-size: 11px;
    color: #6c757d;
}

.menu-preview {
    border: 1px solid #dee2e6;
    border-radius: 6px;
    padding: 15px;
    background: #f8f9fa;
    min-height: 200px;
}

.menu-preview .nav {
    flex-direction: column;
}

.menu-preview .nav-link {
    padding: 8px 12px;
    margin-bottom: 2px;
    border-radius: 4px;
}

.menu-preview .nav-link:hover {
    background-color: #e9ecef;
}

.menu-preview .nav-link.active {
    background-color: #007bff;
    color: white;
}
</style>

<script>
// Wait for jQuery to be loaded
function waitForjQuery() {
    if (typeof $ === 'undefined') {
        // Waiting for jQuery to load...
        setTimeout(waitForjQuery, 100);
        return;
    }
    
    // jQuery loaded successfully
    initializejQuery();
}

function initializejQuery() {

let selectedIconTarget = null;
let selectedIcon = null;

// Initialize drag and drop
document.addEventListener('DOMContentLoaded', function() {
    // Initialize sortable functionality
    initSortable();
    
    // Initialize icon picker
    initIconPicker();
});

function initSortable() {
    // Implementation for sortable menu items
    // This would use a library like SortableJS
    // Initializing sortable menu
}

function initIconPicker() {
    // Category filter
    document.querySelectorAll('#iconCategories .list-group-item').forEach(item => {
        item.addEventListener('click', function() {
            const category = this.dataset.category;
            
            // Update active category
            document.querySelectorAll('#iconCategories .list-group-item').forEach(i => i.classList.remove('active'));
            this.classList.add('active');
            
            // Filter icons
            document.querySelectorAll('.icon-item').forEach(icon => {
                if (category === 'all' || icon.dataset.category === category) {
                    icon.style.display = 'block';
                } else {
                    icon.style.display = 'none';
                }
            });
        });
    });
    
    // Icon selection
    document.querySelectorAll('.icon-item').forEach(item => {
        item.addEventListener('click', function() {
            document.querySelectorAll('.icon-item').forEach(i => i.classList.remove('selected'));
            this.classList.add('selected');
            selectedIcon = this.dataset.icon;
        });
    });
}

// Menu builder functions will be defined globally below

function editModule(id) {
    // Check if jQuery is available
    if (typeof $ === 'undefined') {
        console.error('jQuery is not loaded. Please refresh the page.');
        alert('Error: jQuery is not loaded. Please refresh the page.');
        return;
    }
    
    $('#moduleModalLabel').text('Edit Module');
    $('#moduleForm')[0].reset();
    $('#moduleId').val(id);
    
    // Load module data
    // This would be implemented with AJAX call
    
    $('#moduleModal').modal('show');
}

function editMenuItem(id) {
    // Check if jQuery is available
    if (typeof $ === 'undefined') {
        console.error('jQuery is not loaded. Please refresh the page.');
        alert('Error: jQuery is not loaded. Please refresh the page.');
        return;
    }
    
    $('#menuItemModalLabel').text('Edit Menu Item');
    $('#menuItemForm')[0].reset();
    $('#menuItemId').val(id);
    
    // Load menu item data via AJAX
    fetch(`<?php echo APP_URL; ?>/menu/get-menu-item/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const item = data.menuItem;
                $('#menuItemName').val(item.name);
                $('#menuItemGroup').val(item.group_id);
                $('#menuItemModule').val(item.module_id || '');
                $('#menuItemIcon').val(item.icon);
                $('#menuItemParent').val(item.parent_id || '');
                $('#menuItemSortOrder').val(item.sort_order);
                $('#menuItemIsParent').prop('checked', item.is_parent == 1);
                $('#menuItemIsActive').prop('checked', item.is_active == 1);
                $('#menuItemModal').modal('show');
            } else {
                showToast('error', data.error);
            }
        })
        .catch(error => {
            showToast('error', 'Error loading menu item data');
        });
}

function deleteMenuItem(id) {
    // Check if jQuery is available
    if (typeof $ === 'undefined') {
        console.error('jQuery is not loaded. Please refresh the page.');
        alert('Error: jQuery is not loaded. Please refresh the page.');
        return;
    }
    
    if (confirm('Are you sure you want to delete this menu item? This action cannot be undone.')) {
        fetch('<?php echo APP_URL; ?>/menu/delete-menu-item', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-Token': window.csrfToken || ''
            },
            body: JSON.stringify({
                id: id,
                _token: window.csrfToken || ''
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('success', data.message);
                location.reload();
            } else {
                showToast('error', data.error);
            }
        })
        .catch(error => {
            showToast('error', 'Error deleting menu item');
        });
    }
}

function toggleVisibility(id) {
    // Implementation for toggling module visibility
    // Toggle visibility for module
}

// openIconPicker function moved to global scope below

// selectIcon function moved to global scope below

// previewMenu function moved to global scope below

function saveMenu() {
    // Collect menu structure
    const menuItems = [];
    
    // Implementation for collecting menu structure and saving
    
    fetch('<?php echo APP_URL; ?>/menu/update-sort', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ menu_items: menuItems })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('success', 'Menu saved successfully');
        } else {
            showToast('error', data.error);
        }
    })
    .catch(error => {
        showToast('error', 'An error occurred while saving');
    });
}

function resetMenu() {
    if (confirm('Are you sure you want to reset the menu to its original state?')) {
        location.reload();
    }
}

// Check jQuery availability
if (typeof $ === 'undefined') {
    console.error('jQuery is not loaded. Please refresh the page.');
    document.addEventListener('DOMContentLoaded', function() {
        alert('Error: jQuery is not loaded. Please refresh the page.');
    });
}

// Form submissions
$('#groupForm').on('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const groupId = $('#groupId').val();
    const url = groupId ? 
        '<?php echo APP_URL; ?>/menu/update-group' : 
        '<?php echo APP_URL; ?>/menu/create-group';
    
    fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-Token': window.csrfToken || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('success', data.message);
            $('#groupModal').modal('hide');
            location.reload();
        } else {
            showToast('error', data.error);
        }
    })
    .catch(error => {
        showToast('error', 'An error occurred');
    });
});

$('#moduleForm').on('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('<?php echo APP_URL; ?>/menu/update-module', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-Token': window.csrfToken || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('success', data.message);
            $('#moduleModal').modal('hide');
            location.reload();
        } else {
            showToast('error', data.error);
        }
    })
    .catch(error => {
        showToast('error', 'An error occurred');
    });
});

    // Menu Item Form submission
    $('#menuItemForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const menuItemId = $('#menuItemId').val();
        const url = menuItemId ? '<?php echo APP_URL; ?>/menu/update-menu-item' : '<?php echo APP_URL; ?>/menu/create-menu-item';
        
        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-Token': window.csrfToken || ''
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('success', data.message);
                $('#menuItemModal').modal('hide');
                location.reload();
            } else {
                showToast('error', data.error);
            }
        })
        .catch(error => {
            showToast('error', 'An error occurred');
        });
    });

// Toast notification function
} // End of initializejQuery()

// Global functions
function addGroup() {
    // Check if jQuery is available
    if (typeof $ === 'undefined') {
        console.error('jQuery is not loaded. Please refresh the page.');
        alert('Error: jQuery is not loaded. Please refresh the page.');
        return;
    }
    
    $('#groupModalLabel').text('Add Menu Group');
    $('#groupForm')[0].reset();
    $('#groupId').val('');
    $('#groupModal').modal('show');
}

function addMenuItem() {
    // Check if jQuery is available
    if (typeof $ === 'undefined') {
        console.error('jQuery is not loaded. Please refresh the page.');
        alert('Error: jQuery is not loaded. Please refresh the page.');
        return;
    }
    
    $('#menuItemModalLabel').text('Add Menu Item');
    $('#menuItemForm')[0].reset();
    $('#menuItemId').val('');
    
    // If we're in group mode, pre-select the group
    <?php if (isset($selected_group_id) && $selected_group_id): ?>
    $('#menuItemGroup').val('<?php echo $selected_group_id; ?>');
    <?php endif; ?>
    
    $('#menuItemModal').modal('show');
}

function editGroup(id) {
    // Check if jQuery is available
    if (typeof $ === 'undefined') {
        console.error('jQuery is not loaded. Please refresh the page.');
        alert('Error: jQuery is not loaded. Please refresh the page.');
        return;
    }
    
    $('#groupModalLabel').text('Edit Menu Group');
    $('#groupForm')[0].reset();
    $('#groupId').val(id);
    
    // Load group data
    // This would be implemented with AJAX call
    
    $('#groupModal').modal('show');
}

function deleteGroup(id) {
    if (confirm('Are you sure you want to delete this group? This will also delete all modules within this group.')) {
        // Delete group from database
        fetch('<?php echo APP_URL; ?>/menu/delete-group', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-Token': window.csrfToken || ''
            },
            body: JSON.stringify({
                id: id,
                _token: window.csrfToken || ''
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('success', data.message || 'Group deleted successfully');
                // Remove group from UI
                const groupElement = document.querySelector(`[data-group-id="${id}"]`);
                if (groupElement) {
                    groupElement.remove();
                }
                // Or reload page to refresh the list
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                showToast('error', data.error || 'Failed to delete group');
            }
        })
        .catch(error => {
            console.error('Error deleting group:', error);
            showToast('error', 'An error occurred while deleting the group');
        });
    }
}

function showToast(type, message) {
    // Check if jQuery is available
    if (typeof $ === 'undefined') {
        // Show toast notification
        return;
    }
    
    // Implementation for toast notifications
    // Show toast notification
}

function openIconPicker(target) {
    // Check if jQuery is available
    if (typeof $ === 'undefined') {
        console.error('jQuery is not loaded. Please refresh the page.');
        alert('Error: jQuery is not loaded. Please refresh the page.');
        return;
    }
    
    selectedIconTarget = target;
    selectedIcon = null;
    
    // Reset selection
    document.querySelectorAll('.icon-item').forEach(i => i.classList.remove('selected'));
    
    $('#iconPickerModal').modal('show');
}

function selectIcon() {
    // Check if jQuery is available
    if (typeof $ === 'undefined') {
        console.error('jQuery is not loaded. Please refresh the page.');
        alert('Error: jQuery is not loaded. Please refresh the page.');
        return;
    }
    
    if (selectedIcon && selectedIconTarget) {
        document.getElementById(selectedIconTarget).value = selectedIcon;
        $('#iconPickerModal').modal('hide');
    }
}

function previewMenu() {
    // Load menu preview
    fetch('<?php echo APP_URL; ?>/menu/preview', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('menuPreview').innerHTML = data.menu_html;
        }
    })
    .catch(error => {
        console.error('Error loading preview:', error);
    });
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    waitForjQuery();
});

</script>

<?php
// End output buffering and capture content
$content = ob_get_clean();

// Include the main layout with content
include __DIR__ . '/../layouts/app.php';
?>
<style>
/* Menu Builder Hierarchical Structure Styling */
.menu-module {
    border: 1px solid #e9ecef;
    border-radius: 6px;
    margin-bottom: 4px;
    background: white;
    transition: all 0.2s ease;
}

.menu-module:hover {
    border-color: #007bff;
    box-shadow: 0 2px 4px rgba(0,123,255,0.1);
}

.menu-child-indent {
    margin-left: 30px;
    border-left: 3px solid #e9ecef;
    padding-left: 16px;
    background: #f8f9fa;
    position: relative;
}

.menu-inactive {
    opacity: 0.7;
    background: #f8f9fa;
}

.menu-inactive .menu-item-name {
    color: #6c757d;
    text-decoration: line-through;
}

.menu-module-info {
    padding: 12px;
}

.menu-item-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
}

.menu-item-main {
    display: flex;
    align-items: center;
    flex: 1;
}

.menu-item-name {
    font-weight: 500;
    color: #495057;
    font-size: 14px;
}

.menu-item-meta {
    display: flex;
    align-items: center;
    gap: 4px;
}

.badge-sm {
    font-size: 10px;
    padding: 2px 6px;
}

.menu-module-actions {
    display: flex;
    gap: 4px;
    opacity: 0;
    transition: opacity 0.2s ease;
}

.menu-module:hover .menu-module-actions {
    opacity: 1;
}

.menu-module-actions .btn {
    padding: 4px 8px;
    font-size: 12px;
}

/* Visual hierarchy indicators */
.menu-child-indent .menu-item-main i {
    margin-left: 0;
}

/* Parent item styling */
.menu-parent-item {
    border-left: 4px solid #007bff;
}

.menu-parent-item .menu-item-name {
    font-weight: 600;
}

.parent-indicator {
    color: #28a745;
    font-size: 14px;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

/* Enhanced child indentation */
.menu-child-indent::before {
    content: '';
    position: absolute;
    left: -3px;
    top: 0;
    bottom: 0;
    width: 3px;
    background: linear-gradient(to bottom, #007bff, #e9ecef);
    border-radius: 0 2px 2px 0;
}

/* Empty state styling */
.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #6c757d;
}

.empty-state i {
    margin-bottom: 16px;
}

/* Group header styling */
.menu-group-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 2px solid #dee2e6;
}

.menu-group-info {
    display: flex;
    align-items: center;
}

.menu-group-info i {
    color: #007bff;
    margin-right: 8px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .menu-item-content {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    
    .menu-item-meta {
        align-self: flex-end;
    }
    
    .menu-child-indent {
        margin-left: 20px;
        padding-left: 12px;
    }
    
    .parent-indicator {
        font-size: 12px;
    }
}

/* Additional spacing improvements */
.menu-module {
    margin-bottom: 6px;
}

.menu-child-indent {
    margin-bottom: 4px;
}

/* Visual connection between parent and children */
.menu-parent-item + .menu-child-indent {
    margin-top: -2px;
    border-top-left-radius: 0;
    border-top-right-radius: 6px;
}
</style>

