<?php
include 'include/db.php';
session_start();

$pesan_sukses = "";
$pesan_error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama   = $_POST['nama'];
    $email  = $_POST['email'];
    $alamat = $_POST['alamat'];

    if (empty($_SESSION['keranjang'])) {
        $pesan_error = "Keranjang kosong, tidak bisa checkout.";
    } else {
        $total = 0;
        foreach ($_SESSION['keranjang'] as $item) {
            $total += $item['harga'] * $item['jumlah'];
        }

        // Simpan ke tabel pesanan
        $stmt = $conn->prepare("INSERT INTO pesanan (nama, email, alamat, total) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $nama, $email, $alamat, $total);
        $stmt->execute();
        $pesanan_id = $stmt->insert_id;
        $stmt->close();

        // Simpan detail produk
        $stmtDetail = $conn->prepare("INSERT INTO pesanan_detail (pesanan_id, produk_id, nama_produk, harga, jumlah) VALUES (?, ?, ?, ?, ?)");
        foreach ($_SESSION['keranjang'] as $item) {
            $stmtDetail->bind_param("iisii", $pesanan_id, $item['id'], $item['nama'], $item['harga'], $item['jumlah']);
            $stmtDetail->execute();
        }
        $stmtDetail->close();

        // Simpan ke sesi untuk transaksi
        $_SESSION['checkout_nama'] = $nama;
        $_SESSION['checkout_total'] = $total;

        // Kosongkan keranjang
        unset($_SESSION['keranjang']);

        // Redirect ke halaman transaksi
        header("Location: transaksi.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f5f5f5;
            padding: 40px;
        }

        .checkout-container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
            max-width: 700px;
            margin: auto;
        }

        h2 {
            color: #28a745;
        }

        .form-label {
            font-weight: bold;
        }

        .btn-checkout {
            background-color: #28a745;
            color: white;
        }

        .btn-checkout:hover {
            background-color: #218838;
        }

        .alert {
            margin-top: 20px;
        }

        .summary {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<?php include 'include/header.php'; ?>

<div class="checkout-container">
    <h2>Checkout</h2>

    <?php if (!empty($pesan_error)): ?>
        <div class="alert alert-danger"><?= $pesan_error ?></div>
    <?php endif; ?>

    <?php if (!isset($_POST['nama'])): ?>
        <div class="summary">
            <h5>Ringkasan Keranjang:</h5>
            <ul class="list-group">
            <?php
            $total = 0;
            if (isset($_SESSION['keranjang'])) {
                foreach ($_SESSION['keranjang'] as $item) {
                    $sub = $item['harga'] * $item['jumlah'];
                    $total += $sub;
                    echo "<li class='list-group-item d-flex justify-content-between align-items-center'>
                            {$item['nama']} x {$item['jumlah']}
                            <span>Rp " . number_format($sub, 0, ',', '.') . "</span>
                          </li>";
                }
            }
            ?>
            <li class="list-group-item d-flex justify-content-between align-items-center fw-bold">
                Total
                <span>Rp <?= number_format($total, 0, ',', '.'); ?></span>
            </li>
            </ul>
        </div>

        <form method="post">
            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Alamat Pengiriman</label>
                <textarea name="alamat" rows="3" class="form-control" required></textarea>
            </div>
            <button type="submit" class="btn btn-checkout">Kirim Pesanan</button>
        </form>
    <?php endif; ?>
</div>

<?php include 'include/footer.php'; ?>
</body>
</html>
