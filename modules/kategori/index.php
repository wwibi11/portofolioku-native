<?php
// modules/kategori/index.php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/helper.php';

$search = trim($_GET['q'] ?? '');
$sql = "SELECT k.*, 
            (SELECT COUNT(*) FROM blog WHERE kategori_id = k.id) AS total_blog
        FROM kategori k WHERE 1=1";
$params = [];

if ($search !== '') {
    $sql .= " AND k.nama LIKE :q";
    $params[':q'] = '%' . $search . '%';
}
$sql .= " ORDER BY k.nama ASC";

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
                <i class="fas fa-tags" style="color: #7c3aed;"></i>
                Manajemen Kategori
            </h1>
            <p class="mb-0 text-muted small">
                Total: <strong><?= count($items) ?></strong> kategori blog.
            </p>
        </div>
        <a href="?module=kategori&action=create"
           class="btn btn-sm mt-2 mt-sm-0"
           style="background: #7c3aed; color: #fff; border-radius: 8px;">
            <i class="fas fa-plus-circle mr-1"></i> Tambah Kategori
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
                <input type="hidden" name="module" value="kategori">
                <div class="col-md-6 mb-2 mb-md-0">
                    <input type="text" name="q" class="form-control form-control-sm w-100"
                           placeholder="Cari kategori..."
                           value="<?= htmlspecialchars($search) ?>">
                </div>
                <div class="col-md-6">
                    <button type="submit" class="btn btn-sm btn-primary"
                            style="background: #7c3aed; border-color: #7c3aed; border-radius: 6px;">
                        <i class="fas fa-search"></i> Cari
                    </button>
                    <a href="?module=kategori" class="btn btn-sm btn-outline-secondary"
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
                            <th>Nama Kategori</th>
                            <th style="width: 250px;">Slug</th>
                            <th style="width: 100px;" class="text-center">Artikel</th>
                            <th style="width: 150px;" class="text-center pr-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($items)): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    <i class="fas fa-tags fa-3x d-block mb-3" style="color: #cbd5e1;"></i>
                                    <div class="mb-2">Belum ada kategori.</div>
                                    <a href="?module=kategori&action=create" class="btn btn-sm"
                                       style="background: #7c3aed; color: #fff; border-radius: 6px;">
                                        <i class="fas fa-plus"></i> Tambah Kategori Pertama
                                    </a>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($items as $i => $k): ?>
                            <tr>
                                <td class="pl-3 align-middle"><?= $i + 1 ?></td>
                                <td class="align-middle">
                                    <div class="font-weight-bold" style="color: #1a2634;">
                                        <i class="fas fa-tag" style="color: #7c3aed;"></i>
                                        <?= htmlspecialchars($k['nama']) ?>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <code style="background: #f1f5f9; padding: 3px 8px;
                                                 border-radius: 4px; font-size: 12px;">
                                        <?= htmlspecialchars($k['slug']) ?>
                                    </code>
                                </td>
                                <td class="text-center align-middle">
                                    <?php if ($k['total_blog'] > 0): ?>
                                        <span class="badge badge-info" style="font-size: 10px;">
                                            <?= (int)$k['total_blog'] ?> artikel
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted small">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center align-middle pr-3">
                                    <a href="?module=kategori&action=edit&id=<?= (int)$k['id'] ?>"
                                       class="btn btn-sm btn-outline-primary"
                                       style="font-size: 11px; border-radius: 6px;">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="?module=kategori&action=delete&id=<?= (int)$k['id'] ?>"
                                       class="btn btn-sm btn-outline-danger"
                                       style="font-size: 11px; border-radius: 6px;"
                                       onclick="return confirm('Yakin hapus kategori &quot;<?= htmlspecialchars($k['nama'], ENT_QUOTES) ?>&quot;?');">
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