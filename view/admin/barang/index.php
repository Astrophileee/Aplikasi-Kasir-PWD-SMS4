<?php
include_once __DIR__.'/../layouts/session.php';
require_once '../../../Model/Barang.php'; 
$barang = new Barang();

$lastKode = $barang->GetLastKodeBarang();
if ($lastKode) {
    $kodeAngka = (int)substr($lastKode, 1) + 1;
    $newKode = 'B' . str_pad($kodeAngka, 3, '0', STR_PAD_LEFT);
} else {
    $newKode = 'B001';
}


$old_kode = $newKode;
$old_nama = isset($_POST['nama']) ? $_POST['nama'] : '';
$old_merk = isset($_POST['merk']) ? $_POST['merk'] : '';
$old_beli = isset($_POST['beli']) ? $_POST['beli'] : '';
$old_jual = isset($_POST['jual']) ? $_POST['jual'] : '';
$old_stok = isset($_POST['stok']) ? $_POST['stok'] : '';
$old_satuan = isset($_POST['satuan']) ? $_POST['satuan'] : '';

if(isset($_POST['submit'])){
    if(empty($_POST['kode'])){
        $error_message = "Kolom Kode Barang harus diisi.";
        $error_kolom = "kode";
    }elseif(empty($_POST['nama'])){
        $error_message = "Kolom Nama Barang harus diisi.";
        $error_kolom = "nama";
    }elseif(empty($_POST['merk'])){
        $error_message = "Kolom Merk Barang harus diisi.";
        $error_kolom = "merk";
    }elseif(empty($_POST['beli']) || $_POST['beli'] == 0){
        $error_message = "Kolom Harga Beli Barang harus diisi.";
        $error_kolom = "beli";
    }elseif(empty($_POST['jual']) || $_POST['jual'] == 0){
        $error_message = "Kolom Harga Jual Barang harus diisi.";
        $error_kolom = "jual";
    }elseif(empty($_POST['stok']) || $_POST['stok'] == 0 ){
        $error_message = "Kolom Stok harus diisi.";
        $error_kolom = "stok";
    }elseif($_POST['satuan'] == 0){
        $error_message = "Kolom Satuan Barang harus diisi.";
        $error_kolom = "satuan";
    }else {
        $data = [
            'kode_barang' => $_POST['kode'],
            'nama_barang' => $_POST['nama'],
            'merk' => $_POST['merk'],
            'stok' => $_POST['stok'],
            'harga_beli' => $_POST['beli'],
            'harga_jual' => $_POST['jual'],
            'satuan_barang' => $_POST['satuan']
        ];
        $barang->createData($data);
        header('Location: index.php?success=true&message=Data Berhasil Ditambah.');
    }
}

if(isset($_GET['action']) && $_GET['action'] == "hapus"){
    if(isset($_GET['id'])) {
        $id = $_GET['id'];
        $where = ['id' => $id];
        $barang->deleteData($where);
        header('Location: index.php?success=true&message=Data Berhasil Dihapus.');
    }
}

if(isset($_POST['edit'])){
    if(empty($_POST['kode'])){
        $error_message = "Kolom Kode Barang harus diisi.";
        $error_kolom = "kode";
    }elseif(empty($_POST['nama'])){
        $error_message = "Kolom Nama Barang harus diisi.";
        $error_kolom = "nama";
    }elseif(empty($_POST['merk'])){
        $error_message = "Kolom Merk Barang harus diisi.";
        $error_kolom = "merk";
    }elseif(empty($_POST['beli']) || $_POST['beli'] == 0){
        $error_message = "Kolom Harga Beli Barang harus diisi.";
        $error_kolom = "beli";
    }elseif(empty($_POST['jual']) || $_POST['jual'] == 0){
        $error_message = "Kolom Harga Jual Barang harus diisi.";
        $error_kolom = "jual";
    }elseif(empty($_POST['stok']) || $_POST['stok'] == 0 ){
        $error_message = "Kolom Stok harus diisi.";
        $error_kolom = "stok";
    }elseif($_POST['satuan'] == 0){
        $error_message = "Kolom Satuan Barang harus diisi.";
        $error_kolom = "satuan";
    }else {
        $data = [
            'nama_barang' => $_POST['nama'],
            'merk' => $_POST['merk'],
            'stok' => $_POST['stok'],
            'harga_beli' => $_POST['beli'],
            'harga_jual' => $_POST['jual'],
            'satuan_barang' => $_POST['satuan']
        ];
        $where = ['kode_barang' => $_POST['kode']];
        $barang->updateData($data,$where);
        header('Location: index.php?success=true&message=Data Berhasil Diedit.');
    }
}





?>




<!DOCTYPE html>
<html lang="en">

<head>
<?php require_once __DIR__.'/../layouts/header.php'; ?>
</head>

<body>
<?php require_once __DIR__.'/../layouts/sidebar.php'; ?>
        <div class="main p-3">
            <div class="text-center">
                <h1>
                    DATA BARANG
                </h1>
            </div>
            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#exampleModalInsert">
                <i class="fa fa-plus"></i> Insert Data</button>
            </button>	
            <div class="modal-view">
                <table class="table table-bordered table-striped" id="example1">
                    <thead>
                        <tr style="background:#DFF0D8;color:#333;">
                            <th>No.</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Merk</th>
                            <th>Stok</th>
                            <th>Harga Beli</th>
                            <th>Harga Jual</th>
                            <th>Satuan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $row = $barang->readData();
                            $totalBeli = 0;
                            $totalJual = 0;
                            $totalStok = 0;
                            foreach ($row as $key => $data) {

                                $totalBeli += $data['harga_beli'] * $data['stok']; 
                                $totalJual += $data['harga_jual'] * $data['stok'];
                                $totalStok += $data['stok'];
                        ?>
                        <tr>
                            <td><?= $key+1 ?></td>
                            <td><?=$data['kode_barang']?></td>
                            <td><?=ucwords(strtolower($data['nama_barang']));?></td>
                            <td><?=ucwords(strtolower($data['merk']));?></td>
                            <td><?=$data['stok']?></td>
                            <td><?=$data['harga_beli']?></td>
                            <td><?=$data['harga_jual']?></td>
                            <td><?=$data['satuan_barang'];?></td>
                            <td>
                            <button type="button" class="btn btn-warning btn-lg"  data-bs-toggle="modal" data-bs-target="#exampleModalEdit<?=$data['id'];?>">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button onclick="confirmDelete(<?= $data['id']; ?>)" class="btn btn-danger btn-delete btn-lg"><i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4">Total </td>
                            <th><?php echo $totalStok;?></td>
                            <th>Rp.<?php echo number_format($totalBeli);?>,-</td>
                            <th>Rp.<?php echo number_format($totalJual);?>,-</td>
                            <th colspan="2" style="background:#ddd"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="clearfix" style="margin-top:7pc;"></div>
        </div>
    </div>









<!-- Modal insert -->
<div class="modal fade" id="exampleModalInsert" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Barang</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form" method="POST" action="" enctype="multipart/form-data">
            <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="kode">Kode Barang</label>
                        <div>
                            <?php if(isset($error_kolom) && $error_kolom == "kode"): ?>
                                <div class="alert alert-danger"><?php echo $error_message; ?></div>
                            <?php endif; ?>
                            <input name="kode" value="<?php echo $old_kode; ?>" class="form-control" id="kode" type="text" placeholder="Ketikan Kode Barang..." readonly>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="nama">Nama Barang</label>
                        <div>
                            <?php if(isset($error_kolom) && $error_kolom == "nama"): ?>
                                <div class="alert alert-danger"><?php echo $error_message; ?></div>
                            <?php endif; ?>
                            <input name="nama" value="<?php echo $old_nama; ?>" class="form-control" id="nama" type="text" placeholder="Ketikan Nama Barang...">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="merk">Merk Barang</label>
                        <div>
                            <?php if(isset($error_kolom) && $error_kolom == "merk"): ?>
                                <div class="alert alert-danger"><?php echo $error_message; ?></div>
                            <?php endif; ?>
                            <input name="merk" value="<?php echo $old_merk; ?>" class="form-control" id="merk" type="text" placeholder="Ketikan Merk Barang...">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="beli">Harga Beli Barang</label>
                        <div>
                            <?php if(isset($error_kolom) && $error_kolom == "beli"): ?>
                                <div class="alert alert-danger"><?php echo $error_message; ?></div>
                            <?php endif; ?>
                            <input name="beli" value="<?php echo $old_beli; ?>" class="form-control" id="beli" type="number" placeholder="Ketikan Harga Beli Barang...">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="jual">Jual Barang</label>
                        <div>
                            <?php if(isset($error_kolom) && $error_kolom == "jual"): ?>
                                <div class="alert alert-danger"><?php echo $error_message; ?></div>
                            <?php endif; ?>
                            <input name="jual" value="<?php echo $old_jual; ?>" class="form-control" id="jual" type="number" placeholder="Ketikan Jual Barang...">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="stok">Stok Barang</label>
                        <div>
                            <?php if(isset($error_kolom) && $error_kolom == "stok"): ?>
                                <div class="alert alert-danger"><?php echo $error_message; ?></div>
                            <?php endif; ?>
                            <input name="stok" value="<?php echo $old_stok; ?>" class="form-control" id="stok" type="number" placeholder="Ketikan Stok Barang...">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <?php if(isset($error_kolom) && $error_kolom == "satuan"): ?>
                            <div class="alert alert-danger"><?php echo $error_message; ?></div>
                        <?php endif; ?>
                        <div class="form-floating">
                            <select class="form-select" name="satuan" id="satuan" id="floatingSelect">
                                <option value="0" selected>Buka Satuan Menu</option>
                                <option value="PCS">PCS</option>
                            </select>
                            <label for="stok">Satuan barang</label>
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
    $row = $barang->readData();
    foreach ($row as $key => $data) {
?>
<!-- Modal Edit -->
<div class="modal fade" id="exampleModalEdit<?= $data['id'];?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Barang</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit-id">
                    <div class="form-group mb-3">
                        <label for="edit-kode">Kode Barang</label>
                        <div>
                            <input name="kode" value="<?= $data['kode_barang'] ?>" class="form-control" id="edit-kode" type="text" placeholder="Ketikan Kode Barang..." readonly>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit-nama">Nama Barang</label>
                        <div>
                            <?php if(isset($error_kolom) && $error_kolom == "nama"): ?>
                                <div class="alert alert-danger"><?php echo $error_message; ?></div>
                            <?php endif; ?>
                            <input name="nama" value="<?= $data['nama_barang'] ?>" class="form-control" id="edit-nama" type="text" placeholder="Ketikan Nama Barang...">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit-merk">Merk Barang</label>
                        <div>
                            <?php if(isset($error_kolom) && $error_kolom == "merk"): ?>
                                <div class="alert alert-danger"><?php echo $error_message; ?></div>
                            <?php endif; ?>
                            <input name="merk" value="<?= $data['merk'] ?>" class="form-control" id="edit-merk" type="text" placeholder="Ketikan Merk Barang...">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit-beli">Harga Beli Barang</label>
                        <div>
                            <?php if(isset($error_kolom) && $error_kolom == "beli"): ?>
                                <div class="alert alert-danger"><?php echo $error_message; ?></div>
                            <?php endif; ?>
                            <input name="beli" value="<?= $data['harga_beli'] ?>" class="form-control" id="edit-beli" type="number" placeholder="Ketikan Harga Beli Barang...">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit-jual">Jual Barang</label>
                        <div>
                            <?php if(isset($error_kolom) && $error_kolom == "jual"): ?>
                                <div class="alert alert-danger"><?php echo $error_message; ?></div>
                            <?php endif; ?>
                            <input name="jual" value="<?= $data['harga_jual'] ?>" class="form-control" id="edit-jual" type="number" placeholder="Ketikan Jual Barang...">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit-stok">Stok Barang</label>
                        <div>
                            <?php if(isset($error_kolom) && $error_kolom == "stok"): ?>
                                <div class="alert alert-danger"><?php echo $error_message; ?></div>
                            <?php endif; ?>
                            <input name="stok" value="<?= $data['stok'] ?>" class="form-control" id="edit-stok" type="number" placeholder="Ketikan Stok Barang...">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <div class="form-floating">
                            <?php if(isset($error_kolom) && $error_kolom == "satuan"): ?>
                                <div class="alert alert-danger"><?php echo $error_message; ?></div>
                            <?php endif; ?>
                            <select class="form-select" name="satuan" id="edit-satuan" id="floatingSelect">
                                <option value="0" selected>Buka Satuan Menu</option>
                                <option value="PCS" <?php if ($data['satuan_barang'] == "PCS") echo 'selected'; ?>>PCS</option>
                            </select>
                            <label for="edit-stok">Satuan barang</label>
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







    
    <?php require_once __DIR__.'/../layouts/footer.php'; ?>
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
		})
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
        document.addEventListener('DOMContentLoaded', function() {
            <?php if(isset($error_message)): ?>
                var Modal = new bootstrap.Modal(document.getElementById('exampleModalInsert'), {});
                Modal.show();
            <?php endif; ?>
        });
    </script>
</body>

</html>