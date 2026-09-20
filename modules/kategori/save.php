<?php
// modules/kategori/save.php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/helper.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('?module=kategori');
}

$id     = (int)($_POST['id'] ?? 0);
$isEdit = $id > 0;
$nama   = trim($_POST['nama'] ?? '');

if ($nama === '') {
    setFlash('danger', 'Nama kategori wajib diisi.');
    redirect($isEdit ? '?module=kategori&action=edit&id=' . $id : '?module=kategori&action=create');
}

$slug = slugify($nama);

// Pastikan slug unik
$slugBase = $slug;
$n = 1;
try {
    while (true) {
        $stmt = db()->prepare("SELECT id FROM kategori WHERE slug = :slug AND id != :id LIMIT 1");
        $stmt->execute([':slug' => $slug, ':id' => $id]);
        if (!$stmt->fetch()) break;
        $slug = $slugBase . '-' . $n++;
    }

    if ($isEdit) {
        $sql = "UPDATE kategori SET nama = :nama, slug = :slug WHERE id = :id";
        db()->prepare($sql)->execute([':nama' => $nama, ':slug' => $slug, ':id' => $id]);
        setFlash('success', 'Kategori berhasil diupdate!');
    } else {
        $sql = "INSERT INTO kategori (nama, slug) VALUES (:nama, :slug)";
        db()->prepare($sql)->execute([':nama' => $nama, ':slug' => $slug]);
        setFlash('success', 'Kategori berhasil ditambahkan!');
    }
} catch (Exception $e) {
    setFlash('danger', 'Gagal menyimpan: ' . $e->getMessage());
    redirect($isEdit ? '?module=kategori&action=edit&id=' . $id : '?module=kategori&action=create');
}

redirect('?module=kategori');