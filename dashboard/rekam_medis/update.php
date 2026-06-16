<?php
require_once '../../checkuser.php';
require_once '../../db.php';
$role = $_SESSION['role'];
if (!in_array($role, ['admin', 'dokter'])) { header('location: /login.php'); exit; }

if (!isset($_GET['id'])) { header('location: index.php'); exit; }
$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM rekam_medis WHERE id = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$rm = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$rm) { header('location: index.php'); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $diagnosa      = $_POST['diagnosa'];
    $tindakan      = $_POST['tindakan'];
    $tekanan_darah = $_POST['tekanan_darah'];
    $suhu          = $_POST['suhu'];
    $berat_badan   = $_POST['berat_badan'];
    $tinggi_badan  = $_POST['tinggi_badan'];

    $sql = "UPDATE rekam_medis SET diagnosa=:diagnosa, tindakan=:tindakan,
            tekanan_darah=:tekanan_darah, suhu=:suhu,
            berat_badan=:berat_badan, tinggi_badan=:tinggi_badan
            WHERE id=:id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':diagnosa', $diagnosa);
    $stmt->bindParam(':tindakan', $tindakan);
    $stmt->bindParam(':tekanan_darah', $tekanan_darah);
    $stmt->bindParam(':suhu', $suhu);
    $stmt->bindParam(':berat_badan', $berat_badan);
    $stmt->bindParam(':tinggi_badan', $tinggi_badan);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        header('location: index.php?success=Rekam medis berhasil diperbarui'); exit;
    } else {
        $error = "Gagal memperbarui rekam medis.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Rekam Medis - Klinik Madani</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include '../navbar.php'; ?>
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card shadow border-0">
        <div class="card-header bg-primary text-white">
          <h5 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Edit Rekam Medis</h5>
        </div>
        <div class="card-body p-4">
          <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
          <form method="POST">

            <h6 class="text-muted mb-3"><i class="bi bi-activity me-1"></i>Pemeriksaan Fisik</h6>
            <div class="row g-3 mb-3">
              <div class="col-md-3">
                <label class="form-label">Tekanan Darah</label>
                <div class="input-group">
                  <input type="text" name="tekanan_darah" class="form-control" value="<?= htmlspecialchars($rm['tekanan_darah'] ?? '') ?>">
                  <span class="input-group-text">mmHg</span>
                </div>
              </div>
              <div class="col-md-3">
                <label class="form-label">Suhu</label>
                <div class="input-group">
                  <input type="number" name="suhu" class="form-control" step="0.1" value="<?= htmlspecialchars($rm['suhu'] ?? '') ?>">
                  <span class="input-group-text">°C</span>
                </div>
              </div>
              <div class="col-md-3">
                <label class="form-label">Berat Badan</label>
                <div class="input-group">
                  <input type="number" name="berat_badan" class="form-control" step="0.01" value="<?= htmlspecialchars($rm['berat_badan'] ?? '') ?>">
                  <span class="input-group-text">kg</span>
                </div>
              </div>
              <div class="col-md-3">
                <label class="form-label">Tinggi Badan</label>
                <div class="input-group">
                  <input type="number" name="tinggi_badan" class="form-control" step="0.01" value="<?= htmlspecialchars($rm['tinggi_badan'] ?? '') ?>">
                  <span class="input-group-text">cm</span>
                </div>
              </div>
            </div>

            <hr><h6 class="text-muted mb-3"><i class="bi bi-clipboard2-pulse me-1"></i>Hasil Pemeriksaan</h6>
            <div class="mb-3">
              <label class="form-label">Diagnosa</label>
              <input type="text" name="diagnosa" class="form-control" value="<?= htmlspecialchars($rm['diagnosa']) ?>" required>
            </div>
            <div class="mb-4">
              <label class="form-label">Tindakan</label>
              <textarea name="tindakan" class="form-control" rows="3" required><?= htmlspecialchars($rm['tindakan']) ?></textarea>
            </div>

            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan Perubahan</button>
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