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

        $page = (int) ($request->input('page') ?? 1);
        $search = $request->input('search') ?? '';
        $role = $request->input('role') ?? '';
        $perPage = (int) ($request->input('per_page') ?? DEFAULT_PAGE_SIZE);
        $sort = $request->input('sort') ?? 'id';
        $order = $request->input('order') ?? 'asc';

        $where = '1=1';
        $whereParams = [];

        if ($search) {
            $where .= ' AND (caption LIKE :search OR link LIKE :search)';
            $whereParams['search'] = "%{$search}%";
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
     */
    private function getAvailableRoutes()
    {
        return [
            'dashboard' => [
                'value' => '/dashboard',
                'label' => 'Dashboard',
                'description' => 'Main Dashboard'
            ],
            'konfigurasi' => [
                'value' => '/konfigurasi',
                'label' => 'Konfigurasi App',
                'description' => 'App Configuration'
            ],
            'modules' => [
                'value' => '/modules',
                'label' => 'Manajemen Module App',
                'description' => 'App Modules Management'
            ],
            'menu' => [
                'value' => '/menu',
                'label' => 'Manajemen Menu',
                'description' => 'App Menu Management'
            ],
            'users' => [
                'value' => '/users',
                'label' => 'Manajemen User',
                'description' => 'User Management'
            ],
            'messages' => [
                'value' => '/messages',
                'label' => 'Pesan',
                'description' => 'Messages'
            ],
            'call-center' => [
                'value' => '/call-center',
                'label' => 'Call Center',
                'description' => 'Call Center Setting'
            ],
            'profile' => [
                'value' => '/profile',
                'label' => 'Profile User',
                'description' => 'User Profile View'
            ],
            'change-password' => [
                'value' => '/change-password',
                'label' => 'Ganti Password',
                'description' => 'Change User Password'
            ]
        ];
    }

    /**
     * Get available Font Awesome icons for module logo selection
     */
    private function getAvailableIcons()
    {
        return [
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
                'fas fa-bookmark' => 'Bookmark',
                'fas fa-flag' => 'Flag',
                'fas fa-tag' => 'Tag',
                'fas fa-tags' => 'Tags',
                'fas fa-book' => 'Book',
                'fas fa-newspaper' => 'Newspaper',
                'fas fa-magic' => 'Magic',
                'fas fa-gem' => 'Gem',
                'fas fa-crown' => 'Crown',
                'fas fa-trophy' => 'Trophy',
                'fas fa-medal' => 'Medal',
                'fas fa-award' => 'Award',
                'fas fa-certificate' => 'Certificate',
                'fas fa-ribbon' => 'Ribbon',
                'fas fa-gift' => 'Gift',
                'fas fa-birthday-cake' => 'Birthday Cake',
                'fas fa-bell' => 'Bell',
                'fas fa-bell-slash' => 'Bell Slash',
                'fas fa-volume-up' => 'Volume Up',
                'fas fa-volume-down' => 'Volume Down',
                'fas fa-volume-mute' => 'Volume Mute',
                'fas fa-volume-off' => 'Volume Off',
            ],
            'Business & Finance' => [
                'fas fa-briefcase' => 'Briefcase',
                'fas fa-building' => 'Building',
                'fas fa-handshake' => 'Handshake',
                'fas fa-chart-line' => 'Chart Line',
                'fas fa-coins' => 'Coins',
                'fas fa-credit-card' => 'Credit Card',
                'fas fa-receipt' => 'Receipt',
                'fas fa-shopping-cart' => 'Shopping Cart',
                'fas fa-wallet' => 'Wallet',
                'fas fa-piggy-bank' => 'Piggy Bank',
                'fas fa-chart-pie' => 'Chart Pie',
                'fas fa-chart-area' => 'Chart Area',
                'fas fa-trending-up' => 'Trending Up',
                'fas fa-trending-down' => 'Trending Down',
                'fas fa-percentage' => 'Percentage',
                'fas fa-dollar-sign' => 'Dollar Sign',
                'fas fa-euro-sign' => 'Euro Sign',
                'fas fa-pound-sign' => 'Pound Sign',
                'fas fa-yen-sign' => 'Yen Sign',
                'fas fa-rupee-sign' => 'Rupee Sign',
                'fas fa-won-sign' => 'Won Sign',
                'fas fa-shekel-sign' => 'Shekel Sign',
                'fas fa-ruble-sign' => 'Ruble Sign',
                'fas fa-lira-sign' => 'Lira Sign',
                'fas fa-money-bill' => 'Money Bill',
                'fas fa-money-bill-wave' => 'Money Bill Wave',
                'fas fa-money-bill-alt' => 'Money Bill Alt',
                'fas fa-money-check' => 'Money Check',
                'fas fa-money-check-alt' => 'Money Check Alt',
                'fas fa-cash-register' => 'Cash Register',
                'fas fa-store' => 'Store',
                'fas fa-store-alt' => 'Store Alt',
                'fas fa-shopping-bag' => 'Shopping Bag',
                'fas fa-shopping-basket' => 'Shopping Basket',
                'fas fa-barcode' => 'Barcode',
                'fas fa-qrcode' => 'QR Code',
                'fas fa-receipt' => 'Receipt',
                'fas fa-file-invoice' => 'File Invoice',
                'fas fa-file-invoice-dollar' => 'File Invoice Dollar',
                'fas fa-balance-scale' => 'Balance Scale',
                'fas fa-balance-scale-left' => 'Balance Scale Left',
                'fas fa-balance-scale-right' => 'Balance Scale Right',
                'fas fa-gavel' => 'Gavel',
                'fas fa-landmark' => 'Landmark',
                'fas fa-university' => 'University',
                'fas fa-industry' => 'Industry',
                'fas fa-warehouse' => 'Warehouse',
                'fas fa-truck' => 'Truck',
                'fas fa-shipping-fast' => 'Shipping Fast',
                'fas fa-shipping-timed' => 'Shipping Timed',
                'fas fa-box' => 'Box',
                'fas fa-boxes' => 'Boxes',
                'fas fa-pallet' => 'Pallet',
                'fas fa-dolly' => 'Dolly',
                'fas fa-dolly-flatbed' => 'Dolly Flatbed',
                'fas fa-forklift' => 'Forklift',
                'fas fa-truck-loading' => 'Truck Loading',
                'fas fa-truck-moving' => 'Truck Moving',
                'fas fa-truck-pickup' => 'Truck Pickup',
            ],
            'Communication & Social' => [
                'fas fa-comments' => 'Comments',
                'fas fa-comment' => 'Comment',
                'fas fa-bell' => 'Bell',
                'fas fa-bell-slash' => 'Bell Slash',
                'fas fa-paper-plane' => 'Paper Plane',
                'fas fa-share' => 'Share',
                'fas fa-reply' => 'Reply',
                'fas fa-forward' => 'Forward',
                'fas fa-at' => 'At',
                'fas fa-hashtag' => 'Hashtag',
                'fas fa-link' => 'Link',
                'fas fa-unlink' => 'Unlink',
                'fas fa-rss' => 'RSS',
                'fas fa-broadcast-tower' => 'Broadcast Tower',
                'fas fa-microphone' => 'Microphone',
                'fas fa-video' => 'Video',
                'fas fa-camera' => 'Camera',
                'fas fa-image' => 'Image',
                'fas fa-comment-dots' => 'Comment Dots',
                'fas fa-comment-alt' => 'Comment Alt',
                'fas fa-comment-slash' => 'Comment Slash',
                'fas fa-comments-dollar' => 'Comments Dollar',
                'fas fa-comment-medical' => 'Comment Medical',
                'fas fa-phone' => 'Phone',
                'fas fa-phone-alt' => 'Phone Alt',
                'fas fa-phone-slash' => 'Phone Slash',
                'fas fa-phone-square' => 'Phone Square',
                'fas fa-phone-square-alt' => 'Phone Square Alt',
                'fas fa-phone-volume' => 'Phone Volume',
                'fas fa-fax' => 'Fax',
                'fas fa-voicemail' => 'Voicemail',
                'fas fa-address-book' => 'Address Book',
                'fas fa-address-card' => 'Address Card',
                'fas fa-user-friends' => 'User Friends',
                'fas fa-user-plus' => 'User Plus',
                'fas fa-user-minus' => 'User Minus',
                'fas fa-user-times' => 'User Times',
                'fas fa-user-check' => 'User Check',
                'fas fa-user-clock' => 'User Clock',
                'fas fa-user-cog' => 'User Cog',
                'fas fa-user-edit' => 'User Edit',
                'fas fa-user-graduate' => 'User Graduate',
                'fas fa-user-injured' => 'User Injured',
                'fas fa-user-lock' => 'User Lock',
                'fas fa-user-md' => 'User MD',
                'fas fa-user-ninja' => 'User Ninja',
                'fas fa-user-nurse' => 'User Nurse',
                'fas fa-user-secret' => 'User Secret',
                'fas fa-user-shield' => 'User Shield',
                'fas fa-user-tie' => 'User Tie',
                'fas fa-users-cog' => 'Users Cog',
                'fas fa-user-astronaut' => 'User Astronaut',
                'fas fa-users-slash' => 'Users Slash',
            ],
            'Files & Documents' => [
                'fas fa-file' => 'File',
                'fas fa-file-alt' => 'File Alt',
                'fas fa-folder' => 'Folder',
                'fas fa-folder-open' => 'Folder Open',
                'fas fa-upload' => 'Upload',
                'fas fa-download' => 'Download',
                'fas fa-save' => 'Save',
                'fas fa-print' => 'Print',
                'fas fa-copy' => 'Copy',
                'fas fa-cut' => 'Cut',
                'fas fa-paste' => 'Paste',
                'fas fa-archive' => 'Archive',
                'fas fa-compress' => 'Compress',
                'fas fa-expand' => 'Expand',
                'fas fa-compress-arrows-alt' => 'Compress Arrows',
                'fas fa-expand-arrows-alt' => 'Expand Arrows',
                'fas fa-file-pdf' => 'PDF',
                'fas fa-file-word' => 'Word',
                'fas fa-file-excel' => 'Excel',
            ],
            'Interface & Controls' => [
                'fas fa-plus' => 'Plus',
                'fas fa-minus' => 'Minus',
                'fas fa-edit' => 'Edit',
                'fas fa-trash' => 'Trash',
                'fas fa-eye' => 'Eye',
                'fas fa-eye-slash' => 'Eye Slash',
                'fas fa-search' => 'Search',
                'fas fa-filter' => 'Filter',
                'fas fa-sort' => 'Sort',
                'fas fa-sort-up' => 'Sort Up',
                'fas fa-sort-down' => 'Sort Down',
                'fas fa-sort-alpha-up' => 'Sort Alpha Up',
                'fas fa-sort-alpha-down' => 'Sort Alpha Down',
                'fas fa-sort-numeric-up' => 'Sort Numeric Up',
                'fas fa-sort-numeric-down' => 'Sort Numeric Down',
                'fas fa-toggle-on' => 'Toggle On',
                'fas fa-toggle-off' => 'Toggle Off',
                'fas fa-sliders-h' => 'Sliders',
            ],
            'Navigation & Arrows' => [
                'fas fa-arrow-left' => 'Arrow Left',
                'fas fa-arrow-right' => 'Arrow Right',
                'fas fa-arrow-up' => 'Arrow Up',
                'fas fa-arrow-down' => 'Arrow Down',
                'fas fa-chevron-left' => 'Chevron Left',
                'fas fa-chevron-right' => 'Chevron Right',
                'fas fa-chevron-up' => 'Chevron Up',
                'fas fa-chevron-down' => 'Chevron Down',
                'fas fa-angle-left' => 'Angle Left',
                'fas fa-angle-right' => 'Angle Right',
                'fas fa-angle-up' => 'Angle Up',
                'fas fa-angle-down' => 'Angle Down',
                'fas fa-caret-left' => 'Caret Left',
                'fas fa-caret-right' => 'Caret Right',
                'fas fa-caret-up' => 'Caret Up',
                'fas fa-caret-down' => 'Caret Down',
                'fas fa-undo' => 'Undo',
                'fas fa-redo' => 'Redo',
            ],
            'Status & Alerts' => [
                'fas fa-check' => 'Check',
                'fas fa-check-circle' => 'Check Circle',
                'fas fa-times' => 'Times',
                'fas fa-times-circle' => 'Times Circle',
                'fas fa-exclamation' => 'Exclamation',
                'fas fa-exclamation-triangle' => 'Exclamation Triangle',
                'fas fa-info' => 'Info',
                'fas fa-question' => 'Question',
                'fas fa-question-circle' => 'Question Circle',
                'fas fa-info-circle' => 'Info Circle',
                'fas fa-exclamation-circle' => 'Exclamation Circle',
                'fas fa-ban' => 'Ban',
                'fas fa-lock' => 'Lock',
                'fas fa-unlock' => 'Unlock',
                'fas fa-shield-alt' => 'Shield',
                'fas fa-warning' => 'Warning',
                'fas fa-bug' => 'Bug',
                'fas fa-fire' => 'Fire',
            ],
            'Technology & Devices' => [
                'fas fa-desktop' => 'Desktop',
                'fas fa-laptop' => 'Laptop',
                'fas fa-tablet' => 'Tablet',
                'fas fa-mobile' => 'Mobile',
                'fas fa-server' => 'Server',
                'fas fa-database' => 'Database',
                'fas fa-hdd' => 'Hard Drive',
                'fas fa-memory' => 'Memory',
                'fas fa-microchip' => 'Microchip',
                'fas fa-wifi' => 'WiFi',
                'fas fa-bluetooth' => 'Bluetooth',
                'fas fa-usb' => 'USB',
                'fas fa-plug' => 'Plug',
                'fas fa-battery-full' => 'Battery Full',
                'fas fa-battery-half' => 'Battery Half',
                'fas fa-battery-empty' => 'Battery Empty',
                'fas fa-power-off' => 'Power Off',
                'fas fa-play' => 'Play',
                'fas fa-pause' => 'Pause',
                'fas fa-stop' => 'Stop',
            ],
            'Transportation & Travel' => [
                'fas fa-car' => 'Car',
                'fas fa-truck' => 'Truck',
                'fas fa-motorcycle' => 'Motorcycle',
                'fas fa-bicycle' => 'Bicycle',
                'fas fa-plane' => 'Plane',
                'fas fa-helicopter' => 'Helicopter',
                'fas fa-ship' => 'Ship',
                'fas fa-train' => 'Train',
                'fas fa-bus' => 'Bus',
                'fas fa-taxi' => 'Taxi',
                'fas fa-ambulance' => 'Ambulance',
                'fas fa-fire-truck' => 'Fire Truck',
                'fas fa-scooter' => 'Scooter',
                'fas fa-walking' => 'Walking',
                'fas fa-running' => 'Running',
                'fas fa-swimmer' => 'Swimmer',
                'fas fa-skiing' => 'Skiing',
            ],
            'Food & Health' => [
                'fas fa-utensils' => 'Utensils',
                'fas fa-coffee' => 'Coffee',
                'fas fa-pizza-slice' => 'Pizza',
                'fas fa-hamburger' => 'Hamburger',
                'fas fa-apple-alt' => 'Apple',
                'fas fa-lemon' => 'Lemon',
                'fas fa-carrot' => 'Carrot',
                'fas fa-bread-slice' => 'Bread',
                'fas fa-egg' => 'Egg',
                'fas fa-fish' => 'Fish',
                'fas fa-drumstick-bite' => 'Chicken',
                'fas fa-ice-cream' => 'Ice Cream',
                'fas fa-candy-cane' => 'Candy',
                'fas fa-lollipop' => 'Lollipop',
                'fas fa-cookie' => 'Cookie',
                'fas fa-cake' => 'Cake',
                'fas fa-birthday-cake' => 'Birthday Cake',
                'fas fa-wine-glass' => 'Wine Glass',
                'fas fa-beer' => 'Beer',
            ],
            'Sports & Recreation' => [
                'fas fa-futbol' => 'Football',
                'fas fa-basketball-ball' => 'Basketball',
                'fas fa-baseball-ball' => 'Baseball',
                'fas fa-volleyball-ball' => 'Volleyball',
                'fas fa-table-tennis' => 'Table Tennis',
                'fas fa-golf-ball' => 'Golf Ball',
                'fas fa-hockey-puck' => 'Hockey Puck',
                'fas fa-bowling-ball' => 'Bowling Ball',
                'fas fa-dumbbell' => 'Dumbbell',
                'fas fa-running' => 'Running',
                'fas fa-swimmer' => 'Swimmer',
                'fas fa-skiing' => 'Skiing',
                'fas fa-snowboarding' => 'Snowboarding',
                'fas fa-skating' => 'Skating',
                'fas fa-bicycle' => 'Bicycle',
                'fas fa-motorcycle' => 'Motorcycle',
                'fas fa-hiking' => 'Hiking',
                'fas fa-camping' => 'Camping',
            ],
            'Weather & Nature' => [
                'fas fa-sun' => 'Sun',
                'fas fa-moon' => 'Moon',
                'fas fa-cloud' => 'Cloud',
                'fas fa-cloud-rain' => 'Cloud Rain',
                'fas fa-cloud-sun' => 'Cloud Sun',
                'fas fa-cloud-moon' => 'Cloud Moon',
                'fas fa-snowflake' => 'Snowflake',
                'fas fa-umbrella' => 'Umbrella',
                'fas fa-thermometer-half' => 'Thermometer',
                'fas fa-wind' => 'Wind',
                'fas fa-bolt' => 'Lightning',
                'fas fa-tree' => 'Tree',
                'fas fa-leaf' => 'Leaf',
                'fas fa-seedling' => 'Seedling',
                'fas fa-flower' => 'Flower',
                'fas fa-mountain' => 'Mountain',
                'fas fa-water' => 'Water',
                'fas fa-fire' => 'Fire',
            ],
            'Education & Learning' => [
                'fas fa-graduation-cap' => 'Graduation Cap',
                'fas fa-school' => 'School',
                'fas fa-university' => 'University',
                'fas fa-book' => 'Book',
                'fas fa-book-open' => 'Book Open',
                'fas fa-book-reader' => 'Book Reader',
                'fas fa-chalkboard' => 'Chalkboard',
                'fas fa-chalkboard-teacher' => 'Chalkboard Teacher',
                'fas fa-pencil-alt' => 'Pencil Alt',
                'fas fa-pen' => 'Pen',
                'fas fa-pen-fancy' => 'Pen Fancy',
                'fas fa-pen-nib' => 'Pen Nib',
                'fas fa-pen-square' => 'Pen Square',
                'fas fa-pencil-ruler' => 'Pencil Ruler',
                'fas fa-ruler' => 'Ruler',
                'fas fa-ruler-combined' => 'Ruler Combined',
                'fas fa-ruler-horizontal' => 'Ruler Horizontal',
                'fas fa-ruler-vertical' => 'Ruler Vertical',
                'fas fa-calculator' => 'Calculator',
                'fas fa-microscope' => 'Microscope',
                'fas fa-flask' => 'Flask',
                'fas fa-atom' => 'Atom',
                'fas fa-dna' => 'DNA',
                'fas fa-vial' => 'Vial',
                'fas fa-vials' => 'Vials',
                'fas fa-test-tube' => 'Test Tube',
                'fas fa-test-tube-alt' => 'Test Tube Alt',
                'fas fa-mortar-board' => 'Mortar Board',
                'fas fa-certificate' => 'Certificate',
                'fas fa-medal' => 'Medal',
                'fas fa-trophy' => 'Trophy',
                'fas fa-award' => 'Award',
                'fas fa-ribbon' => 'Ribbon',
                'fas fa-star' => 'Star',
                'fas fa-star-half' => 'Star Half',
                'fas fa-star-half-alt' => 'Star Half Alt',
                'fas fa-thumbs-up' => 'Thumbs Up',
                'fas fa-thumbs-down' => 'Thumbs Down',
                'fas fa-heart' => 'Heart',
                'fas fa-heart-broken' => 'Heart Broken',
                'fas fa-smile' => 'Smile',
                'fas fa-frown' => 'Frown',
                'fas fa-meh' => 'Meh',
                'fas fa-grin' => 'Grin',
                'fas fa-grin-beam' => 'Grin Beam',
                'fas fa-grin-beam-sweat' => 'Grin Beam Sweat',
                'fas fa-grin-hearts' => 'Grin Hearts',
                'fas fa-grin-squint' => 'Grin Squint',
                'fas fa-grin-squint-tears' => 'Grin Squint Tears',
                'fas fa-grin-stars' => 'Grin Stars',
                'fas fa-grin-tears' => 'Grin Tears',
                'fas fa-grin-tongue' => 'Grin Tongue',
                'fas fa-grin-tongue-squint' => 'Grin Tongue Squint',
                'fas fa-grin-tongue-wink' => 'Grin Tongue Wink',
                'fas fa-grin-wink' => 'Grin Wink',
                'fas fa-kiss' => 'Kiss',
                'fas fa-kiss-beam' => 'Kiss Beam',
                'fas fa-kiss-wink-heart' => 'Kiss Wink Heart',
                'fas fa-laugh' => 'Laugh',
                'fas fa-laugh-beam' => 'Laugh Beam',
                'fas fa-laugh-squint' => 'Laugh Squint',
                'fas fa-laugh-wink' => 'Laugh Wink',
                'fas fa-sad-cry' => 'Sad Cry',
                'fas fa-sad-tear' => 'Sad Tear',
                'fas fa-smile-beam' => 'Smile Beam',
                'fas fa-smile-wink' => 'Smile Wink',
                'fas fa-surprise' => 'Surprise',
                'fas fa-tired' => 'Tired',
                'fas fa-angry' => 'Angry',
                'fas fa-dizzy' => 'Dizzy',
                'fas fa-flushed' => 'Flushed',
                'fas fa-grimace' => 'Grimace',
                'fas fa-grin-alt' => 'Grin Alt',
            ]
        ];
    }
}
