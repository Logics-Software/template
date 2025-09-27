<?php
$content = '
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
        <div class="card shadow">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <h2 class="fw-bold">Welcome Back</h2>
                    <p class="text-muted">Sign in to your account</p>
                </div>

                <form method="POST" action="' . APP_URL . '/login" id="loginForm">
                    <input type="hidden" name="_token" value="' . $csrf_token . '">
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember">
                        <label class="form-check-label" for="remember">
                            Remember me
                        </label>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-box-arrow-in-right me-2"></i>Sign In
                        </button>
                    </div>
                </form>

                <div class="text-center mt-4">
                    <p class="text-muted">
                        Don\'t have an account? 
                        <a href="' . APP_URL . '/register" class="text-decoration-none">Sign up here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
';
?>
