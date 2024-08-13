<?php
include 'connect.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Query untuk menghapus data admin berdasarkan ID
    $sql = "DELETE FROM login_admin WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo 1; // Sukses
    } else {
        echo 0; // Gagal
    }

    $conn->close();
}
?>
