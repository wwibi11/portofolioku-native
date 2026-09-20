<?php
// modules/skill/delete.php — Hapus Skill
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/helper.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    setFlash('danger', 'ID skill tidak valid.');
    redirect('?module=skill');
}

try {
    $stmt = db()->prepare("SELECT nama FROM skills WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);
    $skill = $stmt->fetch();

    if (!$skill) {
        setFlash('danger', 'Skill tidak ditemukan.');
        redirect('?module=skill');
    }

    db()->prepare("DELETE FROM skills WHERE id = :id")->execute([':id' => $id]);
    setFlash('success', 'Skill "' . $skill['nama'] . '" berhasil dihapus.');
} catch (Exception $e) {
    setFlash('danger', 'Gagal menghapus: ' . $e->getMessage());
}

redirect('?module=skill');