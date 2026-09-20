<?php
// modules/skill/index.php — Daftar Skill
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/helper.php';

// Filter
$search   = trim($_GET['q'] ?? '');
$kategori = $_GET['kategori'] ?? '';

// Query
$sql = "SELECT * FROM skills WHERE 1=1";
$params = [];

if ($search !== '') {
    $sql .= " AND nama LIKE :q";
    $params[':q'] = '%' . $search . '%';
}
if ($kategori !== '') {
    $sql .= " AND kategori = :kat";
    $params[':kat'] = $kategori;
}
$sql .= " ORDER BY urutan ASC, id ASC";

try {
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    $skillList = $stmt->fetchAll();
} catch (Exception $e) {
    $skillList = [];
    setFlash('danger', 'Gagal memuat data: ' . $e->getMessage());
}

// Daftar kategori unik untuk filter
$kategoriList = [];
try {
    $kategoriList = db()->query("SELECT DISTINCT kategori FROM skills WHERE kategori != '' ORDER BY kategori")->fetchAll(PDO::FETCH_COLUMN);
} catch (Exception $e) {}
?>

<div class="container-fluid px-2 px-md-4">

    <!-- HEADER -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-3">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-code" style="color: #7c3aed;"></i>
                Manajemen Skill
            </h1>
            <p class="mb-0 text-muted small">
                Kelola keahlian yang ditampilkan di portofolio.
                Total: <strong><?= count($skillList) ?></strong> skill.
            </p>
        </div>
        <a href="?module=skill&action=create"
           class="btn btn-sm mt-2 mt-sm-0"
           style="background: #7c3aed; color: #fff; border-radius: 8px;">
            <i class="fas fa-plus-circle mr-1"></i> Tambah Skill
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
                <input type="hidden" name="module" value="skill">
                <div class="col-md-5 mb-2 mb-md-0">
                    <input type="text" name="q" class="form-control form-control-sm w-100"
                           placeholder="Cari skill..."
                           value="<?= htmlspecialchars($search) ?>">
                </div>
                <div class="col-md-3 mb-2 mb-md-0">
                    <select name="kategori" class="form-control form-control-sm w-100">
                        <option value="">Semua Kategori</option>
                        <?php foreach ($kategoriList as $kat): ?>
                            <option value="<?= htmlspecialchars($kat) ?>"
                                <?= $kategori === $kat ? 'selected' : '' ?>>
                                <?= htmlspecialchars(ucfirst($kat)) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-sm btn-primary"
                            style="background: #7c3aed; border-color: #7c3aed; border-radius: 6px;">
                        <i class="fas fa-search"></i> Cari
                    </button>
                    <a href="?module=skill" class="btn btn-sm btn-outline-secondary"
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
                            <th>Nama Skill</th>
                            <th style="width: 200px;">Level</th>
                            <th style="width: 130px;" class="text-center">Kategori</th>
                            <th style="width: 80px;" class="text-center">Urutan</th>
                            <th style="width: 150px;" class="text-center pr-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($skillList)): ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="fas fa-code fa-3x d-block mb-3" style="color: #cbd5e1;"></i>
                                    <div class="mb-2">Belum ada skill.</div>
                                    <a href="?module=skill&action=create" class="btn btn-sm"
                                       style="background: #7c3aed; color: #fff; border-radius: 6px;">
                                        <i class="fas fa-plus"></i> Tambah Skill Pertama
                                    </a>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($skillList as $i => $s):
                                $level = (int) $s['level'];
                                $barColor = $level >= 80 ? '#22c55e' : ($level >= 60 ? '#f59e0b' : '#ef4444');
                            ?>
                            <tr>
                                <td class="pl-3 align-middle"><?= $i + 1 ?></td>
                                <td class="align-middle">
                                    <div class="font-weight-bold" style="color: #1a2634;">
                                        <?= htmlspecialchars($s['nama']) ?>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        <div style="flex: 1; height: 6px; background: #eef2f7;
                                                    border-radius: 3px; overflow: hidden; margin-right: 8px;">
                                            <div style="width: <?= $level ?>%; height: 100%;
                                                        background: <?= $barColor ?>;"></div>
                                        </div>
                                        <span class="font-weight-bold" style="font-size: 12px;
                                              color: <?= $barColor ?>; width: 40px;">
                                            <?= $level ?>%
                                        </span>
                                    </div>
                                </td>
                                <td class="text-center align-middle">
                                    <?php if (!empty($s['kategori'])): ?>
                                        <span class="badge"
                                              style="background: #ede9fe; color: #7c3aed; font-size: 10px;">
                                            <?= htmlspecialchars(ucfirst($s['kategori'])) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted small">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center align-middle">
                                    <?= (int) $s['urutan'] ?>
                                </td>
                                <td class="text-center align-middle pr-3">
                                    <a href="?module=skill&action=edit&id=<?= (int)$s['id'] ?>"
                                       class="btn btn-sm btn-outline-primary"
                                       style="font-size: 11px; border-radius: 6px;"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="?module=skill&action=delete&id=<?= (int)$s['id'] ?>"
                                       class="btn btn-sm btn-outline-danger"
                                       style="font-size: 11px; border-radius: 6px;"
                                       title="Hapus"
                                       onclick="return confirm('Yakin hapus skill &quot;<?= htmlspecialchars($s['nama'], ENT_QUOTES) ?>&quot;?');">
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