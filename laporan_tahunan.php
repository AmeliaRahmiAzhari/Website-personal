<?php
session_start();
include 'include/db.php';

if (!isset($_SESSION['admin_login'])) {
    header("Location: login.php");
    exit;
}

$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');
$pimpinan = isset($_GET['pimpinan']) ? htmlspecialchars($_GET['pimpinan']) : '';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Laporan Tahunan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Times New Roman', serif;
            font-size: 14px;
            color: #000;
        }
        .no-print {
            display: block;
        }
        @media print {
            .no-print, form, .btn, .d-flex {
                display: none !important;
            }
            body {
                margin: 0;
                padding: 0;
            }
            .container {
                width: 100%;
            }
        }
        .laporan-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .laporan-header h2, .laporan-header h5 {
            margin: 0;
        }
        .ttd {
            margin-top: 80px;
            width: 100%;
            text-align: right;
            padding-right: 80px;
        }
        table.table-bordered th, table.table-bordered td {
            border: 1px solid #000 !important;
        }
        hr {
            border-top: 2px solid #000;
        }
    </style>
    <script>
        function printReport() {
            window.print();
        }
    </script>
</head>
<body class="container pt-4">

<div class="no-print mb-4">
    <form method="GET" class="row g-3">
        <div class="col-md-4">
            <label>Tahun:</label>
            <input type="number" name="tahun" class="form-control" value="<?= $tahun ?>">
        </div>
        <div class="col-md-5">
            <label>Nama Pimpinan:</label>
            <input type="text" name="pimpinan" class="form-control" value="<?= $pimpinan ?>">
        </div>
        <div class="col-md-3 d-flex align-items-end gap-2">
            <button type="submit" class="btn btn-primary">Tampilkan</button>
            <button type="button" onclick="printReport()" class="btn btn-success">Cetak</button>
        </div>
    </form>
</div>

<div class="laporan-header">
    <h2>LAPORAN TAHUNAN</h2>
    <h5>Transaksi & Keuangan Tahun <?= $tahun ?></h5>
    <hr>
</div>

<?php
$sql = "SELECT nama_pelanggan, total, tanggal FROM transaksi WHERE YEAR(tanggal)=?";
$stmt = $conn->prepare($sql);
$total_pemasukan = 0;

if ($stmt) {
    $stmt->bind_param("s", $tahun);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<h5>📅 Data Transaksi</h5>";
    if ($result->num_rows > 0) {
        echo "<table class='table table-bordered mt-3'>
                <thead><tr><th>No</th><th>Nama Pelanggan</th><th>Total (Rp)</th><th>Tanggal</th></tr></thead><tbody>";
        $no = 1;
        while ($row = $result->fetch_assoc()) {
            $total_pemasukan += $row['total'];
            echo "<tr>
                    <td>$no</td>
                    <td>{$row['nama_pelanggan']}</td>
                    <td>" . number_format($row['total'], 0, ',', '.') . "</td>
                    <td>{$row['tanggal']}</td>
                  </tr>";
            $no++;
        }
        echo "</tbody></table>";
    } else {
        echo "<p><em>Tidak ada data transaksi tahun ini.</em></p>";
    }
}

$sql2 = "SELECT p.name AS nama, SUM(d.jumlah) AS total_terjual
         FROM pesanan_detail d
         JOIN pesanan t ON d.pesanan_id = t.id
         JOIN products p ON d.produk_id = p.id
         WHERE YEAR(t.tanggal) = ?
         GROUP BY d.produk_id";
$stmt2 = $conn->prepare($sql2);

echo "<h5 class='mt-5'>🛒 Produk Terjual</h5>";
if ($stmt2) {
    $stmt2->bind_param("s", $tahun);
    $stmt2->execute();
    $result2 = $stmt2->get_result();

    if ($result2->num_rows > 0) {
        echo "<table class='table table-bordered'>
                <thead><tr><th>No</th><th>Nama Produk</th><th>Jumlah Terjual</th></tr></thead><tbody>";
        $no = 1;
        while ($row = $result2->fetch_assoc()) {
            echo "<tr>
                    <td>$no</td>
                    <td>{$row['nama']}</td>
                    <td>{$row['total_terjual']}</td>
                  </tr>";
            $no++;
        }
        echo "</tbody></table>";
    } else {
        echo "<p><em>Tidak ada produk terjual tahun ini.</em></p>";
    }
}

$sql3 = "SELECT SUM(jumlah) AS total_pengeluaran FROM pengeluaran WHERE YEAR(tanggal) = ?";
$stmt3 = $conn->prepare($sql3);
$total_pengeluaran = 0;
if ($stmt3) {
    $stmt3->bind_param("s", $tahun);
    $stmt3->execute();
    $result3 = $stmt3->get_result();
    $row3 = $result3->fetch_assoc();
    $total_pengeluaran = $row3['total_pengeluaran'] ?? 0;
}

$keuntungan = $total_pemasukan - $total_pengeluaran;

echo "<h5 class='mt-5'>💰 Ringkasan Keuangan</h5>";
echo "<table class='table table-bordered w-50'>
        <tr><th>Total Pemasukan</th><td>Rp" . number_format($total_pemasukan, 0, ',', '.') . "</td></tr>
        <tr><th>Total Pengeluaran</th><td>Rp" . number_format($total_pengeluaran, 0, ',', '.') . "</td></tr>
        <tr><th>Keuntungan Bersih</th><td>Rp" . number_format($keuntungan, 0, ',', '.') . "</td></tr>
      </table>";

if ($pimpinan) {
    echo "<div class='ttd'>
            <p>Padang, " . date('d F Y') . "</p>
            <p><strong>$pimpinan</strong></p>
            <br><br><br>
            __________________
          </div>";
}
?>
</body>
</html>
