<?php
// modules/profil/save.php — Handler Edit Profil
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/helper.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('?module=profil');
}

// Ambil input
$nama            = trim($_POST['nama'] ?? '');
$gelarAkademik   = trim($_POST['gelar_akademik'] ?? '');
$brand1          = strtolower(trim($_POST['brand_1'] ?? ''));
$brand2          = strtolower(trim($_POST['brand_2'] ?? ''));
$gelar           = trim($_POST['gelar'] ?? '');
$bio             = trim($_POST['bio'] ?? '');
$email           = trim($_POST['email'] ?? '');
$telepon         = trim($_POST['telepon'] ?? '');
$whatsapp        = trim($_POST['whatsapp'] ?? '');
$telegram        = trim($_POST['telegram'] ?? '');
$alamat          = trim($_POST['alamat'] ?? '');
$website         = trim($_POST['website'] ?? '');
$github          = trim($_POST['github'] ?? '');
$linkedin        = trim($_POST['linkedin'] ?? '');
$instagram       = trim($_POST['instagram'] ?? '');
$threads         = trim($_POST['threads'] ?? '');
$facebook        = trim($_POST['facebook'] ?? '');
$twitter         = trim($_POST['twitter'] ?? '');
$youtube         = trim($_POST['youtube'] ?? '');
$tiktok          = trim($_POST['tiktok'] ?? '');

if ($nama === '') {
    setFlash('danger', 'Nama wajib diisi.');
    redirect('?module=profil');
}

// Handle upload foto
$foto = $_POST['foto_lama'] ?? '';
if (!empty($_FILES['foto']['name'])) {
    $result = uploadImage($_FILES['foto'], 'profil');
    if (!empty($result['success'])) {
        if (!empty($_POST['foto_lama'])) {
            deleteUpload($_POST['foto_lama']);
        }
        $foto = $result['filename'];
    } else {
        setFlash('warning', 'Upload foto gagal: ' . ($result['message'] ?? 'Unknown'));
    }
}

// Handle upload CV (PDF)
$cvFile = $_POST['cv_lama'] ?? '';
if (!empty($_FILES['cv_file']['name'])) {
    $file = $_FILES['cv_file'];
    $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if ($ext !== 'pdf') {
        setFlash('warning', 'CV harus berformat PDF.');
    } elseif ($file['size'] > 5 * 1024 * 1024) {
        setFlash('warning', 'CV maksimal 5MB.');
    } elseif ($file['error'] !== UPLOAD_ERR_OK) {
        setFlash('warning', 'Gagal upload CV.');
    } else {
        if (!is_dir(UPLOAD_PATH)) mkdir(UPLOAD_PATH, 0755, true);
        $filename = 'cv-' . date('YmdHis') . '-' . bin2hex(random_bytes(4)) . '.pdf';
        if (move_uploaded_file($file['tmp_name'], UPLOAD_PATH . $filename)) {
            if (!empty($_POST['cv_lama'])) deleteUpload($_POST['cv_lama']);
            $cvFile = $filename;
        } else {
            setFlash('warning', 'Gagal memindahkan CV.');
        }
    }
}

// Simpan ke DB
try {
    // Cek apakah baris profil sudah ada
    $exists = (int) db()->query("SELECT COUNT(*) FROM profil WHERE id = 1")->fetchColumn();

    if ($exists) {
        $sql = "UPDATE profil SET
                    nama = :nama,
                    gelar_akademik = :gelar_akademik,
                    brand_1 = :brand1,
                    brand_2 = :brand2,
                    gelar = :gelar,
                    bio = :bio,
                    foto = :foto,
                    email = :email,
                    telepon = :telepon,
                    whatsapp = :wa,
                    telegram = :tg,
                    alamat = :alamat,
                    website = :website,
                    github = :github,
                    linkedin = :linkedin,
                    instagram = :ig,
                    threads = :threads,
                    facebook = :fb,
                    twitter = :tw,
                    youtube = :yt,
                    tiktok = :tt,
                    cv_file = :cv
                WHERE id = 1";
    } else {
        $sql = "INSERT INTO profil
                    (id, nama, gelar_akademik, brand_1, brand_2, gelar, bio, foto,
                     email, telepon, whatsapp, telegram, alamat, website,
                     github, linkedin, instagram, threads, facebook, twitter,
                     youtube, tiktok, cv_file)
                VALUES
                    (1, :nama, :gelar_akademik, :brand1, :brand2, :gelar, :bio, :foto,
                     :email, :telepon, :wa, :tg, :alamat, :website,
                     :github, :linkedin, :ig, :threads, :fb, :tw,
                     :yt, :tt, :cv)";
    }

    $params = [
        ':nama'            => $nama,
        ':gelar_akademik'  => $gelarAkademik,
        ':brand1'          => $brand1,
        ':brand2'          => $brand2,
        ':gelar'           => $gelar,
        ':bio'             => $bio,
        ':foto'            => $foto,
        ':email'           => $email,
        ':telepon'         => $telepon,
        ':wa'              => $whatsapp,
        ':tg'              => $telegram,
        ':alamat'          => $alamat,
        ':website'         => $website,
        ':github'          => $github,
        ':linkedin'        => $linkedin,
        ':ig'              => $instagram,
        ':threads'         => $threads,
        ':fb'              => $facebook,
        ':tw'              => $twitter,
        ':yt'              => $youtube,
        ':tt'              => $tiktok,
        ':cv'              => $cvFile,
    ];

    db()->prepare($sql)->execute($params);
    setFlash('success', 'Profil berhasil disimpan!');

} catch (Exception $e) {
    setFlash('danger', 'Gagal menyimpan profil: ' . $e->getMessage());
}

redirect('?module=profil');