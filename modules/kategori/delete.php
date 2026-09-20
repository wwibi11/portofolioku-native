<?php
// modules/kategori/delete.php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/helper.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    setFlash('danger', 'ID tidak valid.');
    redirect('?module=kategori');
}

try {
    $stmt = db()->prepare("SELECT nama FROM kategori WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);
    $data = $stmt->fetch();

    if (!$data) {
        setFlash('danger', 'Kategori tidak ditemukan.');
        redirect('?module=kategori');
    }

    // Cek apakah masih dipakai blog
    $stmt = db()->prepare("SELECT COUNT(*) FROM blog WHERE kategori_id = :id");
    $stmt->execute([':id' => $id]);
    $usedCount = (int)$stmt->fetchColumn();

    if ($usedCount > 0) {
        setFlash('warning', 'Kategori "' . $data['nama'] . '" masih dipakai di '
            . $usedCount . ' artikel. Pindahkan artikel dulu.');
        redirect('?module=kategori');
    }

    db()->prepare("DELETE FROM kategori WHERE id = :id")->execute([':id' => $id]);
    setFlash('success', 'Kategori "' . $data['nama'] . '" berhasil dihapus.');
} catch (Exception $e) {
    setFlash('danger', 'Gagal menghapus: ' . $e->getMessage());
}

redirect('?module=kategori');