<?php
// mulai sesi
session_start();

// jika belum login, arahkan ke login.php
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// koneksi database
include 'config/database.php';
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

    <!-- LOGOUT -->
    <div class="col-md-3">
      <div class="card menu-card">
        <div class="card-body text-center">
          <div class="icon bg-danger-subtle text-danger mb-3"><i class="bi bi-box-arrow-right fs-2"></i></div>
          <h5 class="card-title">Logout</h5>
          <p class="card-text small text-muted">Keluar dari aplikasi dengan aman.</p>
          <a href="logout.php" class="btn btn-danger w-100">Logout</a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>


<!-- Bootstrap Icons + Script -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
