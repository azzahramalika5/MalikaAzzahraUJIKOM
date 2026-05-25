<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../../login.php");
    exit;
}

include '../../config/koneksi.php';


/*
|--------------------------------------------------------------------------
| PROSES PEMBAYARAN
|--------------------------------------------------------------------------
*/
if (isset($_POST['bayar'])) {

    $id_pembayaran     = $_POST['id_pembayaran'];
    $status            = $_POST['status'];
    $nisn              = $_POST['nisn'];
    $tgl_bayar         = $_POST['tgl_bayar'];
    $tgl_terakhir      = $_POST['tgl_terakhir_bayar'];
    $batas_pembayaran  = $_POST['batas_pembayaran'];
    $jumlah_bulan      = $_POST['jumlah_bulan'];
    $id_spp            = $_POST['id_spp'];
    $nominal_bayar     = $_POST['nominal_bayar'];
    $jumlah_bayar      = $_POST['jumlah_bayar'];

    $kembalian = $jumlah_bayar - $nominal_bayar;

    mysqli_query($conn, "
        INSERT INTO tb_pembayaran
        VALUES (
            '$id_pembayaran',
            '$status',
            '$nisn',
            '$tgl_bayar',
            '$tgl_terakhir',
            '$batas_pembayaran',
            '$jumlah_bulan',
            '$id_spp',
            '$nominal_bayar',
            '$jumlah_bayar',
            '$kembalian'
        )
    ");

    header("Location: index.php");
}


/*
|--------------------------------------------------------------------------
| SEARCH SISWA
|--------------------------------------------------------------------------
*/
$cari = '';

if (isset($_GET['cari'])) {
    $cari = $_GET['cari'];
}

$data = mysqli_query($conn, "
    SELECT * FROM tb_siswa
    WHERE
    nama LIKE '%$cari%'
    OR nisn LIKE '%$cari%'
");



/*
|--------------------------------------------------------------------------
| DATA PEMBAYARAN
|--------------------------------------------------------------------------
*/
$pembayaran = mysqli_query($conn, "
    SELECT * FROM tb_pembayaran
");

?>

<!DOCTYPE html>
<html>
<head>
    <title>Pembayaran</title>

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
            margin-bottom:25px;
        }

        .table thead{
            background:#2563eb;
            color:white;
        }

        .btn-custom{
            background:#2563eb;
            color:white;
            border:none;
            padding:10px 18px;
            border-radius:10px;
        }

        .btn-custom:hover{
            background:#1e40af;
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

            <h5>Pembayaran SPP</h5>

            <div>
                <?= $_SESSION['nama_petugas']; ?>
            </div>

        </div>



        <!-- MAIN -->

        <div class="main-content">

            <!-- FORM PEMBAYARAN -->

            <div class="card-box">

                <div class="d-flex justify-content-between mb-4">

                    <h5>Form Pembayaran</h5>

                    <form method="GET">

                        <input
                            type="text"
                            name="cari"
                            class="form-control"
                            placeholder="Cari siswa / NISN"
                            value="<?= $cari; ?>"
                        >

                    </form>

                </div>


                <!-- DATA SISWA -->

                <div class="table-responsive mb-4">

                    <table class="table table-bordered table-hover">

                        <thead>

                            <tr>
                                <th>NISN</th>
                                <th>Nama</th>
                                <th>Kelas</th>
                                <th>ID SPP</th>
                                <th>Aksi</th>
                            </tr>

                        </thead>

                        <tbody>

                        <?php while($d = mysqli_fetch_array($data)) { ?>

                            <tr>

                                <td><?= $d['nisn']; ?></td>
                                <td><?= $d['nama']; ?></td>
                                <td><?= $d['nama_kelas']; ?></td>
                                <td><?= $d['id_spp']; ?></td>

                                <td>

                                    <button
                                        class="btn btn-success btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#bayar<?= $d['nisn']; ?>"
                                    >
                                        Bayar
                                    </button>

                                </td>

                            </tr>



                            <!-- MODAL BAYAR -->

                            <div
                                class="modal fade"
                                id="bayar<?= $d['nisn']; ?>"
                                tabindex="-1"
                            >

                                <div class="modal-dialog modal-lg">

                                    <div class="modal-content">

                                        <div class="modal-header">
                                            <h5>Pembayaran SPP</h5>
                                        </div>

                                        <form method="POST">

                                            <div class="modal-body">

                                                <div class="row">

                                                    <div class="col-md-6 mb-3">

                                                        <label>ID Pembayaran</label>

                                                        <input
                                                            type="text"
                                                            name="id_pembayaran"
                                                            class="form-control"
                                                            required
                                                        >

                                                    </div>

                                                    <div class="col-md-6 mb-3">

                                                        <label>Status</label>

                                                        <select
                                                            name="status"
                                                            class="form-control"
                                                        >
                                                            <option value="belum bayar">
                                                                Belum Bayar
                                                            </option>

                                                            <option value="sudah bayar">
                                                                Sudah Bayar
                                                            </option>
                                                        </select>

                                                    </div>

                                                    <div class="col-md-6 mb-3">

                                                        <label>NISN</label>

                                                        <input
                                                            type="text"
                                                            name="nisn"
                                                            class="form-control"
                                                            value="<?= $d['nisn']; ?>"
                                                            readonly
                                                        >

                                                    </div>

                                                    <div class="col-md-6 mb-3">

                                                        <label>Tanggal Bayar</label>

                                                        <input
                                                            type="date"
                                                            name="tgl_bayar"
                                                            class="form-control"
                                                        >

                                                    </div>

                                                    <div class="col-md-6 mb-3">

                                                        <label>Tanggal Terakhir Bayar</label>

                                                        <input
                                                            type="date"
                                                            name="tgl_terakhir_bayar"
                                                            class="form-control"
                                                        >

                                                    </div>

                                                    <div class="col-md-6 mb-3">

                                                        <label>Batas Pembayaran</label>

                                                        <input
                                                            type="date"
                                                            name="batas_pembayaran"
                                                            class="form-control"
                                                        >

                                                    </div>

                                                    <div class="col-md-6 mb-3">

                                                        <label>Jumlah Bulan</label>

                                                        <input
                                                            type="text"
                                                            name="jumlah_bulan"
                                                            class="form-control"
                                                        >

                                                    </div>

                                                    <div class="col-md-6 mb-3">

                                                        <label>ID SPP</label>

                                                        <input
                                                            type="text"
                                                            name="id_spp"
                                                            class="form-control"
                                                            value="<?= $d['id_spp']; ?>"
                                                        >

                                                    </div>

                                                    <div class="col-md-6 mb-3">

                                                        <label>Nominal Bayar</label>

                                                        <input
                                                            type="text"
                                                            name="nominal_bayar"
                                                            class="form-control"
                                                        >

                                                    </div>

                                                    <div class="col-md-6 mb-3">

                                                        <label>Jumlah Bayar</label>

                                                        <input
                                                            type="text"
                                                            name="jumlah_bayar"
                                                            class="form-control"
                                                        >

                                                    </div>

                                                </div>

                                            </div>

                                            <div class="modal-footer">

                                                <button
                                                    type="button"
                                                    class="btn btn-secondary"
                                                    data-bs-dismiss="modal"
                                                >
                                                    Batal
                                                </button>

                                                <button
                                                    type="submit"
                                                    name="bayar"
                                                    class="btn btn-primary"
                                                >
                                                    Simpan
                                                </button>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            </div>

                        <?php } ?>

                        </tbody>

                    </table>

                </div>

            </div>



            <!-- DATA PEMBAYARAN -->

            <div class="card-box">

                <h5 class="mb-4">
                    Riwayat Pembayaran
                </h5>

                <div class="table-responsive">

                    <table class="table table-bordered table-hover">

                        <thead>

                            <tr>

                                <th>ID</th>
                                <th>NISN</th>
                                <th>Status</th>
                                <th>Tgl Bayar</th>
                                <th>Jumlah Bulan</th>
                                <th>Nominal</th>
                                <th>Jumlah Bayar</th>
                                <th>Kembalian</th>

                            </tr>

                        </thead>

                        <tbody>

                        <?php while($p = mysqli_fetch_array($pembayaran)) { ?>

                            <tr>

                                <td><?= $p['id_pembayaran']; ?></td>
                                <td><?= $p['nisn']; ?></td>
                                <td><?= $p['status']; ?></td>
                                <td><?= $p['tgl_bayar']; ?></td>
                                <td><?= $p['jumlah_bulan']; ?></td>
                                <td><?= $p['nominal_bayar']; ?></td>
                                <td><?= $p['jumlah_bayar']; ?></td>
                                <td><?= $p['kembalian']; ?></td>

                            </tr>

                        <?php } ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>