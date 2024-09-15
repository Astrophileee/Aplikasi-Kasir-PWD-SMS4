<?php
session_start();
$userAktif = $_SESSION['user'];
if (!isset($userAktif)) {
    header('Location: /kasir/view/admin/user/Formlogin.php?success=false&message=Anda Belum Login .');
    exit;
}

$sesi_id = session_id();
setcookie('sesi_cookie', $sesi_id, time() + (86400 * 7), "/");

?>