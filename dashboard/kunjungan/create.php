<?php
require_once '../../checkuser.php';
require_once '../../db.php';
if ($_SESSION['role'] !== 'admin') { header('location: /login.php'); exit; }

// Ambil list pasien & dokter untuk dropdown
$pasienList = $conn->query("SELECT id, nama FROM pasien ORDER BY nama")->fetchAll(PDO::FETCH_ASSOC);
$dokterList = $conn->query("SELECT id, nama, spesialis FROM dokter ORDER BY nama")->fetchAll(PDO::FETCH_ASSOC);

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pasien_id = $_POST['pasien_id'];
    $dokter_id = $_POST['dokter_id'];
    $tanggal   = $_POST['tanggal'];
    $keluhan   = $_POST['keluhan'];

    // Nomor antrian otomatis: cek berapa kunjungan hari ini
    $stmtAntri = $conn->prepare("SELECT COUNT(*) FROM kunjungan WHERE tanggal = :tanggal");
    $stmtAntri->bindParam(':tanggal', $tanggal);
    $stmtAntri->execute();
    $count = $stmtAntri->fetchColumn();
    $nomor_antrian = $count + 1;

    $sql = "INSERT INTO kunjungan (pasien_id, dokter_id, tanggal, keluhan, nomor_antrian, status)
            VALUES (:pasien_id, :dokter_id, :tanggal, :keluhan, :nomor_antrian, 'menunggu')";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':pasien_id', $pasien_id);
    $stmt->bindParam(':dokter_id', $dokter_id);
    $stmt->bindParam(':tanggal', $tanggal);
    $stmt->bindParam(':keluhan', $keluhan);
    $stmt->bindParam(':nomor_antrian', $nomor_antrian);

    if ($stmt->execute()) {
        header('location: index.php?success=Kunjungan berhasil ditambahkan'); exit;
    } else {
        $error = "Gagal menambahkan kunjungan.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Kunjungan - Klinik Madani</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include '../navbar.php'; ?>
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-7">
      <div class="card shadow border-0">
        <div class="card-header bg-primary text-white">
          <h5 class="mb-0"><i class="bi bi-calendar-plus me-2"></i>Tambah Kunjungan</h5>
        </div>
        <div class="card-body p-4">
          <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
          <form method="POST">
            <div class="mb-3">
              <label class="form-label">Pasien</label>
              <select name="pasien_id" class="form-select" required>
                <option value="">-- Pilih Pasien --</option>
                <?php foreach ($pasienList as $p): ?>
                  <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nama']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
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
              <label class="form-label">Tanggal Kunjungan</label>
              <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
            </div>
            <div class="mb-4">
              <label class="form-label">Keluhan</label>
              <textarea name="keluhan" class="form-control" rows="3" placeholder="Tulis keluhan pasien..." required></textarea>
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