<?php
// modules/pendidikan/save.php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/helper.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('?module=pendidikan');
}

$id            = (int)($_POST['id'] ?? 0);
$isEdit        = $id > 0;
$jenjang       = trim($_POST['jenjang'] ?? '');
$institusi     = trim($_POST['institusi'] ?? '');
$jurusan       = trim($_POST['jurusan'] ?? '');
$tahunMulai    = (int)($_POST['tahun_mulai'] ?? 0);
$tahunSelesai  = $_POST['tahun_selesai'] ?? '';
$deskripsi     = trim($_POST['deskripsi'] ?? '');
$urutan        = (int)($_POST['urutan'] ?? 0);

if ($jenjang === '' || $institusi === '' || $tahunMulai <= 0) {
    setFlash('danger', 'Jenjang, institusi, dan tahun mulai wajib diisi.');
    redirect($isEdit ? '?module=pendidikan&action=edit&id=' . $id : '?module=pendidikan&action=create');
}

$tahunSelesaiDb = ($tahunSelesai === '') ? null : (int)$tahunSelesai;

try {
    if ($isEdit) {
        $sql = "UPDATE pendidikan SET
                    jenjang = :j, institusi = :i, jurusan = :r,
                    tahun_mulai = :tm, tahun_selesai = :ts,
                    deskripsi = :d, urutan = :u
                WHERE id = :id";
        $params = [
            ':j' => $jenjang, ':i' => $institusi, ':r' => $jurusan,
            ':tm' => $tahunMulai, ':ts' => $tahunSelesaiDb,
            ':d' => $deskripsi, ':u' => $urutan,
            ':id' => $id,
        ];
        db()->prepare($sql)->execute($params);
        setFlash('success', 'Pendidikan berhasil diupdate!');
    } else {
        $sql = "INSERT INTO pendidikan
                    (jenjang, institusi, jurusan, tahun_mulai, tahun_selesai, deskripsi, urutan)
                VALUES (:j, :i, :r, :tm, :ts, :d, :u)";
        $params = [
            ':j' => $jenjang, ':i' => $institusi, ':r' => $jurusan,
            ':tm' => $tahunMulai, ':ts' => $tahunSelesaiDb,
            ':d' => $deskripsi, ':u' => $urutan,
        ];
        db()->prepare($sql)->execute($params);
        setFlash('success', 'Pendidikan berhasil ditambahkan!');
    }
} catch (Exception $e) {
    setFlash('danger', 'Gagal menyimpan: ' . $e->getMessage());
    redirect($isEdit ? '?module=pendidikan&action=edit&id=' . $id : '?module=pendidikan&action=create');
}

redirect('?module=pendidikan');