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
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Madani Clinic</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  </head>

  <body class="bg-light">

    <div class="container d-flex justify-content-center align-items-center vh-100">
      <div class="card shadow p-4" style="width: 400px;">
        <h3 class="text-center mb-4">Madani Clinic</h3>

        <form name="login" method="post" action="login.php">
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="text" name="email" class="form-control" placeholder="Masukkan email">
          </div>

          <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Masukkan password">
          </div>

          <div class="mb-3">
            <label class="form-label">Remember me</label>
            <input type="checkbox" value="1">
          </div>

          <input type="submit" name="submit" value="login!" class="btn btn-primary w-100">

          <!-- <button type="submit" name="submit" value="login!" class="btn btn-primary w-100">Login</button> -->
        </form>
      </div>
    </div>

  </body>

  </html>
  <?php
}
?>