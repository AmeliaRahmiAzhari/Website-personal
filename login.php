<?php
session_start();
include 'include/create_admin.php';

$error = "";

if (isset($_POST['login'])) {
  $username = mysqli_real_escape_string($conn, $_POST['username']);
  $password = $_POST['password'];

  $query = "SELECT * FROM admin WHERE username='$username'";
  $result = mysqli_query($conn, $query);
  $admin = mysqli_fetch_assoc($result);

  if ($admin && password_verify($password, $admin['password'])) {
    $_SESSION['admin_login'] = true;
    header("Location: dashboard.php");
    exit;
  } else {
    $error = "Username atau password salah!";
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login Admin | NusantaraFood</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #e0f7fa, #f1f8e9);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .login-container {
      max-width: 400px;
      margin: 100px auto;
      padding: 40px;
      background-color: #ffffff;
      border-radius: 20px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.1);
      animation: fadeIn 0.8s ease-in-out;
    }
    .login-container h2 {
      text-align: center;
      margin-bottom: 30px;
      color: #007bff;
    }
    .form-control {
      border-radius: 30px;
      padding: 12px 20px;
    }
    .btn-custom {
      width: 100%;
      padding: 12px;
      border-radius: 30px;
      background-color: #007bff;
      color: white;
      font-weight: bold;
      transition: all 0.3s ease;
    }
    .btn-custom:hover {
      background-color: #0056b3;
    }
    .error-message {
      color: red;
      text-align: center;
      margin-top: 15px;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>

  <div class="login-container">
    <h2>Login Admin</h2>
    <form method="POST" novalidate>
      <div class="mb-3">
        <input type="text" name="username" class="form-control" placeholder="Username" required>
      </div>
      <div class="mb-3">
        <input type="password" name="password" class="form-control" placeholder="Password" required>
      </div>
      <button type="submit" name="login" class="btn btn-custom">Login</button>

      <?php if ($error): ?>
        <div class="error-message"><?php echo $error; ?></div>
      <?php endif; ?>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
