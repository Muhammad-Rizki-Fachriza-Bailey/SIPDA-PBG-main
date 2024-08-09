<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Arsip</title>

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
        <h1 class="title-data-arsip">Tambah Data Formulir</h1>
        <button class="button-back"><a href="index_data_arsip.php" class="back">back</a></button>
  
        <!-- Form start -->
        <form class="edit-form" action="tambah_data_arsip.php" method="post" enctype="multipart/form-data">
          <fieldset>
            <legend>Identitas Diri</legend>
            <label for="nama">Nama:</label>
            <input type="text" id="nama" name="nama" required />
  
            <label for="ttl">Tempat dan Tanggal Lahir:</label>
            <input type="text" id="ttl" name="ttl" required />
  
            <label for="alamat">Alamat:</label>
            <input type="text" id="alamat" name="alamat" required />
  
            <label for="jenis_kelamin">Jenis Kelamin:</label>
            <select id="jenis_kelamin" name="jenis_kelamin" required>
            <option value="Laki-laki">Laki-laki</option>
            <option value="Perempuan">Perempuan</option>
            </select>
  
            <label for="usia">Usia:</label>
            <input type="number" id="usia" name="usia" required />
  
            <label for="pekerjaan">Pekerjaan:</label>
            <input type="text" id="pekerjaan" name="pekerjaan" required />
  
            <label for="telepon">Nomor Telepon:</label>
            <input type="tel" id="telepon" name="telepon" required />
          </fieldset>

          <fieldset>
            <legend>Upload Formulir Permohonan</legend>
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
