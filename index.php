<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

include 'config/koneksi.php';

/*
|--------------------------------------------------------------------------
| TOTAL SISWA SUDAH LUNAS
|--------------------------------------------------------------------------
*/
$lunas = mysqli_query($conn, "
    SELECT DISTINCT nisn 
    FROM tb_pembayaran
");

$total_lunas = mysqli_num_rows($lunas);


/*
|--------------------------------------------------------------------------
| TOTAL SISWA
|--------------------------------------------------------------------------
*/
$siswa = mysqli_query($conn, "SELECT * FROM tb_siswa");
$total_siswa = mysqli_num_rows($siswa);


/*
|--------------------------------------------------------------------------
| TOTAL SISWA BELUM LUNAS
|--------------------------------------------------------------------------
*/
$total_belum_lunas = $total_siswa - $total_lunas;

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard SPP</title>

    <!-- GOOGLE FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family:'Poppins', sans-serif;
            background:#eef5ff;
        }

        .wrapper{
            display:flex;
        }

        /* SIDEBAR */

        .sidebar{
            width:260px;
            min-height:100vh;
            background:linear-gradient(180deg,#2563eb,#1e40af);
            position:fixed;
            color:white;
        }

        .brand{
            padding:25px;
            text-align:center;
            font-size:24px;
            font-weight:700;
            border-bottom:1px solid rgba(255,255,255,0.2);
        }

        .menu-title{
            padding:15px 20px 5px;
            font-size:12px;
            opacity:0.7;
            letter-spacing:1px;
        }

        .sidebar a{
            display:block;
            color:white;
            text-decoration:none;
            padding:13px 20px;
            transition:0.3s;
        }

        .sidebar a:hover{
            background:rgba(255,255,255,0.15);
            padding-left:28px;
        }

        /* CONTENT */

        .content{
            margin-left:260px;
            width:100%;
        }

        .navbar-custom{
            background:white;
            padding:18px 30px;
            display:flex;
            justify-content:space-between;
            align-items:center;
            box-shadow:0 2px 10px rgba(0,0,0,0.05);
        }

        .main-content{
            padding:30px;
        }

        .welcome{
            margin-bottom:30px;
        }

        .welcome h2{
            font-weight:700;
        }

        .welcome p{
            color:#666;
        }

        /* CARD */

        .dashboard-card{
            border-radius:20px;
            padding:30px;
            color:white;
            transition:0.3s;
        }

        .dashboard-card:hover{
            transform:translateY(-5px);
            box-shadow:0 12px 20px rgba(0,0,0,0.1);
        }

        .card-lunas{
            background:linear-gradient(135deg,#10b981,#34d399);
        }

        .card-belum{
            background:linear-gradient(135deg,#ef4444,#f87171);
        }

        .dashboard-card h5{
            font-size:18px;
            font-weight:600;
        }

        .dashboard-card h3{
            font-size:45px;
            margin-top:10px;
            font-weight:700;
        }

    </style>
</head>

<body>

<div class="wrapper">

    <!-- SIDEBAR --> 

    <div class="sidebar">

        <div class="brand">
            SPP SISWA
        </div>

        <a href="index.php">Dashboard</a>
        <a href="modul/data kelas/index.php">Data Kelas</a>
        <a href="modul/data siswa/index.php">Data Siswa</a>
        <a href="modul/cek pembayaran/index.php">Cek Pembayaran</a>
        <a href="modul/pembayaran/index.php">Pembayaran</a>
        <a href="modul/detail pembayaran/index.php">Detail Pembayaran</a>
        <a href="modul/data petugas/index.php">Data Petugas</a>

        <div class="menu-title">AKUN</div>

        <a href="logout.php" style="color:#ffd4d4;">Logout</a>

    </div>


    <!-- CONTENT -->

    <div class="content">

        <!-- NAVBAR -->

        <div class="navbar-custom">
            <h5 class="mb-0">Dashboard Admin</h5>

            <div>
                Admin |
                <?= date('d M Y') ?>
            </div>
        </div>


        <!-- MAIN CONTENT -->

        <div class="main-content">

            <div class="welcome">
                <h2>Dashboard Pembayaran SPP</h2>
                <p>Monitoring pembayaran siswa.</p>
            </div>

            <div class="row g-4">

                <!-- SISWA LUNAS -->

                <div class="col-md-6">
                    <div class="dashboard-card card-lunas">
                        <h5>Siswa Sudah Lunas</h5>
                        <h3><?= $total_lunas ?></h3>
                    </div>
                </div>


                <!-- SISWA BELUM LUNAS -->

                <div class="col-md-6">
                    <div class="dashboard-card card-belum">
                        <h5>Siswa Belum Lunas</h5>
                        <h3><?= $total_belum_lunas ?></h3>
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>