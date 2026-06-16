<?php
require_once '../../checkuser.php';
require_once '../../db.php';
if ($_SESSION['role'] !== 'admin') { header('location: /login.php'); exit; }

// Kunjungan yang sudah selesai dan belum punya tagihan
$kunjunganList = $conn->query(
    "SELECT k.id, p.nama AS nama_pasien, k.tanggal
     FROM kunjungan k
     LEFT JOIN pasien p ON k.pasien_id = p.id
     WHERE k.status = 'selesai'
     AND k.id NOT IN (SELECT kunjungan_id FROM tagihan)
     ORDER BY k.tanggal DESC"
)->fetchAll(PDO::FETCH_ASSOC);

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kunjungan_id = $_POST['kunjungan_id'];
    $total_bayar  = $_POST['total_bayar'];

    $stmt = $conn->prepare("INSERT INTO tagihan (kunjungan_id, total_bayar, status) VALUES (:kunjungan_id, :total_bayar, 'belum_bayar')");
    $stmt->bindParam(':kunjungan_id', $kunjungan_id);
    $stmt->bindParam(':total_bayar', $total_bayar);

    if ($stmt->execute()) {
        header('location: index.php?success=Tagihan berhasil dibuat'); exit;
    } else {
        $error = "Gagal membuat tagihan.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Buat Tagihan - Klinik Madani</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include '../navbar.php'; ?>
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-6">
      <div class="card shadow border-0">
        <div class="card-header bg-primary text-white">
          <h5 class="mb-0"><i class="bi bi-receipt me-2"></i>Buat Tagihan</h5>
        </div>
        <div class="card-body p-4">
          <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
          <?php if (empty($kunjunganList)): ?>
            <div class="alert alert-info">Tidak ada kunjungan selesai yang belum ditagih.</div>
            <a href="index.php" class="btn btn-secondary">Kembali</a>
          <?php else: ?>
          <form method="POST">
            <div class="mb-3">
              <label class="form-label">Kunjungan</label>
              <select name="kunjungan_id" class="form-select" required>
                <option value="">-- Pilih Kunjungan --</option>
                <?php foreach ($kunjunganList as $k): ?>
                  <option value="<?= $k['id'] ?>"><?= htmlspecialchars($k['nama_pasien']) ?> - <?= $k['tanggal'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="mb-4">
              <label class="form-label">Total Bayar (Rp)</label>
              <div class="input-group">
                <span class="input-group-text">Rp</span>
                <input type="number" name="total_bayar" class="form-control" min="0" step="1000" required>
              </div>
            </div>
            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
              <a href="index.php" class="btn btn-secondary">Kembali</a>
            </div>
          </form>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>