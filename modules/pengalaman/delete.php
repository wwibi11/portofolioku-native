<?php
// modules/pengalaman/delete.php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/helper.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    setFlash('danger', 'ID tidak valid.');
    redirect('?module=pengalaman');
}

try {
    $stmt = db()->prepare("SELECT posisi FROM pengalaman WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);
    $data = $stmt->fetch();

    if (!$data) {
        setFlash('danger', 'Pengalaman tidak ditemukan.');
        redirect('?module=pengalaman');
    }

    db()->prepare("DELETE FROM pengalaman WHERE id = :id")->execute([':id' => $id]);
    setFlash('success', 'Pengalaman "' . $data['posisi'] . '" berhasil dihapus.');
} catch (Exception $e) {
    setFlash('danger', 'Gagal menghapus: ' . $e->getMessage());
}

redirect('?module=pengalaman');