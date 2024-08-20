<?php
session_start();
require 'connect.php';

if (!isset($_GET['id'])) {
    die("ID not provided.");
}

$id = $conn->real_escape_string($_GET['id']);

// Update status to 'deleted' for the corresponding records
$sql = "UPDATE surat_imb s
        JOIN bangunan b ON s.id_bangunan = b.id_bangunan
        JOIN pemohon p ON b.id_pemohon = p.id_pemohon
        SET s.status = 'deleted', b.status = 'deleted', p.status = 'deleted'
        WHERE b.id_bangunan = '$id'";

if ($conn->query($sql) === TRUE) {
    echo "Record marked as deleted successfully";
    header("Location: data_arsip.php");
} else {
    echo "Error updating record: " . $conn->error;
}

$conn->close();
?>
