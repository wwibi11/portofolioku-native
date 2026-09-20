<?php
// modules/proyek/delete.php — Hapus Proyek
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/helper.php';

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    setFlash('danger', 'ID proyek tidak valid.');
    redirect('?module=proyek');
}

try {
    // Ambil thumbnail dulu
    $stmt = db()->prepare("SELECT thumbnail, judul FROM proyek WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);
    $proyek = $stmt->fetch();

    if (!$proyek) {
        setFlash('danger', 'Proyek tidak ditemukan.');
        redirect('?module=proyek');
    }

    // Hapus thumbnail
    if (!empty($proyek['thumbnail'])) {
        deleteUpload($proyek['thumbnail']);
    }

    // Hapus data
    db()->prepare("DELETE FROM proyek WHERE id = :id")->execute([':id' => $id]);

    setFlash('success', 'Proyek "' . $proyek['judul'] . '" berhasil dihapus.');
} catch (Exception $e) {
    setFlash('danger', 'Gagal menghapus: ' . $e->getMessage());
}

redirect('?module=proyek');