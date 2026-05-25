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

    $nisn     = mysqli_real_escape_string($conn, $_POST['nisn']);
    $nis      = mysqli_real_escape_string($conn, $_POST['nis']);
    $nama     = mysqli_real_escape_string($conn, $_POST['nama']);
    $id_kelas = mysqli_real_escape_string($conn, $_POST['id_kelas']);
    $alamat   = mysqli_real_escape_string($conn, $_POST['alamat']);
    $no_telp  = mysqli_real_escape_string($conn, $_POST['no_telp']);
    $id_spp   = mysqli_real_escape_string($conn, $_POST['id_spp']);

    // cek kelas
    $cek_kelas = mysqli_query($conn, "
        SELECT * FROM tb_kelas
        WHERE id_kelas='$id_kelas'
    ");

    if(mysqli_num_rows($cek_kelas) == 0){

        echo "
        <script>
            alert('Kelas tidak ditemukan!');
            window.location='index.php';
        </script>
        ";

        exit;
    }

    $dk = mysqli_fetch_array($cek_kelas);
    $nama_kelas = $dk['nama_kelas'];

    // cek nisn
    $cek_nisn = mysqli_query($conn, "
        SELECT * FROM tb_siswa
        WHERE nisn='$nisn'
    ");

    if(mysqli_num_rows($cek_nisn) > 0){

        echo "
        <script>
            alert('NISN sudah ada!');
            window.location='index.php';
        </script>
        ";

    } else {

        $insert = mysqli_query($conn, "
            INSERT INTO tb_siswa(
                nisn,
                nis,
                nama,
                id_kelas,
                nama_kelas,
                alamat,
                no_telp,
                id_spp
            )
            VALUES(
                '$nisn',
                '$nis',
                '$nama',
                '$id_kelas',
                '$nama_kelas',
                '$alamat',
                '$no_telp',
                '$id_spp'
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

if (isset($_POST['update'])) {

    $nisn_lama = mysqli_real_escape_string($conn, $_POST['nisn_lama']);

    $nisn     = mysqli_real_escape_string($conn, $_POST['nisn']);
    $nis      = mysqli_real_escape_string($conn, $_POST['nis']);
    $nama     = mysqli_real_escape_string($conn, $_POST['nama']);
    $id_kelas = mysqli_real_escape_string($conn, $_POST['id_kelas']);
    $alamat   = mysqli_real_escape_string($conn, $_POST['alamat']);
    $no_telp  = mysqli_real_escape_string($conn, $_POST['no_telp']);
    $id_spp   = mysqli_real_escape_string($conn, $_POST['id_spp']);

    // cek kelas
    $cek_kelas = mysqli_query($conn, "
        SELECT * FROM tb_kelas
        WHERE id_kelas='$id_kelas'
    ");

    if(mysqli_num_rows($cek_kelas) == 0){

        echo "
        <script>
            alert('Kelas tidak ditemukan!');
            window.location='index.php';
        </script>
        ";

        exit;
    }

    $dk = mysqli_fetch_array($cek_kelas);
    $nama_kelas = $dk['nama_kelas'];

    $update = mysqli_query($conn, "
        UPDATE tb_siswa SET
        nisn='$nisn',
        nis='$nis',
        nama='$nama',
        id_kelas='$id_kelas',
        nama_kelas='$nama_kelas',
        alamat='$alamat',
        no_telp='$no_telp',
        id_spp='$id_spp'
        WHERE nisn='$nisn_lama'
    ");

    if($update){

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

if (isset($_GET['hapus'])) {

    $nisn = $_GET['hapus'];

    mysqli_query($conn, "
        DELETE FROM tb_siswa
        WHERE nisn='$nisn'
    ");

    header("Location:index.php");
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
    SELECT * FROM tb_siswa
    WHERE
    nama LIKE '%$cari%'
    OR nisn LIKE '%$cari%'
    OR nama_kelas LIKE '%$cari%'
");

?>

<!DOCTYPE html>
<html>
<head>

    <title>Data Siswa</title>

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

        .search-box{
            width:300px;
        }

        .table thead{
            background:#2563eb;
            color:white;
        }

        .modal-content{
            border-radius:20px;
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

            <h5>Data Siswa</h5>

            <div>
                <?= $_SESSION['nama_petugas']; ?>
            </div>

        </div>


        <div class="main-content">

            <div class="card-box">

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <button
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
                            placeholder="Cari siswa..."
                            value="<?= $cari; ?>"
                        >

                    </form>

                </div>


                <div class="table-responsive">

                    <table class="table table-bordered table-hover">

                        <thead>

                            <tr>
                                <th>No</th>
                                <th>NISN</th>
                                <th>NIS</th>
                                <th>Nama</th>
                                <th>Kelas</th>
                                <th>Alamat</th>
                                <th>No Telp</th>
                                <th>ID SPP</th>
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
                            <td><?= $d['nisn']; ?></td>
                            <td><?= $d['nis']; ?></td>
                            <td><?= $d['nama']; ?></td>
                            <td><?= $d['nama_kelas']; ?></td>
                            <td><?= $d['alamat']; ?></td>
                            <td><?= $d['no_telp']; ?></td>
                            <td><?= $d['id_spp']; ?></td>

                            <td>

                                <button
                                    type="button"
                                    class="btn btn-warning btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#edit<?= $d['nisn']; ?>"
                                >
                                    Ubah
                                </button>

                                <a
                                    href="?hapus=<?= $d['nisn']; ?>"
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
    id="edit<?= $d['nisn']; ?>"
    tabindex="-1"
>

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Ubah Data Siswa
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
                        name="nisn_lama"
                        value="<?= $d['nisn']; ?>"
                    >

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label">NISN</label>

                            <input
                                type="text"
                                name="nisn"
                                class="form-control"
                                value="<?= $d['nisn']; ?>"
                                required
                            >

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">NIS</label>

                            <input
                                type="text"
                                name="nis"
                                class="form-control"
                                value="<?= $d['nis']; ?>"
                            >

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">Nama</label>

                            <input
                                type="text"
                                name="nama"
                                class="form-control"
                                value="<?= $d['nama']; ?>"
                                required
                            >

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">Kelas</label>

                            <select
                                name="id_kelas"
                                class="form-control"
                                required
                            >

                                <?php
                                $kelas_edit = mysqli_query($conn, "
                                    SELECT * FROM tb_kelas
                                ");

                                while($k = mysqli_fetch_array($kelas_edit)) {
                                ?>

                                <option
                                    value="<?= $k['id_kelas']; ?>"
                                    <?= ($d['id_kelas'] == $k['id_kelas']) ? 'selected' : ''; ?>
                                >
                                    <?= $k['nama_kelas']; ?>
                                </option>

                                <?php } ?>

                            </select>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">No Telepon</label>

                            <input
                                type="text"
                                name="no_telp"
                                class="form-control"
                                value="<?= $d['no_telp']; ?>"
                            >

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">ID SPP</label>

                            <input
                                type="text"
                                name="id_spp"
                                class="form-control"
                                value="<?= $d['id_spp']; ?>"
                            >

                        </div>

                        <div class="col-md-12 mb-3">

                            <label class="form-label">Alamat</label>

                            <textarea
                                name="alamat"
                                class="form-control"
                            ><?= $d['alamat']; ?></textarea>

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

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header">
                <h5>Tambah Data Siswa</h5>
            </div>

            <form method="POST">

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <input
                                type="text"
                                name="nisn"
                                class="form-control"
                                placeholder="NISN"
                                required
                            >

                        </div>

                        <div class="col-md-6 mb-3">

                            <input
                                type="text"
                                name="nis"
                                class="form-control"
                                placeholder="NIS"
                            >

                        </div>

                        <div class="col-md-6 mb-3">

                            <input
                                type="text"
                                name="nama"
                                class="form-control"
                                placeholder="Nama Siswa"
                                required
                            >

                        </div>

                        <div class="col-md-6 mb-3">

                            <select
                                name="id_kelas"
                                class="form-control"
                                required
                            >

                                <option value="">-- Pilih Kelas --</option>

                                <?php
                                $kelas = mysqli_query($conn, "
                                    SELECT * FROM tb_kelas
                                ");

                                while($k = mysqli_fetch_array($kelas)) {
                                ?>

                                <option value="<?= $k['id_kelas']; ?>">
                                    <?= $k['nama_kelas']; ?>
                                </option>

                                <?php } ?>

                            </select>

                        </div>

                        <div class="col-md-6 mb-3">

                            <input
                                type="text"
                                name="no_telp"
                                class="form-control"
                                placeholder="No Telepon"
                            >

                        </div>

                        <div class="col-md-12 mb-3">

                            <textarea
                                name="alamat"
                                class="form-control"
                                placeholder="Alamat"
                            ></textarea>

                        </div>

                        <div class="col-md-12">

                            <input
                                type="text"
                                name="id_spp"
                                class="form-control"
                                placeholder="ID SPP"
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