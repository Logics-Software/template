<?php
$content = '
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <h2 class="fw-bold">Create Account</h2>
                    <p class="text-muted">Join us today</p>
                </div>

                <form method="POST" action="' . APP_URL . '/register" id="registerForm">
                    <input type="hidden" name="_token" value="' . $csrf_token . '">
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-person"></i>
                            </span>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                    </div>

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

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock-fill"></i>
                            </span>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="terms" required>
                        <label class="form-check-label" for="terms">
                            I agree to the <a href="#" class="text-decoration-none">Terms and Conditions</a>
                        </label>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-person-plus me-2"></i>Create Account
                        </button>
                    </div>
                </form>

                <div class="text-center mt-4">
                    <p class="text-muted">
                        Already have an account? 
                        <a href="' . APP_URL . '/login" class="text-decoration-none">Sign in here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
';
?>
