<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit;
}
include '../config/database.php';

// === MODE VIEW ===
if (isset($_GET['view'])) {
    $_SESSION['view'] = $_GET['view'];
    header("Location: produk.php");
    exit;
}
$view = $_SESSION['view'] ?? 'grid';

// === TAMBAH PRODUK ===
if (isset($_POST['tambah'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
$harga = (int)$_POST['harga'];
$stok = (int)$_POST['stok'];
$kategori = mysqli_real_escape_string($conn, $_POST['kategori']);

$gambar = null;
if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
    $target_dir = "../uploads/";
    if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
    $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
    $filename = time() . '_' . uniqid() . '.' . $ext;
    move_uploaded_file($_FILES['gambar']['tmp_name'], $target_dir . $filename);
    $gambar = mysqli_real_escape_string($conn, $filename);
}

// Gunakan NULL jika $gambar kosong
$gambar_sql = $gambar ? "'$gambar'" : "NULL";

mysqli_query($conn, "INSERT INTO produk (nama_produk, harga, stok, kategori, gambar)
                     VALUES ('$nama', '$harga', '$stok', '$kategori', $gambar_sql)");

// Dapatkan ID produk baru
$id_produk = mysqli_insert_id($conn);

// ===== Tambahkan catatan ke histori_edit =====
$id_user = $_SESSION['user_id'] ?? 0;
$aksi = mysqli_real_escape_string($conn, 
    "Menambahkan produk baru: '$nama' (Kategori: $kategori, Harga: Rp$harga, Stok: $stok)"
);
mysqli_query($conn, "INSERT INTO histori_edit (id_user, id_produk, aksi)
                     VALUES ('$id_user', '$id_produk', '$aksi')");

header("Location: produk.php?status=added");
exit;

}

// === HAPUS PRODUK ===
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    // Ambil data produk dulu
    $q = mysqli_query($conn, "SELECT * FROM produk WHERE id='$id'");
    $data = mysqli_fetch_assoc($q);

    if ($data) {
        // Catat histori SEBELUM hapus
        $id_user = $_SESSION['user_id'] ?? 0;
        $nama_produk = mysqli_real_escape_string($conn, $data['nama_produk']);
        $kategori = mysqli_real_escape_string($conn, $data['kategori']);
        $harga = $data['harga'];
        $stok = $data['stok'];

        $aksi = "Menghapus produk: '$nama_produk' (Kategori: $kategori, Harga: Rp$harga, Stok: $stok)";
        $aksi = mysqli_real_escape_string($conn, $aksi);

        mysqli_query($conn, "INSERT INTO histori_edit (id_user, id_produk, aksi) 
                             VALUES ('$id_user', '$id', '$aksi')");

        // Hapus gambar
        if ($data['gambar'] && file_exists("../uploads/" . $data['gambar'])) {
            unlink("../uploads/" . $data['gambar']);
        }

        // Hapus produk
        mysqli_query($conn, "DELETE FROM produk WHERE id='$id'");

        header("Location: produk.php?status=deleted");
        exit;
    }
}



// === EDIT PRODUK ===
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $kategori = $_POST['kategori'];

    $gambar_lama = $_POST['gambar_lama'];
    $gambar_baru = $gambar_lama;

    // Ambil data lama dari database
    $query_lama = mysqli_query($conn, "SELECT * FROM produk WHERE id='$id'");
    $produk_lama = mysqli_fetch_assoc($query_lama);

    // --- PROSES GAMBAR BARU ---
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $target_dir = "../uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $filename = time() . '_' . uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['gambar']['tmp_name'], $target_dir . $filename);
        $gambar_baru = $filename;

        // Hapus gambar lama jika ada
        if ($gambar_lama && file_exists("../uploads/" . $gambar_lama)) {
            unlink("../uploads/" . $gambar_lama);
        }
    }

    // --- UPDATE DATA PRODUK ---
    mysqli_query($conn, "UPDATE produk SET 
        nama_produk='$nama', 
        harga='$harga', 
        stok='$stok', 
        kategori='$kategori',
        gambar='$gambar_baru'
        WHERE id='$id'");

    // --- CATAT HISTORI PERUBAHAN ---
    $aksi = [];

    if ($produk_lama['nama_produk'] != $nama) {
        $aksi[] = "Ubah nama dari '{$produk_lama['nama_produk']}' ke '$nama'";
    }
    if ($produk_lama['harga'] != $harga) {
        $aksi[] = "Ubah harga dari Rp{$produk_lama['harga']} ke Rp{$harga}";
    }
    if ($produk_lama['stok'] != $stok) {
        $aksi[] = "Ubah stok dari {$produk_lama['stok']} ke {$stok}";
    }
    if ($produk_lama['kategori'] != $kategori) {
        $aksi[] = "Ubah kategori dari '{$produk_lama['kategori']}' ke '$kategori'";
    }
    if ($produk_lama['gambar'] != $gambar_baru) {
        $aksi[] = "Ganti gambar produk";
    }

    if (!empty($aksi)) {
        $keterangan = implode('. ', $aksi) . '.';
        $id_user = $_SESSION['user_id'] ?? 0; // pastikan ada session user

        mysqli_query($conn, "INSERT INTO histori_edit (id_user, id_produk, aksi) 
                             VALUES ('$id_user', '$id', '$keterangan')");
    }

    // --- REDIRECT SELESAI ---
    header("Location: produk.php");
    exit;
}

// === AMBIL DATA PRODUK ===
$produk = mysqli_query($conn, "SELECT * FROM produk ORDER BY id DESC");
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
    <style>
      .product-card {
  transform: scale(0.85); /* perkecil keseluruhan card */
  transform-origin: top center; /* supaya tetap sejajar */
  transition: transform 0.2s ease-in-out;
}
      .product-card:hover { transform: scale(0.95); box-shadow: 0 3px 10px rgba(0,0,0,0.1); }
      .product-img { height: 200px; object-fit: cover; border-bottom: 1px solid #eee; }
 .card.shadow-sm {
  margin-bottom: 20px !important;
  margin-top: 10px !important;
}

#grid {
  margin-bottom: 50px !important;
}
#list {
  margin-bottom: 50px !important;
}
form.card.shadow-sm {
  margin-bottom: 20px !important;
}
    </style>
</head>
<body>

<?php include '../includes/header.php'; ?>

<div class="container py-4">
  <h3 class="fw-bold text-success mb-4">Produk</h3>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="dropdown">
            <button class="btn btn-outline-success dropdown-toggle" type="button" data-bs-toggle="dropdown">
                View: <?= ucfirst($view) ?>
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item <?= $view == 'list' ? 'active' : '' ?>" href="?view=list">List</a></li>
                <li><a class="dropdown-item <?= $view == 'grid' ? 'active' : '' ?>" href="?view=grid">Grid</a></li>
            </ul>
        </div>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#tambahModal">+ Tambah Produk</button>
    </div>

    <?php if ($view == 'list'): ?>
    <!-- === LIST VIEW === -->
    <div id="list" class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead class="table-success">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Kategori</th>
                        <th>Gambar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no=1; foreach($produk as $row): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $row['nama_produk']; ?></td>
                        <td>Rp <?= number_format($row['harga'],0,',','.'); ?></td>
                        <td><?= $row['stok']; ?></td>
                        <td><?= $row['kategori']; ?></td>
                        <td>
                            <img src="<?= $row['gambar'] ? '../uploads/'.$row['gambar'] : '../assets/img/default.png'; ?>"
                                 width="60" height="60" style="object-fit:cover; border-radius:6px;">
                        </td>
                        <td>
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#editModal<?= $row['id']; ?>">Edit</button>
                            <a href="?hapus=<?= $row['id']; ?>" class="btn btn-sm btn-danger"
                               onclick="return confirm('Yakin hapus produk ini?')">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php else: ?>
    <!-- === GRID VIEW === -->
    <div id="grid"  class="row row-cols-1 row-cols-md-1 row-cols-lg-5 g-1 none">
        <?php foreach($produk as $row): ?>
        <div class="col">
            <div class="card product-card">
                <img src="<?= $row['gambar'] ? '../uploads/'.$row['gambar'] : '../assets/img/default.png'; ?>"
                onerror="this.src='../assets/img/default.png'; this.removeAttribute('onerror');" 
                     class="card-img-top product-img" alt="Produk">
                <div class="card-body">
                    <h5 class="card-title text-success fw-semibold"><?= $row['nama_produk']; ?></h5>
                    <p class="mb-1"><strong>Harga:</strong> Rp <?= number_format($row['harga'],0,',','.'); ?></p>
                    <p class="mb-1"><strong>Stok:</strong> <?= $row['stok']; ?></p>
                    <p class="mb-2"><strong>Kategori:</strong> <?= $row['kategori']; ?></p>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-primary flex-grow-1" data-bs-toggle="modal"
                                data-bs-target="#editModal<?= $row['id']; ?>">Edit</button>
                        <a href="?hapus=<?= $row['id']; ?>" class="btn btn-sm btn-danger flex-grow-1"
                           onclick="return confirm('Yakin hapus produk ini?')">Hapus</a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<!-- === MODAL TAMBAH === -->
<div class="modal fade" id="tambahModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post" enctype="multipart/form-data">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title">Tambah Produk</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3"><label>Nama Produk</label><input type="text" name="nama" class="form-control" required></div>
          <div class="mb-3"><label>Harga</label><input type="number" name="harga" class="form-control" required></div>
          <div class="mb-3"><label>Stok</label><input type="number" name="stok" class="form-control" required></div>
          <div class="mb-3"><label>Kategori</label><input type="text" name="kategori" class="form-control" required></div>
          <div class="mb-3"><label>Gambar Produk</label><input type="file" name="gambar" class="form-control" accept="image/*"></div>
        </div>
        <div class="modal-footer"><button type="submit" name="tambah" class="btn btn-success">Simpan</button></div>
      </form>
    </div>
  </div>
</div>

<!-- === MODAL EDIT (dinamis untuk setiap produk) === -->
<?php foreach($produk as $row): ?>
<div class="modal fade" id="editModal<?= $row['id']; ?>" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post" enctype="multipart/form-data">
        <div class="modal-header bg-warning text-white">
          <h5 class="modal-title">Edit Produk</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" value="<?= $row['id']; ?>">
          <input type="hidden" name="gambar_lama" value="<?= $row['gambar']; ?>">
          <div class="mb-3"><label>Nama Produk</label>
            <input type="text" name="nama" class="form-control" value="<?= $row['nama_produk']; ?>" required></div>
          <div class="mb-3"><label>Harga</label>
            <input type="number" name="harga" class="form-control" value="<?= $row['harga']; ?>" required></div>
          <div class="mb-3"><label>Stok</label>
            <input type="number" name="stok" class="form-control" value="<?= $row['stok']; ?>" required></div>
          <div class="mb-3"><label>Kategori</label>
            <input type="text" name="kategori" class="form-control" value="<?= $row['kategori']; ?>" required></div>
          <div class="mb-3">
            <label>Gambar Sekarang</label><br>
            <img src="<?= $row['gambar'] ? '../uploads/'.$row['gambar'] : '../assets/images/default-product.jpg'; ?>"
                 width="80" height="80" style="object-fit:cover; border-radius:6px;">
          </div>
          <div class="mb-3">
            <label>Ganti Gambar (opsional)</label>
            <input type="file" name="gambar" class="form-control" accept="image/*">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="edit" class="btn btn-warning">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php endforeach; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php include '../includes/footer.php'; ?>
</body>
</html>
