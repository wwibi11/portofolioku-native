<?php
// portofolio/maintenance.php
require_once __DIR__ . '/config/config.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Maintenance | <?= defined('APP_NAME') ? APP_NAME : 'Portfolio' ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      min-height: 100vh;
      background: #0f172a;
      overflow: hidden;
      position: relative;
      color: #fff;
    }

    /* ============================================
       BACKGROUND ANIMATION — FLOATING GRADIENT BLOBS
       ============================================ */
    .blob {
      position: absolute;
      border-radius: 50%;
      filter: blur(80px);
      opacity: 0.5;
      animation: float 20s infinite ease-in-out;
      z-index: 0;
    }

    .blob-1 {
      width: 500px;
      height: 500px;
      background: #7c3aed;
      top: -150px;
      left: -150px;
      animation-delay: 0s;
    }

    .blob-2 {
      width: 400px;
      height: 400px;
      background: #3b82f6;
      bottom: -100px;
      right: -100px;
      animation-delay: -5s;
    }

    .blob-3 {
      width: 350px;
      height: 350px;
      background: #ec4899;
      top: 40%;
      left: 45%;
      animation-delay: -10s;
    }

    @keyframes float {
      0%, 100% {
        transform: translate(0, 0) scale(1);
      }
      33% {
        transform: translate(50px, -50px) scale(1.1);
      }
      66% {
        transform: translate(-50px, 50px) scale(0.9);
      }
    }

    /* ============================================
       PARTICLES / GRID OVERLAY
       ============================================ */
    .grid-overlay {
      position: absolute;
      inset: 0;
      background-image:
        linear-gradient(rgba(124, 58, 237, 0.08) 1px, transparent 1px),
        linear-gradient(90deg, rgba(124, 58, 237, 0.08) 1px, transparent 1px);
      background-size: 40px 40px;
      z-index: 1;
      animation: gridMove 20s linear infinite;
    }

    @keyframes gridMove {
      0% { background-position: 0 0; }
      100% { background-position: 40px 40px; }
    }

    /* ============================================
       FLOATING ICONS (tools, gear, code)
       ============================================ */
    .float-icon {
      position: absolute;
      font-size: 24px;
      color: rgba(124, 58, 237, 0.4);
      animation: floatIcon 15s infinite ease-in-out;
      z-index: 2;
    }

    .float-icon:nth-child(1) { top: 15%; left: 10%; animation-delay: 0s; font-size: 32px; }
    .float-icon:nth-child(2) { top: 25%; right: 15%; animation-delay: -2s; font-size: 28px; }
    .float-icon:nth-child(3) { bottom: 20%; left: 20%; animation-delay: -4s; font-size: 36px; }
    .float-icon:nth-child(4) { bottom: 30%; right: 10%; animation-delay: -6s; font-size: 24px; }
    .float-icon:nth-child(5) { top: 50%; left: 5%; animation-delay: -8s; font-size: 30px; }
    .float-icon:nth-child(6) { top: 60%; right: 5%; animation-delay: -10s; font-size: 28px; }

    @keyframes floatIcon {
      0%, 100% {
        transform: translateY(0) rotate(0deg);
        opacity: 0.3;
      }
      50% {
        transform: translateY(-30px) rotate(15deg);
        opacity: 0.6;
      }
    }

    /* ============================================
       CONTENT
       ============================================ */
    .maintenance-content {
      position: relative;
      z-index: 10;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .content-inner {
      max-width: 600px;
      text-align: center;
      animation: fadeInUp 1s ease-out;
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* ============================================
       ICON ANIMATION — GEAR ROTATING
       ============================================ */
    .icon-wrapper {
      display: inline-block;
      position: relative;
      margin-bottom: 30px;
    }

    .main-icon {
      font-size: 6rem;
      color: #7c3aed;
      display: inline-block;
      animation: iconRotate 4s ease-in-out infinite;
      filter: drop-shadow(0 0 30px rgba(124, 58, 237, 0.6));
    }

    @keyframes iconRotate {
      0%, 100% {
        transform: rotate(-15deg) scale(1);
      }
      50% {
        transform: rotate(15deg) scale(1.1);
      }
    }

    /* Pulse ring around icon */
    .pulse-ring {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 140px;
      height: 140px;
      border: 2px solid rgba(124, 58, 237, 0.4);
      border-radius: 50%;
      animation: pulseRing 2s ease-out infinite;
    }

    .pulse-ring.delay {
      animation-delay: 1s;
    }

    @keyframes pulseRing {
      0% {
        transform: translate(-50%, -50%) scale(0.8);
        opacity: 1;
      }
      100% {
        transform: translate(-50%, -50%) scale(1.8);
        opacity: 0;
      }
    }

    /* ============================================
       TYPOGRAPHY
       ============================================ */
    h1 {
      font-weight: 800;
      font-size: 2.5rem;
      margin-bottom: 15px;
      background: linear-gradient(135deg, #7c3aed 0%, #3b82f6 50%, #ec4899 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      background-size: 200% 200%;
      animation: gradientShift 5s ease infinite;
    }

    @keyframes gradientShift {
      0%, 100% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
    }

    p {
      color: #94a3b8;
      font-size: 1.05rem;
      line-height: 1.6;
      margin-bottom: 30px;
    }

    /* ============================================
       PROGRESS BAR ANIMATION
       ============================================ */
    .progress-container {
      width: 100%;
      max-width: 400px;
      margin: 0 auto 30px;
      background: rgba(255, 255, 255, 0.05);
      border-radius: 10px;
      height: 6px;
      overflow: hidden;
      position: relative;
    }

    .progress-bar-animated {
      height: 100%;
      background: linear-gradient(90deg, #7c3aed, #3b82f6, #7c3aed);
      background-size: 200% 100%;
      border-radius: 10px;
      animation: progressMove 2s linear infinite;
      width: 60%;
    }

    @keyframes progressMove {
      0% {
        background-position: 0% 50%;
        transform: translateX(-100%);
      }
      100% {
        background-position: 100% 50%;
        transform: translateX(200%);
      }
    }

    /* ============================================
       BUTTON
       ============================================ */
    .btn-refresh {
      background: rgba(124, 58, 237, 0.15);
      border: 1px solid rgba(124, 58, 237, 0.4);
      color: #a78bfa;
      padding: 12px 28px;
      border-radius: 10px;
      font-weight: 600;
      font-size: 14px;
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      cursor: pointer;
    }

    .btn-refresh:hover {
      background: rgba(124, 58, 237, 0.3);
      border-color: #7c3aed;
      color: #fff;
      transform: translateY(-2px);
      box-shadow: 0 10px 30px rgba(124, 58, 237, 0.3);
    }

    .btn-refresh:active {
      transform: translateY(0);
    }

    .btn-refresh .refresh-icon {
      transition: transform 0.6s ease;
    }

    .btn-refresh:hover .refresh-icon {
      transform: rotate(360deg);
    }

    /* ============================================
       FOOTER
       ============================================ */
    .footer-text {
      position: absolute;
      bottom: 20px;
      left: 0;
      right: 0;
      text-align: center;
      color: #475569;
      font-size: 12px;
      z-index: 10;
    }

    /* ============================================
       TYPING DOTS ANIMATION
       ============================================ */
    .typing-dots {
      display: inline-flex;
      gap: 4px;
      margin-left: 4px;
    }

    .typing-dots span {
      width: 6px;
      height: 6px;
      background: #7c3aed;
      border-radius: 50%;
      display: inline-block;
      animation: typingBounce 1.4s infinite ease-in-out both;
    }

    .typing-dots span:nth-child(1) { animation-delay: -0.32s; }
    .typing-dots span:nth-child(2) { animation-delay: -0.16s; }

    @keyframes typingBounce {
      0%, 80%, 100% {
        transform: scale(0.6);
        opacity: 0.4;
      }
      40% {
        transform: scale(1);
        opacity: 1;
      }
    }

    /* ============================================
       RESPONSIVE
       ============================================ */
    @media (max-width: 576px) {
      h1 { font-size: 1.75rem; }
      p { font-size: 0.95rem; }
      .main-icon { font-size: 4rem; }
      .pulse-ring { width: 100px; height: 100px; }
      .blob { filter: blur(60px); }
      .blob-1, .blob-2, .blob-3 { width: 250px; height: 250px; }
    }
  </style>
</head>
<body>

  <!-- Background Blobs -->
  <div class="blob blob-1"></div>
  <div class="blob blob-2"></div>
  <div class="blob blob-3"></div>

  <!-- Grid Overlay -->
  <div class="grid-overlay"></div>

  <!-- Floating Icons -->
  <i class="bi bi-gear-fill float-icon"></i>
  <i class="bi bi-code-slash float-icon"></i>
  <i class="bi bi-tools float-icon"></i>
  <i class="bi bi-cpu float-icon"></i>
  <i class="bi bi-database float-icon"></i>
  <i class="bi bi-bug-fill float-icon"></i>

  <!-- Content -->
  <div class="maintenance-content">
    <div class="content-inner">

      <!-- Icon dengan pulse ring -->
      <div class="icon-wrapper">
        <div class="pulse-ring"></div>
        <div class="pulse-ring delay"></div>
        <i class="bi bi-gear-wide-connected main-icon"></i>
      </div>

      <!-- Title -->
      <h1>Sedang Maintenance</h1>

      <!-- Subtitle -->
      <p>
        Kami sedang melakukan perbaikan untuk meningkatkan kualitas layanan.
        Silakan kembali beberapa saat lagi
        <span class="typing-dots">
          <span></span>
          <span></span>
          <span></span>
        </span>
      </p>

      <!-- Progress Bar -->
      <div class="progress-container">
        <div class="progress-bar-animated"></div>
      </div>

      <!-- Refresh Button -->
     <a href="<?= BASE_URL ?>" class="btn btn-outline-secondary" style="border-radius: 8px;">
        <i class="bi bi-arrow-clockwise"></i> Refresh
     </a>

    </div>
  </div>

  <!-- Footer -->
  <div class="footer-text">
    &copy; <?= date('Y') ?> <?= defined('APP_NAME') ? APP_NAME : 'Portfolio' ?>
  </div>

</body>
</html>