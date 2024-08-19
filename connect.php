<?php
$servername = "localhost";
$username = "arsipmpp"; 
$password = "4UOJTiTDbh88HXi"; 
$dbname = "arsipmpp";

// Membuat koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);

// Mengecek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
