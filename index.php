<?php
// portofolio/index.php
// ============================================================
// FRONT CONTROLLER — Support Portfolio + Blog Subdomain
// ============================================================

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/helper.php';
require_once __DIR__ . '/auth/middleware.php';

// ============================================================
// 1. DETEKSI HOST
// ============================================================
$host = $_SERVER['HTTP_HOST'] ?? '';
$isBlog = (strpos($host, 'blog.') === 0);

define('IS_BLOG', $isBlog);

// ============================================================
// 2. CEK MAINTENANCE
// ============================================================
$currentScript = basename($_SERVER['SCRIPT_NAME'] ?? '');
$skipMaintenance = ['maintenance.php', 'login.php', 'proses_login.php'];

$maintenanceActive = $GLOBALS['maintenance_mode_active'] ?? MAINTENANCE_MODE;

// Cek apakah ini area admin (ada ?module=...)
$isAdminArea = isset($_GET['module']) && $_GET['module'] !== '';

if (
    $maintenanceActive &&
    !isLoggedIn() &&
    !in_array($currentScript, $skipMaintenance) &&
    !$isAdminArea  // ← INI YANG BARU
) {
    // Kalau akses area admin tanpa login → arahkan ke login, bukan maintenance
    if ($isAdminArea) {
        // (tidak akan sampai sini karena !$isAdminArea, tapi biar logis)
    }
    header("Location: " . BASE_URL . "/maintenance.php");
    exit;
}

// Kalau maintenance aktif + akses admin tanpa login → redirect ke login
if (
    $maintenanceActive &&
    !isLoggedIn() &&
    $isAdminArea
) {
    header("Location: " . BASE_URL . "/auth/login.php");
    exit;
}

// ============================================================
// 3. AMBIL PARAMETER
// ============================================================
$page   = $_GET['page']   ?? null;
$module = $_GET['module'] ?? null;
$action = $_GET['action'] ?? 'index';

// Sanitasi
$page   = $page ? preg_replace('/[^a-zA-Z0-9_]/', '', $page) : null;
$module = $module ? preg_replace('/[^a-zA-Z0-9_]/', '', $module) : null;
$action = preg_replace('/[^a-zA-Z0-9_]/', '', $action);

// ============================================================
// 4. DAFTAR HALAMAN PER HOST
// ============================================================
$portfolioPages = [
    'home', 'about',
    'projects', 'project_detail',
    'pendidikan', 'pengalaman',
    'contact'
];

$blogPages = [
    'blog', 'blog_detail', 'blog_kategori'
];

// ============================================================
// 5. REDIRECT OTOMATIS ANTAR DOMAIN
// ============================================================

// --- Kalau di PORTFOLIO tapi akses halaman BLOG → lempar ke subdomain ---
if (!$isBlog && $page && in_array($page, $blogPages)) {
    // Ambil query string selain 'page'
    $params = $_GET;
    unset($params['page']);
    $qs = !empty($params) ? '?' . http_build_query($params) : '';
    
    header("Location: " . BLOG_URL . "/?page=" . urlencode($page) . ($qs ? '&' . http_build_query($params) : ''));
    exit;
}

// --- Kalau di BLOG tapi akses halaman PORTFOLIO → lempar ke domain utama ---
if ($isBlog && $page && in_array($page, $portfolioPages)) {
    $params = $_GET;
    unset($params['page']);
    $qs = !empty($params) ? '&' . http_build_query($params) : '';
    
    header("Location: " . BASE_URL . "/?page=" . urlencode($page) . $qs);
    exit;
}

// --- Kalau di BLOG tanpa parameter → set default blog ---
if ($isBlog && !$page) {
    $page = 'blog';
}

// --- Kalau di PORTFOLIO tanpa parameter → set default home ---
if (!$isBlog && !$page) {
    $page = 'home';
}

// ============================================================
// 6. AREA ADMIN — wajib login (berlaku di kedua host)
// ============================================================
if ($module) {
    requireLogin();

    $file = __DIR__ . "/modules/{$module}/{$action}.php";

    if (!file_exists($file)) {
        http_response_code(404);
        require_once __DIR__ . '/views/errors/404.php';
        exit;
    }

    // ============================================================
    // ACTION YANG BUTUH REDIRECT → jalankan SEBELUM output HTML
    // ============================================================
    $redirectActions = ['save', 'delete', 'store', 'update', 'destroy'];

    if (in_array($action, $redirectActions)) {
        require_once $file;
        exit;  // save.php & delete.php sudah handle redirect sendiri
    }
    require_once __DIR__ . '/views/admin/header.php';
    require_once __DIR__ . '/views/admin/sidebar.php';
    require_once __DIR__ . '/views/admin/topbar.php';
    require_once $file;
    require_once __DIR__ . '/views/admin/footer.php';
    exit;
}

// ============================================================
// 7. AREA PUBLIK — filter halaman sesuai host
// ============================================================
$allowedPages = $isBlog ? $blogPages : $portfolioPages;

if (!in_array($page, $allowedPages)) {
    // Halaman tidak sesuai host → redirect ke home masing-masing
    $fallback = $isBlog ? BLOG_URL . '/?page=blog' : BASE_URL . '/?page=home';
    header("Location: " . $fallback);
    exit;
}

// Tampilkan halaman
$file = __DIR__ . "/views/public/{$page}.php";

if (file_exists($file)) {
    require_once __DIR__ . '/views/layout/header.php';
    require_once $file;
    require_once __DIR__ . '/views/layout/footer.php';
} else {
    http_response_code(404);
    require_once __DIR__ . '/views/errors/404.php';
}
exit;