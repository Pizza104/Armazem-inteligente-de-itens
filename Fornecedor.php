<?php
require_once 'Conn.php';

class Fornecedor{

    private $id;
    private $nome;
    private $cidade;

    public function getId(){
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function getNome(){
        return $this->nome;
    }

    public function setNome($nome){
        $this->nome = $nome;
    }

    public function getCidade(){
        return $this->cidade;
    }

    public function setCidade($cidade){
        $this->cidade = $cidade;
    }

    public function salvar(){

        $conn = new Conn();
        $pdo = $conn->conectar();

        $sql = "INSERT INTO fornecedor(nome,cidade)
                VALUES(:nome,:cidade)";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':nome',$this->nome);
        $stmt->bindValue(':cidade',$this->cidade);

        return $stmt->execute();
    }
}
?>