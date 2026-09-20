<?php
// Ambil data profil
$profil = db()->query("SELECT * FROM profil WHERE id = 1")->fetch() ?: [];
?>
<section class="hero">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-7">
        <h1 class="mb-3">
          Halo, saya <span style="color:var(--primary)"><?= e($profil['nama'] ?? 'Nama Anda') ?></span>
        </h1>
        <p class="lead mb-4"><?= e($profil['gelar'] ?? 'Web Developer & UI Designer') ?></p>
        <p class="mb-4" style="max-width:600px; color:var(--text-muted);">
          <?= e($profil['bio'] ?? 'Saya membuat website modern, cepat, dan mudah digunakan.') ?>
        </p>
        <div class="d-flex gap-2 flex-wrap">
          <a href="<?= BASE_URL ?>/?page=contact" class="btn btn-primary px-4 py-2">
            <i class="bi bi-envelope"></i> Hubungi Saya
          </a>
          <a href="<?= BASE_URL ?>/?page=projects" class="btn btn-outline-dark px-4 py-2">
            Lihat Portfolio
          </a>
        </div>
      </div>
      <div class="col-lg-5 text-center mt-5 mt-lg-0">
        <?php if (!empty($profil['foto']) && file_exists(UPLOAD_PATH . $profil['foto'])): ?>
          <img src="<?= UPLOAD_URL . e($profil['foto']) ?>"
               alt="<?= e($profil['nama']) ?>"
               class="rounded-circle shadow"
               style="width:260px; height:260px; object-fit:cover;">
        <?php else: ?>
          <img src="https://via.placeholder.com/260" alt="Foto"
               class="rounded-circle shadow" style="width:260px; height:260px;">
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