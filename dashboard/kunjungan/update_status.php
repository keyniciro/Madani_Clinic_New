<?php
require_once '../../checkuser.php';
require_once '../../db.php';
if ($_SESSION['role'] !== 'admin') { header('location: /login.php'); exit; }

if (isset($_GET['id'], $_GET['status'])) {
    $id     = $_GET['id'];
    $status = $_GET['status'];
    if (!in_array($status, ['menunggu','diproses','selesai'])) { header('location: index.php'); exit; }

    $stmt = $conn->prepare("UPDATE kunjungan SET status = :status WHERE id = :id");
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    header('location: index.php?success=Status kunjungan berhasil diperbarui'); exit;
} else {
    header('location: index.php'); exit;
}