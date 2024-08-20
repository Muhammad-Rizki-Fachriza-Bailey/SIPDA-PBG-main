<?php
session_start();
require 'connect.php';

// Cek apakah pengguna sudah login, jika ya arahkan ke halaman dashboard
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Mencegah SQL Injection
    $username = $conn->real_escape_string($username);

    // Ambil data dari database
    $sql = "SELECT * FROM login_admin WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result === false) {
        die("Error: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verifikasi password
       
            $_SESSION['username'] = $row['username'];
            header("Location: dashboard.php");
            exit();
        
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login SIPDA - PBG | DPMPTSP Kota Tanjungpinang</title>

    <!-- my fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet" />

    <!-- my style -->
    <link rel="stylesheet" href="./css/style_login.css" />
</head>
<body>
    <div class="input-box">
        <form action="index.php" method="post">
            <img src="asset/Logo DPMPTSP.png" alt="DPMPTSP KOTA TANJUNGPINANG" class="logo"/>
            <h1>Login</h1>
            <?php if (isset($error)): ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php endif; ?>
            <input type="text" name="username" placeholder="Username" required/>
            <div class="password-container">
                <input type="password" name="password" placeholder="Password" id="password" required/>
                <img src="asset/eye-close.png" alt="Eye Close" class="eye" id="eyeicon">
            </div>
            <button type="submit">Login</button>
        </form>
    </div>

    <!-- my javascript -->
    <script src="./script/script_login.js"></script>
    <script>
        const togglePassword = document.querySelector('#eyeicon');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function () {
            // toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            // toggle the eye slash icon
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>
