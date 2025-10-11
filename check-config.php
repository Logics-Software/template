<?php
/**
 * Configuration Checker
 * Use this file to verify APP_URL and BASE_URL are correctly configured
 * Access: https://your-domain.com/check-config.php
 */

// Start output buffering
ob_start();

// Define APP_PATH
define('APP_PATH', __DIR__);

// Build APP_URL with proper sub-directory support
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost:8000';
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$scriptDir = dirname($scriptName);

if ($scriptDir === '/' || $scriptDir === '.' || $scriptDir === '\\') {
    define('APP_URL', $protocol . '://' . $host);
} else {
    define('APP_URL', $protocol . '://' . $host . $scriptDir);
}

// Include config to get BASE_URL
require_once 'app/config/config.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuration Checker</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #4CAF50;
            padding-bottom: 10px;
        }
        .info-group {
            margin: 20px 0;
            padding: 15px;
            background: #f9f9f9;
            border-left: 4px solid #4CAF50;
            border-radius: 5px;
        }
        .info-group h3 {
            margin-top: 0;
            color: #4CAF50;
        }
        .value {
            font-family: 'Courier New', monospace;
            background: #e8f5e9;
            padding: 8px 12px;
            border-radius: 4px;
            display: inline-block;
            margin: 5px 0;
            word-break: break-all;
        }
        .label {
            font-weight: bold;
            color: #666;
            display: block;
            margin-bottom: 5px;
        }
        .test-section {
            margin-top: 30px;
            padding: 20px;
            background: #e3f2fd;
            border-radius: 5px;
        }
        .success {
            color: #4CAF50;
            font-weight: bold;
        }
        .warning {
            color: #ff9800;
            font-weight: bold;
        }
        .error {
            color: #f44336;
            font-weight: bold;
        }
        button {
            background: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin: 10px 5px 10px 0;
        }
        button:hover {
            background: #45a049;
        }
        #testResult {
            margin-top: 15px;
            padding: 15px;
            border-radius: 5px;
            display: none;
        }
        code {
            background: #f4f4f4;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Configuration Checker</h1>
        
        <div class="info-group">
            <h3>Server Information</h3>
            <span class="label">Protocol:</span>
            <div class="value"><?php echo $protocol; ?></div>
            
            <span class="label">Host:</span>
            <div class="value"><?php echo $host; ?></div>
            
            <span class="label">Script Name:</span>
            <div class="value"><?php echo $scriptName; ?></div>
            
            <span class="label">Script Directory:</span>
            <div class="value"><?php echo $scriptDir; ?></div>
        </div>

        <div class="info-group">
            <h3>Application URLs</h3>
            <span class="label">APP_URL:</span>
            <div class="value"><?php echo APP_URL; ?></div>
            
            <span class="label">BASE_URL:</span>
            <div class="value"><?php echo BASE_URL; ?></div>
            
            <?php if (APP_URL === rtrim(BASE_URL, '/')): ?>
                <p class="success">✓ APP_URL and BASE_URL are consistent!</p>
            <?php else: ?>
                <p class="warning">⚠ Warning: APP_URL and BASE_URL are different!</p>
                <p>This might cause issues with API calls in JavaScript.</p>
            <?php endif; ?>
        </div>

        <div class="test-section">
            <h3>Test API Endpoints</h3>
            <p>Click the button below to test if API endpoints are accessible:</p>
            
            <button onclick="testAPI()">Test API Endpoint</button>
            <button onclick="testMessages()">Test Messages API</button>
            
            <div id="testResult"></div>
        </div>

        <div class="info-group">
            <h3>JavaScript Configuration</h3>
            <p>In your browser console, you should see:</p>
            <code>window.appUrl = "<?php echo APP_URL; ?>"</code>
            
            <p style="margin-top: 15px;">API calls should go to:</p>
            <code><?php echo APP_URL; ?>/api/messages/unread-count</code>
        </div>

        <div class="info-group">
            <h3>Troubleshooting Steps</h3>
            <ol>
                <li>Check browser console (F12) for JavaScript errors</li>
                <li>Check Network tab to see if API calls are being made</li>
                <li>Verify that <code>window.appUrl</code> is set correctly</li>
                <li>Check if .htaccess is configured properly for API routes</li>
                <li>Ensure PHP error reporting is enabled in development</li>
            </ol>
        </div>
    </div>

    <script>
        function testAPI() {
            const resultDiv = document.getElementById('testResult');
            resultDiv.style.display = 'block';
            resultDiv.style.background = '#fff3cd';
            resultDiv.innerHTML = '⏳ Testing API endpoint...';
            
            const apiUrl = '<?php echo APP_URL; ?>/api/messages/unread-count';
            
            fetch(apiUrl)
                .then(response => {
                    if (response.ok) {
                        return response.json();
                    }
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                })
                .then(data => {
                    resultDiv.style.background = '#d4edda';
                    resultDiv.innerHTML = `
                        <strong class="success">✓ API Test Successful!</strong><br>
                        <strong>Endpoint:</strong> ${apiUrl}<br>
                        <strong>Response:</strong> <code>${JSON.stringify(data, null, 2)}</code>
                    `;
                })
                .catch(error => {
                    resultDiv.style.background = '#f8d7da';
                    resultDiv.innerHTML = `
                        <strong class="error">✗ API Test Failed!</strong><br>
                        <strong>Endpoint:</strong> ${apiUrl}<br>
                        <strong>Error:</strong> ${error.message}<br>
                        <br>
                        <strong>Possible causes:</strong>
                        <ul>
                            <li>You are not logged in</li>
                            <li>API endpoint doesn't exist</li>
                            <li>CORS issue</li>
                            <li>Wrong URL configuration</li>
                        </ul>
                    `;
                });
        }

        function testMessages() {
            const resultDiv = document.getElementById('testResult');
            resultDiv.style.display = 'block';
            resultDiv.style.background = '#fff3cd';
            resultDiv.innerHTML = '⏳ Testing Messages API...';
            
            const apiUrl = '<?php echo APP_URL; ?>/api/messages/recent';
            
            fetch(apiUrl)
                .then(response => {
                    if (response.ok) {
                        return response.json();
                    }
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                })
                .then(data => {
                    resultDiv.style.background = '#d4edda';
                    resultDiv.innerHTML = `
                        <strong class="success">✓ Messages API Test Successful!</strong><br>
                        <strong>Endpoint:</strong> ${apiUrl}<br>
                        <strong>Response:</strong> <code>${JSON.stringify(data, null, 2)}</code>
                    `;
                })
                .catch(error => {
                    resultDiv.style.background = '#f8d7da';
                    resultDiv.innerHTML = `
                        <strong class="error">✗ Messages API Test Failed!</strong><br>
                        <strong>Endpoint:</strong> ${apiUrl}<br>
                        <strong>Error:</strong> ${error.message}<br>
                        <br>
                        <strong>Note:</strong> You need to be logged in to test this endpoint.
                    `;
                });
        }
    </script>
</body>
</html>
<?php
// Delete this file after checking configuration for security
?>

