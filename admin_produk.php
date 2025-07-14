<?php
session_start();
include 'include/db.php';

if (!isset($_SESSION['admin_login'])) {
  header("Location: login.php");
  exit;
}

// Tambah produk
if (isset($_POST['add_product'])) {
  $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
  $category_id = $_POST['category_id'];
  $price = mysqli_real_escape_string($conn, $_POST['price']);
  $stock = isset($_POST['stock']) ? (int) $_POST['stock'] : 0;
  $description = mysqli_real_escape_string($conn, $_POST['description']);

  $image_name = $_FILES['image']['name'];
  $image_tmp = $_FILES['image']['tmp_name'];
  $image_path = 'uploads/' . uniqid() . '_' . basename($image_name);
  move_uploaded_file($image_tmp, $image_path);

  mysqli_query($conn, "INSERT INTO products (name, category_id, price, stock, description, image)
    VALUES ('$product_name', '$category_id', '$price', '$stock', '$description', '$image_path')");
  header("Location: admin_produk.php?success=added");
  exit;
}

// Edit produk
if (isset($_POST['edit_product'])) {
  $product_id = $_POST['product_id'];
  $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
  $category_id = $_POST['category_id'];
  $price = mysqli_real_escape_string($conn, $_POST['price']);
  $stock = isset($_POST['stock']) ? (int) $_POST['stock'] : 0;
  $description = mysqli_real_escape_string($conn, $_POST['description']);

  $image_update = '';
  if (!empty($_FILES['image']['name'])) {
    $image_name = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $image_path = 'uploads/' . uniqid() . '_' . basename($image_name);
    move_uploaded_file($image_tmp, $image_path);
    $image_update = ", image = '$image_path'";
  }

  mysqli_query($conn, "UPDATE products SET name = '$product_name', category_id = '$category_id',
    price = '$price', stock = '$stock', description = '$description' $image_update WHERE id = $product_id");
  header("Location: admin_produk.php?success=updated");
  exit;
}

// Hapus produk
if (isset($_GET['delete_product'])) {
  $product_id = $_GET['delete_product'];
  mysqli_query($conn, "DELETE FROM products WHERE id = $product_id");
  header("Location: admin_produk.php?success=deleted");
  exit;
}

// Ambil data produk
$result = mysqli_query($conn, "
  SELECT products.*, categories.name AS category_name 
  FROM products 
  LEFT JOIN categories ON products.category_id = categories.id 
  ORDER BY products.id DESC
");

// Edit mode
$edit_mode = false;
if (isset($_GET['edit_product'])) {
  $edit_mode = true;
  $product_id = $_GET['edit_product'];
  $edit_result = mysqli_query($conn, "SELECT * FROM products WHERE id = $product_id");
  $product_to_edit = mysqli_fetch_assoc($edit_result);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Admin - Kelola Produk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
    .container { margin-top: 60px; }
    .card-form {
      background-color: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
    }
    textarea { resize: vertical; }
    img.thumb { max-width: 60px; border-radius: 6px; }
  </style>
</head>
<body>

<div class="container">
  <div class="card-form">
    <h3 class="mb-4"><?= $edit_mode ? 'Edit Produk' : 'Tambah Produk' ?></h3>
    <?php if (isset($_GET['success'])): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        Produk berhasil <?= $_GET['success'] === 'added' ? 'ditambahkan' : ($_GET['success'] === 'updated' ? 'diperbarui' : 'dihapus') ?>.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>
    <form method="post" class="row g-3" enctype="multipart/form-data">
      <?php if ($edit_mode): ?>
        <input type="hidden" name="product_id" value="<?= $product_to_edit['id'] ?>">
      <?php endif; ?>
      <div class="col-md-6">
        <input type="text" name="product_name" class="form-control" placeholder="Nama Produk"
               value="<?= $edit_mode ? htmlspecialchars($product_to_edit['name']) : '' ?>" required>
      </div>
      <div class="col-md-6">
        <select name="category_id" class="form-select" required>
          <option value="">Pilih Kategori</option>
          <?php
          $category_query = mysqli_query($conn, "SELECT * FROM categories");
          while ($category = mysqli_fetch_assoc($category_query)) {
            $selected = ($edit_mode && $category['id'] == $product_to_edit['category_id']) ? 'selected' : '';
            echo "<option value='{$category['id']}' $selected>{$category['name']}</option>";
          }
          ?>
        </select>
      </div>
      <div class="col-md-6">
        <input type="text" name="price" class="form-control" placeholder="Harga (Rp)"
               value="<?= $edit_mode ? htmlspecialchars($product_to_edit['price']) : '' ?>" required>
      </div>
      <div class="col-md-6">
        <input type="number" name="stock" class="form-control" placeholder="Stok Produk"
               value="<?= $edit_mode ? htmlspecialchars($product_to_edit['stock']) : '' ?>" required>
      </div>
      <div class="col-md-12">
        <textarea name="description" class="form-control" rows="4" placeholder="Deskripsi Produk" required><?= $edit_mode ? htmlspecialchars($product_to_edit['description']) : '' ?></textarea>
      </div>
      <div class="col-md-12">
        <label for="image" class="form-label">Gambar Produk</label>
        <input type="file" name="image" class="form-control" accept="image/*" <?= $edit_mode ? '' : 'required' ?>>
        <?php if ($edit_mode && $product_to_edit['image']): ?>
          <img src="<?= $product_to_edit['image'] ?>" alt="Preview" class="thumb mt-2">
        <?php endif; ?>
      </div>
      <div class="col-md-12">
        <button type="submit" name="<?= $edit_mode ? 'edit_product' : 'add_product' ?>" class="btn btn-primary w-100">
          <?= $edit_mode ? 'Simpan Perubahan' : 'Tambah Produk' ?>
        </button>
      </div>
    </form>
  </div>

  <div class="table-responsive mt-5">
    <h4>Daftar Produk</h4>
    <table class="table table-bordered table-striped mt-3">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>Nama Produk</th>
          <th>Kategori</th>
          <th>Harga</th>
          <th>Stok</th>
          <th>Deskripsi</th>
          <th>Gambar</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['category_name']) ?></td>
            <td>Rp<?= number_format($row['price'], 0, ',', '.') ?></td>
            <td><?= htmlspecialchars($row['stock']) ?></td>
            <td><?= htmlspecialchars($row['description']) ?></td>
            <td>
              <?php if ($row['image']): ?>
                <img src="<?= htmlspecialchars($row['image']) ?>" class="thumb">
              <?php else: ?>
                <span class="text-muted">Tidak ada</span>
              <?php endif; ?>
            </td>
            <td>
              <a href="admin_produk.php?edit_product=<?= $row['id'] ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil-square"></i></a>
              <a href="admin_produk.php?delete_product=<?= $row['id'] ?>" onclick="return confirm('Hapus produk ini?')" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
