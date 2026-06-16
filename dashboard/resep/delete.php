<?php
require_once '../../checkuser.php';
require_once '../../db.php';
$role = $_SESSION['role'];
if (!in_array($role, ['admin', 'dokter'])) { header('location: /login.php'); exit; }

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Hapus detail dulu baru resepnya
    $conn->prepare("DELETE FROM resep_detail WHERE resep_id = :id")->execute([':id' => $id]);
    $stmt = $conn->prepare("DELETE FROM resep WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    if ($stmt->execute()) {
        header('location: index.php?success=Resep berhasil dihapus'); exit;
    } else {
        header('location: index.php?error=Gagal menghapus resep'); exit;
    }
} else {
    header('location: index.php'); exit;
}