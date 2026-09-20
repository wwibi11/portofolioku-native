<?php
// modules/dashboard/index.php — Dashboard Portofolio
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/helper.php';

// ============================================
// STATISTIK
// ============================================

// Total proyek
$totalProyek = 0;
try {
    $totalProyek = (int) db()->query("SELECT COUNT(*) FROM proyek")->fetchColumn();
} catch (Exception $e) {}

// Total blog
$totalBlog = 0;
$blogPublish = 0;
try {
    $totalBlog    = (int) db()->query("SELECT COUNT(*) FROM blog")->fetchColumn();
    $blogPublish  = (int) db()->query("SELECT COUNT(*) FROM blog WHERE status = 'publish'")->fetchColumn();
} catch (Exception $e) {}

// Total skill
$totalSkill = 0;
try {
    $totalSkill = (int) db()->query("SELECT COUNT(*) FROM skills")->fetchColumn();
} catch (Exception $e) {}

// Total pengalaman
$totalPengalaman = 0;
try {
    $totalPengalaman = (int) db()->query("SELECT COUNT(*) FROM pengalaman")->fetchColumn();
} catch (Exception $e) {}

// Total pendidikan
$totalPendidikan = 0;
try {
    $totalPendidikan = (int) db()->query("SELECT COUNT(*) FROM pendidikan")->fetchColumn();
} catch (Exception $e) {}

// Pesan masuk (belum dibaca)
$totalPesan = 0;
$pesanBelum = 0;
try {
    $totalPesan = (int) db()->query("SELECT COUNT(*) FROM pesan")->fetchColumn();
    $pesanBelum = (int) db()->query("SELECT COUNT(*) FROM pesan WHERE status = 'belum'")->fetchColumn();
} catch (Exception $e) {}

// Total views blog (kalau ada)
$totalViews = 0;
try {
    $totalViews = (int) db()->query("SELECT COALESCE(SUM(views), 0) FROM blog")->fetchColumn();
} catch (Exception $e) {}

// Proyek terbaru
$proyekTerbaru = [];
try {
    $stmt = db()->query("SELECT id, judul, slug, status, created_at FROM proyek ORDER BY created_at DESC LIMIT 5");
    $proyekTerbaru = $stmt->fetchAll();
} catch (Exception $e) {}

// Blog terbaru
$blogTerbaru = [];
try {
    $stmt = db()->query("SELECT id, judul, slug, status, created_at FROM blog ORDER BY created_at DESC LIMIT 5");
    $blogTerbaru = $stmt->fetchAll();
} catch (Exception $e) {}

// Pesan terbaru
$pesanTerbaru = [];
try {
    $stmt = db()->query("SELECT id, nama, email, subjek, status, created_at FROM pesan ORDER BY created_at DESC LIMIT 5");
    $pesanTerbaru = $stmt->fetchAll();
} catch (Exception $e) {}

// User yang login
$user = currentUser();
$userName = $user['nama'] ?? 'Administrator';
?>

<div class="container-fluid px-2 px-md-4">

    <!-- HEADER -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-3">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-tachometer-alt" style="color: #7c3aed;"></i>
                Dashboard
            </h1>
            <p class="mb-0 text-muted small">
                Selamat datang, <strong><?= htmlspecialchars($userName) ?></strong>!
                Berikut ringkasan portofolio Anda.
            </p>
        </div>
        <div class="mt-2 mt-sm-0">
            <a href="?module=proyek&action=create" class="btn btn-sm"
               style="background: #7c3aed; color: #fff; border-radius: 8px;">
                <i class="fas fa-plus-circle mr-1"></i> Tambah Proyek
            </a>
            <a href="?module=blog&action=create" class="btn btn-outline-primary btn-sm"
               style="border-radius: 8px; color: #7c3aed; border-color: #7c3aed;">
                <i class="fas fa-plus mr-1"></i> Tulis Artikel
            </a>
        </div>
    </div>

    <!-- STAT CARDS UTAMA -->
    <div class="row">
        <!-- Proyek -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2" style="border-radius: 10px; border-left: 4px solid #7c3aed;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #7c3aed;">
                                Total Proyek
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format($totalProyek) ?>
                            </div>
                            <div class="small text-muted">portofolio aktif</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-folder-open fa-2x" style="color: #ddd6fe;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Blog -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2" style="border-radius: 10px; border-left: 4px solid #3b82f6;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #3b82f6;">
                                Artikel Blog
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format($totalBlog) ?>
                            </div>
                            <div class="small text-muted"><?= number_format($blogPublish) ?> publish</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-newspaper fa-2x" style="color: #bfdbfe;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Skill -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2" style="border-radius: 10px; border-left: 4px solid #22c55e;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #22c55e;">
                                Skill
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format($totalSkill) ?>
                            </div>
                            <div class="small text-muted">keahlian tercatat</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-code fa-2x" style="color: #bbf7d0;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pesan -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2" style="border-radius: 10px; border-left: 4px solid #f59e0b;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #f59e0b;">
                                Pesan Masuk
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format($totalPesan) ?>
                            </div>
                            <div class="small text-muted">
                                <?php if ($pesanBelum > 0): ?>
                                    <span class="badge badge-danger" style="font-size: 10px;">
                                        <?= $pesanBelum ?> belum dibaca
                                    </span>
                                <?php else: ?>
                                    semua sudah dibaca
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-envelope fa-2x" style="color: #fde68a;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- QUICK STATS -->
    <div class="row mb-4">
        <div class="col-6 col-md-3 mb-2">
            <div class="card shadow h-100 py-2" style="border-radius: 10px;">
                <div class="card-body text-center py-3">
                    <div class="h5 mb-0 font-weight-bold" style="color: #7c3aed;">
                        <?= number_format($totalPengalaman) ?>
                    </div>
                    <div class="small text-muted">
                        <i class="fas fa-briefcase"></i> Pengalaman
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-2">
            <div class="card shadow h-100 py-2" style="border-radius: 10px;">
                <div class="card-body text-center py-3">
                    <div class="h5 mb-0 font-weight-bold" style="color: #3b82f6;">
                        <?= number_format($totalPendidikan) ?>
                    </div>
                    <div class="small text-muted">
                        <i class="fas fa-graduation-cap"></i> Pendidikan
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-2">
            <div class="card shadow h-100 py-2" style="border-radius: 10px;">
                <div class="card-body text-center py-3">
                    <div class="h5 mb-0 font-weight-bold" style="color: #22c55e;">
                        <?= number_format($totalViews) ?>
                    </div>
                    <div class="small text-muted">
                        <i class="fas fa-eye"></i> Views Blog
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-2">
            <div class="card shadow h-100 py-2" style="border-radius: 10px;">
                <div class="card-body text-center py-3">
                    <div class="h5 mb-0 font-weight-bold" style="color: #f59e0b;">
                        <?= number_format($blogPublish) ?>
                    </div>
                    <div class="small text-muted">
                        <i class="fas fa-check-circle"></i> Blog Publish
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TABEL: PROYEK TERBARU & BLOG TERBARU -->
    <div class="row">
        <!-- Proyek Terbaru -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow" style="border-radius: 10px;">
                <div class="card-header py-3 bg-white d-flex align-items-center justify-content-between"
                     style="border-bottom: 1px solid #eef2f7; border-radius: 10px 10px 0 0;">
                    <h6 class="m-0 font-weight-bold" style="color: #7c3aed;">
                        <i class="fas fa-folder-open mr-1"></i> Proyek Terbaru
                    </h6>
                    <a href="?module=proyek" class="small" style="color: #7c3aed; text-decoration: none;">
                        Lihat Semua <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0" style="font-size: 13px;">
                            <thead style="background: #f8f9fc;">
                                <tr>
                                    <th class="pl-3">Judul</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center pr-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($proyekTerbaru)): ?>
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">
                                            <i class="fas fa-inbox fa-2x d-block mb-2" style="color: #cbd5e1;"></i>
                                            Belum ada proyek.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($proyekTerbaru as $p): ?>
                                    <tr>
                                        <td class="pl-3">
                                            <div class="font-weight-bold" style="color: #1a2634;">
                                                <?= htmlspecialchars($p['judul']) ?>
                                            </div>
                                            <div class="small text-muted">
                                                <?= date('d M Y', strtotime($p['created_at'])) ?>
                                            </div>
                                        </td>
                                        <td class="text-center align-middle">
                                            <?php if ($p['status'] == 'publish'): ?>
                                                <span class="badge badge-success" style="font-size: 10px;">Publish</span>
                                            <?php else: ?>
                                                <span class="badge badge-secondary" style="font-size: 10px;">Draft</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center align-middle pr-3">
                                            <a href="?module=proyek&action=edit&id=<?= (int)$p['id'] ?>"
                                               class="btn btn-sm btn-outline-primary" style="font-size: 11px;">
                                                <i class="fas fa-edit"></i>
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

        <!-- Blog Terbaru -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow" style="border-radius: 10px;">
                <div class="card-header py-3 bg-white d-flex align-items-center justify-content-between"
                     style="border-bottom: 1px solid #eef2f7; border-radius: 10px 10px 0 0;">
                    <h6 class="m-0 font-weight-bold" style="color: #3b82f6;">
                        <i class="fas fa-newspaper mr-1"></i> Blog Terbaru
                    </h6>
                    <a href="?module=blog" class="small" style="color: #3b82f6; text-decoration: none;">
                        Lihat Semua <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0" style="font-size: 13px;">
                            <thead style="background: #f8f9fc;">
                                <tr>
                                    <th class="pl-3">Judul</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center pr-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($blogTerbaru)): ?>
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">
                                            <i class="fas fa-inbox fa-2x d-block mb-2" style="color: #cbd5e1;"></i>
                                            Belum ada artikel.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($blogTerbaru as $b): ?>
                                    <tr>
                                        <td class="pl-3">
                                            <div class="font-weight-bold" style="color: #1a2634;">
                                                <?= htmlspecialchars($b['judul']) ?>
                                            </div>
                                            <div class="small text-muted">
                                                <?= date('d M Y', strtotime($b['created_at'])) ?>
                                            </div>
                                        </td>
                                        <td class="text-center align-middle">
                                            <?php if ($b['status'] == 'publish'): ?>
                                                <span class="badge badge-success" style="font-size: 10px;">Publish</span>
                                            <?php else: ?>
                                                <span class="badge badge-secondary" style="font-size: 10px;">Draft</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center align-middle pr-3">
                                            <a href="?module=blog&action=edit&id=<?= (int)$b['id'] ?>"
                                               class="btn btn-sm btn-outline-primary" style="font-size: 11px;">
                                                <i class="fas fa-edit"></i>
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
    </div>

    <!-- PESAN TERBARU -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow" style="border-radius: 10px;">
                <div class="card-header py-3 bg-white d-flex align-items-center justify-content-between"
                     style="border-bottom: 1px solid #eef2f7; border-radius: 10px 10px 0 0;">
                    <h6 class="m-0 font-weight-bold" style="color: #f59e0b;">
                        <i class="fas fa-envelope mr-1"></i> Pesan Terbaru
                        <?php if ($pesanBelum > 0): ?>
                            <span class="badge badge-danger ml-1" style="font-size: 9px;">
                                <?= $pesanBelum ?> baru
                            </span>
                        <?php endif; ?>
                    </h6>
                    <a href="?module=pesan" class="small" style="color: #f59e0b; text-decoration: none;">
                        Lihat Semua <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0" style="font-size: 13px;">
                            <thead style="background: #f8f9fc;">
                                <tr>
                                    <th class="pl-3">Nama</th>
                                    <th>Subjek</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center pr-3">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($pesanTerbaru)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            <i class="fas fa-inbox fa-2x d-block mb-2" style="color: #cbd5e1;"></i>
                                            Belum ada pesan masuk.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($pesanTerbaru as $p): ?>
                                    <tr>
                                        <td class="pl-3">
                                            <div class="font-weight-bold" style="color: #1a2634;">
                                                <?= htmlspecialchars($p['nama']) ?>
                                            </div>
                                            <div class="small text-muted">
                                                <?= htmlspecialchars($p['email']) ?>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <?= htmlspecialchars($p['subjek'] ?: '-') ?>
                                        </td>
                                        <td class="text-center align-middle">
                                            <?php if ($p['status'] == 'belum'): ?>
                                                <span class="badge badge-warning" style="font-size: 10px;">Baru</span>
                                            <?php else: ?>
                                                <span class="badge badge-secondary" style="font-size: 10px;">Dibaca</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center align-middle pr-3" style="font-size: 12px;">
                                            <?= date('d/m/Y H:i', strtotime($p['created_at'])) ?>
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
    </div>

</div>