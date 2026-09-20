<?php
// views/public/blog_kategori.php — Redirect ke blog.php dengan filter kategori
$slug = trim($_GET['slug'] ?? '');
header("Location: " . BASE_URL . "/?page=blog" . ($slug ? "&kategori=" . urlencode($slug) : ""));
exit;