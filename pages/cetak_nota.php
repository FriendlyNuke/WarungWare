<?php
include '../config/database.php';

if (!isset($_GET['id_penjualan'])) {
  die("ID Penjualan tidak ditemukan.");
}

$id = $_GET['id_penjualan'];

$nota = mysqli_query($conn, "
  SELECT 
    p.tanggal,
    pr.nama_produk,
    d.jumlah,
    d.subtotal
  FROM penjualan p
  JOIN detail_penjualan d ON p.id = d.id_penjualan
  JOIN produk pr ON d.id_produk = pr.id
  WHERE p.id = '$id'
");

$transaksi = mysqli_fetch_assoc(mysqli_query($conn, "SELECT tanggal, total FROM penjualan WHERE id = '$id'"));
?>
<!DOCTYPE html>
<html lang="id"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<head>
<meta charset="UTF-8">
<title>Nota Penjualan #<?= $id; ?> - WarungWare</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { font-family: 'Poppins', sans-serif; margin: 20px; }
@media print { .no-print { display: none; } }
</style>
</head>
<body>

<div class="text-center mb-3">
  <h4 class="fw-bold text-success">WarungWare</h4>
  <p class="mb-0">Nota Penjualan #<?= $id; ?><br><?= date('d M Y H:i', strtotime($transaksi['tanggal'])); ?></p>
  <hr>
</div>

<table class="table table-bordered">
  <thead class="table-success">
    <tr>
      <th>No</th>
      <th>Produk</th>
      <th>Jumlah</th>
      <th>Subtotal</th>
    </tr>
  </thead>
  <tbody>
    <?php $no=1; $total=0; foreach ($nota as $n): $total += $n['subtotal']; ?>
    <tr>
      <td><?= $no++; ?></td>
      <td><?= $n['nama_produk']; ?></td>
      <td><?= $n['jumlah']; ?></td>
      <td>Rp <?= number_format($n['subtotal'],0,',','.'); ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<div class="text-end fw-bold fs-5 mt-3">
  Total: Rp <?= number_format($total,0,',','.'); ?>
</div>

<div class="text-center mt-4 no-print">
  <button onclick="window.print()" class="btn btn-success">🖨 Cetak</button>
   <button onclick="window.close()" class="btn btn-secondary">⬅ Kembali</button>
</div>

</body>
</html>
