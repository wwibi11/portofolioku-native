<?php
// modules/pesan/detail.php — Lihat Detail Pesan
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/helper.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    setFlash('danger', 'ID pesan tidak valid.');
    redirect('?module=pesan');
}

try {
    $stmt = db()->prepare("SELECT * FROM pesan WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);
    $pesan = $stmt->fetch();
} catch (Exception $e) {
    setFlash('danger', 'Gagal memuat: ' . $e->getMessage());
    redirect('?module=pesan');
}

if (!$pesan) {
    setFlash('danger', 'Pesan tidak ditemukan.');
    redirect('?module=pesan');
}

// Tandai sudah dibaca
if ($pesan['status'] === 'belum') {
    try {
        db()->prepare("UPDATE pesan SET status = 'sudah' WHERE id = :id")
            ->execute([':id' => $id]);
        $pesan['status'] = 'sudah';
    } catch (Exception $e) {}
}
?>

<div class="container-fluid px-2 px-md-4">

    <!-- HEADER -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-3">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-envelope-open-text" style="color: #7c3aed;"></i>
                Detail Pesan
            </h1>
            <p class="mb-0 text-muted small">
                Diterima: <?= date('d M Y, H:i', strtotime($pesan['created_at'])) ?> WIB
            </p>
        </div>
        <div>
            <a href="?module=pesan" class="btn btn-sm btn-outline-secondary mt-2 mt-sm-0"
               style="border-radius: 8px;">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Kolom Konten Pesan -->
        <div class="col-lg-8">
            <div class="card shadow mb-3" style="border-radius: 10px;">
                <div class="card-body">

                    <!-- Subjek -->
                    <h5 class="font-weight-bold mb-3" style="color: #1a2634;">
                        <?= htmlspecialchars($pesan['subjek'] ?: '(Tanpa Subjek)') ?>
                    </h5>

                    <!-- Pengirim -->
                    <div class="d-flex align-items-center mb-4 pb-3"
                         style="border-bottom: 1px solid #eef2f7;">
                        <div style="width: 48px; height: 48px; background: #ede9fe;
                                    border-radius: 50%; display: flex; align-items: center;
                                    justify-content: center; color: #7c3aed; font-weight: 700;
                                    font-size: 18px; flex-shrink: 0;">
                            <?= strtoupper(substr($pesan['nama'], 0, 1)) ?>
                        </div>
                        <div class="ml-3">
                            <div class="font-weight-bold" style="color: #1a2634; font-size: 15px;">
                                <?= htmlspecialchars($pesan['nama']) ?>
                            </div>
                            <div class="text-muted" style="font-size: 13px;">
                                <i class="fas fa-envelope"></i>
                                <a href="mailto:<?= htmlspecialchars($pesan['email']) ?>"
                                   style="color: #7c3aed; text-decoration: none;">
                                    <?= htmlspecialchars($pesan['email']) ?>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Isi Pesan -->
                    <div style="color: #1a2634; font-size: 14px; line-height: 1.8;
                                white-space: pre-wrap;"><?= htmlspecialchars($pesan['pesan']) ?></div>

                </div>
            </div>
        </div>

        <!-- Kolom Aksi -->
        <div class="col-lg-4">

            <!-- Info -->
            <div class="card shadow mb-3" style="border-radius: 10px;">
                <div class="card-header py-3 bg-white"
                     style="border-bottom: 1px solid #eef2f7;">
                    <h6 class="m-0 font-weight-bold" style="color: #7c3aed; font-size: 13px;">
                        <i class="fas fa-info-circle"></i> Informasi
                    </h6>
                </div>
                <div class="card-body">

                    <div class="mb-3">
                        <div class="text-muted small">Status</div>
                        <?php if ($pesan['status'] === 'belum'): ?>
                            <span class="badge badge-warning" style="font-size: 11px;">Belum Dibaca</span>
                        <?php else: ?>
                            <span class="badge badge-secondary" style="font-size: 11px;">Sudah Dibaca</span>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <div class="text-muted small">Tanggal Masuk</div>
                        <div class="font-weight-bold" style="font-size: 13px; color: #1a2634;">
                            <?= date('d M Y', strtotime($pesan['created_at'])) ?><br>
                            <span class="text-muted"><?= date('H:i', strtotime($pesan['created_at'])) ?> WIB</span>
                        </div>
                    </div>

                    <div class="mb-0">
                        <div class="text-muted small">Dikirim Ke</div>
                        <div style="font-size: 13px; color: #1a2634;">
                            Form Kontak Website
                        </div>
                    </div>

                </div>
            </div>

            <!-- Aksi -->
            <div class="card shadow" style="border-radius: 10px;">
                <div class="card-body">
                    <a href="mailto:<?= htmlspecialchars($pesan['email']) ?>?subject=Re: <?= urlencode($pesan['subjek'] ?: 'Pesan Anda') ?>"
                       class="btn btn-block mb-2"
                       style="background: #7c3aed; color: #fff; border-radius: 8px;">
                        <i class="fas fa-reply mr-1"></i> Balas via Email
                    </a>
                    <a href="?module=pesan&action=delete&id=<?= (int)$pesan['id'] ?>"
                       class="btn btn-block btn-outline-danger"
                       style="border-radius: 8px;"
                       onclick="return confirm('Yakin hapus pesan ini?');">
                        <i class="fas fa-trash mr-1"></i> Hapus Pesan
                    </a>
                </div>
            </div>

        </div>
    </div>

</div>