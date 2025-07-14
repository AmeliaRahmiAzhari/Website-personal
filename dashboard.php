<?php
session_start();
if (!isset($_SESSION['admin_login'])) {
  header("Location: login.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dasbor Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #f8f9fa, #e3f2fd);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .dashboard-container {
      max-width: 600px;
      margin: 80px auto;
      padding: 40px;
      background-color: #ffffff;
      border-radius: 20px;
      box-shadow: 0 0 20px rgba(0,0,0,0.1);
      text-align: center;
    }
    .dashboard-container h2 {
      margin-bottom: 30px;
      color: #007bff;
    }
    .nav-links a {
      display: block;
      margin: 15px 0;
      padding: 12px 20px;
      border-radius: 10px;
      text-decoration: none;
      color: #343a40;
      background-color: #f1f3f5;
      transition: all 0.3s ease;
      font-size: 18px;
    }
    .nav-links a:hover {
      background-color: #007bff;
      color: white;
    }
    .nav-links i {
      margin-right: 10px;
    }
    .logout-btn {
      margin-top: 30px;
      padding: 10px 25px;
      border-radius: 25px;
      background-color: #dc3545;
      color: white;
      text-decoration: none;
      transition: background-color 0.3s;
    }
    .logout-btn:hover {
      background-color: #b02a37;
    }
  </style>
</head>
<body>

  <div class="dashboard-container">
    <h2>Selamat Datang, Admin!</h2>
    <div class="nav-links">
      <a href="admin_kategori.php"><i class="bi bi-tags-fill"></i>Kelola Kategori</a>
      <a href="admin_produk.php"><i class="bi bi-box-seam"></i>Kelola Produk</a>
      <a href="admin_testimoni.php"><i class="bi bi-chat-left-text"></i>Kelola Testimoni</a>
      <a href="laporan_harian.php"><i class="bi bi-chat-left-text"></i>Laporan Harian</a>
      <a href="laporan_bulanan.php"><i class="bi bi-chat-left-text"></i>Laporan Bulanan</a>
      <a href="laporan_tahunan.php"><i class="bi bi-chat-left-text"></i>Laporan Tahunan</a>
      <a href="laporan_user_admin.php"><i class="bi bi-chat-left-text"></i>Laporan User</a>
    </div>
    <a href="logout.php" class="logout-btn"><i class="bi bi-box-arrow-right"></i> Logout</a>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
