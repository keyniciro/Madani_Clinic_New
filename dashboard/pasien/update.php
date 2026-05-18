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

    // Query untuk mengambil data pasien berdasarkan id
    $sql = "SELECT * FROM pasien WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $pasien = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nama = $_POST['nama'];
        $nik = $_POST['nik'];
        $tanggal_lahir = $_POST['tanggal_lahir'];
        $jenis_kelamin = $_POST['jenis_kelamin'];
        $alamat = $_POST['alamat'];
        $telepon = $_POST['telepon'];

        // Verifikasi apakah password dan konfirmasi password cocok
        // if ($password !== $verify_password) {
        //     echo "Password dan konfirmasi password tidak cocok.";
        //     exit;
        // }

        // Enkripsi password
        // $encrypted_password = encryptPassword($password);

        // Menyiapkan SQL query untuk update pasien
        $sql = "UPDATE pasien SET nama = :nama, nik = :nik,  tanggal_lahir = :tanggal_lahir, jenis_kelamin = :jenis_kelamin, alamat = :alamat, telepon = :telepon WHERE id = :id";
        $stmt = $conn->prepare($sql);

        // Binding parameters
        $stmt->bindParam(':nama', $nama);
        $stmt->bindParam(':nik', $nik);
        $stmt->bindParam(':tanggal_lahir', $tanggal_lahir);
        $stmt->bindParam(':jenis_kelamin', $jenis_kelamin);
        $stmt->bindParam(':alamat', $alamat);
        $stmt->bindParam(':telepon', $telepon);
        $stmt->bindParam(':id', $id);

        // Menjalankan query
        if ($stmt->execute()) {
            echo "Pasien berhasil diperbarui";
        } else {
            echo "Error: Gagal memperbarui pasien.";
        }
    }
}
?>

<form method="POST">
    Nama: <input type="text" name="nama" value="<?php echo $pasien['nama']; ?>" required><br>
    Nik: <input type="text" name="nik" value="<?php echo $pasien['nik']; ?>"required><br>
    Tanggal Lahir: <input type="date" name="tanggal_lahir" value="<?php echo $pasien['tanggal_lahir']; ?>" required><br>
    Jenis Kelamin: <input type="text" name="jenis_kelamin" value="<?php echo $pasien['jenis_kelamin']; ?>" required><br>
    Alamat: <input type="text" name="alamat" value="<?php echo $pasien['alamat']; ?>" required><br>
    Telepon: <input type="text" name="telepon" value="<?php echo $pasien['telepon']; ?>" required><br>
    <input type="submit" value="Update Pasien">
</form>
