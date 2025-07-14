<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "nusantaraku"; // ganti sesuai nama database kamu

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
  die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>
