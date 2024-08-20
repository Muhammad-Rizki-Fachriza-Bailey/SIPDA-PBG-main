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
    
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <div class="container">
        <button class="button-back"><a href="data_arsip.php" class="back">back</a></button>
        <h2>Masukkan Data Admin Baru</h2>
        <div class="form-container">
            <?php
            include 'connect.php';

            // Deklarasi variabel untuk menyimpan data yang akan diedit
            $editMode = false;
            $editId = '';
            $editUsername = '';
            $editPassword = '';

            // Cek apakah dalam mode edit
            if (isset($_GET['edit'])) {
                $editMode = true;
                $editId = $_GET['edit'];
                
                // Ambil data admin berdasarkan ID untuk diedit
                $sql = "SELECT * FROM login_admin WHERE id='$editId'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $editUsername = $row['username'];
                    $editPassword = $row['password'];
                }
            }

            // Menyimpan atau memperbarui data admin
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $username = $_POST['username'];
                $password = $_POST['password'];

                if ($editMode) {
                    // Query untuk memperbarui data admin
                    $sql = "UPDATE login_admin SET username='$username', password='$password' WHERE id='$editId'";
                } else {
                    // Query untuk menyimpan data admin baru
                    $sql = "INSERT INTO login_admin (username, password) VALUES ('$username', '$password')";
                }

                if ($conn->query($sql) === TRUE) {
                    echo "<p class='success'>Data admin berhasil " . ($editMode ? "diperbarui" : "disimpan") . ".</p>";
                    $editMode = false; // Kembali ke mode tambah setelah update
                } else {
                    echo "<p class='error'>Error: " . $sql . "<br>" . $conn->error . "</p>";
                }
            }
            ?>
            
            <!-- Form untuk input data admin -->
            <form action="#" method="POST">
                <input type="hidden" name="id" value="<?php echo $editId; ?>">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php echo $editUsername; ?>" required>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" value="<?php echo $editPassword; ?>" required>

                <button type="submit"><?php echo $editMode ? 'Perbarui Data' : 'Simpan Data'; ?></button>
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
                <tbody id="adminData">
                    <?php
                    // Query untuk mengambil data dari tabel login_admin
                    $sql = "SELECT id, username, password FROM login_admin";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        // Menampilkan data untuk setiap baris
                        $no = 1;
                        while($row = $result->fetch_assoc()) {
                            echo "<tr id='row-" . $row["id"] . "'>";
                            echo "<td>" . $no++ . "</td>";
                            echo "<td>" . $row["username"] . "</td>";
                            echo "<td>" . $row["password"] . "</td>";
                            echo "<td>
                                    <a href='manage_admin.php?edit=" . $row["id"] . "' class='edit-btn'>Ubah</a>
                                    <button class='delete-btn' data-id='" . $row["id"] . "'>Hapus</button>
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

    <!-- Script untuk menangani penghapusan dengan AJAX -->
    <script>
        $(document).ready(function(){
            $('.delete-btn').on('click', function(){
                var id = $(this).data('id');
                var row = '#row-' + id;

                if(confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                    $.ajax({
                        url: 'delete_admin.php',
                        type: 'POST',
                        data: { id: id },
                        success: function(response) {
                            if(response == 1) {
                                $(row).fadeOut('slow', function(){
                                    $(this).remove();
                                });
                            } else {
                                alert('Penghapusan data gagal.');
                            }
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
