<?php

require_once '../../../Model/User.php'; 

$user = new User();
$user->logout();

header('Location: /Kasir/view/admin/user/Formlogin.php?success=true&message=Anda Berhasil Logout.');
exit;
?>