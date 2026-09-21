<?php
// modules/profil/hapus_logo.php — Hapus Logo Brand
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/helper.php';

// Ambil logo lama
try {
    $stmt = db()->query("SELECT logo FROM profil WHERE id = 1 LIMIT 1");
    $profil = $stmt->fetch();
} catch (Exception $e) {
    setFlash('danger', 'Gagal memuat data: ' . $e->getMessage());
    redirect('?module=profil');
}

if (!$profil || empty($profil['logo'])) {
    setFlash('warning', 'Belum ada logo untuk dihapus.');
    redirect('?module=profil');
}

// Hapus file fisik
if (file_exists(UPLOAD_PATH . $profil['logo'])) {
    deleteUpload($profil['logo']);
}

// Set kolom logo jadi NULL
try {
    db()->prepare("UPDATE profil SET logo = NULL WHERE id = 1")->execute();
    setFlash('success', 'Logo brand berhasil dihapus. Navbar kembali ke text brand.');
} catch (Exception $e) {
    setFlash('danger', 'Gagal menghapus logo: ' . $e->getMessage());
}

redirect('?module=profil');