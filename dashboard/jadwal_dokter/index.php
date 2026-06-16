<?php
require_once '../../checkuser.php';
require_once '../../db.php';
$role = $_SESSION['role'];
if (!in_array($role, ['admin', 'dokter'])) { header('location: /login.php'); exit; }

if ($role === 'dokter') {
    $stmtD = $conn->prepare("SELECT d.id FROM dokter d LEFT JOIN users u ON d.user_id = u.id WHERE u.email = :email");
    $stmtD->bindParam(':email', $_SESSION['email']);
    $stmtD->execute();
    $dData = $stmtD->fetch(PDO::FETCH_ASSOC);
    $dokter_id = $dData['id'] ?? 0;
    $sql = "SELECT jd.*, d.nama AS nama_dokter, d.spesialis FROM jadwal_dokter jd LEFT JOIN dokter d ON jd.dokter_id = d.id WHERE jd.dokter_id = :dokter_id ORDER BY FIELD(jd.hari,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu')";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':dokter_id', $dokter_id);
} else {
    $sql = "SELECT jd.*, d.nama AS nama_dokter, d.spesialis FROM jadwal_dokter jd LEFT JOIN dokter d ON jd.dokter_id = d.id ORDER BY d.nama, FIELD(jd.hari,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu')";
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
  <title>Jadwal Dokter - Klinik Madani</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include '../navbar.php'; ?>
<div class="container py-4">
  <?php if ($success): ?><div class="alert alert-success alert-dismissible fade show"><?= htmlspecialchars($success) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>

  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold mb-0"><i class="bi bi-calendar-week me-2"></i>Jadwal Dokter</h4>
      <small class="text-muted">Total: <?= count($rows) ?> jadwal</small>
    </div>
    <?php if ($role === 'admin'): ?>
      <a href="create.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah Jadwal</a>
    <?php endif; ?>
  </div>

  <div class="card border-0 shadow-sm">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-primary text-center">
            <tr>
              <th>No</th><th>Dokter</th><th>Spesialis</th><th>Hari</th><th>Jam Mulai</th><th>Jam Selesai</th>
              <?php if ($role === 'admin'): ?><th>Aksi</th><?php endif; ?>
            </tr>
          </thead>
          <tbody>
            <?php if (count($rows) > 0): ?>
              <?php $no = 1; foreach ($rows as $row): ?>
              <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td>Dr. <?= htmlspecialchars($row['nama_dokter']) ?></td>
                <td><?= htmlspecialchars($row['spesialis']) ?></td>
                <td class="text-center"><span class="badge bg-primary"><?= $row['hari'] ?></span></td>
                <td class="text-center"><?= substr($row['jam_mulai'], 0, 5) ?></td>
                <td class="text-center"><?= substr($row['jam_selesai'], 0, 5) ?></td>
                <?php if ($role === 'admin'): ?>
                <td class="text-center">
                  <a href="update.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></a>
                  <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus jadwal ini?')"><i class="bi bi-trash"></i></a>
                </td>
                <?php endif; ?>
              </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="7" class="text-center text-muted py-4">Belum ada jadwal dokter</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>