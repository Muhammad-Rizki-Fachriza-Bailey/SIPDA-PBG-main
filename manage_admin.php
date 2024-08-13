<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Admin SIPDA-PBG</title>

    <!-- my fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet" />

    <!-- My style -->
    <link rel="stylesheet" href="./css/manage_admin.css">
</head>
<body>
    <div class="container">
        <h2>Masukkan Data Admin Baru</h2>
        <div class="form-container">
            <form action="#" method="POST">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>

                <button type="submit">Simpan Data</button>
            </form>
        </div>

        <div class="table-container">
            <h3>Data Admin SIPDA-PBG</h3>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Menghubungkan ke database
                    include 'connect.php';

                    // Menyimpan data admin baru jika form disubmit
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        $username = $_POST['username'];
                        $password = $_POST['password'];

                        // Query untuk menyimpan data ke tabel login_admin
                        $sql = "INSERT INTO login_admin (username, password) VALUES ('$username', '$password')";

                        if ($conn->query($sql) === TRUE) {
                            echo "Data admin berhasil disimpan";
                        } else {
                            echo "Error: " . $sql . "<br>" . $conn->error;
                        }
                    }

                    // Query untuk mengambil data dari tabel login_admin
                    $sql = "SELECT id, username, password FROM login_admin";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        // Menampilkan data untuk setiap baris
                        $no = 1;
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $no++ . "</td>";
                            echo "<td>" . $row["username"] . "</td>";
                            echo "<td>" . $row["password"] . "</td>";
                            echo "<td>
                                    <button class='edit-btn'>Ubah</button>
                                    <button class='delete-btn'>Hapus</button>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>Tidak ada data admin.</td></tr>";
                    }

                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
