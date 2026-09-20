<?php
// views/public/blog_detail.php — Detail Artikel
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/helper.php';

$slug = trim($_GET['slug'] ?? '');
if ($slug === '') {
    header("Location: " . BASE_URL . "/?page=blog");
    exit;
}

// Ambil artikel
try {
    $stmt = db()->prepare("SELECT b.*, k.nama AS kategori_nama, k.slug AS kategori_slug
                           FROM blog b
                           LEFT JOIN kategori k ON k.id = b.kategori_id
                           WHERE b.slug = :slug AND b.status = 'publish'
                           LIMIT 1");
    $stmt->execute([':slug' => $slug]);
    $artikel = $stmt->fetch();
} catch (Exception $e) {
    $artikel = null;
}

if (!$artikel) {
    http_response_code(404);
    require_once __DIR__ . '/../errors/404.php';
    exit;
}

// Increment views
try {
    db()->prepare("UPDATE blog SET views = views + 1 WHERE id = :id")
        ->execute([':id' => $artikel['id']]);
    $artikel['views'] = (int)$artikel['views'] + 1;
} catch (Exception $e) {}

// Artikel terkait (kategori sama, exclude current)
$artikelTerkait = [];
if (!empty($artikel['kategori_id'])) {
    try {
        $stmt = db()->prepare("
            SELECT judul, slug, cover, created_at
            FROM blog
            WHERE status = 'publish' AND kategori_id = :kat AND id != :id
            ORDER BY created_at DESC
            LIMIT 3
        ");
        $stmt->execute([':kat' => $artikel['kategori_id'], ':id' => $artikel['id']]);
        $artikelTerkait = $stmt->fetchAll();
    } catch (Exception $e) {}
}

// Tags
$tags = [];
if (!empty($artikel['tags'])) {
    $tags = array_filter(array_map('trim', explode(',', $artikel['tags'])));
}
?>

<!-- HERO -->
<section style="background: linear-gradient(135deg, #ede9fe 0%, #f5f3ff 100%);
                padding: 60px 0 40px;">
  <div class="container" style="max-width: 800px;">
    <div class="text-center">

      <?php if (!empty($artikel['kategori_nama'])): ?>
        <a href="?page=blog&kategori=<?= urlencode($artikel['kategori_slug']) ?>"
           class="badge text-decoration-none mb-3"
           style="background: #7c3aed; color: #fff; padding: 8px 16px;
                  border-radius: 30px; font-size: 12px;">
          <?= htmlspecialchars($artikel['kategori_nama']) ?>
        </a>
      <?php endif; ?>

      <h1 style="font-weight: 800; font-size: clamp(1.75rem, 4vw, 2.5rem);
                 line-height: 1.25; color: #0f172a; margin-bottom: 20px;">
        <?= htmlspecialchars($artikel['judul']) ?>
      </h1>

      <div class="d-flex justify-content-center align-items-center gap-3 flex-wrap"
           style="font-size: 13px; color: #64748b;">
        <span>
          <i class="bi bi-calendar"></i>
          <?= tanggalIndo($artikel['created_at'], true) ?>
        </span>
        <span>·</span>
        <span><i class="bi bi-eye"></i> <?= (int)$artikel['views'] ?> views</span>
        <span>·</span>
        <span><i class="bi bi-person"></i> <?= htmlspecialchars($profil['nama'] ?? 'Admin') ?></span>
      </div>

    </div>
  </div>
</section>

<!-- COVER -->
<?php if (!empty($artikel['cover']) && file_exists(UPLOAD_PATH . $artikel['cover'])): ?>
<div class="container" style="max-width: 900px; margin-top: -30px; position: relative; z-index: 2;">
  <img src="<?= upload($artikel['cover']) ?>" alt="<?= htmlspecialchars($artikel['judul']) ?>"
       style="width: 100%; max-height: 500px; object-fit: cover; border-radius: 20px;
              box-shadow: 0 20px 60px rgba(124,58,237,0.2);">
</div>
<?php endif; ?>

<!-- KONTEN -->
<div class="container" style="max-width: 800px; padding: 60px 15px 80px;">

  <article style="font-size: 17px; line-height: 1.8; color: #334155;">
    <?= $artikel['konten'] ?>
  </article>

  <!-- Tags -->
  <?php if (!empty($tags)): ?>
  <div class="mt-5 pt-4" style="border-top: 1px solid #eef2f7;">
    <strong style="color: #0f172a; font-size: 14px;">
      <i class="bi bi-tags"></i> Tags:
    </strong>
    <?php foreach ($tags as $tag): ?>
      <span class="badge" style="background: #ede9fe; color: #7c3aed;
            padding: 6px 14px; border-radius: 20px; font-size: 12px; margin: 4px;">
        #<?= htmlspecialchars($tag) ?>
      </span>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

  <!-- Share -->
  <div class="mt-4 pt-4" style="border-top: 1px solid #eef2f7;">
    <strong style="color: #0f172a; font-size: 14px;">
      <i class="bi bi-share"></i> Bagikan:
    </strong>
    <div class="d-inline-flex gap-2 ms-2">
      <?php
      $shareUrl = urlencode(BASE_URL . '/?page=blog_detail&slug=' . $artikel['slug']);
      $shareTitle = urlencode($artikel['judul']);
      ?>
      <a href="https://twitter.com/intent/tweet?url=<?= $shareUrl ?>&text=<?= $shareTitle ?>"
         target="_blank" class="btn btn-sm"
         style="background: #0f172a; color: #fff; border-radius: 8px;">
        <i class="bi bi-twitter-x"></i>
      </a>
      <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $shareUrl ?>"
         target="_blank" class="btn btn-sm"
         style="background: #1877f2; color: #fff; border-radius: 8px;">
        <i class="bi bi-facebook"></i>
      </a>
      <a href="https://wa.me/?text=<?= $shareTitle ?>%20<?= $shareUrl ?>"
         target="_blank" class="btn btn-sm"
         style="background: #25d366; color: #fff; border-radius: 8px;">
        <i class="bi bi-whatsapp"></i>
      </a>
      <a href="https://t.me/share/url?url=<?= $shareUrl ?>&text=<?= $shareTitle ?>"
         target="_blank" class="btn btn-sm"
         style="background: #0088cc; color: #fff; border-radius: 8px;">
        <i class="bi bi-telegram"></i>
      </a>
    </div>
  </div>

  <!-- Back -->
  <div class="mt-5">
    <a href="?page=blog" class="text-decoration-none"
       style="color: #7c3aed; font-weight: 600;">
      <i class="bi bi-arrow-left"></i> Kembali ke Blog
    </a>
  </div>

</div>

<!-- ARTIKEL TERKAIT -->
<?php if (!empty($artikelTerkait)): ?>
<section style="background: #f8f9fc; padding: 60px 0;">
  <div class="container">
    <h2 style="font-weight: 700; color: #0f172a; margin-bottom: 30px;">
      <i class="bi bi-bookmark" style="color: #7c3aed;"></i> Artikel Terkait
    </h2>
    <div class="row g-4">
      <?php foreach ($artikelTerkait as $t): ?>
      <div class="col-md-4">
        <article class="card h-100 border-0 shadow-sm" style="border-radius: 16px; overflow: hidden;">
          <div style="aspect-ratio: 16/10; background: #ede9fe; overflow: hidden;">
            <?php if (!empty($t['cover']) && file_exists(UPLOAD_PATH . $t['cover'])): ?>
              <img src="<?= upload($t['cover']) ?>" alt=""
                   style="width: 100%; height: 100%; object-fit: cover;">
            <?php else: ?>
              <div style="width: 100%; height: 100%; display: flex; align-items: center;
                          justify-content: center; color: #7c3aed;">
                <i class="bi bi-file-text" style="font-size: 2.5rem; opacity: 0.3;"></i>
              </div>
            <?php endif; ?>
          </div>
          <div class="card-body p-3">
            <h5 style="font-weight: 600; font-size: 1rem; margin-bottom: 8px;">
              <a href="?page=blog_detail&slug=<?= urlencode($t['slug']) ?>"
                 class="text-decoration-none" style="color: #0f172a;">
                <?= htmlspecialchars(excerpt($t['judul'], 70)) ?>
              </a>
            </h5>
            <div style="font-size: 12px; color: #8a94a6;">
              <i class="bi bi-calendar"></i>
              <?= date('d M Y', strtotime($t['created_at'])) ?>
            </div>
          </div>
        </article>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>