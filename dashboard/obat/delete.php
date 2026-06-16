<?php
require_once '../../../checkuser.php';
require_once '../../../db.php';
if ($_SESSION['role'] !== 'admin') { header('location: /login.php'); exit; }

if (isset($_GET['id'])) {
    $stmt = $conn->prepare("DELETE FROM obat WHERE id = :id");
    $stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
    if ($stmt->execute()) {
        header('location: index.php?success=Obat berhasil dihapus'); exit;
    } else {
        header('location: index.php?error=Gagal menghapus obat'); exit;
    }
} else {
    header('location: index.php'); exit;
}