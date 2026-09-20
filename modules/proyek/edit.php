<?php
// modules/proyek/edit.php — Form Edit Proyek
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/helper.php';

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    setFlash('danger', 'ID proyek tidak valid.');
    redirect('?module=proyek');
}

try {
    $stmt = db()->prepare("SELECT * FROM proyek WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);
    $proyek = $stmt->fetch();
} catch (Exception $e) {
    setFlash('danger', 'Gagal memuat data: ' . $e->getMessage());
    redirect('?module=proyek');
}

if (!$proyek) {
    setFlash('danger', 'Proyek tidak ditemukan.');
    redirect('?module=proyek');
}
?>

<div class="container-fluid px-2 px-md-4">

    <!-- HEADER -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-3">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-edit" style="color: #7c3aed;"></i>
                Edit Proyek
            </h1>
            <p class="mb-0 text-muted small">
                Edit: <strong><?= htmlspecialchars($proyek['judul']) ?></strong>
            </p>
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
        <input type="hidden" name="id" value="<?= (int)$proyek['id'] ?>">
        <input type="hidden" name="thumbnail_lama" value="<?= htmlspecialchars($proyek['thumbnail'] ?? '') ?>">

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
                                   value="<?= htmlspecialchars($proyek['judul']) ?>"
                                   required maxlength="150">
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold" style="font-size: 13px;">
                                Deskripsi Singkat <span class="text-danger">*</span>
                            </label>
                            <textarea name="deskripsi_singkat" class="form-control" rows="3"
                                      required><?= htmlspecialchars($proyek['deskripsi_singkat']) ?></textarea>
                        </div>

                        <div class="form-group mb-0">
                            <label class="font-weight-bold" style="font-size: 13px;">
                                Deskripsi Lengkap
                            </label>
                            <textarea name="deskripsi_lengkap" class="form-control"
                                      rows="8"><?= htmlspecialchars($proyek['deskripsi_lengkap'] ?? '') ?></textarea>
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
                             border: 2px solid #eef2f7; border-radius: 8px; display: flex;
                             align-items: center; justify-content: center; margin-bottom: 10px;
                             overflow: hidden;">
                            <?php if (!empty($proyek['thumbnail']) && file_exists(UPLOAD_PATH . $proyek['thumbnail'])): ?>
                                <img src="<?= upload($proyek['thumbnail']) ?>"
                                     style="width: 100%; height: 100%; object-fit: cover;">
                            <?php else: ?>
                                <span class="text-muted small">
                                    <i class="fas fa-cloud-upload-alt fa-2x d-block text-center mb-1"></i>
                                    Belum ada thumbnail
                                </span>
                            <?php endif; ?>
                        </div>
                        <input type="file" name="thumbnail" id="thumbInput"
                               class="form-control-file" accept="image/*"
                               onchange="previewThumb(this)">
                        <small class="text-muted d-block mt-1">
                            Kosongkan jika tidak ingin ganti.
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
                                   value="<?= htmlspecialchars($proyek['tech_stack'] ?? '') ?>"
                                   placeholder="PHP, MySQL, Bootstrap">
                            <small class="text-muted">Pisah dengan koma.</small>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold" style="font-size: 12px;">Link Demo</label>
                            <input type="url" name="link_demo" class="form-control form-control-sm"
                                   value="<?= htmlspecialchars($proyek['link_demo'] ?? '') ?>"
                                   placeholder="https://...">
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold" style="font-size: 12px;">Link GitHub</label>
                            <input type="url" name="link_github" class="form-control form-control-sm"
                                   value="<?= htmlspecialchars($proyek['link_github'] ?? '') ?>"
                                   placeholder="https://github.com/...">
                        </div>

                        <div class="form-group mb-0">
                            <label class="font-weight-bold" style="font-size: 12px;">Status</label>
                            <select name="status" class="form-control form-control-sm">
                                <option value="publish" <?= $proyek['status'] === 'publish' ? 'selected' : '' ?>>
                                    Publish (tampil di publik)
                                </option>
                                <option value="draft" <?= $proyek['status'] === 'draft' ? 'selected' : '' ?>>
                                    Draft (sembunyikan)
                                </option>
                            </select>
                        </div>

                    </div>
                </div>

                <!-- Submit -->
                <div class="card shadow" style="border-radius: 10px;">
                    <div class="card-body">
                        <button type="submit" class="btn btn-block"
                                style="background: #7c3aed; color: #fff; border-radius: 8px;">
                            <i class="fas fa-save mr-1"></i> Update Proyek
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