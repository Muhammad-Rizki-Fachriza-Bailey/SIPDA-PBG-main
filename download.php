<?php
require 'connect.php';

// Ensure id_bangunan is provided
if (!isset($_GET['id'])) {
    die("ID not provided.");
}

$id = $conn->real_escape_string($_GET['id']);

// Query to fetch file path from database
$sql = "SELECT file_sk FROM bangunan WHERE id_bangunan = '$id'";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $file = $result->fetch_assoc();
    $file_path = $file['file_sk'];

    if (file_exists($file_path)) {
        // Prepare headers for file download
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_path));
        
        // Output file for download
        readfile($file_path);
        exit;
    } else {
        die("File not found.");
    }
} else {
    die("No records found.");
}
?>
