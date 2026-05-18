<?php
require_once '../../../checkuser.php';
require_once "../../../db.php";

// Query untuk mengambil semua data pengguna
$sql = "SELECT * FROM users";
$stmt = $conn->prepare($sql);
$stmt->execute();

// Menampilkan data dalam tabel HTML
if ($stmt->rowCount() > 0) {
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>ID</th><th>Email</th><th>Role</th><th>Action</th></tr>";

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['email'] . "</td>";
        echo "<td>" . $row['role'] . "</td>";
        echo "<td><a href='update_user.php?id=" . $row['id'] . "'>Edit</a> | <a href='delete_user.php?id=" . $row['id'] . "'>Delete</a></td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "Tidak ada data ditemukan";
}
?>
