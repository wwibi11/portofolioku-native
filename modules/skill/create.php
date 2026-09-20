<?php
// modules/skill/create.php — Form Tambah Skill
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/helper.php';
?>

<div class="container-fluid px-2 px-md-4">

    <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-3">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-plus-circle" style="color: #7c3aed;"></i>
                Tambah Skill
            </h1>
            <p class="mb-0 text-muted small">Tambahkan keahlian baru.</p>
        </div>
        <a href="?module=skill" class="btn btn-sm btn-outline-secondary mt-2 mt-sm-0"
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

    <form action="?module=skill&action=save" method="post">
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow mb-3" style="border-radius: 10px;">
                    <div class="card-body">

                        <div class="form-group">
                            <label class="font-weight-bold" style="font-size: 13px;">
                                Nama Skill <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="nama" class="form-control"
                                   placeholder="Contoh: PHP & MySQL"
                                   required maxlength="50">
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold" style="font-size: 13px;">
                                Level Kemampuan: <span id="levelValue" style="color: #7c3aed;">80</span>%
                            </label>
                            <input type="range" name="level" class="form-control-range"
                                   min="0" max="100" value="80" step="5"
                                   oninput="document.getElementById('levelValue').innerText = this.value">
                            <small class="text-muted">0 = pemula, 100 = expert.</small>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold" style="font-size: 13px;">Kategori</label>
                            <input type="text" name="kategori" class="form-control"
                                   placeholder="frontend / backend / design"
                                   maxlength="50" list="kategoriOptions">
                            <datalist id="kategoriOptions">
                                <option value="frontend">
                                <option value="backend">
                                <option value="design">
                                <option value="database">
                                <option value="tools">
                                <option value="softskill">
                            </datalist>
                            <small class="text-muted">Bisa pilih atau tulis sendiri.</small>
                        </div>

                        <div class="form-group mb-0">
                            <label class="font-weight-bold" style="font-size: 13px;">Urutan</label>
                            <input type="number" name="urutan" class="form-control"
                                   value="0" min="0" max="999" style="max-width: 150px;">
                            <small class="text-muted">Angka kecil = tampil lebih dulu.</small>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow" style="border-radius: 10px;">
                    <div class="card-body">
                        <button type="submit" class="btn btn-block"
                                style="background: #7c3aed; color: #fff; border-radius: 8px;">
                            <i class="fas fa-save mr-1"></i> Simpan Skill
                        </button>
                        <a href="?module=skill" class="btn btn-block btn-outline-secondary"
                           style="border-radius: 8px;">
                            Batal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>