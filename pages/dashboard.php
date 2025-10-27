<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Aplikasi Penjualan Warung</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body>

<?php include '../includes/header.php'; ?>

<div class="container mt-4">
    <h3>Dashboard</h3>
    <p>Halo, <strong><?= $_SESSION['username']; ?></strong>! Selamat datang di aplikasi penjualan warung.</p>

    <div class="row">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Produk</h5>
                    <a href="produk.php" class="btn btn-primary">Kelola Produk</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Transaksi</h5>
                    <a href="transaksi.php" class="btn btn-success">Mulai Jualan</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Laporan</h5>
                    <a href="laporan.php" class="btn btn-warning">Lihat Laporan</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
<script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
