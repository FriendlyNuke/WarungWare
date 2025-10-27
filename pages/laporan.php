<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit;
}

include '../config/database.php';

// Query untuk mengambil data penjualan, produk, dan jumlah
$penjualan = mysqli_query($conn, "
  SELECT 
  p.id,
    p.tanggal,
    pr.nama_produk,
    SUM(d.jumlah) AS total_jumlah,
    SUM(d.subtotal) AS total_harga
  FROM penjualan p
  JOIN detail_penjualan d ON p.id = d.id_penjualan
  JOIN produk pr ON d.id_produk = pr.id
  GROUP BY p.tanggal, pr.id
  ORDER BY p.tanggal ASC
");

?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Laporan Penjualan - <?php include '../brand.php' ?></title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/style.css">
</head> <style>
  .card.shadow-sm {
  margin-bottom: 20px !important;
  margin-top: 10px !important;
}

#gtotal {
  margin-bottom: 50px !important;
}

form.card.shadow-sm {
  margin-bottom: 20px !important;
}

</style>
<body>

<?php if (isset($_GET['views'])) {
    $_SESSION['views'] = $_GET['views'];
}
include '../includes/header.php'; $views = $_SESSION['views'] ?? 'terbaru'; ?>

<div class="container py-4">
  <h3 class="fw-bold text-success mb-3">Laporan Penjualan</h3>
<div class="dropdown">
            <button class="btn btn-outline-success dropdown-toggle" type="button" data-bs-toggle="dropdown">
               Urutkan Berdasarkan: <?= ucfirst($views) ?>
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item <?= $views == 'Terbaru' ? 'active' : '' ?>" href="?views=terbaru">Terbaru</a></li>
                <li><a class="dropdown-item <?= $views == 'Terlama' ? 'active' : '' ?>" href="?views=terlama">Terlama</a></li>
                <li><a class="dropdown-item <?= $views == 'Total Tertinggi' ? 'active' : '' ?>" href="?views=total tertinggi">Total Tertinggi</a></li>
                <li><a class="dropdown-item <?= $views == 'Total Terendah' ? 'active' : '' ?>" href="?views=total terendah">Total Terendah</a></li>

            </ul>
        </div>
  <?php  
 if ($views == 'terbaru'){
  $penjualan = mysqli_query($conn, "
  SELECT 
  p.id,
    p.tanggal,
    pr.nama_produk,
    SUM(d.jumlah) AS total_jumlah,
    SUM(d.subtotal) AS total_harga
  FROM penjualan p
  JOIN detail_penjualan d ON p.id = d.id_penjualan
  JOIN produk pr ON d.id_produk = pr.id
  GROUP BY p.tanggal, pr.id
  ORDER BY p.tanggal DESC
");
 } else if ($views == 'terlama'){
  $penjualan = mysqli_query($conn, "
  SELECT 
  p.id,
    p.tanggal,
    pr.nama_produk,
    SUM(d.jumlah) AS total_jumlah,
    SUM(d.subtotal) AS total_harga
  FROM penjualan p
  JOIN detail_penjualan d ON p.id = d.id_penjualan
  JOIN produk pr ON d.id_produk = pr.id
  GROUP BY p.tanggal, pr.id
  ORDER BY p.tanggal ASC
");
  } else if ($views == 'total tertinggi') {
  $penjualan = mysqli_query($conn, "
    SELECT 
    p.id,
      p.tanggal,
      pr.nama_produk,
      SUM(d.jumlah) AS total_jumlah,
      SUM(d.subtotal) AS total_harga
    FROM penjualan p
    JOIN detail_penjualan d ON p.id = d.id_penjualan
    JOIN produk pr ON d.id_produk = pr.id
    GROUP BY p.tanggal, pr.id
    ORDER BY total_harga DESC
  ");
}else if ($views == 'total terendah') {
  $penjualan = mysqli_query($conn, "
    SELECT 
    p.id,
      p.tanggal,
      pr.nama_produk,
      SUM(d.jumlah) AS total_jumlah,
      SUM(d.subtotal) AS total_harga
    FROM penjualan p
    JOIN detail_penjualan d ON p.id = d.id_penjualan
    JOIN produk pr ON d.id_produk = pr.id
    GROUP BY p.tanggal, pr.id
    ORDER BY total_harga ASC
  ");
}

  
  ?>      
  <div class="card shadow-sm">
    <div class="card-body">
      <table class="table table-striped align-middle">
        <thead class="table-success">
          <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Produk</th>
            <th>Jumlah</th>
            <th>Total Penjualan</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          $current_date = '';
          $total_harian = 0;
          $grand_total = 0;
          $no = 1;

          foreach ($penjualan as $p):
            // Jika tanggal berubah, tampilkan total harian sebelumnya
            if ($current_date != $p['tanggal']) {
              if ($current_date != '') {
                echo "<tr class='table-secondary'>
                        <td colspan='4' class='text-end fw-bold'>Total Harian</td>
                        <td class='fw-bold'>Rp " . number_format($total_harian, 0, ',', '.') . "</td>
                      </tr>";
                $total_harian = 0;
              }

              // Header tanggal
              $current_date = $p['tanggal'];
              echo "<tr class='table-success'>
        <td colspan='4'>{$p['tanggal']}</td>
        <td class='text-end'>
        <html>
          <a href='cetak_nota.php?id_penjualan={$p['id']}' target='_blank' class='btn btn-sm btn-outline-success'>
            🧾 Cetak Nota
          </a>
          </html>
        </td>
      </tr>";

            }

            // Tambah total harian & total keseluruhan
            $total_harian += $p['total_harga'];
            $grand_total += $p['total_harga'];
          ?>
          <tr>
            <td><?= $no++; ?></td>
            <td><?= $p['tanggal']; ?></td>
            <td><?= $p['nama_produk']; ?></td>
            <td><?= $p['total_jumlah']; ?></td>
            <td>Rp <?= number_format($p['total_harga'], 0, ',', '.'); ?></td>
          </tr>
          <?php endforeach; ?>

          <!-- Tampilkan total harian terakhir -->
          <tr class="table-secondary">
            <td colspan="4" class="text-end fw-bold">Total Harian</td>
            <td class="fw-bold">Rp <?= number_format($total_harian, 0, ',', '.'); ?></td>
          </tr>
        </tbody>
      </table>

      <div id="gtotal" class="text-end fw-bold fs-5">
        Total Keseluruhan: Rp <?= number_format($grand_total, 0, ',', '.'); ?>
      </div>
    </div>
  </div>
</div>
          </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
<?php include '../includes/footer.php'; ?>
</html>

