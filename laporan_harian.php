<?php
session_start();
include 'include/db.php';

if (!isset($_SESSION['admin_login'])) {
    header("Location: login.php");
    exit;
}

$tanggal = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');
$pimpinan = isset($_GET['pimpinan']) ? htmlspecialchars($_GET['pimpinan']) : '';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transaksi Harian</title>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }
        .report-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        @media print {
            #form-input, button, .navbar, .badge-emoji { display: none; }
            body { background: white !important; }
        }
        .signature {
            margin-top: 50px;
        }
        .signature p {
            margin-bottom: 4px;
        }
    </style>
    <script>
        function printReport() {
            window.print();
        }
    </script>
</head>
<body class="container py-5">

<div class="report-card">
    <h2 class="text-center mb-4">📋 <span class="badge bg-secondary badge-emoji">Laporan Transaksi Harian</span></h2>

    <form method="GET" id="form-input" class="mb-4">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">📅 Pilih Tanggal:</label>
                <input type="date" name="tanggal" class="form-control" value="<?php echo $tanggal; ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">👤 Nama Pimpinan:</label>
                <input type="text" name="pimpinan" class="form-control" value="<?php echo $pimpinan; ?>">
            </div>
            <div class="col-md-4 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-outline-primary">🔍 Tampilkan</button>
                <button type="button" onclick="printReport()" class="btn btn-success">🖨️ Cetak</button>
            </div>
        </div>
    </form>

    <?php
    $sql = "SELECT nama_pelanggan, total, tanggal FROM transaksi WHERE tanggal = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo "<div class='alert alert-danger mt-3'>❌ Query gagal: " . $conn->error . "</div>";
    } else {
        $stmt->bind_param("s", $tanggal);
        $stmt->execute();
        $result = $stmt->get_result();

        echo "<h5 class='mt-5'>🗓️ Transaksi pada <strong>" . date('d M Y', strtotime($tanggal)) . "</strong></h5>";

        if ($result->num_rows > 0) {
            echo "<table class='table table-striped table-hover mt-3'>";
            echo "<thead class='table-dark'><tr><th>👥 Nama Pelanggan</th><th>💵 Total (Rp)</th><th>🕒 Tanggal</th></tr></thead><tbody>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row["nama_pelanggan"]) . "</td>
                        <td>" . number_format($row["total"], 0, ',', '.') . "</td>
                        <td>" . htmlspecialchars($row["tanggal"]) . "</td>
                      </tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<div class='alert alert-warning mt-3'>⚠️ Tidak ada transaksi pada tanggal ini.</div>";
        }
    }

    if ($pimpinan) {
        echo "<div class='signature mt-5'>";
        echo "<h6>✍️ Disetujui oleh:</h6>";
        echo "<p><strong>$pimpinan</strong></p>";
        echo "<br><p>__________________________</p>";
        echo "<p><em>Tanda Tangan</em></p>";
        echo "</div>";
    }
    ?>
</div>

</body>
</html>
