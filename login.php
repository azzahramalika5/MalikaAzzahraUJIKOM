<?php
session_start();
include 'config/koneksi.php';

if (isset($_POST['login'])) {

    $user = trim($_POST['username']);
    $pass = trim($_POST['password']);

    // CEK USERNAME
    $query = mysqli_prepare($conn, "
        SELECT * FROM tb_petugas 
        WHERE username=?
    ");

    mysqli_stmt_bind_param($query, "s", $user);
    mysqli_stmt_execute($query);

    $result = mysqli_stmt_get_result($query);
    $data = mysqli_fetch_assoc($result);

    // CEK LOGIN
    if ($data && md5($pass) == $data['password']) {

        $_SESSION['login'] = true;
        $_SESSION['id_petugas'] = $data['id_petugas'];
        $_SESSION['username'] = $data['username'];
        $_SESSION['nama_petugas'] = $data['nama_petugas'];
        $_SESSION['level'] = $data['level'];

        header("Location: index.php");
        exit;

    } else {

        $error = "Username atau password salah!";

    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login SPP</title>

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
            overflow:hidden;
        }

        body::before{
            content:'';
            position:absolute;
            width:250px;
            height:250px;
            background:rgba(255,255,255,0.2);
            border-radius:50%;
            top:-80px;
            left:-80px;
        }

        body::after{
            content:'';
            position:absolute;
            width:350px;
            height:350px;
            background:rgba(255,255,255,0.1);
            border-radius:50%;
            bottom:-120px;
            right:-120px;
        }

        .login-container{
            width:420px;
            background:white;
            padding:35px;
            border-radius:25px;
            box-shadow:0 15px 40px rgba(0,0,0,0.2);
            position:relative;
            z-index:1;
            animation:fadeIn 0.5s ease;
        }

        @keyframes fadeIn{
            from{
                opacity:0;
                transform:translateY(-20px);
            }
            to{
                opacity:1;
                transform:translateY(0);
            }
        }

        .logo{
            text-align:center;
            margin-bottom:25px;
        }

        .logo i{
            font-size:55px;
            color:#00aaff;
        }

        .logo h2{
            color:#0077cc;
            font-weight:700;
            margin-top:10px;
        }

        .logo p{
            color:#777;
            font-size:13px;
        }

        .input-group{
            margin-bottom:20px;
            position:relative;
        }

        .input-group i{
            position:absolute;
            left:12px;
            top:50%;
            transform:translateY(-50%);
            color:#888;
        }

        .input-group input{
            width:100%;
            padding:14px 14px 14px 40px;
            border-radius:10px;
            border:2px solid #d0e7ff;
            transition:0.3s;
            font-size:14px;
        }

        .input-group input:focus{
            border-color:#00aaff;
            box-shadow:0 0 0 3px rgba(0,170,255,0.15);
            outline:none;
        }

        .btn-login{
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

        .btn-login:hover{
            transform:translateY(-2px);
            box-shadow:0 8px 20px rgba(0,170,255,0.3);
        }

        .error-message{
            background:#ffecec;
            color:#ff4d4d;
            padding:10px;
            border-radius:10px;
            margin-bottom:15px;
            font-size:13px;
        }

        .register-link{
            text-align:center;
            margin-top:15px;
            font-size:13px;
        }

        .register-link a{
            color:#0077cc;
            text-decoration:none;
            font-weight:bold;
        }

        .register-link a:hover{
            text-decoration:underline;
        }

        .toggle-password{
            position:absolute;
            right:12px;
            top:50%;
            transform:translateY(-50%);
            cursor:pointer;
            color:#777;
        }

        @media(max-width:500px){

            .login-container{
                width:90%;
            }

        }

    </style>
</head>

<body>

<div class="login-container">

    <div class="logo">
        <i class="fas fa-school"></i>
        <h2>SPP SISWA</h2>
        <p>Sistem Pembayaran SPP Siswa</p>
    </div>

    <?php if(isset($error)) { ?>

        <div class="error-message">
            <?= $error ?>
        </div>

    <?php } ?>

    <form method="POST">

        <div class="input-group">
            <i class="fas fa-user"></i>

            <input
                type="text"
                name="username"
                placeholder="Username"
                required
                autofocus
            >
        </div>

        <div class="input-group">

            <i class="fas fa-lock"></i>

            <input
                type="password"
                name="password"
                id="password"
                placeholder="Password"
                required
            >

            <i class="fas fa-eye toggle-password" id="togglePassword"></i>

        </div>

        <button type="submit" name="login" class="btn-login">
            Login
        </button>

        <div class="register-link">
            Belum punya akun?
            <a href="register.php">Daftar sekarang</a>
        </div>

    </form>

</div>

<script>

const togglePassword =
    document.querySelector('#togglePassword');

const password =
    document.querySelector('#password');

togglePassword.addEventListener('click', function () {

    const type =
        password.type === 'password'
        ? 'text'
        : 'password';

    password.type = type;

    this.classList.toggle('fa-eye');
    this.classList.toggle('fa-eye-slash');

});

</script>

</body>
</html>