<?php
session_start();
require_once('db.php');

if (isset($_POST['email']) && isset($_POST['password'])) {
  $salt1 = "qm&h*";
  $salt2 = "pg!@";

  $email = $_POST['email'];
  $pw_temp = $_POST['password'];
  $token = sha1("$salt1$pw_temp$salt2");

  $prepared = $conn->prepare("SELECT * FROM users WHERE email=:email AND password=:password");
  $prepared->bindParam(':email', $email);
  $prepared->bindParam(':password', $token);

  try {
    $prepared->execute();
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
  }

  $users = $prepared->fetch(PDO::FETCH_ASSOC);

  if ($users && $token == $users['password']) {
    // Set session
    $_SESSION['email'] = $email;
    $_SESSION['password'] = $token;
    $_SESSION['role'] = $users['role']; // FIX 1: role sekarang tersimpan

    // Remember me
    if (isset($_POST['rememberme'])) {
      setcookie('email', $email, time() + 60 * 60 * 24 * 365, '/'); // FIX 2: logic remember me dibalik
    } else {
      setcookie('email', $email, false, '/'); // FIX 3: $un_temp → $email
    }

    // Redirect berdasarkan role
    $dashLink = match($users['role']) {
      'admin'  => 'dashboard/pasien/index.php',
      'dokter' => 'dashboard/dokter/index.php',
      'pasien' => 'portal/index.php',
      default  => 'dashboard/index.php'
    };
    header('location: ' . $dashLink);
    exit;
  } else {
    $error = "Email atau password Anda salah.";
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Klinik Madani</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/login.css">
</head>
<body>

<div class="auth-card">

  <div class="form-panel">
    <form method="post" action="login.php">

      <div id="login-form">
        <h5 class="mb-1" style="font-size:17px;font-weight:600;">Masuk ke Akun Anda</h5>
        <p class="text-muted mb-3" style="font-size:13px;">Selamat datang kembali, silakan masukkan detail Anda.</p>

        <?php if (isset($error)): ?>
          <div class="alert alert-danger py-2" style="font-size:13px;"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="mb-2">
          <label class="form-label">Email Address*</label>
          <input type="email" class="form-control" name="email" placeholder="email@example.com"
                 value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Password*</label>
          <div class="input-group">
            <input type="password" class="form-control" id="pw1" name="password" required>
            <button type="button" class="btn btn-outline-secondary" onclick="togglePw('pw1', this)">
              <i class="bi bi-eye"></i>
            </button>
          </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
          <div class="form-check mb-0">
            <input class="form-check-input" type="checkbox" name="rememberme" id="remember" value="1">
            <label class="form-check-label" for="remember">Ingat saya</label>
          </div>
          <a href="#" class="forgot-link">Lupa Password?</a>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2">Login</button>

        <p class="switch-text mt-3 text-center" style="font-size:13px;">
          Belum punya akun? <a href="dashboard/user/create.php">Daftar</a>
        </p>
      </div>

    </form>
  </div>

  <div class="brand-panel">
    <div>
      <h2>Klinik Madani</h2>
      <p>Siap Melayani Sepenuh Hati</p>
    </div>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  function togglePw(id, btn) {
    const input = document.getElementById(id);
    const icon = btn.querySelector('i');
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