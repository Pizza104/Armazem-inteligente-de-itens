<?php
class Conn{

    private $host = "localhost";
    private $db   = "empresa";
    private $user = "root";
    private $pass = "";

    public function conectar(){
        try{
            $conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db}",
                $this->user,
                $this->pass
            );

            $conn->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );

            return $conn;

        }catch(PDOException $e){
            die("Erro: ".$e->getMessage());
        }
    }
}
?>