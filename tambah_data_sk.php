<?php
session_start();
require 'connect.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $nomor_surat = $conn->real_escape_string($_POST['nomor_surat']);
    $tanggal = $conn->real_escape_string($_POST['tanggal']);
    $tahun = $conn->real_escape_string($_POST['tahun']);
    $jenis_bangunan = $conn->real_escape_string($_POST['jenis_bangunan']);
    $jumlah_unit = $conn->real_escape_string($_POST['jumlah_unit']);
    $jumlah_lantai = $conn->real_escape_string($_POST['jumlah_lantai']);
    $lokasi_bangunan = $conn->real_escape_string($_POST['lokasi_bangunan']);
    $kecamatan = $conn->real_escape_string($_POST['kecamatan']);
    $kelurahan = $conn->real_escape_string($_POST['kelurahan']);
    $id_pemohon = $conn->real_escape_string($_POST['id_pemohon']);

    // Handle file upload
    $file_pdf = $_FILES['file_pdf']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($file_pdf);

    if (move_uploaded_file($_FILES['file_pdf']['tmp_name'], $target_file)) {
        $file_pdf_path = $target_file;
    } else {
        die("Sorry, there was an error uploading your file.");
    }

    // Simpan data ke tabel bangunan
    $sql_bangunan = "INSERT INTO bangunan (jenis_bangunan, lokasi_bangunan, jumlah_unit, jumlah_lantai, kecamatan, kelurahan, id_pemohon, file_sk)
                     VALUES ('$jenis_bangunan', '$lokasi_bangunan', '$jumlah_unit', '$jumlah_lantai', '$kecamatan', '$kelurahan', '$id_pemohon', '$file_pdf_path')";

    if ($conn->query($sql_bangunan) === TRUE) {
        echo "Data inserted into bangunan<br>";
        $id_bangunan = $conn->insert_id;

        // Simpan data ke tabel surat_imb
        $sql_surat = "INSERT INTO surat_imb (nomor_sk, id_pemohon, id_bangunan, tanggal, tahun)
                      VALUES ('$nomor_surat', '$id_pemohon', '$id_bangunan', '$tanggal', '$tahun')";

        if ($conn->query($sql_surat) === TRUE) {
            echo "Data inserted into surat_imb<br>";
            header("Location: index_data_arsip.php");
            exit();
        } else {
            echo "Error: " . $sql_surat . "<br>" . $conn->error;
        }
    } else {
        echo "Error: " . $sql_bangunan . "<br>" . $conn->error;
    }
}

// Query untuk mendapatkan data pemohon
$sql_pemohon = "SELECT id_pemohon, nama_pemohon FROM pemohon";
$result_pemohon = $conn->query($sql_pemohon);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Arsip SK</title>

    <!-- my fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet" />
    
    <!-- my style -->
    <link rel="stylesheet" href="./css/tambah_style.css" />
</head>
<body>
    <!-- sidebar start -->
    <div class="sidebar" id="sidebar">
        <img src="asset/Logo DPMPTSP.png" alt="DPMPTSP" class="dpmptsp" />
        <hr>
        <ul>
            <li>
                <a href="index_dashboard.php" class="dashboard"><img src="asset/dashboard (1).png" alt="dashboard-icon" class="dashboard-icon"/>Dashboard</a><hr>
            </li>
            <li>
                <a href="index_data_arsip.php" class="arsip"><img src="asset/archive.png" alt="arsip-icon" class="archive-icon"/>Data Arsip</a><hr>
            </li>
        </ul>
        <a href="login.php"><button>LogOut</button></a>
    </div>
    <!-- sidebar end -->

    <!-- navbar start -->
    <div class="navbar">
        <header>
            <img src="asset/menu.png" alt="menu-icon" class="menu-icon" id="menu-icon"/>
            <a href="index_dashboard.php" class="title"><h2>SIPDA - PBG</h2></a>
        </header>
    </div>
    <!-- navbar end -->

    <!-- main content start -->
    <div class="main-content">
        <h1 class="title-data-arsip">Tambah Data Arsip SK</h1>
  
        <!-- Form start -->
        <form class="edit-form" action="tambah_data_sk.php" method="post" enctype="multipart/form-data">
            <fieldset>
                <legend>Identitas Surat</legend>
                
                <label for="id_pemohon">Nama:</label>
                <select id="id_pemohon" name="id_pemohon" required>
                    <option value="">Pilih Nama</option>
                    <?php
                    if ($result_pemohon->num_rows > 0) {
                        while ($row = $result_pemohon->fetch_assoc()) {
                            echo "<option value='" . $row['id_pemohon'] . "'>" . $row['nama_pemohon'] . "</option>";
                        }
                    } else {
                        echo "<option value=''>No data available</option>";
                    }
                    ?>
                </select>
                
                <label for="nomor_surat">Nomor SK:</label>
                <input type="text" id="nomor_surat" name="nomor_surat" required />
  
                <label for="tanggal">Tanggal:</label>
                <input type="date" id="tanggal" name="tanggal" required />

                <label for="tahun">Tahun:</label>
                <input type="text" id="tahun" name="tahun" required />

                <label for="jenis_bangunan">Jenis Bangunan:</label>
                <input type="text" id="jenis_bangunan" name="jenis_bangunan" required />
  
                <label for="jumlah_unit">Jumlah Unit:</label>
                <input type="number" id="jumlah_unit" name="jumlah_unit" required />
  
                <label for="jumlah_lantai">Jumlah Lantai:</label>
                <input type="number" id="jumlah_lantai" name="jumlah_lantai" required />
  
                <label for="lokasi_bangunan">Lokasi Bangunan:</label>
                <input type="text" id="lokasi_bangunan" name="lokasi_bangunan" required />
  
                <label for="kecamatan">Kecamatan:</label>
                <input type="text" id="kecamatan" name="kecamatan" required />
  
                <label for="kelurahan">Kelurahan:</label>
                <input type="text" id="kelurahan" name="kelurahan" required />
            </fieldset>

            <fieldset>
                <legend>Upload File PDF</legend>
                <label for="file_pdf">File PDF:</label>
                <input type="file" id="file_pdf" name="file_pdf" accept="application/pdf" required />
            </fieldset>
  
            <div class="form-buttons">
                <button type="button" class="btn-batal" id="btn-cancel">Batal</button>
                <button type="submit" class="btn-simpan" id="btn-save">Simpan</button>
            </div>
        </form>
        <!-- Form end -->
    </div>
    <!-- main content end -->

    <!-- my javascript -->
    <script src="./script/tambah_script.js"></script>
</body>
</html>
