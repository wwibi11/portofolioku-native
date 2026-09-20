<?php
// modules/kategori/create.php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/helper.php';
?>

<div class="container-fluid px-2 px-md-4">

    <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-3">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-plus-circle" style="color: #7c3aed;"></i>
                Tambah Kategori
            </h1>
            <p class="mb-0 text-muted small">Tambahkan kategori untuk artikel blog.</p>
        </div>
        <a href="?module=kategori" class="btn btn-sm btn-outline-secondary mt-2 mt-sm-0"
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

    <form action="?module=kategori&action=save" method="post">
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow mb-3" style="border-radius: 10px;">
                    <div class="card-body">
                        <div class="form-group mb-0">
                            <label class="font-weight-bold" style="font-size: 13px;">
                                Nama Kategori <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="nama" class="form-control"
                                   placeholder="Contoh: Programming"
                                   required maxlength="50"
                                   oninput="document.getElementById('slugPreview').innerText = slugify(this.value)">
                            <small class="text-muted">
                                Slug: <code id="slugPreview" style="background: #f1f5f9;
                                padding: 2px 6px; border-radius: 4px;">-</code>
                            </small>
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
                        <a href="?module=kategori" class="btn btn-block btn-outline-secondary"
                           style="border-radius: 8px;">
                            Batal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>

<script>
function slugify(text) {
    return text.toString().toLowerCase()
        .replace(/[^\w\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .replace(/^-+|-+$/g, '');
}
</script>