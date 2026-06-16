<?php
require_once '../../checkuser.php';
require_once '../../db.php';
if ($_SESSION['role'] !== 'admin') { header('location: /login.php'); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_obat = $_POST['nama_obat'];
    $stok      = $_POST['stok'];
    $harga     = $_POST['harga'];

    $stmt = $conn->prepare("INSERT INTO obat (nama_obat, stok, harga) VALUES (:nama_obat, :stok, :harga)");
    $stmt->bindParam(':nama_obat', $nama_obat);
    $stmt->bindParam(':stok', $stok);
    $stmt->bindParam(':harga', $harga);

    if ($stmt->execute()) {
        header('location: index.php?success=Obat berhasil ditambahkan'); exit;
    } else {
        $error = "Gagal menambahkan obat.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Obat - Klinik Madani</title>
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
          <h5 class="mb-0"><i class="bi bi-capsule me-2"></i>Tambah Obat</h5>
        </div>
        <div class="card-body p-4">
          <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
          <form method="POST">
            <div class="mb-3">
              <label class="form-label">Nama Obat</label>
              <input type="text" name="nama_obat" class="form-control" placeholder="Contoh: Paracetamol 500mg" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Stok</label>
              <input type="number" name="stok" class="form-control" min="0" required>
            </div>
            <div class="mb-4">
              <label class="form-label">Harga (Rp)</label>
              <div class="input-group">
                <span class="input-group-text">Rp</span>
                <input type="number" name="harga" class="form-control" min="0" step="100" required>
              </div>
            </div>
            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
              <a href="index.php" class="btn btn-secondary">Kembali</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>