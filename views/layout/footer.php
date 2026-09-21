<?php
if (!isset($profil)) {
    $profil = db()->query("SELECT * FROM profil WHERE id = 1")->fetch() ?: [];
}

$footerKategori = [];
try {
    $footerKategori = db()->query("
        SELECT k.*, COUNT(b.id) AS total
        FROM kategori k
        LEFT JOIN blog b ON b.kategori_id = k.id AND b.status = 'publish'
        GROUP BY k.id ORDER BY k.nama LIMIT 6
    ")->fetchAll();
} catch (Exception $e) {}

$brand1 = !empty($profil['brand_1']) ? $profil['brand_1'] : 'wisnu';
$brand2 = !empty($profil['brand_2']) ? $profil['brand_2'] : 'wibisono';
$namaLengkap = $profil['nama'] ?? 'Wisnu Wibisono';
?>

<!-- ==================== FOOTER ==================== -->
<footer class="footer-wrapper">
  <div class="container">
    <div class="footer-card">

      <div class="row g-4">

        <!-- KOLOM 1: Brand + Kontak -->
        <div class="col-lg-4 col-md-6">
          <div class="footer-brand">
            <?= e($brand1) ?><span><?= e($brand2) ?></span>
          </div>

          <h6 class="footer-heading mt-4">Hubungi Saya</h6>
          <div class="footer-contact">
            <?php if (!empty($profil['email'])): ?>
              <div>
                <i class="bi bi-envelope-fill"></i>
                <a href="mailto:<?= e($profil['email']) ?>"><?= e($profil['email']) ?></a>
              </div>
            <?php endif; ?>
            <?php if (!empty($profil['telepon'])): ?>
              <div>
                <i class="bi bi-telephone-fill"></i>
                <a href="tel:<?= e($profil['telepon']) ?>"><?= e($profil['telepon']) ?></a>
              </div>
            <?php endif; ?>
            <?php if (!empty($profil['alamat'])): ?>
              <div>
                <i class="bi bi-geo-alt-fill"></i>
                <?= e($profil['alamat']) ?>
              </div>
            <?php endif; ?>
          </div>
        </div>

        <!-- KOLOM 2: Kategori -->
        <div class="col-lg-4 col-md-6">
          <h6 class="footer-heading">Kategori Tulisan</h6>
          <div class="footer-links">
            <?php if (empty($footerKategori)): ?>
              <div style="color: #94a3b8; font-size: 0.9rem;">Belum ada kategori</div>
            <?php else: foreach ($footerKategori as $kat): ?>
              <div>
                <a href="<?= BASE_URL ?>/?page=blog&kategori=<?= e($kat['slug']) ?>">
                  <?= e($kat['nama']) ?> <span class="footer-count">(<?= (int)$kat['total'] ?>)</span>
                </a>
              </div>
            <?php endforeach; endif; ?>
          </div>
        </div>

        <!-- KOLOM 3: Tautan -->
        <div class="col-lg-4 col-md-6">
          <h6 class="footer-heading">Tautan</h6>
          <div class="footer-links">
            <div><a href="<?= BASE_URL ?>/?page=home">Beranda</a></div>
            <div><a href="<?= BASE_URL ?>/?page=about">Tentang Saya</a></div>
            <div><a href="<?= BASE_URL ?>/?page=projects">Portfolio</a></div>
            <div><a href="<?= BASE_URL ?>/?page=pendidikan">Pendidikan</a></div>
            <div><a href="<?= BASE_URL ?>/?page=pengalaman">Pengalaman</a></div>
            <div><a href="<?= BASE_URL ?>/?page=contact">Kontak</a></div>
          </div>
        </div>

      </div>

      <!-- DIVIDER -->
      <hr class="footer-divider">

      <!-- SOCIAL -->
      <div class="footer-social">
        <?php if (!empty($profil['email'])): ?>
          <a href="mailto:<?= e($profil['email']) ?>" class="social-circle" target="_blank" title="Email"><i class="bi bi-envelope-fill"></i></a>
        <?php endif; ?>
        <?php if (!empty($profil['whatsapp'])): 
          $wa = preg_replace('/[^0-9]/', '', $profil['whatsapp']);
          if (substr($wa, 0, 1) === '0') $wa = '62' . substr($wa, 1);
        ?>
          <a href="https://wa.me/<?= e($wa) ?>" class="social-circle" target="_blank" title="WhatsApp"><i class="bi bi-whatsapp"></i></a>
        <?php endif; ?>
        <?php if (!empty($profil['telegram'])): ?>
          <a href="https://t.me/<?= e(ltrim($profil['telegram'], '@')) ?>" class="social-circle" target="_blank" title="Telegram"><i class="bi bi-telegram"></i></a>
        <?php endif; ?>
        <?php if (!empty($profil['instagram'])): ?>
          <a href="<?= e($profil['instagram']) ?>" class="social-circle" target="_blank" title="Instagram"><i class="bi bi-instagram"></i></a>
        <?php endif; ?>
        <?php if (!empty($profil['threads'])): ?>
          <a href="<?= e($profil['threads']) ?>" class="social-circle" target="_blank" title="Threads"><i class="bi bi-threads"></i></a>
        <?php endif; ?>
        <?php if (!empty($profil['twitter'])): ?>
          <a href="<?= e($profil['twitter']) ?>" class="social-circle" target="_blank" title="Twitter / X"><i class="bi bi-twitter-x"></i></a>
        <?php endif; ?>
        <?php if (!empty($profil['facebook'])): ?>
          <a href="<?= e($profil['facebook']) ?>" class="social-circle" target="_blank" title="Facebook"><i class="bi bi-facebook"></i></a>
        <?php endif; ?>
        <?php if (!empty($profil['linkedin'])): ?>
          <a href="<?= e($profil['linkedin']) ?>" class="social-circle" target="_blank" title="LinkedIn"><i class="bi bi-linkedin"></i></a>
        <?php endif; ?>
        <?php if (!empty($profil['github'])): ?>
          <a href="<?= e($profil['github']) ?>" class="social-circle" target="_blank" title="GitHub"><i class="bi bi-github"></i></a>
        <?php endif; ?>
        <?php if (!empty($profil['youtube'])): ?>
          <a href="<?= e($profil['youtube']) ?>" class="social-circle" target="_blank" title="YouTube"><i class="bi bi-youtube"></i></a>
        <?php endif; ?>
        <?php if (!empty($profil['tiktok'])): ?>
          <a href="<?= e($profil['tiktok']) ?>" class="social-circle" target="_blank" title="TikTok"><i class="bi bi-tiktok"></i></a>
        <?php endif; ?>
        <?php if (!empty($profil['website'])): ?>
          <a href="<?= e($profil['website']) ?>" class="social-circle" target="_blank" title="Website"><i class="bi bi-globe"></i></a>
        <?php endif; ?>
      </div>

      <!-- COPYRIGHT -->
      <div class="footer-copy">
        &copy; <?= date('Y') ?>
        <strong><?= e($namaLengkap) ?></strong>.
        Dibuat dengan <i class="bi bi-heart-fill" style="color: #ef4444;"></i>
        menggunakan
        <a href="https://getbootstrap.com" class="footer-credit" target="_blank">Bootstrap 5</a>
      </div>

    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>