<?php
require_once '../../checkuser.php';
require_once "../../db.php";

// Query untuk mengambil data dari tabel bibliografi dan join dengan tabel bibliografi_kategori
$sql = "SELECT k.*, p.nama AS nama_pasien, u.email AS email_dokter, d.spesialis
FROM kunjungan k
LEFT JOIN pasien p ON k.pasien_id = p.id
LEFT JOIN dokter d ON k.dokter_id = d.id
LEFT JOIN users u ON d.user_id = u.id";

$stmt = $conn->prepare($sql);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC); // ← ambil semua dulu sebelum $conn di-null

$conn = null; // ← baru null setelah data diambil
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Data Kunjungan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container py-5">

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Data Kunjungan Pasien</h2>
                <p class="text-muted mb-0">Manajemen data kunjungan klinik</p>
            </div>
            <a href="create.php" class="btn btn-primary">+ Tambah Kunjungan</a>
        </div>

        <!-- CARD -->
        <div class="card border-0 shadow-sm">
            <div class="card-body">

                <!-- FILTER -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <input type="text" id="searchInput" class="form-control" placeholder="Cari nama pasien...">
                    </div>
                    <div class="col-md-3">
                        <select id="statusFilter" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="menunggu">Menunggu</option>
                            <option value="diproses">Diproses</option>
                            <option value="selesai">Selesai</option>
                        </select>
                    </div>
                </div>

                <!-- TABLE -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle table-bordered" id="tabelKunjungan">

                        <thead class="table-primary text-center">
                            <tr>
                                <th>No</th>
                                <th>No Antrian</th>
                                <th>Nama Pasien</th>
                                <th>Dokter (Spesialis)</th>
                                <th>Tanggal</th>
                                <th>Keluhan</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (count($rows) > 0): ?>
                                <?php $no = 1;
                                foreach ($rows as $row): ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>

                                        <td class="text-center"><?= htmlspecialchars($row['nomor_antrian']) ?></td>

                                        <td><?= htmlspecialchars($row['nama_pasien']) ?></td>

                                        <td><?= htmlspecialchars($row['spesialis']) ?></td>

                                        <td><?= htmlspecialchars($row['tanggal']) ?></td>

                                        <td><?= htmlspecialchars($row['keluhan']) ?></td>

                                        <td class="text-center">

                                            <?php if ($row['status'] === 'menunggu'): ?>
                                                <a href="update_status.php?id=<?= $row['id'] ?>&status=diproses"
                                                    class="btn btn-info btn-sm"
                                                    onclick="return confirm('Ubah status ke Diproses?')">
                                                    ▶ Proses
                                                </a>

                                            <?php elseif ($row['status'] === 'diproses'): ?>
                                                <a href="update_status.php?id=<?= $row['id'] ?>&status=selesai"
                                                    class="btn btn-success btn-sm"
                                                    onclick="return confirm('Ubah status ke Selesai?')">
                                                    ✔ Selesai
                                                </a>

                                            <?php else: ?>
                                                <span class="text-muted small">Sudah selesai</span>

                                            <?php endif; ?>

                                            <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Yakin hapus data kunjungan ini?')">
                                                Hapus
                                            </a>

                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted">Belum ada data kunjungan</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>

                    </table>
                </div>

            </div>
        </div>

    </div>

    <script>
        // Filter search + status (client-side)
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const rows = document.querySelectorAll('#tabelKunjungan tbody tr');

        function filterTable() {
            const search = searchInput.value.toLowerCase();
            const status = statusFilter.value.toLowerCase();

            rows.forEach(row => {
                const namaPasien = row.cells[2]?.textContent.toLowerCase() ?? '';
                const statusRow = row.cells[6]?.textContent.trim().toLowerCase() ?? '';

                const matchSearch = namaPasien.includes(search);
                const matchStatus = status === '' || statusRow.includes(status);

                row.style.display = (matchSearch && matchStatus) ? '' : 'none';
            });
        }

        searchInput.addEventListener('input', filterTable);
        statusFilter.addEventListener('change', filterTable);
    </script>

</body>

</html>