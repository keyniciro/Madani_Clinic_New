<?php
require_once '../../checkuser.php';
require_once "../../db.php";
// if ($_SESSION["role"] != "admin") {
//     echo"Akses ditolak, Anda bukan admin";
// }else{

// Fungsi untuk mengenkripsi password dengan SHA1 dan salt
function encryptPassword($password) {
    $salt1 = "qm&h*";
    $salt2 = "pg!@";
    return sha1($salt1 . $password . $salt2);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $verify_password = $_POST['verify_password'];
    $role = $_POST['role'];

    // Verifikasi apakah password dan konfirmasi password cocok
    if ($password !== $verify_password) {
        echo "Password dan konfirmasi password tidak cocok.";
        exit;
    }

    // Enkripsi password
    $encrypted_password = encryptPassword($password);

    // Menyiapkan SQL query untuk menambahkan user
    $sql = "INSERT INTO users (email, password, role) VALUES (:email, :password, :role)";
    $stmt = $conn->prepare($sql);

    // Binding parameters
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $encrypted_password);
    $stmt->bindParam(':role', $role);

    // Menjalankan query
    if ($stmt->execute()) {
        echo "User berhasil ditambahkan";
    } else {
        echo "Error: Gagal menambahkan user.";
    }
}

?>

<!-- <form method="POST">
    email: <input type="text" name="email" required><br>
    Password: <input type="password" name="password" required><br>
    Verify Password: <input type="password" name="verify_password" required><br>
    Role: <input type="text" name="role" required><br>
    <input type="submit" value="Tambah User">
</form> -->
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register - Madani Klinik</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />

  <link rel="stylesheet" href="../../css/login.css" />
</head>

<body>

  <div class="auth-card">

    <div class="form-panel">

      <form action="user/insert.php" method="POST">

        <div id="signup-form">

          <h5 class="mb-1" style="font-size:17px;font-weight:600;">
            Create an Account
          </h5>

          <p class="text-muted mb-3" style="font-size:13px;">
            Signup now to get started on an account.
          </p>

          <button class="btn-google mb-2">

            <img src="https://www.svgrepo.com/show/475656/google-color.svg" width="18" alt="Google" />

            Sign up with Google

          </button>

          <div class="divider">OR</div>

          <!-- <div class="mb-2">
            <label class="form-label">Full Name*</label>

            <input type="text" class="form-control" placeholder="Khen Dzaky Amalin" />
          </div> -->

          <div class="mb-2">
            <label class="form-label">Email Address*</label>

            <input type="email" class="form-control" placeholder="email@example.com" />
          </div>

          <div class="mb-2">
            <label class="form-label">Password*</label>

            <div class="input-group">

              <input type="password" class="form-control" id="pw1" placeholder="••••••••" />

              <button type="button" class="btn" onclick="togglePw('pw1', this)">
                <i class="bi bi-eye"></i>
              </button>

            </div>
          </div>

          <div class="mb-2">
            <label class="form-label">Confirm Password*</label>

            <div class="input-group">

              <input type="password" class="form-control" id="pw2" placeholder="••••••••" />

              <button type="button" class="btn" onclick="togglePw('pw2', this)">
                <i class="bi bi-eye"></i>
              </button>

            </div>
          </div>

          <div class="form-check mb-3 mt-2">

            <input class="form-check-input" type="checkbox" id="terms" />

            <label class="form-check-label" for="terms">
              I have read and agree to the
              <a href="#">Terms of Service</a>
            </label>

          </div>

          <button class="btn-primary-main">Get Started</button>

          <p class="switch-text">
            Already have an account?
            <a href="../../login.php">Log in</a>
          </p>

        </div>

      </form>

    </div>

    <div class="brand-panel">

      <div>

        <h2>Sign Up Form</h2>

        <p>by Madani Klinik</p>

        <div class="d-flex flex-column gap-2">

          <div class="social-chip">
            <i class="bi bi-google"></i>
            Google
          </div>

          <div class="social-chip">
            <i class="bi bi-instagram"></i>
            Instagram
          </div>

        </div>

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

<?php
// }?>