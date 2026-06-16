<?php
require_once '../checkuser.php';
require_once '../db.php';
if ($_SESSION['role'] !== 'pasien') { header('location: /login.php'); exit; }

$stmt = $conn->prepare("SELECT p.id FROM pasien p LEFT JOIN users u ON p.user_id = u.id WHERE u.email = :email");
$stmt->bindParam(':email', $_SESSION['email']);
$stmt->execute();
$pasien = $stmt->fetch(PDO::FETCH_ASSOC);
$pasien_id = $pasien['id'] ?? 0;

$dokterList = $conn->query("SELECT id, nama, spesialis FROM dokter ORDER BY nama")->fetchAll(PDO::FETCH_ASSOC);

$error = $success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dokter_id = $_POST['dokter_id'];
    $tanggal   = $_POST['tanggal'];
    $keluhan   = $_POST['keluhan'];

    $stmtA = $conn->prepare("SELECT COUNT(*) FROM kunjungan WHERE tanggal = :tanggal");
    $stmtA->bindParam(':tanggal', $tanggal);
    $stmtA->execute();
    $nomor_antrian = $stmtA->fetchColumn() + 1;

    $sql = "INSERT INTO kunjungan (pasien_id, dokter_id, tanggal, keluhan, nomor_antrian, status)
            VALUES (:pasien_id, :dokter_id, :tanggal, :keluhan, :nomor_antrian, 'menunggu')";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':pasien_id', $pasien_id);
    $stmt->bindParam(':dokter_id', $dokter_id);
    $stmt->bindParam(':tanggal', $tanggal);
    $stmt->bindParam(':keluhan', $keluhan);
    $stmt->bindParam(':nomor_antrian', $nomor_antrian);

    if ($stmt->execute()) {
        $success = "Pendaftaran berhasil! Nomor antrian Anda: <strong>$nomor_antrian</strong>";
    } else {
        $error = "Gagal mendaftar, coba lagi.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar Kunjungan - Klinik Madani</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include '../dashboard/navbar.php'; ?>
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-7">
      <div class="card shadow border-0">
        <div class="card-header bg-primary text-white">
          <h5 class="mb-0"><i class="bi bi-calendar-plus me-2"></i>Daftar Kunjungan Baru</h5>
        </div>
        <div class="card-body p-4">
          <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
          <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
          <form method="POST">
            <div class="mb-3">
              <label class="form-label">Pilih Dokter</label>
              <select name="dokter_id" class="form-select" required>
                <option value="">-- Pilih Dokter --</option>
                <?php foreach ($dokterList as $d): ?>
                  <option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['nama']) ?> - <?= htmlspecialchars($d['spesialis']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Tanggal Kunjungan</label>
              <input type="date" name="tanggal" class="form-control" min="<?= date('Y-m-d') ?>" required>
            </div>
            <div class="mb-4">
              <label class="form-label">Keluhan</label>
              <textarea name="keluhan" class="form-control" rows="3" placeholder="Ceritakan keluhan Anda..." required></textarea>
            </div>
            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-check-circle me-1"></i>Daftar Sekarang</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>