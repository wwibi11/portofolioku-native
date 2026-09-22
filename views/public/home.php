<?php
// Ambil data profil
$profil = db()->query("SELECT * FROM profil WHERE id = 1")->fetch() ?: [];
?>
<!-- HERO -->
<section class="hero">
  <div class="container">
    <div class="row align-items-center">

      <!-- KIRI: Text -->
      <div class="col-lg-7">
      <h1 class="mb-3">
        <span class="hero-halo">Halo, saya</span>
        <span class="hero-nama">
          <?= e($profil['nama'] ?? 'Nama') ?><?= !empty($profil['gelar_akademik']) ? ', ' . e($profil['gelar_akademik']) : '' ?>
        </span>
      </h1>
  

       <p class="lead hero-profesi">
          <?= e($profil['gelar'] ?? 'Web Developer') ?>
        </p>

       <?php
        $bioFull = trim($profil['bio'] ?? '');
        $bioLimit = 250;
        $bioTruncated = false;

        if (strlen($bioFull) > $bioLimit) {
            // Cari titik terakhir sebelum limit
            $cut = substr($bioFull, 0, $bioLimit);
            $lastDot = strrpos($cut, '.');

            if ($lastDot !== false && $lastDot > 100) {
                // Potong di titik
                $bioShort = substr($bioFull, 0, $lastDot + 1);
                $bioTruncated = true;
            } else {
                // Kalau tidak ada titik, potong di spasi terakhir
                $lastSpace = strrpos($cut, ' ');
                $bioShort = ($lastSpace !== false)
                    ? substr($bioFull, 0, $lastSpace) . '...'
                    : $cut . '...';
                $bioTruncated = true;
            }
        } else {
            $bioShort = $bioFull;
        }
        ?>

        <!-- BIO — dengan fallback -->
        <p class="hero-bio mb-4" style="color: #64748b; line-height: 1.75;">
          <?php if ($bioFull === ''): ?>
            <em style="color: #94a3b8;">Bio belum diisi.</em>
          <?php else: ?>
            <?= e($bioShort) ?>
            <?php if ($bioTruncated): ?>
              <a href="<?= BASE_URL ?>/?page=about"
                 style="color: #7c3aed; text-decoration: none; font-weight: 600; white-space: nowrap;">
                Selengkapnya →
              </a>
            <?php endif; ?>
          <?php endif; ?>
        </p>

        <div class="d-flex gap-2 flex-wrap">
          <a href="<?= BASE_URL ?>/?page=contact" class="btn btn-primary">
            <i class="bi bi-envelope-fill"></i> Hubungi Saya
          </a>
          <a href="<?= BASE_URL ?>/?page=projects" class="btn btn-outline-dark">
            <i class="bi bi-folder2-open"></i> Lihat Portfolio
          </a>
        </div>
      </div>

      <!-- KANAN: Foto — simple, TANPA blob -->
      <div class="col-lg-5 text-center mt-4 mt-lg-0">
        <?php if (!empty($profil['foto']) && file_exists(UPLOAD_PATH . $profil['foto'])): ?>
          <img src="<?= upload($profil['foto']) ?>"
               alt="<?= e($profil['nama']) ?>"
               class="hero-photo"
               style="width: 220px; height: 220px; object-fit: cover;
                      border-radius: 50%; border: 4px solid #fff;
                      box-shadow: 0 10px 30px rgba(124,58,237,0.15);">
        <?php else: ?>
          <div style="width: 220px; height: 220px; margin: 0 auto;
                      background: #ede9fe; border-radius: 50%;
                      display: flex; align-items: center; justify-content: center;
                      color: #7c3aed; font-size: 5rem;
                      border: 4px solid #fff;">
            <i class="bi bi-person-fill"></i>
          </div>
        <?php endif; ?>
      </div>

    </div>
  </div>
</section>

<!-- Preview Portfolio -->
<section class="section bg-light">
  <div class="container">
    <div class="d-flex justify-content-between align-items-end mb-4">
      <div>
        <h2 class="section-title">Portfolio Terbaru</h2>
        <p class="section-subtitle mb-0">Beberapa proyek yang pernah saya kerjakan</p>
      </div>
      <a href="<?= BASE_URL ?>/?page=projects" class="btn btn-outline-primary">
        Lihat Semua <i class="bi bi-arrow-right"></i>
      </a>
    </div>

    <div class="row g-4">
      <?php
      $projects = db()->query("SELECT * FROM proyek WHERE status = 'publish' ORDER BY id DESC LIMIT 2")->fetchAll();
      foreach ($projects as $p):
      ?>
        <div class="col-md-6">
          <article class="portfolio-item">
            <div class="portfolio-thumb">
              <?php if (!empty($p['thumbnail']) && file_exists(UPLOAD_PATH . $p['thumbnail'])): ?>
                <img src="<?= UPLOAD_URL . e($p['thumbnail']) ?>" alt="<?= e($p['judul']) ?>">
              <?php else: ?>
                <img src="https://via.placeholder.com/800x500/7c3aed/ffffff?text=<?= urlencode($p['judul']) ?>" alt="<?= e($p['judul']) ?>">
              <?php endif; ?>
            </div>
            <h3 class="portfolio-title"><?= e($p['judul']) ?></h3>
            <a href="<?= BASE_URL ?>/?page=project_detail&slug=<?= e($p['slug']) ?>" class="portfolio-link">
              Lihat Disini
            </a>
            <p class="portfolio-desc"><?= e($p['deskripsi_singkat']) ?></p>
          </article>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>



<!-- SKILL SECTION -->
<?php
$skillsHome = db()->query("SELECT * FROM skills ORDER BY urutan ASC, id ASC LIMIT 8")->fetchAll();
?>

<?php if (!empty($skillsHome)): ?>
<section class="section">
  <div class="container">
    <div class="d-flex justify-content-between align-items-end mb-4">
      <div>
        <h2 class="section-title">Skill & Keahlian</h2>
        <p class="section-subtitle mb-0">Tools dan teknologi yang saya kuasai</p>
      </div>
      <a href="<?= BASE_URL ?>/?page=about" 
         class="btn btn-outline-primary d-none d-md-inline-flex">
        Selengkapnya <i class="bi bi-arrow-right"></i>
      </a>
    </div>

    <div class="row g-3">
      <?php foreach ($skillsHome as $s):
        $level = (int)$s['level'];
      ?>
        <div class="col-md-6 col-lg-3">
          <div style="background: rgba(255,255,255,0.5);
                      border: 1px solid rgba(255,255,255,0.6);
                      border-radius: 14px; padding: 18px;
                      height: 100%; transition: all 0.3s ease;"
               onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 40px rgba(124,58,237,0.1)'"
               onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none'">

            <div class="d-flex justify-content-between align-items-center mb-3">
              <span style="font-weight: 700; color: #0f172a; font-size: 14px;">
                <?= e($s['nama']) ?>
              </span>
              <span style="color: #7c3aed; font-weight: 800; font-size: 12px;
                           background: rgba(124, 58, 237, 0.1);
                           padding: 3px 10px; border-radius: 20px;">
                <?= $level ?>%
              </span>
            </div>

            <div style="height: 6px; background: rgba(245, 158, 11, 0.12);
            border-radius: 10px; overflow: hidden;">
              <div style="width: <?= $level ?>%; height: 100%;
                          background: linear-gradient(90deg, #fbbf24, #f59e0b);
                          border-radius: 10px;"></div>
            </div>

            <?php if (!empty($s['kategori'])): ?>
              <div style="font-size: 11px; color: #94a3b8; margin-top: 8px;
                          text-transform: uppercase; letter-spacing: 0.5px;
                          font-weight: 600;">
                <?= e(ucfirst($s['kategori'])) ?>
              </div>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Mobile "Selengkapnya" button -->
    <div class="text-center mt-4 d-md-none">
      <a href="<?= BASE_URL ?>/?page=about" class="btn btn-outline-primary">
        Lihat Semua Skill <i class="bi bi-arrow-right"></i>
      </a>
    </div>
  </div>
</section>
<?php endif; ?>




<!-- Preview Blog -->
<section class="section">
  <div class="container">
    <div class="d-flex justify-content-between align-items-end mb-4">
      <div>
        <h2 class="section-title">Blog Terbaru</h2>
        <p class="section-subtitle mb-0">Tulisan seputar web development & design</p>
      </div>
      <a href="<?= BASE_URL ?>/?page=blog" class="btn btn-outline-primary">
        Semua Artikel <i class="bi bi-arrow-right"></i>
      </a>
    </div>

    <div class="row g-4">
      <?php
      $posts = db()->query("SELECT b.*, k.nama AS kategori FROM blog b
                            LEFT JOIN kategori k ON b.kategori_id = k.id
                            WHERE b.status = 'publish'
                            ORDER BY b.created_at DESC LIMIT 3")->fetchAll();
      foreach ($posts as $post):
      ?>
        <div class="col-md-4">
          <article class="card border-0 shadow-sm h-100">
            <img src="<?= !empty($post['cover']) && file_exists(UPLOAD_PATH . $post['cover'])
                        ? UPLOAD_URL . e($post['cover'])
                        : 'https://via.placeholder.com/400x220/7c3aed/ffffff?text=Blog' ?>"
                 class="card-img-top" style="height:200px; object-fit:cover;" alt="">
            <div class="card-body">
              <?php if (!empty($post['kategori'])): ?>
                <span class="badge bg-primary mb-2"><?= e($post['kategori']) ?></span>
              <?php endif; ?>
              <h5 class="fw-bold"><?= e($post['judul']) ?></h5>
              <p class="text-muted small mb-2"><?= excerpt($post['konten'], 100) ?></p>
              <small class="text-muted"><i class="bi bi-calendar3"></i> <?= tanggalIndo($post['created_at']) ?></small>
            </div>
            <div class="card-footer bg-white border-0">
              <a href="<?= BASE_URL ?>/?page=blog_detail&slug=<?= e($post['slug']) ?>" class="text-primary text-decoration-none">
                Baca Selengkapnya <i class="bi bi-arrow-right"></i>
              </a>
            </div>
          </article>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>


