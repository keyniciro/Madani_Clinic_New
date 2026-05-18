<?php
require_once '../../../checkuser.php';
require_once "../../../db.php";

// Fungsi untuk mengenkripsi password dengan SHA1 dan salt
function encryptPassword($password) {
    $salt1 = "qm&h*";
    $salt2 = "pg!@";
    return sha1($salt1 . $password . $salt2);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk mengambil data user berdasarkan id
    $sql = "SELECT * FROM user WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
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

        // Menyiapkan SQL query untuk update user
        $sql = "UPDATE user SET username = :username, password = :password, role = :role WHERE id = :id";
        $stmt = $conn->prepare($sql);

        // Binding parameters
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $encrypted_password);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':id', $id);

        // Menjalankan query
        if ($stmt->execute()) {
            echo "User berhasil diperbarui";
        } else {
            echo "Error: Gagal memperbarui user.";
        }
    }
}
?>

<form method="POST">
    Username: <input type="text" name="username" value="<?php echo $user['username']; ?>" required><br>
    Password: <input type="password" name="password" required><br>
    Verify Password: <input type="password" name="verify_password" required><br>
    Role: <input type="text" name="role" value="<?php echo $user['role']; ?>" required><br>
    <input type="submit" value="Update User">
</form>
