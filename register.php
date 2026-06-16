<?php
session_start();
require_once('db.php');

// Kalau udah login langsung redirect
if (isset($_SESSION['email'])) {
    header('location: dashboard/index.php'); exit;
}

$error = $success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email           = trim($_POST['email']);
    $password        = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $nama            = trim($_POST['nama']);
    $nik             = trim($_POST['nik']);
    $tanggal_lahir   = $_POST['tanggal_lahir'];
    $jenis_kelamin   = $_POST['jenis_kelamin'];
    $alamat          = trim($_POST['alamat']);
    $telepon         = trim($_POST['telepon']);

    // Validasi
    if ($password !== $confirm_password) {
        $error = "Password dan konfirmasi password tidak cocok.";
    } elseif (strlen($password) < 6) {
        $error = "Password minimal 6 karakter.";
    } else {
        // Cek email sudah terdaftar
        $cek = $conn->prepare("SELECT id FROM users WHERE email = :email");
        $cek->bindParam(':email', $email);
        $cek->execute();

        if ($cek->fetch()) {
            $error = "Email sudah terdaftar, gunakan email lain.";
        } else {
            // Hash password
            $salt1 = "qm&h*";
            $salt2 = "pg!@";
            $token = sha1("$salt1$password$salt2");

            // Insert ke tabel users
            $stmtU = $conn->prepare("INSERT INTO users (email, password, role) VALUES (:email, :password, 'pasien')");
            $stmtU->bindParam(':email', $email);
            $stmtU->bindParam(':password', $token);

            if ($stmtU->execute()) {
                $user_id = $conn->lastInsertId();

                // Insert ke tabel pasien
                $stmtP = $conn->prepare("INSERT INTO pasien (user_id, nama, nik, tanggal_lahir, jenis_kelamin, alamat, telepon)
                                         VALUES (:user_id, :nama, :nik, :tanggal_lahir, :jenis_kelamin, :alamat, :telepon)");
                $stmtP->bindParam(':user_id', $user_id);
                $stmtP->bindParam(':nama', $nama);
                $stmtP->bindParam(':nik', $nik);
                $stmtP->bindParam(':tanggal_lahir', $tanggal_lahir);
                $stmtP->bindParam(':jenis_kelamin', $jenis_kelamin);
                $stmtP->bindParam(':alamat', $alamat);
                $stmtP->bindParam(':telepon', $telepon);

                if ($stmtP->execute()) {
                    $success = "Akun berhasil dibuat! Silakan login.";
                } else {
                    // Rollback: hapus user yang barusan dibuat kalau insert pasien gagal
                    $conn->prepare("DELETE FROM users WHERE id = :id")->execute([':id' => $user_id]);
                    $error = "Gagal menyimpan data pasien, coba lagi.";
                }
            } else {
                $error = "Gagal membuat akun, coba lagi.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Akun - Klinik Madani</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <style>
    body { min-height: 100vh; background: #f0f4f8; display: flex; align-items: center; justify-content: center; padding: 2rem 0; }
    .register-card { background: white; border-radius: 16px; box-shadow: 0 8px 32px rgba(0,0,0,0.10); width: 100%; max-width: 620px; padding: 2.5rem; }
    .brand-title { color: #0d6efd; font-weight: 700; font-size: 1.4rem; }
  </style>
</head>
<body>

<div class="register-card">
  <div class="text-center mb-4">
    <i class="bi bi-heart-pulse-fill text-primary fs-1"></i>
    <div class="brand-title mt-1">Klinik Madani</div>
    <p class="text-muted mb-0" style="font-size:13px;">Buat akun pasien baru</p>
  </div>

  <?php if ($success): ?>
    <div class="alert alert-success text-center">
      <i class="bi bi-check-circle me-1"></i><?= $success ?>
      <div class="mt-2">
        <a href="login.php" class="btn btn-primary btn-sm">Login Sekarang</a>
      </div>
    </div>
  <?php else: ?>

  <?php if ($error): ?>
    <div class="alert alert-danger"><i class="bi bi-exclamation-circle me-1"></i><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="POST" action="register.php">

    <h6 class="fw-semibold text-muted mb-3"><i class="bi bi-lock me-1"></i>Informasi Akun</h6>
    <div class="row g-3 mb-3">
      <div class="col-12">
        <label class="form-label">Email <span class="text-danger">*</span></label>
        <input type="email" name="email" class="form-control" placeholder="email@example.com"
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Password <span class="text-danger">*</span></label>
        <div class="input-group">
          <input type="password" name="password" class="form-control" id="pw1" placeholder="Min. 6 karakter" required>
          <button type="button" class="btn btn-outline-secondary" onclick="togglePw('pw1',this)">
            <i class="bi bi-eye"></i>
          </button>
        </div>
      </div>
      <div class="col-md-6">
        <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
        <div class="input-group">
          <input type="password" name="confirm_password" class="form-control" id="pw2" placeholder="Ulangi password" required>
          <button type="button" class="btn btn-outline-secondary" onclick="togglePw('pw2',this)">
            <i class="bi bi-eye"></i>
          </button>
        </div>
      </div>
    </div>

    <hr>
    <h6 class="fw-semibold text-muted mb-3"><i class="bi bi-person me-1"></i>Data Diri</h6>
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
        <input type="text" name="nama" class="form-control" placeholder="Nama sesuai KTP"
               value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">NIK <span class="text-danger">*</span></label>
        <input type="text" name="nik" class="form-control" placeholder="16 digit NIK"
               maxlength="16" value="<?= htmlspecialchars($_POST['nik'] ?? '') ?>" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
        <input type="date" name="tanggal_lahir" class="form-control"
               value="<?= htmlspecialchars($_POST['tanggal_lahir'] ?? '') ?>" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
        <select name="jenis_kelamin" class="form-select" required>
          <option value="">-- Pilih --</option>
          <option value="L" <?= ($_POST['jenis_kelamin'] ?? '') === 'L' ? 'selected' : '' ?>>Laki-laki</option>
          <option value="P" <?= ($_POST['jenis_kelamin'] ?? '') === 'P' ? 'selected' : '' ?>>Perempuan</option>
        </select>
      </div>
      <div class="col-12">
        <label class="form-label">Alamat <span class="text-danger">*</span></label>
        <textarea name="alamat" class="form-control" rows="2" placeholder="Alamat lengkap" required><?= htmlspecialchars($_POST['alamat'] ?? '') ?></textarea>
      </div>
      <div class="col-md-6">
        <label class="form-label">No. Telepon <span class="text-danger">*</span></label>
        <input type="text" name="telepon" class="form-control" placeholder="08xxxxxxxxxx"
               value="<?= htmlspecialchars($_POST['telepon'] ?? '') ?>" required>
      </div>
    </div>

    <button type="submit" class="btn btn-primary w-100 py-2 mt-4">
      <i class="bi bi-person-plus me-1"></i>Daftar Sekarang
    </button>

    <p class="text-center text-muted mt-3 mb-0" style="font-size:13px;">
      Sudah punya akun? <a href="login.php">Login di sini</a>
    </p>

  </form>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  function togglePw(id, btn) {
    const input = document.getElementById(id);
    const icon  = btn.querySelector('i');
    if (input.type === 'password') {
      input.type = 'text';
      icon.className = 'bi bi-eye-slash';
    } else {
      input.type = 'password';
      icon.className = 'bi bi-eye';
    }
  }
</script>
</body>
</html>