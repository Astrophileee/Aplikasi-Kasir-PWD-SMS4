<?php 
include_once __DIR__.'/../layouts/session.php';
require_once '../../../Model/User.php'; 
$user = new User();

$old_nama = isset($_POST['nama']) ? $_POST['nama'] : '';

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
                'level' => $_POST['level'],
                'password' => $passwordHash
            ];
            $user->createData($data);
        header('Location: /kasir/view/admin/user/dataUser.php?success=true&message=Data Berhasil Ditambahkan.');

        exit;
    }
}

if(isset($_GET['action']) && $_GET['action'] == "hapus"){
    if(isset($_GET['id'])) {
        $id = $_GET['id'];
        $where = ['id' => $id];
        $user->deleteData($where);
        header('Location: dataUser.php?success=true&message=Data Berhasil Dihapus.');
    }
}



if(isset($_POST['edit'])){
    $userId = $_POST['id'];
    $newUsername = $_POST['username'];
    $userEdit = $user->readData("WHERE id = $userId")[0];
    $checkUsername = $user->checkUsername($_POST['username']);
    if(empty($newUsername)){
        $error_message = "Kolom Username harus diisi.";
        $error_kolom = "nama";
    }elseif($newUsername !== $userEdit["username"] && !empty($user->checkUsername($newUsername))) {
        $error_message = "Username Telah Ada.";
        $error_kolom = "nama";
    }elseif(empty($_POST['nama'])){
        $error_message = "Kolom Nama harus diisi.";
        $error_kolom = "nama";
    }else {
        if ($_SESSION['user']['id'] == $userId) {
            $_SESSION['user']['nama'] = $_POST['nama'];
        }
        $data = [
            'nama' => $_POST['nama'],
            'username' => $_POST['username'],
            'level' => $_POST['level']
        ];
        $where = ['id' => $_POST['id']];
        $user->updateData($data,$where);
        header('Location: dataUser.php?success=true&message=Data Berhasil diedit.');
    }
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
<?php include_once __DIR__.'/../layouts/header.php'; ?>
</head>

<body>
<?php include_once __DIR__.'/../layouts/sidebar.php'; ?>
        <div class="main p-3">
            <div class="text-center">
                <h1>
                    DATA USER
                </h1>
            </div>
            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#exampleModalInsert">
                <i class="fa fa-plus"></i> Insert Data</button>
            </button>
            <table class="table table-bordered table-striped" id="example1">
                    <thead>
                        <tr style="background:#DFF0D8;color:#333;">
                            <th>No.</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>level</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $row = $user->readData();
                            foreach ($row as $key => $data) {
                        ?>
                        <tr>
                            <td><?= $key+1 ?></td>
                            <td><?=ucwords(strtolower($data['nama']));?></td>
                            <td><?=$data['username']?></td>
                            <td><?= $data['level']; ?></td>
                            <td>
                            <button type="button" class="btn btn-warning btn-lg"  data-bs-toggle="modal" data-bs-target="#exampleModalEdit<?=$data['id'];?>">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button onclick="confirmDelete(<?= $data['id']; ?>)" class="btn btn-danger btn-delete btn-lg"><i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
        </div>
    </div>





<!-- Modal insert -->
<div class="modal fade" id="exampleModalInsert" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah User</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form" method="POST" action="" enctype="multipart/form-data">
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label for="nama">Nama</label>
                    <div>
                        <?php if(isset($error_kolom) && $error_kolom == "nama"): ?>
                            <div class="alert alert-danger"><?php echo $error_message; ?></div>
                        <?php endif; ?>
                        <input name="nama" value="<?php echo $old_nama; ?>" class="form-control" type="text" placeholder="Ketikan Nama...">
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label for="username">Username</label>
                    <div>
                        <?php if(isset($error_kolom) && $error_kolom == "username"): ?>
                            <div class="alert alert-danger"><?php echo $error_message; ?></div>
                        <?php endif; ?>
                        <input name="username" class="form-control" type="text" placeholder="Ketikan Username...">
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label for="password">Password</label>
                    <div>
                        <?php if(isset($error_kolom) && $error_kolom == "password"): ?>
                            <div class="alert alert-danger"><?php echo $error_message; ?></div>
                        <?php endif; ?>
                        <input name="password" class="form-control" type="password" placeholder="Ketikan Password...">
                    </div>
                </div>
                <div class="form-group mb-3">
                        <div class="form-floating">
                            <?php if(isset($error_kolom) && $error_kolom == "level"): ?>
                                <div class="alert alert-danger"><?php echo $error_message; ?></div>
                            <?php endif; ?>
                            <select class="form-select" name="level" id="floatingSelect">
                                <option value="0" >level 0</option>
                                <option value="1" >level 1</option>
                            </select>
                            <label for="edit-stok">Level User</label>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button class="movebtn btn btn-primary" type="Submit" name="submit">Submit <i class="fa fa-fw fa-paper-plane"></i></button>
            </div>
            </form>
        </div>
    </div>
</div>




    <?php
    $row = $user->readData();
    foreach ($row as $key => $data) {
?>
<!-- Modal Edit -->
<div class="modal fade" id="exampleModalEdit<?= $data['id'];?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit User</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="id" value="<?= $data['id'] ?>">

                    <div class="form-group mb-3">
                        <label for="edit-nama">Nama</label>
                        <div>
                            <?php if(isset($error_kolom) && $error_kolom == "nama"): ?>
                                <div class="alert alert-danger"><?php echo $error_message; ?></div>
                            <?php endif; ?>
                            <input name="nama" value="<?= $data['nama'] ?>" class="form-control" type="text" placeholder="Ketikan Nama Anda...">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit-merk">Username</label>
                        <div>
                            <?php if(isset($error_kolom) && $error_kolom == "username"): ?>
                                <div class="alert alert-danger"><?php echo $error_message; ?></div>
                            <?php endif; ?>
                            <input name="username" value="<?= $data['username'] ?>" class="form-control" type="text" placeholder="Ketikan Username...">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <div class="form-floating">
                            <?php if(isset($error_kolom) && $error_kolom == "level"): ?>
                                <div class="alert alert-danger"><?php echo $error_message; ?></div>
                            <?php endif; ?>
                            <select class="form-select" name="level" id="floatingSelect">
                                <option value="0" <?php if ($data['level'] == 0) echo 'selected'; ?>>level 0</option>
                                <option value="1" <?php if ($data['level'] == 1) echo 'selected'; ?>>level 1</option>
                            </select>
                            <label for="edit-stok">Level User</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button class="movebtn btn btn-primary" type="Submit" name="edit">Submit <i class="fa fa-fw fa-paper-plane"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php } ?>










<?php include_once __DIR__.'/../layouts/footer.php'; ?>
<script>
    $(function () {
        $("#example1").DataTable();
    });
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
    document.addEventListener('DOMContentLoaded', function() {
        <?php if(isset($error_message)): ?>
            var Modal = new bootstrap.Modal(document.getElementById('exampleModalInsert'), {});
            Modal.show();
        <?php endif; ?>
    });

    function confirmDelete(id) {
			Swal.fire({
				title: 'Apakah Anda yakin?',
				text: 'Anda tidak akan dapat mengembalikan tindakan ini!',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Ya, hapus!',
				cancelButtonText: 'Batal',
			}).then((result) => {
				if (result.isConfirmed) {
					window.location.href = '?action=hapus&id=' + id
				}
			})
		}
</script>

</body>

</html>