<?php
// modules/pesan/kirim.php — Handler Form Kontak Publik
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/helper.php';

// Cek method POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . BASE_URL . "/?page=contact");
    exit;
}

// Ambil input
$nama   = trim($_POST['nama'] ?? '');
$email  = trim($_POST['email'] ?? '');
$subjek = trim($_POST['subjek'] ?? '');
$pesan  = trim($_POST['pesan'] ?? '');

// Validasi
if ($nama === '' || $email === '' || $pesan === '') {
    setFlash('danger', 'Nama, email, dan pesan wajib diisi.');
    header("Location: " . BASE_URL . "/?page=contact");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    setFlash('danger', 'Format email tidak valid.');
    header("Location: " . BASE_URL . "/?page=contact");
    exit;
}

// Simpan ke database
try {
    $sql = "INSERT INTO pesan (nama, email, subjek, pesan, status, created_at)
            VALUES (:nama, :email, :subjek, :pesan, 'belum', NOW())";

    db()->prepare($sql)->execute([
        ':nama'   => $nama,
        ':email'  => $email,
        ':subjek' => $subjek,
        ':pesan'  => $pesan,
    ]);

    // Versi formal
    setFlash('success', 'Terima kasih, ' . $nama . '. Pesan Anda sudah kami terima dan akan segera direspon.');

    // Versi singkat
    setFlash('success', 'Pesan terkirim! Saya akan segera merespon.');

    // Versi dengan nama
    setFlash('success', 'Halo ' . $nama . ', pesanmu sudah sampai! Saya akan balas via email segera.');
    } catch (Exception $e) {
        setFlash('danger', 'Gagal mengirim pesan: ' . $e->getMessage());
    }

// Redirect balik ke contact
header("Location: " . BASE_URL . "/?page=contact");
exit;