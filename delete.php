<?php
session_start();
require 'connect.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Ensure id is provided
if (!isset($_GET['id'])) {
    die("ID not provided.");
}

$id_bangunan = $conn->real_escape_string($_GET['id']);

// Get the related id_pemohon
$sql_pemohon = "SELECT id_pemohon FROM bangunan WHERE id_bangunan = '$id_bangunan'";
$result_pemohon = $conn->query($sql_pemohon);

if (!$result_pemohon) {
    die("Query failed: " . $conn->error);
}

$data_pemohon = $result_pemohon->fetch_assoc();
$id_pemohon = $data_pemohon['id_pemohon'];

// Delete from surat_imb
$sql1 = "DELETE FROM surat_imb WHERE id_bangunan = '$id_bangunan'";

// Delete from bangunan
$sql2 = "DELETE FROM bangunan WHERE id_bangunan = '$id_bangunan'";

// Delete from pemohon (only if there are no other related records in `bangunan`)
$sql3 = "DELETE FROM pemohon WHERE id_pemohon = '$id_pemohon' AND NOT EXISTS (SELECT 1 FROM bangunan WHERE id_pemohon = '$id_pemohon')";

if ($conn->query($sql1) === TRUE && $conn->query($sql2) === TRUE && $conn->query($sql3) === TRUE) {
    echo "<script>
        alert('Data berhasil dihapus.');
        window.location.href='index_data_arsip.php';
    </script>";
} else {
    echo "Error deleting record: " . $conn->error;
}

$conn->close();
?>
