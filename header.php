
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">Makanan Nusantara</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php#kategori">Kategori</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php#unggulan">Unggulan</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php#tentang">Tentang Kami</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php#kontak">Kontak</a></li>
        <li class="nav-item"><a class="nav-link" href="keranjang.php">🛒 Keranjang</a></li>
      </ul>
    </div>
  </div>
</nav>
<?php if (isset($_SESSION['user_nama'])): ?>
  <div class="text-end me-3">
    Halo, <?= $_SESSION['user_nama'] ?> | <a href="logout.php">Logout</a>
  </div>
<?php endif; ?>

