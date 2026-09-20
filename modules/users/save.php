<?php
// modules/users/save.php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/helper.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('?module=users');
}

$id       = (int)($_POST['id'] ?? 0);
$isEdit   = $id > 0;
$nama     = trim($_POST['nama'] ?? '');
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$confirm  = $_POST['password_confirm'] ?? '';
$role     = $_POST['role'] ?? 'admin';

if ($nama === '' || $username === '') {
    setFlash('danger', 'Nama dan username wajib diisi.');
    redirect($isEdit ? '?module=users&action=edit&id=' . $id : '?module=users&action=create');
}

if (!in_array($role, ['admin', 'editor', 'super_admin'])) {
    $role = 'admin';
}

// Cek username unik
try {
    $stmt = db()->prepare("SELECT id FROM users WHERE username = :u AND id != :id LIMIT 1");
    $stmt->execute([':u' => $username, ':id' => $id]);
    if ($stmt->fetch()) {
        setFlash('danger', 'Username sudah dipakai.');
        redirect($isEdit ? '?module=users&action=edit&id=' . $id : '?module=users&action=create');
    }
} catch (Exception $e) {
    setFlash('danger', 'Error cek username: ' . $e->getMessage());
    redirect('?module=users');
}

// Validasi password
if (!$isEdit && $password === '') {
    setFlash('danger', 'Password wajib diisi untuk user baru.');
    redirect('?module=users&action=create');
}

if ($password !== '' && $password !== $confirm) {
    setFlash('danger', 'Password dan konfirmasi tidak cocok.');
    redirect($isEdit ? '?module=users&action=edit&id=' . $id : '?module=users&action=create');
}

if ($password !== '' && strlen($password) < 6) {
    setFlash('danger', 'Password minimal 6 karakter.');
    redirect($isEdit ? '?module=users&action=edit&id=' . $id : '?module=users&action=create');
}

try {
    if ($isEdit) {
        if ($password !== '') {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET nama=:n, username=:u, role=:r, password=:p WHERE id=:id";
            db()->prepare($sql)->execute([
                ':n' => $nama, ':u' => $username, ':r' => $role,
                ':p' => $hash, ':id' => $id
            ]);
        } else {
            $sql = "UPDATE users SET nama=:n, username=:u, role=:r WHERE id=:id";
            db()->prepare($sql)->execute([
                ':n' => $nama, ':u' => $username, ':r' => $role, ':id' => $id
            ]);
        }
        setFlash('success', 'User berhasil diupdate!');
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (nama, username, password, role, created_at)
                VALUES (:n, :u, :p, :r, NOW())";
        db()->prepare($sql)->execute([
            ':n' => $nama, ':u' => $username, ':p' => $hash, ':r' => $role
        ]);
        setFlash('success', 'User berhasil ditambahkan!');
    }
} catch (Exception $e) {
    setFlash('danger', 'Gagal menyimpan: ' . $e->getMessage());
    redirect($isEdit ? '?module=users&action=edit&id=' . $id : '?module=users&action=create');
}

redirect('?module=users');