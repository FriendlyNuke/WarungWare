<?php
session_start();
include '../config/database.php';

// Pastikan user login
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Produk - <?php include '../brand.php' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<?php include '../includes/header.php'; ?>
<div class="container py-5">
  <h3 class="mb-4 text-success">📜 Histori Perubahan Produk</h3>
  <table class="table table-bordered table-hover">
    <thead class="table-success">
    <thead>
        <tr>
            <th>No</th>
            <th>Produk</th>
            <th>Aksi</th>
            <th>Tanggal</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $q = mysqli_query($conn, "SELECT h.*, u.username, p.nama_produk 
                                  FROM histori_edit h
                                  LEFT JOIN users u ON h.id_user=u.id
                                  LEFT JOIN produk p ON h.id_produk=p.id
                                  ORDER BY h.created_at DESC");
        $no = 1;
        while ($row = mysqli_fetch_assoc($q)) {
            echo "<tr>
                    <td>{$no}</td>
                    <td>".($row['nama_produk'] ?? '-')."</td>
                    <td>{$row['aksi']}</td>
                    <td>{$row['created_at']}</td>
                  </tr>";
            $no++;
        }
        ?>
    </tbody>
</table>
    </div>
  <a href="../" class="btn btn-secondary mt-3">⬅ Kembali ke Dashboard</a>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php include '../includes/footer.php'; ?>
</body>
</html>
