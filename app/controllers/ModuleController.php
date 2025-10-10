<?php
/**
 * Module Management Controller
 */
class ModuleController extends BaseController
{
    private $moduleModel;

    public function __construct()
    {
        parent::__construct();
        $this->moduleModel = new Module();
    }

    public function index($request = null, $response = null, $params = [])
    {
        // Check if user is logged in
        if (!Session::has('user_id')) {
            $this->redirect('/login');
            return;
        }

        try {
            $page = (int) ($request->input('page') ?? 1);
            $search = $request->input('search') ?? '';
            $role = $request->input('role') ?? '';
            $perPage = (int) ($request->input('per_page') ?? DEFAULT_PAGE_SIZE);
            $sort = $request->input('sort') ?? 'id';
            $order = $request->input('order') ?? 'asc';

            $where = '1=1';
            $whereParams = [];

            if ($search) {
                $where .= ' AND (caption LIKE :search1 OR link LIKE :search2)';
                $whereParams['search1'] = "%{$search}%";
                $whereParams['search2'] = "%{$search}%";
            }

            if ($role) {
                $where .= ' AND ' . $role . ' = 1';
            }

            // Validate sort field
            $allowedSorts = ['id', 'caption', 'link', 'created_at', 'updated_at'];
            if (!in_array($sort, $allowedSorts)) {
                $sort = 'id';
            }

            // Validate order
            $order = strtolower($order) === 'desc' ? 'desc' : 'asc';

            $modules = $this->moduleModel->paginate($page, $perPage, $where, $whereParams, $sort, $order);

            $this->view('modules/index', [
                'title' => 'Modules',
                'current_page' => 'modules',
                'modules' => $modules,
                'search' => $search,
                'role' => $role,
                'csrf_token' => $this->csrfToken()
            ]);
        } catch (Exception $e) {
            // Log the error for debugging
            error_log("ModuleController index error: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            
            // Show a user-friendly error message
            $this->withError('An error occurred while loading modules. Please try again.');
            $this->redirect('/modules');
        }
    }
    
    public function create($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
        }

        $this->view('modules/create', [
            'title' => 'Create Module',
            'csrf_token' => $this->csrfToken(),
            'available_routes' => $this->getAvailableRoutes(),
            'available_icons' => $this->getAvailableIcons()
        ]);
    }

    public function store($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
        }

        $validator = $request->validate([
            'caption' => 'required|string|max:255',
            'logo' => 'required|string|max:100',
            'link' => 'required|string|max:255'
        ]);

        if (!$validator->validate()) {
            if ($request->isAjax()) {
                $this->json(['errors' => $validator->errors()], 422);
            } else {
                $this->withErrors($validator->errors());
                $this->redirect('/modules/create');
            }
        }

        $data = [
            'caption' => $request->input('caption'),
            'logo' => $request->input('logo'),
            'link' => $request->input('link'),
            'admin' => $request->input('admin') ? 1 : 0,
            'manajemen' => $request->input('manajemen') ? 1 : 0,
            'user' => $request->input('user') ? 1 : 0,
            'marketing' => $request->input('marketing') ? 1 : 0,
            'customer' => $request->input('customer') ? 1 : 0
        ];

        try {
            $this->moduleModel->beginTransaction();
            $moduleId = $this->moduleModel->create($data);
            
            if ($moduleId) {
                $this->moduleModel->commit();
                if ($request->isAjax()) {
                    $this->json(['success' => true, 'message' => 'Module created successfully!']);
                } else {
                    $this->withSuccess('Module created successfully!');
                    $this->redirect('/modules');
                }
            } else {
                $this->moduleModel->rollback();
                if ($request->isAjax()) {
                    $this->json(['error' => 'Failed to create module'], 500);
                } else {
                    $this->withError('Failed to create module!');
                    $this->redirect('/modules/create');
                }
            }
        } catch (Exception $e) {
            $this->moduleModel->rollback();
            if ($request->isAjax()) {
                $this->json(['error' => 'An error occurred while creating module'], 500);
            } else {
                $this->withError('An error occurred while creating module');
                $this->redirect('/modules/create');
            }
        }
    }
    
    public function show($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
        }

        $id = $params[0] ?? $request->input('id');
        $module = $this->moduleModel->find($id);

        if (!$module) {
            if ($request->isAjax()) {
                $this->json(['error' => 'Module not found'], 404);
            } else {
                $this->withError('Module not found');
                $this->redirect('/modules');
            }
        }

        $this->view('modules/show', [
            'title' => 'Module Details',
            'module' => $module,
            'csrf_token' => $this->csrfToken()
        ]);
    }
    
    public function edit($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
        }

        $id = $params[0] ?? $request->input('id');
        
        $module = $this->moduleModel->find($id);

        if (!$module) {
            $this->withError('Module not found');
            $this->redirect('/modules');
            return;
        }
        $this->view('modules/edit', [
            'title' => 'Edit Module',
            'module' => $module,
            'csrf_token' => $this->csrfToken(),
            'available_routes' => $this->getAvailableRoutes(),
            'available_icons' => $this->getAvailableIcons()
        ]);
    }

    public function update($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
        }

        $id = $params[0] ?? $request->input('id');
        $module = $this->moduleModel->find($id);

        if (!$module) {
            if ($request->isAjax()) {
                $this->json(['error' => 'Module not found'], 404);
            } else {
                $this->withError('Module not found');
                $this->redirect('/modules');
            }
        }

        $validator = $request->validate([
            'caption' => 'required|string|max:255',
            'logo' => 'required|string|max:100',
            'link' => 'required|string|max:255'
        ]);

        if (!$validator->validate()) {
            if ($request->isAjax()) {
                $this->json(['errors' => $validator->errors()], 422);
            } else {
                $this->withErrors($validator->errors());
                $this->redirect("/modules/{$id}/edit");
            }
        }

        $data = [
            'caption' => $request->input('caption'),
            'logo' => $request->input('logo'),
            'link' => $request->input('link'),
            'admin' => $request->input('admin') ? 1 : 0,
            'manajemen' => $request->input('manajemen') ? 1 : 0,
            'user' => $request->input('user') ? 1 : 0,
            'marketing' => $request->input('marketing') ? 1 : 0,
            'customer' => $request->input('customer') ? 1 : 0
        ];

        try {
            $this->moduleModel->beginTransaction();
            $this->moduleModel->update($id, $data);
            $this->moduleModel->commit();

            if ($request->isAjax()) {
                $this->json(['success' => true, 'message' => 'Module updated successfully']);
            } else {
                $this->withSuccess('Module updated successfully');
                $this->redirect('/modules');
            }
        } catch (Exception $e) {
            $this->moduleModel->rollback();
            if ($request->isAjax()) {
                $this->json(['error' => 'Failed to update module'], 500);
            } else {
                $this->withError('Failed to update module');
                $this->redirect("/modules/{$id}/edit");
            }
        }
    }
    
    public function destroy($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
        }

        $id = $params[0] ?? $request->input('id');
        $module = $this->moduleModel->find($id);

        if (!$module) {
            if ($request->isAjax()) {
                $this->json(['error' => 'Module not found'], 404);
            } else {
                $this->withError('Module not found');
                $this->redirect('/modules');
            }
        }

        try {
            $this->moduleModel->beginTransaction();
            $this->moduleModel->delete($id);
            $this->moduleModel->commit();

            if ($request->isAjax()) {
                $this->json(['success' => true, 'message' => 'Module deleted successfully']);
            } else {
                $this->withSuccess('Module deleted successfully');
                $this->redirect('/modules');
            }
        } catch (Exception $e) {
            $this->moduleModel->rollback();
            if ($request->isAjax()) {
                $this->json(['error' => 'Failed to delete module'], 500);
            } else {
                $this->withError('Failed to delete module');
                $this->redirect('/modules');
            }
        }
    }

    /**
     * Get available routes for module link selection
     * Auto-generated from App routes with @index handler
     */
    private function getAvailableRoutes()
    {
        // Get routes from App singleton (Fix memory leak)
        $app = App::getInstance();
        $router = $app->getRouter();
        $allRoutes = $router->getRoutes();
        
        $availableRoutes = [];
        
        foreach ($allRoutes as $route) {
            // Filter: GET method and handler with @index
            if ($route['method'] === 'GET' && strpos($route['handler'], '@index') !== false) {
                $path = $route['path'];
                
                // Skip root path and paths with parameters
                if ($path === '/' || strpos($path, '{') !== false) {
                    continue;
                }
                
                // Generate label from path: remove slashes, replace hyphens with spaces, capitalize
                $label = $this->generateLabelFromPath($path);
                
                // Use same label for description
                $description = $label;
                
                $availableRoutes[] = [
                    'value' => $path,
                    'label' => $label,
                    'description' => $description
                ];
            }
        }
        
        return $availableRoutes;
    }

    /**
     * Generate capitalized label from path
     * Example: /call-center -> Call Center
     */
    private function generateLabelFromPath($path)
    {
        // Remove leading/trailing slashes
        $clean = trim($path, '/');
        
        // Replace hyphens with spaces
        $clean = str_replace('-', ' ', $clean);
        
        // Capitalize each word
        $label = ucwords($clean);
        
        return $label;
    }

    /**
     * Get available Font Awesome icons for module logo selection
     * Auto-generated from local Font Awesome metadata with categories
     */
    public function getAvailableIcons()
    {
        $cacheFile = __DIR__ . '/../../cache/fontawesome_icons.json';
        $metadataDir = __DIR__ . '/../../assets/fontawesome/metadata/';
        $categoriesFile = $metadataDir . 'categories.yml';
        $iconsFile = $metadataDir . 'icons.json';
        
        // Check if cache exists and is newer than metadata files
        if (file_exists($cacheFile) && 
            file_exists($categoriesFile) && 
            file_exists($iconsFile) &&
            filemtime($cacheFile) > filemtime($categoriesFile) &&
            filemtime($cacheFile) > filemtime($iconsFile)) {
            $cachedData = file_get_contents($cacheFile);
            if ($cachedData) {
                return json_decode($cachedData, true);
            }
        }
        
        // Parse metadata and build categorized icons
        $icons = $this->parseIconsFromMetadata($categoriesFile, $iconsFile);
        
        // Save to cache
        $cacheDir = dirname($cacheFile);
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }
        file_put_contents($cacheFile, json_encode($icons));
        
        return $icons;
    }
    
    /**
     * Parse Font Awesome icons from metadata files
     * Uses official Font Awesome categories and icon definitions
     */
    private function parseIconsFromMetadata($categoriesFile, $iconsFile)
    {
        if (!file_exists($categoriesFile) || !file_exists($iconsFile)) {
            // Fallback to empty array if metadata files not found
            return ['All Icons' => []];
        }
        
        // Parse categories YAML file
        $categoriesData = $this->parseYAML($categoriesFile);
        
        // Parse icons JSON file
        $iconsData = json_decode(file_get_contents($iconsFile), true);
        
        $categorizedIcons = [];
        
        // Selected popular categories for better UX (to avoid overwhelming users)
        $selectedCategories = [
            'accessibility' => true,
            'alert' => true,
            'arrows' => true,
            'business' => true,
            'charts-diagrams' => true,
            'communication' => true,
            'devices-hardware' => true,
            'editing' => true,
            'files' => true,
            'medical-health' => true,
            'money' => true,
            'navigation' => false, // Skip, covered by arrows
            'security' => true,
            'social' => true,
            'shopping' => true,
            'sports-fitness' => true,
            'time' => true,
            'transportation' => true,
            'users-people' => true,
            'weather' => true
        ];
        
        // Process each category
        foreach ($categoriesData as $categoryKey => $categoryData) {
            // Skip categories not in selected list or explicitly disabled
            if (!isset($selectedCategories[$categoryKey]) || $selectedCategories[$categoryKey] === false) {
                continue;
            }
            
            $categoryLabel = $categoryData['label'] ?? ucwords(str_replace('-', ' ', $categoryKey));
            $categoryIcons = $categoryData['icons'] ?? [];
            
            foreach ($categoryIcons as $iconName) {
                // Get icon details from icons.json
                if (isset($iconsData[$iconName])) {
                    $iconData = $iconsData[$iconName];
                    $iconLabel = $iconData['label'] ?? ucwords(str_replace('-', ' ', $iconName));
                    $styles = $iconData['styles'] ?? ['solid'];
                    
                    // Use 'solid' style by default (most common for free version)
                    if (in_array('solid', $styles)) {
                        $iconClass = 'fas fa-' . $iconName;
                        $categorizedIcons[$categoryLabel][$iconClass] = $iconLabel;
                    } elseif (in_array('regular', $styles)) {
                        $iconClass = 'far fa-' . $iconName;
                        $categorizedIcons[$categoryLabel][$iconClass] = $iconLabel;
                    } elseif (in_array('brands', $styles)) {
                        $iconClass = 'fab fa-' . $iconName;
                        $categorizedIcons[$categoryLabel][$iconClass] = $iconLabel;
                    }
                }
            }
        }
        
        // Sort categories and icons
        ksort($categorizedIcons);
        foreach ($categorizedIcons as $category => $icons) {
            asort($categorizedIcons[$category]);
        }
        
        return $categorizedIcons;
    }
    
    /**
     * Simple YAML parser for categories file
     * Parses basic YAML structure with categories, icons list, and labels
     */
    private function parseYAML($filePath)
    {
        $content = file_get_contents($filePath);
        $lines = explode("\n", $content);
        $result = [];
        $currentCategory = null;
        $inIconsList = false;
        
        foreach ($lines as $line) {
            // Category header (no indentation, ends with colon)
            if (preg_match('/^([a-z0-9-]+):$/', $line, $matches)) {
                $currentCategory = $matches[1];
                $result[$currentCategory] = ['icons' => [], 'label' => ''];
                $inIconsList = false;
            }
            // Icons list start
            elseif (preg_match('/^  icons:$/', $line)) {
                $inIconsList = true;
            }
            // Icon item in list
            elseif ($inIconsList && preg_match('/^    - (.+)$/', $line, $matches)) {
                $iconName = trim($matches[1], '\'"');
                if ($currentCategory) {
                    $result[$currentCategory]['icons'][] = $iconName;
                }
            }
            // Label
            elseif (preg_match('/^  label: (.+)$/', $line, $matches)) {
                if ($currentCategory) {
                    $result[$currentCategory]['label'] = $matches[1];
                }
                $inIconsList = false;
            }
        }
        
        return $result;
    }
}
