<?php
require 'connect.php';

// Ambil semua pengguna dari database
$sql = "SELECT * FROM login_admin";
$result = $conn->query($sql);

if ($result === false) {
    die("Error: " . $conn->error);
}

while ($row = $result->fetch_assoc()) {
    $id = $row['id'];
    $plainPassword = $row['password'];

    // Hash password yang tersimpan
    $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

    // Update password yang tersimpan menjadi hash
    $updateSql = "UPDATE login_admin SET password='$hashedPassword' WHERE id='$id'";
    if ($conn->query($updateSql) === false) {
        die("Error updating password: " . $conn->error);
    }
}

echo "Passwords updated successfully!";
?>
