<?php
require_once "../../../Controller/database.php";

class Penjualan{
    private $table="penjualan";
    private $db;

    function __construct(){
        $this->db = new Database();
    }

    function readData($condition = null){ 
        return $this->db->select($this->table, $condition);
    } 


    function createData($rows){
        $this->db->insert($this->table, $rows);
    }

    function updateData($data,$where){
        $this->db->update($this->table,$data, $where);
    }

    function deleteData($where){
        $this->db->delete($this->table, $where);
    }

    function kurangiStok($data){
        $jumlah = $data['jumlah'];
        $kode_barang = $data['kode_barang'];
        $this->db->updateStok($jumlah, $kode_barang);
    }

    function checkStock($data){
        $jumlah = $data['jumlah'];
        $kode_barang = $data['kode_barang'];
        return $this->db->checkStock($jumlah, $kode_barang);
    }
    

}
?>
