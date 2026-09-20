<?php
// modules/pengalaman/edit.php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/helper.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    setFlash('danger', 'ID tidak valid.');
    redirect('?module=pengalaman');
}

try {
    $stmt = db()->prepare("SELECT * FROM pengalaman WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);
    $data = $stmt->fetch();
} catch (Exception $e) {
    setFlash('danger', 'Gagal memuat: ' . $e->getMessage());
    redirect('?module=pengalaman');
}

if (!$data) {
    setFlash('danger', 'Pengalaman tidak ditemukan.');
    redirect('?module=pengalaman');
}
?>

<div class="container-fluid px-2 px-md-4">

    <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-3">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-edit" style="color: #7c3aed;"></i>
                Edit Pengalaman
            </h1>
            <p class="mb-0 text-muted small">
                Edit: <strong><?= htmlspecialchars($data['posisi']) ?></strong>
            </p>
        </div>
        <a href="?module=pengalaman" class="btn btn-sm btn-outline-secondary mt-2 mt-sm-0"
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

    <form action="?module=pengalaman&action=save" method="post">
        <input type="hidden" name="id" value="<?= (int)$data['id'] ?>">
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow mb-3" style="border-radius: 10px;">
                    <div class="card-body">

                        <div class="form-group">
                            <label class="font-weight-bold" style="font-size: 13px;">
                                Posisi / Jabatan <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="posisi" class="form-control"
                                   value="<?= htmlspecialchars($data['posisi']) ?>"
                                   required maxlength="100">
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold" style="font-size: 13px;">
                                Perusahaan <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="perusahaan" class="form-control"
                                   value="<?= htmlspecialchars($data['perusahaan']) ?>"
                                   required maxlength="100">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold" style="font-size: 13px;">
                                        Tanggal Mulai <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" name="mulai" class="form-control"
                                           value="<?= htmlspecialchars($data['mulai']) ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold" style="font-size: 13px;">
                                        Tanggal Selesai
                                    </label>
                                    <input type="date" name="selesai" class="form-control"
                                           value="<?= htmlspecialchars($data['selesai']) ?>">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <label class="font-weight-bold" style="font-size: 13px;">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control"
                                      rows="5"><?= htmlspecialchars($data['deskripsi']) ?></textarea>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow mb-3" style="border-radius: 10px;">
                    <div class="card-body">
                        <div class="form-group mb-0">
                            <label class="font-weight-bold" style="font-size: 13px;">Urutan</label>
                            <input type="number" name="urutan" class="form-control"
                                   value="<?= (int)$data['urutan'] ?>" min="0" max="999">
                        </div>
                    </div>
                </div>

                <div class="card shadow" style="border-radius: 10px;">
                    <div class="card-body">
                        <button type="submit" class="btn btn-block"
                                style="background: #7c3aed; color: #fff; border-radius: 8px;">
                            <i class="fas fa-save mr-1"></i> Update
                        </button>
                        <a href="?module=pengalaman" class="btn btn-block btn-outline-secondary"
                           style="border-radius: 8px;">
                            Batal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>