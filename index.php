<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <title>Madani Clinic</title>
</head>

<script type="application/javascript">
    // document.cookie = "tesjs=John Doe";
    // alert(document.cookie);
</script>
<body>

<div class="container">
    <header class="blog-header py-3">
        <div class="row flex-nowrap justify-content-between align-items-center">
            <div class="col-4 pt-1">
            </div>
            <div class="col-4 text-center">
                <a class="blog-header-logo text-dark" href="#">Madani Clinic</a>
            </div>
            <div class="col-4 d-flex justify-content-end align-items-center">
                <?php
                if (!isset($_SESSION['email'])) {
                    ?>
                    <a href="login.php">
                        <button type="button" class="btn btn-primary">Login</button>
                    </a>
                    <?php
                } else {
                    echo "<p>Halo, " . $_SESSION['email'] . "</p>";
                    ?>
                    <a href="logout.php">
                        <button type="button" class="btn btn-primary">Logout</button>
                    </a>
                    <?php
                }
                ?>
            </div>
        </div>
    </header>

    <div class="nav-scroller py-1 mb-2">
        <nav class="nav d-flex justify-content-between">
            <a class="p-2 text-muted" href="pasien\index.php">Pasien</a>
            <a class="p-2 text-muted" href="dokter\index.php">Dokter</a>
            <a class="p-2 text-muted" href="jadwal_dokter\index.php">Jadwal Dokter</a>
            <a class="p-2 text-muted" href="kunjungan\index.php">Kunjungan</a>
            <a class="p-2 text-muted" href="rekam_medis\index.php">Rekam medis</a>
            <a class="p-2 text-muted" href="obat\index.php">obat</a>
            <a class="p-2 text-muted" href="resep\index.php">resep</a>
            <a class="p-2 text-muted" href="tagihan\index.php">Tagihan</a>
        </nav>
    </div>

    <div class="row mb-2">
        <div class="col-md-12">
            <div class="row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
                <div class="col p-4 d-flex flex-column position-static">
                    <strong class="d-inline-block mb-2 text-primary">Selamat datang di Clinic Madani</strong>
                    <h3 class="mb-0">Featured post</h3>
                    <div class="mb-1 text-muted">Nov 12</div>
                    <p class="card-text mb-auto">This is a wider card with supporting text below as a natural lead-in to
                        additional content.</p>
                    <a href="#" class="stretched-link">Continue reading</a>
                </div>

            </div>
        </div>
    </div>
    <footer class="blog-footer">
        <p>Template built for <a href="https://getbootstrap.com/">Bootstrap</a> by <a href="https://twitter.com/mdo">@mdo</a>.
        </p>
        <p>
            <a href="#">Back to top</a>
        </p>
    </footer>
</div>
</html>
