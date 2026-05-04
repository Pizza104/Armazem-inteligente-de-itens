<?php
require_once 'Conn.php';

class Cliente
{
    private $id;
    private $nome;
    private $email;

    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getNome() { return $this->nome; }
    public function setNome($nome) { $this->nome = $nome; }

    public function getEmail() { return $this->email; }
    public function setEmail($email) { $this->email = $email; }

    public function salvar()
    {
        $conn = new Conn();
        $pdo = $conn->conectar();

        $sql = "INSERT INTO cliente(nome, email) VALUES (:nome, :email)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':nome', $this->nome);
        $stmt->bindValue(':email', $this->email);

        return $stmt->execute();
    }

    public static function listarTodos()
    {
        $conn = new Conn();
        $pdo = $conn->conectar();
        $stmt = $pdo->prepare("SELECT id, nome, email FROM cliente ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>