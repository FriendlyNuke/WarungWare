<?php
    session_start();
    if (!isset($_SESSION['username'])) {
        header("Location: ../login.php");
        exit;
    }
    include '../config/database.php';

    // ambil daftar produk
    $produk = mysqli_query($conn, "SELECT * FROM produk WHERE stok > 0");

    // inisialisasi keranjang
    if (!isset($_SESSION['keranjang'])) {
        $_SESSION['keranjang'] = [];
    }

    // tambah ke keranjang (baik dari form list maupun modal gambar)
    if (isset($_POST['tambah'])) {
        $id_produk = $_POST['id_produk'];
        $jumlah = (int) $_POST['jumlah'];
        $data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM produk WHERE id='$id_produk'"));

        if (!$data) {
            $error = "Produk tidak ditemukan.";
        } elseif ($jumlah < 1) {
            $error = "Jumlah minimal 1.";
        } elseif ($jumlah > $data['stok']) {
            $error = "Jumlah melebihi stok yang tersedia (" . $data['stok'] . ").";
        } else {
            if (isset($_SESSION['keranjang'][$id_produk])) {
                $newJumlah = $_SESSION['keranjang'][$id_produk] + $jumlah;
                if ($newJumlah > $data['stok']) {
                    $error = "Total jumlah melebihi stok yang tersedia.";
                } else {
                    $_SESSION['keranjang'][$id_produk] = $newJumlah;
                }
            } else {
                $_SESSION['keranjang'][$id_produk] = $jumlah;
            }
        }
    }

    // hapus item dari keranjang
    if (isset($_GET['hapus'])) {
        $hapus_id = $_GET['hapus'];
        unset($_SESSION['keranjang'][$hapus_id]);
        header("Location: transaksi.php");
        exit;
    }

    // simpan transaksi
    if (isset($_POST['simpan'])) {
        if (!empty($_SESSION['keranjang'])) {
            $total = 0;
            mysqli_query($conn, "INSERT INTO penjualan (tanggal, total) VALUES (NOW(), 0)");
            $id_penjualan = mysqli_insert_id($conn);

            foreach ($_SESSION['keranjang'] as $id_produk => $jumlah) {
                $data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM produk WHERE id='$id_produk'"));
                $subtotal = $data['harga'] * $jumlah;
                $total += $subtotal;
                mysqli_query($conn, "INSERT INTO detail_penjualan (id_penjualan, id_produk, jumlah, subtotal)
                                    VALUES ('$id_penjualan','$id_produk','$jumlah','$subtotal')");
                mysqli_query($conn, "UPDATE produk SET stok = stok - $jumlah WHERE id='$id_produk'");
            }

            mysqli_query($conn, "UPDATE penjualan SET total = '$total' WHERE id='$id_penjualan'");
            unset($_SESSION['keranjang']);
            $sukses = "Transaksi berhasil disimpan!";
        } else {
            $error = "Keranjang masih kosong!";
        }
    }
    $produk = mysqli_query($conn, "SELECT * FROM produk ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Transaksi - <?php include '../brand.php' ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
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

#view-gambar {
  margin-bottom: 10px !important;
}

#keranjang {
  margin-top: 10px !important;
}

form.card.shadow-sm {
  margin-bottom: 10px !important;
}
.bootstrap-select .dropdown-menu li a img {
  vertical-align: middle;
  margin-right: 8px;
  border-radius: 4px;
  width: 32px;
  height: 32px;
  object-fit: cover;
}
.bootstrap-select .dropdown-toggle {
  display: flex;
  align-items: center;
}

</style>
</head>
<body>

<?php include '../includes/header.php'; ?>

<div class="container py-4">
  <h3 class="fw-bold text-success mb-4">Transaksi Penjualan</h3>

  <?php if (!empty($sukses)): ?>
    <div class="alert alert-success"><?= $sukses; ?></div>
  <?php elseif (!empty($error)): ?>
    <div class="alert alert-danger"><?= $error; ?></div>
  <?php endif; ?>

  <!-- Dropdown Pilihan View -->
  <div class="dropdown mb-3">
    <button class="btn btn-outline-success dropdown-toggle" type="button" id="viewDropdown" data-bs-toggle="dropdown" aria-expanded="false">
      Pilih Tampilan
    </button>
    <ul class="dropdown-menu">
      <li><a class="dropdown-item" href="#" onclick="setView('list')">List</a></li>
      <li><a class="dropdown-item" href="#" onclick="setView('gambar')">Gambar</a></li>

    </ul>
  </div>

  <!-- VIEW: LIST -->
  <div id="view-list">
  <form method="post" class="card shadow-sm p-4 mb-4">
    <div class="row g-3 align-items-end">
      <div class="col-md-6">
        <label>Produk</label>
       <select name="id_produk"
        class="selectpicker"
        data-live-search="true"
        data-style="btn-outline-primary"
        data-container="body"
        title="-- Pilih Produk --"
        required>
  <option value="">-- Pilih Produk --</option>
          <?php mysqli_data_seek($produk, 0); foreach($produk as $p): ?>
            <?php
              // Tentukan path gambar produk (gunakan default jika kosong)
              $gambar = !empty($p['gambar']) ? '../uploads/' . htmlspecialchars($p['gambar']) : '../assets/img/default.png';
              $nama   = htmlspecialchars($p['nama_produk']);
              $stok   = (int)$p['stok'];
              $harga  = number_format($p['harga']);
            ?>
            <option 
              value="<?= $p['id']; ?>"
              data-content="
                <div class='d-flex align-items-center'>
                  <img src='<?= $gambar; ?>' 
                       class='rounded me-2 border' 
                       style='width:40px;height:40px;object-fit:cover;'>
                  <div class='d-inline-block text-start'>
                    <div class='fw-semibold'><?= $nama; ?></div>
                    <small class='text-muted'>Stok: <?= $stok; ?> - Rp <?= $harga; ?></small>
                  </div>
                </div>
              ">
              <?= $nama; ?> <!-- fallback text (wajib untuk HTML select) -->
            </option>
          <?php endforeach; ?>
        </select>

      </div>
      <div class="col-md-3">
        <label>Jumlah</label>
        <input type="number" name="jumlah" class="form-control" min="1" required>
      </div>
      <div class="col-md-3">
        <button type="submit" name="tambah" class="btn btn-primary w-100">Tambahkan</button>
      </div>
    </div>
  </form>
</div>

  <!-- VIEW: GAMBAR -->

  <div id="view-gambar" class="row row-cols-1 row-cols-md-1 row-cols-lg-5 g-1 none ">
    <?php mysqli_data_seek($produk, 0); foreach($produk as $p): ?>
    <div class="col-md-3">
      <div class="card product-card">
        <img src="<?= $p['gambar'] ? '../uploads/'.$p['gambar'] : '../assets/images/default.png'; ?>"
                                 style="object-fit:cover; border-radius:6px;"
             onerror="this.src='../assets/img/default.png'; this.removeAttribute('onerror');" 
             class="card-img-top product-img">
        <div class="card-body text-center">
          <h6><?= htmlspecialchars($p['nama_produk']); ?></h6>
          <p class="text-success mb-1">Rp <?= number_format($p['harga'],0,',','.'); ?></p>
          <small class="text-muted">Stok: <?= $p['stok']; ?></small><br>
          <button class="btn btn-sm btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#qtyModal" 
                  data-id="<?= $p['id']; ?>" data-nama="<?= htmlspecialchars($p['nama_produk']); ?>">Tambahkan</button>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>



  <!-- DAFTAR KERANJANG -->
  <?php if (!empty($_SESSION['keranjang'])): ?>
  <div id="keranjang" class="card shadow-sm mt-5">
    <div class="card-body">
      <h5>Daftar Produk dalam Transaksi</h5>
      <table class="table table-striped">
        <thead class="table-success">
          <tr>
            <th>No</th>
            <th>Produk</th>
            <th>Jumlah</th>
            <th>Subtotal</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          $no=1; $total=0;
          foreach($_SESSION['keranjang'] as $id_produk => $jumlah):
            $data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM produk WHERE id='$id_produk'"));
            $subtotal = $data['harga'] * $jumlah;
            $total += $subtotal;
          ?>
          <tr>
            <td><?= $no++; ?></td>
            <td><?= htmlspecialchars($data['nama_produk']); ?></td>
            <td><?= $jumlah; ?></td>
            <td>Rp <?= number_format($subtotal,0,',','.'); ?></td>
            <td>
              <a href="?hapus=<?= $id_produk; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus produk ini dari transaksi?')">Hapus</a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <div class="text-end fw-bold fs-5 mt-3">
        Total: Rp <?= number_format($total,0,',','.'); ?>
      </div>
      <form method="post" class="mt-3">
        <button type="submit" name="simpan" class="btn btn-success w-100">Simpan Transaksi</button>
      </form>
    </div>
  </div>
  <?php endif; ?>

</div>

<!-- MODAL KUANTITAS -->
<div class="modal fade" id="qtyModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Tambah ke Keranjang</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id_produk" id="produkId">
          <div class="mb-3">
            <label>Produk</label>
            <input type="text" id="produkNama" class="form-control" readonly>
          </div>
          <div class="mb-3">
            <label>Jumlah</label>
            <input type="number" name="jumlah" class="form-control" min="1" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="tambah" class="btn btn-primary">Tambahkan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- JS -->
 <script>
  // Inisialisasi plugin Bootstrap-Select setelah halaman dimuat
  document.addEventListener("DOMContentLoaded", function() {
    $('.selectpicker').selectpicker();
  });
</script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Bootstrap-Select JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
>
<script>
const qtyModal = document.getElementById('qtyModal');
qtyModal.addEventListener('show.bs.modal', function (event) {
  const button = event.relatedTarget;
  document.getElementById('produkId').value = button.getAttribute('data-id');
  document.getElementById('produkNama').value = button.getAttribute('data-nama');
});

function setView(view) {
  document.getElementById('view-list').classList.add('d-none');
  document.getElementById('view-gambar').classList.add('d-none');
  document.getElementById('view-' + view).classList.remove('d-none');
  localStorage.setItem('viewMode', view);
}

document.addEventListener('DOMContentLoaded', () => {
  const lastView = localStorage.getItem('viewMode') || 'list';
  setView(lastView);
});

document.getElementById('barcodeInput')?.addEventListener('change', function() {
  const kode = this.value.trim();
  if (kode !== '') alert('Barcode terdeteksi: ' + kode);
});
</script>
<?php include '../includes/footer.php'; ?>
</body>
</html>
