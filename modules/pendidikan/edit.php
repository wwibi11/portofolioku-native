<?php
// modules/pendidikan/edit.php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/helper.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    setFlash('danger', 'ID tidak valid.');
    redirect('?module=pendidikan');
}

try {
    $stmt = db()->prepare("SELECT * FROM pendidikan WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);
    $data = $stmt->fetch();
} catch (Exception $e) {
    setFlash('danger', 'Gagal memuat: ' . $e->getMessage());
    redirect('?module=pendidikan');
}

if (!$data) {
    setFlash('danger', 'Data tidak ditemukan.');
    redirect('?module=pendidikan');
}
?>

<div class="container-fluid px-2 px-md-4">

    <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-3">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-edit" style="color: #7c3aed;"></i>
                Edit Pendidikan
            </h1>
            <p class="mb-0 text-muted small">
                Edit: <strong><?= htmlspecialchars($data['institusi']) ?></strong>
            </p>
        </div>
        <a href="?module=pendidikan" class="btn btn-sm btn-outline-secondary mt-2 mt-sm-0"
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

    <form action="?module=pendidikan&action=save" method="post">
        <input type="hidden" name="id" value="<?= (int)$data['id'] ?>">
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow mb-3" style="border-radius: 10px;">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold" style="font-size: 13px;">
                                        Jenjang <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="jenjang" class="form-control"
                                           value="<?= htmlspecialchars($data['jenjang']) ?>"
                                           required maxlength="50" list="jenjangList">
                                    <datalist id="jenjangList">
                                        <option value="SD"><option value="SMP"><option value="SMA">
                                        <option value="SMK"><option value="D3"><option value="D4">
                                        <option value="S1"><option value="S2"><option value="S3">
                                    </datalist>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label class="font-weight-bold" style="font-size: 13px;">
                                        Institusi <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="institusi" class="form-control"
                                           value="<?= htmlspecialchars($data['institusi']) ?>"
                                           required maxlength="150">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold" style="font-size: 13px;">Jurusan</label>
                            <input type="text" name="jurusan" class="form-control"
                                   value="<?= htmlspecialchars($data['jurusan']) ?>"
                                   maxlength="100">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold" style="font-size: 13px;">
                                        Tahun Mulai <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" name="tahun_mulai" class="form-control"
                                           value="<?= (int)$data['tahun_mulai'] ?>"
                                           min="1900" max="2100" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold" style="font-size: 13px;">
                                        Tahun Selesai
                                    </label>
                                    <input type="number" name="tahun_selesai" class="form-control"
                                           value="<?= $data['tahun_selesai'] ? (int)$data['tahun_selesai'] : '' ?>"
                                           min="1900" max="2100">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <label class="font-weight-bold" style="font-size: 13px;">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control"
                                      rows="4"><?= htmlspecialchars($data['deskripsi']) ?></textarea>
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
                        <a href="?module=pendidikan" class="btn btn-block btn-outline-secondary"
                           style="border-radius: 8px;">
                            Batal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>