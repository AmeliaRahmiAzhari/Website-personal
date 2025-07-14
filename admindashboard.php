<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kelola Kategori</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-4">
    <h2 class="mb-4">Kelola Kategori</h2>

    <!-- Form Tambah Kategori -->
    <div class="card mb-4 shadow-sm">
      <div class="card-header bg-primary text-white">Tambah Kategori</div>
      <div class="card-body">
        <form method="post">
          <div class="mb-3">
            <label for="category_name" class="form-label">Nama Kategori</label>
            <input type="text" name="category_name" id="category_name" class="form-control" required>
          </div>
          <button type="submit" name="add_category" class="btn btn-primary">Tambah</button>
        </form>
      </div>
    </div>

    <!-- Daftar Kategori -->
    <div class="card shadow-sm mb-4">
      <div class="card-header bg-success text-white">Daftar Kategori</div>
      <ul class="list-group list-group-flush">
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <span><?= htmlspecialchars($row['name']) ?></span>
            <div>
              <a href="admin_kategori.php?edit_category=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
              <a href="admin_kategori.php?delete_category=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">Hapus</a>
            </div>
          </li>
        <?php endwhile; ?>
      </ul>
    </div>

    <!-- Form Edit Kategori -->
    <?php if (isset($_GET['edit_category'])): ?>
      <?php
        $category_id = $_GET['edit_category'];
        $query = "SELECT * FROM categories WHERE id = $category_id";
        $result = mysqli_query($conn, $query);
        $category = mysqli_fetch_assoc($result);
      ?>
      <div class="card shadow-sm">
        <div class="card-header bg-warning">Edit Kategori</div>
        <div class="card-body">
          <form method="post">
            <input type="hidden" name="category_id" value="<?= $category['id'] ?>">
            <div class="mb-3">
              <label for="edit_category_name" class="form-label">Nama Kategori</label>
              <input type="text" name="category_name" id="edit_category_name" class="form-control" value="<?= htmlspecialchars($category['name']) ?>" required>
            </div>
            <button type="submit" name="edit_category" class="btn btn-warning">Simpan Perubahan</button>
            <a href="admin_kategori.php" class="btn btn-secondary">Batal</a>
          </form>
        </div>
      </div>
    <?php endif; ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
