<?php
// portofolio/auth/login.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/helper.php';
require_once __DIR__ . '/middleware.php';

// Jika sudah login, redirect ke dashboard
if (isLoggedIn()) redirect('?module=dashboard');

$flash = getFlash();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Admin | <?= APP_NAME ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <style>
    body {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .login-card {
      max-width: 420px;
      width: 100%;
      border-radius: 16px;
      box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    }
  </style>
</head>
<body>
  <div class="login-card card border-0 p-4">
    <div class="text-center mb-4">
      <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle mb-3" style="width:64px;height:64px;">
        <i class="bi bi-shield-lock fs-3"></i>
      </div>
      <h4 class="fw-bold mb-1">Login Admin</h4>
      <p class="text-muted small mb-0">Masuk untuk mengelola portofolio</p>
    </div>

    <?php if ($flash): ?>
      <div class="alert alert-<?= $flash['type'] ?> py-2 small">
        <?= e($flash['message']) ?>
      </div>
    <?php endif; ?>

    <form action="proses_login.php" method="POST">
      <div class="mb-3">
        <label class="form-label small fw-semibold">Username</label>
        <div class="input-group">
          <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
          <input type="text" name="username" class="form-control" placeholder="username" required autofocus>
        </div>
      </div>
      <div class="mb-4">
        <label class="form-label small fw-semibold">Password</label>
        <div class="input-group">
          <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
          <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
          <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">
            <i class="bi bi-eye" id="eyeIcon"></i>
          </button>
        </div>
      </div>
      <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
        <i class="bi bi-box-arrow-in-right"></i> Masuk
      </button>
    </form>

    <div class="text-center mt-4">
      <a href="<?= BASE_URL ?>" class="text-muted small text-decoration-none">
        <i class="bi bi-arrow-left"></i> Kembali ke website
      </a>
    </div>
  </div>

  <script>
    function togglePassword() {
      const pwd = document.getElementById('password');
      const icon = document.getElementById('eyeIcon');
      if (pwd.type === 'password') {
        pwd.type = 'text';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
      } else {
        pwd.type = 'password';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
      }
    }
  </script>
</body>
</html>