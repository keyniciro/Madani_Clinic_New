<?php
require_once '../../checkuser.php';
require_once '../../db.php';
$role = $_SESSION['role'];
if (!in_array($role, ['admin', 'dokter'])) { header('location: /login.php'); exit; }

if (!isset($_GET['id'])) { header('location: index.php'); exit; }
$id = $_GET['id'];

$stmtR = $conn->prepare("SELECT r.*, rm.diagnosa, rm.tindakan, p.nama AS nama_pasien, k.tanggal, k.keluhan, d.nama AS nama_dokter
                          FROM resep r
                          LEFT JOIN rekam_medis rm ON r.rekam_medis_id = rm.id
                          LEFT JOIN kunjungan k ON rm.kunjungan_id = k.id
                          LEFT JOIN pasien p ON k.pasien_id = p.id
                          LEFT JOIN dokter d ON k.dokter_id = d.id
                          WHERE r.id = :id");
$stmtR->bindParam(':id', $id, PDO::PARAM_INT);
$stmtR->execute();
$resep = $stmtR->fetch(PDO::FETCH_ASSOC);
if (!$resep) { header('location: index.php'); exit; }

$stmtD = $conn->prepare("SELECT rd.*, o.nama_obat, o.harga FROM resep_detail rd LEFT JOIN obat o ON rd.obat_id = o.id WHERE rd.resep_id = :resep_id");
$stmtD->bindParam(':resep_id', $id, PDO::PARAM_INT);
$stmtD->execute();
$details = $stmtD->fetchAll(PDO::FETCH_ASSOC);
$total = array_sum(array_map(fn($d) => $d['harga'] * $d['jumlah'], $details));
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Detail Resep - Klinik Madani</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include '../navbar.php'; ?>
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-file-earmark-medical me-2"></i>Detail Resep #<?= $id ?></h4>
    <a href="index.php" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
  </div>

  <div class="row g-3">
    <div class="col-md-6">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
          <h6 class="fw-bold mb-3"><i class="bi bi-person me-1"></i>Info Pasien</h6>
          <table class="table table-sm mb-0">
            <tr><td class="text-muted">Pasien</td><td><?= htmlspecialchars($resep['nama_pasien']) ?></td></tr>
            <tr><td class="text-muted">Dokter</td><td>Dr. <?= htmlspecialchars($resep['nama_dokter']) ?></td></tr>
            <tr><td class="text-muted">Tanggal</td><td><?= htmlspecialchars($resep['tanggal']) ?></td></tr>
            <tr><td class="text-muted">Keluhan</td><td><?= htmlspecialchars($resep['keluhan']) ?></td></tr>
            <tr><td class="text-muted">Diagnosa</td><td><?= htmlspecialchars($resep['diagnosa']) ?></td></tr>
          </table>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
          <h6 class="fw-bold mb-3"><i class="bi bi-capsule me-1"></i>Daftar Obat</h6>
          <table class="table table-sm">
            <thead class="table-light"><tr><th>Obat</th><th>Dosis</th><th>Jml</th><th>Subtotal</th></tr></thead>
            <tbody>
              <?php foreach ($details as $d): ?>
              <tr>
                <td><?= htmlspecialchars($d['nama_obat']) ?></td>
                <td><?= htmlspecialchars($d['dosis']) ?></td>
                <td><?= $d['jumlah'] ?></td>
                <td>Rp <?= number_format($d['harga'] * $d['jumlah'], 0, ',', '.') ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
            <tfoot>
              <tr class="fw-bold"><td colspan="3" class="text-end">Total Obat:</td><td>Rp <?= number_format($total, 0, ',', '.') ?></td></tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>