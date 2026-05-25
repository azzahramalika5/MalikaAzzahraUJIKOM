<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../../login.php");
    exit;
}

include '../../config/koneksi.php';



/*
|--------------------------------------------------------------------------
| SEARCH NAMA
|--------------------------------------------------------------------------
*/
$data_nama = null;

if (isset($_GET['cari_nama'])) {

    $cari_nama = $_GET['cari_nama'];

    $data_nama = mysqli_query($conn, "

        SELECT
            s.nisn,
            s.nama,
            s.no_telp,

            COALESCE(SUM(p.jumlah_bulan),0) as jumlah_bulan,

            MAX(p.tgl_bayar) as tgl_terakhir_bayar,

            CASE
                WHEN COALESCE(SUM(p.jumlah_bulan),0) >= 12
                THEN 'sudah lunas'
                ELSE 'belum lunas'
            END as status_pembayaran

        FROM tb_siswa s

        LEFT JOIN tb_pembayaran p
        ON s.nisn = p.nisn

        WHERE s.nama LIKE '%$cari_nama%'

        GROUP BY s.nisn

    ");
}



/*
|--------------------------------------------------------------------------
| SEARCH NISN
|--------------------------------------------------------------------------
*/
$data_nisn = null;

if (isset($_GET['cari_nisn'])) {

    $cari_nisn = $_GET['cari_nisn'];

    $data_nisn = mysqli_query($conn, "

        SELECT
            s.nisn,
            s.nama,
            s.no_telp,

            COALESCE(SUM(p.jumlah_bulan),0) as jumlah_bulan,

            MAX(p.tgl_bayar) as tgl_terakhir_bayar,

            CASE
                WHEN COALESCE(SUM(p.jumlah_bulan),0) >= 12
                THEN 'sudah lunas'
                ELSE 'belum lunas'
            END as status_pembayaran

        FROM tb_siswa s

        LEFT JOIN tb_pembayaran p
        ON s.nisn = p.nisn

        WHERE s.nisn LIKE '%$cari_nisn%'

        GROUP BY s.nisn

    ");
}



/*
|--------------------------------------------------------------------------
| SISWA SUDAH LUNAS
|--------------------------------------------------------------------------
*/
$lunas = mysqli_query($conn, "

    SELECT
        s.nisn,
        s.nama,

        COALESCE(SUM(p.jumlah_bulan),0) as jumlah_bulan

    FROM tb_siswa s

    LEFT JOIN tb_pembayaran p
    ON s.nisn = p.nisn

    GROUP BY s.nisn

    HAVING jumlah_bulan >= 12

");



/*
|--------------------------------------------------------------------------
| SISWA BELUM LUNAS
|--------------------------------------------------------------------------
*/
$belum = mysqli_query($conn, "

    SELECT
        s.nisn,
        s.nama,

        COALESCE(SUM(p.jumlah_bulan),0) as jumlah_bulan,

        CASE
            WHEN COALESCE(SUM(p.jumlah_bulan),0) >= 12
            THEN 'sudah lunas'
            ELSE 'belum lunas'
        END as status_pembayaran

    FROM tb_siswa s

    LEFT JOIN tb_pembayaran p
    ON s.nisn = p.nisn

    GROUP BY s.nisn

    HAVING jumlah_bulan < 12

");

?>

<!DOCTYPE html>
<html>
<head>
    <title>Cek Pembayaran</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>

        body{
            margin:0;
            font-family:'Poppins', sans-serif;
            background:#eef5ff;
        }

        .wrapper{
            display:flex;
        }

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

        .card-box{
            background:white;
            border-radius:20px;
            padding:25px;
            box-shadow:0 4px 15px rgba(0,0,0,0.05);
            margin-bottom:25px;
        }

        .table thead{
            background:#2563eb;
            color:white;
        }

        h5{
            font-weight:600;
        }

    </style>
</head>

<body>

<div class="wrapper">

    <div class="sidebar">

        <div class="brand">
            SPP SISWA
        </div>

        <a href="../../index.php">Dashboard</a>
        <a href="../data kelas/index.php">Data Kelas</a>
        <a href="../data siswa/index.php">Data Siswa</a>
        <a href="../cek pembayaran/index.php">Cek Pembayaran</a>
        <a href="../pembayaran/index.php">Pembayaran</a>
        <a href="../detail pembayaran/index.php">Detail Pembayaran</a>
        <a href="../data petugas/index.php">Data Petugas</a>

        <div class="menu-title">AKUN</div>

        <a href="../../logout.php">Logout</a>

    </div>



    <div class="content">

        <div class="navbar-custom">

            <h5>Cek Pembayaran</h5>

            <div>
                <?= $_SESSION['nama_petugas']; ?>
            </div>

        </div>



        <div class="main-content">


            <!-- SEARCH NAMA -->

            <div class="card-box">

                <h5 class="mb-3">
                    Cari Siswa Berdasarkan Nama
                </h5>

                <form method="GET">

                    <div class="row">

                        <div class="col-md-10">

                            <input
                                type="text"
                                name="cari_nama"
                                class="form-control"
                                placeholder="Masukkan nama siswa..."
                            >

                        </div>

                        <div class="col-md-2">

                            <button
                                type="submit"
                                class="btn btn-primary w-100"
                            >
                                Cari
                            </button>

                        </div>

                    </div>

                </form>


                <?php if($data_nama != null){ ?>

                <div class="table-responsive mt-4">

                    <table class="table table-bordered table-hover">

                        <thead>

                            <tr>
                                <th>NISN</th>
                                <th>Nama</th>
                                <th>Status</th>
                                <th>Bulan Sudah Dibayar</th>
                                <th>Tanggal Bayar</th>
                            </tr>

                        </thead>

                        <tbody>

                        <?php while($d = mysqli_fetch_array($data_nama)) { ?>

                            <tr>

                                <td><?= $d['nisn']; ?></td>
                                <td><?= $d['nama']; ?></td>
                                <td><?= $d['status_pembayaran']; ?></td>
                                <td><?= $d['jumlah_bulan']; ?> Bulan</td>
                                <td><?= $d['tgl_terakhir_bayar']; ?></td>

                            </tr>

                        <?php } ?>

                        </tbody>

                    </table>

                </div>

                <?php } ?>

            </div>



            <!-- SEARCH NISN -->

            <div class="card-box">

                <h5 class="mb-3">
                    Cari Pembayaran Berdasarkan NISN
                </h5>

                <form method="GET">

                    <div class="row">

                        <div class="col-md-10">

                            <input
                                type="text"
                                name="cari_nisn"
                                class="form-control"
                                placeholder="Masukkan NISN..."
                            >

                        </div>

                        <div class="col-md-2">

                            <button
                                type="submit"
                                class="btn btn-primary w-100"
                            >
                                Cari
                            </button>

                        </div>

                    </div>

                </form>


                <?php if($data_nisn != null){ ?>

                <div class="table-responsive mt-4">

                    <table class="table table-bordered table-hover">

                        <thead>

                            <tr>
                                <th>NISN</th>
                                <th>Nama</th>
                                <th>Status</th>
                                <th>Bulan Sudah Dibayar</th>
                                <th>Tanggal Bayar</th>
                                <th>No Telepon</th>
                            </tr>

                        </thead>

                        <tbody>

                        <?php while($d = mysqli_fetch_array($data_nisn)) { ?>

                            <tr>

                                <td><?= $d['nisn']; ?></td>
                                <td><?= $d['nama']; ?></td>
                                <td><?= $d['status_pembayaran']; ?></td>
                                <td><?= $d['jumlah_bulan']; ?> Bulan</td>
                                <td><?= $d['tgl_terakhir_bayar']; ?></td>
                                <td><?= $d['no_telp']; ?></td>

                            </tr>

                        <?php } ?>

                        </tbody>

                    </table>

                </div>

                <?php } ?>

            </div>



            <!-- TABLE LUNAS & BELUM LUNAS -->

            <div class="row">

                <div class="col-md-6">

                    <div class="card-box">

                        <h5 class="mb-3">
                            Siswa Sudah Lunas
                        </h5>

                        <div class="table-responsive">

                            <table class="table table-bordered table-hover">

                                <thead>

                                    <tr>
                                        <th>NISN</th>
                                        <th>Nama</th>
                                        <th>Bulan Dibayar</th>
                                    </tr>

                                </thead>

                                <tbody>

                                <?php while($d = mysqli_fetch_array($lunas)) { ?>

                                    <tr>

                                        <td><?= $d['nisn']; ?></td>
                                        <td><?= $d['nama']; ?></td>
                                        <td><?= $d['jumlah_bulan']; ?> Bulan</td>

                                    </tr>

                                <?php } ?>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>



                <div class="col-md-6">

                    <div class="card-box">

                        <h5 class="mb-3">
                            Siswa Belum Lunas
                        </h5>

                        <div class="table-responsive">

                            <table class="table table-bordered table-hover">

                                <thead>

                                    <tr>
                                        <th>NISN</th>
                                        <th>Nama</th>
                                        <th>Status</th>
                                        <th>Bulan Dibayar</th>
                                    </tr>

                                </thead>

                                <tbody>

                                <?php while($d = mysqli_fetch_array($belum)) { ?>

                                    <tr>

                                        <td><?= $d['nisn']; ?></td>
                                        <td><?= $d['nama']; ?></td>
                                        <td><?= $d['status_pembayaran']; ?></td>
                                        <td><?= $d['jumlah_bulan']; ?> Bulan</td>

                                    </tr>

                                <?php } ?>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>