<?php
// Konfigurasi database
$host     = "localhost";   // biasanya localhost
$user     = "root";        // username MySQL
$password = "";            // password MySQL (kosongkan jika default XAMPP)
$database = "nusantaraku"; // nama database

// Membuat koneksi
$koneksi = mysqli_connect($host, $user, $password, $database);

// Cek koneksi
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>