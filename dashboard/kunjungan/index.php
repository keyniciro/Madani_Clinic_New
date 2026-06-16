<?php
require_once '../../checkuser.php';
require_once '../../db.php';

$role = $_SESSION['role'];
if (!in_array($role, ['admin', 'dokter'])) { header('location: /login.php'); exit; }

// Dokter hanya lihat kunjungan miliknya sendiri
if ($role === 'dokter') {
    $stmt = $conn->prepare("SELECT d.id FROM dokter d LEFT JOIN users u ON d.user_id = u.id WHERE u.email = :email");
    $stmt->bindParam(':email', $_SESSION['email']);
    $stmt->execute();
    $dokterData = $stmt->fetch(PDO::FETCH_ASSOC);
    $dokter_id = $dokterData['id'] ?? 0;

    $sql = "SELECT k.*, p.nama AS nama_pasien, d.nama AS nama_dokter, d.spesialis
            FROM kunjungan k
            LEFT JOIN pasien p ON k.pasien_id = p.id
            LEFT JOIN dokter d ON k.dokter_id = d.id
            WHERE k.dokter_id = :dokter_id
            ORDER BY k.tanggal DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':dokter_id', $dokter_id);
} else {
    $sql = "SELECT k.*, p.nama AS nama_pasien, d.nama AS nama_dokter, d.spesialis
            FROM kunjungan k
            LEFT JOIN pasien p ON k.pasien_id = p.id
            LEFT JOIN dokter d ON k.dokter_id = d.id
            ORDER BY k.tanggal DESC";
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
  <title>Kunjungan - Klinik Madani</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include '../navbar.php'; ?>
<div class="container py-4">
  <?php if ($success): ?><div class="alert alert-success alert-dismissible fade show"><?= htmlspecialchars($success) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>

  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold mb-0"><i class="bi bi-calendar-check me-2"></i>Data Kunjungan</h4>
      <small class="text-muted">Total: <?= count($rows) ?> kunjungan</small>
    </div>
    <?php if ($role === 'admin'): ?>
      <a href="create.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah Kunjungan</a>
    <?php endif; ?>
  </div>

  <div class="card border-0 shadow-sm">
    <div class="card-body">
      <div class="row mb-3 g-2">
        <div class="col-md-4">
          <input type="text" id="search" class="form-control" placeholder="Cari nama pasien...">
        </div>
        <div class="col-md-3">
          <select id="filterStatus" class="form-select">
            <option value="">Semua Status</option>
            <option value="menunggu">Menunggu</option>
            <option value="diproses">Diproses</option>
            <option value="selesai">Selesai</option>
          </select>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table table-hover align-middle" id="tabel">
          <thead class="table-primary text-center">
            <tr>
              <th>No</th><th>No. Antrian</th><th>Pasien</th><th>Dokter</th><th>Tanggal</th><th>Keluhan</th><th>Status</th>
              <?php if ($role === 'admin'): ?><th>Aksi</th><?php endif; ?>
            </tr>
          </thead>
          <tbody>
            <?php if (count($rows) > 0): ?>
              <?php $no = 1; foreach ($rows as $row): ?>
              <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td class="text-center fw-bold"><?= htmlspecialchars($row['nomor_antrian']) ?></td>
                <td><?= htmlspecialchars($row['nama_pasien']) ?></td>
                <td><?= htmlspecialchars($row['nama_dokter']) ?><br><small class="text-muted"><?= htmlspecialchars($row['spesialis']) ?></small></td>
                <td><?= htmlspecialchars($row['tanggal']) ?></td>
                <td><?= htmlspecialchars($row['keluhan']) ?></td>
                <td class="text-center">
                  <?php
                  $badge = match($row['status']) {
                    'menunggu' => 'warning text-dark',
                    'diproses' => 'info text-dark',
                    'selesai'  => 'success',
                    default    => 'secondary'
                  };
                  ?>
                  <span class="badge bg-<?= $badge ?>"><?= ucfirst($row['status']) ?></span>
                </td>
                <?php if ($role === 'admin'): ?>
                <td class="text-center">
                  <?php if ($row['status'] === 'menunggu'): ?>
                    <a href="update_status.php?id=<?= $row['id'] ?>&status=diproses" class="btn btn-info btn-sm" onclick="return confirm('Ubah ke Diproses?')"><i class="bi bi-play-fill"></i></a>
                  <?php elseif ($row['status'] === 'diproses'): ?>
                    <a href="update_status.php?id=<?= $row['id'] ?>&status=selesai" class="btn btn-success btn-sm" onclick="return confirm('Tandai Selesai?')"><i class="bi bi-check-lg"></i></a>
                  <?php endif; ?>
                  <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus kunjungan ini?')"><i class="bi bi-trash"></i></a>
                </td>
                <?php endif; ?>
              </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="8" class="text-center text-muted py-4">Belum ada data kunjungan</td></tr>
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
      const status = tr.cells[6]?.textContent.trim().toLowerCase() ?? '';
      tr.style.display = text.includes(q) && (s === '' || status.includes(s)) ? '' : 'none';
    });
  }
  document.getElementById('search').addEventListener('input', filter);
  document.getElementById('filterStatus').addEventListener('change', filter);
</script>
</body>
</html>