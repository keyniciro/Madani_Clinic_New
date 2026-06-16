<?php
require_once '../../checkuser.php';
require_once "../../db.php";

// Query untuk mengambil semua data pengguna
$sql = "SELECT p.*, u.email, u.role FROM pasien p LEFT JOIN users u ON p.user_id = u.id";
$stmt = $conn->prepare($sql);
$stmt->execute();

// Menampilkan data dalam tabel HTML
// if ($stmt->rowCount() > 0) {
//     echo "<table border='1' cellpadding='10'>";
//     echo "<tr><th>Nama</th><th>nik</th><th>Tanggal Lahir</th><th>Jenis Kelamin</th><th>Alamat</th><th>No telpon</th><th>Action</th></tr>";

//     while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
//         echo "<tr>";
//         echo "<td>" . $row['nama'] . "</td>";
//         echo "<td>" . $row['nik'] . "</td>";
//         echo "<td>" . $row['tanggal_lahir'] . "</td>";
//         echo "<td>" . $row['jenis_kelamin'] . "</td>";
//         echo "<td>" . $row['alamat'] . "</td>";
//         echo "<td>" . $row["no_hp"] . "</td>";
//         echo "<td><a href='update_user.php?id=" . $row['id'] . "'>Edit</a> | <a href='delete_user.php?id=" . $row['id'] . "'>Delete</a></td>";
//         echo "</tr>";
//     }

//     echo "</table>";
// } else {
//     echo "Tidak ada data ditemukan";
// }

$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
$success = $_GET['success'] ?? '';
$error   = $_GET['error'] ?? '';

?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Pasien - Klinik Madani</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include '../navbar.php'; ?>
<div class="container py-4">
  <?php if ($success): ?><div class="alert alert-success alert-dismissible fade show"><?= htmlspecialchars($success) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
  <?php if ($error): ?><div class="alert alert-danger alert-dismissible fade show"><?= htmlspecialchars($error) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>

  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold mb-0"><i class="bi bi-people me-2"></i>Data Pasien</h4>
      <small class="text-muted">Total: <?= count($rows) ?> pasien</small>
    </div>
    <a href="create.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah Pasien</a>
  </div>

  <div class="card border-0 shadow-sm">
    <div class="card-body">
      <div class="row mb-3">
        <div class="col-md-4">
          <input type="text" id="search" class="form-control" placeholder="Cari nama / NIK...">
        </div>
      </div>
      <div class="table-responsive">
        <table class="table table-hover align-middle" id="tabel">
          <thead class="table-primary text-center">
            <tr>
              <th>No</th><th>Nama</th><th>NIK</th><th>Tgl Lahir</th><th>JK</th><th>Telepon</th><th>Email</th><th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (count($rows) > 0): ?>
              <?php $no = 1; foreach ($rows as $row): ?>
              <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['nama']) ?></td>
                <td><?= htmlspecialchars($row['nik']) ?></td>
                <td><?= htmlspecialchars($row['tanggal_lahir']) ?></td>
                <td class="text-center"><?= $row['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan' ?></td>
                <td><?= htmlspecialchars($row['telepon']) ?></td>
                <td><?= htmlspecialchars($row['email'] ?? '-') ?></td>
                <td class="text-center">
                  <a href="update.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></a>
                  <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus pasien ini?')"><i class="bi bi-trash"></i></a>
                </td>
              </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="8" class="text-center text-muted py-4">Belum ada data pasien</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.getElementById('search').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#tabel tbody tr').forEach(tr => {
      tr.style.display = tr.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
  });
</script>
</body>
</html>