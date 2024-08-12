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

$id = $conn->real_escape_string($_GET['id']);


$sql = "SELECT s.nomor_sk, s.tanggal, s.tahun, 
        b.id_bangunan, b.jenis_bangunan, b.lokasi_bangunan, b.jumlah_unit, b.jumlah_lantai, b.kecamatan, b.kelurahan, 
        p.nama_pemohon, p.tempat_dan_tanggal_lahir, p.jenis_kelamin, p.usia, p.pekerjaan, p.alamat, p.no_telp, 
        p.file_formulir_permohonan, b.file_sk
        FROM surat_imb s
        JOIN bangunan b ON s.id_bangunan = b.id_bangunan
        JOIN pemohon p ON b.id_pemohon = p.id_pemohon
        WHERE s.nomor_sk = '$id' AND s.status = 'active' AND b.status = 'active' AND p.status = 'active'";



$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

$data = $result->fetch_assoc();

if (!$data) {
    die("No records found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Detail Data Arsip SIPDA - PBG</title>
    <!-- my fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet" />
    <!-- my style -->
    <link rel="stylesheet" href="./css/detail_styles.css" />
</head>
<body>
    <!-- sidebar start -->
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
    <!-- sidebar end -->

    <!-- navbar start -->
    <div class="navbar">
        <header>
            <img src="asset/menu.png" alt="menu-icon" class="menu-icon" id="menu-icon" />
            <a href="index_dashboard.php" class="title"><h2>SIPDA - PBG</h2></a>
        </header>
    </div>
    <!-- navbar end -->

    <!-- main content start -->
    <div class="main-content">
        <h1 class="title-data-arsip">Detail Data Arsip</h1>
        <button class="button-back"><a href="index_data_arsip.php" class="back">back</a></button>

       <!-- Identity sections -->
<div class="identity-section">
    <h2>IDENTITAS DIRI</h2>
    <table>
        <tr>
            <td>Nama</td>
            <td>:</td>
            <td><?php echo htmlspecialchars($data['nama_pemohon']); ?></td>
        </tr>
        <tr>
            <td>Tempat dan Tanggal Lahir</td>
            <td>:</td>
            <td><?php echo htmlspecialchars($data['tempat_dan_tanggal_lahir']); ?></td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>:</td>
            <td><?php echo htmlspecialchars($data['alamat']); ?></td>
        </tr>
        <tr>
            <td>Jenis Kelamin</td>
            <td>:</td>
            <td><?php echo htmlspecialchars($data['jenis_kelamin']); ?></td>
        </tr>
        <tr>
            <td>Usia</td>
            <td>:</td>
            <td><?php echo htmlspecialchars($data['usia']); ?></td>
        </tr>
        <tr>
            <td>Pekerjaan</td>
            <td>:</td>
            <td><?php echo htmlspecialchars($data['pekerjaan']); ?></td>
        </tr>
        <tr>
            <td>Nomor Telepon</td>
            <td>:</td>
            <td><?php echo htmlspecialchars($data['no_telp']); ?></td>
        </tr>
        <tr>
            <td>File Formulir Permohonan</td>
            <td>:</td>
            <td>
                <?php
                if ($data['file_formulir_permohonan']) {
                    echo "<a href='" . htmlspecialchars($data['file_formulir_permohonan']) . "' target='_blank'>Lihat File</a>";
                } else {
                    echo "Tidak ada file tersedia";
                }
                ?>
            </td>
        </tr>
    </table>
</div>

<div class="identity-section">
    <h2>IDENTITAS SURAT</h2>
    <table>
        <tr>
            <td>Nomor SK</td>
            <td>:</td>
            <td><?php echo htmlspecialchars($data['nomor_sk']); ?></td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>:</td>
            <td><?php echo htmlspecialchars($data['tanggal']); ?></td>
        </tr>
        <tr>
            <td>Tahun</td>
            <td>:</td>
            <td><?php echo htmlspecialchars($data['tahun']); ?></td>
        </tr>
        <tr>
            <td>Jenis Bangunan</td>
            <td>:</td>
            <td><?php echo htmlspecialchars($data['jenis_bangunan']); ?></td>
        </tr>
        <tr>
            <td>Jumlah Unit</td>
            <td>:</td>
            <td><?php echo htmlspecialchars($data['jumlah_unit']); ?></td>
        </tr>
        <tr>
            <td>Jumlah Lantai</td>
            <td>:</td>
            <td><?php echo htmlspecialchars($data['jumlah_lantai']); ?></td>
        </tr>
        <tr>
            <td>Lokasi Bangunan</td>
            <td>:</td>
            <td><?php echo htmlspecialchars($data['lokasi_bangunan']); ?></td>
        </tr>
        <tr>
            <td>Kecamatan</td>
            <td>:</td>
            <td><?php echo htmlspecialchars($data['kecamatan']); ?></td>
        </tr>
        <tr>
            <td>Kelurahan</td>
            <td>:</td>
            <td><?php echo htmlspecialchars($data['kelurahan']); ?></td>
        </tr>
        <tr>
            <td>File SK Bangunan</td>
            <td>:</td>
            <td>
                <?php
                if ($data['file_sk']) {
                    echo "<a href='" . htmlspecialchars($data['file_sk']) . "' target='_blank'>Lihat File</a>";
                } else {
                    echo "Tidak ada file tersedia";
                }
                ?>
            </td>
        </tr>
    </table>
</div>

        <div class="action-buttons">
            <button class="delete-button" id="btn_delete"><img src="asset/delete.png" alt="Delete"/>Delete</button>
            <button class="download-button">
                <a href="download.php?id=<?php echo htmlspecialchars($data['id_bangunan']); ?>">
                    <img src="asset/downloads.png" alt="Download" />Download
                </a>
            </button>
        </div>
    </div>
    <!-- main content end -->

    <!-- my javascript -->
    <script src="./script/detail_script.js"></script>
    <script src="asset/package/dist/sweetalert2.all.min.js"></script>
    <script>
    document.getElementById('btn_delete').addEventListener('click', function() {
    Swal.fire({
        title: 'Yakin Untuk Menghapus Data ini?',
        text: "Jika data dihapus, maka tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Hapus!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'delete.php?id=<?php echo htmlspecialchars($data['id_bangunan']); ?>';
        }
    });
});

    </script>
</body>
</html>

<?php
$conn->close();
?>
