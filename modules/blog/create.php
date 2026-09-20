<?php
// modules/blog/create.php — Form Tulis Artikel
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/helper.php';

$kategoriList = [];
try {
    $kategoriList = db()->query("SELECT * FROM kategori ORDER BY nama")->fetchAll();
} catch (Exception $e) {}
?>

<div class="container-fluid px-2 px-md-4">

    <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-3">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-pen" style="color: #7c3aed;"></i>
                Tulis Artikel
            </h1>
            <p class="mb-0 text-muted small">Tulis artikel baru untuk blog.</p>
        </div>
        <a href="?module=blog" class="btn btn-sm btn-outline-secondary mt-2 mt-sm-0"
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

    <form action="?module=blog&action=save" method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow mb-3" style="border-radius: 10px;">
                    <div class="card-body">

                        <div class="form-group">
                            <label class="font-weight-bold" style="font-size: 13px;">
                                Judul Artikel <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="judul" class="form-control"
                                   placeholder="Contoh: Belajar Bootstrap 5 dari Nol"
                                   required maxlength="200">
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold" style="font-size: 13px;">
                                Konten Artikel <span class="text-danger">*</span>
                            </label>
                            <textarea name="konten" class="form-control" rows="15"
                                      placeholder="Tulis konten di sini. Bisa pakai HTML: <p>, <h2>, <strong>, <ul>, <li>, <a>, dll."
                                      required></textarea>
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i>
                                Bisa pakai tag HTML sederhana: <code>&lt;p&gt;</code>, <code>&lt;h2&gt;</code>,
                                <code>&lt;strong&gt;</code>, <code>&lt;ul&gt;&lt;li&gt;</code>, <code>&lt;a href=""&gt;</code>
                            </small>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-lg-4">

                <!-- Cover -->
                <div class="card shadow mb-3" style="border-radius: 10px;">
                    <div class="card-header py-2 bg-white"
                         style="border-bottom: 1px solid #eef2f7;">
                        <h6 class="m-0 font-weight-bold" style="color: #7c3aed; font-size: 13px;">
                            <i class="fas fa-image"></i> Cover Artikel
                        </h6>
                    </div>
                    <div class="card-body">
                        <div id="coverPreview" style="width: 100%; aspect-ratio: 16/10; background: #f8f9fc;
                             border: 2px dashed #cbd5e1; border-radius: 8px; display: flex;
                             align-items: center; justify-content: center; margin-bottom: 10px;
                             overflow: hidden;">
                            <span class="text-muted small text-center">
                                <i class="fas fa-cloud-upload-alt fa-2x d-block mb-1"></i>
                                Preview
                            </span>
                        </div>
                        <input type="file" name="cover" id="coverInput"
                               class="form-control-file" accept="image/*"
                               onchange="previewCover(this)">
                        <small class="text-muted d-block mt-1">
                            Format: JPG, PNG, WebP. Max 2MB.
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
                            <label class="font-weight-bold" style="font-size: 12px;">Kategori</label>
                            <select name="kategori_id" class="form-control form-control-sm">
                                <option value="">-- Tanpa Kategori --</option>
                                <?php foreach ($kategoriList as $k): ?>
                                    <option value="<?= (int)$k['id'] ?>">
                                        <?= htmlspecialchars($k['nama']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold" style="font-size: 12px;">Tags</label>
                            <input type="text" name="tags" class="form-control form-control-sm"
                                   placeholder="php, bootstrap, tutorial">
                            <small class="text-muted">Pisah dengan koma.</small>
                        </div>

                        <div class="form-group mb-0">
                            <label class="font-weight-bold" style="font-size: 12px;">Status</label>
                            <select name="status" class="form-control form-control-sm">
                                <option value="publish">Publish (tampil di blog)</option>
                                <option value="draft">Draft (simpan dulu)</option>
                            </select>
                        </div>

                    </div>
                </div>

                <!-- Submit -->
                <div class="card shadow" style="border-radius: 10px;">
                    <div class="card-body">
                        <button type="submit" class="btn btn-block"
                                style="background: #7c3aed; color: #fff; border-radius: 8px;">
                            <i class="fas fa-save mr-1"></i> Simpan Artikel
                        </button>
                        <a href="?module=blog" class="btn btn-block btn-outline-secondary"
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
function previewCover(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var el = document.getElementById('coverPreview');
            el.innerHTML = '<img src="' + e.target.result +
                '" style="width: 100%; height: 100%; object-fit: cover;">';
            el.style.border = '2px solid #7c3aed';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>