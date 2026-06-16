<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Klinik Madani — Siap Melayani Sepenuh Hati</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/landing.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg">
  <div class="container">
    <a class="navbar-brand" href="index.php">
      <i class="bi bi-heart-pulse-fill me-2" style="color:var(--accent)"></i>Klinik Madani
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav mx-auto">
        <li class="nav-item"><a class="nav-link" href="#tentang">Tentang</a></li>
        <li class="nav-item"><a class="nav-link" href="#layanan">Layanan</a></li>
        <li class="nav-item"><a class="nav-link" href="#fasilitas">Fasilitas</a></li>
        <li class="nav-item"><a class="nav-link" href="#berita">Berita</a></li>
        <li class="nav-item"><a class="nav-link" href="#lokasi">Lokasi</a></li>
      </ul>
      <div class="d-flex gap-2">
        <?php if (isset($_SESSION['email'])): ?>
          <?php
            $dashLink = match($_SESSION['role'] ?? '') {
              'admin'  => 'dashboard/pasien/index.php',
              'dokter' => 'dashboard/dokter/index.php',
              'pasien' => 'portal/index.php',
              default  => 'login.php'
            };
          ?>
          <a href="<?= $dashLink ?>" class="btn-login btn">Dashboard</a>
        <?php else: ?>
          <a href="login.php" class="btn-login btn">Login</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>

<!-- HERO -->
<section class="hero">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6 hero-content">
        <div class="hero-badge fade-up"><i class="bi bi-shield-check me-1"></i> Terpercaya sejak 2010</div>
        <h1 class="fade-up-delay-1">Siap Melayani<br>Sepenuh Hati<br><span style="color:#7dd3fc">Klinik Madani</span></h1>
        <p class="fade-up-delay-2">Memberikan pelayanan kesehatan terbaik dengan tenaga medis profesional dan fasilitas modern untuk keluarga Indonesia.</p>
        <div class="d-flex gap-3 flex-wrap fade-up-delay-3">
          <a href="login.php" class="btn-hero-primary"><i class="bi bi-calendar-check me-2"></i>Daftar Sekarang</a>
          <a href="#layanan" class="btn-hero-outline"><i class="bi bi-play-circle me-2"></i>Lihat Layanan</a>
        </div>
        <div class="hero-stats fade-up-delay-3">
          <div class="stat-item"><span class="number">15+</span><span class="label">Dokter Spesialis</span></div>
          <div class="stat-item"><span class="number">10K+</span><span class="label">Pasien Terlayani</span></div>
          <div class="stat-item"><span class="number">24/7</span><span class="label">Siap Melayani</span></div>
        </div>
      </div>
      <div class="col-lg-6 hero-image-wrap d-none d-lg-block">
        <div class="hero-card fade-up-delay-1">
          <div class="icon"><i class="bi bi-activity"></i></div>
          <div style="font-weight:600;margin-bottom:0.3rem">IGD 24 Jam</div>
          <div style="font-size:0.85rem;color:rgba(255,255,255,0.7)">Siap tangani kedaruratan kapan saja</div>
        </div>
        <div class="row g-3">
          <div class="col-6">
            <div class="hero-card">
              <div class="icon"><i class="bi bi-capsule"></i></div>
              <div style="font-weight:600;margin-bottom:0.3rem">Apotek</div>
              <div style="font-size:0.82rem;color:rgba(255,255,255,0.7)">Obat lengkap tersedia</div>
            </div>
          </div>
          <div class="col-6">
            <div class="hero-card">
              <div class="icon"><i class="bi bi-microscope"></i></div>
              <div style="font-weight:600;margin-bottom:0.3rem">Laboratorium</div>
              <div style="font-size:0.82rem;color:rgba(255,255,255,0.7)">Hasil akurat & cepat</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- TENTANG -->
<section class="about" id="tentang">
  <div class="container">
    <div class="row g-5 align-items-center">
      <div class="col-lg-5">
        <span class="section-tag">Mengenal Kami</span>
        <h2 class="section-title">Mengenal Klinik Madani</h2>
        <p class="section-sub">Klinik Madani hadir untuk memberikan pelayanan kesehatan yang berkualitas, terjangkau, dan mudah diakses oleh seluruh lapisan masyarakat dengan standar pelayanan tinggi.</p>
      </div>
      <div class="col-lg-7">
        <div class="row g-3">
          <div class="col-md-6">
            <div class="about-visi-card">
              <h5><i class="bi bi-eye me-2"></i>Visi</h5>
              <p style="color:var(--muted);font-size:0.9rem;line-height:1.7">Menjadi klinik pilihan utama yang memberikan layanan kesehatan komprehensif, berkualitas, dan terpercaya.</p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="about-visi-card" style="border-left-color:var(--accent)">
              <h5 style="color:var(--accent)"><i class="bi bi-bullseye me-2"></i>Tujuan</h5>
              <p style="color:var(--muted);font-size:0.9rem;line-height:1.7">Mewujudkan masyarakat sehat melalui pelayanan promotif, preventif, kuratif, dan rehabilitatif.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- LAYANAN -->
<section class="layanan" id="layanan">
  <div class="container">
    <div class="text-center mb-5">
      <span class="section-tag">Apa yang Kami Tawarkan</span>
      <h2 class="section-title">Layanan Kesehatan Kami</h2>
    </div>
    <div class="row g-4">
      <?php
      $layanan = [
        ['bi-activity','IGD 24 Jam','Penanganan darurat siap 24 jam sehari, 7 hari seminggu.'],
        ['bi-person-badge','Poli Umum','Konsultasi dokter umum untuk keluhan kesehatan sehari-hari.'],
        ['bi-microscope','Laboratorium','Pemeriksaan lab dengan alat modern dan hasil akurat.'],
        ['bi-capsule','Apotek','Ketersediaan obat lengkap dengan harga terjangkau.'],
        ['bi-person-hearts','Dokter Spesialis','Konsultasi dengan dokter spesialis berpengalaman.'],
        ['bi-clipboard2-pulse','Rekam Medis','Pengelolaan rekam medis digital terintegrasi.'],
      ];
      foreach ($layanan as $l): ?>
      <div class="col-md-4 col-sm-6">
        <div class="layanan-card">
          <div class="layanan-icon"><i class="bi <?= $l[0] ?>"></i></div>
          <h6><?= $l[1] ?></h6>
          <p><?= $l[2] ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- FASILITAS -->
<section id="fasilitas" style="background:#fff">
  <div class="container">
    <div class="text-center mb-5">
      <span class="section-tag">Fasilitas Kami</span>
      <h2 class="section-title">Fasilitas Penunjang</h2>
    </div>
    <div class="row g-3">
      <?php
      $fasilitas = [
        ['bi-building','Rawat Inap','Kamar nyaman dengan peralatan medis lengkap.'],
        ['bi-shield-plus','R. Persalinan','Ruang bersalin modern dan steril.'],
        ['bi-book-half','Mushola','Tempat ibadah tersedia untuk pasien & keluarga.'],
        ['bi-door-open','Kamar Mandi','Fasilitas sanitasi bersih dan terawat.'],
        ['bi-car-front','Ambulans','Armada ambulans siap 24 jam.'],
        ['bi-people','Ruang Tunggu','Ruang tunggu nyaman ber-AC.'],
      ];
      foreach ($fasilitas as $f): ?>
      <div class="col-md-4 col-sm-6">
        <div class="fasilitas-card">
          <div class="fasilitas-icon"><i class="bi <?= $f[0] ?>"></i></div>
          <div>
            <h6><?= $f[1] ?></h6>
            <p><?= $f[2] ?></p>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- BERITA -->
<section class="berita" id="berita">
  <div class="container">
    <div class="d-flex justify-content-between align-items-end mb-5">
      <div>
        <span class="section-tag">Update Terbaru</span>
        <h2 class="section-title mb-0">Berita & Informasi</h2>
      </div>
      <a href="#" style="color:var(--primary);font-size:0.9rem;font-weight:600;text-decoration:none">Lihat semua <i class="bi bi-arrow-right"></i></a>
    </div>
    <div class="row g-4">
      <?php
      $berita = [
        ['🏥','Kesehatan','Pentingnya Pemeriksaan Rutin','Deteksi dini penyakit melalui medical check-up rutin.','12 Mei 2026'],
        ['💉','Imunisasi','Jadwal Imunisasi Anak 2026','Informasi terbaru jadwal imunisasi lengkap untuk balita.','10 Mei 2026'],
        ['🌿','Tips Sehat','Pola Hidup Sehat di Musim Hujan','Tips menjaga kesehatan saat musim hujan tiba.','8 Mei 2026'],
      ];
      foreach ($berita as $b): ?>
      <div class="col-md-4">
        <div class="berita-card">
          <div class="berita-img"><?= $b[0] ?></div>
          <div class="berita-body">
            <span class="berita-tag"><?= $b[1] ?></span>
            <h6><?= $b[2] ?></h6>
            <p><?= $b[3] ?></p>
            <div style="font-size:0.8rem;color:var(--muted)"><i class="bi bi-calendar3 me-1"></i><?= $b[4] ?></div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="cta-section py-5">
  <div class="container text-center py-3">
    <h2 class="mb-3">Butuh Konsultasi Sekarang?</h2>
    <p class="mb-4">Daftar online mudah dan cepat. Tim kami siap membantu Anda.</p>
    <div class="d-flex justify-content-center gap-3 flex-wrap">
      <a href="login.php" class="btn btn-light fw-600 rounded-pill px-4 py-2" style="color:var(--primary);font-weight:600">
        <i class="bi bi-calendar-plus me-2"></i>Daftar Online
      </a>
      <a href="tel:+62812345678" class="btn btn-outline-light rounded-pill px-4 py-2">
        <i class="bi bi-telephone me-2"></i>Hubungi Kami
      </a>
    </div>
  </div>
</section>

<!-- LOKASI -->
<section class="map-section" id="lokasi">
  <div class="container">
    <div class="text-center mb-5">
      <span class="section-tag">Temukan Kami</span>
      <h2 class="section-title">Lokasi Klinik</h2>
    </div>
    <div class="row g-4 align-items-stretch">
      <div class="col-lg-4">
        <div class="info-klinik">
          <h5><i class="bi bi-geo-alt me-2"></i>Info Klinik Madani</h5>
          <div class="info-item">
            <div class="icon"><i class="bi bi-geo-alt"></i></div>
            <div class="text"><span class="label">Alamat</span>Jl. Contoh No.21, Kecamatan X, Kota Y</div>
          </div>
          <div class="info-item">
            <div class="icon"><i class="bi bi-clock"></i></div>
            <div class="text"><span class="label">Jam Operasional</span>Senin–Sabtu: 08.00–20.00<br>Minggu: 08.00–14.00</div>
          </div>
          <div class="info-item">
            <div class="icon"><i class="bi bi-telephone"></i></div>
            <div class="text"><span class="label">Telepon</span>0812-3456-789</div>
          </div>
          <div class="info-item">
            <div class="icon"><i class="bi bi-whatsapp"></i></div>
            <div class="text"><span class="label">WhatsApp</span>0812-3456-789</div>
          </div>
        </div>
      </div>
      <div class="col-lg-8">
        <div class="map-wrap" style="height:350px">
          <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d253840.65271040477!2d106.68943232441393!3d-6.229386547919444!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f3e945e34b9d%3A0x5371bf0fdad786a2!2sJakarta!5e0!3m2!1sid!2sid!4v1716000000000!5m2!1sid!2sid"
            width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer>
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-4">
        <div class="footer-brand"><i class="bi bi-heart-pulse-fill me-2" style="color:var(--accent)"></i>Klinik Madani</div>
        <p style="font-size:0.88rem;line-height:1.7">Memberikan pelayanan kesehatan terbaik dengan penuh rasa tanggung jawab dan ketulusan hati.</p>
      </div>
      <div class="col-lg-2 col-6">
        <h6>Layanan</h6>
        <a href="#">IGD 24 Jam</a>
        <a href="#">Poli Umum</a>
        <a href="#">Laboratorium</a>
        <a href="#">Apotek</a>
      </div>
      <div class="col-lg-2 col-6">
        <h6>Informasi</h6>
        <a href="#">Tentang Kami</a>
        <a href="#">Berita</a>
        <a href="#">Karir</a>
        <a href="#">Kontak</a>
      </div>
      <div class="col-lg-4">
        <h6>Hubungi Kami</h6>
        <a href="#"><i class="bi bi-telephone me-2"></i>0812-3456-789</a>
        <a href="#"><i class="bi bi-whatsapp me-2"></i>0812-3456-789</a>
        <a href="#"><i class="bi bi-envelope me-2"></i>info@klinikmadani.id</a>
        <a href="#"><i class="bi bi-instagram me-2"></i>@klinikmadani</a>
      </div>
    </div>
    <div class="footer-bottom d-flex justify-content-between flex-wrap gap-2">
      <span>&copy; 2026 Klinik Madani. All rights reserved.</span>
      <span>Made with <i class="bi bi-heart-fill" style="color:#e74c3c"></i> for health</span>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
