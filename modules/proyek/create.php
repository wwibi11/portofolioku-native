<?php
// modules/proyek/create.php — Form Tambah Proyek
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/helper.php';
?>

<div class="container-fluid px-2 px-md-4">

    <!-- HEADER -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-3">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-plus-circle" style="color: #7c3aed;"></i>
                Tambah Proyek
            </h1>
            <p class="mb-0 text-muted small">Tambahkan proyek baru ke portofolio Anda.</p>
        </div>
        <a href="?module=proyek" class="btn btn-sm btn-outline-secondary mt-2 mt-sm-0"
           style="border-radius: 8px;">
            <i class="fas fa-arrow-left"></i> Kembali
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

    <!-- FORM -->
    <form action="?module=proyek&action=save" method="post" enctype="multipart/form-data">
        <div class="row">
            <!-- Kolom Kiri -->
            <div class="col-lg-8">
                <div class="card shadow mb-3" style="border-radius: 10px;">
                    <div class="card-body">

                        <div class="form-group">
                            <label class="font-weight-bold" style="font-size: 13px;">
                                Judul Proyek <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="judul" class="form-control"
                                   placeholder="Contoh: Portal Klaim BRI"
                                   required maxlength="150">
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold" style="font-size: 13px;">
                                Deskripsi Singkat <span class="text-danger">*</span>
                            </label>
                            <textarea name="deskripsi_singkat" class="form-control" rows="3"
                                      placeholder="Ringkasan proyek (1-3 kalimat)"
                                      required></textarea>
                            <small class="text-muted">Tampil di halaman portfolio grid.</small>
                        </div>

                        <div class="form-group mb-0">
                            <label class="font-weight-bold" style="font-size: 13px;">
                                Deskripsi Lengkap
                            </label>
                            <textarea name="deskripsi_lengkap" class="form-control" rows="8"
                                      placeholder="Detail lengkap proyek (opsional)"></textarea>
                            <small class="text-muted">Tampil di halaman detail proyek.</small>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="col-lg-4">

                <!-- Thumbnail -->
                <div class="card shadow mb-3" style="border-radius: 10px;">
                    <div class="card-header py-2 bg-white"
                         style="border-bottom: 1px solid #eef2f7;">
                        <h6 class="m-0 font-weight-bold" style="color: #7c3aed; font-size: 13px;">
                            <i class="fas fa-image"></i> Thumbnail
                        </h6>
                    </div>
                    <div class="card-body">
                        <div id="thumbPreview" style="width: 100%; aspect-ratio: 16/10; background: #f8f9fc;
                             border: 2px dashed #cbd5e1; border-radius: 8px; display: flex;
                             align-items: center; justify-content: center; margin-bottom: 10px;
                             overflow: hidden;">
                            <span class="text-muted small">
                                <i class="fas fa-cloud-upload-alt fa-2x d-block text-center mb-1"></i>
                                Preview
                            </span>
                        </div>
                        <input type="file" name="thumbnail" id="thumbInput"
                               class="form-control-file" accept="image/*"
                               onchange="previewThumb(this)">
                        <small class="text-muted d-block mt-1">
                            Format: JPG, PNG, WebP. Max 2MB. Rasio 16:10.
                        </small>
                    </div>
                </div>

                <!-- Meta -->
                <div class="card shadow mb-3" style="border-radius: 10px;">
                    <div class="card-header py-2 bg-white"
                         style="border-bottom: 1px solid #eef2f7;">
                        <h6 class="m-0 font-weight-bold" style="color: #7c3aed; font-size: 13px;">
                            <i class="fas fa-cog"></i> Meta
                        </h6>
                    </div>
                    <div class="card-body">

                        <div class="form-group">
                            <label class="font-weight-bold" style="font-size: 12px;">Tech Stack</label>
                            <input type="text" name="tech_stack" class="form-control form-control-sm"
                                   placeholder="PHP, MySQL, Bootstrap"
                                   data-role="tagsinput">
                            <small class="text-muted">Pisah dengan koma.</small>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold" style="font-size: 12px;">Link Demo</label>
                            <input type="url" name="link_demo" class="form-control form-control-sm"
                                   placeholder="https://...">
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold" style="font-size: 12px;">Link GitHub</label>
                            <input type="url" name="link_github" class="form-control form-control-sm"
                                   placeholder="https://github.com/...">
                        </div>

                        <div class="form-group mb-0">
                            <label class="font-weight-bold" style="font-size: 12px;">Status</label>
                            <select name="status" class="form-control form-control-sm">
                                <option value="publish">Publish (tampil di publik)</option>
                                <option value="draft">Draft (sembunyikan)</option>
                            </select>
                        </div>

                    </div>
                </div>

                <!-- Submit -->
                <div class="card shadow" style="border-radius: 10px;">
                    <div class="card-body">
                        <button type="submit" class="btn btn-block"
                                style="background: #7c3aed; color: #fff; border-radius: 8px;">
                            <i class="fas fa-save mr-1"></i> Simpan Proyek
                        </button>
                        <a href="?module=proyek" class="btn btn-block btn-outline-secondary"
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
function previewThumb(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var el = document.getElementById('thumbPreview');
            el.innerHTML = '<img src="' + e.target.result +
                '" style="width: 100%; height: 100%; object-fit: cover;">';
            el.style.border = '2px solid #7c3aed';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>