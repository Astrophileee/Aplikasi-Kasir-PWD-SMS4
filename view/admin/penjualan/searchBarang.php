<?php
include_once __DIR__.'/../layouts/session.php';
require_once "../../../Model/barang.php";

if(isset($_POST['searchTerm'])) {
    $searchTerm = $_POST['searchTerm'];
    $barangModel = new Barang();
    $results = $barangModel->readData("WHERE nama_barang LIKE '%$searchTerm%' OR kode_barang LIKE '%$searchTerm%'");
    echo json_encode($results);
} else {
    echo json_encode(array('error' => 'Search term is not provided'));
}
?>
