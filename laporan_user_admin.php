<?php
session_start();
include 'include/db.php';

// Cek login admin
if (!isset($_SESSION['admin_login'])) {
    header("Location: login.php");
    exit();
}

$pimpinan = '';
$users = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pimpinan = $_POST['pimpinan'];

    $query = "SELECT * FROM users ORDER BY id DESC";
    $result = $conn->query($query);
    if ($result) {
        $users = $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Laporan Data Pengguna - Nusantara Food</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container my-5">
  <h2 class="text-center mb-4">Laporan Data Pengguna</h2>

  <form method="POST" class="mb-4">
    <div class="row g-3 align-items-center">
      <div class="col-auto">
        <label for="pimpinan" class="col-form-label">Nama Pimpinan:</label>
      </div>
      <div class="col-auto">
        <input type="text" name="pimpinan" id="pimpinan" class="form-control" required>
      </div>
      <div class="col-auto">
        <button type="submit" class="btn btn-primary">Tampilkan Laporan</button>
      </div>
    </div>
  </form>

  <?php if (!empty($users)): ?>
    <table class="table table-bordered table-striped">
      <thead class="table-warning">
        <tr>
          <th>ID</th>
          <th>Nama</th>
          <th>Email</th>
          <th>Tanggal Registrasi</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $user): ?>
          <tr>
            <td><?= $user['id'] ?></td>
            <td><?= htmlspecialchars($user['nama']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td><?= $user['created_at'] ?? '-' ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <div class="text-end mt-4">
      <p>Padang, <?= date('d F Y') ?></p>
      <p>Pimpinan,</p>
      <br><br>
      <p><strong><?= htmlspecialchars($pimpinan) ?></strong></p>
    </div>
  <?php endif; ?>
</div>
</body>
</html>
