<?php
require_once '../../checkuser.php';
require_once "../../db.php";
if ($_SESSION['role'] !== 'admin') { header('location: /login.php'); exit; }


// Query untuk mengambil semua data pengguna
$stmt = $conn->prepare("SELECT * FROM users ORDER BY id DESC");
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
$success = $_GET['success'] ?? '';
$error   = $_GET['error'] ?? '';

// Menampilkan data dalam tabel HTML
// if ($stmt->rowCount() > 0) {
//     echo "<table border='1' cellpadding='10'>";
//     echo "<tr><th>ID</th><th>Email</th><th>Role</th><th>Action</th></tr>";

//     while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
//         echo "<tr>";
//         echo "<td>" . $row['id'] . "</td>";
//         echo "<td>" . $row['email'] . "</td>";
//         echo "<td>" . $row['role'] . "</td>";
//         echo "<td><a href='update_user.php?id=" . $row['id'] . "'>Edit</a> | <a href='delete_user.php?id=" . $row['id'] . "'>Delete</a></td>";
//         echo "</tr>";
//     }

//     echo "</table>";
// } else {
//     echo "Tidak ada data ditemukan";
// }
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data User - Klinik Madani</title>
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
      <h4 class="fw-bold mb-0"><i class="bi bi-person-gear me-2"></i>Data User</h4>
      <small class="text-muted">Total: <?= count($rows) ?> user</small>
    </div>
    <a href="create.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah User</a>
  </div>

  <div class="card border-0 shadow-sm">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-primary text-center">
            <tr><th>No</th><th>Email</th><th>Role</th><th>Aksi</th></tr>
          </thead>
          <tbody>
            <?php if (count($rows) > 0): ?>
              <?php $no = 1; foreach ($rows as $row): ?>
              <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td>
                  <?php
                  $badge = match($row['role']) {
                    'admin'  => 'danger',
                    'dokter' => 'primary',
                    'pasien' => 'success',
                    default  => 'secondary'
                  };
                  ?>
                  <span class="badge bg-<?= $badge ?>"><?= $row['role'] ?></span>
                </td>
                <td class="text-center">
                  <a href="update.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></a>
                  <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus user ini?')"><i class="bi bi-trash"></i></a>
                </td>
              </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="4" class="text-center text-muted py-4">Belum ada data user</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
