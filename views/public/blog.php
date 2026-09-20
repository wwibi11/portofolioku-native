<?php
// views/public/blog.php — Daftar Artikel Blog
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/helper.php';

// Pagination
$page   = max(1, (int)($_GET['p'] ?? 1));
$perPage = 9;
$offset = ($page - 1) * $perPage;

// Filter kategori
$kategoriSlug = trim($_GET['kategori'] ?? '');
$kategoriId   = 0;
$kategoriNama = '';

if ($kategoriSlug !== '') {
    try {
        $stmt = db()->prepare("SELECT id, nama FROM kategori WHERE slug = :s LIMIT 1");
        $stmt->execute([':s' => $kategoriSlug]);
        $kat = $stmt->fetch();
        if ($kat) {
            $kategoriId   = (int)$kat['id'];
            $kategoriNama = $kat['nama'];
        }
    } catch (Exception $e) {}
}

// Query artikel
$sql = "SELECT b.*, k.nama AS kategori_nama, k.slug AS kategori_slug
        FROM blog b
        LEFT JOIN kategori k ON k.id = b.kategori_id
        WHERE b.status = 'publish'";
$params = [];

if ($kategoriId > 0) {
    $sql .= " AND b.kategori_id = :kat";
    $params[':kat'] = $kategoriId;
}

$sql .= " ORDER BY b.created_at DESC LIMIT $perPage OFFSET $offset";

try {
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    $artikelList = $stmt->fetchAll();
} catch (Exception $e) {
    $artikelList = [];
}

// Total untuk pagination
$totalSql = "SELECT COUNT(*) FROM blog b WHERE b.status = 'publish'";
if ($kategoriId > 0) $totalSql .= " AND b.kategori_id = $kategoriId";
$totalArtikel = 0;
try {
    $totalArtikel = (int) db()->query($totalSql)->fetchColumn();
} catch (Exception $e) {}
$totalPages = ceil($totalArtikel / $perPage);

// Kategori untuk sidebar
$kategoriList = [];
try {
    $kategoriList = db()->query("
        SELECT k.*, COUNT(b.id) AS total
        FROM kategori k
        LEFT JOIN blog b ON b.kategori_id = k.id AND b.status = 'publish'
        GROUP BY k.id
        ORDER BY k.nama
    ")->fetchAll();
} catch (Exception $e) {}

// Artikel populer (top 5 views)
$artikelPopuler = [];
try {
    $artikelPopuler = db()->query("
        SELECT judul, slug, cover, views, created_at
        FROM blog
        WHERE status = 'publish'
        ORDER BY views DESC, created_at DESC
        LIMIT 5
    ")->fetchAll();
} catch (Exception $e) {}
?>

<!-- HERO SECTION -->
<section style="background: linear-gradient(135deg, #ede9fe 0%, #f5f3ff 100%);
                padding: 80px 0 60px; margin-bottom: 60px;">
  <div class="container text-center">
    <span class="badge mb-3" style="background: #7c3aed; color: #fff; padding: 8px 16px;
                                    border-radius: 30px; font-size: 13px;">
      <i class="bi bi-newspaper"></i> Blog
    </span>
    <h1 style="font-weight: 800; font-size: clamp(2rem, 5vw, 3rem); color: #0f172a;
               margin-bottom: 15px;">
      <?= $kategoriNama ? htmlspecialchars($kategoriNama) : 'Artikel & Tulisan' ?>
    </h1>
    <p style="color: #64748b; font-size: 1.05rem; max-width: 600px; margin: 0 auto;">
      <?= $kategoriNama
          ? 'Kumpulan artikel dalam kategori ' . htmlspecialchars($kategoriNama)
          : 'Catatan, tutorial, dan pemikiran seputar web development & desain.' ?>
    </p>
  </div>
</section>

<!-- MAIN CONTENT -->
<div class="container" style="padding-bottom: 80px;">
  <div class="row g-4">

    <!-- KOLOM ARTIKEL -->
    <div class="col-lg-8">

      <?php if (empty($artikelList)): ?>
        <div class="text-center py-5" style="background: #fff; border-radius: 16px;
             border: 1px solid #eef2f7;">
          <i class="bi bi-inbox" style="font-size: 4rem; color: #cbd5e1;"></i>
          <h4 class="mt-3" style="color: #0f172a;">Belum ada artikel</h4>
          <p class="text-muted">
            <?= $kategoriNama
                ? 'Belum ada artikel dalam kategori ini.'
                : 'Artikel akan muncul di sini setelah dipublish.' ?>
          </p>
        </div>
      <?php else: ?>

        <div class="row g-4">
          <?php foreach ($artikelList as $i => $a):
            $isFeatured = ($i === 0 && $page === 1 && !$kategoriNama);
          ?>
          <div class="col-<?= $isFeatured ? '12' : 'md-6' ?>">
            <article class="card h-100 border-0 shadow-sm"
                     style="border-radius: 16px; overflow: hidden;
                            transition: transform 0.3s, box-shadow 0.3s;"
                     onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 40px rgba(124,58,237,0.15)';"
                     onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 2px 8px rgba(0,0,0,0.04)';">

              <!-- Cover -->
              <div style="aspect-ratio: <?= $isFeatured ? '21/9' : '16/10' ?>;
                          background: linear-gradient(135deg, #ede9fe 0%, #f5f3ff 100%);
                          overflow: hidden; position: relative;">
                <?php if (!empty($a['cover']) && file_exists(UPLOAD_PATH . $a['cover'])): ?>
                  <img src="<?= upload($a['cover']) ?>" alt="<?= htmlspecialchars($a['judul']) ?>"
                       style="width: 100%; height: 100%; object-fit: cover;">
                <?php else: ?>
                  <div style="width: 100%; height: 100%; display: flex; align-items: center;
                              justify-content: center; color: #7c3aed;">
                    <i class="bi bi-file-earmark-text" style="font-size: 3rem; opacity: 0.3;"></i>
                  </div>
                <?php endif; ?>

                <?php if ($isFeatured): ?>
                  <span class="badge" style="position: absolute; top: 16px; left: 16px;
                        background: #7c3aed; color: #fff; padding: 6px 14px;
                        border-radius: 20px; font-size: 12px;">
                    <i class="bi bi-star-fill"></i> Terbaru
                  </span>
                <?php endif; ?>
              </div>

              <div class="card-body p-4">
                <!-- Meta -->
                <div class="d-flex align-items-center gap-3 mb-3"
                     style="font-size: 12px; color: #8a94a6;">
                  <?php if (!empty($a['kategori_nama'])): ?>
                    <a href="?page=blog&kategori=<?= urlencode($a['kategori_slug']) ?>"
                       class="badge text-decoration-none"
                       style="background: #ede9fe; color: #7c3aed; padding: 5px 10px;
                              border-radius: 20px; font-size: 11px;">
                      <?= htmlspecialchars($a['kategori_nama']) ?>
                    </a>
                  <?php endif; ?>
                  <span>
                    <i class="bi bi-calendar"></i>
                    <?= date('d M Y', strtotime($a['created_at'])) ?>
                  </span>
                  <span>
                    <i class="bi bi-eye"></i> <?= (int)$a['views'] ?>
                  </span>
                </div>

                <!-- Judul -->
                <h3 style="font-weight: 700; font-size: <?= $isFeatured ? '1.5rem' : '1.1rem' ?>;
                           line-height: 1.3; margin-bottom: 12px;">
                  <a href="?page=blog_detail&slug=<?= urlencode($a['slug']) ?>"
                     class="text-decoration-none" style="color: #0f172a;">
                    <?= htmlspecialchars($a['judul']) ?>
                  </a>
                </h3>

                <!-- Excerpt -->
                <p style="color: #64748b; font-size: 14px; line-height: 1.6; margin-bottom: 16px;">
                  <?= htmlspecialchars(excerpt(strip_tags($a['konten']), $isFeatured ? 200 : 100)) ?>
                </p>

                <!-- Read More -->
                <a href="?page=blog_detail&slug=<?= urlencode($a['slug']) ?>"
                   class="text-decoration-none"
                   style="color: #7c3aed; font-weight: 600; font-size: 14px;">
                  Baca Selengkapnya <i class="bi bi-arrow-right"></i>
                </a>
              </div>

            </article>
          </div>
          <?php endforeach; ?>
        </div>

        <!-- PAGINATION -->
        <?php if ($totalPages > 1): ?>
          <nav class="mt-5">
            <ul class="pagination justify-content-center">
              <?php
              $baseUrl = '?page=blog';
              if ($kategoriSlug) $baseUrl .= '&kategori=' . urlencode($kategoriSlug);
              ?>

              <!-- Prev -->
              <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                <a class="page-link" href="<?= $baseUrl ?>&p=<?= $page - 1 ?>">
                  <i class="bi bi-chevron-left"></i>
                </a>
              </li>

              <!-- Numbers -->
              <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                  <a class="page-link" href="<?= $baseUrl ?>&p=<?= $i ?>"
                     style="<?= $i === $page ? 'background: #7c3aed; border-color: #7c3aed;' : '' ?>">
                    <?= $i ?>
                  </a>
                </li>
              <?php endfor; ?>

              <!-- Next -->
              <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                <a class="page-link" href="<?= $baseUrl ?>&p=<?= $page + 1 ?>">
                  <i class="bi bi-chevron-right"></i>
                </a>
              </li>
            </ul>
          </nav>
        <?php endif; ?>

      <?php endif; ?>

    </div>

    <!-- SIDEBAR -->
    <div class="col-lg-4">

      <!-- Kategori -->
      <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
          <h5 style="font-weight: 700; color: #0f172a; margin-bottom: 20px;">
            <i class="bi bi-tags" style="color: #7c3aed;"></i> Kategori
          </h5>

          <?php if (empty($kategoriList)): ?>
            <p class="text-muted small mb-0">Belum ada kategori.</p>
          <?php else: ?>
            <ul class="list-unstyled mb-0">
              <?php foreach ($kategoriList as $k): ?>
              <li class="mb-2">
                <a href="?page=blog&kategori=<?= urlencode($k['slug']) ?>"
                   class="d-flex justify-content-between align-items-center text-decoration-none"
                   style="padding: 10px 14px; border-radius: 8px;
                          color: <?= $kategoriSlug === $k['slug'] ? '#7c3aed' : '#4a5568' ?>;
                          background: <?= $kategoriSlug === $k['slug'] ? '#ede9fe' : '#f8f9fc' ?>;
                          font-weight: <?= $kategoriSlug === $k['slug'] ? '600' : '500' ?>;">
                  <span>
                    <i class="bi bi-folder"></i>
                    <?= htmlspecialchars($k['nama']) ?>
                  </span>
                  <span class="badge"
                        style="background: #7c3aed; color: #fff; font-size: 10px;">
                    <?= (int)$k['total'] ?>
                  </span>
                </a>
              </li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
        </div>
      </div>

      <!-- Artikel Populer -->
      <?php if (!empty($artikelPopuler)): ?>
      <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
          <h5 style="font-weight: 700; color: #0f172a; margin-bottom: 20px;">
            <i class="bi bi-fire" style="color: #7c3aed;"></i> Populer
          </h5>

          <ul class="list-unstyled mb-0">
            <?php foreach ($artikelPopuler as $i => $p): ?>
            <li class="<?= $i < count($artikelPopuler) - 1 ? 'mb-3 pb-3' : '' ?>"
                style="<?= $i < count($artikelPopuler) - 1 ? 'border-bottom: 1px solid #eef2f7;' : '' ?>">
              <a href="?page=blog_detail&slug=<?= urlencode($p['slug']) ?>"
                 class="text-decoration-none d-flex gap-3">
                <div style="width: 60px; height: 60px; flex-shrink: 0; border-radius: 10px;
                            background: #f5f3ff; overflow: hidden;">
                  <?php if (!empty($p['cover']) && file_exists(UPLOAD_PATH . $p['cover'])): ?>
                    <img src="<?= upload($p['cover']) ?>" alt=""
                         style="width: 100%; height: 100%; object-fit: cover;">
                  <?php else: ?>
                    <div style="width: 100%; height: 100%; display: flex; align-items: center;
                                justify-content: center; color: #7c3aed;">
                      <i class="bi bi-file-text"></i>
                    </div>
                  <?php endif; ?>
                </div>
                <div style="flex: 1; min-width: 0;">
                  <div style="font-weight: 600; font-size: 13px; color: #0f172a;
                              line-height: 1.4; margin-bottom: 4px;">
                    <?= htmlspecialchars(excerpt($p['judul'], 60)) ?>
                  </div>
                  <div style="font-size: 11px; color: #8a94a6;">
                    <i class="bi bi-eye"></i> <?= (int)$p['views'] ?> views
                  </div>
                </div>
              </a>
            </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
      <?php endif; ?>

    </div>

  </div>
</div>