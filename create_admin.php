<?php
// Simpan file ini misalnya sebagai create_admin.php lalu jalankan sekali
include 'include/db.php';

$username = 'admin';
$password = password_hash('admin123', PASSWORD_DEFAULT); // ubah 'admin123' ke password yang kamu mau

$sql = "INSERT INTO admin (username, password) VALUES ('$username', '$password')";
mysqli_query($conn, $sql);
echo "Admin berhasil dibuat!";
?>
