<?php
include_once __DIR__.'/../layouts/session.php';
require_once '../../../Model/Penjualan.php'; 
$penjualan = new Penjualan();

$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : 0;
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : 0;
$tanggal = isset($_GET['tanggal']) ? $_GET['tanggal'] : '';

$query = "JOIN barang ON penjualan.kode_barang = barang.kode_barang JOIN user ON penjualan.id_user = user.id";

if (!empty($tanggal)) {
    $tanggal = date('Y-m-d', strtotime($tanggal));
    $query .= " WHERE DATE(penjualan.tanggal_input) = '$tanggal'";
} else {
    if ($bulan != 0 && $tahun != 0) {
        $query .= " WHERE MONTH(penjualan.tanggal_input) = $bulan AND YEAR(penjualan.tanggal_input) = $tahun";
    } elseif ($bulan != 0) {
        $query .= " WHERE MONTH(penjualan.tanggal_input) = $bulan";
    } elseif ($tahun != 0) {
        $query .= " WHERE YEAR(penjualan.tanggal_input) = $tahun";
    }
}

$row = $penjualan->readData($query);

$output = '';

$output .= '<table class="table table-bordered table-striped" id="example1">
    <thead>
        <tr style="background:#DFF0D8;color:#333;">
            <th>No</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Jumlah</th>
            <th>Modal Barang</th>
            <th>Total Bayar</th>
            <th>Kasir</th>
            <th>Tanggal Transaksi</th>
        </tr>
    </thead>
    <tbody>';

$jumlahTerjual = 0;
$modalTerjual = 0;
$totalTerjual = 0;

foreach ($row as $key => $data) {
    $modal = $data['jumlah'] * $data['harga_beli'];
    $modalTerjual += $modal;
    $modal = 'Rp ' . number_format($modal, 0, ',', '.');
    $total = 'Rp ' . number_format($data['total'], 0, ',', '.');
    $tanggal_transaksi = date('d-m-Y H:i:s', strtotime($data['tanggal_input']));
    $jumlahTerjual += $data['jumlah'];
    $totalTerjual += $data['total'];

    $output .= "<tr>
        <td>".($key+1)."</td>
        <td>".$data['kode_barang']."</td>
        <td>".$data['nama_barang']."</td>
        <td>".$data['jumlah']."</td>
        <td>".$modal."</td>
        <td>".$total."</td>
        <td>".$data['nama']."</td>
        <td>".$tanggal_transaksi."</td>
    </tr>";
}

$modalTerjualFormatted = 'Rp ' . number_format($modalTerjual, 0, ',', '.');
$totalTerjualFormatted = 'Rp ' . number_format($totalTerjual, 0, ',', '.');
$keuntungan = $totalTerjual - $modalTerjual;
$keuntunganFormatted = 'Rp ' . number_format($keuntungan, 0, ',', '.');

$output .= "</tbody>
    <tfoot>
        <tr>
            <th colspan='3'>Total Penjualan</th>
            <th>$jumlahTerjual</th>
            <th>$modalTerjualFormatted</th>
            <th>$totalTerjualFormatted</th>
            <th style='background:#0bb365;color:#fff;'>Keuntungan</th>
            <th style='background:#0bb365;color:#fff;'>$keuntunganFormatted</th>
        </tr>
    </tfoot>
</table>";

echo $output;
?>
