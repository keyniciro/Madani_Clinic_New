<?php
require_once '../../../checkuser.php';
require_once '../../../db.php';
if ($_SESSION['role'] !== 'admin') { header('location: /login.php'); exit; }

if (!isset($_GET['id'])) { header('location: index.php'); exit; }
$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) { header('location: index.php'); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $role  = $_POST['role'];

    if (!empty($_POST['password'])) {
        if ($_POST['password'] !== $_POST['verify_password']) {
            $error = "Password dan konfirmasi tidak cocok.";
        } else {
            $salt1 = "qm&h*"; $salt2 = "pg!@";
            $pass = sha1($salt1 . $_POST['password'] . $salt2);
            $sql = "UPDATE users SET email=:email, password=:password, role=:role WHERE id=:id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':password', $pass);
        }
    } else {
        $sql = "UPDATE users SET email=:email, role=:role WHERE id=:id";
        $stmt = $conn->prepare($sql);
    }

    if (!$error) {
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':id', $id);
        if ($stmt->execute()) {
            header('location: index.php?success=User berhasil diperbarui'); exit;
        } else {
            $error = "Gagal memperbarui user.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit User - Klinik Madani</title>
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
          <h5 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Edit User</h5>
        </div>
        <div class="card-body p-4">
          <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
          <form method="POST">
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Role</label>
              <select name="role" class="form-select" required>
                <option value="admin"  <?= $user['role']==='admin'  ? 'selected':'' ?>>Admin</option>
                <option value="dokter" <?= $user['role']==='dokter' ? 'selected':'' ?>>Dokter</option>
                <option value="pasien" <?= $user['role']==='pasien' ? 'selected':'' ?>>Pasien</option>
              </select>
            </div>
            <hr>
            <p class="text-muted small">Kosongkan password jika tidak ingin mengubahnya.</p>
            <div class="mb-3">
              <label class="form-label">Password Baru</label>
              <input type="password" name="password" class="form-control">
            </div>
            <div class="mb-4">
              <label class="form-label">Konfirmasi Password</label>
              <input type="password" name="verify_password" class="form-control">
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