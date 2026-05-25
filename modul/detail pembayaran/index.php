<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../../login.php");
    exit;
}

include '../../config/koneksi.php';



/*
|--------------------------------------------------------------------------
| SEARCH DETAIL PEMBAYARAN
|--------------------------------------------------------------------------
*/
$cari = '';

if(isset($_GET['cari'])){

    $cari = $_GET['cari'];

}



/*
|--------------------------------------------------------------------------
| FILTER CETAK PER SISWA
|--------------------------------------------------------------------------
*/
$filter_nisn = '';

if(isset($_GET['filter_nisn'])){

    $filter_nisn = $_GET['filter_nisn'];

}



/*
|--------------------------------------------------------------------------
| DATA SISWA UNTUK FILTER CETAK
|--------------------------------------------------------------------------
*/
$siswa = mysqli_query($conn, "
    SELECT * FROM tb_siswa
    ORDER BY nama ASC
");



/*
|--------------------------------------------------------------------------
| QUERY DATA DETAIL PEMBAYARAN
|--------------------------------------------------------------------------
*/

$where = "";

if($filter_nisn != ''){

    $where .= " AND tb_pembayaran.nisn='$filter_nisn' ";

}

$data = mysqli_query($conn, "

    SELECT
        tb_pembayaran.*,
        tb_siswa.nama,
        tb_siswa.nama_kelas,
        tb_siswa.no_telp

    FROM tb_pembayaran

    INNER JOIN tb_siswa
    ON tb_pembayaran.nisn = tb_siswa.nisn

    WHERE
    (
        tb_siswa.nama LIKE '%$cari%'
        OR tb_pembayaran.nisn LIKE '%$cari%'
    )

    $where

    ORDER BY tb_pembayaran.tgl_bayar DESC

");

?>

<!DOCTYPE html>
<html>
<head>

    <title>Detail Pembayaran</title>

    <!-- FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- BOOTSTRAP -->
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
        }

        .table thead{
            background:#2563eb;
            color:white;
        }

        .search-box{
            width:300px;
        }

        @media print{

            .sidebar,
            .navbar-custom,
            .btn,
            form{
                display:none;
            }

            .content{
                margin-left:0;
            }

            body{
                background:white;
            }

            .card-box{
                box-shadow:none;
                padding:0;
            }

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



    <!-- CONTENT -->

    <div class="content">

        <!-- NAVBAR -->

        <div class="navbar-custom">

            <h5>Detail Pembayaran</h5>

            <div>
                <?= $_SESSION['nama_petugas']; ?>
            </div>

        </div>



        <!-- MAIN -->

        <div class="main-content">

            <div class="card-box">

                <div class="d-flex justify-content-between align-items-center mb-4">

                    <h5>
                        Riwayat Detail Pembayaran
                    </h5>

                    <div class="d-flex gap-2">

                        <!-- SEARCH -->

                        <form method="GET">

                            <input
                                type="text"
                                name="cari"
                                class="form-control search-box"
                                placeholder="Cari nama / NISN..."
                                value="<?= $cari; ?>"
                            >

                        </form>



                        <!-- FILTER CETAK -->

                        <form method="GET">

                            <select
                                name="filter_nisn"
                                class="form-control"
                            >

                                <option value="">
                                    Semua Siswa
                                </option>

                                <?php while($s = mysqli_fetch_array($siswa)) { ?>

                                    <option
                                        value="<?= $s['nisn']; ?>"
                                        <?php if($filter_nisn == $s['nisn']) echo 'selected'; ?>
                                    >
                                        <?= $s['nama']; ?>
                                    </option>

                                <?php } ?>

                            </select>

                            <button
                                type="submit"
                                class="btn btn-primary mt-2 w-100"
                            >
                                Filter
                            </button>

                        </form>



                        <!-- CETAK -->

                        <button
                            onclick="window.print()"
                            class="btn btn-success"
                        >
                            Cetak
                        </button>

                    </div>

                </div>



                <!-- TABLE -->

                <div class="table-responsive">

                    <table class="table table-bordered table-hover">

                        <thead>

                            <tr>

                                <th>No</th>
                                <th>ID Pembayaran</th>
                                <th>NISN</th>
                                <th>Nama</th>
                                <th>Kelas</th>
                                <th>Status</th>
                                <th>Tanggal Bayar</th>
                                <th>Jumlah Bulan</th>
                                <th>Nominal</th>
                                <th>Jumlah Bayar</th>
                                <th>Kembalian</th>
                                <th>No Telepon</th>

                            </tr>

                        </thead>

                        <tbody>

                        <?php
                        $no = 1;

                        while($d = mysqli_fetch_array($data)) {
                        ?>

                            <tr>

                                <td><?= $no++; ?></td>

                                <td><?= $d['id_pembayaran']; ?></td>

                                <td><?= $d['nisn']; ?></td>

                                <td><?= $d['nama']; ?></td>

                                <td><?= $d['nama_kelas']; ?></td>

                                <td>

                                    <?php
                                    if($d['status'] == 'sudah bayar'){
                                        echo "<span class='badge bg-success'>Sudah Bayar</span>";
                                    } else {
                                        echo "<span class='badge bg-danger'>Belum Bayar</span>";
                                    }
                                    ?>

                                </td>

                                <td><?= $d['tgl_bayar']; ?></td>

                                <td><?= $d['jumlah_bulan']; ?> Bulan</td>

                                <td>
                                    Rp <?= number_format($d['nominal_bayar']); ?>
                                </td>

                                <td>
                                    Rp <?= number_format($d['jumlah_bayar']); ?>
                                </td>

                                <td>
                                    Rp <?= number_format($d['kembalian']); ?>
                                </td>

                                <td><?= $d['no_telp']; ?></td>

                            </tr>

                        <?php } ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>