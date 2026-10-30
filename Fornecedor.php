<?php
declare(strict_types=1);

require_once __DIR__ . '/Conn.php';

class Fornecedor
{
    private ?int $id = null;
    private string $nome = '';
    private string $cidade = '';

    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): void { $this->id = $id; }
    public function getNome(): string { return $this->nome; }
    public function setNome(string $nome): void { $this->nome = trim($nome); }
    public function getCidade(): string { return $this->cidade; }
    public function setCidade(string $cidade): void { $this->cidade = trim($cidade); }

    public function salvar(): bool
    {
        $pdo = (new Conn())->conectar();
        $stmt = $pdo->prepare('INSERT INTO fornecedor (nome, cidade) VALUES (:nome, :cidade)');
        return $stmt->execute(['nome' => $this->nome, 'cidade' => $this->cidade]);
    }

    public static function listarTodos(): array
    {
        $pdo = (new Conn())->conectar();
        return $pdo->query('SELECT id, nome, cidade FROM fornecedor ORDER BY id DESC')->fetchAll();
    }
}
