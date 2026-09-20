<?php
// portofolio/auth/logout.php
// ============================================================
// LOGOUT — Hapus session & redirect ke login
// ============================================================

// Load config & helper (butuh setFlash, redirect, BASE_URL)
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/helper.php';

// ============================================================
// 1. Set flash message SEBELUM destroy session
// ============================================================
setFlash('success', 'Anda berhasil logout.');

// ============================================================
// 2. Hapus semua data session
// ============================================================
$_SESSION = [];

// Hapus cookie session (kalau ada)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Destroy session
session_destroy();

// ============================================================
// 3. Mulai session baru (untuk flash message)
// ============================================================
session_start();
setFlash('success', 'Anda berhasil logout.');

// ============================================================
// 4. Redirect ke login
// ============================================================
redirect('auth/login.php');