<?php $pageTitle = 'Tentang Saya | ' . APP_NAME;
$profil = db()->query("SELECT * FROM profil WHERE id = 1")->fetch() ?: [];
?>
<section class="section">
  <div class="container">
    <div class="mb-5">
      <h1 class="section-title">Tentang Saya</h1>
      <p class="section-subtitle">Kenalan yuk!</p>
    </div>
    <div class="row">
      <div class="col-lg-8">
        <p style="font-size:1.1rem; line-height:1.9;"><?= nl2br(e($profil['bio'] ?? '')) ?></p>
      </div>
    </div>
  </div>
</section>