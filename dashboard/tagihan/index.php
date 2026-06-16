<?php
require_once '../../checkuser.php';
require_once '../../db.php';
if ($_SESSION['role'] !== 'admin') { header('location: /login.php'); exit; }

$sql = "SELECT t.*, p.nama AS nama_pasien, k.tanggal
        FROM tagihan t
        LEFT JOIN kunjungan k ON t.kunjungan_id = k.id
        LEFT JOIN pasien p ON k.pasien_id = p.id
        ORDER BY t.id DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
$success = $_GET['success'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tagihan - Klinik Madani</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include '../navbar.php'; ?>
<div class="container py-4">
  <?php if ($success): ?><div class="alert alert-success alert-dismissible fade show"><?= htmlspecialchars($success) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>

  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold mb-0"><i class="bi bi-receipt me-2"></i>Data Tagihan</h4>
      <small class="text-muted">Total: <?= count($rows) ?> tagihan</small>
    </div>
    <a href="create.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Buat Tagihan</a>
  </div>

  <div class="card border-0 shadow-sm">
    <div class="card-body">
      <div class="row mb-3 g-2">
        <div class="col-md-4">
          <input type="text" id="search" class="form-control" placeholder="Cari pasien...">
        </div>
        <div class="col-md-3">
          <select id="filterStatus" class="form-select">
            <option value="">Semua Status</option>
            <option value="belum_bayar">Belum Bayar</option>
            <option value="lunas">Lunas</option>
          </select>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table table-hover align-middle" id="tabel">
          <thead class="table-primary text-center">
            <tr>
              <th>No</th><th>Pasien</th><th>Tgl Kunjungan</th><th>Total Bayar</th><th>Status</th><th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (count($rows) > 0): ?>
              <?php $no = 1; foreach ($rows as $row): ?>
              <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['nama_pasien']) ?></td>
                <td><?= htmlspecialchars($row['tanggal']) ?></td>
                <td>Rp <?= number_format($row['total_bayar'], 0, ',', '.') ?></td>
                <td class="text-center">
                  <span class="badge bg-<?= $row['status'] === 'lunas' ? 'success' : 'danger' ?>">
                    <?= $row['status'] === 'lunas' ? 'Lunas' : 'Belum Bayar' ?>
                  </span>
                </td>
                <td class="text-center">
                  <?php if ($row['status'] === 'belum_bayar'): ?>
                    <a href="bayar.php?id=<?= $row['id'] ?>" class="btn btn-success btn-sm" onclick="return confirm('Tandai sudah lunas?')">
                      <i class="bi bi-check-circle me-1"></i>Bayar
                    </a>
                  <?php endif; ?>
                  <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus tagihan ini?')"><i class="bi bi-trash"></i></a>
                </td>
              </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data tagihan</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  function filter() {
    const q = document.getElementById('search').value.toLowerCase();
    const s = document.getElementById('filterStatus').value.toLowerCase();
    document.querySelectorAll('#tabel tbody tr').forEach(tr => {
      const text   = tr.textContent.toLowerCase();
      const status = tr.cells[4]?.textContent.trim().toLowerCase() ?? '';
      tr.style.display = text.includes(q) && (s === '' || status.includes(s === 'belum_bayar' ? 'belum' : s)) ? '' : 'none';
    });
  }
  document.getElementById('search').addEventListener('input', filter);
  document.getElementById('filterStatus').addEventListener('change', filter);
</script>
</body>
</html>