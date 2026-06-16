<?php
require_once '../../checkuser.php';
require_once '../../db.php';
if ($_SESSION['role'] !== 'admin') { header('location: /login.php'); exit; }

$stmt = $conn->prepare("SELECT * FROM obat ORDER BY nama_obat ASC");
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
$success = $_GET['success'] ?? '';
$error   = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Obat - Klinik Madani</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include '../navbar.php'; ?>
<div class="container py-4">
  <?php if ($success): ?><div class="alert alert-success alert-dismissible fade show"><?= htmlspecialchars($success) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
  <?php if ($error): ?><div class="alert alert-danger alert-dismissible fade show"><?= htmlspecialchars($error) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>

  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold mb-0"><i class="bi bi-capsule me-2"></i>Data Obat</h4>
      <small class="text-muted">Total: <?= count($rows) ?> obat</small>
    </div>
    <a href="create.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah Obat</a>
  </div>

  <div class="card border-0 shadow-sm">
    <div class="card-body">
      <div class="mb-3">
        <input type="text" id="search" class="form-control w-25" placeholder="Cari nama obat...">
      </div>
      <div class="table-responsive">
        <table class="table table-hover align-middle" id="tabel">
          <thead class="table-primary text-center">
            <tr>
              <th>No</th><th>Nama Obat</th><th>Stok</th><th>Harga</th><th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (count($rows) > 0): ?>
              <?php $no = 1; foreach ($rows as $row): ?>
              <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['nama_obat']) ?></td>
                <td class="text-center">
                  <span class="badge bg-<?= $row['stok'] <= 10 ? 'danger' : 'success' ?>">
                    <?= $row['stok'] ?>
                  </span>
                </td>
                <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                <td class="text-center">
                  <a href="update.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></a>
                  <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus obat ini?')"><i class="bi bi-trash"></i></a>
                </td>
              </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="5" class="text-center text-muted py-4">Belum ada data obat</td></tr>
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