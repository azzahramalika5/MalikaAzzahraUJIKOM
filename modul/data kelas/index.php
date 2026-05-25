<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../../login.php");
    exit;
}

include '../../config/koneksi.php';

/*
|--------------------------------------------------------------------------
| TAMBAH DATA
|--------------------------------------------------------------------------
*/
if (isset($_POST['tambah'])) {

    $id_kelas   = $_POST['id_kelas'];
    $nama_kelas = $_POST['nama_kelas'];
    $kompetensi = $_POST['komp_keahlian'];

    mysqli_query($conn, "
        INSERT INTO tb_kelas
        VALUES (
            '$id_kelas',
            '$nama_kelas',
            '$kompetensi'
        )
    ");

    header("Location: index.php");
}


/*
|--------------------------------------------------------------------------
| HAPUS DATA
|--------------------------------------------------------------------------
*/
if (isset($_GET['hapus'])) {

    $id = $_GET['hapus'];

    mysqli_query($conn, "
        DELETE FROM tb_kelas
        WHERE id_kelas='$id'
    ");

    header("Location: index.php");
}


/*
|--------------------------------------------------------------------------
| UPDATE DATA
|--------------------------------------------------------------------------
*/
if (isset($_POST['update'])) {

    $id_kelas   = $_POST['id_kelas'];
    $nama_kelas = $_POST['nama_kelas'];
    $kompetensi = $_POST['komp_keahlian'];

    mysqli_query($conn, "
        UPDATE tb_kelas SET
        nama_kelas='$nama_kelas',
        komp_keahlian='$kompetensi'
        WHERE id_kelas='$id_kelas'
    ");

    header("Location: index.php");
}


/*
|--------------------------------------------------------------------------
| SEARCH
|--------------------------------------------------------------------------
*/
$cari = '';

if (isset($_GET['cari'])) {
    $cari = $_GET['cari'];
}

$data = mysqli_query($conn, "
    SELECT * FROM tb_kelas
    WHERE
    nama_kelas LIKE '%$cari%'
    OR komp_keahlian LIKE '%$cari%'
");

?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Kelas</title>

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

        .card-box{
            background:white;
            border-radius:20px;
            padding:25px;
            box-shadow:0 4px 15px rgba(0,0,0,0.05);
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

        table{
            margin-top:20px;
        }

        .table thead{
            background:#2563eb;
            color:white;
        }

        .search-box{
            width:300px;
        }

        .modal-content{
            border-radius:20px;
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
            <h5>Data Kelas</h5>

            <div>
                <?= $_SESSION['nama_petugas']; ?>
            </div>
        </div>


        <!-- MAIN -->

        <div class="main-content">

            <div class="card-box">

                <div class="d-flex justify-content-between align-items-center">

                    <!-- BUTTON TAMBAH -->

                    <button
                        class="btn-custom"
                        data-bs-toggle="modal"
                        data-bs-target="#modalTambah"
                    >
                        Tambah Data
                    </button>


                    <!-- SEARCH -->

                    <form method="GET">
                        <input
                            type="text"
                            name="cari"
                            class="form-control search-box"
                            placeholder="Cari data kelas..."
                            value="<?= $cari; ?>"
                        >
                    </form>

                </div>


                <!-- TABLE -->

                <table class="table table-bordered table-hover">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID Kelas</th>
                            <th>Nama Kelas</th>
                            <th>Kompetensi Keahlian</th>
                            <th width="180">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php
                    $no = 1;

                    while($d = mysqli_fetch_array($data)) {
                    ?>

                        <tr>

                            <td><?= $no++; ?></td>
                            <td><?= $d['id_kelas']; ?></td>
                            <td><?= $d['nama_kelas']; ?></td>
                            <td><?= $d['komp_keahlian']; ?></td>

                            <td>

                                <!-- BUTTON EDIT -->

                                <button
                                    class="btn btn-warning btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#edit<?= $d['id_kelas']; ?>"
                                >
                                    Ubah
                                </button>

                                <!-- BUTTON HAPUS -->

                                <a
                                    href="?hapus=<?= $d['id_kelas']; ?>"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin hapus data?')"
                                >
                                    Hapus
                                </a>

                            </td>

                        </tr>


                        <!-- MODAL EDIT -->

                        <div
                            class="modal fade"
                            id="edit<?= $d['id_kelas']; ?>"
                            tabindex="-1"
                        >

                            <div class="modal-dialog">

                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5>Ubah Data</h5>
                                    </div>

                                    <form method="POST">

                                        <div class="modal-body">

                                            <input
                                                type="text"
                                                name="id_kelas"
                                                class="form-control mb-3"
                                                value="<?= $d['id_kelas']; ?>"
                                                readonly
                                            >

                                            <input
                                                type="text"
                                                name="nama_kelas"
                                                class="form-control mb-3"
                                                value="<?= $d['nama_kelas']; ?>"
                                                required
                                            >

                                            <input
                                                type="text"
                                                name="komp_keahlian"
                                                class="form-control"
                                                value="<?= $d['komp_keahlian']; ?>"
                                                required
                                            >

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
                                                name="update"
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

    </div>

</div>


<!-- MODAL TAMBAH -->

<div
    class="modal fade"
    id="modalTambah"
    tabindex="-1"
>

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">
                <h5>Tambah Data Kelas</h5>
            </div>

            <form method="POST">

                <div class="modal-body">

                    <input
                        type="text"
                        name="id_kelas"
                        class="form-control mb-3"
                        placeholder="ID Kelas"
                        required
                    >

                    <input
                        type="text"
                        name="nama_kelas"
                        class="form-control mb-3"
                        placeholder="Nama Kelas"
                        required
                    >

                    <input
                        type="text"
                        name="komp_keahlian"
                        class="form-control"
                        placeholder="Kompetensi Keahlian"
                        required
                    >

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
                        name="tambah"
                        class="btn btn-primary"
                    >
                        Simpan
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>