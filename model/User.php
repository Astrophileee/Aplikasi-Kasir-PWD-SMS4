<?php
require_once "../../../Controller/database.php";

class User{
    private $table="user";
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

    function checkUsername($keyword) {
        $condition = "WHERE username LIKE '$keyword' ";
        return $this->db->select($this->table, $condition);
    }

    function checkLogin($username, $password){
        $user = $this->db->checkUser($this->table, $username, $password);
        
        if($user){
            session_start();
            $_SESSION['user'] = $user;
            return true;
        } else {
            return "Username atau password salah.";
        }
    }   

    function logout() {
        session_start();
        session_unset();
        session_destroy();
    }

}
?>
