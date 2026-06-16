<?php
require_once '../../checkuser.php';
require_once '../../db.php';
$role = $_SESSION['role'];
if (!in_array($role, ['admin', 'dokter'])) { header('location: /login.php'); exit; }

if ($role === 'dokter') {
    $stmtD = $conn->prepare("SELECT d.id FROM dokter d LEFT JOIN users u ON d.user_id = u.id WHERE u.email = :email");
    $stmtD->bindParam(':email', $_SESSION['email']);
    $stmtD->execute();
    $dokterData = $stmtD->fetch(PDO::FETCH_ASSOC);
    $dokter_id  = $dokterData['id'] ?? 0;

    $sql = "SELECT r.*, rm.diagnosa, p.nama AS nama_pasien, k.tanggal
            FROM resep r
            LEFT JOIN rekam_medis rm ON r.rekam_medis_id = rm.id
            LEFT JOIN kunjungan k ON rm.kunjungan_id = k.id
            LEFT JOIN pasien p ON k.pasien_id = p.id
            WHERE k.dokter_id = :dokter_id
            ORDER BY r.id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':dokter_id', $dokter_id);
} else {
    $sql = "SELECT r.*, rm.diagnosa, p.nama AS nama_pasien, k.tanggal
            FROM resep r
            LEFT JOIN rekam_medis rm ON r.rekam_medis_id = rm.id
            LEFT JOIN kunjungan k ON rm.kunjungan_id = k.id
            LEFT JOIN pasien p ON k.pasien_id = p.id
            ORDER BY r.id DESC";
    $stmt = $conn->prepare($sql);
}
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
$success = $_GET['success'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Resep - Klinik Madani</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include '../navbar.php'; ?>
<div class="container py-4">
  <?php if ($success): ?><div class="alert alert-success alert-dismissible fade show"><?= htmlspecialchars($success) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>

  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold mb-0"><i class="bi bi-file-earmark-medical me-2"></i>Data Resep</h4>
      <small class="text-muted">Total: <?= count($rows) ?> resep</small>
    </div>
    <a href="create.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Buat Resep</a>
  </div>

  <div class="card border-0 shadow-sm">
    <div class="card-body">
      <div class="mb-3">
        <input type="text" id="search" class="form-control w-25" placeholder="Cari pasien...">
      </div>
      <div class="table-responsive">
        <table class="table table-hover align-middle" id="tabel">
          <thead class="table-primary text-center">
            <tr>
              <th>No</th><th>Pasien</th><th>Diagnosa</th><th>Tgl Kunjungan</th><th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (count($rows) > 0): ?>
              <?php $no = 1; foreach ($rows as $row): ?>
              <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['nama_pasien']) ?></td>
                <td><?= htmlspecialchars($row['diagnosa']) ?></td>
                <td><?= htmlspecialchars($row['tanggal']) ?></td>
                <td class="text-center">
                  <a href="detail.php?id=<?= $row['id'] ?>" class="btn btn-info btn-sm"><i class="bi bi-eye"></i></a>
                  <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus resep ini?')"><i class="bi bi-trash"></i></a>
                </td>
              </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="5" class="text-center text-muted py-4">Belum ada data resep</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.getElementById('search').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#tabel tbody tr').forEach(tr => {
      tr.style.display = tr.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
  });
</script>
</body>
</html>