<?php
session_start();
require 'connect.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if ID is provided
if (!isset($_GET['id'])) {
    die("ID not provided.");
}

$id = $conn->real_escape_string($_GET['id']);
$current_file = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_pemohon = $conn->real_escape_string($_POST['id_pemohon']);
    $ttl = $conn->real_escape_string($_POST['tempat_dan_tanggal_lahir']);
    $alamat = $conn->real_escape_string($_POST['alamat']);
    $jenis_kelamin = $conn->real_escape_string($_POST['jenis_kelamin']);
    $usia = $conn->real_escape_string($_POST['usia']);
    $pekerjaan = $conn->real_escape_string($_POST['pekerjaan']);
    $telepon = $conn->real_escape_string($_POST['no_telp']);

    // Get current file path from the database
    $sql_get_file = "SELECT file_formulir_permohonan FROM pemohon WHERE id_pemohon='$id_pemohon'";
    $result_get_file = $conn->query($sql_get_file);
    if ($result_get_file->num_rows > 0) {
        $current_file = $result_get_file->fetch_assoc()['file_formulir_permohonan'];
    }

    // Handle file upload if provided
    $file_pdf_path = $current_file;
    if (!empty($_FILES['file_pdf']['name'])) {
        $file_pdf = $_FILES['file_pdf']['name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($file_pdf);

        if (move_uploaded_file($_FILES['file_pdf']['tmp_name'], $target_file)) {
            $file_pdf_path = $target_file;
        } else {
            die("Sorry, there was an error uploading your file.");
        }
    }

    // Update data in pemohon table
    $sql_pemohon = "UPDATE pemohon SET 
                        tempat_dan_tanggal_lahir='$ttl', 
                        jenis_kelamin='$jenis_kelamin', 
                        usia='$usia', 
                        pekerjaan='$pekerjaan', 
                        alamat='$alamat', 
                        no_telp='$telepon'" . 
                        ($file_pdf_path ? ", file_formulir_permohonan='$file_pdf_path'" : "") . " 
                    WHERE id_pemohon='$id_pemohon'";

    if ($conn->query($sql_pemohon) === TRUE) {
        header("Location: index_data_arsip.php");
        exit();
    } else {
        echo "Error: " . $sql_pemohon . "<br>" . $conn->error;
    }
}

// Query to fetch data for editing
$sql = "SELECT 
            p.id_pemohon, p.nama_pemohon, p.tempat_dan_tanggal_lahir, p.jenis_kelamin, p.usia, p.pekerjaan, p.alamat, p.no_telp, p.file_formulir_permohonan
        FROM pemohon p
        WHERE p.id_pemohon = '$id'";

$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

$data = $result->fetch_assoc();

if (!$data) {
    die("No records found.");
}

$sql_pemohon = "SELECT id_pemohon, nama_pemohon FROM pemohon";
$result_pemohon = $conn->query($sql_pemohon);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Data Identitas Diri | SIPDA PBG DPMPTSP</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="./css/edit_style.css" />
</head>
<body>
    <div class="sidebar" id="sidebar">
        <img src="asset/Logo DPMPTSP.png" alt="DPMPTSP" class="dpmptsp" />
        <hr />
        <ul>
            <li>
                <a href="index_dashboard.php" class="dashboard"><img src="asset/dashboard (1).png" alt="dashboard-icon" class="dashboard-icon" />Dashboard</a>
                <hr />
            </li>
            <li>
                <a href="index_data_arsip.php" class="arsip"><img src="asset/archive.png" alt="arsip-icon" class="archive-icon" />Data Arsip</a>
                <hr />
            </li>
        </ul>
        <a href="login.php"><button>LogOut</button></a>
    </div>

    <div class="navbar">
        <header>
            <img src="asset/menu.png" alt="menu-icon" class="menu-icon" id="menu-icon" />
            <a href="index_dashboard.php" class="title"><h2>SIPDA - PBG</h2></a>
        </header>
    </div>

    <div class="main-content">
        <h1 class="title-data-arsip">Edit Data Identitas Diri</h1>
        <button class="button-back"><a href="index_data_arsip.php" class="back">back</a></button>
        
        <form class="edit-form" action="update_data_arsip.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
            <fieldset>
                <legend>Identitas Diri</legend>
                <label for="id_pemohon">Nama:</label>
                <select id="id_pemohon" name="id_pemohon" required>
                    <option value="">Pilih Nama</option>
                    <?php
                    if ($result_pemohon->num_rows > 0) {
                        while ($row = $result_pemohon->fetch_assoc()) {
                            $selected = ($row['id_pemohon'] == $data['id_pemohon']) ? 'selected' : '';
                            echo "<option value='" . $row['id_pemohon'] . "' $selected>" . $row['nama_pemohon'] . "</option>";
                        }
                    } else {
                        echo "<option value=''>No data available</option>";
                    }
                    ?>
                </select>

                <label for="tempat_dan_tanggal_lahir">Tempat dan Tanggal Lahir:</label>
                <input type="text" id="tempat_dan_tanggal_lahir" name="tempat_dan_tanggal_lahir" value="<?php echo htmlspecialchars($data['tempat_dan_tanggal_lahir']); ?>" required />

                <label for="alamat">Alamat:</label>
                <input type="text" id="alamat" name="alamat" value="<?php echo htmlspecialchars($data['alamat']); ?>" required />

                <label for="jenis_kelamin">Jenis Kelamin:</label>
                <select id="jenis_kelamin" name="jenis_kelamin" required>
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="Laki-laki" <?php echo ($data['jenis_kelamin'] == 'Laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
                    <option value="Perempuan" <?php echo ($data['jenis_kelamin'] == 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                </select>

                <label for="usia">Usia:</label>
                <input type="number" id="usia" name="usia" value="<?php echo htmlspecialchars($data['usia']); ?>" required />

                <label for="pekerjaan">Pekerjaan:</label>
                <input type="text" id="pekerjaan" name="pekerjaan" value="<?php echo htmlspecialchars($data['pekerjaan']); ?>" required />

                <label for="no_telp">Nomor Telepon:</label>
                <input type="tel" id="no_telp" name="no_telp" value="<?php echo htmlspecialchars($data['no_telp']); ?>" required />

                <label for="file_pdf">File PDF Saat Ini:</label>
                <?php
                if ($data['file_formulir_permohonan']) {
                    echo "<p>Current file: <a href='" . $data['file_formulir_permohonan'] . "' target='_blank'>View File</a></p>";
                } else {
                    echo "<p>No file available</p>";
                }
                ?>
                <input type="file" id="file_pdf" name="file_pdf" accept="application/pdf" />
            </fieldset>

            <div class="form-buttons">
                <button type="button" class="btn-batal" id="btn-cancel">Batal</button>
                <button type="submit" class="btn-simpan" id="btn-save">Simpan</button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('btn-cancel').addEventListener('click', function() {
            window.location.href = 'index_data_arsip.php';
        });
    </script>

    <script src="./script/menu_fuction.js"></script>
</body>
</html>
