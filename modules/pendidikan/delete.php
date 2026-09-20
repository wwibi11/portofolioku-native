<?php
// modules/pendidikan/delete.php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/helper.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    setFlash('danger', 'ID tidak valid.');
    redirect('?module=pendidikan');
}

try {
    $stmt = db()->prepare("SELECT institusi FROM pendidikan WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);
    $data = $stmt->fetch();

    if (!$data) {
        setFlash('danger', 'Data tidak ditemukan.');
        redirect('?module=pendidikan');
    }

    db()->prepare("DELETE FROM pendidikan WHERE id = :id")->execute([':id' => $id]);
    setFlash('success', 'Pendidikan "' . $data['institusi'] . '" berhasil dihapus.');
} catch (Exception $e) {
    setFlash('danger', 'Gagal menghapus: ' . $e->getMessage());
}

redirect('?module=pendidikan');