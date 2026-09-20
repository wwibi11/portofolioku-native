<?php $pageTitle = 'Pengalaman | ' . APP_NAME; ?>
<section class="section">
  <div class="container">
    <div class="mb-5">
      <h1 class="section-title">Pengalaman Kerja</h1>
      <p class="section-subtitle">Perjalanan karier saya</p>
    </div>

    <div class="row">
      <div class="col-lg-8">
        <div class="timeline">
          <?php
          $list = db()->query("SELECT * FROM pengalaman ORDER BY urutan ASC, mulai DESC")->fetchAll();
          foreach ($list as $p):
          ?>
            <div class="timeline-item mb-4 ps-4 position-relative">
              <div class="timeline-dot"></div>
              <small class="text-muted">
                <?= tanggalIndo($p['mulai']) ?> — <?= $p['selesai'] ? tanggalIndo($p['selesai']) : 'Sekarang' ?>
              </small>
              <h4 class="fw-bold mb-1 mt-1"><?= e($p['posisi']) ?></h4>
              <p class="text-primary mb-2"><?= e($p['perusahaan']) ?></p>
              <p class="text-muted mb-0"><?= e($p['deskripsi']) ?></p>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<style>
.timeline { border-left: 3px solid var(--primary); padding-left: 25px; }
.timeline-item { position: relative; }
.timeline-dot {
  position: absolute;
  width: 15px; height: 15px;
  background: var(--primary);
  border-radius: 50%;
  left: -33px; top: 5px;
  border: 3px solid white;
  box-shadow: 0 0 0 3px var(--primary);
}
</style>