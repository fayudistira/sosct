<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ERP System</title>
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
        }
        
        .login-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
            overflow: hidden;
            max-width: 420px;
            width: 100%;
        }
        
        .login-body {
            padding: 1.75rem;
        }
        
        .login-title {
            text-align: center;
            margin-bottom: 1.25rem;
        }
        
        .login-title h2 {
            color: var(--dark-red);
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 0.25rem;
        }
        
        .login-title p {
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
        
        .btn-login {
            background-color: var(--dark-red);
            color: white;
            border: none;
            padding: 0.75rem;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-login:hover {
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
    <div class="login-card">
        <div class="login-body">
            <div class="login-title">
                <h2><i class="bi bi-speedometer2 me-2"></i>Welcome Back</h2>
                <p>Sign in to access your dashboard</p>
            </div>
            <?php if (session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle me-2"></i><?= session('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif ?>
            
            <?php if (session('message')): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle me-2"></i><?= session('message') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif ?>
            
            <form action="<?= url_to('login') ?>" method="post">
                <?= csrf_field() ?>
                
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="email" name="email" placeholder="Email or Username" value="<?= old('email') ?>" required autofocus>
                    <label for="email"><i class="bi bi-person me-2"></i>Email or Username</label>
                </div>
                
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    <label for="password"><i class="bi bi-lock me-2"></i>Password</label>
                </div>
                
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember" <?= old('remember') ? 'checked' : '' ?>>
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
                
                <button type="submit" class="btn btn-login w-100">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                </button>
            </form>
            
            <div class="text-center mt-3">
                <p class="mb-2">Don't have an account? <a href="<?= url_to('register') ?>" class="back-link">Register here</a></p>
                <a href="<?= base_url('/') ?>" class="back-link">
                    <i class="bi bi-arrow-left me-1"></i>Back to Home
                </a>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
