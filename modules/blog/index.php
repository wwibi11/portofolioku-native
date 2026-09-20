<?php
// modules/blog/index.php — Daftar Artikel Blog
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/helper.php';

// Filter & pencarian
$search   = trim($_GET['q'] ?? '');
$status   = $_GET['status'] ?? '';
$kategori = (int)($_GET['kategori'] ?? 0);

// Query
$sql = "SELECT b.*, k.nama AS kategori_nama
        FROM blog b
        LEFT JOIN kategori k ON k.id = b.kategori_id
        WHERE 1=1";
$params = [];

if ($search !== '') {
    $sql .= " AND (b.judul LIKE :q OR b.konten LIKE :q)";
    $params[':q'] = '%' . $search . '%';
}
if ($status !== '' && in_array($status, ['draft', 'publish'])) {
    $sql .= " AND b.status = :status";
    $params[':status'] = $status;
}
if ($kategori > 0) {
    $sql .= " AND b.kategori_id = :kat";
    $params[':kat'] = $kategori;
}
$sql .= " ORDER BY b.created_at DESC";

try {
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    $artikelList = $stmt->fetchAll();
} catch (Exception $e) {
    $artikelList = [];
    setFlash('danger', 'Gagal memuat: ' . $e->getMessage());
}

// Ambil daftar kategori
$kategoriList = [];
try {
    $kategoriList = db()->query("SELECT * FROM kategori ORDER BY nama")->fetchAll();
} catch (Exception $e) {}

// Statistik
$totalArtikel = 0;
$totalPublish = 0;
try {
    $totalArtikel = (int) db()->query("SELECT COUNT(*) FROM blog")->fetchColumn();
    $totalPublish = (int) db()->query("SELECT COUNT(*) FROM blog WHERE status='publish'")->fetchColumn();
} catch (Exception $e) {}
?>

<div class="container-fluid px-2 px-md-4">

    <!-- HEADER -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-3">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-newspaper" style="color: #7c3aed;"></i>
                Artikel Blog
            </h1>
            <p class="mb-0 text-muted small">
                Total: <strong><?= $totalArtikel ?></strong> artikel ·
                <strong><?= $totalPublish ?></strong> publish
            </p>
        </div>
        <a href="?module=blog&action=create"
           class="btn btn-sm mt-2 mt-sm-0"
           style="background: #7c3aed; color: #fff; border-radius: 8px;">
            <i class="fas fa-plus-circle mr-1"></i> Tulis Artikel
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
                <input type="hidden" name="module" value="blog">
                <div class="col-md-4 mb-2 mb-md-0">
                    <input type="text" name="q" class="form-control form-control-sm w-100"
                           placeholder="Cari judul / konten..."
                           value="<?= htmlspecialchars($search) ?>">
                </div>
                <div class="col-md-3 mb-2 mb-md-0">
                    <select name="kategori" class="form-control form-control-sm w-100">
                        <option value="0">Semua Kategori</option>
                        <?php foreach ($kategoriList as $k): ?>
                            <option value="<?= (int)$k['id'] ?>"
                                <?= $kategori === (int)$k['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($k['nama']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2 mb-2 mb-md-0">
                    <select name="status" class="form-control form-control-sm w-100">
                        <option value="">Semua Status</option>
                        <option value="publish" <?= $status === 'publish' ? 'selected' : '' ?>>Publish</option>
                        <option value="draft" <?= $status === 'draft' ? 'selected' : '' ?>>Draft</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-sm btn-primary"
                            style="background: #7c3aed; border-color: #7c3aed; border-radius: 6px;">
                        <i class="fas fa-search"></i> Cari
                    </button>
                    <a href="?module=blog" class="btn btn-sm btn-outline-secondary"
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
                            <th style="width: 70px;">Cover</th>
                            <th>Judul</th>
                            <th style="width: 130px;">Kategori</th>
                            <th style="width: 80px;" class="text-center">Views</th>
                            <th style="width: 90px;" class="text-center">Status</th>
                            <th style="width: 130px;" class="text-center">Tanggal</th>
                            <th style="width: 150px;" class="text-center pr-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($artikelList)): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">
                                    <i class="fas fa-newspaper fa-3x d-block mb-3" style="color: #cbd5e1;"></i>
                                    <div class="mb-2">Belum ada artikel.</div>
                                    <a href="?module=blog&action=create" class="btn btn-sm"
                                       style="background: #7c3aed; color: #fff; border-radius: 6px;">
                                        <i class="fas fa-plus"></i> Tulis Artikel Pertama
                                    </a>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($artikelList as $i => $b): ?>
                            <tr>
                                <td class="pl-3 align-middle"><?= $i + 1 ?></td>
                                <td class="align-middle">
                                    <?php if (!empty($b['cover']) && file_exists(UPLOAD_PATH . $b['cover'])): ?>
                                        <img src="<?= upload($b['cover']) ?>" alt="Cover"
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
                                        <?= htmlspecialchars($b['judul']) ?>
                                    </div>
                                    <div class="small text-muted">
                                        <code style="background: #f1f5f9; padding: 2px 6px;
                                                     border-radius: 4px; font-size: 11px;">
                                            <?= htmlspecialchars($b['slug']) ?>
                                        </code>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <?php if (!empty($b['kategori_nama'])): ?>
                                        <span class="badge"
                                              style="background: #ede9fe; color: #7c3aed; font-size: 10px;">
                                            <?= htmlspecialchars($b['kategori_nama']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted small">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center align-middle">
                                    <span class="badge badge-secondary" style="font-size: 10px;">
                                        <i class="fas fa-eye"></i> <?= (int)$b['views'] ?>
                                    </span>
                                </td>
                                <td class="text-center align-middle">
                                    <?php if ($b['status'] === 'publish'): ?>
                                        <span class="badge badge-success" style="font-size: 10px;">Publish</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary" style="font-size: 10px;">Draft</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center align-middle" style="font-size: 12px;">
                                    <?= date('d M Y', strtotime($b['created_at'])) ?>
                                </td>
                                <td class="text-center align-middle pr-3">
                                    <?php if ($b['status'] === 'publish'): ?>
                                    <a href="<?= BASE_URL ?>/?page=blog_detail&slug=<?= urlencode($b['slug']) ?>"
                                       target="_blank"
                                       class="btn btn-sm btn-outline-info"
                                       style="font-size: 11px; border-radius: 6px;"
                                       title="Lihat di website">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                    <?php endif; ?>
                                    <a href="?module=blog&action=edit&id=<?= (int)$b['id'] ?>"
                                       class="btn btn-sm btn-outline-primary"
                                       style="font-size: 11px; border-radius: 6px;"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="?module=blog&action=delete&id=<?= (int)$b['id'] ?>"
                                       class="btn btn-sm btn-outline-danger"
                                       style="font-size: 11px; border-radius: 6px;"
                                       title="Hapus"
                                       onclick="return confirm('Yakin hapus artikel &quot;<?= htmlspecialchars($b['judul'], ENT_QUOTES) ?>&quot;?');">
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