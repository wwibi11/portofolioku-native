<?php
// portofolio/views/errors/404.php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/helper.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>404 - Halaman Tidak Ditemukan | <?= APP_NAME ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <style>
    body {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
    }
    .error-code {
      font-size: 8rem;
      font-weight: 800;
      line-height: 1;
      text-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }
    .error-icon {
      font-size: 4rem;
      opacity: 0.9;
    }
  </style>
</head>
<body>
  <div class="container text-center">
    <i class="bi bi-exclamation-triangle error-icon"></i>
    <div class="error-code">404</div>
    <h2 class="fw-bold mb-3">Halaman Tidak Ditemukan</h2>
    <p class="lead mb-4" style="max-width:500px; margin:0 auto;">
      Maaf, halaman yang Anda cari tidak ada atau sudah dipindahkan.
    </p>

    <div class="d-flex gap-2 justify-content-center flex-wrap">
      <a href="<?= BASE_URL ?>" class="btn btn-light btn-lg">
        <i class="bi bi-house"></i> Kembali ke Home
      </a>
      <a href="javascript:history.back()" class="btn btn-outline-light btn-lg">
        <i class="bi bi-arrow-left"></i> Halaman Sebelumnya
      </a>
    </div>
  </div>
</body>
</html>