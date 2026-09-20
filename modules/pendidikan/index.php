<?php
// modules/pendidikan/index.php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/helper.php';

$search = trim($_GET['q'] ?? '');
$sql = "SELECT * FROM pendidikan WHERE 1=1";
$params = [];

if ($search !== '') {
    $sql .= " AND (institusi LIKE :q OR jurusan LIKE :q)";
    $params[':q'] = '%' . $search . '%';
}
$sql .= " ORDER BY urutan ASC, tahun_mulai DESC";

try {
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    $items = $stmt->fetchAll();
} catch (Exception $e) {
    $items = [];
    setFlash('danger', 'Gagal memuat: ' . $e->getMessage());
}
?>

<div class="container-fluid px-2 px-md-4">

    <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-3">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-graduation-cap" style="color: #7c3aed;"></i>
                Manajemen Pendidikan
            </h1>
            <p class="mb-0 text-muted small">
                Total: <strong><?= count($items) ?></strong> riwayat pendidikan.
            </p>
        </div>
        <a href="?module=pendidikan&action=create"
           class="btn btn-sm mt-2 mt-sm-0"
           style="background: #7c3aed; color: #fff; border-radius: 8px;">
            <i class="fas fa-plus-circle mr-1"></i> Tambah Pendidikan
        </a>
    </div>

    <?php if ($flash = getFlash()): ?>
    <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show"
         style="border-radius: 10px;">
        <?= htmlspecialchars($flash['message']) ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php endif; ?>

    <div class="card shadow mb-3" style="border-radius: 10px;">
        <div class="card-body py-2">
            <form method="get" class="form-inline row">
                <input type="hidden" name="module" value="pendidikan">
                <div class="col-md-6 mb-2 mb-md-0">
                    <input type="text" name="q" class="form-control form-control-sm w-100"
                           placeholder="Cari institusi / jurusan..."
                           value="<?= htmlspecialchars($search) ?>">
                </div>
                <div class="col-md-6">
                    <button type="submit" class="btn btn-sm btn-primary"
                            style="background: #7c3aed; border-color: #7c3aed; border-radius: 6px;">
                        <i class="fas fa-search"></i> Cari
                    </button>
                    <a href="?module=pendidikan" class="btn btn-sm btn-outline-secondary"
                       style="border-radius: 6px;">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow" style="border-radius: 10px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" style="font-size: 13px;">
                    <thead style="background: #f8f9fc;">
                        <tr>
                            <th class="pl-3" style="width: 60px;">No</th>
                            <th style="width: 80px;">Jenjang</th>
                            <th>Institusi / Jurusan</th>
                            <th style="width: 150px;">Tahun</th>
                            <th style="width: 80px;" class="text-center">Urutan</th>
                            <th style="width: 150px;" class="text-center pr-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($items)): ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="fas fa-graduation-cap fa-3x d-block mb-3" style="color: #cbd5e1;"></i>
                                    <div class="mb-2">Belum ada riwayat pendidikan.</div>
                                    <a href="?module=pendidikan&action=create" class="btn btn-sm"
                                       style="background: #7c3aed; color: #fff; border-radius: 6px;">
                                        <i class="fas fa-plus"></i> Tambah Pendidikan
                                    </a>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($items as $i => $p): ?>
                            <tr>
                                <td class="pl-3 align-middle"><?= $i + 1 ?></td>
                                <td class="align-middle">
                                    <span class="badge"
                                          style="background: #ede9fe; color: #7c3aed; font-size: 11px;">
                                        <?= htmlspecialchars($p['jenjang']) ?>
                                    </span>
                                </td>
                                <td class="align-middle">
                                    <div class="font-weight-bold" style="color: #1a2634;">
                                        <?= htmlspecialchars($p['institusi']) ?>
                                    </div>
                                    <div class="small text-muted">
                                        <?= htmlspecialchars($p['jurusan']) ?>
                                    </div>
                                </td>
                                <td class="align-middle" style="font-size: 12px;">
                                    <?= (int)$p['tahun_mulai'] ?>
                                    <?php if (!empty($p['tahun_selesai'])): ?>
                                        — <?= (int)$p['tahun_selesai'] ?>
                                    <?php else: ?>
                                        — <span class="badge badge-success" style="font-size: 10px;">Sekarang</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center align-middle"><?= (int)$p['urutan'] ?></td>
                                <td class="text-center align-middle pr-3">
                                    <a href="?module=pendidikan&action=edit&id=<?= (int)$p['id'] ?>"
                                       class="btn btn-sm btn-outline-primary"
                                       style="font-size: 11px; border-radius: 6px;">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="?module=pendidikan&action=delete&id=<?= (int)$p['id'] ?>"
                                       class="btn btn-sm btn-outline-danger"
                                       style="font-size: 11px; border-radius: 6px;"
                                       onclick="return confirm('Yakin hapus &quot;<?= htmlspecialchars($p['institusi'], ENT_QUOTES) ?>&quot;?');">
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