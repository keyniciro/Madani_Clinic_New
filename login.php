<?php
session_start();
require_once('db.php');

if (isset($_POST['email']) && isset($_POST['password'])) {
  $salt1 = "qm&h*";
  $salt2 = "pg!@";

  $prepared = $conn->prepare("SELECT * FROM users WHERE email=:email AND password=:password");
  $prepared->bindParam(':email', $email);
  $prepared->bindParam(':password', $token);

  try {
    $email = $_POST['email'];
    $pw_temp = $_POST['password'];
    $token = sha1("$salt1$pw_temp$salt2");
    $prepared->execute();
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
  }
  $users = $prepared->fetch(PDO::FETCH_ASSOC);

  print_r($users);
  if ($token == $users['password']) {
    if (isset($_POST['rememberme'])) {
      setcookie('email', $email, time() + 60 * 60 * 24 * 365, '/');
    } else {
      setcookie('email', $un_temp, false, '/');
      setcookie('password', $token, false, '/');
      $_SESSION['email'] = $email;
      $_SESSION['password'] = $token;
    }
    header('location: index.php');
  } else {
    echo "email/password anda salah";
  }
} else {
  ?>

  <!-- <!doctype html>
  <html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Halaman utama</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>

  <h2>User Login</h2>
  <form name="login" method="post" action="login.php">
    email: <input type="text" name="email"><br>
    Password: <input type="password" name="password"><br>
    remember me: <input type="checkbox" name="rememberme" value="1"><br>
    <input type="submit" name="submit" value="login!">
  </form>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
          integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
          crossorigin="anonymous"></script>
  </body>
  </html> -->

  <!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Log In - Madani Klinik</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />

  <link rel="stylesheet" href="css/login.css" />
</head>

<body>

  <div class="auth-card">

    <div class="form-panel">

      <form action="user/insert.php" method="POST">

        <div id="login-form">
          <h5 class="mb-1" style="font-size:17px;font-weight:600;">Log in to your Account</h5>

          <p class="text-muted mb-3" style="font-size:13px;">
            Welcome back, please enter your details.
          </p>

          <button class="btn-google mb-2">
            <img src="https://www.svgrepo.com/show/475656/google-color.svg" width="18" alt="Google" />
            Continue with Google
          </button>

          <div class="divider">OR</div>

          <div class="mb-2">
            <label class="form-label">Email Address*</label>

            <input type="email" class="form-control" placeholder="KenjiTiveston@gmail.com" />
          </div>

          <div class="mb-3">
            <label class="form-label">Password*</label>

            <div class="input-group">
              <input type="password" class="form-control" id="pw3" value="123456789012" />

              <button type="button" class="btn" onclick="togglePw('pw3', this)">
                <i class="bi bi-eye"></i>
              </button>
            </div>
          </div>

          <div class="d-flex justify-content-between align-items-center mb-3">

            <div class="form-check mb-0">
              <input class="form-check-input" type="checkbox" id="remember" />

              <label class="form-check-label" for="remember">
                Remember me
              </label>
            </div>

            <a href="#" class="forgot-link">Forgot Password?</a>

          </div>

          <button class="btn-primary-main">
            <a href="dashboard/admin.html">Log in</a>
          </button>

          <p class="switch-text">
            Don't have an account?
            <a href="dashboard/user/create.php">Sign Up</a>
          </p>

        </div>

      </form>

    </div>

    <div class="brand-panel">

      <div>

        <h2>Log in Form</h2>

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
}
?>