<?php
$content = '
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Settings</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="' . APP_URL . '/settings">
                        <input type="hidden" name="_token" value="' . $csrf_token . '">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="site_name">Site Name</label>
                                    <input type="text" class="form-control" id="site_name" name="site_name" value="Hando PHP MVC">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="site_email">Site Email</label>
                                    <input type="email" class="form-control" id="site_email" name="site_email" value="admin@hando.com">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="timezone">Timezone</label>
                                    <select class="form-control" id="timezone" name="timezone">
                                        <option value="UTC">UTC</option>
                                        <option value="Asia/Jakarta">Asia/Jakarta</option>
                                        <option value="America/New_York">America/New_York</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="language">Language</label>
                                    <select class="form-control" id="language" name="language">
                                        <option value="en">English</option>
                                        <option value="id">Indonesian</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="maintenance_mode">Maintenance Mode</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="maintenance_mode" name="maintenance_mode">
                                <label class="form-check-label" for="maintenance_mode">
                                    Enable maintenance mode
                                </label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Save Settings</button>
                            <a href="' . APP_URL . '/dashboard" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
';
?>
