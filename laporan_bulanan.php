<?php
session_start();
include 'include/db.php';

if (!isset($_SESSION['admin_login'])) {
    header("Location: login.php");
    exit;
}

$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');
$pimpinan = isset($_GET['pimpinan']) ? htmlspecialchars($_GET['pimpinan']) : '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Bulanan & Stok Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: #f0f8ff;
            padding: 40px 0;
        }

        .container-report {
            max-width: 1000px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        h2, h4 {
            text-align: center;
            margin-bottom: 20px;
            color: #00796b;
        }

        label {
            font-weight: bold;
        }

        .btn-custom {
            border-radius: 30px;
            padding: 6px 14px;
            font-size: 0.875rem;
        }

        .table thead {
            background-color: #b2dfdb;
            color: #004d40;
        }

        .ttd {
            margin-top: 60px;
            text-align: right;
        }

        @media print {
            #form-filter, .btn, nav { display: none; }
            body { background: white; padding: 0; }
        }
    </style>
</head>
<body>

<div class="container-report">
    <h2><i class="bi bi-file-earmark-text-fill"></i> Laporan Bulanan & Stok Produk</h2>

    <form method="GET" class="row g-3 mb-4" id="form-filter">
        <div class="col-md-3">
            <label>Bulan:</label>
            <select name="bulan" class="form-select">
                <?php for ($i = 1; $i <= 12; $i++): 
                    $val = str_pad($i, 2, '0', STR_PAD_LEFT);
                    $sel = ($bulan == $val) ? 'selected' : '';
                    echo "<option value='$val' $sel>$val - " . date('F', mktime(0,0,0,$i,1)) . "</option>";
                endfor; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label>Tahun:</label>
            <input type="number" name="tahun" class="form-control" value="<?= $tahun ?>">
        </div>
        <div class="col-md-4">
            <label>Nama Pimpinan:</label>
            <input type="text" name="pimpinan" class="form-control" value="<?= $pimpinan ?>">
        </div>
        <div class="col-md-2 d-flex align-items-end gap-1">
            <button type="submit" class="btn btn-primary btn-sm btn-custom w-100"><i class="bi bi-eye-fill"></i> Tampilkan</button>
            <button type="button" onclick="window.print()" class="btn btn-success btn-sm btn-custom w-100"><i class="bi bi-printer-fill"></i> Cetak</button>
        </div>
    </form>

    <!-- Transaksi Bulanan -->
    <h4>📆 Transaksi Bulan <?= date('F', mktime(0, 0, 0, $bulan, 1)) ?> <?= $tahun ?></h4>
    <?php
    $sql = "SELECT nama_pelanggan, total, tanggal FROM transaksi WHERE MONTH(tanggal)=? AND YEAR(tanggal)=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $bulan, $tahun);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0):
        echo "<table class='table table-bordered mt-3'>
                <thead><tr><th>Nama Pelanggan</th><th>Total (Rp)</th><th>Tanggal</th></tr></thead><tbody>";
        while ($row = $result->fetch_assoc()):
            echo "<tr>
                    <td>" . htmlspecialchars($row['nama_pelanggan']) . "</td>
                    <td>Rp " . number_format($row['total'], 0, ',', '.') . "</td>
                    <td>" . date('d M Y', strtotime($row['tanggal'])) . "</td>
                  </tr>";
        endwhile;
        echo "</tbody></table>";
    else:
        echo "<div class='alert alert-warning'>⚠️ Tidak ada transaksi pada periode ini.</div>";
    endif;
    ?>

    <!-- Laporan Stok -->
    <h4 class="mt-5">📦 Laporan Stok Produk Saat Ini</h4>
    <?php
    $stok = mysqli_query($conn, "SELECT products.*, categories.name AS category_name 
                                 FROM products 
                                 LEFT JOIN categories ON products.category_id = categories.id 
                                 ORDER BY products.name ASC");

    if (mysqli_num_rows($stok) > 0):
        echo "<table class='table table-bordered mt-3'>
                <thead><tr><th>Nama Produk</th><th>Kategori</th><th>Harga</th><th>Stok</th></tr></thead><tbody>";
        while ($row = mysqli_fetch_assoc($stok)):
            echo "<tr>
                    <td>" . htmlspecialchars($row['name']) . "</td>
                    <td>" . htmlspecialchars($row['category_name']) . "</td>
                    <td>Rp " . number_format($row['price'], 0, ',', '.') . "</td>
                    <td>" . ($row['stock'] ?? 0) . "</td>
                  </tr>";
        endwhile;
        echo "</tbody></table>";
    else:
        echo "<div class='alert alert-warning'>⚠️ Tidak ada data produk.</div>";
    endif;
    ?>

    <!-- Tanda Tangan -->
    <?php if ($pimpinan): ?>
        <div class="ttd mt-5">
            <p>Disetujui oleh:</p><br><br>
            <p><strong><?= $pimpinan ?></strong></p>
            <p>______________________________</p>
            <p><em>Tanda Tangan</em></p>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
