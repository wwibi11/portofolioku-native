<?php
// modules/users/create.php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/helper.php';
?>

<div class="container-fluid px-2 px-md-4">

    <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-3">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user-plus" style="color: #7c3aed;"></i>
                Tambah User
            </h1>
            <p class="mb-0 text-muted small">Tambahkan user admin baru.</p>
        </div>
        <a href="?module=users" class="btn btn-sm btn-outline-secondary mt-2 mt-sm-0"
           style="border-radius: 8px;">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <?php if ($flash = getFlash()): ?>
    <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show"
         style="border-radius: 10px;">
        <?= htmlspecialchars($flash['message']) ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php endif; ?>

    <form action="?module=users&action=save" method="post">
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow mb-3" style="border-radius: 10px;">
                    <div class="card-body">

                        <div class="form-group">
                            <label class="font-weight-bold" style="font-size: 13px;">
                                Nama Lengkap <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="nama" class="form-control"
                                   placeholder="Contoh: Administrator"
                                   required maxlength="100">
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold" style="font-size: 13px;">
                                Username <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="username" class="form-control"
                                   placeholder="admin" required maxlength="50">
                            <small class="text-muted">Tanpa spasi, gunakan huruf/angka/underscore.</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold" style="font-size: 13px;">
                                        Password <span class="text-danger">*</span>
                                    </label>
                                    <input type="password" name="password" class="form-control"
                                           placeholder="Min. 6 karakter" required minlength="6">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold" style="font-size: 13px;">
                                        Konfirmasi Password <span class="text-danger">*</span>
                                    </label>
                                    <input type="password" name="password_confirm" class="form-control"
                                           placeholder="Ulangi password" required minlength="6">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <label class="font-weight-bold" style="font-size: 13px;">Role</label>
                            <select name="role" class="form-control">
                                <option value="admin">Admin</option>
                                <option value="editor">Editor</option>
                            </select>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow" style="border-radius: 10px;">
                    <div class="card-body">
                        <button type="submit" class="btn btn-block"
                                style="background: #7c3aed; color: #fff; border-radius: 8px;">
                            <i class="fas fa-save mr-1"></i> Simpan
                        </button>
                        <a href="?module=users" class="btn btn-block btn-outline-secondary"
                           style="border-radius: 8px;">
                            Batal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>