<?php
// modules/pengaturan/save.php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/helper.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('?module=pengaturan');
}

$appName         = trim($_POST['app_name'] ?? 'Wisnu Wibisono');
$siteTitle       = trim($_POST['site_title'] ?? 'Portofolio');
$siteDescription = trim($_POST['site_description'] ?? '');
$maintenanceMode = !empty($_POST['maintenance_mode']) ? '1' : '0';

try {
    // Insert/update setting
    $sql = "INSERT INTO settings (setting_key, setting_value)
            VALUES (:k, :v)
            ON DUPLICATE KEY UPDATE setting_value = :v2";

    $stmt = db()->prepare($sql);

    // app_name
    $stmt->execute([':k' => 'app_name', ':v' => $appName, ':v2' => $appName]);

    // site_title
    $stmt->execute([':k' => 'site_title', ':v' => $siteTitle, ':v2' => $siteTitle]);

    // site_description
    $stmt->execute([':k' => 'site_description', ':v' => $siteDescription, ':v2' => $siteDescription]);

    // maintenance_mode
    $stmt->execute([':k' => 'maintenance_mode', ':v' => $maintenanceMode, ':v2' => $maintenanceMode]);

    // Info flash
    if ($maintenanceMode === '1') {
        setFlash('warning', 'Pengaturan disimpan. Mode maintenance AKTIF.');
    } else {
        setFlash('success', 'Pengaturan disimpan. Mode maintenance nonaktif.');
    }

} catch (Exception $e) {
    setFlash('danger', 'Gagal menyimpan: ' . $e->getMessage());
}

redirect('?module=pengaturan');