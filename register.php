<?php
session_start();
include 'config/koneksi.php';

$error = '';
$success = '';

if (isset($_POST['register'])) {

    $id_petugas   = trim($_POST['id_petugas']);
    $username     = trim($_POST['username']);
    $password     = trim($_POST['password']);
    $confirm      = trim($_POST['confirm_password']);
    $nama_petugas = trim($_POST['nama_petugas']);
    $level        = trim($_POST['level']);

    if (
        $id_petugas == '' ||
        $username == '' ||
        $password == '' ||
        $nama_petugas == '' ||
        $level == ''
    ) {

        $error = "Semua field wajib diisi!";

    } elseif ($password != $confirm) {

        $error = "Konfirmasi password tidak sesuai!";

    } else {

        // CEK USERNAME
        $cek = mysqli_prepare($conn, "
            SELECT id_petugas 
            FROM tb_petugas 
            WHERE username=?
        ");

        mysqli_stmt_bind_param($cek, "s", $username);
        mysqli_stmt_execute($cek);
        mysqli_stmt_store_result($cek);

        if (mysqli_stmt_num_rows($cek) > 0) {

            $error = "Username sudah digunakan!";

        } else {

            // HASH PASSWORD
            $hashed_password = md5($password);

            // INSERT DATA
            $stmt = mysqli_prepare($conn, "
                INSERT INTO tb_petugas 
                (id_petugas, username, password, nama_petugas, level)
                VALUES (?, ?, ?, ?, ?)
            ");

            mysqli_stmt_bind_param(
                $stmt,
                "sssss",
                $id_petugas,
                $username,
                $hashed_password,
                $nama_petugas,
                $level
            );

            if (mysqli_stmt_execute($stmt)) {

                $success = "Registrasi berhasil!";
                header("refresh:2;url=login.php");

            } else {

                $error = "Registrasi gagal!";

            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register SPP</title>

    <!-- FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- ICON -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family:'Quicksand', sans-serif;
            background:linear-gradient(135deg,#4facfe,#00c6ff);
            min-height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
        }

        .register-container{
            width:420px;
            background:white;
            padding:35px;
            border-radius:25px;
            box-shadow:0 15px 40px rgba(0,0,0,0.2);
        }

        .logo{
            text-align:center;
            margin-bottom:25px;
        }

        .logo h2{
            color:#0077cc;
            margin-top:10px;
        }

        .logo i{
            font-size:55px;
            color:#00aaff;
        }

        .input-group{
            margin-bottom:18px;
            position:relative;
        }

        .input-group i{
            position:absolute;
            left:12px;
            top:50%;
            transform:translateY(-50%);
            color:#888;
        }

        .input-group input,
        .input-group select{
            width:100%;
            padding:14px 14px 14px 40px;
            border-radius:10px;
            border:2px solid #d0e7ff;
            outline:none;
            transition:0.3s;
        }

        .input-group input:focus,
        .input-group select:focus{
            border-color:#00aaff;
        }

        .btn-register{
            width:100%;
            padding:14px;
            border:none;
            border-radius:12px;
            background:linear-gradient(135deg,#00aaff,#0077cc);
            color:white;
            font-weight:bold;
            cursor:pointer;
            transition:0.3s;
        }

        .btn-register:hover{
            transform:translateY(-2px);
            box-shadow:0 8px 20px rgba(0,170,255,0.3);
        }

        .error-message{
            background:#ffecec;
            color:#ff4d4d;
            padding:10px;
            border-radius:10px;
            margin-bottom:15px;
        }

        .success-message{
            background:#e8f5e9;
            color:#2ecc71;
            padding:10px;
            border-radius:10px;
            margin-bottom:15px;
        }

        .login-link{
            text-align:center;
            margin-top:15px;
            font-size:14px;
        }

        .login-link a{
            color:#0077cc;
            text-decoration:none;
            font-weight:bold;
        }

        .toggle-password{
            position:absolute;
            right:12px;
            top:50%;
            transform:translateY(-50%);
            cursor:pointer;
            color:#777;
        }

    </style>
</head>
<body>

<div class="register-container">

    <div class="logo">
        <i class="fas fa-user-plus"></i>
        <h2>Register Petugas</h2>
    </div>

    <?php if($error): ?>
        <div class="error-message">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <?php if($success): ?>
        <div class="success-message">
            <?= $success ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <div class="input-group">
            <i class="fas fa-id-card"></i>
            <input type="text" name="id_petugas" placeholder="ID Petugas" required>
        </div>

        <div class="input-group">
            <i class="fas fa-user"></i>
            <input type="text" name="username" placeholder="Username" required>
        </div>

        <div class="input-group">
            <i class="fas fa-user-tie"></i>
            <input type="text" name="nama_petugas" placeholder="Nama Petugas" required>
        </div>

        <div class="input-group">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" id="password" placeholder="Password" required>

            <i class="fas fa-eye toggle-password" id="togglePassword"></i>
        </div>

        <div class="input-group">
            <i class="fas fa-lock"></i>
            <input type="password" name="confirm_password" placeholder="Konfirmasi Password" required>
        </div>

        <div class="input-group">
            <i class="fas fa-user-shield"></i>

            <select name="level" required>
                <option value="">-- Pilih Level --</option>
                <option value="admin">Admin</option>
                <option value="petugas">Petugas</option>
                <option value="siswa">Siswa</option>
            </select>
        </div>

        <button type="submit" name="register" class="btn-register">
            Daftar
        </button>

        <div class="login-link">
            Sudah punya akun?
            <a href="login.php">Login</a>
        </div>

    </form>

</div>

<script>

const togglePassword = document.querySelector('#togglePassword');
const password = document.querySelector('#password');

togglePassword.addEventListener('click', function () {

    const type =
        password.type === 'password'
        ? 'text'
        : 'password';

    password.type = type;

    this.classList.toggle('fa-eye-slash');

});

</script>

</body>
</html>