<?php
// modules/users/index.php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/helper.php';

$currentId = $_SESSION['user']['id'] ?? $_SESSION['user_id'] ?? 0;

try {
    $items = db()->query("SELECT * FROM users ORDER BY id ASC")->fetchAll();
} catch (Exception $e) {
    $items = [];
    setFlash('danger', 'Gagal memuat: ' . $e->getMessage());
}
?>

<div class="container-fluid px-2 px-md-4">

    <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-3">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-users-cog" style="color: #7c3aed;"></i>
                Manajemen User
            </h1>
            <p class="mb-0 text-muted small">
                Total: <strong><?= count($items) ?></strong> user admin.
            </p>
        </div>
        <a href="?module=users&action=create"
           class="btn btn-sm mt-2 mt-sm-0"
           style="background: #7c3aed; color: #fff; border-radius: 8px;">
            <i class="fas fa-plus-circle mr-1"></i> Tambah User
        </a>
    </div>

    <?php if ($flash = getFlash()): ?>
    <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show"
         style="border-radius: 10px;">
        <?= htmlspecialchars($flash['message']) ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php endif; ?>

    <div class="card shadow" style="border-radius: 10px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" style="font-size: 13px;">
                    <thead style="background: #f8f9fc;">
                        <tr>
                            <th class="pl-3" style="width: 60px;">No</th>
                            <th>Nama</th>
                            <th style="width: 200px;">Username</th>
                            <th style="width: 120px;" class="text-center">Role</th>
                            <th style="width: 180px;" class="text-center">Dibuat</th>
                            <th style="width: 150px;" class="text-center pr-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($items)): ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="fas fa-users fa-3x d-block mb-3" style="color: #cbd5e1;"></i>
                                    Belum ada user.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($items as $i => $u): ?>
                            <tr>
                                <td class="pl-3 align-middle"><?= $i + 1 ?></td>
                                <td class="align-middle">
                                    <div class="font-weight-bold" style="color: #1a2634;">
                                        <?= htmlspecialchars($u['nama']) ?>
                                        <?php if ((int)$u['id'] === (int)$currentId): ?>
                                            <span class="badge badge-info ml-1" style="font-size: 9px;">
                                                Anda
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <code style="background: #f1f5f9; padding: 3px 8px;
                                                 border-radius: 4px; font-size: 12px;">
                                        <?= htmlspecialchars($u['username']) ?>
                                    </code>
                                </td>
                                <td class="text-center align-middle">
                                    <?php if ($u['role'] === 'admin'): ?>
                                        <span class="badge" style="background: #ede9fe; color: #7c3aed;
                                              font-size: 10px;">Admin</span>
                                    <?php elseif ($u['role'] === 'super_admin'): ?>
                                        <span class="badge badge-danger" style="font-size: 10px;">Super Admin</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary" style="font-size: 10px;">
                                            <?= htmlspecialchars(ucfirst($u['role'])) ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center align-middle" style="font-size: 12px;">
                                    <?= date('d M Y', strtotime($u['created_at'])) ?>
                                </td>
                                <td class="text-center align-middle pr-3">
                                    <a href="?module=users&action=edit&id=<?= (int)$u['id'] ?>"
                                       class="btn btn-sm btn-outline-primary"
                                       style="font-size: 11px; border-radius: 6px;">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if ((int)$u['id'] !== (int)$currentId): ?>
                                        <a href="?module=users&action=delete&id=<?= (int)$u['id'] ?>"
                                           class="btn btn-sm btn-outline-danger"
                                           style="font-size: 11px; border-radius: 6px;"
                                           onclick="return confirm('Yakin hapus user &quot;<?= htmlspecialchars($u['nama'], ENT_QUOTES) ?>&quot;?');">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    <?php else: ?>
                                        <button class="btn btn-sm btn-outline-secondary"
                                                style="font-size: 11px; border-radius: 6px;"
                                                disabled title="Tidak bisa hapus diri sendiri">
                                            <i class="fas fa-lock"></i>
                                        </button>
                                    <?php endif; ?>
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