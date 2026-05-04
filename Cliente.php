<<<<<<< HEAD
<?php
require_once 'Conn.php';

class Cliente
{
    private $id;
    private $nome;
    private $email;
    private $telefone;   // novo campo

    public function getId()      { return $this->id; }
    public function setId($id)   { $this->id = $id; }

    public function getNome()    { return $this->nome; }
    public function setNome($nome) { $this->nome = $nome; }

    public function getEmail()   { return $this->email; }
    public function setEmail($email) { $this->email = $email; }

    public function getTelefone()    { return $this->telefone; }
    public function setTelefone($tel) { $this->telefone = $tel; }

    public function salvar()
    {
        $conn = new Conn();
        $pdo = $conn->conectar();

        // Chama a procedure que você criou
        $sql = "CALL sp_inserir_cliente(:nome, :email, :telefone)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':nome', $this->nome);
        $stmt->bindValue(':email', $this->email);
        $stmt->bindValue(':telefone', $this->telefone);

        return $stmt->execute();
    }

    // Método estático para listar (usando a procedure de listagem)
    public static function listarTodos()
    {
        $conn = new Conn();
        $pdo = $conn->conectar();
        $stmt = $pdo->prepare("CALL sp_listar_clientes()");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
=======
<?php
require_once 'Conn.php';

class Cliente{

    private $id;
    private $nome;
    private $email;

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

    public function getEmail(){
        return $this->email;
    }

    public function setEmail($email){
        $this->email = $email;
    }

    public function salvar(){

        $conn = new Conn();
        $pdo = $conn->conectar();

        $sql = "INSERT INTO cliente(nome,email)
                VALUES(:nome,:email)";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':nome',$this->nome);
        $stmt->bindValue(':email',$this->email);

        return $stmt->execute();
    }
}
>>>>>>> 42b214f46ce1c771c8aa6e33228e9a926ad78adf
?>