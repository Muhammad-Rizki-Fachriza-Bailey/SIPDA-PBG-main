<?php
require 'connect.php';
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Query untuk menghitung total data tamu
$sql = "SELECT COUNT(*) as total_pemohon FROM pemohon";
$result = $conn->query($sql);

$total_pemohon = 0;
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_pemohon = $row['total_pemohon'];
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard SIPDA - PBG</title>

    <!-- my fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet" />

    <!-- my style -->
    <link rel="stylesheet" href="./css/style_dashboard.css" />
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
      <a href="logout.php"><button>Logout</button></a>
    </div>
    <!-- sidebar end -->

    <!-- navbar start -->
    <div class="navbar">
      <header>
        <img src="asset/menu.png" alt="menu-icon" class="menu-icon" id="menu-icon"/>
        <h1 class="welcome">Selamat datang, <?php echo $_SESSION['username']; ?>!</h1>
        <a href="index_dashboard.php" class="title"><h2>SIPDA - PBG</h2></a>
      </header>
    </div>
    <!-- navbar end -->

    <!-- main content start -->
    <div class="main-content">
      <h1 class="title-dashboard">Dashboard</h1>
      <div class="cards">
        <div class="card">
          <h1>Data Arsip</h1>
          <img src="asset/folder.png" alt="folder-icon" />
          <h1><?php echo $total_pemohon; ?></h1>
          <hr>
          <p>Total Data Arsip Tersimpan</p>
        </div>
      </div>
    </div>
    <!-- main content end -->

    <!-- my javascript -->
    <script src="./script/script_dashboard.js"></script>
  </body>
</html>
