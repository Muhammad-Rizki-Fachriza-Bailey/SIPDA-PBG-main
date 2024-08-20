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

    // Get current file path from the database
    $sql_get_file = "SELECT b.file_sk FROM bangunan b INNER JOIN surat_imb s ON b.id_bangunan = s.id_bangunan WHERE s.id_bangunan = '$id'";
    $result_get_file = $conn->query($sql_get_file);
    if ($result_get_file->num_rows > 0) {
        $current_file = $result_get_file->fetch_assoc()['file_sk'];
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

    // Update data in bangunan table
    $sql_bangunan = "UPDATE bangunan SET 
                        jenis_bangunan='$jenis_bangunan', 
                        lokasi_bangunan='$lokasi_bangunan', 
                        jumlah_unit='$jumlah_unit', 
                        jumlah_lantai='$jumlah_lantai', 
                        kecamatan='$kecamatan', 
                        kelurahan='$kelurahan', 
                        id_pemohon='$id_pemohon', 
                        file_sk='$file_pdf_path'
                     WHERE id_bangunan=(SELECT id_bangunan FROM surat_imb WHERE id_bangunan='$id')";

    if ($conn->query($sql_bangunan) === TRUE) {
        // Update data in surat_imb table
        $sql_surat = "UPDATE surat_imb SET 
                        nomor_sk='$nomor_surat', 
                        id_pemohon='$id_pemohon', 
                        tanggal='$tanggal', 
                        tahun='$tahun' 
                     WHERE id_bangunan='$id'";

        if ($conn->query($sql_surat) === TRUE) {
            header("Location: data_arsip.php");
            exit();
        } else {
            echo "Error: " . $sql_surat . "<br>" . $conn->error;
        }
    } else {
        echo "Error: " . $sql_bangunan . "<br>" . $conn->error;
    }
}

// Query to fetch data for editing
$sql = "SELECT 
            s.nomor_sk, s.tanggal, s.tahun, 
            b.jenis_bangunan, b.lokasi_bangunan, b.jumlah_unit, b.jumlah_lantai, b.kecamatan, b.kelurahan, b.file_sk,
            p.id_pemohon, p.nama_pemohon
        FROM surat_imb s
        JOIN bangunan b ON s.id_bangunan = b.id_bangunan
        JOIN pemohon p ON s.id_pemohon = p.id_pemohon
        WHERE s.id_bangunan = '$id'";

$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

$data = $result->fetch_assoc();

if (!$data) {
    die("No records found.");
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
    <title>Edit Data Arsip SK</title>

    <!-- my fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet" />
    
    <!-- my style -->
    <link rel="stylesheet" href="./css/edit_style.css" />
</head>
<body>
    <!-- sidebar start -->
    <div class="sidebar" id="sidebar">
        <img src="asset/Logo DPMPTSP.png" alt="DPMPTSP" class="dpmptsp" />
        <hr>
        <ul>
            <li>
                <a href="dashboard.php" class="dashboard"><img src="asset/dashboard (1).png" alt="dashboard-icon" class="dashboard-icon"/>Dashboard</a><hr>
            </li>
            <li>
                <a href="data_arsip.php" class="arsip"><img src="asset/archive.png" alt="arsip-icon" class="archive-icon"/>Data Arsip</a><hr>
            </li>
        </ul>
        <a href="index.php"><button>LogOut</button></a>
    </div>
    <!-- sidebar end -->

    <!-- navbar start -->
    <div class="navbar">
        <header>
            <img src="asset/menu.png" alt="menu-icon" class="menu-icon" id="menu-icon"/>
            <a href="dashboard.php" class="title"><h2>SIPDA - PBG</h2></a>
        </header>
    </div>
    <!-- navbar end -->

    <!-- main content start -->
    <div class="main-content">
        <h1 class="title-data-arsip">Edit Data Arsip SK</h1>
        <button class="button-back"><a href="data_arsip.php" class="back">back</a></button>
        
        <!-- Form start -->
        <form class="edit-form" action="edit_data_sk.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">
            <fieldset>
                <legend>Identitas Surat</legend>
                
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
                
                <label for="nomor_surat">Nomor SK:</label>
                <input type="text" id="nomor_surat" name="nomor_surat" value="<?php echo htmlspecialchars($data['nomor_sk']); ?>" required />
  
                <label for="tanggal">Tanggal:</label>
                <input type="date" id="tanggal" name="tanggal" value="<?php echo htmlspecialchars($data['tanggal']); ?>" required />

                <label for="tahun">Tahun:</label>
                <input type="text" id="tahun" name="tahun" value="<?php echo htmlspecialchars($data['tahun']); ?>" required />

                <label for="jenis_bangunan">Jenis Bangunan:</label>
                <input type="text" id="jenis_bangunan" name="jenis_bangunan" value="<?php echo htmlspecialchars($data['jenis_bangunan']); ?>" required />
  
                <label for="jumlah_unit">Jumlah Unit:</label>
                <input type="number" id="jumlah_unit" name="jumlah_unit" value="<?php echo htmlspecialchars($data['jumlah_unit']); ?>" required />
  
                <label for="jumlah_lantai">Jumlah Lantai:</label>
                <input type="number" id="jumlah_lantai" name="jumlah_lantai" value="<?php echo htmlspecialchars($data['jumlah_lantai']); ?>" required />
  
                <label for="lokasi_bangunan">Lokasi Bangunan:</label>
                <input type="text" id="lokasi_bangunan" name="lokasi_bangunan" value="<?php echo htmlspecialchars($data['lokasi_bangunan']); ?>" required />
  
                <label for="kecamatan">Kecamatan:</label>
                <input type="text" id="kecamatan" name="kecamatan" value="<?php echo htmlspecialchars($data['kecamatan']); ?>" required />
  
                <label for="kelurahan">Kelurahan:</label>
                <input type="text" id="kelurahan" name="kelurahan" value="<?php echo htmlspecialchars($data['kelurahan']); ?>" required />
  
                <label for="file_pdf">File PDF:</label>
                <?php if (!empty($data['file_sk'])): ?>
                    <p>Current file: <a href="<?php echo $data['file_sk']; ?>" target="_blank">View PDF</a></p>
                <?php endif; ?>
                <input type="file" id="file_pdf" name="file_pdf" accept=".pdf" />
                
                <div class="form-buttons">
                <button type="button" class="btn-batal" id="btn-cancel">Batal</button>
                <button type="submit" class="btn-simpan" id="btn-save">Simpan</button>
            </div>
            </fieldset>
        </form>
        <!-- Form end -->
    </div>
    <!-- main content end -->

    <script src="./script/menu_fuction.js"></script>
    <script>
        document.getElementById('btn-cancel').addEventListener('click', function() {
            window.location.href = 'data_arsip.php';
        });
    </script>
</body>
</html>
<?php
$conn->close();
?>
