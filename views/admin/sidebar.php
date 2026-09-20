<?php
// views/admin/sidebar.php
$app_name       = function_exists('getAppName') ? getAppName() : 'Wisnu Wibisono';
$current_module = $_GET['module'] ?? 'dashboard';
?>
<ul class="navbar-nav sidebar sidebar-light accordion" id="accordionSidebar">

    <!-- BRAND -->
    <div class="sidebar-brand d-flex align-items-center justify-content-center">
        <a class="d-flex align-items-center" href="?module=dashboard">
            <div class="sidebar-brand-icon">
                <i class="fas fa-user-circle"></i>
            </div>
            <div class="sidebar-brand-text">
                <?= htmlspecialchars($app_name) ?>
                <small>Admin Panel</small>
            </div>
        </a>
    </div>

    <hr class="sidebar-divider my-0" style="margin: 0;">

    <!-- DASHBOARD -->
    <li class="nav-item" style="margin-top: 12px;">
        <a class="nav-link <?= $current_module == 'dashboard' ? 'active' : '' ?>"
           href="?module=dashboard">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <!-- KONTEN -->
    <div class="sidebar-heading">Konten</div>

    <li class="nav-item">
        <a class="nav-link <?= $current_module == 'profil' ? 'active' : '' ?>" href="?module=profil">
            <i class="fas fa-user"></i><span>Profil</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $current_module == 'skill' ? 'active' : '' ?>" href="?module=skill">
            <i class="fas fa-code"></i><span>Skill</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $current_module == 'pendidikan' ? 'active' : '' ?>" href="?module=pendidikan">
            <i class="fas fa-graduation-cap"></i><span>Pendidikan</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $current_module == 'pengalaman' ? 'active' : '' ?>" href="?module=pengalaman">
            <i class="fas fa-briefcase"></i><span>Pengalaman</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <!-- PORTOFOLIO & BLOG -->
    <div class="sidebar-heading">Portofolio & Blog</div>

    <li class="nav-item">
        <a class="nav-link <?= $current_module == 'proyek' ? 'active' : '' ?>" href="?module=proyek">
            <i class="fas fa-folder-open"></i><span>Proyek</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $current_module == 'blog' ? 'active' : '' ?>" href="?module=blog">
            <i class="fas fa-newspaper"></i><span>Artikel Blog</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $current_module == 'kategori' ? 'active' : '' ?>" href="?module=kategori">
            <i class="fas fa-tags"></i><span>Kategori</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <!-- INTERAKSI -->
    <div class="sidebar-heading">Interaksi</div>

    <li class="nav-item">
        <a class="nav-link <?= $current_module == 'pesan' ? 'active' : '' ?>" href="?module=pesan">
            <i class="fas fa-envelope"></i><span>Pesan Masuk</span>
            <?php
            $unread = 0;
            if (function_exists('db')) {
                try {
                    $stmt = db()->query("SELECT COUNT(*) FROM pesan WHERE status = 'belum'");
                    $unread = (int) $stmt->fetchColumn();
                } catch (Exception $e) { $unread = 0; }
            }
            if ($unread > 0): ?>
                <span class="badge badge-danger ml-auto"><?= $unread ?></span>
            <?php endif; ?>
        </a>
    </li>

    <hr class="sidebar-divider">

    <!-- SISTEM -->
    <div class="sidebar-heading">Sistem</div>

    <li class="nav-item">
        <a class="nav-link <?= $current_module == 'users' ? 'active' : '' ?>" href="?module=users">
            <i class="fas fa-users-cog"></i><span>Manajemen User</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $current_module == 'pengaturan' ? 'active' : '' ?>" href="?module=pengaturan">
            <i class="fas fa-cog"></i><span>Pengaturan</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <!-- LIHAT WEBSITE -->
    <li class="nav-item">
        <a class="nav-link" href="<?= BASE_URL ?>" target="_blank">
            <i class="fas fa-external-link-alt"></i><span>Lihat Website</span>
        </a>
    </li>

    <!-- Spacer -->
    <div style="flex: 1; min-height: 30px;"></div>

    <!-- Logout -->
    <li class="nav-item">
        <a class="nav-link" href="<?= BASE_URL ?>/auth/logout.php"
           onclick="return confirm('Yakin logout?');">
            <i class="fas fa-sign-out-alt"></i><span>Logout</span>
        </a>
    </li>

</ul>