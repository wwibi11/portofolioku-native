<?php
// modules/skill/save.php — Handler Tambah/Edit Skill
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/helper.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('?module=skill');
}

$id       = (int)($_POST['id'] ?? 0);
$isEdit   = $id > 0;

$nama     = trim($_POST['nama'] ?? '');
$level    = max(0, min(100, (int)($_POST['level'] ?? 0)));
$kategori = trim($_POST['kategori'] ?? '');
$urutan   = (int)($_POST['urutan'] ?? 0);

if ($nama === '') {
    setFlash('danger', 'Nama skill wajib diisi.');
    redirect($isEdit ? '?module=skill&action=edit&id=' . $id : '?module=skill&action=create');
}

try {
    if ($isEdit) {
        $sql = "UPDATE skills SET
                    nama = :nama, level = :level,
                    kategori = :kategori, urutan = :urutan
                WHERE id = :id";
        $params = [
            ':nama' => $nama, ':level' => $level,
            ':kategori' => $kategori, ':urutan' => $urutan,
            ':id' => $id,
        ];
        db()->prepare($sql)->execute($params);
        setFlash('success', 'Skill berhasil diupdate!');
    } else {
        $sql = "INSERT INTO skills (nama, level, kategori, urutan)
                VALUES (:nama, :level, :kategori, :urutan)";
        $params = [
            ':nama' => $nama, ':level' => $level,
            ':kategori' => $kategori, ':urutan' => $urutan,
        ];
        db()->prepare($sql)->execute($params);
        setFlash('success', 'Skill berhasil ditambahkan!');
    }
} catch (Exception $e) {
    setFlash('danger', 'Gagal menyimpan: ' . $e->getMessage());
    redirect($isEdit ? '?module=skill&action=edit&id=' . $id : '?module=skill&action=create');
}

redirect('?module=skill');