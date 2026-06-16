<?php
require_once '../../checkuser.php';
require_once '../../db.php';
$role = $_SESSION['role'];
if (!in_array($role, ['admin', 'dokter'])) { header('location: /login.php'); exit; }

if (isset($_GET['id'])) {
    $stmt = $conn->prepare("DELETE FROM rekam_medis WHERE id = :id");
    $stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
    $stmt->execute();
    header('location: index.php?success=Rekam medis berhasil dihapus'); exit;
} else {
    header('location: index.php'); exit;
}