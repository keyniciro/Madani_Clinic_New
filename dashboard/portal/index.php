<?php
require_once '../../checkuser.php';
require_once '../../db.php';
if ($_SESSION['role'] !== 'pasien') { header('location: /login.php'); exit; }

$stmt = $conn->prepare("SELECT p.* FROM pasien p LEFT JOIN users u ON p.user_id = u.id WHERE u.email = :email");
$stmt->bindParam(':email', $_SESSION['email']);
$stmt->execute();
$pasien = $stmt->fetch(PDO::FETCH_ASSOC);

// FIX: simpan ke variabel dulu sebelum bindParam
$pasien_id = $pasien['id'] ?? 0;

$stmtK = $conn->prepare("SELECT COUNT(*) FROM kunjungan WHERE pasien_id = :id");
$stmtK->bindParam(':id', $pasien_id);
$stmtK->execute();
$totalKunjungan = $stmtK->fetchColumn();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Portal Pasien - Klinik Madani</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include '../navbar.php'; ?>
<div class="container py-4">
  <div class="mb-4">
    <h4 class="fw-bold">Selamat datang, <?= htmlspecialchars($pasien['nama'] ?? $_SESSION['email']) ?>!</h4>
    <p class="text-muted">Berikut ringkasan informasi kesehatan Anda.</p>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-md-4">
      <div class="card border-0 shadow-sm text-center p-3">
        <i class="bi bi-calendar-check fs-1 text-primary"></i>
        <h3 class="fw-bold mt-2"><?= $totalKunjungan ?></h3>
        <p class="text-muted mb-0">Total Kunjungan</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card border-0 shadow-sm p-3">
        <h6 class="fw-bold"><i class="bi bi-person-circle me-2"></i>Data Diri</h6>
        <table class="table table-sm mb-0">
          <tr><td class="text-muted">NIK</td><td><?= htmlspecialchars($pasien['nik'] ?? '-') ?></td></tr>
          <tr><td class="text-muted">Tgl Lahir</td><td><?= htmlspecialchars($pasien['tanggal_lahir'] ?? '-') ?></td></tr>
          <tr><td class="text-muted">Telepon</td><td><?= htmlspecialchars($pasien['telepon'] ?? '-') ?></td></tr>
        </table>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card border-0 shadow-sm p-3">
        <h6 class="fw-bold mb-3"><i class="bi bi-lightning me-2"></i>Menu Cepat</h6>
        <div class="d-grid gap-2">
          <a href="kunjungan.php" class="btn btn-primary btn-sm"><i class="bi bi-calendar-plus me-1"></i>Daftar Kunjungan Baru</a>
          <a href="riwayat.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-clock-history me-1"></i>Lihat Riwayat</a>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>