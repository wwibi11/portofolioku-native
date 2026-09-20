<?php
// modules/blog/edit.php — Form Edit Artikel
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/helper.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    setFlash('danger', 'ID artikel tidak valid.');
    redirect('?module=blog');
}

try {
    $stmt = db()->prepare("SELECT * FROM blog WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);
    $artikel = $stmt->fetch();
} catch (Exception $e) {
    setFlash('danger', 'Gagal memuat: ' . $e->getMessage());
    redirect('?module=blog');
}

if (!$artikel) {
    setFlash('danger', 'Artikel tidak ditemukan.');
    redirect('?module=blog');
}

$kategoriList = [];
try {
    $kategoriList = db()->query("SELECT * FROM kategori ORDER BY nama")->fetchAll();
} catch (Exception $e) {}
?>

<div class="container-fluid px-2 px-md-4">

    <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-3">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-edit" style="color: #7c3aed;"></i>
                Edit Artikel
            </h1>
            <p class="mb-0 text-muted small">
                Edit: <strong><?= htmlspecialchars($artikel['judul']) ?></strong>
            </p>
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
        <input type="hidden" name="id" value="<?= (int)$artikel['id'] ?>">
        <input type="hidden" name="cover_lama" value="<?= htmlspecialchars($artikel['cover'] ?? '') ?>">

        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow mb-3" style="border-radius: 10px;">
                    <div class="card-body">

                        <div class="form-group">
                            <label class="font-weight-bold" style="font-size: 13px;">
                                Judul Artikel <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="judul" class="form-control"
                                   value="<?= htmlspecialchars($artikel['judul']) ?>"
                                   required maxlength="200">
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold" style="font-size: 13px;">
                                Konten Artikel <span class="text-danger">*</span>
                            </label>
                            <textarea name="konten" class="form-control"
                                      rows="15" required><?= htmlspecialchars($artikel['konten']) ?></textarea>
                            <small class="text-muted">
                                Bisa pakai tag HTML sederhana: <code>&lt;p&gt;</code>, <code>&lt;h2&gt;</code>, dll.
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
                             border: 2px solid #eef2f7; border-radius: 8px; display: flex;
                             align-items: center; justify-content: center; margin-bottom: 10px;
                             overflow: hidden;">
                            <?php if (!empty($artikel['cover']) && file_exists(UPLOAD_PATH . $artikel['cover'])): ?>
                                <img src="<?= upload($artikel['cover']) ?>"
                                     style="width: 100%; height: 100%; object-fit: cover;">
                            <?php else: ?>
                                <span class="text-muted small text-center">
                                    <i class="fas fa-cloud-upload-alt fa-2x d-block mb-1"></i>
                                    Belum ada cover
                                </span>
                            <?php endif; ?>
                        </div>
                        <input type="file" name="cover" id="coverInput"
                               class="form-control-file" accept="image/*"
                               onchange="previewCover(this)">
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
                            <label class="font-weight-bold" style="font-size: 12px;">Kategori</label>
                            <select name="kategori_id" class="form-control form-control-sm">
                                <option value="">-- Tanpa Kategori --</option>
                                <?php foreach ($kategoriList as $k): ?>
                                    <option value="<?= (int)$k['id'] ?>"
                                        <?= (int)$artikel['kategori_id'] === (int)$k['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($k['nama']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold" style="font-size: 12px;">Tags</label>
                            <input type="text" name="tags" class="form-control form-control-sm"
                                   value="<?= htmlspecialchars($artikel['tags'] ?? '') ?>"
                                   placeholder="php, bootstrap, tutorial">
                        </div>

                        <div class="form-group mb-0">
                            <label class="font-weight-bold" style="font-size: 12px;">Status</label>
                            <select name="status" class="form-control form-control-sm">
                                <option value="publish" <?= $artikel['status'] === 'publish' ? 'selected' : '' ?>>
                                    Publish
                                </option>
                                <option value="draft" <?= $artikel['status'] === 'draft' ? 'selected' : '' ?>>
                                    Draft
                                </option>
                            </select>
                        </div>

                        <div class="mt-3 pt-3" style="border-top: 1px solid #eef2f7; font-size: 11px; color: #8a94a6;">
                            <div><i class="fas fa-eye"></i> Views: <?= (int)$artikel['views'] ?></div>
                            <div><i class="fas fa-calendar"></i> Dibuat: <?= date('d M Y H:i', strtotime($artikel['created_at'])) ?></div>
                            <div><i class="fas fa-edit"></i> Update: <?= date('d M Y H:i', strtotime($artikel['updated_at'])) ?></div>
                        </div>

                    </div>
                </div>

                <!-- Submit -->
                <div class="card shadow" style="border-radius: 10px;">
                    <div class="card-body">
                        <button type="submit" class="btn btn-block"
                                style="background: #7c3aed; color: #fff; border-radius: 8px;">
                            <i class="fas fa-save mr-1"></i> Update Artikel
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