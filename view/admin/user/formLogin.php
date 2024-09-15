<?php
session_start();
if (isset($_SESSION['user'])) {
    header('Location: /Kasir/view/dashboard.php?success=true&message=Anda Sudah Login.');
    exit;
}
require_once '../../../Model/User.php'; 
$user = new User();


if(isset($_POST['submit'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $loginResult = $user->checkLogin($username, $password);
    if ($loginResult === true) {
      header('Location: /Kasir/view/dashboard.php?success=true&message=Anda Berhasil Login.');
        exit;
    } else {
        header('Location: /Kasir/view/admin/user/formLogin.php?success=false&message='.$loginResult);
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
                <div class="card-header " style="background-color: #DFF0D8;">
                    <h2 class="form-login-heading">Aplikasi POS</h2>
                </div>
                <div class="card-body">
                    <form class="form-login" method="POST">
                        <input type="text" class="form-control" name="username" placeholder="Username" autofocus>
                        <br>
                        <input type="password" class="form-control" name="password" placeholder="Password">
                        <br>
                        <button class="btn btn-primary btn-block" name="submit" type="submit"><i class="fa fa-lock"></i> SIGN IN</button>
                        <a href="formRegis.php" type="button" class="btn btn-success">Register?</a>
                    </form>	  	
                </div>
            </div>
        </div>
        <?php require_once __DIR__.'/../layouts/footer.php'; ?>
        <script>
            window.addEventListener('load', function () {
                let urlParams = new URLSearchParams(window.location.search)
                let success = urlParams.get('success')
                let message = urlParams.get('message')

                if (success && message) {
                    if (success === 'true') {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: message,
                            showConfirmButton: false,
                            timer: 1500,
                        })
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: message,
                            icon: 'error',
                        })
                    }
                    window.history.replaceState(
                        {},
                        document.title,
                        window.location.pathname
                    )
                }
            });
        </script>
    </body>
</html>

