<?php
// modules/pesan/delete.php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/helper.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    setFlash('danger', 'ID pesan tidak valid.');
    redirect('?module=pesan');
}

try {
    $stmt = db()->prepare("SELECT nama FROM pesan WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);
    $pesan = $stmt->fetch();

    if (!$pesan) {
        setFlash('danger', 'Pesan tidak ditemukan.');
        redirect('?module=pesan');
    }

    db()->prepare("DELETE FROM pesan WHERE id = :id")->execute([':id' => $id]);
    setFlash('success', 'Pesan dari "' . $pesan['nama'] . '" berhasil dihapus.');
} catch (Exception $e) {
    setFlash('danger', 'Gagal menghapus: ' . $e->getMessage());
}

redirect('?module=pesan');