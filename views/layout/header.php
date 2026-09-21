<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/helper.php';

$profil = db()->query("SELECT * FROM profil WHERE id = 1")->fetch() ?: [];

// Ambil brand dari DB, fallback ke nama
$brand1 = !empty($profil['brand_1']) 
    ? $profil['brand_1'] 
    : strtolower(explode(' ', $profil['nama'] ?? 'nama')[0]);

$brand2 = !empty($profil['brand_2']) 
    ? $profil['brand_2'] 
    : strtolower(explode(' ', $profil['nama'] ?? 'anda')[1] ?? 'anda');

// Deteksi halaman aktif
$currentPage = $_GET['page'] ?? 'home';

// Deteksi apakah sedang di blog
$isBlogPage = (
    (defined('IS_BLOG') && IS_BLOG) ||
    (isset($_GET['page']) && in_array($_GET['page'], ['blog', 'blog_detail', 'blog_kategori']))
);

// URL portfolio (dari blog, harus ke domain utama)
$portfolioBase = $isBlogPage ? 'https://wisnuwb.my.id' : BASE_URL;

function isActive($page, $current) {
    return $page === $current ? 'active fw-semibold' : '';
}

// Helper link menu
function menuLink($page, $portfolioBase) {
    return $portfolioBase . '/?page=' . urlencode($page);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($pageTitle ?? APP_NAME) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>

<!-- ==================== NAVBAR ==================== -->
<nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top border-bottom">
  <div class="container">
    <a class="navbar-brand brand-logo" href="<?= $portfolioBase ?>">
      <?= e($brand1) ?><span class="text-primary"><?= e($brand2) ?></span>
    </a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">

        <li class="nav-item">
          <a class="nav-link <?= isActive('home', $currentPage) ?>"
             href="<?= menuLink('home', $portfolioBase) ?>">Beranda</a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= isActive('about', $currentPage) ?>"
             href="<?= menuLink('about', $portfolioBase) ?>">Tentang Saya</a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= isActive('projects', $currentPage) ?>"
             href="<?= menuLink('projects', $portfolioBase) ?>">Portfolio</a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= isActive('pendidikan', $currentPage) ?>"
             href="<?= menuLink('pendidikan', $portfolioBase) ?>">Pendidikan</a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= isActive('pengalaman', $currentPage) ?>"
             href="<?= menuLink('pengalaman', $portfolioBase) ?>">Pengalaman</a>
        </li>

        <?php if (!$isBlogPage): ?>
        <li class="nav-item">
          <a class="nav-link" href="<?= BLOG_URL ?>">
            Blog <i class="bi bi-box-arrow-up-right" style="font-size: 11px;"></i>
          </a>
        </li>
        <?php endif; ?>

        <li class="nav-item">
          <a class="nav-link <?= isActive('contact', $currentPage) ?>"
             href="<?= menuLink('contact', $portfolioBase) ?>">Kontak</a>
        </li>

      </ul>
    </div>
  </div>
</nav>