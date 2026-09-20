<?php
// modules/blog/delete.php — Hapus Artikel
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/helper.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    setFlash('danger', 'ID tidak valid.');
    redirect('?module=blog');
}

try {
    $stmt = db()->prepare("SELECT judul, cover FROM blog WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);
    $artikel = $stmt->fetch();

    if (!$artikel) {
        setFlash('danger', 'Artikel tidak ditemukan.');
        redirect('?module=blog');
    }

    // Hapus cover
    if (!empty($artikel['cover'])) {
        deleteUpload($artikel['cover']);
    }

    // Hapus data
    db()->prepare("DELETE FROM blog WHERE id = :id")->execute([':id' => $id]);
    setFlash('success', 'Artikel "' . $artikel['judul'] . '" berhasil dihapus.');
} catch (Exception $e) {
    setFlash('danger', 'Gagal menghapus: ' . $e->getMessage());
}

redirect('?module=blog');