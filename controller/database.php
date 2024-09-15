<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'kasir');

class Database{

    private $mysqli;

    function __construct()
    {
        $this->mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
    }

    function select($table, $condition = null){
        $sql = "SELECT * FROM $table " . " $condition";
        $result = $this->mysqli->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    function insert($table, $rows){
        $sql = ("INSERT INTO $table");
        $row = null;
        $value = null;

        foreach($rows as $key => $nilai){
            $row .= ",".$key;
            $value .= ", '".$nilai."'";
        }
        $sql .= "(". substr($row, 1) .")";
        $sql .= " VALUES (". substr($value,1) . ")";
        $query = $this->mysqli->prepare($sql) or die ($this->mysqli->error);
        $query->execute();
    }

    function update($table, $data, $where){
        $sql = ("UPDATE $table SET ");
        $set = null;
        $setWhere = null;

        foreach($data as $key => $value){
            $set .= ", ". $key . " = '". $value ."'";
        }
        foreach($where as $key => $value){
            $setWhere = $key."='".$value."'";
        }
        $sql .= substr($set, 1). " WHERE $setWhere";
        $query = $this->mysqli->prepare($sql) or die ($this->mysqli->error);
        $query->execute();
    }
    function delete($table, $where){
        $setWhere = null;
        foreach ($where as $key => $value){
            $setWhere = $key."='".$value."'";
        }

        $sql = "DELETE FROM $table WHERE $setWhere";
        $query = $this->mysqli->prepare($sql) or die ($this->mysqli->error);
        $query->execute();
    }

    function checkStock($jumlah, $kode_barang){
        $sql = "SELECT stok FROM barang WHERE kode_barang = ?";
        $query = $this->mysqli->prepare($sql);
        $query->bind_param("s", $kode_barang);
        $query->execute();
        $result = $query->get_result();
        $row = $result->fetch_assoc();
        $currentStock = $row['stok'];
    
        if ($currentStock >= $jumlah) {
            return true;
        } else {
            return false;
        }
    }

    function updateStok($jumlah, $kode_barang){
        $sql = "UPDATE barang SET stok = stok - ? WHERE kode_barang = ?";
        $query = $this->mysqli->prepare($sql);
        $query->bind_param("is", $jumlah, $kode_barang);
        $query->execute();
    }

    function checkUser($table, $username, $password) {
        $sql = $this->mysqli->prepare("SELECT * FROM $table WHERE `username` = ?");
        $sql->bind_param('s', $username);
        $sql->execute();
        $result = $sql->get_result();
        $user = $result->fetch_assoc();
        var_dump($user);
        if ($user) {
            if (password_verify($password, $user['password'])) {
                return $user;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    


}