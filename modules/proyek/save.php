<?php
// modules/proyek/save.php — Handler Tambah/Edit Proyek
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/helper.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('?module=proyek');
}

$id       = (int)($_POST['id'] ?? 0);
$isEdit   = $id > 0;

// Ambil input
$judul             = trim($_POST['judul'] ?? '');
$deskripsiSingkat  = trim($_POST['deskripsi_singkat'] ?? '');
$deskripsiLengkap  = trim($_POST['deskripsi_lengkap'] ?? '');
$techStack         = trim($_POST['tech_stack'] ?? '');
$linkDemo          = trim($_POST['link_demo'] ?? '');
$linkGithub        = trim($_POST['link_github'] ?? '');
$status            = $_POST['status'] ?? 'draft';

// Validasi
if ($judul === '' || $deskripsiSingkat === '') {
    setFlash('danger', 'Judul dan deskripsi singkat wajib diisi.');
    redirect($isEdit ? '?module=proyek&action=edit&id=' . $id : '?module=proyek&action=create');
}

if (!in_array($status, ['draft', 'publish'])) {
    $status = 'draft';
}

// Slug unik
$slug = slugify($judul);
$slugBase = $slug;
$n = 1;
try {
    while (true) {
        $stmt = db()->prepare("SELECT id FROM proyek WHERE slug = :slug AND id != :id LIMIT 1");
        $stmt->execute([':slug' => $slug, ':id' => $id]);
        if (!$stmt->fetch()) break;
        $slug = $slugBase . '-' . $n++;
    }
} catch (Exception $e) {
    setFlash('danger', 'Error saat cek slug: ' . $e->getMessage());
    redirect('?module=proyek');
}

// Handle upload thumbnail
$thumbnail = $_POST['thumbnail_lama'] ?? '';
if (!empty($_FILES['thumbnail']['name'])) {
    $result = uploadImage($_FILES['thumbnail'], 'proyek');
    if ($result['success']) {
        // Hapus thumbnail lama
        if ($isEdit && !empty($_POST['thumbnail_lama'])) {
            deleteUpload($_POST['thumbnail_lama']);
        }
        $thumbnail = $result['filename'];
    } else {
        setFlash('warning', 'Upload thumbnail gagal: ' . $result['message']);
    }
}

// Simpan
try {
    if ($isEdit) {
        $sql = "UPDATE proyek SET
                    judul = :judul,
                    slug = :slug,
                    deskripsi_singkat = :ds,
                    deskripsi_lengkap = :dl,
                    thumbnail = :thumb,
                    tech_stack = :tech,
                    link_demo = :demo,
                    link_github = :gh,
                    status = :status
                WHERE id = :id";
        $params = [
            ':judul'  => $judul,
            ':slug'   => $slug,
            ':ds'     => $deskripsiSingkat,
            ':dl'     => $deskripsiLengkap,
            ':thumb'  => $thumbnail,
            ':tech'   => $techStack,
            ':demo'   => $linkDemo,
            ':gh'     => $linkGithub,
            ':status' => $status,
            ':id'     => $id,
        ];
        db()->prepare($sql)->execute($params);
        setFlash('success', 'Proyek berhasil diupdate!');
    } else {
        $sql = "INSERT INTO proyek
                    (judul, slug, deskripsi_singkat, deskripsi_lengkap,
                     thumbnail, tech_stack, link_demo, link_github, status, created_at)
                VALUES
                    (:judul, :slug, :ds, :dl,
                     :thumb, :tech, :demo, :gh, :status, NOW())";
        $params = [
            ':judul'  => $judul,
            ':slug'   => $slug,
            ':ds'     => $deskripsiSingkat,
            ':dl'     => $deskripsiLengkap,
            ':thumb'  => $thumbnail,
            ':tech'   => $techStack,
            ':demo'   => $linkDemo,
            ':gh'     => $linkGithub,
            ':status' => $status,
        ];
        db()->prepare($sql)->execute($params);
        setFlash('success', 'Proyek berhasil ditambahkan!');
    }
} catch (Exception $e) {
    setFlash('danger', 'Gagal menyimpan: ' . $e->getMessage());
    redirect($isEdit ? '?module=proyek&action=edit&id=' . $id : '?module=proyek&action=create');
}

redirect('?module=proyek');