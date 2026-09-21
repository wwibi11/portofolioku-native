<?php
// portofolio/config/helper.php
require_once __DIR__ . '/config.php';

/**
 * Redirect ke URL
 */
function redirect($path) {
    header("Location: " . BASE_URL . "/" . ltrim($path, '/'));
    exit;
}

/**
 * Escape output HTML
 */
function e($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Set flash message
 */
function setFlash($type, $message) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

/**
 * Tampilkan & hapus flash message
 */
function getFlash() {
    if (!isset($_SESSION['flash'])) return null;
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}

/**
 * Buat slug dari judul
 */
function slugify($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    return strtolower($text) ?: 'n-a';
}

/**
 * Format tanggal Indonesia
 */
function tanggalIndo($tanggal, $withDay = false) {
    if (!$tanggal) return '-';
    $hari = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    $bulan = [1=>'Januari','Februari','Maret','April','Mei','Juni',
              'Juli','Agustus','September','Oktober','November','Desember'];
    $ts = strtotime($tanggal);
    $format = date('j', $ts) . ' ' . $bulan[(int)date('n', $ts)] . ' ' . date('Y', $ts);
    if ($withDay) $format = $hari[(int)date('w', $ts)] . ', ' . $format;
    return $format;
}

/**
 * Potong teks
 */
function excerpt($text, $length = 120) {
    $text = strip_tags($text);
    if (strlen($text) <= $length) return $text;
    return substr($text, 0, $length) . '...';
}

/**
 * Upload gambar
 */
function uploadImage($file, $prefix = 'img') {
    if (!isset($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'Tidak ada file atau terjadi error.'];
    }
    if ($file['size'] > MAX_UPLOAD_SIZE) {
        return ['success' => false, 'message' => 'Ukuran file terlalu besar (max 2MB).'];
    }

    $allowed = ['jpg','jpeg','png','webp','gif'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed)) {
        return ['success' => false, 'message' => 'Format file tidak diizinkan.'];
    }

    if (!is_dir(UPLOAD_PATH)) mkdir(UPLOAD_PATH, 0755, true);

    $filename = $prefix . '-' . date('YmdHis') . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
    $target = UPLOAD_PATH . $filename;

    if (move_uploaded_file($file['tmp_name'], $target)) {
        return ['success' => true, 'filename' => $filename];
    }
    return ['success' => false, 'message' => 'Gagal mengupload file.'];
}

/**
 * Hapus file upload
 */
function deleteUpload($filename) {
    if ($filename && file_exists(UPLOAD_PATH . $filename)) {
        return unlink(UPLOAD_PATH . $filename);
    }
    return false;
}

/**
 * Get input POST/GET dengan aman
 */
function input($key, $default = null) {
    return $_POST[$key] ?? $_GET[$key] ?? $default;
}

/* ============================================
   FUNGSI TAMBAHAN UNTUK ASSET URL & APP INFO
   ============================================ */

/**
 * URL aset di folder assets/
 * Contoh: asset('css/style.css') → http://localhost/portofolio/assets/css/style.css
 */
if (!function_exists('asset')) {
    function asset($path) {
        return rtrim(BASE_URL, '/') . '/assets/' . ltrim($path, '/');
    }
}

/**
 * URL file di folder vendor/ (di root project)
 * Contoh: vendor_asset('bootstrap/css/bootstrap.min.css')
 */
if (!function_exists('vendor_asset')) {
    function vendor_asset($path) {
        return rtrim(BASE_URL, '/') . '/vendor/' . ltrim($path, '/');
    }
}

/**
 * URL file upload user
 * Contoh: upload('foto.jpg') → http://localhost/portofolio/assets/uploads/foto.jpg
 */
if (!function_exists('upload')) {
    function upload($filename) {
        return rtrim(BASE_URL, '/') . '/assets/uploads/' . ltrim($filename, '/');
    }
}

/**
 * Ambil nama aplikasi dari settings DB (fallback ke APP_NAME)
 */
if (!function_exists('getAppName')) {
    function getAppName() {
        if (isset($GLOBALS['app_settings']['app_name'])) {
            return $GLOBALS['app_settings']['app_name'];
        }
        return defined('APP_NAME') ? APP_NAME : 'Portfolio';
    }
}

/**
 * Ambil versi aplikasi
 */
if (!function_exists('getAppVersion')) {
    function getAppVersion() {
        return '1.0.0';
    }
}