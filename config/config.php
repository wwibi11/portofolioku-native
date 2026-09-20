<?php
// portofolio/config/config.php
// ============================================================
// AUTO-DETECT ENVIRONMENT (Localhost vs Hosting)
// ============================================================

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host     = $_SERVER['HTTP_HOST'] ?? 'localhost';

// Deteksi localhost
$isLocalhost = (
    $host === 'localhost' ||
    $host === '127.0.0.1' ||
    strpos($host, 'localhost:') === 0 ||
    strpos($host, '127.0.0.1:') === 0 ||
    strpos($host, '.test') !== false ||
    strpos($host, '.local') !== false
);

// ============================================================
// BASE URL (Portfolio)
// ============================================================
if ($isLocalhost) {
    if (strpos($host, '.test') !== false || strpos($host, '.local') !== false) {
        define('BASE_URL', $protocol . '://' . $host);
    } else {
        define('BASE_URL', 'http://localhost/portofolio');
    }
} else {
    if (strpos($host, 'blog.') === 0) {
        define('BASE_URL', 'https://blog.wisnuwb.my.id');
    } else {
        define('BASE_URL', 'https://wisnuwb.my.id');
    }
}

// ============================================================
// BLOG URL
// ============================================================
if ($isLocalhost) {
    define('BLOG_URL', BASE_URL . '/blog');
} else {
    define('BLOG_URL', 'https://blog.wisnuwb.my.id');
}

// ============================================================
// APLIKASI
// ============================================================
define('APP_NAME', 'Wisnu Wibisono');
define('MAINTENANCE_MODE', false);  // Default, akan di-override dari DB

// ============================================================
// DATABASE  ← HARUS DI SINI (sebelum LOAD SETTINGS)
// ============================================================
if ($isLocalhost) {
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'portofolio');
    define('DB_USER', 'root');
    define('DB_PASS', '');
} else {
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'portofolio');
    define('DB_USER', 'xxxx');   // ⬅️ ganti
    define('DB_PASS', 'xxxx');            // ⬅️ ganti
}

// ============================================================
// UPLOAD
// ============================================================
define('UPLOAD_PATH', __DIR__ . '/../assets/uploads/');
define('UPLOAD_URL',  BASE_URL . '/assets/uploads/');
define('MAX_UPLOAD_SIZE', 2 * 1024 * 1024);

// ============================================================
// TIMEZONE & ERROR
// ============================================================
date_default_timezone_set('Asia/Jakarta');
error_reporting(E_ALL);
ini_set('display_errors', $isLocalhost ? 1 : 0);
ini_set('log_errors', 1);

// ============================================================
// SESSION
// ============================================================
if (session_status() === PHP_SESSION_NONE) {
    $cookieDomain = '';
    if (!$isLocalhost && strpos($host, 'wisnuwb.my.id') !== false) {
        $cookieDomain = '.wisnuwb.my.id';
    }

    session_set_cookie_params([
        'domain'   => $cookieDomain ?: null,
        'path'     => '/',
        'secure'   => ($protocol === 'https'),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

// ============================================================
// LOAD SETTINGS DARI DATABASE  ← PALING BAWAH
// ============================================================
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdoSettings = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    $stmt = $pdoSettings->query("SELECT setting_key, setting_value FROM settings");
    $dbSettings = [];
    while ($row = $stmt->fetch()) {
        $dbSettings[$row['setting_key']] = $row['setting_value'];
    }

    $GLOBALS['app_settings'] = $dbSettings;

    if (isset($dbSettings['maintenance_mode'])) {
        $GLOBALS['maintenance_mode_active'] = ($dbSettings['maintenance_mode'] === '1');
    } else {
        $GLOBALS['maintenance_mode_active'] = false;
    }

    $pdoSettings = null;
} catch (Exception $e) {
    $GLOBALS['app_settings'] = [];
    $GLOBALS['maintenance_mode_active'] = MAINTENANCE_MODE;
}