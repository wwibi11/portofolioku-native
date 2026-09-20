<?php $pageTitle = 'Pendidikan | ' . APP_NAME; ?>
<section class="section">
  <div class="container">
    <div class="mb-5">
      <h1 class="section-title">Pendidikan</h1>
      <p class="section-subtitle">Riwayat pendidikan formal</p>
    </div>

    <div class="row">
      <div class="col-lg-8">
        <?php
        $list = db()->query("SELECT * FROM pendidikan ORDER BY urutan ASC, tahun_selesai DESC")->fetchAll();
        foreach ($list as $p):
        ?>
          <div class="card border-0 shadow-sm mb-3">
            <div class="card-body p-4">
              <div class="d-flex justify-content-between flex-wrap">
                <div>
                  <span class="badge bg-primary mb-2"><?= e($p['jenjang']) ?></span>
                  <h4 class="fw-bold mb-1"><?= e($p['institusi']) ?></h4>
                  <?php if (!empty($p['jurusan'])): ?>
                    <p class="text-muted mb-2"><?= e($p['jurusan']) ?></p>
                  <?php endif; ?>
                </div>
                <div class="text-md-end">
                  <small class="text-muted">
                    <i class="bi bi-calendar3"></i>
                    <?= e($p['tahun_mulai']) ?> — <?= $p['tahun_selesai'] ? e($p['tahun_selesai']) : 'Sekarang' ?>
                  </small>
                </div>
              </div>
              <?php if (!empty($p['deskripsi'])): ?>
                <p class="mb-0 mt-2"><?= e($p['deskripsi']) ?></p>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>