<?php
require_once '../../../checkuser.php';
require_once "../../../db.php";
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

<form method="POST">
    email: <input type="text" name="email" required><br>
    Password: <input type="password" name="password" required><br>
    Verify Password: <input type="password" name="verify_password" required><br>
    Role: <input type="text" name="role" required><br>
    <input type="submit" value="Tambah User">
</form>

<?php
// }?>