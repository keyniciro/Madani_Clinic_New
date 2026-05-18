<?php
require_once '../../checkuser.php';
require_once "../../db.php";

// Query untuk mengambil semua data pengguna
$sql = "SELECT p.*, u.email, u.role FROM pasien p LEFT JOIN users u ON p.user_id = u.id";
$stmt = $conn->prepare($sql);
$stmt->execute();

// Menampilkan data dalam tabel HTML
// if ($stmt->rowCount() > 0) {
//     echo "<table border='1' cellpadding='10'>";
//     echo "<tr><th>Nama</th><th>nik</th><th>Tanggal Lahir</th><th>Jenis Kelamin</th><th>Alamat</th><th>No telpon</th><th>Action</th></tr>";

//     while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
//         echo "<tr>";
//         echo "<td>" . $row['nama'] . "</td>";
//         echo "<td>" . $row['nik'] . "</td>";
//         echo "<td>" . $row['tanggal_lahir'] . "</td>";
//         echo "<td>" . $row['jenis_kelamin'] . "</td>";
//         echo "<td>" . $row['alamat'] . "</td>";
//         echo "<td>" . $row["no_hp"] . "</td>";
//         echo "<td><a href='update_user.php?id=" . $row['id'] . "'>Edit</a> | <a href='delete_user.php?id=" . $row['id'] . "'>Delete</a></td>";
//         echo "</tr>";
//     }

//     echo "</table>";
// } else {
//     echo "Tidak ada data ditemukan";
// }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pasien</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Data Pasien</h2>
            <a href="create.php" class="btn btn-primary">+ Tambah Pasien</a>
        </div>

        <div class="card shadow">
            <div class="card-body">

                <div class="row mb-3">
                    <div class="col-md-4">
                        <input type="text" class="form-control" placeholder="Cari pasien...">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-primary text-center">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>NIK</th>
                                <th>Tanggal Lahir</th>
                                <th>Jenis Kelamin</th>
                                <th>No HP</th>
                                <th>Alamat</th>
                                <th>Email</th>
                                <th width="180">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $no = 1;

                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                ?>

                                <tr>

                                    <td class="text-center"><?= $no++ ?></td>

                                    <td><?= $row['nama'] ?></td>

                                    <td><?= $row['nik'] ?></td>

                                    <td><?= $row['tanggal_lahir'] ?></td>

                                    <td class="text-center">
                                        <?php
                                        if ($row['jenis_kelamin'] == 'L') {
                                            echo "Laki-laki";
                                        } else {
                                            echo "Perempuan";
                                        }
                                        ?>
                                    </td>

                                    <td><?= $row['telepon'] ?></td>

                                    <td><?= $row['alamat'] ?></td>

                                    <td><?= $row['email'] ?></td>

                                    <td class="text-center">

                                        <a href="update.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">
                                            Edit
                                        </a>

                                        <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin hapus data?')">
                                            Hapus
                                        </a>

                                    </td>

                                </tr>

                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>