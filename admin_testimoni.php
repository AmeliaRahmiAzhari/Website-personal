<?php
session_start();
include 'include/db.php';
if (!isset($_SESSION['admin_login'])) {
  header("Location: login.php");
  exit;
}

// Tambah testimoni
if (isset($_POST['add_testimonial'])) {
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $testimonial = mysqli_real_escape_string($conn, $_POST['testimonial']);
  mysqli_query($conn, "INSERT INTO testimonials (name, testimonial) VALUES ('$name', '$testimonial')");
  header("Location: admin_testimoni.php?success=added");
  exit;
}

// Edit testimoni
if (isset($_POST['edit_testimonial'])) {
  $id = $_POST['testimonial_id'];
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $testimonial = mysqli_real_escape_string($conn, $_POST['testimonial']);
  mysqli_query($conn, "UPDATE testimonials SET name='$name', testimonial='$testimonial' WHERE id=$id");
  header("Location: admin_testimoni.php?success=updated");
  exit;
}

// Hapus testimoni
if (isset($_GET['delete_testimonial'])) {
  $id = $_GET['delete_testimonial'];
  mysqli_query($conn, "DELETE FROM testimonials WHERE id=$id");
  header("Location: admin_testimoni.php?success=deleted");
  exit;
}

// Ambil semua testimoni
$result = mysqli_query($conn, "SELECT * FROM testimonials ORDER BY id DESC");

// Cek jika sedang mengedit
$edit_mode = false;
if (isset($_GET['edit_testimonial'])) {
  $edit_mode = true;
  $edit_id = $_GET['edit_testimonial'];
  $edit_result = mysqli_query($conn, "SELECT * FROM testimonials WHERE id = $edit_id");
  $testimonial_to_edit = mysqli_fetch_assoc($edit_result);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Admin - Kelola Testimoni</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    .container { margin-top: 60px; }
    .card-form, .card-list { background-color: #fff; padding: 25px; border-radius: 12px; box-shadow: 0 4px 16px rgba(0,0,0,0.05); }
    textarea { resize: vertical; }
  </style>
</head>
<body class="bg-light">

<div class="container">
  <div class="card-form mb-4">
    <h3 class="mb-3"><?= $edit_mode ? 'Edit Testimoni' : 'Tambah Testimoni' ?></h3>

    <?php if (isset($_GET['success'])): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        Testimoni berhasil <?= $_GET['success'] === 'added' ? 'ditambahkan' : ($_GET['success'] === 'updated' ? 'diperbarui' : 'dihapus') ?>.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <form method="post" class="row g-3">
      <?php if ($edit_mode): ?>
        <input type="hidden" name="testimonial_id" value="<?= $testimonial_to_edit['id'] ?>">
      <?php endif; ?>
      <div class="col-md-6">
        <input type="text" name="name" class="form-control" placeholder="Nama Pengguna" 
               value="<?= $edit_mode ? htmlspecialchars($testimonial_to_edit['name']) : '' ?>" required>
      </div>
      <div class="col-md-12">
        <textarea name="testimonial" class="form-control" rows="4" placeholder="Testimoni" required><?= $edit_mode ? htmlspecialchars($testimonial_to_edit['testimonial']) : '' ?></textarea>
      </div>
      <div class="col-md-12">
        <button type="submit" name="<?= $edit_mode ? 'edit_testimonial' : 'add_testimonial' ?>" class="btn btn-primary w-100">
          <?= $edit_mode ? 'Simpan Perubahan' : 'Tambah Testimoni' ?>
        </button>
      </div>
    </form>
  </div>

  <div class="card-list">
    <h4 class="mb-4">Daftar Testimoni</h4>
    <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
      <div class="card mb-3">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <h5 class="card-title mb-1"><?= htmlspecialchars($row['name']) ?></h5>
            <p class="card-text"><?= nl2br(htmlspecialchars($row['testimonial'])) ?></p>
          </div>
          <div>
            <a href="admin_testimoni.php?edit_testimonial=<?= $row['id'] ?>" class="btn btn-warning btn-sm me-2"><i class="bi bi-pencil-square"></i></a>
            <a href="admin_testimoni.php?delete_testimonial=<?= $row['id'] ?>" onclick="return confirm('Hapus testimoni ini?')" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></a>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
