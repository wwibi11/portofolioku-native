<?php
// modules/pengalaman/save.php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/helper.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('?module=pengalaman');
}

$id         = (int)($_POST['id'] ?? 0);
$isEdit     = $id > 0;
$posisi     = trim($_POST['posisi'] ?? '');
$perusahaan = trim($_POST['perusahaan'] ?? '');
$mulai      = $_POST['mulai'] ?? '';
$selesai    = $_POST['selesai'] ?? '';
$deskripsi  = trim($_POST['deskripsi'] ?? '');
$urutan     = (int)($_POST['urutan'] ?? 0);

if ($posisi === '' || $perusahaan === '' || $mulai === '') {
    setFlash('danger', 'Posisi, perusahaan, dan tanggal mulai wajib diisi.');
    redirect($isEdit ? '?module=pengalaman&action=edit&id=' . $id : '?module=pengalaman&action=create');
}

// Kosong → NULL (masih bekerja)
$selesaiDb = ($selesai === '') ? null : $selesai;

try {
    if ($isEdit) {
        $sql = "UPDATE pengalaman SET
                    posisi = :p, perusahaan = :c, mulai = :m,
                    selesai = :s, deskripsi = :d, urutan = :u
                WHERE id = :id";
        $params = [
            ':p' => $posisi, ':c' => $perusahaan, ':m' => $mulai,
            ':s' => $selesaiDb, ':d' => $deskripsi, ':u' => $urutan,
            ':id' => $id,
        ];
        db()->prepare($sql)->execute($params);
        setFlash('success', 'Pengalaman berhasil diupdate!');
    } else {
        $sql = "INSERT INTO pengalaman (posisi, perusahaan, mulai, selesai, deskripsi, urutan)
                VALUES (:p, :c, :m, :s, :d, :u)";
        $params = [
            ':p' => $posisi, ':c' => $perusahaan, ':m' => $mulai,
            ':s' => $selesaiDb, ':d' => $deskripsi, ':u' => $urutan,
        ];
        db()->prepare($sql)->execute($params);
        setFlash('success', 'Pengalaman berhasil ditambahkan!');
    }
} catch (Exception $e) {
    setFlash('danger', 'Gagal menyimpan: ' . $e->getMessage());
    redirect($isEdit ? '?module=pengalaman&action=edit&id=' . $id : '?module=pengalaman&action=create');
}

redirect('?module=pengalaman');