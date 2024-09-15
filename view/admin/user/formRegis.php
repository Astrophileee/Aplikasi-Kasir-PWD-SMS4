<?php
require_once '../../../Model/User.php'; 
$user = new User();


if(isset($_POST['submit'])){
    $checkUsername = $user->checkUsername($_POST['username']);
    if(empty($_POST['username'])){
        $error_message = "Kolom Username harus diisi.";
        $error_kolom = "username";
    }elseif(!empty($checkUsername)) {
        $error_message = "Username Telah Ada.";
        $error_kolom = "username";
    }elseif(empty($_POST['nama'])){
        $error_message = "Kolom Nama harus diisi.";
        $error_kolom = "nama";
    }
    elseif(empty($_POST['password'])){
        $error_message = "Kolom Password harus diisi.";
        $error_kolom = "password";
    }else {
            $password = $_POST['password'];
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $passwordHash = trim($passwordHash);
            $data = [
                'nama' => $_POST['nama'],
                'username' => $_POST['username'],
                'password' => $passwordHash
            ];
            $user->createData($data);
        header('Location: /kasir/view/admin/user/Formlogin.php?success=true&message=Anda Berhasil Registrasi.');

        exit;
    }
}



?>


<!DOCTYPE html>
<html lang="en">
    <head>
    <?php require_once __DIR__.'/../layouts/header.php'; ?>
    </head>
    <body style="background:#004643;color:#fff;">
        <div class="container" style="display: flex; justify-content: center; text-align: center; margin-top: 250px;">
            <div class="card col-sm-4 mb-5 card-cari">
                <form class="form-login" method="POST">
                    <div class="card-header " style="background-color: #DFF0D8;">
                        <h2 class="form-login-heading">Registrasi</h2>
                    </div>
                    <div class="card-body">
                        <?php if(isset($error_kolom) && $error_kolom == "nama"): ?>
                            <div class="alert alert-danger"><?php echo $error_message; ?></div>
                        <?php endif; ?>
                        <input type="text" class="form-control" name="nama" placeholder="Name" autofocus>
                        <br>
                        <?php if(isset($error_kolom) && $error_kolom == "username"): ?>
                            <div class="alert alert-danger"><?php echo $error_message; ?></div>
                        <?php endif; ?>
                        <input type="text" class="form-control" name="username" placeholder="Username">
                        <br>
                        <?php if(isset($error_kolom) && $error_kolom == "password"): ?>
                            <div class="alert alert-danger"><?php echo $error_message; ?></div>
                        <?php endif; ?>
                        <input type="password" class="form-control" name="password" placeholder="Password">
                        <br>
                        <a href="formLogin.php" type="button" class="btn btn-success">Login?</a>
                        <button class="btn btn-primary btn-block" name="submit" type="submit"><i class="fa fa-lock"></i> SIGN UP</button>
                    </div>
                </form>	  	
            </div>
        </div>
        <?php require_once __DIR__.'/../layouts/footer.php'; ?>
    </body>
</html>

