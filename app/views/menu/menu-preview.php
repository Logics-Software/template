<?php
/**
 * Menu Preview View
 */

// Generate CSRF token
$csrfToken = Session::generateCSRF();

// Start output buffering
ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Preview - <?php echo APP_NAME; ?></title>
    <meta name="csrf-token" content="<?php echo $csrfToken; ?>">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?php echo APP_URL; ?>/assets/css/style.css" rel="stylesheet">
    
    <style>
        .preview-container {
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        .preview-sidebar {
            background-color: #343a40;
            min-height: 100vh;
            padding: 20px;
        }
        .preview-content {
            padding: 20px;
        }
        .menu-preview {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .nav-link {
            color: #6c757d;
            padding: 10px 15px;
            border-radius: 5px;
            transition: all 0.3s;
        }
        .nav-link:hover {
            background-color: #f8f9fa;
            color: #495057;
        }
        .nav-link.active {
            background-color: #007bff;
            color: white;
        }
        .nav-link i {
            margin-right: 10px;
            width: 20px;
        }
    </style>
</head>
<body>
    <div class="preview-container">
        <div class="row">
            <!-- Preview Sidebar -->
            <div class="col-md-4">
                <div class="preview-sidebar">
                    <h5 class="text-white mb-4">Menu Preview</h5>
                    <div class="menu-preview">
                        <h6 class="mb-3">Generated Menu:</h6>
                        <ul class="nav flex-column">
                            <?php
                            if (isset($menuItems) && is_array($menuItems)) {
                                foreach ($menuItems as $item) {
                                    echo '<li class="nav-item">';
                                    echo '<a class="nav-link" href="' . htmlspecialchars($item['url']) . '">';
                                    if (isset($item['icon'])) {
                                        echo '<i class="' . htmlspecialchars($item['icon']) . '"></i>';
                                    }
                                    echo '<span>' . htmlspecialchars($item['name']) . '</span>';
                                    echo '</a>';
                                    echo '</li>';
                                }
                            } else {
                                echo '<li class="nav-item"><span class="text-muted">No menu items found</span></li>';
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Preview Content -->
            <div class="col-md-8">
                <div class="preview-content">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4>Menu Preview</h4>
                        <div>
                            <button class="btn btn-primary" onclick="refreshPreview()">
                                <i class="fas fa-sync-alt"></i> Refresh
                            </button>
                            <button class="btn btn-secondary" onclick="window.close()">
                                <i class="fas fa-times"></i> Close
                            </button>
                        </div>
                    </div>
                    
                    <div class="menu-preview">
                        <h5>Menu Structure</h5>
                        <div class="mb-3">
                            <strong>User ID:</strong> <?php echo Session::get('user_id'); ?><br>
                            <strong>User Role:</strong> <?php echo Session::get('user_role'); ?><br>
                            <strong>Menu Items Count:</strong> <?php echo isset($menuItems) ? count($menuItems) : 0; ?>
                        </div>
                        
                        <h6>Raw Menu Data:</h6>
                        <pre class="bg-light p-3 rounded"><?php echo json_encode($menuItems ?? [], JSON_PRETTY_PRINT); ?></pre>
                        
                        <h6>Generated HTML:</h6>
                        <pre class="bg-light p-3 rounded"><?php echo htmlspecialchars($menuHtml ?? ''); ?></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        function refreshPreview() {
            window.location.reload();
        }
        
        // Auto-refresh every 30 seconds
        setInterval(function() {
            // Optional: Auto-refresh preview
        }, 30000);
    </script>
</body>
</html>

<?php
$content = ob_get_clean();
echo $content;
?>
