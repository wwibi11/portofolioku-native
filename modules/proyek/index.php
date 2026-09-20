<?php
// modules/proyek/index.php — Daftar Proyek
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/helper.php';

// Filter & pencarian
$search = trim($_GET['q'] ?? '');
$status = $_GET['status'] ?? '';

// Build query
$sql = "SELECT * FROM proyek WHERE 1=1";
$params = [];

if ($search !== '') {
    $sql .= " AND (judul LIKE :q OR deskripsi_singkat LIKE :q)";
    $params[':q'] = '%' . $search . '%';
}

if ($status !== '' && in_array($status, ['draft', 'publish'])) {
    $sql .= " AND status = :status";
    $params[':status'] = $status;
}

$sql .= " ORDER BY created_at DESC";

try {
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    $proyekList = $stmt->fetchAll();
} catch (Exception $e) {
    $proyekList = [];
    setFlash('danger', 'Gagal memuat data: ' . $e->getMessage());
}
?>

<div class="container-fluid px-2 px-md-4">

    <!-- HEADER -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-3">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-folder-open" style="color: #7c3aed;"></i>
                Manajemen Proyek
            </h1>
            <p class="mb-0 text-muted small">
                Kelola portofolio proyek Anda.
                Total: <strong><?= count($proyekList) ?></strong> proyek.
            </p>
        </div>
        <a href="?module=proyek&action=create"
           class="btn btn-sm mt-2 mt-sm-0"
           style="background: #7c3aed; color: #fff; border-radius: 8px;">
            <i class="fas fa-plus-circle mr-1"></i> Tambah Proyek
        </a>
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
                <input type="hidden" name="module" value="proyek">
                <div class="col-md-5 mb-2 mb-md-0">
                    <input type="text" name="q" class="form-control form-control-sm w-100"
                           placeholder="Cari judul..."
                           value="<?= htmlspecialchars($search) ?>">
                </div>
                <div class="col-md-3 mb-2 mb-md-0">
                    <select name="status" class="form-control form-control-sm w-100">
                        <option value="">Semua Status</option>
                        <option value="publish" <?= $status === 'publish' ? 'selected' : '' ?>>Publish</option>
                        <option value="draft" <?= $status === 'draft' ? 'selected' : '' ?>>Draft</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-sm btn-primary"
                            style="background: #7c3aed; border-color: #7c3aed; border-radius: 6px;">
                        <i class="fas fa-search"></i> Cari
                    </button>
                    <a href="?module=proyek" class="btn btn-sm btn-outline-secondary"
                       style="border-radius: 6px;">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- TABEL -->
    <div class="card shadow" style="border-radius: 10px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" style="font-size: 13px;">
                    <thead style="background: #f8f9fc;">
                        <tr>
                            <th class="pl-3" style="width: 60px;">No</th>
                            <th style="width: 80px;">Thumbnail</th>
                            <th>Judul</th>
                            <th style="width: 200px;">Tech Stack</th>
                            <th style="width: 90px;" class="text-center">Status</th>
                            <th style="width: 130px;" class="text-center">Tanggal</th>
                            <th style="width: 150px;" class="text-center pr-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($proyekList)): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i class="fas fa-folder-open fa-3x d-block mb-3" style="color: #cbd5e1;"></i>
                                    <div class="mb-2">Belum ada proyek.</div>
                                    <a href="?module=proyek&action=create" class="btn btn-sm"
                                       style="background: #7c3aed; color: #fff; border-radius: 6px;">
                                        <i class="fas fa-plus"></i> Tambah Proyek Pertama
                                    </a>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($proyekList as $i => $p): ?>
                            <tr>
                                <td class="pl-3 align-middle"><?= $i + 1 ?></td>
                                <td class="align-middle">
                                    <?php if (!empty($p['thumbnail']) && file_exists(UPLOAD_PATH . $p['thumbnail'])): ?>
                                        <img src="<?= upload($p['thumbnail']) ?>"
                                             alt="Thumbnail"
                                             style="width: 60px; height: 45px; object-fit: cover; border-radius: 6px;">
                                    <?php else: ?>
                                        <div style="width: 60px; height: 45px; background: #f1f5f9;
                                                    border-radius: 6px; display: flex; align-items: center;
                                                    justify-content: center; color: #cbd5e1;">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="align-middle">
                                    <div class="font-weight-bold" style="color: #1a2634;">
                                        <?= htmlspecialchars($p['judul']) ?>
                                    </div>
                                    <div class="small text-muted" style="max-width: 400px;
                                         white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        <?= htmlspecialchars($p['deskripsi_singkat'] ?: '-') ?>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <?php if (!empty($p['tech_stack'])):
                                        $techs = array_map('trim', explode(',', $p['tech_stack']));
                                        foreach (array_slice($techs, 0, 3) as $tech): ?>
                                            <span class="badge"
                                                  style="background: #ede9fe; color: #7c3aed; font-size: 10px; margin: 1px;">
                                                <?= htmlspecialchars($tech) ?>
                                            </span>
                                        <?php endforeach;
                                        if (count($techs) > 3): ?>
                                            <span class="badge badge-secondary" style="font-size: 10px;">
                                                +<?= count($techs) - 3 ?>
                                            </span>
                                        <?php endif;
                                    else: ?>
                                        <span class="text-muted small">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center align-middle">
                                    <?php if ($p['status'] === 'publish'): ?>
                                        <span class="badge badge-success" style="font-size: 10px;">Publish</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary" style="font-size: 10px;">Draft</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center align-middle" style="font-size: 12px;">
                                    <?= date('d M Y', strtotime($p['created_at'])) ?>
                                </td>
                                <td class="text-center align-middle pr-3">
                                    <a href="?module=proyek&action=edit&id=<?= (int)$p['id'] ?>"
                                       class="btn btn-sm btn-outline-primary"
                                       style="font-size: 11px; border-radius: 6px;"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="?module=proyek&action=delete&id=<?= (int)$p['id'] ?>"
                                       class="btn btn-sm btn-outline-danger"
                                       style="font-size: 11px; border-radius: 6px;"
                                       title="Hapus"
                                       onclick="return confirm('Yakin hapus proyek &quot;<?= htmlspecialchars($p['judul'], ENT_QUOTES) ?>&quot;?');">
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