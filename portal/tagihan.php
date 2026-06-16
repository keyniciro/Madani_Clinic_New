<?php
require_once '../checkuser.php';
require_once '../db.php';
if ($_SESSION['role'] !== 'pasien') { header('location: /login.php'); exit; }

$stmt = $conn->prepare("SELECT p.id FROM pasien p LEFT JOIN users u ON p.user_id = u.id WHERE u.email = :email");
$stmt->bindParam(':email', $_SESSION['email']);
$stmt->execute();
$pasien    = $stmt->fetch(PDO::FETCH_ASSOC);
$pasien_id = $pasien['id'] ?? 0;

$sql = "SELECT t.*, k.tanggal, d.nama AS nama_dokter
        FROM tagihan t
        LEFT JOIN kunjungan k ON t.kunjungan_id = k.id
        LEFT JOIN dokter d ON k.dokter_id = d.id
        WHERE k.pasien_id = :pasien_id
        ORDER BY t.id DESC";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':pasien_id', $pasien_id);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tagihan Saya - Klinik Madani</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include '../dashboard/navbar.php'; ?>
<div class="container py-4">
  <h4 class="fw-bold mb-4"><i class="bi bi-receipt me-2"></i>Tagihan Saya</h4>

  <?php if (count($rows) > 0): ?>
    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead class="table-primary text-center">
              <tr><th>No</th><th>Tgl Kunjungan</th><th>Dokter</th><th>Total</th><th>Status</th></tr>
            </thead>
            <tbody>
              <?php $no = 1; foreach ($rows as $row): ?>
              <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['tanggal']) ?></td>
                <td>Dr. <?= htmlspecialchars($row['nama_dokter']) ?></td>
                <td>Rp <?= number_format($row['total_bayar'], 0, ',', '.') ?></td>
                <td class="text-center">
                  <span class="badge bg-<?= $row['status'] === 'lunas' ? 'success' : 'danger' ?>">
                    <?= $row['status'] === 'lunas' ? 'Lunas' : 'Belum Bayar' ?>
                  </span>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  <?php else: ?>
    <div class="text-center py-5 text-muted">
      <i class="bi bi-receipt fs-1"></i>
      <p class="mt-2">Belum ada tagihan</p>
    </div>
  <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>