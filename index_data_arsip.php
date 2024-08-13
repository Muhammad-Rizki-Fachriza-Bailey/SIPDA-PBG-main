<?php
session_start();
require 'connect.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set the number of items per page
$items_per_page = 10;

// Get the current page number from the URL, default to 1 if not set
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Get the search query from the URL if set
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// Get the filter parameters from the URL if set
$filter_date = isset($_GET['filter_date']) ? $conn->real_escape_string($_GET['filter_date']) : '';

// Modify the query to include the search term if provided
$whereClause = 'WHERE s.status = 1';  // Only fetch active records
if ($search) {
    $whereClause .= " AND (s.nomor_sk LIKE ? OR p.nama_pemohon LIKE ? OR s.tanggal LIKE ? OR s.tahun LIKE ?)";
}

// Modify the query to include filters if set
if ($filter_date) {
    $whereClause .= " AND s.tanggal = '$filter_date'";
}

// Query to count the total number of records with search term and filters
$count_sql = "SELECT COUNT(*) AS total FROM surat_imb s 
              JOIN bangunan b ON s.id_bangunan = b.id_bangunan 
              JOIN pemohon p ON b.id_pemohon = p.id_pemohon " . $whereClause;
$stmt = $conn->prepare($count_sql);
if ($search) {
    $searchTerm = "%$search%";
    $stmt->bind_param('ssss', $searchTerm, $searchTerm, $searchTerm, $searchTerm);
}
$stmt->execute();
$count_result = $stmt->get_result();
$total_items = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_items / $items_per_page);

// Query to fetch data with limit, offset, and filters
$sql = "SELECT s.nomor_sk, p.id_pemohon, b.id_bangunan, p.nama_pemohon, s.tanggal, s.tahun 
        FROM surat_imb s 
        JOIN bangunan b ON s.id_bangunan = b.id_bangunan 
        JOIN pemohon p ON b.id_pemohon = p.id_pemohon " . $whereClause . " 
        ORDER BY s.tanggal DESC 
        LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
if ($search) {
    $stmt->bind_param('ssssii', $searchTerm, $searchTerm, $searchTerm, $searchTerm, $items_per_page, $offset);
} else {
    $stmt->bind_param('ii', $items_per_page, $offset);
}
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Data Arsip SIPDA - PBG</title>
    <!-- my fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet" />
    <!-- my style -->
    <link rel="stylesheet" href="./css/style_data_arsip.css" />
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
        <h1 class="title-data-arsip">Arsip Data</h1>
        <div class="search-and-filters">
            <div class="search-container">
                <img src="asset/search-interface-symbol.png" alt="search-icon" class="icon" />
                <form action="index_data_arsip.php" method="get">
                    <input type="text" name="search" class="search-bar" placeholder="Cari data" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" />
                    <button type="submit" class="search-button">Cari</button>
                </form>
            </div>
            <div class="filter-container">
                <form action="index_data_arsip.php" method="get" class="filter-form">
                    <input type="date" name="filter_date" class="filter-date" value="<?php echo isset($_GET['filter_date']) ? htmlspecialchars($_GET['filter_date']) : ''; ?>" />
                    <button type="submit" class="filter-button">Filter</button>
                </form>
            </div>
            <a href="tambah_data_formulir.php"><button class="add-data-button">+ Tambah Formulir</button></a>
            <a href="tambah_data_sk.php"><button class="add-data-button">+ Tambah SK</button></a>
        </div>
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nomor SK</th>
                        <th>Nama Pemohon</th>
                        <th>Tanggal</th>
                        <th>Tahun</th>
                        <th>Aksi</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        // Output data of each row
                        while($row = $result->fetch_assoc()) {
                            $nomor_sk = htmlspecialchars($row['nomor_sk']);
                            $id_pemohon = htmlspecialchars($row['id_pemohon']);
                            $id_bangunan = htmlspecialchars($row['id_bangunan']);
                            echo "<tr>";
                            echo "<td>" . $nomor_sk . "</td>";
                            echo "<td>" . htmlspecialchars($row['nama_pemohon']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['tanggal']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['tahun']) . "</td>";
                            echo "<td><a href='detail_data_arsip.php?id=$nomor_sk'>Lihat detail >></a></td>";
                            echo "<td>
                                    <a href='update_data_arsip.php?id=$id_pemohon' class='edit-button'>Edit Arsip</a> |
                                    <a href='edit_data_sk.php?id=$id_bangunan' class='edit-button'>Edit SK</a>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No records found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="index_data_arsip.php?page=<?php echo $page - 1; ?>"><<</a>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="index_data_arsip.php?page=<?php echo $i; ?>" class="<?php if ($page == $i) echo 'active'; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>
                <?php if ($page < $total_pages): ?>
                    <a href="index_data_arsip.php?page=<?php echo $page + 1; ?>">>></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!-- main content end -->

    <!-- my javascript -->
    <script src="./script/menu_fuction.js"></script>
    <script>
        function sortData(sortBy) {
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('sort_by', sortBy);
            window.location.href = currentUrl.toString();
        }
    </script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
