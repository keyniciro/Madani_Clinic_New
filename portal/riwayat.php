<?php
require_once '../checkuser.php';
require_once '../db.php';
if ($_SESSION['role'] !== 'pasien') { header('location: /login.php'); exit; }

$stmt = $conn->prepare("SELECT p.id FROM pasien p LEFT JOIN users u ON p.user_id = u.id WHERE u.email = :email");
$stmt->bindParam(':email', $_SESSION['email']);
$stmt->execute();
$pasien    = $stmt->fetch(PDO::FETCH_ASSOC);
$pasien_id = $pasien['id'] ?? 0;

$sql = "SELECT k.*, d.nama AS nama_dokter, d.spesialis,
               rm.id AS rm_id, rm.diagnosa, rm.tindakan,
               rm.tekanan_darah, rm.suhu, rm.berat_badan, rm.tinggi_badan
        FROM kunjungan k
        LEFT JOIN dokter d ON k.dokter_id = d.id
        LEFT JOIN rekam_medis rm ON rm.kunjungan_id = k.id
        WHERE k.pasien_id = :pasien_id
        ORDER BY k.tanggal DESC";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':pasien_id', $pasien_id);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Riwayat Kunjungan - Klinik Madani</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include '../dashboard/navbar.php'; ?>
<div class="container py-4">
  <h4 class="fw-bold mb-4"><i class="bi bi-clock-history me-2"></i>Riwayat Kunjungan Saya</h4>

  <?php if (count($rows) > 0): ?>
    <?php foreach ($rows as $row): ?>
    <div class="card border-0 shadow-sm mb-3">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <div>
            <h6 class="fw-bold mb-0">Dr. <?= htmlspecialchars($row['nama_dokter']) ?></h6>
            <small class="text-muted"><?= htmlspecialchars($row['spesialis']) ?></small>
          </div>
          <div class="text-end">
            <?php
            $badge = match($row['status']) {
              'menunggu' => 'warning text-dark',
              'diproses' => 'info text-dark',
              'selesai'  => 'success',
              default    => 'secondary'
            };
            ?>
            <span class="badge bg-<?= $badge ?>"><?= ucfirst($row['status']) ?></span>
            <div class="text-muted small mt-1"><?= htmlspecialchars($row['tanggal']) ?></div>
          </div>
        </div>

        <p class="mb-2"><strong>Keluhan:</strong> <?= htmlspecialchars($row['keluhan']) ?></p>

        <?php if ($row['rm_id']): ?>
          <hr class="my-2">
          <div class="row g-2 mb-2">
            <div class="col-6 col-md-3">
              <div class="bg-light rounded p-2 text-center">
                <small class="text-muted d-block">Tekanan Darah</small>
                <strong><?= htmlspecialchars($row['tekanan_darah'] ?? '-') ?> mmHg</strong>
              </div>
            </div>
            <div class="col-6 col-md-3">
              <div class="bg-light rounded p-2 text-center">
                <small class="text-muted d-block">Suhu</small>
                <strong><?= $row['suhu'] ? $row['suhu'].' °C' : '-' ?></strong>
              </div>
            </div>
            <div class="col-6 col-md-3">
              <div class="bg-light rounded p-2 text-center">
                <small class="text-muted d-block">Berat Badan</small>
                <strong><?= $row['berat_badan'] ? $row['berat_badan'].' kg' : '-' ?></strong>
              </div>
            </div>
            <div class="col-6 col-md-3">
              <div class="bg-light rounded p-2 text-center">
                <small class="text-muted d-block">Tinggi Badan</small>
                <strong><?= $row['tinggi_badan'] ? $row['tinggi_badan'].' cm' : '-' ?></strong>
              </div>
            </div>
          </div>
          <p class="mb-1"><strong>Diagnosa:</strong> <?= htmlspecialchars($row['diagnosa']) ?></p>
          <p class="mb-0"><strong>Tindakan:</strong> <?= htmlspecialchars($row['tindakan']) ?></p>
        <?php else: ?>
          <p class="text-muted small mb-0 mt-1">
            <i class="bi bi-info-circle me-1"></i>Rekam medis belum tersedia
          </p>
        <?php endif; ?>
      </div>
    </div>
    <?php endforeach; ?>
  <?php else: ?>
    <div class="text-center py-5 text-muted">
      <i class="bi bi-calendar-x fs-1"></i>
      <p class="mt-2">Belum ada riwayat kunjungan</p>
      <a href="kunjungan.php" class="btn btn-primary">Daftar Kunjungan Sekarang</a>
    </div>
  <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>