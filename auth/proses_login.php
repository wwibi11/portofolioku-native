<?php
// portofolio/auth/proses_login.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/helper.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('auth/login.php');
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    setFlash('danger', 'Username dan password wajib diisi.');
    redirect('auth/login.php');
}

try {
    $stmt = db()->prepare("SELECT * FROM users WHERE username = :u LIMIT 1");
    $stmt->execute([':u' => $username]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password'])) {
        setFlash('danger', 'Username atau password salah.');
        redirect('auth/login.php');
    }

    // ============================================================
    // SET SESSION (nested + flat, biar kompatibel)
    // ============================================================
    $_SESSION['user'] = [
        'id'       => (int) $user['id'],
        'nama'     => $user['nama'],
        'name'     => $user['nama'],          // alias
        'username' => $user['username'],
        'role'     => $user['role'],
        'email'    => $user['email'] ?? '',
    ];

    // Flat (fallback untuk kode lama)
    $_SESSION['user_id']  = (int) $user['id'];
    $_SESSION['nama']     = $user['nama'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role']     = $user['role'];

    setFlash('success', 'Selamat datang, ' . $user['nama'] . '!');
    redirect('?module=dashboard');

} catch (PDOException $e) {
    setFlash('danger', 'Terjadi kesalahan database: ' . $e->getMessage());
    redirect('auth/login.php');
}