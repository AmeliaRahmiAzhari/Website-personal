<?php
include 'includes/koneksi.php';
session_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Katalog Produk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'include/header.php'; ?>

<div class="container mt-5">
  <h1 class="text-light">Daftar Produk</h1>
  <div class="row">
    <?php
    $produk = mysqli_query($conn, "SELECT * FROM produk");
    while ($p = mysqli_fetch_assoc($produk)) {
    ?>
      <div class="col-md-4 mb-4">
        <div class="card">
          <img src="<?= $p['gambar']; ?>" class="card-img-top" alt="<?= $p['nama']; ?>">
          <div class="card-body">
            <h5 class="card-title"><?= $p['nama']; ?></h5>
            <p class="card-text"><?= $p['deskripsi']; ?></p>
            <p class="text-primary">Rp <?= number_format($p['harga'], 0, ',', '.'); ?></p>
            <a href="tambah_keranjang.php?id=<?= $p['id']; ?>" class="btn btn-success">Tambah ke Keranjang</a>
          </div>
        </div>
      </div>
    <?php } ?>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>
