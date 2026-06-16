<?php
require_once '../../checkuser.php';
require_once '../../db.php';
$role = $_SESSION['role'];
if (!in_array($role, ['admin', 'dokter'])) { header('location: /login.php'); exit; }

$kunjunganList = $conn->query(
    "SELECT k.id, p.nama AS nama_pasien, k.tanggal, k.keluhan
     FROM kunjungan k
     LEFT JOIN pasien p ON k.pasien_id = p.id
     WHERE k.status IN ('diproses','selesai')
     ORDER BY k.tanggal DESC"
)->fetchAll(PDO::FETCH_ASSOC);

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kunjungan_id  = $_POST['kunjungan_id'];
    $diagnosa      = $_POST['diagnosa'];
    $tindakan      = $_POST['tindakan'];
    $tekanan_darah = $_POST['tekanan_darah'];
    $suhu          = $_POST['suhu'];
    $berat_badan   = $_POST['berat_badan'];
    $tinggi_badan  = $_POST['tinggi_badan'];

    $sql = "INSERT INTO rekam_medis (kunjungan_id, diagnosa, tindakan, tekanan_darah, suhu, berat_badan, tinggi_badan)
            VALUES (:kunjungan_id, :diagnosa, :tindakan, :tekanan_darah, :suhu, :berat_badan, :tinggi_badan)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':kunjungan_id', $kunjungan_id);
    $stmt->bindParam(':diagnosa', $diagnosa);
    $stmt->bindParam(':tindakan', $tindakan);
    $stmt->bindParam(':tekanan_darah', $tekanan_darah);
    $stmt->bindParam(':suhu', $suhu);
    $stmt->bindParam(':berat_badan', $berat_badan);
    $stmt->bindParam(':tinggi_badan', $tinggi_badan);

    if ($stmt->execute()) {
        // Update status kunjungan jadi selesai
        $stmtU = $conn->prepare("UPDATE kunjungan SET status='selesai' WHERE id=:id");
        $stmtU->bindParam(':id', $kunjungan_id);
        $stmtU->execute();
        header('location: index.php?success=Rekam medis berhasil ditambahkan'); exit;
    } else {
        $error = "Gagal menyimpan rekam medis.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Rekam Medis - Klinik Madani</title>
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
          <h5 class="mb-0"><i class="bi bi-clipboard2-plus me-2"></i>Tambah Rekam Medis</h5>
        </div>
        <div class="card-body p-4">
          <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
          <form method="POST">

            <div class="mb-3">
              <label class="form-label">Kunjungan</label>
              <select name="kunjungan_id" class="form-select" required>
                <option value="">-- Pilih Kunjungan --</option>
                <?php foreach ($kunjunganList as $k): ?>
                  <option value="<?= $k['id'] ?>">
                    <?= htmlspecialchars($k['nama_pasien']) ?> - <?= $k['tanggal'] ?> | <?= htmlspecialchars($k['keluhan']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <hr><h6 class="text-muted mb-3"><i class="bi bi-activity me-1"></i>Pemeriksaan Fisik</h6>
            <div class="row g-3 mb-3">
              <div class="col-md-3">
                <label class="form-label">Tekanan Darah</label>
                <div class="input-group">
                  <input type="text" name="tekanan_darah" class="form-control" placeholder="120/80">
                  <span class="input-group-text">mmHg</span>
                </div>
              </div>
              <div class="col-md-3">
                <label class="form-label">Suhu</label>
                <div class="input-group">
                  <input type="number" name="suhu" class="form-control" step="0.1" placeholder="36.5">
                  <span class="input-group-text">°C</span>
                </div>
              </div>
              <div class="col-md-3">
                <label class="form-label">Berat Badan</label>
                <div class="input-group">
                  <input type="number" name="berat_badan" class="form-control" step="0.01" placeholder="60">
                  <span class="input-group-text">kg</span>
                </div>
              </div>
              <div class="col-md-3">
                <label class="form-label">Tinggi Badan</label>
                <div class="input-group">
                  <input type="number" name="tinggi_badan" class="form-control" step="0.01" placeholder="170">
                  <span class="input-group-text">cm</span>
                </div>
              </div>
            </div>

            <hr><h6 class="text-muted mb-3"><i class="bi bi-clipboard2-pulse me-1"></i>Hasil Pemeriksaan</h6>
            <div class="mb-3">
              <label class="form-label">Diagnosa</label>
              <input type="text" name="diagnosa" class="form-control" placeholder="Contoh: Hipertensi, ISPA..." required>
            </div>
            <div class="mb-4">
              <label class="form-label">Tindakan</label>
              <textarea name="tindakan" class="form-control" rows="3" placeholder="Tindakan yang dilakukan dokter..." required></textarea>
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