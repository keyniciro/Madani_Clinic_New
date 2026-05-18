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

    // Query untuk mengambil data dokter berdasarkan id
    $sql = "SELECT * FROM dokter WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $dokter = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nama = $_POST['nama'];
        $spesialis = $_POST['spesialis'];
        $telepon = $_POST['telepon'];

        // Verifikasi apakah password dan konfirmasi password cocok
        // if ($password !== $verify_password) {
        //     echo "Password dan konfirmasi password tidak cocok.";
        //     exit;
        // }

        // Enkripsi password
        // $encrypted_password = encryptPassword($password);

        // Menyiapkan SQL query untuk update dokter
        $sql = "UPDATE dokter SET nama = :nama, spesialis = :spesialis, telepon = :telepon WHERE id = :id";
        $stmt = $conn->prepare($sql);

        // Binding parameters
        $stmt->bindParam(':nama', $nama);
        $stmt->bindParam(':spesialis', $spesialis);
        $stmt->bindParam(':telepon', $telepon);
        $stmt->bindParam(':id', $id);

        // Menjalankan query
        if ($stmt->execute()) {
            echo "Dokter berhasil diperbarui";
        } else {
            echo "Error: Gagal memperbarui dokter.";
        }
    }
}
?>

<form method="POST">
    Nama: <input type="text" name="nama" value="<?php echo $dokter['nama']; ?>" required><br>
    Spesialis: <input type="text" name="spesialis" value="<?php echo $dokter['spesialis']; ?>"required><br>
    Telepon: <input type="text" name="telepon" value="<?php echo $dokter['telepon']; ?>" required><br>
    <input type="submit" value="Update Dokter">
</form>
