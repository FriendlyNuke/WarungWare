<?php
// mulai sesi
session_start();

// jika belum login, arahkan ke login.php
if (!isset($_SESSION['username'])||(!isset($_SESSION['user_id']))) {
    header("Location: login.php");
    exit;
}

// koneksi database
include 'config/database.php';
$query_stok = mysqli_query($conn, "SELECT nama_produk FROM produk WHERE stok <= 0");
$produk_habis = [];
while ($row = mysqli_fetch_assoc($query_stok)) {
    $produk_habis[] = $row['nama_produk'];
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - Aplikasi Penjualan Warung</title>

  <!-- Bootstrap & Font -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold" href="">🏪 WarungWare</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="pages/produk.php">Produk</a></li>
        <li class="nav-item"><a class="nav-link" href="pages/transaksi.php">Transaksi</a></li>
        <li class="nav-item"><a class="nav-link" href="pages/laporan.php">Laporan</a></li>
        <li class="nav-item"><a class="nav-link" href="/penjualan/pages/histori.php">Histori</a></li>

        <li class="nav-item"><a href="logout.php" class="btn btn-danger w-100">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- CONTENT -->
<div class="container py-5">
  <div class="text-center mb-5">
    <h2 class="fw-bold text-success">Selamat Datang di WarungWare</h2>
    <p class="lead">Halo 👋 ,  
    Pilih menu di bawah untuk memulai penjualan.</p>
  </div>

  <div class="row g-4">
    <!-- PRODUK -->
    <div class="col-md-3">
      <div class="card menu-card">
        <div class="card-body text-center">
          <div class="icon bg-success-subtle text-success mb-3"><i class="bi bi-basket2-fill fs-2"></i></div>
          <h5 class="card-title">Produk</h5>
          <p class="card-text small text-muted">Kelola daftar produk dan stok barang.</p>
          <a href="pages/produk.php" class="btn btn-success w-100">Lihat Produk</a>
        </div>
      </div>
    </div>

    <!-- TRANSAKSI -->
    <div class="col-md-3">
      <div class="card menu-card">
        <div class="card-body text-center">
          <div class="icon bg-primary-subtle text-primary mb-3"><i class="bi bi-cart-fill fs-2"></i></div>
          <h5 class="card-title">Transaksi</h5>
          <p class="card-text small text-muted">Mulai penjualan baru dengan mudah.</p>
          <a href="pages/transaksi.php" class="btn btn-primary w-100">Mulai Transaksi</a>
        </div>
      </div>
    </div>

    <!-- LAPORAN -->
    <div class="col-md-3">
      <div class="card menu-card">
        <div class="card-body text-center">
          <div class="icon bg-warning-subtle text-warning mb-3"><i class="bi bi-graph-up-arrow fs-2"></i></div>
          <h5 class="card-title">Laporan</h5>
          <p class="card-text small text-muted">Pantau hasil penjualan dan cetak nota.</p>
          <a href="pages/laporan.php" class="btn btn-warning w-100 text-white">Lihat Laporan</a>
        </div>
      </div>
    </div>

    <!-- HISTORI -->
<div class="col-md-3">
  <div class="card menu-card">
    <div class="card-body text-center">
      <div class="icon bg-secondary-subtle text-secondary mb-3"><i class="bi bi-clock-history fs-2"></i></div>
      <h5 class="card-title">Histori</h5>
      <p class="card-text small text-muted">Lihat histori perubahan pada daftar produk.</p>
     <a href="pages/histori.php" class="btn btn-secondary w-100 text-white">Lihat Histori</a> 
    </div>
  </div>
</div>

<!-- Toast Notifikasi -->
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1100">
  <div id="stokHabisToast" 
       class="toast align-items-center text-bg-danger border-0 shadow"
       role="alert" aria-live="assertive" aria-atomic="true"
       style="cursor: pointer;">  
    <div class="d-flex">
      <div class="toast-body">
        <strong>⚠️ Stok Produk Habis!</strong><br>
        Beberapa produk kehabisan stok:
        <ul class="mb-0 small">
          <?php foreach ($produk_habis as $nama): ?>
            <li><?= htmlspecialchars($nama); ?></li>
          <?php endforeach; ?>
        </ul>
        <div class="small text-decoration-underline mt-2">Klik untuk kelola produk</div>
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" 
              data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
</div>



<?php include 'includes/footer.php'; ?>


<!-- Bootstrap Icons + Script -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
  <?php if (!empty($produk_habis)): ?>
    const toastEl = document.getElementById('stokHabisToast');
    const toast = new bootstrap.Toast(toastEl, { delay: 8000 }); // tampil 8 detik
    toast.show();
  <?php endif; ?>
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function() {
  <?php if (!empty($produk_habis)): ?>
    const toastEl = document.getElementById('stokHabisToast');
    const toast = new bootstrap.Toast(toastEl, { delay: 8000 });
    toast.show();

    // Klik toast untuk redirect
    toastEl.addEventListener('click', function(e) {
      // Pastikan bukan tombol close yang diklik
      if (!e.target.classList.contains('btn-close')) {
        window.location.href = 'pages/produk.php';
      }
    });
  <?php endif; ?>
});
</script>

</body>
</html>
