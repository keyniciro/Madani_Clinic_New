<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('location: /login.php'); exit;
}
$role = $_SESSION['role'];
if ($role === 'admin') {
    header('location: /dashboard/pasien/index.php'); exit;
} elseif ($role === 'dokter') {
    header('location: /dashboard/kunjungan/index.php'); exit;
} elseif ($role === 'pasien') {
    header('location: /portal/index.php'); exit;
} else {
    header('location: /login.php'); exit;
}