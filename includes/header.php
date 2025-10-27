<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!-- Header / Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold" href="/penjualan/">🏪 WarungWare</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="/penjualan/">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="/penjualan/pages/produk.php">Produk</a></li>
        <li class="nav-item"><a class="nav-link" href="/penjualan/pages/transaksi.php">Transaksi</a></li>
        <li class="nav-item"><a class="nav-link" href="/penjualan/pages/laporan.php">Laporan</a></li>
        <li class="nav-item"><a href="/penjualan/logout.php" class="btn btn-danger w-100">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>
