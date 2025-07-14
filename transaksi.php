<?php
session_start();
include 'include/db.php';

// Ambil data dari keranjang jika ada
if (isset($_SESSION['keranjang']) && !empty($_SESSION['keranjang'])) {
    $produkList = [];
    $jumlahList = [];
    $totalHarga = 0;

    foreach ($_SESSION['keranjang'] as $item) {
        $produkList[] = $item['nama'];
        $jumlahList[] = $item['jumlah'];
        $totalHarga += $item['harga'] * $item['jumlah'];
    }

    $_SESSION['checkout_produk'] = implode(', ', $produkList);
    $_SESSION['checkout_jumlah'] = implode(', ', $jumlahList);
    $_SESSION['checkout_total'] = $totalHarga;
}

$nama = $_SESSION['checkout_nama'] ?? '';
$produk = $_SESSION['checkout_produk'] ?? '';
$jumlah = $_SESSION['checkout_jumlah'] ?? '';
$total = $_SESSION['checkout_total'] ?? '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $produk = $_POST['produk'];
    $jumlah = $_POST['jumlah'];
    $total = $_POST['total'];
    $tanggal = date('Y-m-d');

    $query = "INSERT INTO transaksi (tanggal, nama_pelanggan, produk, jumlah, total) VALUES ('$tanggal', '$nama', '$produk', '$jumlah', '$total')";
    mysqli_query($conn, $query);

    unset($_SESSION['checkout_nama']);
    unset($_SESSION['checkout_produk']);
    unset($_SESSION['checkout_jumlah']);
    unset($_SESSION['checkout_total']);
    unset($_SESSION['keranjang']);

    echo "<div class='alert alert-success text-center mt-4 rounded shadow-sm animate__animated animate__fadeInDown'>
            🎉 Transaksi <strong>berhasil</strong> disimpan!
          </div>";
    echo "<script>setTimeout(() => window.location.href='transaksi.php', 2000);</script>";
}
?>

<?php include 'include/header.php'; ?>

<!-- CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" />

<!-- Custom Style -->
<style>
    body {
        background: #f4f4f9;
        font-family: 'Georgia', serif;
        color: #333;
        min-height: 100vh;
        padding: 20px;
    }

    .card-formal {
        background: #ffffff;
        border: 1px solid #ddd;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }

    .btn-formal {
        background-color: #006400;
        color: white;
        font-weight: bold;
        border-radius: 6px;
        padding: 10px 20px;
        transition: background-color 0.3s ease;
    }

    .btn-formal:hover {
        background-color: #004d00;
    }

    .form-label {
        font-weight: 600;
        margin-bottom: 6px;
    }

    .form-control {
        border-radius: 4px;
    }

    .card-header h4 {
        font-weight: bold;
    }
</style>

<!-- Content -->
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card card-formal animate__animated animate__fadeInUp">
                <div class="card-header bg-white border-bottom text-center pb-3 mb-4">
                    <h4 class="text-success">🧾 Formulir Transaksi</h4>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-4">
                            <label class="form-label">Nama Pelanggan</label>
                            <input type="text" name="nama" class="form-control" required value="<?= htmlspecialchars($nama); ?>">
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Nama Produk</label>
                            <input type="text" name="produk" class="form-control" required value="<?= htmlspecialchars($produk); ?>">
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Jumlah</label>
                            <input type="text" name="jumlah" class="form-control" required value="<?= htmlspecialchars($jumlah); ?>">
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Total Belanja (Rp)</label>
                            <input type="number" name="total" class="form-control" required value="<?= htmlspecialchars($total); ?>">
                        </div>
                        <button type="submit" class="btn btn-formal w-100">✅ Simpan Transaksi</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'include/footer.php'; ?>
