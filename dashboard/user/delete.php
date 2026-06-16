<?php
require_once '../../checkuser.php';
require_once "../../db.php";
if ($_SESSION['role'] !== 'admin') { header('location: /login.php'); exit; }

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    if ($stmt->execute()) {
        header('location: index.php?success=User berhasil dihapus'); exit;
    } else {
        header('location: index.php?error=Gagal menghapus user'); exit;
    }
} else {
    header('location: index.php'); exit;
}