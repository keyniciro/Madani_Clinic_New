<?php
require_once '../../../checkuser.php';
require_once "../../../db.php";

// Fungsi untuk mengenkripsi password dengan SHA1 dan salt
function encryptPassword($password) {
    $salt1 = "qm&h*";
    $salt2 = "pg!@";
    return sha1($salt1 . $password . $salt2);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $spesialis = $_POST['spesialis'];
    $telepon = $_POST['telepon'];

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
        $id_terakhir = $conn->lastInsertId();
        // Menyiapkan SQL query untuk menambahkan dokter
        $sql = "INSERT INTO dokter (nama, spesialis, telepon, user_id) 
                VALUES (:nama, :spesialis, :telepon, $id_terakhir)";
        $stmt1 = $conn->prepare($sql);

        // Binding parameters
        $stmt1->bindParam(':nama', $nama);
        $stmt1->bindParam(':spesialis', $spesialis);
        $stmt1->bindParam(':telepon', $telepon);
        if ($stmt1->execute()) {
            echo "Dokter berhasil ditambahkan";
        }else{
            echo "Error: Gagal menambahkan Dokter.";
        }
    } else {
        echo "Error: Gagal menambahkan user.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Dokter</title>

    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5 mb-5">
  <div class="card shadow">

    <div class="card-header bg-primary text-white">
      <h4 class="mb-0">Tambah Dokter</h4>
    </div>

    <div class="card-body">

      <form action="" method="POST">

        <!-- DATA Dokter -->
        <h5 class="mb-3">Data Dokter</h5>

        <div class="row">

          <div class="col-md-6 mb-3">
            <label class="form-label">Nama Dokter</label>
            <input 
              type="text" 
              name="nama" 
              class="form-control"
              placeholder="Masukkan nama dokter"
              required
            >
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label">SPESIALIS</label>
            <input 
              type="text" 
              name="spesialis" 
              class="form-control"
              placeholder="Masukkan spesialisasi anda"
              required
            >
          </div>

        </div>

        <!-- <div class="row">

          <div class="col-md-6 mb-3">
            <label class="form-label">Tanggal Lahir</label>
            <input 
              type="date" 
              name="tanggal_lahir" 
              class="form-control"
              required
            >
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label">Jenis Kelamin</label>

            <select 
              name="jenis_kelamin" 
              class="form-select"
              required
            >
              <option value="">-- Pilih Jenis Kelamin --</option>
              <option value="L">Laki-laki</option>
              <option value="P">Perempuan</option>
            </select>
          </div>

        </div> -->

        <!-- <div class="mb-3">
          <label class="form-label">Alamat</label>

          <textarea 
            name="alamat" 
            class="form-control"
            rows="3"
            placeholder="Masukkan alamat dokter"
            required
          ></textarea>
        </div> -->

        <div class="mb-4">
          <label class="form-label">No Telepon</label>

          <input 
            type="text" 
            name="telepon" 
            class="form-control"
            placeholder="Masukkan nomor telepon"
            required
          >
        </div>

        <hr>

        <!-- DATA LOGIN -->
        <h5 class="mb-3">Data Login</h5>

        <div class="row">

          <div class="col-md-6 mb-3">
            <label class="form-label">Email</label>

            <input 
              type="email" 
              name="email" 
              class="form-control"
              placeholder="Masukkan email"
              required
            >
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label">Role</label>

            <select 
              name="role" 
              class="form-select"
              required
            >
              <option value="">-- Pilih Role --</option>
              <option value="dokter">Dokter</option>
              <option value="admin">Admin</option>
              <option value="pasien">Pasien</option>
            </select>
          </div>

        </div>

        <div class="row">

          <div class="col-md-6 mb-3">
            <label class="form-label">Password</label>

            <input 
              type="password" 
              name="password" 
              class="form-control"
              placeholder="Masukkan password"
              required
            >
          </div>

          <div class="col-md-6 mb-4">
            <label class="form-label">Verify Password</label>

            <input 
              type="password" 
              name="verify_password" 
              class="form-control"
              placeholder="Ulangi password"
              required
            >
          </div>

        </div>

        <!-- BUTTON -->
        <div class="d-flex gap-2">

          <button 
            type="submit" 
            class="btn btn-primary"
          >
            Simpan
          </button>

          <a 
            href="index.php" 
            class="btn btn-secondary"
          >
            Kembali
          </a>

        </div>

      </form>

    </div>
  </div>
</div>

</body>

</html>
<!-- <form method="POST">
    Nama: <input type="text" name="nama" required><br>
    Nik: <input type="text" name="nik" required><br>
    Tanggal Lahir: <input type="date" name="tanggal_lahir"><br>
    Jenis Kelamin: <input type="text" name="jenis_kelamin" required><br>
    Alamat: <input type="text" name="alamat" required><br>
    Telepon: <input type="text" name="telepon" required><br>
    Email: <input type="text" name="email" required><br>
    Password: <input type="password" name="password" required><br>
    Verify Password: <input type="password" name="verify_password" required><br>
    Role: <input type="dropdown" name="role" required><br>
    <input type="hidden" name="role" value="dokter"><br>
    <input type="submit" value="Tambah User">
</form> -->
