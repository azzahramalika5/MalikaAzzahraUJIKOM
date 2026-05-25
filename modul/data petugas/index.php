<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../../login.php");
    exit;
}

include '../../config/koneksi.php';



/*
|--------------------------------------------------------------------------
| TAMBAH PETUGAS
|--------------------------------------------------------------------------
*/
if(isset($_POST['tambah'])){

    $id_petugas     = $_POST['id_petugas'];
    $username       = $_POST['username'];
    $password       = $_POST['password'];
    $nama_petugas   = $_POST['nama_petugas'];
    $level          = $_POST['level'];

    mysqli_query($conn, "
        INSERT INTO tb_petugas
        VALUES(
            '$id_petugas',
            '$username',
            '$password',
            '$nama_petugas',
            '$level'
        )
    ");

    header("Location: index.php");
}



/*
|--------------------------------------------------------------------------
| HAPUS PETUGAS
|--------------------------------------------------------------------------
*/
if(isset($_GET['hapus'])){

    $id_petugas = $_GET['hapus'];

    mysqli_query($conn, "
        DELETE FROM tb_petugas
        WHERE id_petugas='$id_petugas'
    ");

    header("Location: index.php");
}



/*
|--------------------------------------------------------------------------
| UPDATE PETUGAS
|--------------------------------------------------------------------------
*/
if(isset($_POST['update'])){

    $id_petugas     = $_POST['id_petugas'];
    $username       = $_POST['username'];
    $password       = $_POST['password'];
    $nama_petugas   = $_POST['nama_petugas'];
    $level          = $_POST['level'];

    mysqli_query($conn, "
        UPDATE tb_petugas
        SET
        username='$username',
        password='$password',
        nama_petugas='$nama_petugas',
        level='$level'
        WHERE id_petugas='$id_petugas'
    ");

    header("Location: index.php");
}



/*
|--------------------------------------------------------------------------
| SEARCH
|--------------------------------------------------------------------------
*/
$cari = '';

if(isset($_GET['cari'])){

    $cari = $_GET['cari'];

}

$data = mysqli_query($conn, "
    SELECT * FROM tb_petugas
    WHERE
    nama_petugas LIKE '%$cari%'
    OR username LIKE '%$cari%'
    OR level LIKE '%$cari%'
");

?>

<!DOCTYPE html>
<html>
<head>

    <title>Data Petugas</title>

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

        .table thead{
            background:#2563eb;
            color:white;
        }

        .search-box{
            width:300px;
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

            <h5>Data Petugas</h5>

            <div>
                <?= $_SESSION['nama_petugas']; ?>
            </div>

        </div>



        <!-- MAIN -->

        <div class="main-content">

            <div class="card-box">

                <div class="d-flex justify-content-between align-items-center mb-4">

                    <button
                        class="btn-custom"
                        data-bs-toggle="modal"
                        data-bs-target="#modalTambah"
                    >
                        Tambah Petugas
                    </button>

                    <form method="GET">

                        <input
                            type="text"
                            name="cari"
                            class="form-control search-box"
                            placeholder="Cari petugas..."
                            value="<?= $cari; ?>"
                        >

                    </form>

                </div>



                <!-- TABLE -->

                <div class="table-responsive">

                    <table class="table table-bordered table-hover">

                        <thead>

                            <tr>

                                <th>No</th>
                                <th>ID Petugas</th>
                                <th>Username</th>
                                <th>Password</th>
                                <th>Nama Petugas</th>
                                <th>Level</th>
                                <th width="170">Aksi</th>

                            </tr>

                        </thead>

                        <tbody>

                        <?php
                        $no = 1;

                        while($d = mysqli_fetch_array($data)) {
                        ?>

                            <tr>

                                <td><?= $no++; ?></td>
                                <td><?= $d['id_petugas']; ?></td>
                                <td><?= $d['username']; ?></td>
                                <td><?= $d['password']; ?></td>
                                <td><?= $d['nama_petugas']; ?></td>
                                <td><?= $d['level']; ?></td>

                                <td>

                                    <button
                                        class="btn btn-warning btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#edit<?= $d['id_petugas']; ?>"
                                    >
                                        Ubah
                                    </button>

                                    <a
                                        href="?hapus=<?= $d['id_petugas']; ?>"
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
                                id="edit<?= $d['id_petugas']; ?>"
                                tabindex="-1"
                            >

                                <div class="modal-dialog">

                                    <div class="modal-content">

                                        <div class="modal-header">
                                            <h5>Ubah Data Petugas</h5>
                                        </div>

                                        <form method="POST">

                                            <div class="modal-body">

                                                <div class="mb-3">

                                                    <label>ID Petugas</label>

                                                    <input
                                                        type="text"
                                                        name="id_petugas"
                                                        class="form-control"
                                                        value="<?= $d['id_petugas']; ?>"
                                                        readonly
                                                    >

                                                </div>

                                                <div class="mb-3">

                                                    <label>Username</label>

                                                    <input
                                                        type="text"
                                                        name="username"
                                                        class="form-control"
                                                        value="<?= $d['username']; ?>"
                                                    >

                                                </div>

                                                <div class="mb-3">

                                                    <label>Password</label>

                                                    <input
                                                        type="text"
                                                        name="password"
                                                        class="form-control"
                                                        value="<?= $d['password']; ?>"
                                                    >

                                                </div>

                                                <div class="mb-3">

                                                    <label>Nama Petugas</label>

                                                    <input
                                                        type="text"
                                                        name="nama_petugas"
                                                        class="form-control"
                                                        value="<?= $d['nama_petugas']; ?>"
                                                    >

                                                </div>

                                                <div class="mb-3">

                                                    <label>Level</label>

                                                    <select
                                                        name="level"
                                                        class="form-control"
                                                    >

                                                        <option value="admin">
                                                            Admin
                                                        </option>

                                                        <option value="petugas">
                                                            Petugas
                                                        </option>

                                                    </select>

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
                <h5>Tambah Petugas</h5>
            </div>

            <form method="POST">

                <div class="modal-body">

                    <div class="mb-3">

                        <label>ID Petugas</label>

                        <input
                            type="text"
                            name="id_petugas"
                            class="form-control"
                            required
                        >

                    </div>

                    <div class="mb-3">

                        <label>Username</label>

                        <input
                            type="text"
                            name="username"
                            class="form-control"
                            required
                        >

                    </div>

                    <div class="mb-3">

                        <label>Password</label>

                        <input
                            type="text"
                            name="password"
                            class="form-control"
                            required
                        >

                    </div>

                    <div class="mb-3">

                        <label>Nama Petugas</label>

                        <input
                            type="text"
                            name="nama_petugas"
                            class="form-control"
                            required
                        >

                    </div>

                    <div class="mb-3">

                        <label>Level</label>

                        <select
                            name="level"
                            class="form-control"
                        >

                            <option value="admin">
                                Admin
                            </option>

                            <option value="petugas">
                                Petugas
                            </option>

                        </select>

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