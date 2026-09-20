<?php
// modules/pesan/index.php — Inbox Pesan
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/helper.php';

// Filter
$search = trim($_GET['q'] ?? '');
$status = $_GET['status'] ?? '';

// Query
$sql = "SELECT * FROM pesan WHERE 1=1";
$params = [];

if ($search !== '') {
    $sql .= " AND (nama LIKE :q OR email LIKE :q OR subjek LIKE :q OR pesan LIKE :q)";
    $params[':q'] = '%' . $search . '%';
}
if ($status !== '' && in_array($status, ['belum', 'sudah'])) {
    $sql .= " AND status = :status";
    $params[':status'] = $status;
}
$sql .= " ORDER BY 
            CASE WHEN status = 'belum' THEN 0 ELSE 1 END,
            created_at DESC";

try {
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    $items = $stmt->fetchAll();
} catch (Exception $e) {
    $items = [];
    setFlash('danger', 'Gagal memuat: ' . $e->getMessage());
}

// Statistik
$totalPesan = 0;
$totalBelum = 0;
try {
    $totalPesan = (int) db()->query("SELECT COUNT(*) FROM pesan")->fetchColumn();
    $totalBelum = (int) db()->query("SELECT COUNT(*) FROM pesan WHERE status = 'belum'")->fetchColumn();
} catch (Exception $e) {}
?>

<div class="container-fluid px-2 px-md-4">

    <!-- HEADER -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-3">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-envelope" style="color: #7c3aed;"></i>
                Pesan Masuk
                <?php if ($totalBelum > 0): ?>
                    <span class="badge badge-danger ml-2" style="font-size: 12px; vertical-align: middle;">
                        <?= $totalBelum ?> baru
                    </span>
                <?php endif; ?>
            </h1>
            <p class="mb-0 text-muted small">
                Total: <strong><?= $totalPesan ?></strong> pesan ·
                <strong><?= $totalBelum ?></strong> belum dibaca
            </p>
        </div>
    </div>

    <!-- FLASH -->
    <?php if ($flash = getFlash()): ?>
    <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show"
         style="border-radius: 10px;">
        <?= htmlspecialchars($flash['message']) ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php endif; ?>

    <!-- FILTER -->
    <div class="card shadow mb-3" style="border-radius: 10px;">
        <div class="card-body py-2">
            <form method="get" class="form-inline row">
                <input type="hidden" name="module" value="pesan">
                <div class="col-md-5 mb-2 mb-md-0">
                    <input type="text" name="q" class="form-control form-control-sm w-100"
                           placeholder="Cari nama, email, subjek..."
                           value="<?= htmlspecialchars($search) ?>">
                </div>
                <div class="col-md-3 mb-2 mb-md-0">
                    <select name="status" class="form-control form-control-sm w-100">
                        <option value="">Semua Status</option>
                        <option value="belum" <?= $status === 'belum' ? 'selected' : '' ?>>Belum Dibaca</option>
                        <option value="sudah" <?= $status === 'sudah' ? 'selected' : '' ?>>Sudah Dibaca</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-sm btn-primary"
                            style="background: #7c3aed; border-color: #7c3aed; border-radius: 6px;">
                        <i class="fas fa-search"></i> Cari
                    </button>
                    <a href="?module=pesan" class="btn btn-sm btn-outline-secondary"
                       style="border-radius: 6px;">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- TABEL PESAN -->
    <div class="card shadow" style="border-radius: 10px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" style="font-size: 13px;">
                    <thead style="background: #f8f9fc;">
                        <tr>
                            <th class="pl-3" style="width: 50px;"></th>
                            <th style="width: 180px;">Pengirim</th>
                            <th>Subjek</th>
                            <th style="width: 100px;" class="text-center">Status</th>
                            <th style="width: 150px;" class="text-center">Tanggal</th>
                            <th style="width: 150px;" class="text-center pr-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($items)): ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="fas fa-inbox fa-3x d-block mb-3" style="color: #cbd5e1;"></i>
                                    <div class="mb-2">
                                        <?= $search !== '' || $status !== '' 
                                            ? 'Tidak ada pesan yang cocok.' 
                                            : 'Belum ada pesan masuk.' ?>
                                    </div>
                                    <div class="small text-muted">
                                        Pesan akan muncul di sini setelah pengunjung mengisi form kontak.
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($items as $p): 
                                $isUnread = $p['status'] === 'belum';
                            ?>
                            <tr style="<?= $isUnread ? 'background: #fef9f3;' : '' ?>">
                                <td class="pl-3 align-middle text-center">
                                    <?php if ($isUnread): ?>
                                        <span style="display: inline-block; width: 8px; height: 8px;
                                                     background: #7c3aed; border-radius: 50%;"
                                              title="Belum dibaca"></span>
                                    <?php else: ?>
                                        <i class="fas fa-check-circle" style="color: #cbd5e1; font-size: 12px;"></i>
                                    <?php endif; ?>
                                </td>
                                <td class="align-middle">
                                    <div class="font-weight-bold" style="color: #1a2634;
                                        <?= $isUnread ? 'font-weight: 700;' : '' ?>">
                                        <?= htmlspecialchars($p['nama']) ?>
                                    </div>
                                    <div class="small text-muted">
                                        <i class="fas fa-envelope"></i>
                                        <?= htmlspecialchars($p['email']) ?>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <a href="?module=pesan&action=detail&id=<?= (int)$p['id'] ?>"
                                       style="color: <?= $isUnread ? '#7c3aed' : '#1a2634' ?>;
                                              text-decoration: none;
                                              <?= $isUnread ? 'font-weight: 700;' : '' ?>">
                                        <?= htmlspecialchars($p['subjek'] ?: '(Tanpa Subjek)') ?>
                                    </a>
                                    <div class="small text-muted" style="max-width: 400px;
                                         white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        <?= htmlspecialchars(excerpt($p['pesan'], 80)) ?>
                                    </div>
                                </td>
                                <td class="text-center align-middle">
                                    <?php if ($isUnread): ?>
                                        <span class="badge badge-warning" style="font-size: 10px;">Baru</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary" style="font-size: 10px;">Dibaca</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center align-middle" style="font-size: 12px;">
                                    <?= date('d M Y', strtotime($p['created_at'])) ?><br>
                                    <span class="text-muted"><?= date('H:i', strtotime($p['created_at'])) ?></span>
                                </td>
                                <td class="text-center align-middle pr-3">
                                    <a href="?module=pesan&action=detail&id=<?= (int)$p['id'] ?>"
                                       class="btn btn-sm btn-outline-primary"
                                       style="font-size: 11px; border-radius: 6px;"
                                       title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="?module=pesan&action=delete&id=<?= (int)$p['id'] ?>"
                                       class="btn btn-sm btn-outline-danger"
                                       style="font-size: 11px; border-radius: 6px;"
                                       title="Hapus"
                                       onclick="return confirm('Yakin hapus pesan dari &quot;<?= htmlspecialchars($p['nama'], ENT_QUOTES) ?>&quot;?');">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>