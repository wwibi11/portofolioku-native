<?php 
$pageTitle = 'Tentang Saya | ' . APP_NAME;
$profil = db()->query("SELECT * FROM profil WHERE id = 1")->fetch() ?: [];

// Ambil skill
$skills = db()->query("SELECT * FROM skills ORDER BY urutan ASC, id ASC")->fetchAll();
?>

<section class="section">
  <div class="container">

    <!-- Header -->
    <div class="mb-5">
      <h1 class="section-title">Tentang Saya</h1>
      <p class="section-subtitle">Kenalan yuk!</p>
    </div>

    <div class="row g-5">

      <!-- KOLOM KIRI — Foto + Bio -->
      <div class="col-lg-7">

        <!-- Foto + Nama -->
        <div class="d-flex align-items-center gap-4 mb-4">
          <?php if (!empty($profil['foto']) && file_exists(UPLOAD_PATH . $profil['foto'])): ?>
            <img src="<?= upload($profil['foto']) ?>" 
                 alt="<?= e($profil['nama']) ?>"
                 class="rounded-circle"
                 style="width: 130px; height: 130px; object-fit: cover;
                        border: 4px solid #ffffff; flex-shrink: 0;
                        box-shadow: 0 12px 40px rgba(245, 158, 11, 0.15);">
          <?php else: ?>
            <div style="width: 130px; height: 130px; flex-shrink: 0;
                        background: #fef3c7; border-radius: 50%;
                        display: flex; align-items: center; justify-content: center;
                        color: #f59e0b; font-size: 3.5rem;
                        border: 4px solid #ffffff;">
              <i class="bi bi-person-fill"></i>
            </div>
          <?php endif; ?>

          <div>
            <h2 style="font-weight: 800; color: #0f172a; margin-bottom: 6px; 
                       font-size: 1.75rem; line-height: 1.2;">
              <?= e($profil['nama'] ?? 'Nama') ?><?= !empty($profil['gelar_akademik']) ? ', ' . e($profil['gelar_akademik']) : '' ?>
            </h2>
            <p style="color: #d97706; font-weight: 600; 
                      font-size: 0.95rem; margin-bottom: 0;">
              <?= e($profil['gelar'] ?? 'Web Developer') ?>
            </p>
          </div>
        </div>

        <!-- Bio -->
        <div style="font-size: 1rem; 
                    line-height: 1.85; 
                    color: #475569; 
                    text-align: justify;
                    text-justify: inter-word;
                    hyphens: auto;">
          <?= nl2br(e($profil['bio'] ?? 'Bio belum diisi.')) ?>
        </div>

        <!-- CTA — Tombol Amber -->
        <div class="d-flex gap-2 flex-wrap mt-4">
          <a href="<?= BASE_URL ?>/?page=contact" 
             style="background: #f59e0b; color: #ffffff; border: none;
                    border-radius: 12px; padding: 12px 28px;
                    font-weight: 600; display: inline-flex;
                    align-items: center; gap: 8px;
                    box-shadow: 0 6px 20px rgba(245, 158, 11, 0.25);
                    transition: all 0.3s ease;">
            <i class="bi bi-envelope-fill"></i> Hubungi Saya
          </a>
          <?php if (!empty($profil['cv_file']) && file_exists(UPLOAD_PATH . $profil['cv_file'])): ?>
            <a href="<?= upload($profil['cv_file']) ?>" target="_blank"
               style="background: transparent; color: #f59e0b;
                      border: 2px solid #f59e0b;
                      border-radius: 12px; padding: 10px 26px;
                      font-weight: 600; display: inline-flex;
                      align-items: center; gap: 8px;
                      transition: all 0.3s ease;">
              <i class="bi bi-download"></i> Download CV
            </a>
          <?php endif; ?>
        </div>

      </div>

      <!-- KOLOM KANAN — Skill -->
      <div class="col-lg-5">
        <?php if (!empty($skills)): ?>
          <div style="background: rgba(255, 255, 255, 0.5);
                      border: 1px solid rgba(255, 255, 255, 0.6);
                      border-radius: 20px; padding: 32px;
                      box-shadow: 0 8px 30px rgba(245, 158, 11, 0.06);">

            <h3 style="font-weight: 800; color: #0f172a; 
                       font-size: 1.25rem; margin-bottom: 6px;">
              <i class="bi bi-stars" style="color: #f59e0b;"></i> Skill & Keahlian
            </h3>
            <p style="color: #64748b; font-size: 0.9rem; margin-bottom: 24px;">
              Tools yang saya kuasai
            </p>

            <?php foreach ($skills as $s):
              $level = (int)$s['level'];
            ?>
              <div class="mb-3">
                <div class="d-flex justify-content-between mb-2">
                  <span style="font-weight: 600; color: #0f172a; font-size: 13.5px;">
                    <?= e($s['nama']) ?>
                  </span>
                  <span style="color: #d97706; font-weight: 700; font-size: 12px;">
                    <?= $level ?>%
                  </span>
                </div>
                <div style="height: 6px; background: rgba(245, 158, 11, 0.12);
                            border-radius: 10px; overflow: hidden;">
                  <div style="width: <?= $level ?>%; height: 100%;
                              background: linear-gradient(90deg, #fbbf24, #f59e0b);
                              border-radius: 10px;"></div>
                </div>
              </div>
            <?php endforeach; ?>

          </div>
        <?php endif; ?>
      </div>

    </div>

  </div>
</section>