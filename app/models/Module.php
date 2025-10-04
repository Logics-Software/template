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
}
