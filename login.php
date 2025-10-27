<?php
session_start();
include 'config/database.php';


if (isset($koneksi) && $koneksi instanceof mysqli) {
    $db = $koneksi;
} elseif (isset($conn) && $conn instanceof mysqli) {
    $db = $conn;
} elseif (isset($mysqli) && $mysqli instanceof mysqli) {
    $db = $mysqli;
} else {

    die('Koneksi database tidak ditemukan. Periksa config/database.php');
}

$error = '';
$usernameValue = '';

if (isset($_POST['login'])) {

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $usernameValue = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');

    if ($username === '' || $password === '') {
        $error = "Username dan password wajib diisi.";
    } else {

        $sql = "SELECT id, username, password FROM users WHERE username = ? LIMIT 1";
        if ($stmt = $db->prepare($sql)) {
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows === 1) {
                $stmt->bind_result($userId, $dbUsername, $dbPassword);
                $stmt->fetch();

                $authenticated = false;

 
                if (password_verify($password, $dbPassword)) {
                    $authenticated = true;
                } else {
      
                    if ($password === $dbPassword) {
                        $authenticated = true;
                    }
                }

                if ($authenticated) {
       
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $userId;
                    $_SESSION['username'] = $dbUsername;

          
                    header("Location: /penjualan/");
                    exit;
                } else {
                    $error = "Username atau password salah.";
                }
            } else {
                $error = "Username atau password salah.";
            }

            $stmt->close();
        } else {
        
            $error = "Terjadi kesalahan pada server. Mohon coba lagi.";
        }
    }
}
?>
<!-- login.php (versi sudah styled) -->
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login - Aplikasi Penjualan Warung</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container login-container">
  <div class="row justify-content-center">
    <div class="col-md-4">
      <div class="card login-card">
        <div class="card-header text-center bg-primary text-white rounded-top">
          <h4 class="my-2">Login Kasir</h4>
        </div>
        <div class="card-body">
          <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= $error; ?></div>
          <?php endif; ?>
          <form method="post">
            <div class="mb-3">
              <label>Username</label>
              <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
            </div>
            <div class="mb-3">
              <label>Password</label>
              <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary w-100">Masuk</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<footer>&copy; <?= date('Y'); ?> Aplikasi Penjualan Warung</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


