<?php
session_start();
require 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $conn->real_escape_string($_POST['nama']);
    $ttl = $conn->real_escape_string($_POST['ttl']);
    $alamat = $conn->real_escape_string($_POST['alamat']);
    $jenis_kelamin = $conn->real_escape_string($_POST['jenis_kelamin']);
    $usia = $conn->real_escape_string($_POST['usia']);
    $pekerjaan = $conn->real_escape_string($_POST['pekerjaan']);
    $telepon = $conn->real_escape_string($_POST['telepon']);
    
    // Handle file upload
    $file_pdf = $_FILES['file_pdf']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($file_pdf);

    if (move_uploaded_file($_FILES['file_pdf']['tmp_name'], $target_file)) {
        $file_pdf_path = $target_file;
    } else {
        die("Sorry, there was an error uploading your file.");
    }

    // Insert data into the database
    $sql = "INSERT INTO pemohon (nama_pemohon, tempat_dan_tanggal_lahir, jenis_kelamin, usia, pekerjaan, alamat, no_telp, file_formulir_permohonan)
            VALUES ('$nama', '$ttl', '$jenis_kelamin', '$usia', '$pekerjaan', '$alamat', '$telepon', '$file_pdf_path')";

    if ($conn->query($sql) === TRUE) {
        header("Location: index_data_arsip.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
