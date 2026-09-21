<?php
// modules/profil/index.php — Form Edit Profil
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/helper.php';

// Ambil data profil (selalu id=1)
try {
    $stmt = db()->query("SELECT * FROM profil WHERE id = 1 LIMIT 1");
    $profil = $stmt->fetch();
} catch (Exception $e) {
    $profil = null;
    setFlash('danger', 'Gagal memuat profil: ' . $e->getMessage());
}

// Kalau belum ada, siapkan default
if (!$profil) {
    $profil = [
        'id' => 1, 'nama' => '', 'gelar_akademik' => '',
        'brand_1' => 'wisnu', 'brand_2' => 'wibisono',
        'gelar' => '', 'bio' => '', 'foto' => '',
        'email' => '', 'telepon' => '', 'whatsapp' => '', 'telegram' => '',
        'alamat' => '', 'website' => '', 'github' => '', 'linkedin' => '',
        'instagram' => '', 'threads' => '', 'facebook' => '', 'twitter' => '',
        'youtube' => '', 'tiktok' => '', 'cv_file' => ''
    ];
}
?>

<div class="container-fluid px-2 px-md-4">

    <!-- HEADER -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-3">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user" style="color: #7c3aed;"></i>
                Edit Profil
            </h1>
            <p class="mb-0 text-muted small">
                Kelola informasi pribadi & sosial media Anda.
            </p>
        </div>
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
    <form action="?module=profil&action=save" method="post" enctype="multipart/form-data">
        <div class="row">

            <!-- KOLOM KIRI -->
            <div class="col-lg-8">

                <!-- Info Dasar -->
                <div class="card shadow mb-3" style="border-radius: 10px;">
                    <div class="card-header py-3 bg-white"
                         style="border-bottom: 1px solid #eef2f7;">
                        <h6 class="m-0 font-weight-bold" style="color: #7c3aed;">
                            <i class="fas fa-id-card"></i> Informasi Dasar
                        </h6>
                    </div>
                    <div class="card-body">

                        <!-- Baris 1: Nama + Gelar Akademik -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold" style="font-size: 13px;">
                                        Nama Lengkap <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="nama" class="form-control"
                                        value="<?= htmlspecialchars($profil['nama']) ?>"
                                        required maxlength="100">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold" style="font-size: 13px;">
                                        Gelar Akademik
                                    </label>
                                    <input type="text" name="gelar_akademik" class="form-control"
                                        value="<?= htmlspecialchars($profil['gelar_akademik'] ?? '') ?>"
                                        placeholder="S.Kom., M.Kom., S.T., dll"
                                        maxlength="50">
                                    <small class="text-muted">Tampil setelah nama.</small>
                                </div>
                            </div>
                        </div>

                        <!-- Baris 2: Gelar / Profesi -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="font-weight-bold" style="font-size: 13px;">Gelar / Profesi</label>
                                    <input type="text" name="gelar" class="form-control"
                                        value="<?= htmlspecialchars($profil['gelar']) ?>"
                                        placeholder="Web Developer & UI Designer"
                                        maxlength="100">
                                    <small class="text-muted">Tampil sebagai subtitle di bawah nama.</small>
                                </div>
                            </div>
                        </div>

                        <!-- Baris 3: Brand Logo -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold" style="font-size: 13px;">
                                        Brand Logo — Kata 1
                                    </label>
                                    <input type="text" name="brand_1" class="form-control"
                                           value="<?= htmlspecialchars($profil['brand_1'] ?? 'wisnu') ?>"
                                           placeholder="wisnu" maxlength="50">
                                    <small class="text-muted">Logo navbar kiri (hitam).</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold" style="font-size: 13px;">
                                        Brand Logo — Kata 2
                                    </label>
                                    <input type="text" name="brand_2" class="form-control"
                                           value="<?= htmlspecialchars($profil['brand_2'] ?? 'wibisono') ?>"
                                           placeholder="wibisono" maxlength="50">
                                    <small class="text-muted">Logo navbar kanan (ungu).</small>
                                </div>
                            </div>
                        </div>

                        <!-- Baris 4: Bio -->
                        <div class="form-group mb-0">
                            <label class="font-weight-bold" style="font-size: 13px;">Bio</label>
                            <textarea name="bio" class="form-control" rows="4"
                                      placeholder="Ceritakan singkat tentang Anda..."><?= htmlspecialchars($profil['bio']) ?></textarea>
                            <small class="text-muted">Tampil di halaman "Tentang Saya".</small>
                        </div>

                    </div>
                </div>

                <!-- Kontak -->
                <div class="card shadow mb-3" style="border-radius: 10px;">
                    <div class="card-header py-3 bg-white"
                         style="border-bottom: 1px solid #eef2f7;">
                        <h6 class="m-0 font-weight-bold" style="color: #7c3aed;">
                            <i class="fas fa-address-book"></i> Kontak
                        </h6>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold" style="font-size: 13px;">
                                        <i class="fas fa-envelope text-muted"></i> Email
                                    </label>
                                    <input type="email" name="email" class="form-control"
                                           value="<?= htmlspecialchars($profil['email']) ?>"
                                           placeholder="email@example.com" maxlength="100">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold" style="font-size: 13px;">
                                        <i class="fas fa-phone text-muted"></i> Telepon
                                    </label>
                                    <input type="text" name="telepon" class="form-control"
                                           value="<?= htmlspecialchars($profil['telepon']) ?>"
                                           placeholder="08xxxxxxxxxx" maxlength="20">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold" style="font-size: 13px;">
                                        <i class="fab fa-whatsapp text-success"></i> WhatsApp
                                    </label>
                                    <input type="text" name="whatsapp" class="form-control"
                                           value="<?= htmlspecialchars($profil['whatsapp']) ?>"
                                           placeholder="628xxxxxxxxxx" maxlength="20">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold" style="font-size: 13px;">
                                        <i class="fab fa-telegram text-info"></i> Telegram
                                    </label>
                                    <input type="text" name="telegram" class="form-control"
                                           value="<?= htmlspecialchars($profil['telegram']) ?>"
                                           placeholder="@username" maxlength="150">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <label class="font-weight-bold" style="font-size: 13px;">
                                <i class="fas fa-map-marker-alt text-danger"></i> Alamat
                            </label>
                            <input type="text" name="alamat" class="form-control"
                                   value="<?= htmlspecialchars($profil['alamat']) ?>"
                                   placeholder="Jakarta, Indonesia" maxlength="255">
                        </div>

                    </div>
                </div>

                <!-- Sosial Media -->
                <div class="card shadow mb-3" style="border-radius: 10px;">
                    <div class="card-header py-3 bg-white"
                         style="border-bottom: 1px solid #eef2f7;">
                        <h6 class="m-0 font-weight-bold" style="color: #7c3aed;">
                            <i class="fas fa-share-alt"></i> Sosial Media
                        </h6>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold" style="font-size: 13px;">
                                        <i class="fas fa-globe text-primary"></i> Website
                                    </label>
                                    <input type="url" name="website" class="form-control"
                                           value="<?= htmlspecialchars($profil['website']) ?>"
                                           placeholder="https://wisnuwb.my.id">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold" style="font-size: 13px;">
                                        <i class="fab fa-github"></i> GitHub
                                    </label>
                                    <input type="url" name="github" class="form-control"
                                           value="<?= htmlspecialchars($profil['github']) ?>"
                                           placeholder="https://github.com/username">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold" style="font-size: 13px;">
                                        <i class="fab fa-linkedin text-primary"></i> LinkedIn
                                    </label>
                                    <input type="url" name="linkedin" class="form-control"
                                           value="<?= htmlspecialchars($profil['linkedin']) ?>"
                                           placeholder="https://linkedin.com/in/username">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold" style="font-size: 13px;">
                                        <i class="fab fa-instagram text-danger"></i> Instagram
                                    </label>
                                    <input type="url" name="instagram" class="form-control"
                                           value="<?= htmlspecialchars($profil['instagram']) ?>"
                                           placeholder="https://instagram.com/username">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold" style="font-size: 13px;">
                                        <i class="fas fa-at"></i> Threads
                                    </label>
                                    <input type="url" name="threads" class="form-control"
                                           value="<?= htmlspecialchars($profil['threads']) ?>"
                                           placeholder="https://threads.net/@username">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold" style="font-size: 13px;">
                                        <i class="fab fa-facebook text-primary"></i> Facebook
                                    </label>
                                    <input type="url" name="facebook" class="form-control"
                                           value="<?= htmlspecialchars($profil['facebook']) ?>"
                                           placeholder="https://facebook.com/username">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold" style="font-size: 13px;">
                                        <i class="fab fa-twitter text-info"></i> Twitter / X
                                    </label>
                                    <input type="url" name="twitter" class="form-control"
                                           value="<?= htmlspecialchars($profil['twitter']) ?>"
                                           placeholder="https://x.com/username">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold" style="font-size: 13px;">
                                        <i class="fab fa-youtube text-danger"></i> YouTube
                                    </label>
                                    <input type="url" name="youtube" class="form-control"
                                           value="<?= htmlspecialchars($profil['youtube']) ?>"
                                           placeholder="https://youtube.com/@channel">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <label class="font-weight-bold" style="font-size: 13px;">
                                <i class="fab fa-tiktok"></i> TikTok
                            </label>
                            <input type="url" name="tiktok" class="form-control"
                                   value="<?= htmlspecialchars($profil['tiktok']) ?>"
                                   placeholder="https://tiktok.com/@username">
                        </div>

                    </div>
                </div>

            </div>

            <!-- KOLOM KANAN -->
            <div class="col-lg-4">

                <!-- Foto Profil -->
                <div class="card shadow mb-3" style="border-radius: 10px;">
                    <div class="card-header py-3 bg-white"
                         style="border-bottom: 1px solid #eef2f7;">
                        <h6 class="m-0 font-weight-bold" style="color: #7c3aed;">
                            <i class="fas fa-camera"></i> Foto Profil
                        </h6>
                    </div>
                    <div class="card-body text-center">

                        <div id="fotoPreview"
                             style="width: 180px; height: 180px; margin: 0 auto 15px;
                                    background: #f8f9fc; border-radius: 50%;
                                    border: 3px dashed #cbd5e1; overflow: hidden;
                                    display: flex; align-items: center; justify-content: center;">
                            <?php if (!empty($profil['foto']) && file_exists(UPLOAD_PATH . $profil['foto'])): ?>
                                <img src="<?= upload($profil['foto']) ?>"
                                     style="width: 100%; height: 100%; object-fit: cover;">
                            <?php else: ?>
                                <div class="text-muted">
                                    <i class="fas fa-user-circle fa-4x mb-2"></i>
                                    <div class="small">Belum ada foto</div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <input type="hidden" name="foto_lama" value="<?= htmlspecialchars($profil['foto']) ?>">
                        <input type="file" name="foto" id="fotoInput"
                               class="form-control-file" accept="image/*"
                               onchange="previewFoto(this)">
                        <small class="text-muted d-block mt-2">
                            Format: JPG, PNG, WebP. Max 2MB.
                        </small>

                    </div>
                </div>

                <!-- CV -->
                <div class="card shadow mb-3" style="border-radius: 10px;">
                    <div class="card-header py-3 bg-white"
                         style="border-bottom: 1px solid #eef2f7;">
                        <h6 class="m-0 font-weight-bold" style="color: #7c3aed;">
                            <i class="fas fa-file-pdf"></i> CV / Resume
                        </h6>
                    </div>
                    <div class="card-body">

                        <?php if (!empty($profil['cv_file']) && file_exists(UPLOAD_PATH . $profil['cv_file'])): ?>
                            <div class="alert alert-info py-2 mb-2" style="border-radius: 8px; font-size: 13px;">
                                <i class="fas fa-file-pdf"></i>
                                CV saat ini:
                                <a href="<?= upload($profil['cv_file']) ?>" target="_blank"
                                   class="font-weight-bold">Lihat</a>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-secondary py-2 mb-2" style="border-radius: 8px; font-size: 13px;">
                                <i class="fas fa-info-circle"></i> Belum ada CV.
                            </div>
                        <?php endif; ?>

                        <input type="hidden" name="cv_lama" value="<?= htmlspecialchars($profil['cv_file']) ?>">
                        <input type="file" name="cv_file" class="form-control-file" accept=".pdf">
                        <small class="text-muted d-block mt-2">Format: PDF. Max 5MB.</small>

                    </div>
                </div>

                <!-- Submit -->
                <div class="card shadow" style="border-radius: 10px;">
                    <div class="card-body">
                        <button type="submit" class="btn btn-block"
                                style="background: #7c3aed; color: #fff; border-radius: 8px;">
                            <i class="fas fa-save mr-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>

            </div>

        </div>
    </form>

</div>

<script>
function previewFoto(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var el = document.getElementById('fotoPreview');
            el.innerHTML = '<img src="' + e.target.result +
                '" style="width: 100%; height: 100%; object-fit: cover;">';
            el.style.border = '3px solid #7c3aed';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>