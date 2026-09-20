<?php
// Ambil data profil untuk footer
$footerProfil = db()->query("SELECT * FROM profil WHERE id = 1")->fetch() ?: [];

// Ambil kategori untuk footer
$footerKategori = db()->query("SELECT * FROM kategori ORDER BY nama ASC LIMIT 5")->fetchAll();

// ============================================================
// DAFTAR SOSIAL MEDIA
// ============================================================
// key = nama kolom di tabel profil
// isi 'prefix' kalau butuh tambahan di depan (misal mailto:, tel:, wa.me/)
$sosmedList = [
  'email' => [
    'icon'  => 'bi-envelope-fill',
    'label' => 'Email',
    'prefix'=> 'mailto:',
  ],
  'whatsapp' => [
    'icon'  => 'bi-whatsapp',
    'label' => 'WhatsApp',
    'prefix'=> 'https://wa.me/',
    'clean' => true,   // hilangkan karakter non-digit
  ],
  'telegram' => [
    'icon'  => 'bi-telegram',
    'label' => 'Telegram',
    'prefix'=> 'https://t.me/',
  ],
  'instagram' => [
    'icon'  => 'bi-instagram',
    'label' => 'Instagram',
    'prefix'=> '',
  ],
  'threads' => [
    'icon'  => 'bi-threads',
    'label' => 'Threads',
    'prefix'=> '',
  ],
  'twitter' => [
    'icon'  => 'bi-twitter-x',
    'label' => 'X (Twitter)',
    'prefix'=> '',
  ],
  'facebook' => [
    'icon'  => 'bi-facebook',
    'label' => 'Facebook',
    'prefix'=> '',
  ],
  'linkedin' => [
    'icon'  => 'bi-linkedin',
    'label' => 'LinkedIn',
    'prefix'=> '',
  ],
  'github' => [
    'icon'  => 'bi-github',
    'label' => 'GitHub',
    'prefix'=> '',
  ],
  'youtube' => [
    'icon'  => 'bi-youtube',
    'label' => 'YouTube',
    'prefix'=> '',
  ],
  'tiktok' => [
    'icon'  => 'bi-tiktok',
    'label' => 'TikTok',
    'prefix'=> '',
  ],
  'website' => [
    'icon'  => 'bi-globe2',
    'label' => 'Website',
    'prefix'=> '',
  ],
];
?>
<!-- ==================== FOOTER ==================== -->
<footer class="footer-dark">
  <div class="container">

    <!-- Bagian Atas: 3 Kolom -->
    <div class="row g-5 pb-5">

      <!-- Kolom 1: Brand & Kontak -->
      <div class="col-lg-4 col-md-6">
        <h3 class="footer-brand"><?= e($footerProfil['nama'] ?? APP_NAME) ?></h3>

        <h6 class="footer-heading mt-4">Hubungi Saya</h6>
        <ul class="footer-contact list-unstyled mb-0">
          <?php if (!empty($footerProfil['email'])): ?>
            <li>
              <i class="bi bi-envelope me-2"></i>
              <a href="mailto:<?= e($footerProfil['email']) ?>"><?= e($footerProfil['email']) ?></a>
            </li>
          <?php endif; ?>
          <?php if (!empty($footerProfil['telepon'])): ?>
            <li>
              <i class="bi bi-telephone me-2"></i>
              <a href="tel:<?= e($footerProfil['telepon']) ?>"><?= e($footerProfil['telepon']) ?></a>
            </li>
          <?php endif; ?>
          <?php if (!empty($footerProfil['alamat'])): ?>
            <li>
              <i class="bi bi-geo-alt me-2"></i>
              <?= e($footerProfil['alamat']) ?>
            </li>
          <?php endif; ?>
        </ul>
      </div>

      <!-- Kolom 2: Kategori Tulisan -->
      <div class="col-lg-4 col-md-6">
        <h6 class="footer-heading">Kategori Tulisan</h6>
        <ul class="footer-links list-unstyled mb-0">
          <?php if (empty($footerKategori)): ?>
            <li><span class="text-muted small">Belum ada kategori</span></li>
          <?php else: foreach ($footerKategori as $kat): ?>
            <li>
              <a href="<?= BASE_URL ?>/?page=blog&kategori=<?= e($kat['slug']) ?>">
                <?= e($kat['nama']) ?>
              </a>
            </li>
          <?php endforeach; endif; ?>
        </ul>
      </div>

      <!-- Kolom 3: Tautan -->
      <div class="col-lg-4 col-md-12">
        <h6 class="footer-heading">Tautan</h6>
        <ul class="footer-links list-unstyled mb-0">
          <li><a href="<?= BASE_URL ?>/?page=home">Beranda</a></li>
          <li><a href="<?= BASE_URL ?>/?page=about">Tentang Saya</a></li>
          <li><a href="<?= BASE_URL ?>/?page=projects">Portfolio</a></li>
          <li><a href="<?= BASE_URL ?>/?page=pendidikan">Pendidikan</a></li>
          <li><a href="<?= BASE_URL ?>/?page=pengalaman">Pengalaman</a></li>
          <li><a href="<?= BASE_URL ?>/?page=blog">Blog</a></li>
          <li><a href="<?= BASE_URL ?>/?page=contact">Kontak</a></li>
        </ul>
      </div>

    </div>

    <!-- Divider -->
    <hr class="footer-divider">

    <!-- Sosial Media -->
    <div class="footer-social mb-4">
      <?php foreach ($sosmedList as $key => $s):
        $value = $footerProfil[$key] ?? null;
        if (empty($value)) continue;

        // Bersihkan nomor HP untuk WhatsApp
        if (!empty($s['clean'])) {
            $value = preg_replace('/[^0-9]/', '', $value);
            // Ganti 0 di depan jadi 62 (Indonesia)
            if (substr($value, 0, 1) === '0') {
                $value = '62' . substr($value, 1);
            }
        }

        // Kalau bukan URL lengkap, tambah prefix
        if (!empty($s['prefix']) && !preg_match('/^https?:\/\//', $value) && !str_starts_with($value, 'mailto:')) {
            $url = $s['prefix'] . $value;
        } else {
            $url = $value;
        }
      ?>
        <a href="<?= e($url) ?>"
           target="_blank"
           rel="noopener noreferrer"
           class="social-circle"
           title="<?= e($s['label']) ?>"
           aria-label="<?= e($s['label']) ?>">
          <i class="bi <?= $s['icon'] ?>"></i>
        </a>
      <?php endforeach; ?>
    </div>

    <!-- Copyright -->
    <div class="footer-copy text-center">
      <small>
        Dibuat oleh
        <a href="<?= BASE_URL ?>" class="footer-credit">
          <?= e($footerProfil['nama'] ?? APP_NAME) ?>
        </a>,
        menggunakan <strong>Bootstrap 5</strong>
      </small>
    </div>

  </div>
</footer>

<!-- Scroll to top -->
<button id="scrollTop" onclick="window.scrollTo({top:0, behavior:'smooth'})" aria-label="Scroll ke atas">
  <i class="bi bi-chevron-up"></i>
</button>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  window.addEventListener('scroll', () => {
    const btn = document.getElementById('scrollTop');
    if (window.scrollY > 300) btn.classList.add('show');
    else btn.classList.remove('show');
  });

  window.addEventListener('scroll', () => {
    const nav = document.querySelector('.navbar');
    if (window.scrollY > 10) nav.classList.add('shadow-sm');
    else nav.classList.remove('shadow-sm');
  });
</script>
</body>
</html>