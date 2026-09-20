<?php
// portofolio/blog/index.php — BRIDGE ke blog
define('IS_BLOG_FORCED', true);

if (!isset($_GET['page']) || $_GET['page'] === '') {
    $_GET['page'] = 'blog';
}

require_once __DIR__ . '/../index.php';