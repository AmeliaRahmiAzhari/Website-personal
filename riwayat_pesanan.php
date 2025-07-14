<?php
include 'include/db.php';
session_start();

// Ambil semua pesanan
$sql = "SELECT * FROM pesanan ORDER BY tanggal DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Riwayat Pesanan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f4f4f4;
      padding: 40px;
    }

    .riwayat-container {
      background-color: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .riwayat-container h2 {
      color: #343a40;
      margin-bottom: 30px;
    }

    .card {
      margin-bottom: 25px;
      border-left: 5px solid #28a745;
    }

    .card-header {
      background: #28a745;
      color: white;
      font-weight: bold;
    }

    .produk-item {
      background: #f8f9fa;
      padding: 10px 15px;
      border-radius: 5px;
      margin-bottom: 8px;
    }
  </style>
</head>
<body>
<?php include 'include/header.php'; ?>

<div class="container riwayat-container">
  <h2>Riwayat Pesanan</h2>

  <?php if ($result->num_rows > 0): ?>
    <?php while ($pesanan = $result->fetch_assoc()): ?>
      <div class="card">
        <div class="card-header">
          Pesanan #<?= $pesanan['id']; ?> | <?= $pesanan['nama']; ?> | Rp <?= number_format($pesanan['total'], 0, ',', '.'); ?>
        </div>
        <div class="card-body">
          <p><strong>Email:</strong> <?= $pesanan['email']; ?></p>
          <p><strong>Alamat:</strong> <?= $pesanan['alamat']; ?></p>
          <p><strong>Tanggal:</strong> <?= date('d M Y H:i', strtotime($pesanan['tanggal'])); ?></p>
          <h6>Daftar Produk:</h6>
          <?php
          $pesanan_id = $pesanan['id'];
          $detailQuery = "SELECT * FROM pesanan_detail WHERE pesanan_id = $pesanan_id";
          $detailResult = $conn->query($detailQuery);
          while ($produk = $detailResult->fetch_assoc()):
          ?>
            <div class="produk-item">
              <?= $produk['nama_produk']; ?> x <?= $produk['jumlah']; ?> <span class="text-muted"> (Rp <?= number_format($produk['harga'], 0, ',', '.'); ?>)</span>
            </div>
          <?php endwhile; ?>
        </div>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <div class="alert alert-info">Belum ada pesanan.</div>
  <?php endif; ?>
</div>

<?php include 'include/footer.php'; ?>
</body>
</html>
