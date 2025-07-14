<?php
session_start();
include 'include/db.php'; // Ganti sesuai dengan path sebenarnya
if (!isset($_SESSION['admin_login'])) {
  header("Location: login.php");
  exit;
}

// Proses Tambah Kategori
if (isset($_POST['add_category'])) {
  $category_name = mysqli_real_escape_string($conn, $_POST['category_name']);
  mysqli_query($conn, "INSERT INTO categories (name) VALUES ('$category_name')");
  header("Location: admin_kategori.php?success=added");
  exit;
}

// Proses Edit Kategori
if (isset($_POST['edit_category'])) {
  $category_id = $_POST['category_id'];
  $category_name = mysqli_real_escape_string($conn, $_POST['category_name']);
  mysqli_query($conn, "UPDATE categories SET name = '$category_name' WHERE id = $category_id");
  header("Location: admin_kategori.php?success=updated");
  exit;
}

// Proses Hapus Kategori
if (isset($_GET['delete_category'])) {
  $category_id = $_GET['delete_category'];
  mysqli_query($conn, "DELETE FROM categories WHERE id = $category_id");
  header("Location: admin_kategori.php?success=deleted");
  exit;
}

// Ambil Data
$result = mysqli_query($conn, "SELECT * FROM categories ORDER BY id DESC");

// Ambil Data Edit (jika ada)
$edit_mode = false;
if (isset($_GET['edit_category'])) {
  $edit_mode = true;
  $category_id = $_GET['edit_category'];
  $edit_result = mysqli_query($conn, "SELECT * FROM categories WHERE id = $category_id");
  $category_to_edit = mysqli_fetch_assoc($edit_result);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Admin - Kelola Kategori</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .container {
      margin-top: 60px;
    }
    .card-form {
      background-color: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
    }
    .table-container {
      margin-top: 40px;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="card-form">
    <h3 class="mb-4"><?= $edit_mode ? 'Edit Kategori' : 'Tambah Kategori' ?></h3>
    <?php if (isset($_GET['success'])): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        Data berhasil <?= $_GET['success'] === 'added' ? 'ditambahkan' : ($_GET['success'] === 'updated' ? 'diperbarui' : 'dihapus') ?>.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>
    <form method="post" class="row g-3">
      <?php if ($edit_mode): ?>
        <input type="hidden" name="category_id" value="<?= $category_to_edit['id'] ?>">
      <?php endif; ?>
      <div class="col-md-8">
        <input type="text" name="category_name" class="form-control" placeholder="Nama Kategori"
               value="<?= $edit_mode ? htmlspecialchars($category_to_edit['name']) : '' ?>" required>
      </div>
      <div class="col-md-4">
        <button type="submit" name="<?= $edit_mode ? 'edit_category' : 'add_category' ?>" class="btn btn-primary w-100">
          <?= $edit_mode ? 'Simpan Perubahan' : 'Tambah Kategori' ?>
        </button>
      </div>
    </form>
  </div>

  <div class="table-container">
    <h4 class="mt-5">Daftar Kategori</h4>
    <table class="table table-bordered table-striped mt-3">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>Nama Kategori</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td>
              <a href="admin_kategori.php?edit_category=<?= $row['id'] ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil-square"></i></a>
              <a href="admin_kategori.php?delete_category=<?= $row['id'] ?>" onclick="return confirm('Hapus kategori ini?')" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></a>
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
