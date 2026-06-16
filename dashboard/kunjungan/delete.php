<?php
require_once '../../checkuser.php';
require_once '../../db.php';
if ($_SESSION['role'] !== 'admin') { header('location: /login.php'); exit; }

if (isset($_GET['id'])) {
    $stmt = $conn->prepare("DELETE FROM kunjungan WHERE id = :id");
    $stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
    $stmt->execute();
    header('location: index.php?success=Kunjungan berhasil dihapus'); exit;
} else {
    header('location: index.php'); exit;
}