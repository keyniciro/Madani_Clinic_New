<?php $role = $_SESSION['role'] ?? ''; $email = $_SESSION['email'] ?? ''; ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="#"><i class="bi bi-heart-pulse-fill me-2"></i>Klinik Madani</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navDash">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navDash">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <?php if ($role === 'admin'): ?>
          <li class="nav-item"><a class="nav-link" href="../pasien/index.php"><i class="bi bi-people me-1"></i>Pasien</a></li>
          <li class="nav-item"><a class="nav-link" href="../dokter/index.php"><i class="bi bi-person-badge me-1"></i>Dokter</a></li>
          <li class="nav-item"><a class="nav-link" href="../user/index.php"><i class="bi bi-person-gear me-1"></i>User</a></li>
          <li class="nav-item"><a class="nav-link" href="../jadwal_dokter/index.php"><i class="bi bi-calendar-week me-1"></i>Jadwal</a></li>
          <li class="nav-item"><a class="nav-link" href="../kunjungan/index.php"><i class="bi bi-calendar-check me-1"></i>Kunjungan</a></li>
          <li class="nav-item"><a class="nav-link" href="../rekam_medis/index.php"><i class="bi bi-clipboard2-pulse me-1"></i>Rekam Medis</a></li>
          <li class="nav-item"><a class="nav-link" href="../obat/index.php"><i class="bi bi-capsule me-1"></i>Obat</a></li>
          <li class="nav-item"><a class="nav-link" href="../resep/index.php"><i class="bi bi-file-earmark-medical me-1"></i>Resep</a></li>
          <li class="nav-item"><a class="nav-link" href="../tagihan/index.php"><i class="bi bi-receipt me-1"></i>Tagihan</a></li>
        <?php elseif ($role === 'dokter'): ?>
          <li class="nav-item"><a class="nav-link" href="../jadwal_dokter/index.php"><i class="bi bi-calendar-week me-1"></i>Jadwal Saya</a></li>
          <li class="nav-item"><a class="nav-link" href="../kunjungan/index.php"><i class="bi bi-calendar-check me-1"></i>Kunjungan</a></li>
          <li class="nav-item"><a class="nav-link" href="../rekam_medis/index.php"><i class="bi bi-clipboard2-pulse me-1"></i>Rekam Medis</a></li>
          <li class="nav-item"><a class="nav-link" href="../resep/index.php"><i class="bi bi-file-earmark-medical me-1"></i>Resep</a></li>
        <?php elseif ($role === 'pasien'): ?>
          <li class="nav-item"><a class="nav-link" href="../portal/index.php"><i class="bi bi-house me-1"></i>Beranda</a></li>
          <li class="nav-item"><a class="nav-link" href="../portal/kunjungan.php"><i class="bi bi-calendar-plus me-1"></i>Daftar Kunjungan</a></li>
          <li class="nav-item"><a class="nav-link" href="../portal/riwayat.php"><i class="bi bi-clock-history me-1"></i>Riwayat</a></li>
          <li class="nav-item"><a class="nav-link" href="../portal/tagihan.php"><i class="bi bi-receipt me-1"></i>Tagihan</a></li>
        <?php endif; ?>
      </ul>
      <div class="d-flex align-items-center gap-3">
        <span class="text-white-50 small"><i class="bi bi-person-circle me-1"></i><?= htmlspecialchars($email) ?> (<?= $role ?>)</span>
        <a href="../../logout.php" class="btn btn-outline-light btn-sm"><i class="bi bi-box-arrow-right me-1"></i>Logout</a>
      </div>
    </div>
  </div>
</nav>