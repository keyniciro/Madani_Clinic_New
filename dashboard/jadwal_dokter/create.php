<?php
require_once '../../checkuser.php';
require_once '../../db.php';
if ($_SESSION['role'] !== 'admin') { header('location: /login.php'); exit; }

$dokterList = $conn->query("SELECT id, nama, spesialis FROM dokter ORDER BY nama")->fetchAll(PDO::FETCH_ASSOC);
$hariList   = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dokter_id   = $_POST['dokter_id'];
    $hari        = $_POST['hari'];
    $jam_mulai   = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];

    $stmt = $conn->prepare("INSERT INTO jadwal_dokter (dokter_id, hari, jam_mulai, jam_selesai) VALUES (:dokter_id, :hari, :jam_mulai, :jam_selesai)");
    $stmt->bindParam(':dokter_id', $dokter_id);
    $stmt->bindParam(':hari', $hari);
    $stmt->bindParam(':jam_mulai', $jam_mulai);
    $stmt->bindParam(':jam_selesai', $jam_selesai);

    if ($stmt->execute()) {
        header('location: index.php?success=Jadwal berhasil ditambahkan'); exit;
    } else {
        $error = "Gagal menambahkan jadwal.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Jadwal - Klinik Madani</title>
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
          <h5 class="mb-0"><i class="bi bi-calendar-plus me-2"></i>Tambah Jadwal Dokter</h5>
        </div>
        <div class="card-body p-4">
          <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
          <form method="POST">
            <div class="mb-3">
              <label class="form-label">Dokter</label>
              <select name="dokter_id" class="form-select" required>
                <option value="">-- Pilih Dokter --</option>
                <?php foreach ($dokterList as $d): ?>
                  <option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['nama']) ?> - <?= htmlspecialchars($d['spesialis']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Hari</label>
              <select name="hari" class="form-select" required>
                <option value="">-- Pilih Hari --</option>
                <?php foreach ($hariList as $h): ?>
                  <option value="<?= $h ?>"><?= $h ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="row g-3 mb-4">
              <div class="col-6">
                <label class="form-label">Jam Mulai</label>
                <input type="time" name="jam_mulai" class="form-control" required>
              </div>
              <div class="col-6">
                <label class="form-label">Jam Selesai</label>
                <input type="time" name="jam_selesai" class="form-control" required>
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