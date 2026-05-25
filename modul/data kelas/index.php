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

if(isset($_POST['tambah'])){

    $id_kelas       = mysqli_real_escape_string($conn, $_POST['id_kelas']);
    $nama_kelas     = mysqli_real_escape_string($conn, $_POST['nama_kelas']);
    $komp_keahlian  = mysqli_real_escape_string($conn, $_POST['komp_keahlian']);

    // cek id kelas
    $cek = mysqli_query($conn,"
        SELECT * FROM tb_kelas
        WHERE id_kelas='$id_kelas'
    ");

    if(mysqli_num_rows($cek) > 0){

        echo "
        <script>
            alert('ID Kelas sudah ada!');
            window.location='index.php';
        </script>
        ";

    } else {

        $insert = mysqli_query($conn,"
            INSERT INTO tb_kelas(
                id_kelas,
                nama_kelas,
                komp_keahlian
            )
            VALUES(
                '$id_kelas',
                '$nama_kelas',
                '$komp_keahlian'
            )
        ");

        if($insert){

            echo "
            <script>
                alert('Data berhasil ditambahkan');
                window.location='index.php';
            </script>
            ";

        } else {

            echo "
            <script>
                alert('Tambah data gagal');
            </script>
            ";

        }

    }

}



/*
|--------------------------------------------------------------------------
| UPDATE DATA
|--------------------------------------------------------------------------
*/

if(isset($_POST['update'])){

    $id_lama        = mysqli_real_escape_string($conn, $_POST['id_lama']);
    $id_kelas       = mysqli_real_escape_string($conn, $_POST['id_kelas']);
    $nama_kelas     = mysqli_real_escape_string($conn, $_POST['nama_kelas']);
    $komp_keahlian  = mysqli_real_escape_string($conn, $_POST['komp_keahlian']);

    // cek apakah id kelas dipakai siswa
    $cek_siswa = mysqli_query($conn,"
        SELECT * FROM tb_siswa
        WHERE id_kelas='$id_lama'
    ");

    if(mysqli_num_rows($cek_siswa) > 0 && $id_lama != $id_kelas){

        echo "
        <script>
            alert('ID kelas tidak bisa diubah karena sudah dipakai siswa!');
            window.location='index.php';
        </script>
        ";

        exit;
    }

    $update = mysqli_query($conn,"
        UPDATE tb_kelas SET
        id_kelas='$id_kelas',
        nama_kelas='$nama_kelas',
        komp_keahlian='$komp_keahlian'
        WHERE id_kelas='$id_lama'
    ");

    if($update){

        // update nama kelas di tb_siswa
        mysqli_query($conn,"
            UPDATE tb_siswa SET
            nama_kelas='$nama_kelas'
            WHERE id_kelas='$id_kelas'
        ");

        echo "
        <script>
            alert('Data berhasil diubah');
            window.location='index.php';
        </script>
        ";

    } else {

        echo "
        <script>
            alert('Data gagal diubah');
        </script>
        ";

    }

}



/*
|--------------------------------------------------------------------------
| HAPUS DATA
|--------------------------------------------------------------------------
*/

if(isset($_GET['hapus'])){

    $id = $_GET['hapus'];

    // cek apakah kelas dipakai siswa
    $cek_siswa = mysqli_query($conn,"
        SELECT * FROM tb_siswa
        WHERE id_kelas='$id'
    ");

    if(mysqli_num_rows($cek_siswa) > 0){

        echo "
        <script>
            alert('Kelas tidak bisa dihapus karena masih dipakai siswa!');
            window.location='index.php';
        </script>
        ";

        exit;
    }

    $hapus = mysqli_query($conn,"
        DELETE FROM tb_kelas
        WHERE id_kelas='$id'
    ");

    if($hapus){

        echo "
        <script>
            alert('Data berhasil dihapus');
            window.location='index.php';
        </script>
        ";

    } else {

        echo "
        <script>
            alert('Data gagal dihapus');
        </script>
        ";

    }

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

$data = mysqli_query($conn,"
    SELECT * FROM tb_kelas
    WHERE
    nama_kelas LIKE '%$cari%'
    OR komp_keahlian LIKE '%$cari%'
    ORDER BY id_kelas ASC
");

?>

<!DOCTYPE html>
<html>
<head>

    <title>Data Kelas</title>

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

        <div class="navbar-custom">

            <h5>Data Kelas</h5>

            <div>
                <?= $_SESSION['nama_petugas']; ?>
            </div>

        </div>



        <div class="main-content">

            <div class="card-box">

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <button
                        type="button"
                        class="btn-custom"
                        data-bs-toggle="modal"
                        data-bs-target="#modalTambah"
                    >
                        Tambah Data
                    </button>

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



                <div class="table-responsive">

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

                                <button
                                    type="button"
                                    class="btn btn-warning btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#edit<?= $d['id_kelas']; ?>"
                                >
                                    Ubah
                                </button>

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

                <h5 class="modal-title">
                    Ubah Data Kelas
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>

            <form method="POST">

                <div class="modal-body">

                    <input
                        type="hidden"
                        name="id_lama"
                        value="<?= $d['id_kelas']; ?>"
                    >

                    <div class="mb-3">

                        <label class="form-label">
                            ID Kelas
                        </label>

                        <input
                            type="text"
                            name="id_kelas"
                            class="form-control"
                            value="<?= $d['id_kelas']; ?>"
                            required
                        >

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Nama Kelas
                        </label>

                        <input
                            type="text"
                            name="nama_kelas"
                            class="form-control"
                            value="<?= $d['nama_kelas']; ?>"
                            required
                        >

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Kompetensi Keahlian
                        </label>

                        <select
    name="komp_keahlian"
    class="form-control"
    required
>

    <option value="IPA"
        <?= ($d['komp_keahlian'] == 'IPA') ? 'selected' : ''; ?>
    >
        IPA
    </option>

    <option value="IPS"
        <?= ($d['komp_keahlian'] == 'IPS') ? 'selected' : ''; ?>
    >
        IPS
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

<div class="modal fade" id="modalTambah" tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Tambah Data Kelas
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>

            <form method="POST">

                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label">
                            ID Kelas
                        </label>

                        <input
                            type="text"
                            name="id_kelas"
                            class="form-control"
                            required
                        >

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Nama Kelas
                        </label>

                        <input
                            type="text"
                            name="nama_kelas"
                            class="form-control"
                            required
                        >

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Kompetensi Keahlian
                        </label>

                        <select
    name="komp_keahlian"
    class="form-control"
    required
>

    <option value=""></option>
    <option value="IPA">IPA</option>
    <option value="IPS">IPS</option>

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