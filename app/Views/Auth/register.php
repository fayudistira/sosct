<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - ERP System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        :root {
            --dark-red: #8B0000;
            --medium-red: #B22222;
            --light-red: #F5E8E8;
        }
        
        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: linear-gradient(135deg, var(--dark-red) 0%, #6B0000 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }
        
        .register-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
            overflow: hidden;
            max-width: 450px;
            width: 100%;
        }
        
        .register-body {
            padding: 1.75rem;
        }
        
        .register-title {
            text-align: center;
            margin-bottom: 1.25rem;
        }
        
        .register-title h2 {
            color: var(--dark-red);
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 0.25rem;
        }
        
        .register-title p {
            color: #6c757d;
            font-size: 0.85rem;
            margin: 0;
        }
        
        .form-floating > .form-control:focus,
        .form-floating > .form-control:not(:placeholder-shown) {
            border-color: var(--dark-red);
        }
        
        .form-floating > .form-control:focus ~ label,
        .form-floating > .form-control:not(:placeholder-shown) ~ label {
            color: var(--dark-red);
        }
        
        .form-floating > .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(139, 0, 0, 0.25);
        }
        
        .form-floating > label {
            color: #6c757d;
        }
        
        .btn-register {
            background-color: var(--dark-red);
            color: white;
            border: none;
            padding: 0.75rem;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-register:hover {
            background-color: #7a0000;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(139, 0, 0, 0.3);
        }
        

        .back-link {
            color: var(--dark-red);
            text-decoration: none;
            font-weight: 500;
        }
        
        .back-link:hover {
            color: var(--medium-red);
            text-decoration: underline;
        }
        

    </style>
</head>
<body>
    <div class="register-card">
        <div class="register-body">
            <div class="register-title">
                <h2><i class="bi bi-person-plus me-2"></i>Create Account</h2>
                <p>Join us and start your journey</p>
            </div>
            <?php if (session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle me-2"></i><?= session('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif ?>
            
            <?php if (session('errors')): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        <?php foreach (session('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif ?>
            
            <form action="<?= url_to('register') ?>" method="post">
                <?= csrf_field() ?>
                
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="username" name="username" placeholder="Username" value="<?= old('username') ?>" required autofocus>
                    <label for="username"><i class="bi bi-person me-2"></i>Username</label>
                </div>
                
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email Address" value="<?= old('email') ?>" required>
                    <label for="email"><i class="bi bi-envelope me-2"></i>Email Address</label>
                </div>
                
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    <label for="password"><i class="bi bi-lock me-2"></i>Password (min. 8 characters)</label>
                </div>
                
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="password_confirm" name="password_confirm" placeholder="Confirm Password" required>
                    <label for="password_confirm"><i class="bi bi-lock-fill me-2"></i>Confirm Password</label>
                </div>
                
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                    <label class="form-check-label" for="terms">
                        I agree to the <a href="#" class="back-link">Terms and Conditions</a>
                    </label>
                </div>
                
                <button type="submit" class="btn btn-register w-100">
                    <i class="bi bi-person-plus me-2"></i>Create Account
                </button>
            </form>
            
            <div class="text-center mt-3">
                <p class="mb-2">Already have an account? <a href="<?= url_to('login') ?>" class="back-link">Sign in here</a></p>
                <a href="<?= base_url('/') ?>" class="back-link">
                    <i class="bi bi-arrow-left me-1"></i>Back to Home
                </a>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
