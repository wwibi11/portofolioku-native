<?php $pageTitle = 'Portfolio | ' . APP_NAME; ?>
<section class="section">
  <div class="container">

    <div class="mb-5">
      <h1 class="section-title">Portfolio</h1>
      <p class="section-subtitle">Kumpulan proyek yang pernah saya kerjakan</p>
    </div>

    <div class="row g-5">
      <?php
      $projects = db()->query("SELECT * FROM proyek WHERE status = 'publish' ORDER BY id DESC")->fetchAll();
      if (empty($projects)):
      ?>
        <div class="col-12 text-center text-muted">
          <p>Belum ada proyek yang dipublikasikan.</p>
        </div>
      <?php else: foreach ($projects as $p): ?>
        <div class="col-md-6">
          <article class="portfolio-item">
            <div class="portfolio-thumb">
              <?php if (!empty($p['thumbnail']) && file_exists(UPLOAD_PATH . $p['thumbnail'])): ?>
                <img src="<?= UPLOAD_URL . e($p['thumbnail']) ?>" alt="<?= e($p['judul']) ?>">
              <?php else: ?>
                <img src="https://via.placeholder.com/800x500/7c3aed/ffffff?text=<?= urlencode($p['judul']) ?>" alt="">
              <?php endif; ?>
            </div>

            <h3 class="portfolio-title"><?= e($p['judul']) ?></h3>

            <?php if (!empty($p['link_demo'])): ?>
              <a href="<?= e($p['link_demo']) ?>" target="_blank" class="portfolio-link">Lihat Disini</a>
            <?php else: ?>
              <a href="<?= BASE_URL ?>/?page=project_detail&slug=<?= e($p['slug']) ?>" class="portfolio-link">Lihat Disini</a>
            <?php endif; ?>

            <p class="portfolio-desc"><?= e($p['deskripsi_singkat']) ?></p>

            <?php if (!empty($p['tech_stack'])): ?>
              <div class="mt-2">
                <?php foreach (explode(',', $p['tech_stack']) as $tech): ?>
                  <span class="badge bg-light text-dark border me-1"><?= e(trim($tech)) ?></span>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </article>
        </div>
      <?php endforeach; endif; ?>
    </div>

  </div>
</section>