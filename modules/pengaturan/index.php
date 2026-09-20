<?php
// modules/pengaturan/index.php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/helper.php';

// Ambil setting dari DB
$settings = [
    'app_name'         => 'Wisnu Wibisono',
    'site_title'       => 'Portofolio Wisnu Wibisono',
    'site_description' => 'Web Developer & UI Designer',
    'maintenance_mode' => '0'
];

try {
    $stmt = db()->query("SELECT setting_key, setting_value FROM settings");
    while ($row = $stmt->fetch()) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
} catch (Exception $e) {
    setFlash('warning', 'Tabel settings belum ada. Jalankan SQL create tabel dulu.');
}

$maintenanceActive = ($settings['maintenance_mode'] === '1');
?>

<div class="container-fluid px-2 px-md-4">

    <!-- HEADER -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-3">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-cog" style="color: #7c3aed;"></i>
                Pengaturan
            </h1>
            <p class="mb-0 text-muted small">Pengaturan umum website.</p>
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

    <form action="?module=pengaturan&action=save" method="post">
        <div class="row">
            <div class="col-lg-8">

                <!-- INFORMASI WEBSITE -->
                <div class="card shadow mb-3" style="border-radius: 10px;">
                    <div class="card-header py-3 bg-white"
                         style="border-bottom: 1px solid #eef2f7;">
                        <h6 class="m-0 font-weight-bold" style="color: #7c3aed;">
                            <i class="fas fa-globe"></i> Informasi Website
                        </h6>
                    </div>
                    <div class="card-body">

                        <div class="form-group">
                            <label class="font-weight-bold" style="font-size: 13px;">Nama Aplikasi</label>
                            <input type="text" name="app_name" class="form-control"
                                   value="<?= htmlspecialchars($settings['app_name']) ?>"
                                   maxlength="100">
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold" style="font-size: 13px;">Site Title</label>
                            <input type="text" name="site_title" class="form-control"
                                   value="<?= htmlspecialchars($settings['site_title']) ?>"
                                   maxlength="150">
                            <small class="text-muted">Tampil di tab browser.</small>
                        </div>

                        <div class="form-group mb-0">
                            <label class="font-weight-bold" style="font-size: 13px;">Site Description</label>
                            <textarea name="site_description" class="form-control"
                                      rows="3" maxlength="255"><?= htmlspecialchars($settings['site_description']) ?></textarea>
                            <small class="text-muted">Meta description untuk SEO.</small>
                        </div>

                    </div>
                </div>

                <!-- MODE MAINTENANCE -->
                <div class="card shadow mb-3" style="border-radius: 10px;
                     <?= $maintenanceActive ? 'border-left: 4px solid #dc2626 !important;' : '' ?>">
                    <div class="card-header py-3 bg-white"
                         style="border-bottom: 1px solid #eef2f7;">
                        <h6 class="m-0 font-weight-bold"
                            style="color: <?= $maintenanceActive ? '#dc2626' : '#7c3aed' ?>;">
                            <i class="fas fa-tools"></i> Mode Maintenance
                            <?php if ($maintenanceActive): ?>
                                <span class="badge badge-danger ml-2" style="font-size: 10px;">
                                    AKTIF
                                </span>
                            <?php endif; ?>
                        </h6>
                    </div>
                    <div class="card-body">

                        <!-- Toggle Switch -->
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="maintenance_mode"
                                   name="maintenance_mode" value="1"
                                   <?= $maintenanceActive ? 'checked' : '' ?>>
                            <label class="custom-control-label font-weight-bold"
                                   for="maintenance_mode" style="font-size: 14px; cursor: pointer;">
                                Aktifkan Mode Maintenance
                            </label>
                        </div>

                        <div class="alert alert-<?= $maintenanceActive ? 'warning' : 'info' ?>
                             mt-3 mb-0" style="border-radius: 10px; font-size: 13px;">
                            <?php if ($maintenanceActive): ?>
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>Mode maintenance sedang AKTIF.</strong>
                                Pengunjung akan diarahkan ke halaman maintenance.
                                Anda (admin) tetap bisa mengakses website.
                            <?php else: ?>
                                <i class="fas fa-info-circle"></i>
                                Kalau aktif, pengunjung akan diarahkan ke halaman maintenance.
                                Admin yang sudah login tetap bisa mengakses website.
                            <?php endif; ?>
                        </div>

                    </div>
                </div>

            </div>

            <div class="col-lg-4">
                <div class="card shadow" style="border-radius: 10px;">
                    <div class="card-body">
                        <button type="submit" class="btn btn-block"
                                style="background: #7c3aed; color: #fff; border-radius: 8px;">
                            <i class="fas fa-save mr-1"></i> Simpan Pengaturan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>