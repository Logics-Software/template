<?php
/**
 * Module Model - Extended with Menu System
 */
class Module extends Model
{
    protected $table = 'modules';
    protected $fillable = [
        'caption', 'logo', 'link', 'admin', 'manajemen', 'user', 'marketing', 'customer'
    ];
    protected $casts = [
        'admin' => 'boolean',
        'manajemen' => 'boolean',
        'user' => 'boolean',
        'marketing' => 'boolean',
        'customer' => 'boolean'
    ];

    // Legacy methods for backward compatibility
    public function getByRole($role)
    {
        return $this->findAll("{$role} = 1", [], 'caption ASC');
    }

    public function getAdminModules()
    {
        return $this->findAll('admin = 1', [], 'caption ASC');
    }

    public function getManajemenModules()
    {
        return $this->findAll('manajemen = 1', [], 'caption ASC');
    }

    public function getUserModules()
    {
        return $this->findAll('user = 1', [], 'caption ASC');
    }

    public function getMarketingModules()
    {
        return $this->findAll('marketing = 1', [], 'caption ASC');
    }

    public function getCustomerModules()
    {
        return $this->findAll('customer = 1', [], 'caption ASC');
    }

    // Note: Menu system functionality has been moved to MenuItem and MenuGroup models
    // This model now only handles basic module CRUD operations

    public function getAvailableIcons()
    {
        return [
            'General' => [
                'fas fa-home' => 'Home',
                'fas fa-circle' => 'Circle',
                'fas fa-square' => 'Square',
                'fas fa-star' => 'Star',
                'fas fa-heart' => 'Heart',
                'fas fa-check' => 'Check',
                'fas fa-times' => 'Times',
                'fas fa-plus' => 'Plus',
                'fas fa-minus' => 'Minus',
                'fas fa-edit' => 'Edit',
                'fas fa-trash' => 'Trash',
                'fas fa-save' => 'Save',
                'fas fa-print' => 'Print',
                'fas fa-download' => 'Download',
                'fas fa-upload' => 'Upload',
                'fas fa-search' => 'Search',
                'fas fa-filter' => 'Filter',
                'fas fa-sort' => 'Sort',
                'fas fa-refresh' => 'Refresh',
                'fas fa-sync' => 'Sync'
            ],
            'Navigation' => [
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
                'fas fa-angle-down' => 'Angle Down'
            ],
            'Business' => [
                'fas fa-users' => 'Users',
                'fas fa-user' => 'User',
                'fas fa-user-plus' => 'User Plus',
                'fas fa-user-minus' => 'User Minus',
                'fas fa-user-edit' => 'User Edit',
                'fas fa-user-cog' => 'User Cog',
                'fas fa-building' => 'Building',
                'fas fa-briefcase' => 'Briefcase',
                'fas fa-chart-line' => 'Chart Line',
                'fas fa-chart-bar' => 'Chart Bar',
                'fas fa-chart-pie' => 'Chart Pie',
                'fas fa-chart-area' => 'Chart Area',
                'fas fa-dollar-sign' => 'Dollar Sign',
                'fas fa-euro-sign' => 'Euro Sign',
                'fas fa-pound-sign' => 'Pound Sign',
                'fas fa-rupee-sign' => 'Rupee Sign',
                'fas fa-yen-sign' => 'Yen Sign',
                'fas fa-won-sign' => 'Won Sign',
                'fas fa-bitcoin' => 'Bitcoin',
                'fas fa-coins' => 'Coins'
            ],
            'Communication' => [
                'fas fa-envelope' => 'Envelope',
                'fas fa-envelope-open' => 'Envelope Open',
                'fas fa-paper-plane' => 'Paper Plane',
                'fas fa-reply' => 'Reply',
                'fas fa-reply-all' => 'Reply All',
                'fas fa-share' => 'Share',
                'fas fa-share-alt' => 'Share Alt',
                'fas fa-share-square' => 'Share Square',
                'fas fa-comment' => 'Comment',
                'fas fa-comments' => 'Comments',
                'fas fa-comment-dots' => 'Comment Dots',
                'fas fa-comment-alt' => 'Comment Alt',
                'fas fa-comment-alt-check' => 'Comment Alt Check',
                'fas fa-phone' => 'Phone',
                'fas fa-phone-alt' => 'Phone Alt',
                'fas fa-phone-slash' => 'Phone Slash',
                'fas fa-fax' => 'Fax',
                'fas fa-video' => 'Video',
                'fas fa-video-slash' => 'Video Slash',
                'fab fa-whatsapp' => 'WhatsApp',
                'fab fa-telegram' => 'Telegram',
                'fab fa-skype' => 'Skype'
            ],
            'System' => [
                'fas fa-cog' => 'Cog',
                'fas fa-cogs' => 'Cogs',
                'fas fa-wrench' => 'Wrench',
                'fas fa-tools' => 'Tools',
                'fas fa-screwdriver' => 'Screwdriver',
                'fas fa-hammer' => 'Hammer',
                'fas fa-key' => 'Key',
                'fas fa-lock' => 'Lock',
                'fas fa-unlock' => 'Unlock',
                'fas fa-shield-alt' => 'Shield Alt',
                'fas fa-shield-check' => 'Shield Check',
                'fas fa-shield-exclamation' => 'Shield Exclamation',
                'fas fa-shield-times' => 'Shield Times',
                'fas fa-database' => 'Database',
                'fas fa-server' => 'Server',
                'fas fa-desktop' => 'Desktop',
                'fas fa-laptop' => 'Laptop',
                'fas fa-mobile-alt' => 'Mobile Alt',
                'fas fa-tablet-alt' => 'Tablet Alt',
                'fas fa-memory' => 'Memory',
                'fas fa-microchip' => 'Microchip'
            ],
            'Files & Documents' => [
                'fas fa-file' => 'File',
                'fas fa-file-alt' => 'File Alt',
                'fas fa-file-archive' => 'File Archive',
                'fas fa-file-audio' => 'File Audio',
                'fas fa-file-code' => 'File Code',
                'fas fa-file-csv' => 'File CSV',
                'fas fa-file-excel' => 'File Excel',
                'fas fa-file-image' => 'File Image',
                'fas fa-file-pdf' => 'File PDF',
                'fas fa-file-powerpoint' => 'File PowerPoint',
                'fas fa-file-video' => 'File Video',
                'fas fa-file-word' => 'File Word',
                'fas fa-folder' => 'Folder',
                'fas fa-folder-open' => 'Folder Open',
                'fas fa-folder-plus' => 'Folder Plus',
                'fas fa-folder-minus' => 'Folder Minus',
                'fas fa-archive' => 'Archive',
                'fas fa-box' => 'Box',
                'fas fa-boxes' => 'Boxes',
                'fas fa-clipboard' => 'Clipboard',
                'fas fa-clipboard-list' => 'Clipboard List'
            ]
        ];
    }
}
