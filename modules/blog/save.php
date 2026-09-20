<?php
// modules/blog/save.php — Handler Tambah/Edit Artikel
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/helper.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('?module=blog');
}

$id           = (int)($_POST['id'] ?? 0);
$isEdit       = $id > 0;

$judul        = trim($_POST['judul'] ?? '');
$konten       = trim($_POST['konten'] ?? '');
$kategoriId   = (int)($_POST['kategori_id'] ?? 0);
$tags         = trim($_POST['tags'] ?? '');
$status       = $_POST['status'] ?? 'draft';

// Validasi
if ($judul === '' || $konten === '') {
    setFlash('danger', 'Judul dan konten wajib diisi.');
    redirect($isEdit ? '?module=blog&action=edit&id=' . $id : '?module=blog&action=create');
}

if (!in_array($status, ['draft', 'publish'])) {
    $status = 'draft';
}

// Kalau kategori kosong → NULL
$kategoriIdDb = ($kategoriId > 0) ? $kategoriId : null;

// Slug unik
$slug = slugify($judul);
$slugBase = $slug;
$n = 1;
try {
    while (true) {
        $stmt = db()->prepare("SELECT id FROM blog WHERE slug = :slug AND id != :id LIMIT 1");
        $stmt->execute([':slug' => $slug, ':id' => $id]);
        if (!$stmt->fetch()) break;
        $slug = $slugBase . '-' . $n++;
    }
} catch (Exception $e) {
    setFlash('danger', 'Error cek slug: ' . $e->getMessage());
    redirect('?module=blog');
}

// Handle upload cover
$cover = $_POST['cover_lama'] ?? '';
if (!empty($_FILES['cover']['name'])) {
    $result = uploadImage($_FILES['cover'], 'blog');
    if ($result['success']) {
        if ($isEdit && !empty($_POST['cover_lama'])) {
            deleteUpload($_POST['cover_lama']);
        }
        $cover = $result['filename'];
    } else {
        setFlash('warning', 'Upload cover gagal: ' . $result['message']);
    }
}

// Simpan
try {
    if ($isEdit) {
        $sql = "UPDATE blog SET
                    judul = :judul,
                    slug = :slug,
                    cover = :cover,
                    konten = :konten,
                    kategori_id = :kat,
                    tags = :tags,
                    status = :status
                WHERE id = :id";
        $params = [
            ':judul'  => $judul,
            ':slug'   => $slug,
            ':cover'  => $cover,
            ':konten' => $konten,
            ':kat'    => $kategoriIdDb,
            ':tags'   => $tags,
            ':status' => $status,
            ':id'     => $id,
        ];
        db()->prepare($sql)->execute($params);
        setFlash('success', 'Artikel berhasil diupdate!');
    } else {
        $sql = "INSERT INTO blog
                    (judul, slug, cover, konten, kategori_id, tags, status, views, created_at)
                VALUES
                    (:judul, :slug, :cover, :konten, :kat, :tags, :status, 0, NOW())";
        $params = [
            ':judul'  => $judul,
            ':slug'   => $slug,
            ':cover'  => $cover,
            ':konten' => $konten,
            ':kat'    => $kategoriIdDb,
            ':tags'   => $tags,
            ':status' => $status,
        ];
        db()->prepare($sql)->execute($params);
        setFlash('success', 'Artikel berhasil ditambahkan!');
    }
} catch (Exception $e) {
    setFlash('danger', 'Gagal menyimpan: ' . $e->getMessage());
    redirect($isEdit ? '?module=blog&action=edit&id=' . $id : '?module=blog&action=create');
}

redirect('?module=blog');