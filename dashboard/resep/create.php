<?php
require_once '../../checkuser.php';
require_once '../../db.php';
$role = $_SESSION['role'];
if (!in_array($role, ['admin', 'dokter'])) { header('location: /login.php'); exit; }

$rmList  = $conn->query("SELECT rm.id, p.nama AS nama_pasien, rm.diagnosa, k.tanggal
                          FROM rekam_medis rm
                          LEFT JOIN kunjungan k ON rm.kunjungan_id = k.id
                          LEFT JOIN pasien p ON k.pasien_id = p.id
                          ORDER BY k.tanggal DESC")->fetchAll(PDO::FETCH_ASSOC);
$obatList = $conn->query("SELECT * FROM obat WHERE stok > 0 ORDER BY nama_obat")->fetchAll(PDO::FETCH_ASSOC);

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rekam_medis_id = $_POST['rekam_medis_id'];
    $obat_ids       = $_POST['obat_id'] ?? [];
    $dosis          = $_POST['dosis'] ?? [];
    $jumlah         = $_POST['jumlah'] ?? [];

    if (empty($obat_ids)) {
        $error = "Pilih minimal satu obat.";
    } else {
        // Insert resep
        $stmtR = $conn->prepare("INSERT INTO resep (rekam_medis_id) VALUES (:rekam_medis_id)");
        $stmtR->bindParam(':rekam_medis_id', $rekam_medis_id);
        $stmtR->execute();
        $resep_id = $conn->lastInsertId();

        // Insert resep_detail & kurangi stok
        foreach ($obat_ids as $i => $obat_id) {
            $stmtD = $conn->prepare("INSERT INTO resep_detail (resep_id, obat_id, dosis, jumlah) VALUES (:resep_id, :obat_id, :dosis, :jumlah)");
            $stmtD->bindParam(':resep_id', $resep_id);
            $stmtD->bindParam(':obat_id', $obat_id);
            $stmtD->bindParam(':dosis', $dosis[$i]);
            $stmtD->bindParam(':jumlah', $jumlah[$i]);
            $stmtD->execute();

            // Kurangi stok obat
            $stmtS = $conn->prepare("UPDATE obat SET stok = stok - :jumlah WHERE id = :id");
            $stmtS->bindParam(':jumlah', $jumlah[$i]);
            $stmtS->bindParam(':id', $obat_id);
            $stmtS->execute();
        }
        header('location: index.php?success=Resep berhasil dibuat'); exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Buat Resep - Klinik Madani</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include '../navbar.php'; ?>
<div class="container py-5">
  <div class="card shadow border-0">
    <div class="card-header bg-primary text-white">
      <h5 class="mb-0"><i class="bi bi-file-earmark-plus me-2"></i>Buat Resep</h5>
    </div>
    <div class="card-body p-4">
      <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
      <form method="POST" id="formResep">
        <div class="mb-4">
          <label class="form-label">Rekam Medis</label>
          <select name="rekam_medis_id" class="form-select" required>
            <option value="">-- Pilih Rekam Medis --</option>
            <?php foreach ($rmList as $rm): ?>
              <option value="<?= $rm['id'] ?>">
                <?= htmlspecialchars($rm['nama_pasien']) ?> - <?= $rm['tanggal'] ?> | <?= htmlspecialchars($rm['diagnosa']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <hr>
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h6 class="mb-0"><i class="bi bi-capsule me-1"></i>Daftar Obat</h6>
          <button type="button" class="btn btn-outline-primary btn-sm" id="tambahObat">
            <i class="bi bi-plus-lg me-1"></i>Tambah Obat
          </button>
        </div>

        <div id="obatContainer">
          <div class="row g-2 align-items-end mb-2 obat-row">
            <div class="col-md-5">
              <label class="form-label">Obat</label>
              <select name="obat_id[]" class="form-select" required>
                <option value="">-- Pilih Obat --</option>
                <?php foreach ($obatList as $o): ?>
                  <option value="<?= $o['id'] ?>"><?= htmlspecialchars($o['nama_obat']) ?> (stok: <?= $o['stok'] ?>)</option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Dosis</label>
              <input type="text" name="dosis[]" class="form-control" placeholder="Contoh: 3x1 sehari" required>
            </div>
            <div class="col-md-2">
              <label class="form-label">Jumlah</label>
              <input type="number" name="jumlah[]" class="form-control" min="1" value="1" required>
            </div>
            <div class="col-md-1">
              <button type="button" class="btn btn-danger btn-sm hapusObat" style="display:none"><i class="bi bi-trash"></i></button>
            </div>
          </div>
        </div>

        <div class="d-flex gap-2 mt-4">
          <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan Resep</button>
          <a href="index.php" class="btn btn-secondary">Kembali</a>
        </div>
      </form>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const obatOptions = `<?php foreach ($obatList as $o): ?><option value="<?= $o['id'] ?>"><?= htmlspecialchars($o['nama_obat']) ?> (stok: <?= $o['stok'] ?>)</option><?php endforeach; ?>`;

  document.getElementById('tambahObat').addEventListener('click', function() {
    const row = document.createElement('div');
    row.className = 'row g-2 align-items-end mb-2 obat-row';
    row.innerHTML = `
      <div class="col-md-5">
        <select name="obat_id[]" class="form-select" required>
          <option value="">-- Pilih Obat --</option>${obatOptions}
        </select>
      </div>
      <div class="col-md-4">
        <input type="text" name="dosis[]" class="form-control" placeholder="Contoh: 3x1 sehari" required>
      </div>
      <div class="col-md-2">
        <input type="number" name="jumlah[]" class="form-control" min="1" value="1" required>
      </div>
      <div class="col-md-1">
        <button type="button" class="btn btn-danger btn-sm hapusObat"><i class="bi bi-trash"></i></button>
      </div>`;
    document.getElementById('obatContainer').appendChild(row);
    row.querySelector('.hapusObat').addEventListener('click', () => row.remove());
  });
</script>
</body>
</html>