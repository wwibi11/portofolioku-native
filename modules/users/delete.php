<?php
// modules/users/delete.php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/helper.php';

$id = (int)($_GET['id'] ?? 0);
$currentId = $_SESSION['user']['id'] ?? $_SESSION['user_id'] ?? 0;

if ($id <= 0) {
    setFlash('danger', 'ID tidak valid.');
    redirect('?module=users');
}

// Cegah hapus diri sendiri
if ($id === (int)$currentId) {
    setFlash('danger', 'Tidak bisa hapus akun sendiri.');
    redirect('?module=users');
}

try {
    // Cek user ada & bukan satu-satunya admin
    $stmt = db()->prepare("SELECT nama FROM users WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);
    $user = $stmt->fetch();

    if (!$user) {
        setFlash('danger', 'User tidak ditemukan.');
        redirect('?module=users');
    }

    $total = (int) db()->query("SELECT COUNT(*) FROM users")->fetchColumn();
    if ($total <= 1) {
        setFlash('danger', 'Tidak bisa hapus user terakhir.');
        redirect('?module=users');
    }

    db()->prepare("DELETE FROM users WHERE id = :id")->execute([':id' => $id]);
    setFlash('success', 'User "' . $user['nama'] . '" berhasil dihapus.');
} catch (Exception $e) {
    setFlash('danger', 'Gagal menghapus: ' . $e->getMessage());
}

redirect('?module=users');