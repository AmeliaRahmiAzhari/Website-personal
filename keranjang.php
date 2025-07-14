<?php
include 'include/db.php';
session_start();

// Jika produk ditambahkan melalui form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $jumlah = $_POST['jumlah'];

    // Simpan ke dalam session keranjang
    if (!isset($_SESSION['keranjang'])) {
        $_SESSION['keranjang'] = [];
    }

    // Cek jika produk sudah ada dalam keranjang
    $found = false;
    foreach ($_SESSION['keranjang'] as &$item) {
        if ($item['id'] == $id) {
            $item['jumlah'] += $jumlah;
            $found = true;
            break;
        }
    }

    // Jika belum ada, tambahkan produk baru ke keranjang
    if (!$found) {
        $_SESSION['keranjang'][] = [
            'id' => $id,
            'nama' => $nama,
            'harga' => $harga,
            'jumlah' => $jumlah
        ];
    }
}

$session_id = session_id();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Keranjang Belanja</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to bottom right, #1c1c1c, #2a2a2a);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      padding: 30px;
      color: #f5f5f5;
    }

    .keranjang-container {
      background-color: #1f1f1f;
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.4);
      color: #f5f5f5;
    }

    .keranjang-title {
      font-size: 2rem;
      color: #ffc107;
      margin-bottom: 30px;
      font-weight: bold;
    }

    .table-dark th,
    .table-dark td {
      vertical-align: middle;
      background-color: #2a2a2a;
      border-color: #444;
      color: #f1f1f1;
    }

    .table-dark th {
      color: #ffc107;
      font-weight: bold;
    }

    .table-dark td a.btn-danger {
      padding: 4px 10px;
      font-size: 0.875rem;
      border-radius: 8px;
      background-color: #e74c3c;
      border: none;
    }

    .table-dark td a.btn-danger:hover {
      background-color: #c0392b;
    }

    .total-belanja {
      font-size: 1.5rem;
      font-weight: bold;
      color: #00ffcc;
      text-align: right;
      margin-top: 20px;
    }

    .checkout-btn {
      background-color: #28a745;
      border: none;
      padding: 10px 25px;
      font-weight: bold;
      border-radius: 10px;
      transition: 0.3s;
      color: white;
      float: right;
    }

    .checkout-btn:hover {
      background-color: #218838;
    }
  </style>
</head>
<body>
<?php include 'include/header.php'; ?>

<div class="container keranjang-container">
  <h1 class="keranjang-title">Keranjang Belanja</h1>
  <table class="table table-dark table-bordered">
    <thead>
      <tr>
        <th>Produk</th>
        <th>Jumlah</th>
        <th>Harga</th>
        <th>Total</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
    <?php
    $total = 0;
    if (isset($_SESSION['keranjang'])) {
        foreach ($_SESSION['keranjang'] as $item) {
            $sub = $item['harga'] * $item['jumlah'];
            $total += $sub;
            ?>
            <tr>
              <td><?= $item['nama']; ?></td>
              <td><?= $item['jumlah']; ?></td>
              <td>Rp <?= number_format($item['harga'], 0, ',', '.'); ?></td>
              <td>Rp <?= number_format($sub, 0, ',', '.'); ?></td>
              <td><a href="hapus.php?id=<?= $item['id']; ?>" class="btn btn-danger btn-sm">Hapus</a></td>
            </tr>
        <?php }
    } ?>
    </tbody>
  </table>

  <div class="total-belanja">Total: Rp <?= number_format($total, 0, ',', '.'); ?></div>
  <a href="checkout.php" class="btn checkout-btn">Checkout</a>
</div>

<?php include 'include/footer.php'; ?>
</body>
</html>
