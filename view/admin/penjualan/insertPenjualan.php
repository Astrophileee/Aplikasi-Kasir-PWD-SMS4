<?php
include_once __DIR__.'/../layouts/session.php';
require_once "../../../Model/Penjualan.php";

$penjualan = new Penjualan();

$kode_barang = $_POST['kode_barang'];
$jumlah = $_POST['jumlah'];
$total = $_POST['total'];
$total_bayar = $_POST['total_bayar'];
$bayar = $_POST['bayar'];
$id_user = $_SESSION['user']['id'];



for ($i = 0; $i < count($kode_barang); $i++) {
    $data = array(
        'kode_barang' => $kode_barang[$i],
        'jumlah' => $jumlah[$i],
        'total' => $total[$i],
        'id_user' => $id_user
    );

    if (!$penjualan->checkStock($data)) {
        http_response_code(400);
        echo "Stok tidak mencukupi.";
        exit;
    }

    $penjualan->createData($data);
    $penjualan->kurangiStok($data);
}

?>
