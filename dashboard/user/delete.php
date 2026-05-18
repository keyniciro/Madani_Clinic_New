<?php
require_once '../../../checkuser.php';
require_once "../../../db.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Menyiapkan SQL query untuk delete user
    $sql = "DELETE FROM user WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    // Menjalankan query
    if ($stmt->execute()) {
        echo "User berhasil dihapus";
    } else {
        echo "Error: Gagal menghapus user.";
    }
}
?>
