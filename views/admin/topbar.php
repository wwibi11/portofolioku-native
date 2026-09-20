<?php
// views/admin/topbar.php
$user_name  = $_SESSION['user']['name'] ?? $_SESSION['user']['nama'] ?? 'Administrator';
$user_email = $_SESSION['user']['email'] ?? '';
$current_module = $_GET['module'] ?? 'dashboard';
$current_action = $_GET['action'] ?? 'index';

$module_labels = [
    'dashboard'  => 'Dashboard',
    'profil'     => 'Profil',
    'skill'      => 'Skill',
    'pendidikan' => 'Pendidikan',
    'pengalaman' => 'Pengalaman',
    'proyek'     => 'Proyek',
    'blog'       => 'Blog',
    'kategori'   => 'Kategori',
    'pesan'      => 'Pesan Masuk',
    'users'      => 'Manajemen User',
    'pengaturan' => 'Pengaturan'
];
$module_title = $module_labels[$current_module] ?? ucfirst($current_module);
?>
<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">

        <!-- TOPBAR -->
        <nav class="navbar navbar-expand navbar-light bg-navbar topbar static-top"
             style="z-index: 999; position: sticky; top: 0;">

            <!-- Tombol hamburger (mobile) -->
            <button id="sidebarToggleTop" class="btn btn-link rounded-circle"
                    onclick="toggleSidebar()"
                    type="button"
                    title="Toggle Sidebar"
                    aria-label="Toggle Sidebar">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Brand (mobile) -->
            <span class="navbar-brand d-md-none">
                <i class="fas fa-user-circle" style="color: #7c3aed;"></i>
                <?= function_exists('getAppName') ? getAppName() : 'Portfolio' ?>
            </span>

            <!-- Breadcrumb (Desktop) -->
            <div class="d-none d-md-block">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb" style="background: transparent; padding: 0; margin: 0;">
                        <li class="breadcrumb-item">
                            <a href="?module=dashboard">
                                <i class="fas fa-home"></i> Admin
                            </a>
                        </li>
                        <?php if ($current_module != 'dashboard'): ?>
                            <li class="breadcrumb-item active"><?= htmlspecialchars($module_title) ?></li>
                            <?php if (!in_array($current_action, ['index', 'dashboard', ''])): ?>
                                <li class="breadcrumb-item active"><?= ucfirst(htmlspecialchars($current_action)) ?></li>
                            <?php endif; ?>
                        <?php else: ?>
                            <li class="breadcrumb-item active">Dashboard</li>
                        <?php endif; ?>
                    </ol>
                </nav>
            </div>

            <!-- Right Menu -->
            <ul class="navbar-nav ml-auto">

                <!-- Lihat Website -->
                <li class="nav-item d-none d-md-block mr-2">
                    <a href="<?= BASE_URL ?>" target="_blank"
                       class="btn btn-sm"
                       style="background: #ede9fe; color: #7c3aed; border-radius: 8px;
                              font-weight: 500; padding: 6px 12px; font-size: 12px; margin-top: 4px;">
                        <i class="fas fa-external-link-alt"></i> Lihat Website
                    </a>
                </li>

                <!-- User Dropdown -->
                <li class="nav-item dropdown no-arrow" style="position: relative;">
                    <a class="nav-link dropdown-toggle d-flex align-items-center"
                       href="#" id="userDropdown" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                        <div class="img-profile rounded-circle"
                             style="width: 36px; height: 36px; background: #ede9fe;
                                    display: flex; align-items: center; justify-content: center;
                                    color: #7c3aed; font-weight: 700; font-size: 14px;">
                            <?= strtoupper(substr($user_name, 0, 1)) ?>
                        </div>

                        <span class="ml-2 d-none d-lg-inline"
                              style="color: #1a2634 !important; font-weight: 500; font-size: 13px;">
                            <?= htmlspecialchars($user_name) ?>
                            <small style="display: block; font-weight: 400; color: #8a94a6; font-size: 10px;">
                                Super Admin
                            </small>
                        </span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right shadow"
                         aria-labelledby="userDropdown">

                        <div class="dropdown-header">
                            <div style="font-weight: 600;"><?= htmlspecialchars($user_name) ?></div>
                            <div style="font-weight: 400; color: #8a94a6; font-size: 11px;">
                                <?= $user_email ? htmlspecialchars($user_email) : 'Super Admin' ?>
                            </div>
                        </div>

                        <div class="dropdown-divider" style="margin: 0;"></div>

                        <a class="dropdown-item" href="?module=profil">
                            <i class="fas fa-user fa-sm fa-fw mr-2"></i> Profil Saya
                        </a>
                        <a class="dropdown-item" href="?module=pengaturan">
                            <i class="fas fa-cog fa-sm fa-fw mr-2"></i> Pengaturan
                        </a>

                        <div class="dropdown-divider" style="margin: 0;"></div>

                        <a class="dropdown-item" href="<?= BASE_URL ?>/auth/logout.php"
                           style="color: #dc2626;">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2"></i> Logout
                        </a>

                    </div>
                </li>

            </ul>
        </nav>