<?php
// portofolio/auth/middleware.php
require_once __DIR__ . '/../config/config.php';

/**
 * Cek apakah user sudah login
 */
function isLoggedIn() {
    return isset($_SESSION['user']['id']) || isset($_SESSION['user_id']);
}

/**
 * Wajib login — kalau belum, redirect ke halaman login
 */
function requireLogin() {
    if (!isLoggedIn()) {
        setFlash('warning', 'Silakan login terlebih dahulu.');
        redirect('auth/login.php');
    }
}

/**
 * Wajib role tertentu
 */
function requireRole($roles = []) {
    requireLogin();
    $role = currentUserRole();
    if (!in_array($role, $roles)) {
        http_response_code(403);
        require_once __DIR__ . '/../views/errors/403.php';
        exit;
    }
}

/**
 * Ambil data user yang sedang login (array)
 * Support 2 pola: nested ($_SESSION['user']) & flat ($_SESSION['user_id'])
 */
function currentUser() {
    // Pola nested (utama)
    if (isset($_SESSION['user'])) {
        return [
            'id'       => $_SESSION['user']['id']       ?? null,
            'nama'     => $_SESSION['user']['nama']     ?? $_SESSION['user']['name'] ?? null,
            'name'     => $_SESSION['user']['name']     ?? $_SESSION['user']['nama'] ?? null,
            'username' => $_SESSION['user']['username'] ?? null,
            'email'    => $_SESSION['user']['email']    ?? null,
            'role'     => $_SESSION['user']['role']     ?? null,
        ];
    }

    // Fallback: pola flat (lama)
    return [
        'id'       => $_SESSION['user_id']  ?? null,
        'nama'     => $_SESSION['nama']     ?? null,
        'name'     => $_SESSION['nama']     ?? null,
        'username' => $_SESSION['username'] ?? null,
        'email'    => $_SESSION['email']    ?? null,
        'role'     => $_SESSION['role']     ?? null,
    ];
}

/**
 * Ambil role user (string/null)
 */
if (!function_exists('currentUserRole')) {
    function currentUserRole() {
        $user = currentUser();
        return $user['role'] ?? null;
    }
}