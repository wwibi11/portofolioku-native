<?php
// modules/kategori/edit.php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/helper.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    setFlash('danger', 'ID tidak valid.');
    redirect('?module=kategori');
}

try {
    $stmt = db()->prepare("SELECT * FROM kategori WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);
    $data = $stmt->fetch();
} catch (Exception $e) {
    setFlash('danger', 'Gagal memuat: ' . $e->getMessage());
    redirect('?module=kategori');
}

if (!$data) {
    setFlash('danger', 'Kategori tidak ditemukan.');
    redirect('?module=kategori');
}
?>

<div class="container-fluid px-2 px-md-4">

    <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-3">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-edit" style="color: #7c3aed;"></i>
                Edit Kategori
            </h1>
            <p class="mb-0 text-muted small">
                Edit: <strong><?= htmlspecialchars($data['nama']) ?></strong>
            </p>
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
        <input type="hidden" name="id" value="<?= (int)$data['id'] ?>">
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow mb-3" style="border-radius: 10px;">
                    <div class="card-body">
                        <div class="form-group mb-0">
                            <label class="font-weight-bold" style="font-size: 13px;">
                                Nama Kategori <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="nama" class="form-control"
                                   value="<?= htmlspecialchars($data['nama']) ?>"
                                   required maxlength="50"
                                   oninput="document.getElementById('slugPreview').innerText = slugify(this.value)">
                            <small class="text-muted">
                                Slug: <code id="slugPreview" style="background: #f1f5f9;
                                padding: 2px 6px; border-radius: 4px;"><?= htmlspecialchars($data['slug']) ?></code>
                                <br>Slug otomatis diperbarui saat disimpan.
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
                            <i class="fas fa-save mr-1"></i> Update
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