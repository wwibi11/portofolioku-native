<?php $pageTitle = 'Kontak | ' . APP_NAME; ?>
<section class="section">
  <div class="container">
    <div class="mb-5">
      <h1 class="section-title">Kontak</h1>
      <p class="section-subtitle">Mari terhubung! Kirim pesan atau hubungi saya lewat sosial media</p>
    </div>

    <div class="row g-5">
      <div class="col-lg-5">
        <?php
        $profil = db()->query("SELECT * FROM profil WHERE id = 1")->fetch() ?: [];
        ?>
        <div class="mb-3">
          <i class="bi bi-envelope-fill text-primary me-2"></i>
          <a href="mailto:<?= e($profil['email'] ?? '') ?>"><?= e($profil['email'] ?? '-') ?></a>
        </div>
        <div class="mb-3">
          <i class="bi bi-telephone-fill text-primary me-2"></i>
          <?= e($profil['telepon'] ?? '-') ?>
        </div>
        <div class="mb-3">
          <i class="bi bi-geo-alt-fill text-primary me-2"></i>
          <?= e($profil['alamat'] ?? '-') ?>
        </div>

        <div class="mt-4">
          <?php if (!empty($profil['github'])): ?>
            <a href="<?= e($profil['github']) ?>" target="_blank" class="btn btn-outline-dark btn-sm me-1">
              <i class="bi bi-github"></i>
            </a>
          <?php endif; ?>
          <?php if (!empty($profil['linkedin'])): ?>
            <a href="<?= e($profil['linkedin']) ?>" target="_blank" class="btn btn-outline-primary btn-sm me-1">
              <i class="bi bi-linkedin"></i>
            </a>
          <?php endif; ?>
          <?php if (!empty($profil['instagram'])): ?>
            <a href="<?= e($profil['instagram']) ?>" target="_blank" class="btn btn-outline-danger btn-sm me-1">
              <i class="bi bi-instagram"></i>
            </a>
          <?php endif; ?>
        </div>
      </div>

      <div class="col-lg-7">
        <?php $flash = getFlash(); ?>
        <?php if ($flash): ?>
          <div class="alert alert-<?= $flash['type'] ?>"><?= e($flash['message']) ?></div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/modules/pesan/kirim.php" method="POST" class="card border-0 shadow-sm p-4">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label small">Nama</label>
              <input type="text" name="nama" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label small">Email</label>
              <input type="email" name="email" class="form-control" required>
            </div>
            <div class="col-12">
              <label class="form-label small">Subjek</label>
              <input type="text" name="subjek" class="form-control">
            </div>
            <div class="col-12">
              <label class="form-label small">Pesan</label>
              <textarea name="pesan" rows="5" class="form-control" required></textarea>
            </div>
            <div class="col-12">
              <button class="btn btn-primary px-4"><i class="bi bi-send"></i> Kirim Pesan</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>