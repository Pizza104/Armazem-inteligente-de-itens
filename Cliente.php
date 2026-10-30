<?php
declare(strict_types=1);

require_once __DIR__ . '/Conn.php';

class Cliente
{
    private ?int $id = null;
    private string $nome = '';
    private string $email = '';

    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): void { $this->id = $id; }
    public function getNome(): string { return $this->nome; }
    public function setNome(string $nome): void { $this->nome = trim($nome); }
    public function getEmail(): string { return $this->email; }
    public function setEmail(string $email): void { $this->email = trim($email); }

    public function salvar(): bool
    {
        $pdo = (new Conn())->conectar();
        $stmt = $pdo->prepare('INSERT INTO cliente (nome, email) VALUES (:nome, :email)');
        return $stmt->execute(['nome' => $this->nome, 'email' => $this->email]);
    }

    public static function listarTodos(): array
    {
        $pdo = (new Conn())->conectar();
        return $pdo->query('SELECT id, nome, email FROM cliente ORDER BY id DESC')->fetchAll();
    }
}
